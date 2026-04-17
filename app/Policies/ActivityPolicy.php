<?php

namespace App\Policies;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ActivityPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->canViewReports() || $user->isSalesStaff();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Activity $activity): bool
    {
        // Admin can view all
        if ($user->isAdmin()) {
            return true;
        }

        // Manager can view all activities
        if ($user->isManager()) {
            return true;
        }

        // Sales staff can view activities they created or for their assigned records
        if ($user->isSalesStaff()) {
            $isCreator = $activity->user_id === $user->id;
            $isAssignedCustomer = $activity->customer && $activity->customer->assigned_user_id === $user->id;
            $isAssignedLead = $activity->lead && $activity->lead->assigned_user_id === $user->id;

            return $isCreator || $isAssignedCustomer || $isAssignedLead;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->canCreateActivities();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Activity $activity): bool
    {
        // Admin can update all
        if ($user->isAdmin()) {
            return true;
        }

        // Manager can update all activities
        if ($user->isManager()) {
            return true;
        }

        // Sales staff can only update their own activities
        if ($user->isSalesStaff()) {
            return $activity->user_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Activity $activity): bool
    {
        // Admin can delete all
        if ($user->isAdmin()) {
            return true;
        }

        // Manager can delete all activities
        if ($user->isManager()) {
            return true;
        }

        // Sales staff can only delete their own activities
        if ($user->isSalesStaff()) {
            return $activity->user_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Activity $activity): bool
    {
        return $user->isAdmin() || $user->isManager();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Activity $activity): bool
    {
        return $user->isAdmin();
    }
}
