<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'due_date',
        'subject_id',
        'is_published',
        'activity_document_path',  // For teacher's activity document
        'reviewer_attachment_path', // For reviewer's PDF attachment
        'google_docs_url',
        'attachment',         // For Google Docs integration
    ];

    protected $casts = [
        'due_date' => 'datetime',
        'is_published' => 'boolean',
    ];

    /**
     * Get the subject that owns the activity.
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Get the submissions for the activity.
     */
    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }
}
