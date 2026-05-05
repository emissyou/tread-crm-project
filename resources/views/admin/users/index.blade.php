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