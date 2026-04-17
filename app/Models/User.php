<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is manager
     */
    public function isManager(): bool
    {
        return $this->role === 'manager';
    }


    /**
     * Check if user is sales staff
     */
    public function isSalesStaff(): bool
    {
        return $this->role === 'sales_staff';
    }

    /**
     * Check if user has admin or manager role
     */
    public function isAdminOrManager(): bool
    {
        return in_array($this->role, ['admin', 'manager']);
    }

    /**
     * Check if user can manage users
     */
    public function canManageUsers(): bool
    {
        return $this->isAdmin();
    }

    /**
     * Check if user can manage customers and leads
     */
    public function canManageCustomersAndLeads(): bool
    {
        return $this->role === 'admin' || $this->role === 'manager';
    }

    /**
     * Check if user can view reports
     */
    public function canViewReports(): bool
    {
        return in_array($this->role, ['admin', 'manager']);
    }

    /**
     * Check if user can configure system settings
     */
    public function canConfigureSystem(): bool
    {
        return $this->isAdmin();
    }

    /**
     * Check if user can manage leads and opportunities
     */
    public function canManageLeads(): bool
    {
        return in_array($this->role, ['admin', 'manager', 'sales_staff']);
    }

    /**
     * Check if user can record follow-ups
     */
    public function canRecordFollowUps(): bool
    {
        return in_array($this->role, ['admin', 'manager', 'sales_staff']);
    }

    /**
     * Check if user can view assigned tasks
     */
    public function canViewAssignedTasks(): bool
    {
        return in_array($this->role, ['admin', 'manager', 'sales_staff']);
    }

    /**
     * Check if user can monitor team performance
     */
    public function canMonitorTeam(): bool
    {
        return in_array($this->role, ['admin', 'manager']);
    }

    /**
     * Check if user can approve customer assignments
     */
    public function canApproveAssignments(): bool
    {
        return in_array($this->role, ['admin', 'manager']);
    }

    /**
     * Check if user can delete customers (Admin & Manager cannot, Sales cannot)
     */
    public function canDeleteCustomers(): bool
    {
        return $this->isAdmin();
    }

    /**
     * Check if user can delete leads (Admin & Manager cannot, Sales cannot)
     */
    public function canDeleteLeads(): bool
    {
        return $this->isAdmin();
    }

    /**
     * Check if Manager can delete customers (Manager cannot delete)
     */
    public function canManagerDeleteCustomers(): bool
    {
        return false;
    }

    /**
     * Check if Manager can delete leads (Manager cannot delete)
     */
    public function canManagerDeleteLeads(): bool
    {
        return false;
    }

    /**
     * Check if user can access exports and reports
     */
    public function canExportReports(): bool
    {
        return in_array($this->role, ['admin', 'manager']);
    }

    /**
     * Check if user is sales staff member
     */
    public function isSalesStaffMember(): bool
    {
        return $this->isSalesStaff();
    }

    /**
     * Check if user can only access assigned records
     */
    public function hasRestrictedAccess(): bool
    {
        return $this->isSalesStaff();
    }

    /**
     * Check if user can create customers (with restrictions for sales staff)
     */
    public function canCreateCustomers(): bool
    {
        return in_array($this->role, ['admin', 'manager', 'sales_staff']);
    }

    /**
     * Check if sales staff needs approval for customer creation
     */
    public function requiresCustomerApproval(): bool
    {
        return $this->isSalesStaff();
    }

    /**
     * Check if user can create leads (with restrictions for sales staff)
     */
    public function canCreateLeads(): bool
    {
        return in_array($this->role, ['admin', 'manager', 'sales_staff']);
    }

    /**
     * Check if user can create activities
     */
    public function canCreateActivities(): bool
    {
        return in_array($this->role, ['admin', 'manager', 'sales_staff']);
    }

    /**
     * Check if user can create follow-ups
     */
    public function canCreateFollowUps(): bool
    {
        return in_array($this->role, ['admin', 'manager', 'sales_staff']);
    }

    /**
     * Check if user can view dashboard with monitoring
     */
    public function canViewDashboard(): bool
    {
        return in_array($this->role, ['admin', 'manager', 'sales_staff']);
    }

    /**
     * Check if user can review assignments
     */
    public function canReviewAssignments(): bool
    {
        return $this->isManager() || $this->isAdmin();
    }
}
