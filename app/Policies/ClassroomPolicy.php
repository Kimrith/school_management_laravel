<?php

namespace App\Policies;

use App\Enums\Role;
use App\Models\Classroom;
use App\Models\User;

class ClassroomPolicy
{
    /**
     * Perform pre-authorization checks.
     */
    public function before(User $user, string $ability): ?bool
    {
        if ($user->role === Role::Admin) {
            return true;
        }

        return null;
    }

    /**
     * Determine whether the user can view the classroom.
     */
    public function view(User $user, Classroom $classroom): bool
    {
        return $user->role === Role::Teacher && $user->teachesClassroom($classroom);
    }

    /**
     * Determine whether the user can view attendance records for the classroom.
     */
    public function viewAttendance(User $user, Classroom $classroom): bool
    {
        return $user->role === Role::Teacher && $user->teachesClassroom($classroom);
    }

    /**
     * Determine whether the user can record or update attendance for the classroom.
     */
    public function recordAttendance(User $user, Classroom $classroom): bool
    {
        return $user->role === Role::Teacher && $user->teachesClassroom($classroom);
    }
}
