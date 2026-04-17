<?php

namespace App\Policies;

use App\Models\Lead;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class LeadPolicy
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
    public function view(User $user, Lead $lead): bool
    {
        // Admin can view all
        if ($user->isAdmin()) {
            return true;
        }

        // Manager can view all leads
        if ($user->isManager()) {
            return true;
        }

        // Sales staff can only view their assigned leads
        if ($user->isSalesStaff()) {
            return $lead->assigned_user_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->canCreateLeads();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Lead $lead): bool
    {
        // Admin can update all
        if ($user->isAdmin()) {
            return true;
        }

        // Manager can update all leads
        if ($user->isManager()) {
            return true;
        }

        // Sales staff can only update their assigned leads
        if ($user->isSalesStaff()) {
            return $lead->assigned_user_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Lead $lead): bool
    {
        // Only admin can delete leads
        return $user->canDeleteLeads();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Lead $lead): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Lead $lead): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine if manager can approve lead assignment.
     */
    public function approveAssignment(User $user, Lead $lead): bool
    {
        return $user->canReviewAssignments();
    }

    /**
     * Determine if user can reassign lead.
     */
    public function reassign(User $user, Lead $lead): bool
    {
        return $user->isAdmin() || $user->isManager();
    }

    /**
     * Determine if user can convert lead.
     */
    public function convert(User $user, Lead $lead): bool
    {
        return $user->isAdmin() || $user->isManager() || ($user->isSalesStaff() && $lead->assigned_user_id === $user->id);
    }
}
