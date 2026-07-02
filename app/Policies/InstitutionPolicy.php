<?php

namespace App\Policies;

use App\Models\Institution;
use App\Models\User;

class InstitutionPolicy
{
    public function create(User $user): bool
    {
        return $user->isSuperadmin();
    }

    public function update(User $user, Institution $institution): bool
    {
        return $user->isSuperadmin() || ($user->isAdmin() && $user->institution_id === $institution->id);
    }

    public function delete(User $user, Institution $institution): bool
    {
        return $user->isSuperadmin();
    }
}
