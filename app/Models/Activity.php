<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id', 'lead_id', 'user_id', 'activity_type',
        'description', 'activity_date',
    ];

    protected $casts = [
        'activity_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    const TYPES = [
        'call' => 'Phone Call',
        'email' => 'Email',
        'meeting' => 'Meeting',
        'note' => 'Note',
        'task' => 'Task',
        'follow_up' => 'Follow-up',
        'other' => 'Other',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeForCustomer($query, $customerId)
    {
        return $query->where('customer_id', $customerId);
    }

    public function scopeForLead($query, $leadId)
    {
        return $query->where('lead_id', $leadId);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('activity_type', $type);
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    public function getActivityTypeLabelAttribute()
    {
        return self::TYPES[$this->activity_type] ?? 'Unknown';
    }

    public function getIconAttribute()
    {
        return match($this->activity_type) {
            'call' => 'fas fa-phone',
            'email' => 'fas fa-envelope',
            'meeting' => 'fas fa-handshake',
            'note' => 'fas fa-sticky-note',
            'task' => 'fas fa-tasks',
            'follow_up' => 'fas fa-calendar-check',
            default => 'fas fa-circle',
        };
    }

    public function getColorAttribute()
    {
        return match($this->activity_type) {
            'call' => 'primary',
            'email' => 'info',
            'meeting' => 'success',
            'note' => 'warning',
            'task' => 'secondary',
            'follow_up' => 'danger',
            default => 'muted',
        };
    }
}