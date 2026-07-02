<?php

namespace App\Policies;

use App\Models\Assessment;
use App\Models\User;

class AssessmentPolicy
{
    public function view(User $user, Assessment $assessment): bool
    {
        if ($user->isSuperadmin()) {
            return true;
        }

        if ($user->institution_id !== $assessment->rotation->institution_id) {
            return false;
        }

        return $user->isAdmin()
            || $user->id === $assessment->student_id
            || $user->id === $assessment->assessor_id;
    }

    public function create(User $user): bool
    {
        return $user->isLecturer() || $user->isAdmin() || $user->isSuperadmin();
    }

    public function update(User $user, Assessment $assessment): bool
    {
        if ($user->isSuperadmin()) {
            return true;
        }

        if ($user->id === $assessment->assessor_id) {
            return true;
        }

        return $user->isAdmin() && $user->institution_id === $assessment->rotation->institution_id;
    }

    public function delete(User $user, Assessment $assessment): bool
    {
        if ($user->isSuperadmin()) {
            return true;
        }

        return $user->isAdmin() && $user->institution_id === $assessment->rotation->institution_id;
    }
}
