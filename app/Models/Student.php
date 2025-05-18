<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Student extends Authenticatable
{
    use BelongsToTenant;

    protected $fillable = [
        'name',
        'email',
        'student_id',
        'profile_photo',
        'notes',
        'plan',
        'password',
        'tenant_id',
    ];

    /**
     * Get the subjects the student is enrolled in.
     */
    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'student_subjects')
            ->withPivot('status')
            ->withTimestamps();
    }

    /**
     * Get the activity submissions for the student.
     */
    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }

    /**
     * Get the subscriptions for the student.
     */
    public function subscriptions()
    {
        return $this->hasMany(StudentSubscription::class);
    }

    /**
     * Get the active subscription for the student.
     */
    public function activeSubscription()
    {
        return $this->subscriptions()
            ->where('status', 'active')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->latest()
            ->first();
    }

    /**
     * Get the quiz attempts for the student.
     */
    public function quizAttempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }

    /**
     * Check if the student has attempted a specific quiz.
     */
    public function hasAttemptedQuiz($quizId)
    {
        return $this->quizAttempts()->where('quiz_id', $quizId)->exists();
    }

    /**
     * Get the latest attempt for a specific quiz.
     */
    public function getLatestQuizAttempt($quizId)
    {
        return $this->quizAttempts()
            ->where('quiz_id', $quizId)
            ->latest()
            ->first();
    }

    /**
     * Check if the student has premium access.
     */
    public function hasPremiumAccess()
    {
        return $this->plan === 'premium';
    }
}
