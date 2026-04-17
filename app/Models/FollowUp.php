<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FollowUp extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'lead_id',
        'user_id',           // ← Changed from assigned_user_id
        'title',
        'description',
        'due_date',
        'status',
        'completed_at',
    ];

    protected $casts = [
        'due_date'    => 'datetime',
        'created_at'  => 'datetime',
        'updated_at'  => 'datetime',
        'completed_at' => 'datetime',
    ];

    // Relationships
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function user()           // ← This should be the main relationship
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'pending')
                     ->where('due_date', '<', now());
    }

    public function scopeDueToday($query)
    {
        return $query->where('status', 'pending')
                     ->whereDate('due_date', today());
    }

    public function scopeDueSoon($query)
    {
        return $query->where('status', 'pending')
                     ->whereBetween('due_date', [now(), now()->addDays(7)]);
    }

    public function markAsCompleted()
    {
        $this->update([
            'status'       => 'completed',
            'completed_at' => now(),   // You can keep this if you want, or remove it
        ]);
    }

    // Accessors
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'pending'   => 'warning',
            'completed' => 'success',
            'overdue'   => 'danger',
            default     => 'secondary'
        };
    }

    public function getIsOverdueAttribute()
    {
        return $this->status === 'pending' && $this->due_date < now();
    }
}