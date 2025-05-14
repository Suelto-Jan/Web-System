<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\App\TenantController;
use App\Http\Controllers\App\SubjectController;
use App\Http\Controllers\App\StudentController;
use App\Http\Controllers\App\ActivityController;
use App\Http\Controllers\App\SubmissionController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Welcome page with subscription form
Route::get('/', [SubscriptionController::class, 'showApplicationForm'])->name('welcome');

// Subscription application route
Route::post('/subscription/apply', [SubscriptionController::class, 'apply'])->name('subscription.apply');

Route::get('/test', function () {
    return 'This is a test route. If you can see this, the routing is working correctly.';
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Tenant management routes
    Route::resource('tenants', TenantController::class);
    Route::post('/tenants/{tenant}/disable', [TenantController::class, 'disable'])->name('tenants.disable');
    Route::post('/tenants/{tenant}/enable', [TenantController::class, 'enable'])->name('tenants.enable');

    // Tenant application routes
    Route::get('/applications', [SubscriptionController::class, 'listApplications'])->name('applications.index');
    Route::post('/applications/{application}/approve', [SubscriptionController::class, 'approve'])->name('applications.approve');
    Route::post('/applications/{application}/reject', [SubscriptionController::class, 'reject'])->name('applications.reject');
});

// Tenant routes for classroom features
Route::middleware(['tenant', 'auth'])->prefix('app')->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('app.dashboard');
    })->name('tenant.dashboard');

    // Subjects
    Route::resource('subjects', SubjectController::class);
    Route::post('/subjects/{subject}/students', [SubjectController::class, 'addStudents'])->name('subjects.students.add');
    Route::delete('/subjects/{subject}/students/{student}', [SubjectController::class, 'removeStudent'])->name('subjects.students.remove');

    // Students
    Route::resource('students', StudentController::class);

    // Activities
    Route::resource('activities', ActivityController::class);

    // Submissions
    Route::resource('submissions', SubmissionController::class);
    Route::post('/activities/{activity}/submit', [SubmissionController::class, 'submit'])->name('activities.submit');
    Route::post('/submissions/{submission}/grade', [SubmissionController::class, 'grade'])->name('submissions.grade');
});

Route::get('/student/dashboard', function () {
    $student = Auth::guard('student')->user();
    $subjects = $student ? $student->subjects()->with('user')->get() : [];
    return view('app.student-dashboard', compact('subjects'));
})->middleware('auth:student')->name('student.dashboard');

// Submission routes
Route::middleware(['auth:teacher'])->group(function () {
    Route::get('/submissions', [SubmissionController::class, 'index'])->name('submissions.index');
    Route::get('/submissions/{submission}', [SubmissionController::class, 'show'])->name('submissions.show');
    Route::post('/submissions/{submission}/grade', [SubmissionController::class, 'grade'])->name('submissions.grade');
    Route::get('/submissions/{submission}/download', [SubmissionController::class, 'download'])->name('submissions.download');
    Route::get('/submissions/{submission}/preview', [SubmissionController::class, 'preview'])->name('submissions.preview');
});

// Student submission routes
Route::middleware(['auth:student'])->group(function () {
    Route::post('/activities/{activity}/submit', [SubmissionController::class, 'store'])->name('submissions.store');
    Route::get('/submissions/{submission}/preview', [SubmissionController::class, 'preview'])->name('submissions.preview');
});

require __DIR__.'/auth.php';
