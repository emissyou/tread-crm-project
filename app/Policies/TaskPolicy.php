<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TaskPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->canViewTasks();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Task $task): bool
    {
        // Admin can view all
        if ($user->isAdmin()) {
            return true;
        }

        // Manager can view all tasks
        if ($user->isManager()) {
            return true;
        }

        // Sales staff can only view their assigned tasks
        if ($user->isSalesStaff()) {
            return $task->assigned_user_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->canViewTasks();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Task $task): bool
    {
        // Admin can update all
        if ($user->isAdmin()) {
            return true;
        }

        // Manager can update all tasks
        if ($user->isManager()) {
            return true;
        }

        // Sales staff can only update their assigned tasks
        if ($user->isSalesStaff()) {
            return $task->assigned_user_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Task $task): bool
    {
        // Admin can delete all
        if ($user->isAdmin()) {
            return true;
        }

        // Manager can delete all tasks
        if ($user->isManager()) {
            return true;
        }

        // Sales staff can only delete their assigned tasks
        if ($user->isSalesStaff()) {
            return $task->assigned_user_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Task $task): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Task $task): bool
    {
        return $user->isAdmin();
    }
}