<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\Student;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Subject::where('user_id', Auth::id());

        // Apply search filter if provided
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $subjects = $query->paginate(12)->withQueryString();
        return view('app.subjects.index', compact('subjects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('app.subjects.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:20',
            'banner_image' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();
        $data['user_id'] = Auth::id();

        // Handle banner image upload
        if ($request->hasFile('banner_image')) {
            $path = $request->file('banner_image')->store('subject-banners', 'public');
            $data['banner_image'] = $path;
        }

        Subject::create($data);

        return redirect()->route('subjects.index')
            ->with('success', 'Subject created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $subject = Subject::with(['activities' => function($query) {
            $query->orderBy('created_at', 'desc');
        }])->findOrFail($id);

        // Check if the user owns this subject
        if ($subject->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $students = $subject->students;

        // Get top performing students based on their grades
        $topStudents = collect();

        // Get all assignment-type activities for this subject
        $assignmentActivities = $subject->activities()->where('type', 'assignment')->pluck('id');

        if ($assignmentActivities->count() > 0) {
            // Get all students with their average grades for this subject's assignments
            $studentGrades = \App\Models\Submission::whereIn('activity_id', $assignmentActivities)
                ->whereNotNull('grade')
                ->select('student_id', \DB::raw('AVG(grade) as average_grade'), \DB::raw('COUNT(*) as submission_count'))
                ->groupBy('student_id')
                ->having('submission_count', '>', 0)
                ->orderByDesc('average_grade')
                ->limit(5)
                ->get();

            // Get the student details for these top performers
            if ($studentGrades->count() > 0) {
                $studentIds = $studentGrades->pluck('student_id');
                $topStudentsData = \App\Models\Student::whereIn('id', $studentIds)->get();

                // Merge the student data with their grades
                foreach ($studentGrades as $grade) {
                    $student = $topStudentsData->firstWhere('id', $grade->student_id);
                    if ($student) {
                        $student->average_grade = round($grade->average_grade, 1);
                        $student->submission_count = $grade->submission_count;
                        $topStudents->push($student);
                    }
                }

                // Sort by average grade (highest first)
                $topStudents = $topStudents->sortByDesc('average_grade')->values();
            }
        }

        return view('app.subjects.show', compact('subject', 'students', 'topStudents'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $subject = Subject::findOrFail($id);

        // Check if the user owns this subject
        if ($subject->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('app.subjects.edit', compact('subject'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $subject = Subject::findOrFail($id);

        // Check if the user owns this subject
        if ($subject->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:20',
            'banner_image' => 'nullable|image|max:2048',
        ]);

        $data = $request->except(['_token', '_method', 'banner_image']);

        // Handle banner image upload
        if ($request->hasFile('banner_image')) {
            $path = $request->file('banner_image')->store('subject-banners', 'public');
            $data['banner_image'] = $path;
        }

        $subject->update($data);

        return redirect()->route('subjects.show', $subject->id)
            ->with('success', 'Subject updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $subject = Subject::findOrFail($id);

        // Check if the user owns this subject
        if ($subject->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $subject->delete();

        return redirect()->route('subjects.index')
            ->with('success', 'Subject deleted successfully.');
    }

    /**
     * Add students to a subject.
     */
    public function addStudents(Request $request, string $id)
    {
        $subject = Subject::findOrFail($id);

        // Check if the user owns this subject
        if ($subject->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:students,id',
        ]);

        $subject->students()->attach($request->student_ids);

        return redirect()->route('subjects.show', $subject->id)
            ->with('success', 'Students added to subject successfully.');
    }

    /**
     * Remove a student from a subject.
     */
    public function removeStudent(Request $request, string $subjectId, string $studentId)
    {
        $subject = Subject::findOrFail($subjectId);

        // Check if the user owns this subject
        if ($subject->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $subject->students()->detach($studentId);

        return redirect()->route('subjects.show', $subject->id)
            ->with('success', 'Student removed from subject successfully.');
    }
}
