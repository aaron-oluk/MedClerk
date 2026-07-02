<?php

namespace App\Policies;

use App\Models\Feedback;
use App\Models\User;

class FeedbackPolicy
{
    public function view(User $user, Feedback $feedback): bool
    {
        if ($user->isSuperadmin()) {
            return true;
        }

        if ($user->institution_id !== $feedback->student->institution_id) {
            return false;
        }

        return $user->isAdmin()
            || $user->id === $feedback->student_id
            || $user->id === $feedback->given_by;
    }

    public function create(User $user): bool
    {
        return $user->isLecturer() || $user->isAdmin() || $user->isSuperadmin();
    }

    public function update(User $user, Feedback $feedback): bool
    {
        if ($user->isSuperadmin()) {
            return true;
        }

        if ($user->id === $feedback->given_by) {
            return true;
        }

        return $user->isAdmin() && $user->institution_id === $feedback->student->institution_id;
    }

    public function delete(User $user, Feedback $feedback): bool
    {
        if ($user->isSuperadmin()) {
            return true;
        }

        return $user->isAdmin() && $user->institution_id === $feedback->student->institution_id;
    }
}
