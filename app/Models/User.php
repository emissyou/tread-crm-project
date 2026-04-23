<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ====================== EXISTING METHODS ======================
    public function canManageUsers(): bool
    {
        return $this->isAdmin();
    }

    public function canConfigureSystem(): bool
    {
        return $this->isAdmin();
    }

    // === Role Methods ===
    public function isAdmin(): bool 
    { 
        return $this->role === 'admin'; 
    }

    public function isManager(): bool 
    { 
        return $this->role === 'manager'; 
    }

    public function isSalesStaff(): bool 
    { 
        return $this->role === 'sales_staff'; 
    }

    public function isAdminOrManager(): bool 
    { 
        return in_array($this->role, ['admin', 'manager']); 
    }

    public function isActive(): bool
    {
        return $this->deleted_at === null;
    }

    // ====================== NEW METHODS (to fix the error) ======================

    /**
     * Can manage (view + edit) customers and leads
     */
    public function canManageCustomersAndLeads(): bool
    {
        return $this->isAdmin() || $this->isManager();
    }

    /**
     * Can delete leads
     */
    public function canDeleteLeads(): bool
    {
        return $this->isAdmin();           // Only Admin can delete leads
        // If you want Manager to also delete, change to:
        // return $this->isAdminOrManager();
    }

    /**
     * Can delete customers
     */
    public function canDeleteCustomers(): bool
    {
        return $this->isAdmin();           // Only Admin can delete customers
    }

}