<?php

namespace App\Policies;

use App\Models\LogbookEntry;
use App\Models\User;

class LogbookEntryPolicy
{
    public function view(User $user, LogbookEntry $logbookEntry): bool
    {
        if ($user->isSuperadmin()) {
            return true;
        }

        if ($user->institution_id !== $logbookEntry->rotation->institution_id) {
            return false;
        }

        return $user->isAdmin()
            || $user->id === $logbookEntry->student_id
            || $user->id === $logbookEntry->rotation->supervisor_id;
    }

    public function create(User $user): bool
    {
        return $user->isStudent() || $user->isAdmin() || $user->isSuperadmin();
    }

    public function update(User $user, LogbookEntry $logbookEntry): bool
    {
        if ($user->isSuperadmin()) {
            return true;
        }

        if ($user->id === $logbookEntry->student_id) {
            return true;
        }

        return $user->isAdmin() && $user->institution_id === $logbookEntry->rotation->institution_id;
    }

    public function delete(User $user, LogbookEntry $logbookEntry): bool
    {
        if ($user->isSuperadmin()) {
            return true;
        }

        return $user->isAdmin() && $user->institution_id === $logbookEntry->rotation->institution_id;
    }
}
