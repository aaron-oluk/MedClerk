<?php

namespace App\Policies;

use App\Models\Rotation;
use App\Models\User;

class RotationPolicy
{
    public function view(User $user, Rotation $rotation): bool
    {
        if ($user->isSuperadmin()) {
            return true;
        }

        if ($user->institution_id !== $rotation->institution_id) {
            return false;
        }

        return $user->isAdmin()
            || $user->id === $rotation->student_id
            || $user->id === $rotation->supervisor_id;
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isSuperadmin();
    }

    public function update(User $user, Rotation $rotation): bool
    {
        if ($user->isSuperadmin()) {
            return true;
        }

        return $user->isAdmin() && $user->institution_id === $rotation->institution_id;
    }

    public function delete(User $user, Rotation $rotation): bool
    {
        return $this->update($user, $rotation);
    }
}
