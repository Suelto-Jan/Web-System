<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $activities = Activity::with('subject')->latest()->paginate(10);
        return view('app.activities.index', compact('activities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $subjects = Subject::all();
        return view('app.activities.create', compact('subjects'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'subject_id' => 'required|exists:subjects,id',
            'due_date' => 'required|date|after:today',
            'activity_document' => 'nullable|file|mimes:pdf,doc,docx|max:10240', // 10MB max
            'reviewer_attachment' => 'nullable|file|mimes:pdf|max:10240', // PDF only, 10MB max
            'google_docs_url' => 'nullable|url|max:255',
            'attachment' => 'nullable|file|max:20480', // Allow any file type, up to 20MB
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Debug output removed so the file upload process completes

        $data = $request->except(['activity_document', 'reviewer_attachment', 'attachment']);

        // Handle activity document upload
        if ($request->hasFile('activity_document')) {
            $data['activity_document_path'] = $request->file('activity_document')->store('activities/documents');
        }

        // Handle reviewer attachment upload
        if ($request->hasFile('reviewer_attachment')) {
            $data['reviewer_attachment_path'] = $request->file('reviewer_attachment')->store('activities/reviewer');
        }

        // Handle general attachment upload (any file type)
        \Log::info('Default disk', ['default_disk' => config('filesystems.default')]);
        \Log::info('Public disk root', ['public_root' => config('filesystems.disks.public.root')]);
        \Log::info('Storage path', ['storage_path' => storage_path()]);
        
        if ($request->hasFile('attachment')) {
            $tenantDb = app(\Illuminate\Database\Connection::class)->getDatabaseName();
            $relativePath = "$tenantDb/activities/attachments";
            $filename = $request->file('attachment')->getClientOriginalName();
            $fullPath = "$relativePath/$filename";
            $result = \Storage::disk('public')->putFileAs($relativePath, $request->file('attachment'), $filename);
            \Log::info('PutFileAs result', ['result' => $result]);
            $data['attachment'] = $fullPath;
            \Log::info('After forced putFileAs', ['saved_path' => $data['attachment']]);
        }

        $activity = Activity::create($data);

        return redirect()->route('activities.show', $activity)
            ->with('success', 'Activity created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Activity $activity)
    {
        $activity->load('subject', 'submissions');
        return view('app.activities.show', compact('activity'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Activity $activity)
    {
        $subjects = Subject::all();
        return view('app.activities.edit', compact('activity', 'subjects'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Activity $activity)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'subject_id' => 'required|exists:subjects,id',
            'due_date' => 'required|date|after:today',
            'activity_document' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'reviewer_attachment' => 'nullable|file|mimes:pdf|max:10240',
            'google_docs_url' => 'nullable|url|max:255',
            'attachment' => 'nullable|file|max:20480', // Allow any file type, up to 20MB
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $request->except(['activity_document', 'reviewer_attachment', 'attachment']);

        // Handle activity document upload
        if ($request->hasFile('activity_document')) {
            // Delete old file if exists
            if ($activity->activity_document_path) {
                Storage::delete($activity->activity_document_path);
            }
            $data['activity_document_path'] = $request->file('activity_document')->store('activities/documents');
        }

        // Handle reviewer attachment upload
        if ($request->hasFile('reviewer_attachment')) {
            // Delete old file if exists
            if ($activity->reviewer_attachment_path) {
                Storage::delete($activity->reviewer_attachment_path);
            }
            $data['reviewer_attachment_path'] = $request->file('reviewer_attachment')->store('activities/reviewer');
        }

        // Handle general attachment upload (any file type)
        \Log::info('Before upload', ['path' => storage_path('app/public')]);
        if ($request->hasFile('attachment')) {
            $tenantDb = app(\Illuminate\Database\Connection::class)->getDatabaseName();
            $relativePath = "$tenantDb/activities/attachments";
            $filename = $request->file('attachment')->getClientOriginalName();
            $fullPath = "$relativePath/$filename";
            // Delete old file if exists
            if ($activity->attachment) {
                $oldFile = storage_path("app/public/" . $activity->attachment);
                if (file_exists($oldFile)) {
                    unlink($oldFile);
                }
            }
            \Storage::disk('public')->putFileAs($relativePath, $request->file('attachment'), $filename);
            $data['attachment'] = $fullPath;
            \Log::info('After forced putFileAs', ['saved_path' => $data['attachment']]);
        }

        $activity->update($data);

        return redirect()->route('activities.show', $activity)
            ->with('success', 'Activity updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Activity $activity)
    {
        // Delete associated files
        if ($activity->activity_document_path) {
            Storage::delete($activity->activity_document_path);
        }
        if ($activity->reviewer_attachment_path) {
            Storage::delete($activity->reviewer_attachment_path);
        }

        $activity->delete();

        return redirect()->route('activities.index')
            ->with('success', 'Activity deleted successfully.');
    }

    public function publish(Activity $activity)
    {
        $activity->update(['is_published' => true]);
        return back()->with('success', 'Activity published successfully.');
    }

    public function unpublish(Activity $activity)
    {
        $activity->update(['is_published' => false]);
        return back()->with('success', 'Activity unpublished successfully.');
    }

    /**
     * Download an activity attachment from tenant-specific storage.
     */
    public function downloadAttachment(Request $request, $path)
    {
        // Allow access if authenticated as student or default user
        if (!auth('student')->check() && !auth()->check()) {
            return redirect()->route('login');
        }
        $disk = 'public'; // This is the tenant-specific disk due to Stancl tenancy
        if (!\Storage::disk($disk)->exists($path)) {
            abort(404);
        }
        // Optionally, add authorization logic here
        return \Storage::disk($disk)->download($path);
    }
}
