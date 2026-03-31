<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Deal extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title', 'contact_id', 'company_id', 'lead_id',
        'value', 'stage', 'probability',
        'expected_close_date', 'closed_date',
        'notes', 'assigned_to',
    ];

    protected $casts = [
        'expected_close_date' => 'date',
        'closed_date'         => 'date',
        'value'               => 'decimal:2',
    ];

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function getStageLabelAttribute(): string
    {
        return match($this->stage) {
            'prospecting'   => 'Prospecting',
            'qualification' => 'Qualification',
            'proposal'      => 'Proposal',
            'negotiation'   => 'Negotiation',
            'closed_won'    => 'Closed Won',
            'closed_lost'   => 'Closed Lost',
            default         => ucfirst($this->stage),
        };
    }

    public function getStageBadgeAttribute(): string
    {
        return match($this->stage) {
            'prospecting'   => 'secondary',
            'qualification' => 'info',
            'proposal'      => 'primary',
            'negotiation'   => 'warning',
            'closed_won'    => 'success',
            'closed_lost'   => 'danger',
            default         => 'secondary',
        };
    }
}
