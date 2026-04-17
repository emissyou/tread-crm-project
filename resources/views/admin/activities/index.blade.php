@extends('layouts.app')
@section('title', 'Activities - Tread CRM')
@section('page_title', 'Activities')
@section('page_subtitle', 'Track all interactions and communications with customers and leads.')

@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h1 class="page-title"><i class="fas fa-history me-2 text-primary"></i>Activities</h1>
        <p class="page-subtitle">Track all interactions and communications with customers and leads.</p>
    </div>
    <div class="page-actions">
        <button type="button" class="btn-crm-primary" onclick="createActivity()">
            <i class="fas fa-plus"></i> Log Activity
        </button>
    </div>
</div>

<!-- Stats Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="crm-card p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="avatar-circle bg-primary">{{ $stats['total'] }}</div>
                <div>
                    <div class="fw-bold">{{ $stats['total'] }} Total</div>
                    <div class="text-muted small">All activities</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="crm-card p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="avatar-circle bg-info">{{ $stats['calls'] }}</div>
                <div>
                    <div class="fw-bold">{{ $stats['calls'] }} Calls</div>
                    <div class="text-muted small">Phone interactions</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="crm-card p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="avatar-circle bg-success">{{ $stats['meetings'] }}</div>
                <div>
                    <div class="fw-bold">{{ $stats['meetings'] }} Meetings</div>
                    <div class="text-muted small">In-person meetings</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="crm-card p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="avatar-circle bg-warning">{{ $stats['emails'] }}</div>
                <div>
                    <div class="fw-bold">{{ $stats['emails'] }} Emails</div>
                    <div class="text-muted small">Email communications</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="crm-card mb-4">
    <div class="crm-card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="crm-label">Search</label>
                <input type="text" class="crm-input" name="search" value="{{ request('search') }}" placeholder="Search activities...">
            </div>
            <div class="col-md-2">
                <label class="crm-label">Type</label>
                <select class="crm-input" name="activity_type">
                    <option value="">All Types</option>
                    <option value="call" {{ request('activity_type') === 'call' ? 'selected' : '' }}>Call</option>
                    <option value="email" {{ request('activity_type') === 'email' ? 'selected' : '' }}>Email</option>
                    <option value="meeting" {{ request('activity_type') === 'meeting' ? 'selected' : '' }}>Meeting</option>
                    <option value="note" {{ request('activity_type') === 'note' ? 'selected' : '' }}>Note</option>
                    <option value="task" {{ request('activity_type') === 'task' ? 'selected' : '' }}>Task</option>
                    <option value="follow_up" {{ request('activity_type') === 'follow_up' ? 'selected' : '' }}>Follow-up</option>
                    <option value="other" {{ request('activity_type') === 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="crm-label">Customer</label>
                <select class="crm-input" name="customer_id">
                    <option value="">All Customers</option>
                    @foreach($customers as $customer)
                    <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                        {{ $customer->full_name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="crm-label">Lead</label>
                <select class="crm-input" name="lead_id">
                    <option value="">All Leads</option>
                    @foreach($leads as $lead)
                    <option value="{{ $lead->id }}" {{ request('lead_id') == $lead->id ? 'selected' : '' }}>
                        {{ $lead->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="crm-label">Date Range</label>
                <div class="d-flex gap-2">
                    <input type="date" class="crm-input" name="date_from" value="{{ request('date_from') }}">
                    <input type="date" class="crm-input" name="date_to" value="{{ request('date_to') }}">
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Activities Timeline -->
<div class="crm-card">
    <div class="crm-card-header">
        <div>
            <h5 class="mb-1 fw-semibold">Activity Timeline</h5>
            <p class="mb-0 text-muted">Chronological view of all customer and lead interactions.</p>
        </div>
    </div>
    <div class="crm-card-body">
        <div class="timeline">
            @forelse($activities as $activity)
            <div class="timeline-item">
                <div class="timeline-marker bg-{{ $activity->color }}">
                    <i class="{{ $activity->icon }}"></i>
                </div>
                <div class="timeline-content">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h6 class="mb-1">{{ $activity->activity_type_label }}</h6>
                            <small class="text-muted">
                                {{ $activity->date->format('M d, Y \a\t h:i A') }}
                                by {{ $activity->createdBy->name }}
                            </small>
                        </div>
                        <div class="d-flex gap-2 align-items-start">
                            @if($activity->customer || $activity->lead)
                            <div class="text-end">
                                @if($activity->customer)
                                <small class="text-primary">
                                    <i class="fas fa-user me-1"></i>{{ $activity->customer->full_name }}
                                </small>
                                @elseif($activity->lead)
                                <small class="text-warning">
                                    <i class="fas fa-bullseye me-1"></i>{{ $activity->lead->name }}
                                </small>
                                @endif
                            </div>
                            @endif
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle p-0" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="min-width: 32px">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="#" onclick="editActivity({{ $activity->id }}); return false"><i class="fas fa-edit"></i> Edit</a></li>
                                    @if(auth()->user()->isAdminOrManager() || $activity->user_id === auth()->id())
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger" href="#" onclick="deleteActivity({{ $activity->id }}); return false"><i class="fas fa-trash"></i> Delete</a></li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                    <p class="mb-2">{{ $activity->description }}</p>
                </div>
            </div>
            @empty
            <div class="text-center py-5">
                <div class="empty-state">
                    <i class="fas fa-history fa-3x text-muted mb-3"></i>
                    <h5>No activities logged</h5>
                    <p class="text-muted">Start tracking your customer interactions by logging your first activity.</p>
                    <button type="button" class="btn-crm-primary" onclick="createActivity()">
                        <i class="fas fa-plus"></i> Log First Activity
                    </button>
                </div>
            </div>
            @endforelse
        </div>

        @if($activities->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $activities->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Activity Modal -->
<div class="modal fade" id="activityModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content crm-modal">
            <div class="modal-header">
                <h5 class="modal-title" id="activityModalTitle">Log Activity</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="activityForm">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="crm-label">Activity Type *</label>
                            <select class="crm-input" name="activity_type" required>
                                <option value="note">Note</option>
                                <option value="call">Phone Call</option>
                                <option value="email">Email</option>
                                <option value="meeting">Meeting</option>
                                <option value="task">Task</option>
                                <option value="follow_up">Follow-up</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="crm-label">Date & Time *</label>
                            <input type="datetime-local" class="crm-input" name="date" required>
                        </div>
                        <div class="col-md-6">
                            <label class="crm-label">Related Customer</label>
                            <select class="crm-input" name="customer_id" id="activityCustomerSelect">
                                <option value="">Select Customer (Optional)</option>
                                @foreach($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->full_name }} ({{ $customer->email }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="crm-label">Related Lead</label>
                            <select class="crm-input" name="lead_id" id="activityLeadSelect">
                                <option value="">Select Lead (Optional)</option>
                                @foreach($leads as $lead)
                                <option value="{{ $lead->id }}">{{ $lead->name }} ({{ $lead->email }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="crm-label">Description *</label>
                            <textarea class="crm-input" name="description" rows="4" required
                                placeholder="Describe the activity in detail..."></textarea>
                        </div>
                        <div class="col-12" id="metadataSection" style="display: none;">
                            <label class="crm-label">Additional Details (JSON)</label>
                            <textarea class="crm-input" name="metadata" rows="3"
                                placeholder='{"duration": "30min", "outcome": "successful"}'></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-crm-primary">Log Activity</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
let activityModal = new bootstrap.Modal(document.getElementById('activityModal'));
let activityForm = document.getElementById('activityForm');
let isEditing = false;
let editingActivityId = null;

function createActivity() {
    isEditing = false;
    editingActivityId = null;
    document.getElementById('activityModalTitle').textContent = 'Log Activity';
    activityForm.reset();

    // Set default date to now
    const now = new Date();
    activityForm.querySelector('[name="date"]').value = now.toISOString().slice(0, 16);

    activityModal.show();
}

function editActivity(activityId) {
    isEditing = true;
    editingActivityId = activityId;
    document.getElementById('activityModalTitle').textContent = 'Edit Activity';

    fetch(`/admin/activities/${activityId}`)
        .then(response => response.json())
        .then(activity => {
            activityForm.querySelector('[name="activity_type"]').value = activity.activity_type;
            activityForm.querySelector('[name="description"]').value = activity.description;
            activityForm.querySelector('[name="date"]').value = activity.date.slice(0, 16);
            activityForm.querySelector('[name="customer_id"]').value = activity.customer_id || '';
            activityForm.querySelector('[name="lead_id"]').value = activity.lead_id || '';
            if (activity.metadata) {
                activityForm.querySelector('[name="metadata"]').value = JSON.stringify(activity.metadata, null, 2);
                document.getElementById('metadataSection').style.display = 'block';
            }
            activityModal.show();
        });
}

function deleteActivity(activityId) {
    if (confirm('Are you sure you want to delete this activity?')) {
        fetch(`/admin/activities/${activityId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
    }
}

// Show metadata field for certain activity types
activityForm.querySelector('[name="activity_type"]').addEventListener('change', function() {
    const metadataSection = document.getElementById('metadataSection');
    const selectedType = this.value;

    if (['call', 'meeting'].includes(selectedType)) {
        metadataSection.style.display = 'block';
    } else {
        metadataSection.style.display = 'none';
    }
});

activityForm.addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(activityForm);
    const url = isEditing ? `/admin/activities/${editingActivityId}` : '/admin/activities';
    const method = isEditing ? 'PATCH' : 'POST';

    // Parse metadata if provided
    const metadataValue = formData.get('metadata');
    if (metadataValue) {
        try {
            formData.set('metadata', JSON.parse(metadataValue));
        } catch (e) {
            alert('Invalid JSON in metadata field');
            return;
        }
    }

    fetch(url, {
        method: method,
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            activityModal.hide();
            location.reload();
        } else if (data.errors) {
            console.log(data.errors);
        }
    });
});
</script>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e3e7ef;
}

.timeline-item {
    position: relative;
    margin-bottom: 30px;
}

.timeline-marker {
    position: absolute;
    left: -22px;
    top: 5px;
    width: 14px;
    height: 14px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 8px;
}

.timeline-content {
    background: #f8fafc;
    border: 1px solid #e3e7ef;
    border-radius: 8px;
    padding: 16px;
}
</style>
@endsection