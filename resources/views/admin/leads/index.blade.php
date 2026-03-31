@extends('layouts.app')
@section('title', 'Leads')
@section('breadcrumb', 'Leads')

@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h1 class="page-title"><i class="fas fa-bullseye me-2" style="color:var(--crm-warning)"></i>Leads</h1>
        <p class="page-subtitle">Track and manage your sales pipeline leads</p>
    </div>
    <button class="btn-crm-primary" onclick="openModal('addModal')">
        <i class="fas fa-plus"></i> New Lead
    </button>
</div>

<!-- Stats -->
<div class="row g-3 mb-4">
    @php
    $pipeline = [
        ['label'=>'Total Leads','value'=>$stats['total'],'color'=>'#3b82f6','bg'=>'rgba(59,130,246,.12)','icon'=>'fa-bullseye'],
        ['label'=>'New',        'value'=>$stats['new'],  'color'=>'#f59e0b','bg'=>'rgba(245,158,11,.12)', 'icon'=>'fa-star'],
        ['label'=>'Contacted',  'value'=>$stats['contacted'],'color'=>'#06b6d4','bg'=>'rgba(6,182,212,.12)', 'icon'=>'fa-phone'],
        ['label'=>'Negotiating','value'=>$stats['negotiating'],'color'=>'#8b5cf6','bg'=>'rgba(139,92,246,.12)','icon'=>'fa-comments'],
        ['label'=>'Closed',     'value'=>$stats['closed'],'color'=>'#10b981','bg'=>'rgba(16,185,129,.12)','icon'=>'fa-check-circle'],
        ['label'=>'Lost',       'value'=>$stats['lost'], 'color'=>'#ef4444','bg'=>'rgba(239,68,68,.12)', 'icon'=>'fa-times-circle'],
    ];
    @endphp
    @foreach($pipeline as $s)
    <div class="col-6 col-md-2">
        <div class="stat-card">
            <div class="stat-icon" style="background:{{ $s['bg'] }};color:{{ $s['color'] }};width:38px;height:38px;font-size:16px">
                <i class="fas {{ $s['icon'] }}"></i>
            </div>
            <div class="stat-value" style="color:{{ $s['color'] }};font-size:22px;margin-top:8px">{{ $s['value'] }}</div>
            <div class="stat-label">{{ $s['label'] }}</div>
        </div>
    </div>
    @endforeach
</div>

<!-- Filters -->
<div class="crm-card mb-4">
    <div class="crm-card-body">
        <form method="GET" action="{{ route('admin.leads.index') }}" class="d-flex gap-2 flex-wrap align-items-center">
            <div class="search-bar">
                <i class="fas fa-search search-icon"></i>
                <input type="text" name="search" class="crm-input" placeholder="Search leads…" value="{{ request('search') }}">
            </div>
            <select name="status" class="crm-input" style="max-width:150px">
                <option value="">All Statuses</option>
                @foreach(['new','contacted','negotiating','closed','lost'] as $s)
                    <option value="{{ $s }}" {{ request('status')==$s?'selected':'' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
            <select name="priority" class="crm-input" style="max-width:130px">
                <option value="">All Priorities</option>
                @foreach(['high','medium','low'] as $p)
                    <option value="{{ $p }}" {{ request('priority')==$p?'selected':'' }}>{{ ucfirst($p) }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn-crm-primary"><i class="fas fa-filter"></i> Filter</button>
            @if(request()->anyFilled(['search','status','priority']))
                <a href="{{ route('admin.leads.index') }}" class="btn-crm-secondary">Clear</a>
            @endif
        </form>
    </div>
</div>

<!-- Table -->
<div class="crm-card">
    <div class="crm-card-header">
        <i class="fas fa-list" style="color:var(--crm-warning)"></i>
        <h5 class="card-title">All Leads</h5>
        <span class="ms-auto badge-crm badge-warning">{{ $leads->total() }} records</span>
    </div>
    @if($leads->count())
    <div style="overflow-x:auto">
        <table class="crm-table">
            <thead>
                <tr>
                    <th>Lead Title</th>
                    <th>Contact</th>
                    <th>Company</th>
                    <th>Source</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Value</th>
                    <th>Follow Up</th>
                    <th>Assigned</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($leads as $lead)
                <tr>
                    <td style="font-weight:600;max-width:200px">{{ $lead->title }}</td>
                    <td style="font-size:13px">
                        @if($lead->contact)
                            <div>{{ $lead->contact->full_name }}</div>
                            <div style="font-size:11px;color:var(--crm-muted)">{{ $lead->contact->email }}</div>
                        @else <span style="color:var(--crm-muted)">—</span> @endif
                    </td>
                    <td style="font-size:13px">{{ $lead->company?->name ?? '—' }}</td>
                    <td><span class="badge-crm badge-secondary">{{ $lead->source ?? '—' }}</span></td>
                    <td>
                        @php $pb=['high'=>'danger','medium'=>'warning','low'=>'success'][$lead->priority]??'secondary'; @endphp
                        <span class="badge-crm badge-{{ $pb }}">{{ ucfirst($lead->priority) }}</span>
                    </td>
                    <td>
                        @php $sb=['new'=>'warning','contacted'=>'info','negotiating'=>'purple','closed'=>'success','lost'=>'danger'][$lead->status]??'secondary'; @endphp
                        <span class="badge-crm badge-{{ $sb }}">{{ ucfirst($lead->status) }}</span>
                    </td>
                    <td style="font-weight:600;color:var(--crm-success)">
                        {{ $lead->value ? '$'.number_format($lead->value) : '—' }}
                    </td>
                    <td style="font-size:12px;color:var(--crm-muted)">
                        @if($lead->follow_up_date)
                            <span class="{{ $lead->follow_up_date->isPast() ? 'text-danger' : '' }}">
                                {{ $lead->follow_up_date->format('M d, Y') }}
                            </span>
                        @else — @endif
                    </td>
                    <td style="font-size:12px">{{ $lead->assignedUser?->name ?? '—' }}</td>
                    <td>
                        <div class="d-flex gap-1">
                            <button class="btn btn-sm" style="background:rgba(59,130,246,.12);color:#3b82f6;border:none;border-radius:6px;padding:4px 10px"
                                onclick="editLead({{ $lead->id }})"><i class="fas fa-pen"></i></button>
                            <button class="btn btn-sm" style="background:rgba(239,68,68,.12);color:#ef4444;border:none;border-radius:6px;padding:4px 10px"
                                onclick="deleteLead({{ $lead->id }}, '{{ addslashes($lead->title) }}')"><i class="fas fa-trash"></i></button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="p-3 d-flex justify-content-between align-items-center" style="border-top:1px solid var(--crm-border)">
        <span style="font-size:12px;color:var(--crm-muted)">Showing {{ $leads->firstItem() }}–{{ $leads->lastItem() }} of {{ $leads->total() }}</span>
        {{ $leads->links() }}
    </div>
    @else
    <div class="empty-state">
        <div class="empty-icon"><i class="fas fa-bullseye"></i></div>
        <h5>No leads found</h5>
        <p>Start adding leads to track your sales pipeline.</p>
        <button class="btn-crm-primary" onclick="openModal('addModal')"><i class="fas fa-plus"></i> Add Lead</button>
    </div>
    @endif
</div>

<!-- ── Add Modal ── -->
<div class="modal fade crm-modal" id="addModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-bullseye me-2" style="color:var(--crm-warning)"></i>New Lead</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addForm">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="crm-label">Lead Title *</label>
                            <input type="text" name="title" class="crm-input" placeholder="e.g. TechVision Annual License Renewal">
                        </div>
                        <div class="col-md-6">
                            <label class="crm-label">Contact</label>
                            <select name="contact_id" class="crm-input">
                                <option value="">— Select Contact —</option>
                                @foreach($contacts as $c)
                                    <option value="{{ $c->id }}">{{ $c->full_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="crm-label">Company</label>
                            <select name="company_id" class="crm-input">
                                <option value="">— Select Company —</option>
                                @foreach($companies as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="crm-label">Source</label>
                            <select name="source" class="crm-input">
                                <option value="">— Select —</option>
                                @foreach(['web','referral','social','email','event','direct','other'] as $src)
                                    <option value="{{ $src }}">{{ ucfirst($src) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="crm-label">Status *</label>
                            <select name="status" class="crm-input">
                                @foreach(['new','contacted','negotiating','closed','lost'] as $s)
                                    <option value="{{ $s }}">{{ ucfirst($s) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="crm-label">Priority *</label>
                            <select name="priority" class="crm-input">
                                @foreach(['high','medium','low'] as $p)
                                    <option value="{{ $p }}" {{ $p=='medium'?'selected':'' }}>{{ ucfirst($p) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="crm-label">Estimated Value ($)</label>
                            <input type="number" name="value" class="crm-input" placeholder="5000">
                        </div>
                        <div class="col-md-4">
                            <label class="crm-label">Follow-Up Date</label>
                            <input type="date" name="follow_up_date" class="crm-input">
                        </div>
                        <div class="col-md-4">
                            <label class="crm-label">Assigned To</label>
                            <select name="assigned_to" class="crm-input">
                                <option value="">— Select User —</option>
                                @foreach($users as $u)
                                    <option value="{{ $u->id }}">{{ $u->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="crm-label">Notes</label>
                            <textarea name="notes" class="crm-input" rows="3" placeholder="Any notes…"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn-crm-secondary" data-bs-dismiss="modal">Cancel</button>
                <button class="btn-crm-primary" onclick="submitAdd()"><i class="fas fa-save"></i> Save Lead</button>
            </div>
        </div>
    </div>
</div>

<!-- ── Edit Modal ── -->
<div class="modal fade crm-modal" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-pen me-2" style="color:var(--crm-warning)"></i>Edit Lead</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editForm">
                    <input type="hidden" id="edit_id">
                    <div class="row g-3">
                        <div class="col-12"><label class="crm-label">Lead Title *</label><input type="text" name="title" id="edit_title" class="crm-input"></div>
                        <div class="col-md-6">
                            <label class="crm-label">Contact</label>
                            <select name="contact_id" id="edit_contact_id" class="crm-input">
                                <option value="">— Select Contact —</option>
                                @foreach($contacts as $c)<option value="{{ $c->id }}">{{ $c->full_name }}</option>@endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="crm-label">Company</label>
                            <select name="company_id" id="edit_company_id" class="crm-input">
                                <option value="">— Select Company —</option>
                                @foreach($companies as $c)<option value="{{ $c->id }}">{{ $c->name }}</option>@endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="crm-label">Source</label>
                            <select name="source" id="edit_source" class="crm-input">
                                <option value="">— Select —</option>
                                @foreach(['web','referral','social','email','event','direct','other'] as $src)<option value="{{ $src }}">{{ ucfirst($src) }}</option>@endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="crm-label">Status *</label>
                            <select name="status" id="edit_status" class="crm-input">
                                @foreach(['new','contacted','negotiating','closed','lost'] as $s)<option value="{{ $s }}">{{ ucfirst($s) }}</option>@endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="crm-label">Priority *</label>
                            <select name="priority" id="edit_priority" class="crm-input">
                                @foreach(['high','medium','low'] as $p)<option value="{{ $p }}">{{ ucfirst($p) }}</option>@endforeach
                            </select>
                        </div>
                        <div class="col-md-4"><label class="crm-label">Value ($)</label><input type="number" name="value" id="edit_value" class="crm-input"></div>
                        <div class="col-md-4"><label class="crm-label">Follow-Up Date</label><input type="date" name="follow_up_date" id="edit_follow_up_date" class="crm-input"></div>
                        <div class="col-md-4">
                            <label class="crm-label">Assigned To</label>
                            <select name="assigned_to" id="edit_assigned_to" class="crm-input">
                                <option value="">— Select —</option>
                                @foreach($users as $u)<option value="{{ $u->id }}">{{ $u->name }}</option>@endforeach
                            </select>
                        </div>
                        <div class="col-12"><label class="crm-label">Notes</label><textarea name="notes" id="edit_notes" class="crm-input" rows="3"></textarea></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn-crm-secondary" data-bs-dismiss="modal">Cancel</button>
                <button class="btn-crm-primary" onclick="submitEdit()"><i class="fas fa-save"></i> Update Lead</button>
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
            <div class="modal-body"><p style="font-size:14px;color:var(--crm-muted)">Delete lead <strong id="delete_name" style="color:var(--crm-text)"></strong>?</p></div>
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
const BASE = '{{ route("admin.leads.index") }}';
let deleteId = null;
function openModal(id) { new bootstrap.Modal(document.getElementById(id)).show(); }

async function submitAdd() {
    clearFormErrors('addForm');
    const data = Object.fromEntries(new FormData(document.getElementById('addForm')));
    const res = await fetch(BASE, { method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF}, body:JSON.stringify(data) });
    const json = await res.json();
    if (!res.ok) { showFormErrors(json.errors,'addForm'); return; }
    bootstrap.Modal.getInstance(document.getElementById('addModal')).hide();
    showToast(json.message,'success'); setTimeout(() => location.reload(), 800);
}

async function editLead(id) {
    const res = await fetch(`${BASE}/${id}/edit`);
    const l = await res.json();
    document.getElementById('edit_id').value = l.id;
    ['title','source','status','priority','value','notes'].forEach(f => { const el=document.getElementById(`edit_${f}`); if(el) el.value=l[f]??''; });
    document.getElementById('edit_contact_id').value = l.contact_id ?? '';
    document.getElementById('edit_company_id').value = l.company_id ?? '';
    document.getElementById('edit_assigned_to').value = l.assigned_to ?? '';
    document.getElementById('edit_follow_up_date').value = l.follow_up_date ? l.follow_up_date.substring(0,10) : '';
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
    showToast(json.message,'success'); setTimeout(() => location.reload(), 800);
}

function deleteLead(id, name) { deleteId=id; document.getElementById('delete_name').textContent=name; openModal('deleteModal'); }

async function confirmDelete() {
    const res = await fetch(`${BASE}/${deleteId}`, { method:'DELETE', headers:{'X-CSRF-TOKEN':CSRF} });
    const json = await res.json();
    bootstrap.Modal.getInstance(document.getElementById('deleteModal')).hide();
    showToast(json.message,'success'); setTimeout(() => location.reload(), 800);
}
</script>
@endpush
