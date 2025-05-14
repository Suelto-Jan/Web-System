<?php

namespace App\Policies;

use App\Models\Submission;
use App\Models\Student;
use Illuminate\Auth\Access\HandlesAuthorization;

class SubmissionPolicy
{
    use HandlesAuthorization;

    public function view(Student $student, Submission $submission)
    {
        return $student->id === $submission->student_id;
    }

    public function update(Student $student, Submission $submission)
    {
        return $student->id === $submission->student_id;
    }

    public function delete(Student $student, Submission $submission)
    {
        return $student->id === $submission->student_id;
    }
} 