@extends('layouts.app')

@section('title', 'Archived Users - Tread CRM')
@section('page_title', 'Archived Users')
@section('page_subtitle', 'View and restore archived user accounts.')

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