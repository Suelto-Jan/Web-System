<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\App\SubjectController;
use App\Http\Controllers\App\StudentController;
use App\Http\Controllers\App\ActivityController;
use App\Http\Controllers\App\SubmissionController;
use App\Http\Controllers\App\StudentSubscriptionController;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

// API routes for direct access
Route::middleware([
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->prefix('api')->group(function () {
    // Get all students
    Route::get('/students', function () {
        try {
            $students = \App\Models\Student::orderBy('name')->get();

            return response()->json([
                'success' => true,
                'students' => $students,
            ]);
        } catch (\Exception $e) {
            \Log::error('API error getting students', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to get students: ' . $e->getMessage(),
            ], 500);
        }
    });

    // Create a new student
    Route::post('/students', function (Illuminate\Http\Request $request) {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:students,email',
                'student_id' => 'nullable|string|max:50',
                'notes' => 'nullable|string',
                'plan' => 'required|string|in:basic,premium',
            ]);

            // Generate a random password
            $password = \Illuminate\Support\Str::random(8);
            $validated['password'] = \Illuminate\Support\Facades\Hash::make($password);

            // Create the student
            $student = \App\Models\Student::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Student created successfully',
                'student' => $student,
                'password' => $password,
            ]);
        } catch (\Exception $e) {
            \Log::error('API error creating student', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create student: ' . $e->getMessage(),
            ], 500);
        }
    });
});

// Web routes
Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
    Route::get('/', function (){
        return view('app.welcome');
    });

    Route::get('/dashboard', function () {
        return view('app.dashboard');
    })->middleware(['auth', 'verified'])->name('dashboard');

   
    Route::get('/student/dashboard', function () {
        $student = Auth::guard('student')->user();
        $subjects = $student ? $student->subjects()->with('activities')->get() : [];
        return view('app.student-dashboard', compact('subjects'));
    })->middleware(['auth:student'])->name('student.dashboard');

    // Student's My Subjects route
    Route::get('/my-subjects', function () {
        $student = Auth::guard('student')->user();
        $subjects = $student ? $student->subjects()->with('activities')->get() : [];
        return view('app.my-subjects', compact('subjects'));
    })->middleware(['auth:student'])->name('student.subjects');

    // Student's Assignments route
    Route::get('/my-assignments', function () {
        if (!Auth::guard('student')->check()) {
            return redirect()->route('student.login');
        }

        $student = Auth::guard('student')->user();
        $submissions = $student->submissions()->with('activity')->get();
        $activeActivities = $student->subjects()
            ->with(['activities' => function($query) use ($student) {
                $query->where('due_date', '>', now())
                    ->whereDoesntHave('submissions', function($q) use ($student) {
                        $q->where('student_id', $student->id);
                    });
            }])
            ->get()
            ->pluck('activities')
            ->flatten();
            
        return view('app.my-assignments', compact('submissions', 'activeActivities'));
    })->middleware(['auth:student'])->name('student.assignments');

    // Submission routes
    Route::post('/submissions', [App\Http\Controllers\SubmissionController::class, 'store'])
        ->middleware(['auth:student'])
        ->name('submissions.store');
    Route::get('/submissions/{submission}', [App\Http\Controllers\SubmissionController::class, 'show'])
        ->middleware(['auth:student'])
        ->name('submissions.show');

    // Student Profile routes
    Route::middleware('auth:student')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    // Student settings/profile
    Route::middleware(['auth:student'])->group(function () {
        Route::get('/student/settings', [\App\Http\Controllers\App\ProfileController::class, 'edit'])->name('student.settings.edit');
        Route::patch('/student/settings', [\App\Http\Controllers\App\ProfileController::class, 'update'])->name('student.settings.update');
    });

    // Allow students to download attachments
    Route::middleware(['auth:student'])->group(function () {
        Route::get('/download-attachment/{path}', [\App\Http\Controllers\App\ActivityController::class, 'downloadAttachment'])
            ->where('path', '.*')
            ->name('activities.download-attachment');
    });

    // Allow tenants/teachers to download attachments
    Route::middleware(['auth'])->group(function () {
        Route::get('/download-attachment/{path}', [\App\Http\Controllers\App\ActivityController::class, 'downloadAttachment'])
            ->where('path', '.*')
            ->name('activities.download-attachment.tenant');
    });

    // Classroom routes
    Route::middleware('auth')->group(function () {
        // Subjects
        Route::resource('subjects', SubjectController::class);
        Route::post('/subjects/{subject}/students', [SubjectController::class, 'addStudents'])->name('subjects.students.add');
        Route::delete('/subjects/{subject}/students/{student}', [SubjectController::class, 'removeStudent'])->name('subjects.students.remove');

        // Students
        Route::resource('students', StudentController::class);
        Route::post('/students/{student}/enroll', [StudentController::class, 'enroll'])->name('students.enroll');

        // Student Subscriptions
        Route::resource('subscriptions', StudentSubscriptionController::class);
        Route::post('/subscriptions/{id}/activate', [StudentSubscriptionController::class, 'activate'])->name('subscriptions.activate');
        Route::post('/subscriptions/{id}/deactivate', [StudentSubscriptionController::class, 'deactivate'])->name('subscriptions.deactivate');
        

        // Activities
        Route::resource('activities', ActivityController::class);
        Route::post('/activities/{activity}/publish', [ActivityController::class, 'publish'])->name('activities.publish');
        Route::post('/activities/{activity}/unpublish', [ActivityController::class, 'unpublish'])->name('activities.unpublish');

        // Submissions
        Route::get('/submissions', [SubmissionController::class, 'index'])->name('submissions.index');
        Route::get('/submissions/{submission}', [SubmissionController::class, 'show'])->name('submissions.show');
        Route::post('/activities/{activity}/submit', [SubmissionController::class, 'store'])->name('submissions.store');
        Route::post('/submissions/{submission}/grade', [SubmissionController::class, 'grade'])->name('submissions.grade');
    });

    // Tenant/teacher settings/profile
    Route::middleware(['auth'])->group(function () {
        Route::get('/settings', [\App\Http\Controllers\App\ProfileController::class, 'edit'])->name('tenant.settings.edit');
        Route::patch('/settings', [\App\Http\Controllers\App\ProfileController::class, 'update'])->name('tenant.profile.update');
    });

    // DEBUG: Register the download-attachment route globally to ensure it is always available
    Route::get('/download-attachment/{path}', [\App\Http\Controllers\App\ActivityController::class, 'downloadAttachment'])
        ->where('path', '.*')
        ->name('activities.download-attachment');

    require __DIR__.'/tenant-auth.php';
});
