@extends('layouts.app')
@section('title', 'Follow-ups - Tread CRM')
@section('page_title', 'Follow-ups')
@section('page_subtitle', 'Manage scheduled follow-ups and reminders for customers and leads.')

@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h1 class="page-title"><i class="fas fa-calendar-check me-2 text-primary"></i>Follow-ups</h1>
        <p class="page-subtitle">Manage scheduled follow-ups and reminders for customers and leads.</p>
    </div>
    <button type="button" class="btn-crm-primary" data-action="create-followup">
        <i class="fas fa-calendar-plus"></i> Schedule Follow-up
    </button>
</div>

<!-- Stats Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="crm-card p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="avatar-circle bg-primary">{{ $stats['total'] }}</div>
                <div>
                    <div class="fw-bold">{{ $stats['total'] }} Total</div>
                    <div class="text-muted small">All follow-ups</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="crm-card p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="avatar-circle bg-warning">{{ $stats['pending'] }}</div>
                <div>
                    <div class="fw-bold">{{ $stats['pending'] }} Pending</div>
                    <div class="text-muted small">Awaiting completion</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="crm-card p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="avatar-circle bg-danger">{{ $stats['overdue'] }}</div>
                <div>
                    <div class="fw-bold">{{ $stats['overdue'] }} Overdue</div>
                    <div class="text-muted small">Past due date</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="crm-card p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="avatar-circle bg-success">{{ $stats['completed'] }}</div>
                <div>
                    <div class="fw-bold">{{ $stats['completed'] }} Completed</div>
                    <div class="text-muted small">Successfully done</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="crm-card mb-4">
    <div class="crm-card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <label class="crm-label">Search</label>
                <input type="text" class="crm-input" name="search" value="{{ request('search') }}" placeholder="Search follow-ups...">
            </div>
            <div class="col-md-2">
                <label class="crm-label">Status</label>
                <select class="crm-input" name="status">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="overdue" {{ request('status') === 'overdue' ? 'selected' : '' }}>Overdue</option>
                </select>
            </div>
           <div class="col-md-3">
                <label class="crm-label">Assigned User</label>
                <select class="crm-input" name="user_id"> <!-- ← Changed from assigned_user_id -->
                    <option value="">All Users</option>
                    @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="crm-label">Quick Filter</label>
                <select class="crm-input" name="filter" onchange="this.form.submit()">
                    <option value="">All Follow-ups</option>
                    <option value="pending" {{ request('filter') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="overdue" {{ request('filter') === 'overdue' ? 'selected' : '' }}>Overdue</option>
                    <option value="due_today" {{ request('filter') === 'due_today' ? 'selected' : '' }}>Due Today</option>
                    <option value="due_soon" {{ request('filter') === 'due_soon' ? 'selected' : '' }}>Due Soon</option>
                    <option value="completed" {{ request('filter') === 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>
        </form>
    </div>
</div>

<!-- Follow-ups Table -->
<div class="crm-card">
    <div class="crm-card-header">
        <div>
            <h5 class="mb-1 fw-semibold">Follow-up Schedule</h5>
            <p class="mb-0 text-muted">Track and manage all scheduled follow-ups and reminders.</p>
        </div>
    </div>
    <div class="crm-card-body">
        <div class="crm-table-responsive">
            <table class="crm-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Related To</th>
                        <th>Due Date</th>
                        <th>Status</th>
                        <th>Assigned</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($followUps as $followUp)
                    <tr class="{{ $followUp->is_overdue ? 'table-warning' : '' }}">
                        <td>
                            <div>
                                <div class="fw-semibold">{{ $followUp->title }}</div>
                                @if($followUp->description)
                                <div class="text-muted small">{{ Str::limit($followUp->description, 50) }}</div>
                                @endif
                            </div>
                        </td>
                        <td>
                            @if($followUp->customer)
                            <div>
                                <i class="fas fa-user text-primary me-1"></i>
                                <span>{{ $followUp->customer->full_name }}</span>
                                <div class="text-muted small">Customer</div>
                            </div>
                            @elseif($followUp->lead)
                            <div>
                                <i class="fas fa-bullseye text-warning me-1"></i>
                                <span>{{ $followUp->lead->name }}</span>
                                <div class="text-muted small">Lead</div>
                            </div>
                            @else
                            <span class="text-muted">No relation</span>
                            @endif
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $followUp->due_date->format('M d, Y') }}</div>
                            <div class="text-muted small">{{ $followUp->due_date->format('h:i A') }}</div>
                            @if($followUp->is_overdue)
                            <span class="badge-crm badge-danger">Overdue</span>
                            @elseif($followUp->due_date->isToday())
                            <span class="badge-crm badge-warning">Due Today</span>
                            @elseif($followUp->due_date->diffInDays() <= 7)
                            <span class="badge-crm badge-info">Due Soon</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge-crm badge-{{ $followUp->status_badge }}">
                                {{ ucfirst($followUp->status) }}
                            </span>
                        </td>
                       <td>
                            @if($followUp->user) 
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar-circle-small bg-secondary">
                                    {{ substr($followUp->user->name, 0, 1) }} <!-- ← Changed from assignedUser -->
                                </div>
                                <span class="small">{{ $followUp->user->name }}</span> <!-- ← Changed from assignedUser -->
                            </div>
                            @else
                            <span class="text-muted small">Unassigned</span>
                            @endif
                        </td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    @if($followUp->status === 'pending' && (auth()->user()->isAdminOrManager() || $followUp->user_id === auth()->id()))
                                    <li><a class="dropdown-item" href="#" onclick="toggleComplete({{ $followUp->id }}); return false"><i class="fas fa-check"></i> Mark Complete</a></li>
                                    @endif
                                    @if(auth()->user()->isAdminOrManager() || $followUp->user_id === auth()->id())
                                    <li><a class="dropdown-item" href="#" onclick="editFollowUp({{ $followUp->id }}); return false"><i class="fas fa-edit"></i> Edit</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger" href="#" onclick="deleteFollowUp({{ $followUp->id }}, '{{ $followUp->title }}'); return false"><i class="fas fa-trash"></i> Delete</a></li>
                                    @endif
                                </ul>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="empty-state">
                                <i class="fas fa-calendar-check fa-3x text-muted mb-3"></i>
                                <h5>No follow-ups scheduled</h5>
                                <p class="text-muted">Start scheduling follow-ups to stay on top of your customer relationships.</p>
                                <button type="button" class="btn-crm-primary" data-action="create-followup">
                                    <i class="fas fa-calendar-plus"></i> Schedule First Follow-up
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($followUps->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $followUps->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Follow-up Modal -->
<div class="modal fade" id="followUpModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content crm-modal">
            <div class="modal-header">
                <h5 class="modal-title" id="followUpModalTitle">Schedule Follow-up</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="followUpForm">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="crm-label">Title *</label>
                            <input type="text" class="crm-input" name="title" required placeholder="e.g., Follow up on proposal">
                        </div>
                        <div class="col-12">
                            <label class="crm-label">Description</label>
                            <textarea class="crm-input" name="description" rows="3" placeholder="Details about this follow-up..."></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="crm-label">Related Customer</label>
                            <select class="crm-input" name="customer_id" id="customerSelect">
                                <option value="">Select Customer (Optional)</option>
                                @foreach($customers ?? [] as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->full_name }} ({{ $customer->email }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="crm-label">Related Lead</label>
                            <select class="crm-input" name="lead_id" id="leadSelect">
                                <option value="">Select Lead (Optional)</option>
                                @foreach($leads ?? [] as $lead)
                                <option value="{{ $lead->id }}">{{ $lead->name }} ({{ $lead->email }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="crm-label">Due Date & Time *</label>
                            <input type="datetime-local" class="crm-input" name="due_date" required>
                        </div>
                        <div class="col-md-6">
                            <label class="crm-label">Assigned User</label>
                            <select class="crm-input" name="user_id"> <!-- ← Changed from assigned_user_id -->
                                <option value="">Select User</option>
                                @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="crm-label">Notes</label>
                            <textarea class="crm-input" name="notes" rows="2" placeholder="Additional notes..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-crm-primary">Schedule Follow-up</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let followUpModal = new bootstrap.Modal(document.getElementById('followUpModal'));
    let followUpForm = document.getElementById('followUpForm');
    let isEditing = false;
    let editingFollowUpId = null;

    // Handle data-action buttons
    document.addEventListener('click', function(e) {
        if (e.target.closest('[data-action="create-followup"]')) {
            createFollowUp();
        }
    });

    // Make functions globally available (for onclick in dropdowns)
    window.createFollowUp = function() {
        isEditing = false;
        editingFollowUpId = null;
        document.getElementById('followUpModalTitle').textContent = 'Schedule Follow-up';
        followUpForm.reset();

        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        tomorrow.setHours(9, 0, 0, 0);
        followUpForm.querySelector('[name="due_date"]').value = tomorrow.toISOString().slice(0, 16);

        followUpModal.show();
    };

    window.editFollowUp = function(followUpId) {
        isEditing = true;
        editingFollowUpId = followUpUpId;
        document.getElementById('followUpModalTitle').textContent = 'Edit Follow-up';

        fetch(`/admin/follow-ups/${followUpId}`)
            .then(response => response.json())
            .then(followUp => {
                followUpForm.querySelector('[name="title"]').value = followUp.title || '';
                followUpForm.querySelector('[name="description"]').value = followUp.description || '';
                followUpForm.querySelector('[name="customer_id"]').value = followUp.customer_id || '';
                followUpForm.querySelector('[name="lead_id"]').value = followUp.lead_id || '';
                followUpForm.querySelector('[name="due_date"]').value = followUp.due_date ? followUp.due_date.slice(0, 16) : '';
                followUpForm.querySelector('[name="user_id"]').value = followUp.user_id || '';
                followUpModal.show();
            })
            .catch(() => alert('Error loading data'));
    };

    window.toggleComplete = function(followUpId) {
        fetch(`/admin/follow-ups/${followUpId}/toggle`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            },
        })
        .then(r => r.json())
        .then(data => data.success ? location.reload() : alert('Error'))
        .catch(() => alert('Error'));
    };

    window.deleteFollowUp = function(followUpId, title) {
        if (confirm(`Delete "${title}"?`)) {
            fetch(`/admin/follow-ups/${followUpId}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
            })
            .then(r => r.json())
            .then(data => data.success ? location.reload() : alert('Error'))
            .catch(() => alert('Error'));
        }
    };

    // Form handler
    followUpForm.onsubmit = function(e) {
        e.preventDefault();
        const formData = new FormData(followUpForm);
        const url = isEditing ? `/admin/follow-ups/${editingFollowUpId}` : '/admin/follow-ups';
        const method = isEditing ? 'PATCH' : 'POST';

        fetch(url, {
            method,
            body: formData,
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                followUpModal.hide();
                location.reload();
            } else {
                alert('Validation error');
            }
        })
        .catch(() => alert('Error'));
    };
});
</script>
@endsection