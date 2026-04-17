@extends('layouts.app')
@section('title', 'Companies')
@section('breadcrumb', 'Companies')

@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h1 class="page-title"><i class="fas fa-building me-2" style="color:var(--crm-primary)"></i>Companies</h1>
        <p class="page-subtitle">Manage your business accounts and organizations</p>
    </div>
    <button class="btn-crm-primary" onclick="openModal('addModal')">
        <i class="fas fa-plus"></i> New Company
    </button>
</div>

<!-- Stats -->
<div class="row g-3 mb-4">
    @php
    $si = [
        ['label'=>'Total','value'=>$stats['total'],'icon'=>'fa-building','color'=>'#3b82f6','bg'=>'rgba(59,130,246,.12)'],
        ['label'=>'Active', 'value'=>$stats['active'],'icon'=>'fa-check-circle','color'=>'#10b981','bg'=>'rgba(16,185,129,.12)'],
        ['label'=>'Prospects','value'=>$stats['prospect'],'icon'=>'fa-search','color'=>'#f59e0b','bg'=>'rgba(245,158,11,.12)'],
        ['label'=>'Inactive','value'=>$stats['inactive'],'icon'=>'fa-pause-circle','color'=>'#64748b','bg'=>'rgba(100,116,139,.12)'],
    ];
    @endphp
    @foreach($si as $s)
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:{{ $s['bg'] }};color:{{ $s['color'] }}">
                <i class="fas {{ $s['icon'] }}"></i>
            </div>
            <div class="stat-value" style="color:{{ $s['color'] }}">{{ $s['value'] }}</div>
            <div class="stat-label">{{ $s['label'] }}</div>
        </div>
    </div>
    @endforeach
</div>

<!-- Filters -->
<div class="crm-card mb-4">
    <div class="crm-card-body">
        <form method="GET" action="{{ route('admin.companies.index') }}" class="d-flex gap-2 flex-wrap align-items-center">
            <div class="search-bar">
                <i class="fas fa-search search-icon"></i>
                <input type="text" name="search" class="crm-input" placeholder="Search companies…" value="{{ request('search') }}">
            </div>
            <select name="status" class="crm-input" style="max-width:150px">
                <option value="">All Statuses</option>
                @foreach(['active','prospect','inactive'] as $s)
                    <option value="{{ $s }}" {{ request('status')==$s?'selected':'' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
            <select name="industry" class="crm-input" style="max-width:160px">
                <option value="">All Industries</option>
                @foreach($industries as $ind)
                    <option value="{{ $ind }}" {{ request('industry')==$ind?'selected':'' }}>{{ $ind }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn-crm-primary"><i class="fas fa-filter"></i> Filter</button>
            @if(request()->anyFilled(['search','status','industry']))
                <a href="{{ route('admin.companies.index') }}" class="btn-crm-secondary">Clear</a>
            @endif
        </form>
    </div>
</div>

<!-- Table -->
<div class="crm-card">
    <div class="crm-card-header">
        <i class="fas fa-table" style="color:var(--crm-primary)"></i>
        <h5 class="card-title">All Companies</h5>
        <span class="ms-auto badge-crm badge-primary">{{ $companies->total() }} records</span>
    </div>
    @if($companies->count())
    <div style="overflow-x:auto">
        <table class="crm-table">
            <thead>
                <tr>
                    <th>Company</th>
                    <th>Industry</th>
                    <th>Contact</th>
                    <th>Employees</th>
                    <th>Revenue</th>
                    <th>Location</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($companies as $c)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="avatar-circle" style="background:{{ ['#3b82f6','#10b981','#f59e0b','#8b5cf6','#06b6d4','#ef4444'][$c->id%6] }};color:#fff;border-radius:8px">
                                {{ $c->initials }}
                            </div>
                            <div>
                                <div style="font-weight:600;font-size:13.5px">{{ $c->name }}</div>
                                @if($c->website)
                                <a href="{{ $c->website }}" target="_blank" style="font-size:11px;color:var(--crm-primary)">
                                    <i class="fas fa-external-link-alt"></i> website
                                </a>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td><span class="badge-crm badge-secondary">{{ $c->industry ?? '—' }}</span></td>
                    <td style="font-size:13px;color:var(--crm-muted)">
                        @if($c->email)<div>{{ $c->email }}</div>@endif
                        @if($c->phone)<div>{{ formatPhilippinePhone($c->phone) }}</div>@endif
                        @if(!$c->email && !$c->phone)—@endif
                    </td>
                    <td style="font-size:13px">{{ $c->employees ? number_format($c->employees) : '—' }}</td>
                    <td style="font-size:13px;font-weight:600;color:var(--crm-success)">
                        {{ $c->annual_revenue ? '$'.number_format($c->annual_revenue) : '—' }}
                    </td>
                    <td style="font-size:12px;color:var(--crm-muted)">
                        {{ collect([$c->city,$c->country])->filter()->join(', ') ?: '—' }}
                    </td>
                    <td>
                        @php $badge=['active'=>'success','prospect'=>'warning','inactive'=>'secondary'][$c->status]??'secondary'; @endphp
                        <span class="badge-crm badge-{{ $badge }}">{{ ucfirst($c->status) }}</span>
                    </td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                @if(auth()->user()->isAdminOrManager())
                                <li><a class="dropdown-item" href="#" onclick="editCompany({{ $c->id }}); return false"><i class="fas fa-pen"></i> Edit</a></li>
                                @endif
                                @if(auth()->user()->isAdmin())
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="#" onclick="deleteCompany({{ $c->id }}, '{{ addslashes($c->name) }}'); return false"><i class="fas fa-trash"></i> Delete</a></li>
                                @endif
                            </ul>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="p-3 d-flex justify-content-between align-items-center" style="border-top:1px solid var(--crm-border)">
        <span style="font-size:12px;color:var(--crm-muted)">Showing {{ $companies->firstItem() }}–{{ $companies->lastItem() }} of {{ $companies->total() }}</span>
        {{ $companies->links() }}
    </div>
    @else
    <div class="empty-state">
        <div class="empty-icon"><i class="fas fa-building"></i></div>
        <h5>No companies found</h5>
        <p>Add your first company or adjust the filters.</p>
        <button class="btn-crm-primary" onclick="openModal('addModal')"><i class="fas fa-plus"></i> Add Company</button>
    </div>
    @endif
</div>

<!-- Add Modal -->
<div class="modal fade crm-modal" id="addModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-building me-2" style="color:var(--crm-primary)"></i>New Company</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addForm">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="crm-label">Company Name *</label>
                            <input type="text" name="name" class="crm-input" placeholder="Acme Corp">
                        </div>
                        <div class="col-md-6">
                            <label class="crm-label">Industry</label>
                            <input type="text" name="industry" class="crm-input" placeholder="Technology">
                        </div>
                        <div class="col-md-6">
                            <label class="crm-label">Email</label>
                            <input type="email" name="email" class="crm-input" placeholder="contact@company.com">
                        </div>
                        <div class="col-md-6">
                            <label class="crm-label">Phone</label>
                            <input type="text" name="phone" class="crm-input" placeholder="+63 9XX XXX XXXX">
                        </div>
                        <div class="col-md-6">
                            <label class="crm-label">Website</label>
                            <input type="url" name="website" class="crm-input" placeholder="https://company.com">
                        </div>
                        <div class="col-md-3">
                            <label class="crm-label">Employees</label>
                            <input type="number" name="employees" class="crm-input" placeholder="50">
                        </div>
                        <div class="col-md-3">
                            <label class="crm-label">Annual Revenue ($)</label>
                            <input type="number" name="annual_revenue" class="crm-input" placeholder="1000000">
                        </div>
                        <div class="col-md-4">
                            <label class="crm-label">Status *</label>
                            <select name="status" class="crm-input">
                                <option value="active">Active</option>
                                <option value="prospect">Prospect</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="crm-label">City</label>
                            <input type="text" name="city" class="crm-input" placeholder="New York">
                        </div>
                        <div class="col-md-4">
                            <label class="crm-label">Country</label>
                            <input type="text" name="country" class="crm-input" placeholder="USA">
                        </div>
                        <div class="col-12">
                            <label class="crm-label">Address</label>
                            <input type="text" name="address" class="crm-input" placeholder="123 Main St">
                        </div>
                        <div class="col-12">
                            <label class="crm-label">Description</label>
                            <textarea name="description" class="crm-input" rows="3" placeholder="About this company…"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn-crm-secondary" data-bs-dismiss="modal">Cancel</button>
                <button class="btn-crm-primary" onclick="submitAdd()"><i class="fas fa-save"></i> Save Company</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade crm-modal" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-pen me-2" style="color:var(--crm-warning)"></i>Edit Company</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editForm">
                    <input type="hidden" id="edit_id">
                    <div class="row g-3">
                        <div class="col-md-6"><label class="crm-label">Name *</label><input type="text" name="name" id="edit_name" class="crm-input"></div>
                        <div class="col-md-6"><label class="crm-label">Industry</label><input type="text" name="industry" id="edit_industry" class="crm-input"></div>
                        <div class="col-md-6"><label class="crm-label">Email</label><input type="email" name="email" id="edit_email" class="crm-input"></div>
                        <div class="col-md-6"><label class="crm-label">Phone</label><input type="text" name="phone" id="edit_phone" class="crm-input"></div>
                        <div class="col-md-6"><label class="crm-label">Website</label><input type="url" name="website" id="edit_website" class="crm-input"></div>
                        <div class="col-md-3"><label class="crm-label">Employees</label><input type="number" name="employees" id="edit_employees" class="crm-input"></div>
                        <div class="col-md-3"><label class="crm-label">Revenue ($)</label><input type="number" name="annual_revenue" id="edit_annual_revenue" class="crm-input"></div>
                        <div class="col-md-4"><label class="crm-label">Status *</label>
                            <select name="status" id="edit_status" class="crm-input">
                                <option value="active">Active</option><option value="prospect">Prospect</option><option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-4"><label class="crm-label">City</label><input type="text" name="city" id="edit_city" class="crm-input"></div>
                        <div class="col-md-4"><label class="crm-label">Country</label><input type="text" name="country" id="edit_country" class="crm-input"></div>
                        <div class="col-12"><label class="crm-label">Address</label><input type="text" name="address" id="edit_address" class="crm-input"></div>
                        <div class="col-12"><label class="crm-label">Description</label><textarea name="description" id="edit_description" class="crm-input" rows="3"></textarea></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn-crm-secondary" data-bs-dismiss="modal">Cancel</button>
                <button class="btn-crm-primary" onclick="submitEdit()"><i class="fas fa-save"></i> Update</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade crm-modal" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2" style="color:var(--crm-danger)"></i>Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body"><p style="font-size:14px;color:var(--crm-muted)">Delete <strong id="delete_name" style="color:var(--crm-text)"></strong>?</p></div>
            <div class="modal-footer">
                <button class="btn-crm-secondary" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-danger" onclick="confirmDelete()"><i class="fas fa-trash"></i> Delete</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const BASE = '{{ route("admin.companies.index") }}';
let deleteId = null;

function openModal(id) { new bootstrap.Modal(document.getElementById(id)).show(); }

async function submitAdd() {
    clearFormErrors('addForm');
    const data = Object.fromEntries(new FormData(document.getElementById('addForm')));
    const res = await fetch(BASE, { method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF}, body:JSON.stringify(data) });
    const json = await res.json();
    if (!res.ok) { showFormErrors(json.errors,'addForm'); return; }
    bootstrap.Modal.getInstance(document.getElementById('addModal')).hide();
    showToast(json.message,'success');
    setTimeout(() => location.reload(), 800);
}

async function editCompany(id) {
    const res = await fetch(`${BASE}/${id}/edit`);
    const c = await res.json();
    document.getElementById('edit_id').value = c.id;
    ['name','industry','email','phone','website','employees','annual_revenue','status','city','country','address','description']
        .forEach(f => { const el=document.getElementById(`edit_${f}`); if(el) el.value = c[f]??''; });
    openModal('editModal');
}

async function submitEdit() {
    clearFormErrors('editForm');
    const id = document.getElementById('edit_id').value;
    const data = Object.fromEntries(new FormData(document.getElementById('editForm')));
    data._method = 'PUT';
    const res = await fetch(`${BASE}/${id}`, { method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF}, body:JSON.stringify(data) });
    const json = await res.json();
    if (!res.ok) { showFormErrors(json.errors,'editForm'); return; }
    bootstrap.Modal.getInstance(document.getElementById('editModal')).hide();
    showToast(json.message,'success');
    setTimeout(() => location.reload(), 800);
}

function deleteCompany(id, name) { deleteId=id; document.getElementById('delete_name').textContent=name; openModal('deleteModal'); }

async function confirmDelete() {
    const res = await fetch(`${BASE}/${deleteId}`, { method:'DELETE', headers:{'X-CSRF-TOKEN':CSRF} });
    const json = await res.json();
    bootstrap.Modal.getInstance(document.getElementById('deleteModal')).hide();
    showToast(json.message,'success');
    setTimeout(() => location.reload(), 800);
}
</script>
@endpush
