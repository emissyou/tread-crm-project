<?php

namespace App\Policies;

use App\Models\FollowUp;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class FollowUpPolicy
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
    public function view(User $user, FollowUp $followUp): bool
    {
        // Admin can view all
        if ($user->isAdmin()) {
            return true;
        }

        // Manager can view all follow-ups
        if ($user->isManager()) {
            return true;
        }

        // Sales staff can view follow-ups they created or for their assigned records
        if ($user->isSalesStaff()) {
            $isCreator = $followUp->user_id === $user->id;
            $isAssignedCustomer = $followUp->customer && $followUp->customer->assigned_user_id === $user->id;
            $isAssignedLead = $followUp->lead && $followUp->lead->assigned_user_id === $user->id;

            return $isCreator || $isAssignedCustomer || $isAssignedLead;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->canCreateFollowUps();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, FollowUp $followUp): bool
    {
        // Admin can update all
        if ($user->isAdmin()) {
            return true;
        }

        // Manager can update all follow-ups
        if ($user->isManager()) {
            return true;
        }

        // Sales staff can only update their own follow-ups
        if ($user->isSalesStaff()) {
            return $followUp->user_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, FollowUp $followUp): bool
    {
        // Admin can delete all
        if ($user->isAdmin()) {
            return true;
        }

        // Manager can delete all follow-ups
        if ($user->isManager()) {
            return true;
        }

        // Sales staff can only delete their own follow-ups
        if ($user->isSalesStaff()) {
            return $followUp->user_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, FollowUp $followUp): bool
    {
        return $user->isAdmin() || $user->isManager();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, FollowUp $followUp): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine if user can mark as complete.
     */
    public function markComplete(User $user, FollowUp $followUp): bool
    {
        return $this->update($user, $followUp);
    }
}
