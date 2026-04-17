<?php

namespace App\Policies;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CustomerPolicy
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
    public function view(User $user, Customer $customer): bool
    {
        // Admin can view all
        if ($user->isAdmin()) {
            return true;
        }

        // Manager can view all customers
        if ($user->isManager()) {
            return true;
        }

        // Sales staff can only view assigned customers
        if ($user->isSalesStaff()) {
            return $customer->assigned_user_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->canCreateCustomers();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Customer $customer): bool
    {
        // Admin can update all
        if ($user->isAdmin()) {
            return true;
        }

        // Manager can update all customers
        if ($user->isManager()) {
            return true;
        }

        // Sales staff can only update their assigned customers
        if ($user->isSalesStaff()) {
            return $customer->assigned_user_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Customer $customer): bool
    {
        // Only admin can delete customers
        return $user->canDeleteCustomers();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Customer $customer): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Customer $customer): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine if manager can approve assignment.
     */
    public function approveAssignment(User $user, Customer $customer): bool
    {
        return $user->canReviewAssignments();
    }

    /**
     * Determine if user can reassign customer.
     */
    public function reassign(User $user, Customer $customer): bool
    {
        return $user->isAdmin() || $user->isManager();
    }
}
