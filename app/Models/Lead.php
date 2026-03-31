<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title', 'contact_id', 'company_id', 'source',
        'status', 'priority', 'value', 'notes',
        'follow_up_date', 'assigned_to',
    ];

    protected $casts = [
        'follow_up_date' => 'date',
        'value' => 'decimal:2',
    ];

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function deals()
    {
        return $this->hasMany(Deal::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'new'         => 'warning',
            'contacted'   => 'info',
            'negotiating' => 'primary',
            'closed'      => 'success',
            'lost'        => 'danger',
            default       => 'secondary',
        };
    }

    public function getPriorityBadgeAttribute(): string
    {
        return match($this->priority) {
            'high'   => 'danger',
            'medium' => 'warning',
            'low'    => 'success',
            default  => 'secondary',
        };
    }
}
