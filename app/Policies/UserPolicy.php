<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isSuperadmin();
    }

    public function view(User $user, User $target): bool
    {
        if ($user->isSuperadmin()) {
            return true;
        }

        return $user->isAdmin() && $user->institution_id === $target->institution_id;
    }

    public function update(User $user, User $target): bool
    {
        if ($user->isSuperadmin()) {
            return true;
        }

        if (! $user->isAdmin()) {
            return false;
        }

        return $user->institution_id === $target->institution_id && ! $target->isSuperadmin();
    }
}
