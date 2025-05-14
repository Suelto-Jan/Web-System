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
        return $this->hasMany(ActivitySubmission::class);
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
}
