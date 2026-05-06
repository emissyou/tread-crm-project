@extends('layouts.app')

@section('title', 'Archived Users - Tread CRM')
@section('page_title', 'Archived Users')
@section('page_subtitle', 'View and restore archived user accounts.')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap" rel="stylesheet">

<style>
:root {
    --font-sans:   'DM Sans', ui-sans-serif, system-ui, sans-serif;
    --usr-blue:    #1e6fff;
    --usr-blue-lt: #e8f0fe;
    --usr-border:  #e2e8f0;
    --usr-surface: #f8fafc;
    --usr-text:    #1a202c;
    --usr-muted:   #64748b;
    --usr-radius:  10px;
}

/* Apply DM Sans everywhere */
body, h1, h2, h3, h4, h5, h6,
p, span, small, label, a,
input, select, textarea, button,
th, td, li,
.crm-card, .crm-table, .crm-label, .crm-input,
.modal-content, .dropdown-menu, .dropdown-item,
.btn, .badge, .badge-crm {
    font-family: var(--font-sans) !important;
}

/* Page header */
.page-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 24px;
}
.page-title {
    font-size: clamp(1.2rem, 2.5vw, 1.55rem);
    font-weight: 700;
    letter-spacing: -0.02em;
    color: var(--usr-text);
    margin: 0;
}
.page-subtitle {
    font-size: 0.875rem;
    color: var(--usr-muted);
    margin: 4px 0 0;
    font-weight: 400;
}
.page-actions { flex-shrink: 0; }

/* Card */
.crm-card {
    border-radius: var(--usr-radius);
    border: 1px solid var(--usr-border);
    background: #fff;
    overflow: hidden;
}
.crm-card-header {
    padding: 16px 22px;
    border-bottom: 1px solid var(--usr-border);
    display: flex;
    align-items: center;
    gap: 10px;
}
.crm-card-header h5 {
    font-size: 1rem !important;
    font-weight: 700 !important;
    color: var(--usr-text) !important;
    letter-spacing: -0.01em;
    margin: 0 !important;
    display: flex;
    align-items: center;
    gap: 8px;
}
.crm-card-header .badge {
    font-size: 0.72rem !important;
    font-weight: 700 !important;
    padding: 3px 9px !important;
    border-radius: 20px !important;
    font-family: var(--font-sans) !important;
}
.crm-card-body { padding: 0; }

/* Table */
.crm-table-responsive {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}
.crm-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.875rem;
}
.crm-table thead tr {
    background: var(--usr-surface);
    border-bottom: 1px solid var(--usr-border);
}
.crm-table thead th {
    padding: 11px 18px;
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    color: var(--usr-muted);
    white-space: nowrap;
    border: none;
}
.crm-table tbody tr {
    border-bottom: 1px solid var(--usr-border);
    transition: background 0.12s;
}
.crm-table tbody tr:last-child { border-bottom: none; }
.crm-table tbody tr:hover { background: var(--usr-surface); }
.crm-table tbody td {
    padding: 13px 18px;
    color: var(--usr-text);
    vertical-align: middle;
    border: none;
}

/* Avatar */
.avatar-circle {
    width: 38px !important;
    height: 38px !important;
    border-radius: 8px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    font-size: 0.875rem !important;
    font-weight: 700 !important;
    color: #fff !important;
    flex-shrink: 0;
    font-family: var(--font-sans) !important;
    opacity: 0.7;
}

/* User name / email */
.fw-semibold {
    font-size: 0.875rem;
    font-weight: 600 !important;
    color: var(--usr-text);
}
.text-muted.small {
    font-size: 0.78rem !important;
    color: var(--usr-muted) !important;
    font-weight: 400;
}

/* Archived date cell */
.crm-table tbody td:nth-child(3) {
    font-size: 0.82rem;
    color: var(--usr-muted);
    font-weight: 500;
}

/* Badges */
.badge-crm {
    display: inline-flex;
    align-items: center;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 0.72rem;
    font-weight: 700;
    letter-spacing: 0.04em;
    text-transform: capitalize;
    font-family: var(--font-sans) !important;
}
.badge-danger   { background: #fee2e2; color: #dc2626; }
.badge-warning  { background: #fef3c7; color: #d97706; }
.badge-info     { background: #dbeafe; color: #2563eb; }

/* Restore button */
.btn.btn-sm.btn-success {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px !important;
    font-size: 0.8rem !important;
    font-weight: 600 !important;
    border-radius: 7px !important;
    font-family: var(--font-sans) !important;
    transition: transform 0.1s, box-shadow 0.15s;
}
.btn.btn-sm.btn-success:hover {
    transform: translateY(-1px);
    box-shadow: 0 3px 10px rgba(22,163,74,0.25);
}

/* Back button */
.btn.btn-secondary {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 9px 16px !important;
    font-size: 0.875rem !important;
    font-weight: 600 !important;
    border-radius: 8px !important;
    font-family: var(--font-sans) !important;
}

/* Empty state */
.text-center.py-5.text-muted {
    font-size: 0.9rem;
    font-weight: 500;
    color: var(--usr-muted) !important;
    padding: 48px 20px !important;
}

/* Responsive */
@media (max-width: 640px) {
    .page-header { flex-direction: column; }
    .crm-table thead th:nth-child(3),
    .crm-table tbody td:nth-child(3) { display: none; }
}
</style>
@endpush

@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h1 class="page-title"><i class="fas fa-archive me-2 text-secondary"></i>Archived Users</h1>
        <p class="page-subtitle">Users that have been archived.</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Active Users
        </a>
    </div>
</div>

<div class="crm-card">
    <div class="crm-card-header">
        <h5 class="mb-1 fw-semibold">
            Archived Users
            <span class="badge bg-secondary ms-2">{{ $users->count() }}</span>
        </h5>
    </div>
    <div class="crm-card-body">
        <div class="crm-table-responsive">
            <table class="crm-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Role</th>
                        <th>Archived On</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar-circle bg-secondary">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="fw-semibold">{{ $user->name }}</div>
                                    <div class="text-muted small">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge-crm badge-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'manager' ? 'warning' : 'info') }}">
                                {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                            </span>
                        </td>
                        <td>{{ $user->deleted_at->format('M d, Y') }}</td>
                        <td>
                            <button class="btn btn-sm btn-success" 
                                    onclick="restoreUser({{ $user->id }}, '{{ addslashes($user->name) }}')">
                                <i class="fas fa-undo"></i> Restore
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            @if($users->isEmpty())
                <div class="text-center py-5 text-muted">
                    No archived users found.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function restoreUser(userId, userName) {
    if (!confirm(`Restore user "${userName}"?`)) return;

    fetch(`/admin/users/${userId}/restore`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json'
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            alert('User restored successfully!');
            location.reload();
        } else {
            alert(data.error || 'Failed to restore user');
        }
    })
    .catch(() => alert('Network error'));
}
</script>
@endpush