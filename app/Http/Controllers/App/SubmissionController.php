<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Submission;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SubmissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $submissions = Submission::with(['student', 'activity'])->latest()->paginate(10);
        return view('app.submissions.index', compact('submissions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'activity_id' => 'required|exists:activities,id',
            'file' => 'required|file|mimes:pdf,doc,docx|max:10240', // 10MB max
        ]);

        $activity = Activity::findOrFail($request->activity_id);
        $student = Auth::guard('student')->user();

        // Check if student is enrolled in the subject
        if (!$student->subjects()->where('subjects.id', $activity->subject_id)->exists()) {
            return back()->with('error', 'You are not enrolled in this subject.');
        }

        // Check if due date has passed
        if ($activity->due_date < now()) {
            return back()->with('error', 'The due date for this assignment has passed.');
        }

        // Check if already submitted
        if ($activity->submissions()->where('student_id', $student->id)->exists()) {
            return back()->with('error', 'You have already submitted this assignment.');
        }

        // Store the file
        $path = $request->file('file')->store('submissions');

        // Create submission
        $submission = new Submission([
            'student_id' => $student->id,
            'activity_id' => $activity->id,
            'file_path' => $path,
            'submitted_at' => now(),
            'status' => 'submitted',
        ]);

        $submission->save();

        return back()->with('success', 'Assignment submitted successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Submission $submission)
    {
        $this->authorize('view', $submission);
        $student = Auth::guard('student')->user();
        $isPremium = $student ? $student->subscription_type === 'premium' : false;
        
        return view('app.submissions.show', compact('submission', 'isPremium'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function grade(Request $request, Submission $submission)
    {
        $validator = Validator::make($request->all(), [
            'grade' => 'required|numeric|min:0|max:100',
            'feedback' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $submission->update([
            'grade' => $request->grade,
            'feedback' => $request->feedback,
            'status' => 'graded',
            'graded_at' => now(),
        ]);

        return back()->with('success', 'Submission graded successfully.');
    }

    public function download(Submission $submission)
    {
        $this->authorize('view', $submission);
        $student = Auth::guard('student')->user();
        
        // Check if student is premium
        if ($student && $student->subscription_type === 'premium') {
            return Storage::download($submission->file_path);
        }
        
        // For basic users, return a watermarked version
        return $this->getWatermarkedPdf($submission->file_path);
    }

    public function preview(Submission $submission)
    {
        $this->authorize('view', $submission);
        $student = Auth::guard('student')->user();
        
        // Check if student is premium
        if ($student && $student->subscription_type === 'premium') {
            return response()->file(Storage::path($submission->file_path));
        }
        
        // For basic users, return a watermarked preview
        return $this->getWatermarkedPdf($submission->file_path, true);
    }

    private function getWatermarkedPdf($filePath, $isPreview = false)
    {
        // Get the PDF content
        $pdfContent = Storage::get($filePath);
        
        // Create a temporary file
        $tempFile = tempnam(sys_get_temp_dir(), 'pdf_');
        file_put_contents($tempFile, $pdfContent);
        
        // Load the PDF
        $pdf = new \setasign\Fpdi\Fpdi();
        $pageCount = $pdf->setSourceFile($tempFile);
        
        // Create a new PDF
        $newPdf = new \setasign\Fpdi\Fpdi();
        
        // Add watermark to each page
        for ($i = 1; $i <= $pageCount; $i++) {
            $tplId = $pdf->importPage($i);
            $newPdf->AddPage();
            $newPdf->useTemplate($tplId);
            
            // Add watermark
            $newPdf->SetFont('Arial', 'B', 50);
            $newPdf->SetTextColor(200, 200, 200);
            $newPdf->Rotate(45, 105, 150);
            $newPdf->Text(105, 150, 'PREVIEW');
            
            // For preview, only show first 3 pages
            if ($isPreview && $i >= 3) {
                break;
            }
        }
        
        // Clean up
        unlink($tempFile);
        
        // Return the watermarked PDF
        return response($newPdf->Output('S'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => $isPreview ? 'inline' : 'attachment; filename="document.pdf"'
        ]);
    }
}
