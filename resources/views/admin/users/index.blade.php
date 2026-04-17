@extends('layouts.app')
@section('title', 'Users - Tread CRM')
@section('page_title', 'Users')
@section('page_subtitle', 'Manage user accounts, roles and access permissions.')

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
                            <span class="badge-crm badge-success">
                                Active
                            </span>
                        </td>
                        <td>{{ $user->created_at->format('M d, Y') }}</td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    @if(auth()->user()->isAdmin())
                                    <li><a class="dropdown-item" href="#" onclick="editUser({{ $user->id }}); return false"><i class="fas fa-edit"></i> Edit</a></li>
                                    @if($user->id !== auth()->id())
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger" href="#" onclick="deleteUser({{ $user->id }}, '{{ $user->name }}'); return false"><i class="fas fa-trash"></i> Delete</a></li>
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

<!-- Create/Edit User Modal -->
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
                            <label class="crm-label">Name</label>
                            <input type="text" class="crm-input" name="name" required>
                        </div>
                        <div class="col-12">
                            <label class="crm-label">Email</label>
                            <input type="email" class="crm-input" name="email" required>
                        </div>
                        <div class="col-12" id="passwordField">
                            <label class="crm-label">Password</label>
                            <input type="password" class="crm-input" name="password" required>
                        </div>
                        <div class="col-12">
                            <label class="crm-label">Role</label>
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

@endsection

@section('scripts')
<script>
let userModal = new bootstrap.Modal(document.getElementById('userModal'));
let userForm = document.getElementById('userForm');
let isEditing = false;
let editingUserId = null;

function createUser() {
    isEditing = false;
    editingUserId = null;
    document.getElementById('userModalTitle').textContent = 'Add User';
    document.getElementById('passwordField').style.display = 'block';
    document.querySelector('[name="password"]').required = true;
    userForm.reset();
    userModal.show();
}

function editUser(userId) {
    isEditing = true;
    editingUserId = userId;
    document.getElementById('userModalTitle').textContent = 'Edit User';
    document.getElementById('passwordField').style.display = 'none';
    document.querySelector('[name="password"]').required = false;

    fetch(`/admin/users/${userId}`)
        .then(response => response.json())
        .then(user => {
            document.querySelector('[name="name"]').value = user.name;
            document.querySelector('[name="email"]').value = user.email;
            document.querySelector('[name="role"]').value = user.role;
            document.querySelector('[name="phone"]').value = user.phone || '';
            userModal.show();
        });
}

function confirmDelete(userId) {
    if (confirm('Are you sure you want to delete this user?')) {
        fetch(`/admin/users/${userId}`, {
            method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}

function deleteUser(userId, userName) {
    if (confirm(`Are you sure you want to delete ${userName}?`)) {
        fetch(`/admin/users/${userId}`, {
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

userForm.addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(userForm);
    const url = isEditing ? `/admin/users/${editingUserId}` : '/admin/users';
    const method = isEditing ? 'PATCH' : 'POST';

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
            userModal.hide();
            location.reload();
        } else if (data.errors) {
            // Handle validation errors
            console.log(data.errors);
        }
    });
});
</script>
@endsection
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar-circle bg-warning">S</div>
                        <div>
                            <div class="fw-bold">Audit & activity</div>
                            <div class="text-muted small">Security and login overview</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="crm-card">
    <div class="crm-card-header">
        <h5 class="mb-0 fw-semibold">User roles and access</h5>
    </div>
    <div class="crm-card-body">
        <p class="text-muted mb-3">This page is ready to show your user table and role management controls. You can connect it to user data next.</p>
        <div class="table-responsive">
            <table class="crm-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Admin User</td>
                        <td>admin@example.com</td>
                        <td>Administrator</td>
                        <td><span class="badge-crm badge-success">Active</span></td>
                    </tr>
                    <tr>
                        <td>Sales Manager</td>
                        <td>manager@example.com</td>
                        <td>Manager</td>
                        <td><span class="badge-crm badge-primary">Active</span></td>
                    </tr>
                    <tr>
                        <td>Support</td>
                        <td>support@example.com</td>
                        <td>Viewer</td>
                        <td><span class="badge-crm badge-warning">Pending</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
