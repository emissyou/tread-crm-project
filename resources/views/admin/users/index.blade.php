@extends('layouts.app')
@section('title', 'Users - Tread CRM')
@section('page_title', 'Users')
@section('page_subtitle', 'Manage user accounts, roles and access permissions.')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">

<style>
/* ── Corporate Font System: DM Sans ── */
:root {
    --font-sans: 'DM Sans', ui-sans-serif, system-ui, sans-serif;
    --font-mono: 'DM Mono', ui-monospace, monospace;
    --usr-blue:    #1e6fff;
    --usr-blue-lt: #e8f0fe;
    --usr-border:  #e2e8f0;
    --usr-surface: #f8fafc;
    --usr-text:    #1a202c;
    --usr-muted:   #64748b;
    --usr-radius:  10px;
}

/* Apply to everything */
body, h1, h2, h3, h4, h5, h6,
p, span, small, label, a,
input, select, textarea, button,
th, td, li,
.crm-card, .crm-table, .crm-label, .crm-input,
.modal-content, .modal-title, .modal-body,
.dropdown-menu, .dropdown-item,
.btn, .btn-crm-primary, .badge, .badge-crm {
    font-family: var(--font-sans) !important;
}

/* Page Header */
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
.page-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    flex-shrink: 0;
}

/* Stats grid override to CSS Grid */
.row.g-3.mb-4 {
    display: grid !important;
    grid-template-columns: repeat(4, 1fr) !important;
    gap: 14px !important;
    margin-bottom: 24px !important;
}
.row.g-3.mb-4 > [class*="col"] {
    width: 100% !important;
    max-width: 100% !important;
    flex: unset !important;
    padding: 0 !important;
}
.row.g-3.mb-4 .crm-card {
    border-radius: var(--usr-radius);
    border: 1px solid var(--usr-border);
    background: #fff;
    transition: box-shadow 0.18s, border-color 0.18s;
}
.row.g-3.mb-4 .crm-card:hover {
    border-color: var(--usr-blue);
    box-shadow: 0 0 0 3px var(--usr-blue-lt);
}

/* Avatar circle */
.avatar-circle {
    width: 44px !important;
    height: 44px !important;
    border-radius: 10px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    font-size: 0.9rem !important;
    font-weight: 700 !important;
    color: #fff !important;
    flex-shrink: 0;
    font-family: var(--font-sans) !important;
}

/* Stat values */
.fw-bold {
    font-size: 1.05rem;
    font-weight: 700 !important;
    color: var(--usr-text);
    letter-spacing: -0.01em;
}
.text-muted.small {
    font-size: 0.72rem !important;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    color: var(--usr-muted) !important;
}

/* Table card */
.crm-card {
    border-radius: var(--usr-radius);
    border: 1px solid var(--usr-border);
    background: #fff;
    overflow: hidden;
}
.crm-card-header {
    padding: 18px 22px;
    border-bottom: 1px solid var(--usr-border);
}
.crm-card-header h5 {
    font-size: 1rem;
    font-weight: 700;
    color: var(--usr-text);
    letter-spacing: -0.01em;
    margin: 0 0 2px !important;
}
.crm-card-header p {
    font-size: 0.8rem;
    color: var(--usr-muted);
    margin: 0 !important;
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
.crm-table .fw-semibold {
    font-size: 0.875rem;
    font-weight: 600 !important;
    color: var(--usr-text);
}
.crm-table .text-muted.small {
    font-size: 0.78rem !important;
    color: var(--usr-muted) !important;
    text-transform: none;
    letter-spacing: 0;
    font-weight: 400;
}
.crm-table tbody td:nth-child(4) {
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
.badge-success  { background: #dcfce7; color: #16a34a; }
.badge-secondary{ background: #f1f5f9; color: #64748b; }

/* Dropdown */
.dropdown-menu {
    border: 1px solid var(--usr-border) !important;
    box-shadow: 0 8px 24px rgba(0,0,0,0.1) !important;
    border-radius: 9px !important;
    padding: 6px !important;
    font-size: 0.85rem !important;
}
.dropdown-item {
    border-radius: 6px !important;
    padding: 8px 12px !important;
    font-weight: 500 !important;
    display: flex !important;
    align-items: center;
    gap: 8px;
    color: var(--usr-text) !important;
    transition: background 0.12s;
}
.dropdown-item:hover { background: var(--usr-surface) !important; }
.dropdown-item.text-danger { color: #dc2626 !important; }

/* Labels & Inputs */
.crm-label {
    display: block;
    font-size: 0.72rem !important;
    font-weight: 700 !important;
    color: var(--usr-muted) !important;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 6px;
}
.crm-input {
    width: 100%;
    padding: 9px 12px !important;
    border: 1px solid var(--usr-border) !important;
    border-radius: 7px !important;
    font-size: 0.875rem !important;
    color: var(--usr-text) !important;
    background: #fff !important;
    transition: border-color 0.15s, box-shadow 0.15s;
    appearance: none;
    -webkit-appearance: none;
    line-height: 1.5;
}
.crm-input:focus {
    outline: none !important;
    border-color: var(--usr-blue) !important;
    box-shadow: 0 0 0 3px var(--usr-blue-lt) !important;
}

/* Buttons */
.btn-crm-primary {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    background: var(--usr-blue) !important;
    color: #fff !important;
    border: none !important;
    border-radius: 8px !important;
    padding: 9px 18px !important;
    font-size: 0.875rem !important;
    font-weight: 600 !important;
    cursor: pointer;
    white-space: nowrap;
    transition: background 0.15s, transform 0.1s;
    text-decoration: none;
}
.btn-crm-primary:hover {
    background: #1557d6 !important;
    transform: translateY(-1px);
    color: #fff !important;
}
.btn.btn-secondary {
    border-radius: 8px !important;
    font-size: 0.875rem !important;
    font-weight: 600 !important;
    padding: 9px 16px !important;
}

/* Modal */
.modal-content {
    border-radius: 12px !important;
    border: none !important;
    box-shadow: 0 20px 60px rgba(0,0,0,0.15) !important;
}
.modal-header {
    padding: 18px 22px !important;
    border-bottom: 1px solid var(--usr-border) !important;
}
.modal-title {
    font-size: 1rem !important;
    font-weight: 700 !important;
    color: var(--usr-text) !important;
    letter-spacing: -0.01em;
}
.modal-body  { padding: 22px !important; }
.modal-footer {
    padding: 14px 22px !important;
    border-top: 1px solid var(--usr-border) !important;
    gap: 10px;
}

/* Responsive */
@media (max-width: 1024px) {
    .row.g-3.mb-4 { grid-template-columns: repeat(2, 1fr) !important; }
}
@media (max-width: 640px) {
    .row.g-3.mb-4 { grid-template-columns: repeat(2, 1fr) !important; gap: 10px !important; }
    .page-header { flex-direction: column; }
    .page-actions { width: 100%; }
    .crm-table thead th:nth-child(4),
    .crm-table tbody td:nth-child(4) { display: none; }
}
</style>
@endpush

@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h1 class="page-title"><i class="fas fa-users me-2 text-primary"></i>Users</h1>
        <p class="page-subtitle">Manage user accounts, roles and access permissions.</p>
    </div>
    <div class="page-actions">
        <button type="button" class="btn-crm-primary" onclick="createUser()">
            <i class="fas fa-user-plus"></i> Add User
        </button>
        
        <a href="{{ route('admin.users.archived') }}" class="btn btn-secondary">
            <i class="fas fa-archive me-1"></i> 
            Archived Users
            @if(isset($archivedCount) && $archivedCount > 0)
                <span class="badge bg-danger ms-1">{{ $archivedCount }}</span>
            @endif
        </a>
    </div>
</div>

<!-- Stats Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="crm-card p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="avatar-circle bg-primary">{{ $stats['total'] }}</div>
                <div>
                    <div class="fw-bold">{{ $stats['total'] }} Total Users</div>
                    <div class="text-muted small">All registered users</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="crm-card p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="avatar-circle bg-success">{{ $stats['active'] }}</div>
                <div>
                    <div class="fw-bold">{{ $stats['active'] }} Active</div>
                    <div class="text-muted small">Currently active users</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="crm-card p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="avatar-circle bg-warning">{{ $stats['admin'] }}</div>
                <div>
                    <div class="fw-bold">{{ $stats['admin'] }} Admins</div>
                    <div class="text-muted small">Full system access</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="crm-card p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="avatar-circle bg-info">{{ $stats['manager'] + $stats['sales_staff'] }}</div>
                <div>
                    <div class="fw-bold">{{ $stats['manager'] + $stats['sales_staff'] }} Staff</div>
                    <div class="text-muted small">Managers & Sales staff</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Users Table -->
<div class="crm-card">
    <div class="crm-card-header">
        <div>
            <h5 class="mb-1 fw-semibold">User Management</h5>
            <p class="mb-0 text-muted">View and manage all user accounts and their permissions.</p>
        </div>
    </div>
    <div class="crm-card-body">
        <div class="crm-table-responsive">
            <table class="crm-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar-circle bg-primary">
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
                        <td>
                            @if($user->trashed())
                                <span class="badge-crm badge-secondary">Archived</span>
                            @else
                                <span class="badge-crm badge-success">Active</span>
                            @endif
                        </td>
                        <td>{{ $user->created_at->format('M d, Y') }}</td>
                        
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="#" onclick="viewUser({{ $user->id }}); return false">
                                            <i class="fas fa-eye"></i> View Details
                                        </a>
                                    </li>
                                    
                                    @if(auth()->user()->isAdmin())
                                        <li>
                                            <a class="dropdown-item" href="#" onclick="editUser({{ $user->id }}); return false">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                        </li>
                                        
                                        @if($user->id !== auth()->id() && !$user->trashed())
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <a class="dropdown-item text-danger" href="#" 
                                                onclick="archiveUser({{ $user->id }}, '{{ addslashes($user->name) }}'); return false">
                                                    <i class="fas fa-archive"></i> Archive
                                                </a>
                                            </li>
                                        @endif
                                    @endif
                                </ul>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- User Modal (Create / Edit) -->
<div class="modal fade" id="userModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content crm-modal">
            <div class="modal-header">
                <h5 class="modal-title" id="userModalTitle">Add User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="userForm">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="crm-label">Name *</label>
                            <input type="text" class="crm-input" name="name" required>
                        </div>
                        <div class="col-12">
                            <label class="crm-label">Email *</label>
                            <input type="email" class="crm-input" name="email" required>
                        </div>
                        <div class="col-12" id="passwordField">
                            <label class="crm-label">Password *</label>
                            <input type="password" class="crm-input" name="password" required>
                        </div>
                        <div class="col-12">
                            <label class="crm-label">Role *</label>
                            <select class="crm-input" name="role" required>
                                <option value="sales_staff">Sales Staff</option>
                                <option value="manager">Manager</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="crm-label">Phone</label>
                            <input type="text" class="crm-input" name="phone">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-crm-primary">Save User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View User Detail Modal -->
<div class="modal fade" id="viewUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content crm-modal">
            <div class="modal-header">
                <h5 class="modal-title">User Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="userDetailContent">
                <!-- Filled by JavaScript -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {

    const userModal = new bootstrap.Modal(document.getElementById('userModal'));
    const viewUserModal = new bootstrap.Modal(document.getElementById('viewUserModal'));
    const userForm = document.getElementById('userForm');

    let isEditing = false;
    let editingUserId = null;

    function showToast(message, type = 'success') {
        // Simple toast function (same as before)
        const toast = document.createElement('div');
        toast.style.position = 'fixed';
        toast.style.top = '20px';
        toast.style.right = '20px';
        toast.style.zIndex = '9999';
        toast.style.padding = '12px 20px';
        toast.style.borderRadius = '8px';
        toast.style.color = '#fff';
        toast.style.backgroundColor = type === 'success' ? '#10b981' : '#ef4444';
        toast.textContent = message;
        document.body.appendChild(toast);

        setTimeout(() => toast.remove(), 3000);
    }

    function createUser() {
        isEditing = false;
        editingUserId = null;
        document.getElementById('userModalTitle').textContent = 'Add New User';
        document.getElementById('passwordField').style.display = 'block';
        userForm.querySelector('[name="password"]').required = true;
        userForm.reset();
        userModal.show();
    }

    function editUser(userId) {
        isEditing = true;
        editingUserId = userId;
        document.getElementById('userModalTitle').textContent = 'Edit User';
        document.getElementById('passwordField').style.display = 'none';
        userForm.querySelector('[name="password"]').required = false;

        fetch(`/admin/users/${userId}`)
            .then(r => r.json())
            .then(user => {
                userForm.querySelector('[name="name"]').value = user.name;
                userForm.querySelector('[name="email"]').value = user.email;
                userForm.querySelector('[name="role"]').value = user.role;
                userForm.querySelector('[name="phone"]').value = user.phone || '';
                userModal.show();
            })
            .catch(() => showToast('Failed to load user', 'danger'));
    }

    function viewUser(userId) {
        fetch(`/admin/users/${userId}`)
            .then(r => r.json())
            .then(user => {
                let html = `
                    <div class="text-center mb-4">
                        <div class="avatar-circle bg-primary mx-auto" style="width: 80px; height: 80px; font-size: 28px;">
                            ${user.name.charAt(0).toUpperCase()}
                        </div>
                        <h5 class="mt-3">${user.name}</h5>
                        <p class="text-muted">${user.email}</p>
                    </div>
                    <table class="table table-borderless">
                        <tr><td><strong>Role</strong></td><td>${user.role ? user.role.replace('_', ' ').toUpperCase() : 'N/A'}</td></tr>
                        <tr><td><strong>Phone</strong></td><td>${user.phone || 'Not provided'}</td></tr>
                        <tr><td><strong>Joined</strong></td><td>${user.created_at ? new Date(user.created_at).toLocaleDateString('en-US', {year:'numeric', month:'long', day:'numeric'}) : 'N/A'}</td></tr>
                    </table>
                `;
                document.getElementById('userDetailContent').innerHTML = html;
                viewUserModal.show();
            })
            .catch(() => showToast('Failed to load user details', 'danger'));
    }

  function archiveUser(userId, userName) {
        if (!confirm(`Archive user "${userName}"?`)) return;

        const url = `/admin/users/${userId}/archive`;

        console.log('=== ARCHIVE DEBUG ===');
        console.log('Calling URL:', url);
        console.log('User ID:', userId);

        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            console.log('Status Code:', response.status);
            console.log('Response URL:', response.url);

            if (!response.ok) {
                return response.text().then(text => {
                    console.error('Error Response Body:', text);
                    throw new Error(`HTTP ${response.status}`);
                });
            }
            return response.json();
        })
        .then(data => {
            console.log('Success Response:', data);
            if (data.success) {
                showToast('User archived successfully', 'success');
                setTimeout(() => window.location.href = '{{ route('admin.users.archived') }}', 800);
            } else {
                showToast(data.error || 'Failed to archive user', 'danger');
            }
        })
        .catch(error => {
            console.error('Fetch Error:', error);
            showToast(`Failed to archive user (Status: ${error.message})`, 'danger');
        });
    }

    window.createUser = createUser;
    window.editUser = editUser;
    window.viewUser = viewUser;
    window.archiveUser = archiveUser;

    // Form Submit
    userForm.addEventListener('submit', function(e) {
        e.preventDefault();

        // Client-side validation
        const name = userForm.querySelector('[name="name"]').value.trim();
        const email = userForm.querySelector('[name="email"]').value.trim();
        const role = userForm.querySelector('[name="role"]').value;
        const password = userForm.querySelector('[name="password"]').value;
        const phone = userForm.querySelector('[name="phone"]').value.trim();

        if (!name) {
            showToast('Name is required', 'danger');
            return;
        }

        if (!email) {
            showToast('Email is required', 'danger');
            return;
        }

        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            showToast('Please enter a valid email address', 'danger');
            return;
        }

        if (!role) {
            showToast('Role is required', 'danger');
            return;
        }

        if (!isEditing && password.length < 8) {
            showToast('Password must be at least 8 characters long', 'danger');
            return;
        }

        if (isEditing && password && password.length > 0 && password.length < 8) {
            showToast('Password must be at least 8 characters long', 'danger');
            return;
        }

        const data = {
            name: name,
            email: email,
            role: role,
            phone: phone,
        };

        if (!isEditing) {
            data.password = password;
        } else if (password.trim() !== '') {
            data.password = password;
        }

        const url = isEditing ? `/admin/users/${editingUserId}` : '/admin/users';
        const method = isEditing ? 'PATCH' : 'POST';

        fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => { throw err; });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                userModal.hide();
                showToast(data.message, 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast(data.error || 'Failed to save user', 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if (error.errors) {
                // Handle validation errors
                let errorMessages = [];
                for (let field in error.errors) {
                    errorMessages.push(error.errors[field].join(', '));
                }
                showToast('Validation errors: ' + errorMessages.join('; '), 'danger');
            } else {
                showToast(error.message || 'An error occurred', 'danger');
            }
        });
    });

});
</script>
@endpush