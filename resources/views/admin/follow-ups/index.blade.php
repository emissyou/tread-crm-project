@extends('layouts.app')
@section('title', 'Follow-ups - Tread CRM')
@section('page_title', 'Follow-ups')
@section('page_subtitle', 'Manage scheduled follow-ups and reminders for customers and leads.')

@push('styles')
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
    /* ── Font: same as Customer & Lead pages ── */
    #followup-page, #followup-page *:not(i) {
        font-family: 'DM Sans', sans-serif !important;
    }

    /* ── Stats grid: 2-col mobile → 4-col desktop ── */
    #followup-page .stats-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.75rem;
        margin-bottom: 1.5rem;
    }
    @media (min-width: 768px) {
        #followup-page .stats-grid {
            grid-template-columns: repeat(4, 1fr);
        }
    }

    /* ── Avatar circles scoped to page ── */
    #followup-page .avatar-circle {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 44px;
        height: 44px;
        border-radius: 12px;
        font-size: 0.9rem;
        font-weight: 800;
        color: #fff !important;
        flex-shrink: 0;
    }
    #followup-page .avatar-circle.bg-primary  { background: linear-gradient(135deg,#3b82f6,#2563eb) !important; }
    #followup-page .avatar-circle.bg-warning  { background: linear-gradient(135deg,#f59e0b,#d97706) !important; }
    #followup-page .avatar-circle.bg-danger   { background: linear-gradient(135deg,#ef4444,#dc2626) !important; }
    #followup-page .avatar-circle.bg-success  { background: linear-gradient(135deg,#10b981,#059669) !important; }
    #followup-page .avatar-circle.bg-secondary{ background: linear-gradient(135deg,#6b7280,#4b5563) !important; }

    /* Small avatar for assigned user in table */
    #followup-page .avatar-circle-small {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        border-radius: 8px;
        font-size: 0.72rem;
        font-weight: 700;
        color: #fff !important;
        flex-shrink: 0;
        background: linear-gradient(135deg,#6b7280,#4b5563);
    }

    /* ── Filter form responsive ── */
    #followup-page .filter-row {
        display: grid;
        grid-template-columns: 1fr;
        gap: 0.75rem;
    }
    @media (min-width: 576px) {
        #followup-page .filter-row { grid-template-columns: repeat(2, 1fr); }
    }
    @media (min-width: 992px) {
        #followup-page .filter-row { grid-template-columns: 2fr 1fr 1fr 1fr; align-items: end; }
    }

    /* ── Table: horizontally scrollable on small screens ── */
    #followup-page .crm-table-responsive {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    #followup-page .crm-table {
        min-width: 620px;
    }

    /* ── Mobile card view for rows ── */
    @media (max-width: 575px) {
        #followup-page .crm-table thead { display: none; }
        #followup-page .crm-table,
        #followup-page .crm-table tbody,
        #followup-page .crm-table tr,
        #followup-page .crm-table td { display: block; width: 100%; }
        #followup-page .crm-table tr {
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            margin-bottom: 0.75rem;
            padding: 0.75rem;
            background: #fff;
            box-shadow: 0 1px 4px rgba(0,0,0,.06);
        }
        #followup-page .crm-table td {
            border: none;
            padding: 0.3rem 0;
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
            font-size: 0.82rem;
        }
        #followup-page .crm-table td::before {
            content: attr(data-label);
            font-weight: 600;
            font-size: 0.68rem;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: #9ca3af;
            min-width: 76px;
            padding-top: 2px;
            flex-shrink: 0;
        }
        /* Overdue row highlight fallback on mobile */
        #followup-page .crm-table tr.table-warning {
            border-color: #fcd34d;
            background: #fffbeb !important;
        }
    }

    /* ── Pagination ── */
    .followup-pagination-wrap {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 0.85rem 1rem;
        border-top: 1px solid #eef2ff;
    }
    /* Tailwind nav structure */
    .followup-pagination-wrap nav {
        display: flex;
        justify-content: center;
        width: 100%;
    }
    .followup-pagination-wrap nav > div {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-wrap: wrap;
        gap: 0.3rem;
        width: 100%;
    }
    .followup-pagination-wrap nav span,
    .followup-pagination-wrap nav a {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        min-width: 36px !important;
        height: 36px !important;
        padding: 0 0.55rem !important;
        border-radius: 8px !important;
        border: 1px solid #e5e7eb !important;
        background: #fff !important;
        color: #374151 !important;
        font-size: 0.85rem !important;
        font-weight: 500 !important;
        text-decoration: none !important;
        transition: all .15s !important;
        line-height: 1 !important;
    }
    .followup-pagination-wrap nav a:hover {
        background: #f3f4f6 !important;
        border-color: #d1d5db !important;
        color: #111827 !important;
    }
    .followup-pagination-wrap nav span[aria-current="page"] > span {
        background: #2563eb !important;
        border-color: #2563eb !important;
        color: #fff !important;
        box-shadow: 0 2px 6px rgba(37,99,235,.3) !important;
        border-radius: 8px !important;
        min-width: 36px !important;
        height: 36px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        padding: 0 0.55rem !important;
    }
    .followup-pagination-wrap nav span[aria-disabled="true"] > span {
        background: #f9fafb !important;
        border-color: #e5e7eb !important;
        color: #c0c5ce !important;
        cursor: not-allowed !important;
    }
    .followup-pagination-wrap nav span:not([aria-current]):not([aria-label]):not([aria-disabled]) {
        border-color: transparent !important;
        background: transparent !important;
        cursor: default !important;
        color: #9ca3af !important;
    }
    /* Bootstrap 5 ul.pagination structure */
    .followup-pagination-wrap .pagination {
        display: flex !important;
        flex-wrap: wrap !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 0.3rem !important;
        margin: 0 !important;
        padding: 0 !important;
        list-style: none !important;
        width: 100% !important;
    }
    .followup-pagination-wrap .pagination .page-item { list-style: none !important; }
    .followup-pagination-wrap .pagination .page-link {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        min-width: 36px !important;
        height: 36px !important;
        padding: 0 0.55rem !important;
        border-radius: 8px !important;
        border: 1px solid #e5e7eb !important;
        background: #fff !important;
        color: #374151 !important;
        font-size: 0.85rem !important;
        font-weight: 500 !important;
        text-decoration: none !important;
        transition: all .15s !important;
        line-height: 1 !important;
        box-shadow: none !important;
    }
    .followup-pagination-wrap .pagination .page-link:hover {
        background: #f3f4f6 !important;
        border-color: #d1d5db !important;
        color: #111827 !important;
        z-index: auto !important;
    }
    .followup-pagination-wrap .pagination .page-item.active .page-link {
        background: #2563eb !important;
        border-color: #2563eb !important;
        color: #fff !important;
        box-shadow: 0 2px 6px rgba(37,99,235,.3) !important;
    }
    .followup-pagination-wrap .pagination .page-item.disabled .page-link {
        background: #f9fafb !important;
        border-color: #e5e7eb !important;
        color: #c0c5ce !important;
        cursor: not-allowed !important;
        pointer-events: none !important;
    }
    /* Cap SVG arrow size for both structures */
    .followup-pagination-wrap svg {
        width: 14px !important;
        height: 14px !important;
        display: block !important;
        flex-shrink: 0 !important;
        pointer-events: none;
    }
    @media (max-width: 480px) {
        .followup-pagination-wrap nav span,
        .followup-pagination-wrap nav a,
        .followup-pagination-wrap .pagination .page-link {
            min-width: 30px !important;
            height: 30px !important;
            font-size: 0.78rem !important;
            border-radius: 6px !important;
        }
        .followup-pagination-wrap nav span[aria-current="page"] > span {
            min-width: 30px !important;
            height: 30px !important;
            border-radius: 6px !important;
        }
        .followup-pagination-wrap svg {
            width: 12px !important;
            height: 12px !important;
        }
    }

    /* ── Page header responsive ── */
    @media (max-width: 575px) {
        #followup-page .page-header { flex-direction: column; gap: 0.75rem; }
        #followup-page .page-header .btn-crm-primary { width: 100%; justify-content: center; }
    }
</style>
@endpush

@section('content')
<div id="followup-page">
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
<div class="stats-grid">
    <div>
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
    <div>
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
    <div>
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
    <div>
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
        <form method="GET" class="filter-row">
            <div>
                <label class="crm-label">Search</label>
                <input type="text" class="crm-input" name="search" value="{{ request('search') }}" placeholder="Search follow-ups...">
            </div>
            <div>
                <label class="crm-label">Status</label>
                <select class="crm-input" name="status">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="overdue" {{ request('status') === 'overdue' ? 'selected' : '' }}>Overdue</option>
                </select>
            </div>
           <div>
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
            <div>
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
                        <td data-label="Title">
                            <div>
                                <div class="fw-semibold">{{ $followUp->title }}</div>
                                @if($followUp->description)
                                <div class="text-muted small">{{ Str::limit($followUp->description, 50) }}</div>
                                @endif
                            </div>
                        </td>
                        <td data-label="Related To">
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
                        <td data-label="Due Date">
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
                        <td data-label="Status">
                            <span class="badge-crm badge-{{ $followUp->status_badge }}">
                                {{ ucfirst($followUp->status) }}
                            </span>
                        </td>
                       <td data-label="Assigned">
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
                        <td data-label="Actions">
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
        <div class="followup-pagination-wrap">
            {{ $followUps->onEachSide(1)->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
        <div class="text-center text-muted py-2" style="font-size:0.78rem">
            Showing {{ $followUps->firstItem() }}–{{ $followUps->lastItem() }} of {{ $followUps->total() }} results
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
                        <div>
                            <label class="crm-label">Related Customer</label>
                            <select class="crm-input" name="customer_id" id="customerSelect">
                                <option value="">Select Customer (Optional)</option>
                                @foreach($customers ?? [] as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->full_name }} ({{ $customer->email }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="crm-label">Related Lead</label>
                            <select class="crm-input" name="lead_id" id="leadSelect">
                                <option value="">Select Lead (Optional)</option>
                                @foreach($leads ?? [] as $lead)
                                <option value="{{ $lead->id }}">{{ $lead->name }} ({{ $lead->email }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="crm-label">Due Date & Time *</label>
                            <input type="datetime-local" class="crm-input" name="due_date" required>
                        </div>
                        <div>
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
</div>{{-- #followup-page --}}
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {

    console.log('🎉 Follow-up script starting...');

    const modalElement = document.getElementById('followUpModal');
    if (!modalElement) {
        console.error('❌ Modal not found!');
        return;
    }

    const followUpModal = new bootstrap.Modal(modalElement);
    const followUpForm = document.getElementById('followUpForm');

    let isEditing = false;
    let editingFollowUpId = null;

    function createFollowUp() {
        console.log('✅ createFollowUp() called');
        isEditing = false;
        editingFollowUpId = null;

        document.getElementById('followUpModalTitle').textContent = 'Schedule Follow-up';
        followUpForm.reset();

        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        tomorrow.setHours(9, 0, 0, 0);
        const dueDateInput = followUpForm.querySelector('[name="due_date"]');
        if (dueDateInput) dueDateInput.value = tomorrow.toISOString().slice(0, 16);

        followUpModal.show();
    }

    function editFollowUp(followUpId) {
        console.log('✅ editFollowUp called:', followUpId);
        isEditing = true;
        editingFollowUpId = followUpId;

        document.getElementById('followUpModalTitle').textContent = 'Edit Follow-up';

        fetch(`/admin/follow-ups/${followUpId}`)
            .then(r => r.json())
            .then(data => {
                followUpForm.querySelector('[name="title"]').value = data.title || '';
                followUpForm.querySelector('[name="description"]').value = data.description || '';
                followUpForm.querySelector('[name="customer_id"]').value = data.customer_id || '';
                followUpForm.querySelector('[name="lead_id"]').value = data.lead_id || '';
                followUpForm.querySelector('[name="user_id"]').value = data.user_id || '';

                const dueInput = followUpForm.querySelector('[name="due_date"]');
                if (data.due_date && dueInput) dueInput.value = data.due_date.slice(0, 16);

                followUpModal.show();
            })
            .catch(err => console.error(err));
    }

    function toggleComplete(followUpId) {
        if (!confirm('Mark complete?')) return;
        fetch(`/admin/follow-ups/${followUpId}/toggle`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(r => r.json())
        .then(data => data.success ? location.reload() : alert('Error'))
        .catch(() => alert('Network error'));
    }

    function deleteFollowUp(followUpId, title) {
        if (!confirm(`Delete "${title}"?`)) return;
        fetch(`/admin/follow-ups/${followUpId}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
        })
        .then(r => r.json())
        .then(data => data.success ? location.reload() : alert('Error'))
        .catch(() => alert('Network error'));
    }

    window.createFollowUp = createFollowUp;
    window.editFollowUp = editFollowUp;
    window.toggleComplete = toggleComplete;
    window.deleteFollowUp = deleteFollowUp;

    // Click for create button
    document.addEventListener('click', function(e) {
        if (e.target.closest('[data-action="create-followup"]')) {
            e.preventDefault();
            createFollowUp();
        }
    });

    // ====================== FIXED FORM SUBMIT (JSON) ======================
    if (followUpForm) {
        followUpForm.addEventListener('submit', function(e) {
            e.preventDefault();

            // Collect form data manually
            const data = {
                title: followUpForm.querySelector('[name="title"]').value.trim(),
                description: followUpForm.querySelector('[name="description"]').value.trim(),
                customer_id: followUpForm.querySelector('[name="customer_id"]').value || null,
                lead_id: followUpForm.querySelector('[name="lead_id"]').value || null,
                due_date: followUpForm.querySelector('[name="due_date"]').value,
                user_id: followUpForm.querySelector('[name="user_id"]').value || null,
            };

            const url = isEditing ? `/admin/follow-ups/${editingFollowUpId}` : '/admin/follow-ups';
            const method = isEditing ? 'PATCH' : 'POST';

            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(data)
            })
            .then(response => {
                if (response.status === 419) {
                    throw new Error('CSRF Token Mismatch - Please refresh the page and try again.');
                }
                if (!response.ok) {
                    return response.text().then(text => { throw new Error(text); });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    followUpModal.hide();
                    location.reload();
                } else {
                    let msg = 'Validation error';
                    if (data.errors) {
                        msg = Object.values(data.errors).flat().join('\n');
                    }
                    alert(msg);
                }
            })
            .catch(err => {
                console.error('Submit error:', err);
                alert(err.message || 'Failed to save follow-up. Please refresh the page.');
            });
        });
    }

    console.log('✅ Follow-up JS fully loaded and ready');
});
</script>
@endpush