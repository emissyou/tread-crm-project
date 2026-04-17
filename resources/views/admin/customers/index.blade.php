@extends('layouts.app')

@section('title', 'Customers - Tread CRM')
@section('page_title', 'Customers')
@section('page_subtitle', 'Manage your customer database and relationships.')

@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h1 class="page-title"><i class="fas fa-users me-2 text-primary"></i>Customers</h1>
        <p class="page-subtitle">Manage your customer database and relationships.</p>
    </div>
    <div class="page-actions">
        <button type="button" class="btn-crm-primary" id="addCustomerBtn">
            <i class="fas fa-user-plus"></i> Add Customer
        </button>
        <a href="{{ route('admin.customers.export') }}" class="btn btn-outline-primary">
            <i class="fas fa-download"></i> Export
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
                    <div class="fw-bold">{{ $stats['total'] }} Total</div>
                    <div class="text-muted small">All records</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="crm-card p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="avatar-circle bg-success">{{ $stats['customer'] }}</div>
                <div>
                    <div class="fw-bold">{{ $stats['customer'] }} Customers</div>
                    <div class="text-muted small">Active customers</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="crm-card p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="avatar-circle bg-warning">{{ $stats['lead'] }}</div>
                <div>
                    <div class="fw-bold">{{ $stats['lead'] }} Leads</div>
                    <div class="text-muted small">Potential customers</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="crm-card p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="avatar-circle bg-info">{{ $stats['prospect'] }}</div>
                <div>
                    <div class="fw-bold">{{ $stats['prospect'] }} Prospects</div>
                    <div class="text-muted small">New prospects</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="crm-card mb-4">
    <div class="crm-card-body">
        <form method="GET" class="row g-3" id="filterForm">
            <div class="col-md-4">
                <label class="crm-label">Search</label>
                <input type="text" class="crm-input" name="search" value="{{ request('search') }}" placeholder="Search customers...">
            </div>
            <div class="col-md-2">
                <label class="crm-label">Status</label>
                <select class="crm-input" name="status">
                    <option value="">All Status</option>
                    <option value="customer" {{ request('status') === 'customer' ? 'selected' : '' }}>Customer</option>
                    <option value="lead" {{ request('status') === 'lead' ? 'selected' : '' }}>Lead</option>
                    <option value="prospect" {{ request('status') === 'prospect' ? 'selected' : '' }}>Prospect</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="crm-label">Assigned User</label>
                <select class="crm-input" name="assigned_user_id">
                    <option value="">All Users</option>
                    @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ request('assigned_user_id') == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="crm-label">&nbsp;</label>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn-crm-primary">
                        <i class="fas fa-search"></i> Filter
                    </button>
                    <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times"></i> Clear
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Customers Table -->
<div class="crm-card">
    <div class="crm-card-header">
        <h5 class="mb-1 fw-semibold">Customer Database</h5>
        <p class="mb-0 text-muted">View and manage all your customers and prospects.</p>
    </div>
    <div class="crm-card-body">
        <div class="crm-table-responsive">
            <table class="crm-table">
                <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Contact</th>
                        <th>Company</th>
                        <th>Status</th>
                        <th>Assigned</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $customer)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar-circle bg-primary">
                                    {{ $customer->initials }}
                                </div>
                                <div>
                                    <div class="fw-semibold">{{ $customer->full_name }}</div>
                                    <div class="text-muted small">#{{ $customer->id }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div>{{ $customer->email ?? '—' }}</div>
                            @if($customer->phone)
                                <div class="text-muted small">{{ $customer->phone }}</div>
                            @endif
                        </td>
                        <td>{{ $customer->company ?? '—' }}</td>
                        <td>
                            <span class="badge-crm badge-{{ $customer->status_badge }}">
                                {{ ucfirst($customer->status) }}
                            </span>
                        </td>
                        <td>
                            @if($customer->assignedUser)
                                <span class="small">{{ $customer->assignedUser->name }}</span>
                            @else
                                <span class="text-muted small">Unassigned</span>
                            @endif
                        </td>
                        <td>{{ $customer->created_at->format('M d, Y') }}</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="#" onclick="viewCustomer({{ $customer->id }}); return false"><i class="fas fa-eye"></i> View</a></li>
                                    @if(auth()->user()->canManageCustomersAndLeads() || (auth()->user()->isSalesStaff() && $customer->assigned_user_id === auth()->id()))
                                        <li><a class="dropdown-item" href="#" onclick="editCustomer({{ $customer->id }}); return false"><i class="fas fa-edit"></i> Edit</a></li>
                                    @endif
                                    @if(auth()->user()->canDeleteCustomers())
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item text-danger" href="#" onclick="deleteCustomer({{ $customer->id }}, '{{ $customer->full_name }}'); return false"><i class="fas fa-trash"></i> Delete</a></li>
                                    @endif
                                </ul>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="empty-state">
                                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                <h5>No customers found</h5>
                                <button type="button" class="btn-crm-primary" onclick="createCustomer()">
                                    <i class="fas fa-user-plus"></i> Add First Customer
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($customers->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $customers->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Customer Modal (Add / Edit) -->
<div class="modal fade" id="customerModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="customerModalTitle">Add Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="customerForm" onsubmit="return false;">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">First Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="first_name" id="field_first_name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Last Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="last_name" id="field_last_name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" name="email" id="field_email" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone</label>
                            <input type="text" class="form-control" name="phone" id="field_phone" placeholder="+63 9XX XXX XXXX">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Company</label>
                            <input type="text" class="form-control" name="company" id="field_company">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Address</label>
                            <textarea class="form-control" name="address" id="field_address" rows="2"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select" name="status" id="field_status" required>
                                <option value="prospect">Prospect</option>
                                <option value="lead">Lead</option>
                                <option value="customer">Customer</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Assigned User</label>
                            <select class="form-select" name="assigned_user_id" id="field_assigned_user_id">
                                <option value="">Unassigned</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Customer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Customer View Modal (NEW - This was missing!) -->
<div class="modal fade" id="customerViewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewModalTitle">
                    <i class="fas fa-user me-2"></i>Customer Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-8">
                        <h6 class="fw-bold mb-2"><i class="fas fa-user me-1 text-primary"></i>Customer Info</h6>
                        <div class="row g-2">
                            <div class="col-md-6">
                                <strong>Name:</strong><br>
                                <span id="view_name" class="text-muted fw-semibold fs-5"></span>
                            </div>
                            <div class="col-md-6">
                                <strong>Status:</strong><br>
                                <span id="view_status" class="badge badge-crm badge-info fs-6 px-3 py-2"></span>
                            </div>
                            <div class="col-md-6">
                                <strong>Email:</strong><br>
                                <span id="view_email" class="text-muted"></span>
                            </div>
                            <div class="col-md-6">
                                <strong>Phone:</strong><br>
                                <span id="view_phone" class="text-muted"></span>
                            </div>
                            <div class="col-md-12">
                                <strong>Company:</strong><br>
                                <span id="view_company" class="text-muted"></span>
                            </div>
                            <div class="col-md-12">
                                <strong>Address:</strong><br>
                                <div class="bg-light p-3 rounded" id="view_address"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center border-start ps-4">
                            <div class="avatar-circle bg-primary mb-3 mx-auto fs-2" style="width: 80px; height: 80px; line-height: 80px;">
                                <span id="view_initials"></span>
                            </div>
                            <h6 class="mb-1" id="view_customer_id"></h6>
                            <p class="text-muted small mb-2">Customer ID</p>
                            <div class="small text-muted">
                                <div><i class="fas fa-calendar me-1"></i>Created: <span id="view_created"></span></div>
                                @if(auth()->user()->canManageCustomersAndLeads())
                                <div class="mt-2">
                                    <a href="#" onclick="editCustomerFromView()" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Toast Container -->
<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1100;"></div>

@endsection

@push('scripts')
<script>
    console.log('✅ Customer page scripts loaded');

    let customerModal = null;

    document.addEventListener('DOMContentLoaded', function() {
        console.log('✅ DOM Content Loaded');

        // Initialize modal
        const modalElement = document.getElementById('customerModal');
        if (modalElement) {
            customerModal = new bootstrap.Modal(modalElement);
            console.log('✅ Modal initialized successfully');
        } else {
            console.error('❌ Could not find #customerModal element');
        }

        // Attach click handler to Add Customer button
        const addButton = document.getElementById('addCustomerBtn');
        if (addButton) {
            addButton.addEventListener('click', function(e) {
                e.preventDefault();
                createCustomer();
            });
            console.log('✅ Click handler attached to Add Customer button');
        }
    });

    document.querySelectorAll('#customerModal select').forEach(el => {
        el.addEventListener('change', function(e) {
            e.stopPropagation();
        });
    });

    document.querySelectorAll('#filterForm select').forEach(el => {
        el.addEventListener('change', () => {
            document.getElementById('filterForm').submit();
        });
    });

    // FIX #5: Guard both lines inside the same if block
    function createCustomer() {
        console.log('✅ createCustomer() called');

        if (!customerModal) {
            alert('Modal is not ready. Please refresh the page.');
            return;
        }

        const form = document.getElementById('customerForm');
        if (form) {
            form.reset();
            form.removeAttribute('data-id'); // FIX #5: was outside the if block before
        }

        document.getElementById('customerModalTitle').textContent = 'Add New Customer';

        // Default status
        const statusField = document.getElementById('field_status');
        if (statusField) statusField.value = 'prospect';

        customerModal.show();
        console.log('✅ Modal shown');
    }

    // FIX #2 + #3: Use correct field IDs and show modal after populating
    window.editCustomer = async function(id) {
        try {
            const res = await fetch(`/admin/customers/${id}`);
            const data = await res.json();

            const c = data.customer;

            const form = document.getElementById('customerForm');
            if (form) {
                form.reset();
                form.dataset.id = c.id; // FIX #3: set dataset.id so submit handler sends PATCH
            }

            // FIX #3: Use the correct field IDs that exist in the modal
            document.getElementById('field_first_name').value = c.first_name ?? '';
            document.getElementById('field_last_name').value = c.last_name ?? '';
            document.getElementById('field_email').value = c.email ?? '';
            document.getElementById('field_phone').value = c.phone ?? '';
            document.getElementById('field_company').value = c.company ?? '';
            document.getElementById('field_address').value = c.address ?? '';
            document.getElementById('field_status').value = c.status ?? '';
            document.getElementById('field_assigned_user_id').value = c.assigned_user_id ?? '';

            document.getElementById('customerModalTitle').textContent = 'Edit Customer';

            // FIX #2: Actually show the modal
            customerModal.show();
            console.log('✅ Edit modal shown');
        } catch (err) {
            console.error('❌ editCustomer error:', err);
        }
    }

    // FIX #1: Single definition of viewCustomer — removed the duplicate window.viewCustomer that showed a plain alert
   // FIXED viewCustomer - now shows modal and handles missing data
window.viewCustomer = async function(id) {
    try {
        console.log(`🔍 Fetching customer ${id}`);
        const res = await fetch(`/admin/customers/${id}`);
        const data = await res.json();

        const c = data.customer;

        // Populate all fields with fallbacks
        document.getElementById('view_name').innerText = `${c.first_name || ''} ${c.last_name || ''}`.trim() || '—';
        document.getElementById('view_email').innerText = c.email || '—';
        document.getElementById('view_phone').innerText = c.phone || '—';
        document.getElementById('view_company').innerText = c.company || '—';
        document.getElementById('view_status').innerText = c.status ? ucfirst(c.status) : '—';
        document.getElementById('view_status').className = `badge badge-crm badge-${c.status || 'secondary'} fs-6 px-3 py-2`;
        document.getElementById('view_address').innerHTML = c.address ? `<div>${c.address.replace(/\n/g, '<br>')}</div>` : '<em>No address</em>';
        document.getElementById('view_initials').innerText = c.initials || '?';
        document.getElementById('view_customer_id').innerText = `#${c.id}`;
        document.getElementById('view_created').innerText = c.created_at ? new Date(c.created_at).toLocaleDateString() : '—';

        // Show the modal
        const viewModalElement = document.getElementById('customerViewModal');
        if (viewModalElement) {
            const viewModal = new bootstrap.Modal(viewModalElement);
            viewModal.show();
            console.log('✅ Customer view modal shown');
        } else {
            console.error('❌ View modal not found');
        }
    } catch (err) {
        console.error('❌ viewCustomer error:', err);
        showToast('Failed to load customer details', 'danger');
    }
}

// Helper function
function ucfirst(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
}

// Optional: Edit from view modal
window.editCustomerFromView = function() {
    const viewModal = bootstrap.Modal.getInstance(document.getElementById('customerViewModal'));
    viewModal.hide();
    
    // Extract ID from view_customer_id and trigger edit
    const customerId = document.getElementById('view_customer_id').innerText.replace('#', '');
    editCustomer(customerId);
};

    // FIX #4: Read CSRF token from meta tag instead of hidden input for reliability
    window.deleteCustomer = function(id, name) {
        if (!confirm(`Delete ${name}?`)) return;

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                       ?? document.querySelector('input[name="_token"]')?.value;

        fetch(`/admin/customers/${id}`, {
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ _method: 'DELETE' })
        })
        .then(res => res.json())
        .then(data => {
            showToast(data.message, 'success');
            window.location.href = "{{ route('admin.customers.index') }}";
        })
        .catch(() => showToast('Delete failed', 'danger'));
    }

    // Toast function
    function showToast(message, type = 'success') {
        const bg = type === 'success' ? 'success' : 'danger';
        const toastHTML = `
            <div class="toast align-items-center text-white bg-${bg} border-0" role="alert">
                <div class="d-flex">
                    <div class="toast-body">${message}</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>`;

        let container = document.querySelector('.toast-container');
        if (!container) {
            container = document.createElement('div');
            container.className = 'toast-container position-fixed bottom-0 end-0 p-3';
            container.style.zIndex = '1100';
            document.body.appendChild(container);
        }

        container.insertAdjacentHTML('beforeend', toastHTML);
        const toastEl = container.lastElementChild;
        const toast = new bootstrap.Toast(toastEl, { delay: 4000 });
        toast.show();

        setTimeout(() => toastEl.remove(), 5000);
    }

    // FIX #6: Removed unused `let method = "POST"` variable
    document.getElementById('customerForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const form = e.target;
        const formData = new FormData(form);

        let url = "{{ route('admin.customers.store') }}";

        if (form.dataset.id) {
            url = `/admin/customers/${form.dataset.id}`;
            formData.append('_method', 'PATCH');
        }

        fetch(url, {
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
                customerModal.hide();
                location.reload();
            } else {
                showToast('Something went wrong', 'danger');
            }
        })
        .catch(() => showToast('Error saving customer', 'danger'));
    });
</script>
@endpush