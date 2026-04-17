<?php

namespace App\Services;

use App\Models\User;
use App\Models\Customer;
use App\Models\Lead;
use App\Models\Activity;
use App\Models\FollowUp;

/**
 * Authorization service for role-based permission checks
 */
class AuthorizationService
{
    /**
     * Check if user has admin role
     */
    public static function isAdmin(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Check if user has manager role
     */
    public static function isManager(User $user): bool
    {
        return $user->isManager();
    }

    /**
     * Check if user has sales staff role
     */
    public static function isSalesStaff(User $user): bool
    {
        return $user->isSalesStaff();
    }

    /**
     * Check if user can view customer
     */
    public static function canViewCustomer(User $user, Customer $customer): bool
    {
        if ($user->isAdmin() || $user->isManager()) {
            return true;
        }

        if ($user->isSalesStaff()) {
            return $customer->assigned_user_id === $user->id;
        }

        return false;
    }

    /**
     * Check if user can edit customer
     */
    public static function canEditCustomer(User $user, Customer $customer): bool
    {
        if ($user->isAdmin() || $user->isManager()) {
            return true;
        }

        if ($user->isSalesStaff()) {
            return $customer->assigned_user_id === $user->id;
        }

        return false;
    }

    /**
     * Check if user can delete customer (only admin)
     */
    public static function canDeleteCustomer(User $user, Customer $customer): bool
    {
        return $user->isAdmin();
    }

    /**
     * Check if user can view lead
     */
    public static function canViewLead(User $user, Lead $lead): bool
    {
        if ($user->isAdmin() || $user->isManager()) {
            return true;
        }

        if ($user->isSalesStaff()) {
            return $lead->assigned_user_id === $user->id;
        }

        return false;
    }

    /**
     * Check if user can edit lead
     */
    public static function canEditLead(User $user, Lead $lead): bool
    {
        if ($user->isAdmin() || $user->isManager()) {
            return true;
        }

        if ($user->isSalesStaff()) {
            return $lead->assigned_user_id === $user->id;
        }

        return false;
    }

    /**
     * Check if user can delete lead (only admin)
     */
    public static function canDeleteLead(User $user, Lead $lead): bool
    {
        return $user->isAdmin();
    }

    /**
     * Check if user can view activity
     */
    public static function canViewActivity(User $user, Activity $activity): bool
    {
        if ($user->isAdmin() || $user->isManager()) {
            return true;
        }

        if ($user->isSalesStaff()) {
            $isCreator = $activity->user_id === $user->id;
            $isAssignedCustomer = $activity->customer && $activity->customer->assigned_user_id === $user->id;
            $isAssignedLead = $activity->lead && $activity->lead->assigned_user_id === $user->id;

            return $isCreator || $isAssignedCustomer || $isAssignedLead;
        }

        return false;
    }

    /**
     * Check if user can edit activity
     */
    public static function canEditActivity(User $user, Activity $activity): bool
    {
        if ($user->isAdmin() || $user->isManager()) {
            return true;
        }

        if ($user->isSalesStaff()) {
            return $activity->user_id === $user->id;
        }

        return false;
    }

    /**
     * Check if user can delete activity
     */
    public static function canDeleteActivity(User $user, Activity $activity): bool
    {
        if ($user->isAdmin() || $user->isManager()) {
            return true;
        }

        if ($user->isSalesStaff()) {
            return $activity->user_id === $user->id;
        }

        return false;
    }

    /**
     * Check if user can view follow-up
     */
    public static function canViewFollowUp(User $user, FollowUp $followUp): bool
    {
        if ($user->isAdmin() || $user->isManager()) {
            return true;
        }

        if ($user->isSalesStaff()) {
            $isCreator = $followUp->user_id === $user->id;
            $isAssignedCustomer = $followUp->customer && $followUp->customer->assigned_user_id === $user->id;
            $isAssignedLead = $followUp->lead && $followUp->lead->assigned_user_id === $user->id;

            return $isCreator || $isAssignedCustomer || $isAssignedLead;
        }

        return false;
    }

    /**
     * Check if user can edit follow-up
     */
    public static function canEditFollowUp(User $user, FollowUp $followUp): bool
    {
        if ($user->isAdmin() || $user->isManager()) {
            return true;
        }

        if ($user->isSalesStaff()) {
            return $followUp->user_id === $user->id;
        }

        return false;
    }

    /**
     * Check if user can delete follow-up
     */
    public static function canDeleteFollowUp(User $user, FollowUp $followUp): bool
    {
        if ($user->isAdmin() || $user->isManager()) {
            return true;
        }

        if ($user->isSalesStaff()) {
            return $followUp->user_id === $user->id;
        }

        return false;
    }

    /**
     * Get all customers accessible to user
     */
    public static function getAccessibleCustomers(User $user)
    {
        if ($user->isAdmin() || $user->isManager()) {
            return Customer::all();
        }

        if ($user->isSalesStaff()) {
            return Customer::where('assigned_user_id', $user->id)->get();
        }

        return collect([]);
    }

    /**
     * Get all leads accessible to user
     */
    public static function getAccessibleLeads(User $user)
    {
        if ($user->isAdmin() || $user->isManager()) {
            return Lead::all();
        }

        if ($user->isSalesStaff()) {
            return Lead::where('assigned_user_id', $user->id)->get();
        }

        return collect([]);
    }
}
