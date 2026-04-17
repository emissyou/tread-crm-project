@extends('layouts.app')
@section('title', 'Tasks')
@section('breadcrumb', 'Tasks')

@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h1 class="page-title"><i class="fas fa-check-square me-2" style="color:var(--crm-info)"></i>Tasks</h1>
        <p class="page-subtitle">Manage your team's activities and to-dos</p>
    </div>
    <button class="btn-crm-primary" onclick="openModal('addModal')">
        <i class="fas fa-plus"></i> New Task
    </button>
</div>

<!-- Stats -->
<div class="row g-3 mb-4">
    @php $si=[
        ['label'=>'Total',      'value'=>$stats['total'],      'color'=>'#3b82f6','bg'=>'rgba(59,130,246,.12)', 'icon'=>'fa-tasks'],
        ['label'=>'Pending',    'value'=>$stats['pending'],    'color'=>'#f59e0b','bg'=>'rgba(245,158,11,.12)', 'icon'=>'fa-clock'],
        ['label'=>'In Progress','value'=>$stats['in_progress'],'color'=>'#06b6d4','bg'=>'rgba(6,182,212,.12)',  'icon'=>'fa-spinner'],
        ['label'=>'Completed',  'value'=>$stats['completed'],  'color'=>'#10b981','bg'=>'rgba(16,185,129,.12)','icon'=>'fa-check-circle'],
        ['label'=>'Overdue',    'value'=>$stats['overdue'],    'color'=>'#ef4444','bg'=>'rgba(239,68,68,.12)',  'icon'=>'fa-exclamation-circle'],
    ]; @endphp
    @foreach($si as $s)
    <div class="col-6 col-md" style="min-width:160px">
        <div class="stat-card">
            <div class="stat-icon" style="background:{{ $s['bg'] }};color:{{ $s['color'] }};width:38px;height:38px;font-size:15px"><i class="fas {{ $s['icon'] }}"></i></div>
            <div class="stat-value" style="color:{{ $s['color'] }};font-size:22px;margin-top:8px">{{ $s['value'] }}</div>
            <div class="stat-label">{{ $s['label'] }}</div>
        </div>
    </div>
    @endforeach
</div>

<!-- Filters -->
<div class="crm-card mb-4">
    <div class="crm-card-body">
        <form method="GET" action="{{ route('admin.tasks.index') }}" class="d-flex gap-2 flex-wrap align-items-center">
            <div class="search-bar">
                <i class="fas fa-search search-icon"></i>
                <input type="text" name="search" class="crm-input" placeholder="Search tasks…" value="{{ request('search') }}">
            </div>
            <select name="status" class="crm-input" style="max-width:150px">
                <option value="">All Statuses</option>
                @foreach(['pending','in_progress','completed','cancelled'] as $s)
                    <option value="{{ $s }}" {{ request('status')==$s?'selected':'' }}>{{ ucwords(str_replace('_',' ',$s)) }}</option>
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
                <a href="{{ route('admin.tasks.index') }}" class="btn-crm-secondary">Clear</a>
            @endif
        </form>
    </div>
</div>

<!-- Task list -->
<div class="crm-card">
    <div class="crm-card-header">
        <i class="fas fa-list-check" style="color:var(--crm-info)"></i>
        <h5 class="card-title">All Tasks</h5>
        <span class="ms-auto badge-crm badge-info">{{ $tasks->total() }} records</span>
    </div>
    @if($tasks->count())
    <div style="overflow-x:auto">
        <table class="crm-table">
            <thead>
                <tr>
                    <th style="width:44px"></th>
                    <th>Task</th><th>Priority</th><th>Status</th>
                    <th>Due Date</th><th>Contact</th><th>Assigned</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tasks as $task)
                @php
                    $isOverdue = $task->due_date && $task->due_date->isPast() && $task->status !== 'completed';
                @endphp
                <tr style="{{ $task->status === 'completed' ? 'opacity:.55' : '' }}">
                    <td>
                        <button onclick="toggleTask({{ $task->id }}, this)"
                            style="width:22px;height:22px;border-radius:6px;border:2px solid {{ $task->status==='completed' ? 'var(--crm-success)' : 'var(--crm-border)' }};background:{{ $task->status==='completed' ? 'var(--crm-success)' : 'transparent' }};cursor:pointer;display:flex;align-items:center;justify-content:center;color:#fff;font-size:11px">
                            @if($task->status==='completed')<i class="fas fa-check"></i>@endif
                        </button>
                    </td>
                    <td>
                        <div style="font-weight:600;font-size:13.5px;{{ $task->status==='completed'?'text-decoration:line-through;':'' }}">
                            {{ $task->title }}
                        </div>
                        @if($task->description)
                            <div style="font-size:11px;color:var(--crm-muted);margin-top:2px">{{ Str::limit($task->description, 60) }}</div>
                        @endif
                    </td>
                    <td>
                        @php $pb=['high'=>'danger','medium'=>'warning','low'=>'success'][$task->priority]??'secondary'; @endphp
                        <span class="badge-crm badge-{{ $pb }}">{{ ucfirst($task->priority) }}</span>
                    </td>
                    <td>
                        @php $sb=['pending'=>'warning','in_progress'=>'info','completed'=>'success','cancelled'=>'danger'][$task->status]??'secondary'; @endphp
                        <span class="badge-crm badge-{{ $sb }}">{{ ucwords(str_replace('_',' ',$task->status)) }}</span>
                    </td>
                    <td>
                        @if($task->due_date)
                            <span style="font-size:12px;{{ $isOverdue ? 'color:var(--crm-danger);font-weight:600' : 'color:var(--crm-muted)' }}">
                                @if($isOverdue)<i class="fas fa-exclamation-circle"></i> @endif
                                {{ $task->due_date->format('M d, Y') }}
                                @if($task->due_time) · {{ $task->due_time }} @endif
                            </span>
                        @else <span style="color:var(--crm-muted)">—</span> @endif
                    </td>
                    <td style="font-size:13px">{{ $task->contact?->full_name ?? '—' }}</td>
                    <td style="font-size:13px">{{ $task->assignedUser?->name ?? '—' }}</td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="padding:6px 10px">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#" onclick="viewTask({{ $task->id }}); return false"><i class="fas fa-eye"></i> View</a></li>
                                @if(auth()->user()->isAdminOrManager() || (auth()->user()->isSalesStaff() && $task->assigned_user_id === auth()->id()))
                                <li><a class="dropdown-item" href="#" onclick="editTask({{ $task->id }}); return false"><i class="fas fa-pen"></i> Edit</a></li>
                                @endif
                                @if(auth()->user()->isAdmin())
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="#" onclick="deleteTask({{ $task->id }}, '{{ addslashes($task->title) }}'); return false"><i class="fas fa-trash"></i> Delete</a></li>
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
        <span style="font-size:12px;color:var(--crm-muted)">Showing {{ $tasks->firstItem() }}–{{ $tasks->lastItem() }} of {{ $tasks->total() }}</span>
        {{ $tasks->links() }}
    </div>
    @else
    <div class="empty-state">
        <div class="empty-icon"><i class="fas fa-check-square"></i></div>
        <h5>No tasks found</h5>
        <p>Create your first task to get started.</p>
        <button class="btn-crm-primary" onclick="openModal('addModal')"><i class="fas fa-plus"></i> Add Task</button>
    </div>
    @endif
</div>

<!-- Add Modal -->
<div class="modal fade crm-modal" id="addModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-plus-circle me-2" style="color:var(--crm-info)"></i>New Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addForm">
                    <div class="row g-3">
                        <div class="col-12"><label class="crm-label">Title *</label><input type="text" name="title" class="crm-input" placeholder="Task title…"></div>
                        <div class="col-12"><label class="crm-label">Description</label><textarea name="description" class="crm-input" rows="2" placeholder="Details…"></textarea></div>
                        <div class="col-md-4">
                            <label class="crm-label">Status *</label>
                            <select name="status" class="crm-input">
                                @foreach(['pending','in_progress','completed','cancelled'] as $s)
                                    <option value="{{ $s }}">{{ ucwords(str_replace('_',' ',$s)) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="crm-label">Priority *</label>
                            <select name="priority" class="crm-input">
                                @foreach(['high','medium','low'] as $p)<option value="{{ $p }}" {{ $p=='medium'?'selected':'' }}>{{ ucfirst($p) }}</option>@endforeach
                            </select>
                        </div>
                        <div class="col-md-4"><label class="crm-label">Due Date</label><input type="date" name="due_date" class="crm-input"></div>
                        <div class="col-md-4">
                            <label class="crm-label">Contact</label>
                            <select name="contact_id" class="crm-input"><option value="">— None —</option>@foreach($contacts as $c)<option value="{{ $c->id }}">{{ $c->full_name }}</option>@endforeach</select>
                        </div>
                        <div class="col-md-4">
                            <label class="crm-label">Lead</label>
                            <select name="lead_id" class="crm-input"><option value="">— None —</option>@foreach($leads as $l)<option value="{{ $l->id }}">{{ Str::limit($l->title,35) }}</option>@endforeach</select>
                        </div>
                        <div class="col-md-4">
                            <label class="crm-label">Deal</label>
                            <select name="deal_id" class="crm-input"><option value="">— None —</option>@foreach($deals as $d)<option value="{{ $d->id }}">{{ Str::limit($d->title,35) }}</option>@endforeach</select>
                        </div>
                        <div class="col-md-6">
                            <label class="crm-label">Assigned To</label>
                            <select name="assigned_to" class="crm-input"><option value="">— Select User —</option>@foreach($users as $u)<option value="{{ $u->id }}">{{ $u->name }}</option>@endforeach</select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn-crm-secondary" data-bs-dismiss="modal">Cancel</button>
                <button class="btn-crm-primary" onclick="submitAdd()"><i class="fas fa-save"></i> Save Task</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade crm-modal" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-pen me-2" style="color:var(--crm-warning)"></i>Edit Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editForm">
                    <input type="hidden" id="edit_id">
                    <div class="row g-3">
                        <div class="col-12"><label class="crm-label">Title *</label><input type="text" name="title" id="edit_title" class="crm-input"></div>
                        <div class="col-12"><label class="crm-label">Description</label><textarea name="description" id="edit_description" class="crm-input" rows="2"></textarea></div>
                        <div class="col-md-4">
                            <label class="crm-label">Status *</label>
                            <select name="status" id="edit_status" class="crm-input">
                                @foreach(['pending','in_progress','completed','cancelled'] as $s)<option value="{{ $s }}">{{ ucwords(str_replace('_',' ',$s)) }}</option>@endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="crm-label">Priority *</label>
                            <select name="priority" id="edit_priority" class="crm-input">
                                @foreach(['high','medium','low'] as $p)<option value="{{ $p }}">{{ ucfirst($p) }}</option>@endforeach
                            </select>
                        </div>
                        <div class="col-md-4"><label class="crm-label">Due Date</label><input type="date" name="due_date" id="edit_due_date" class="crm-input"></div>
                        <div class="col-md-4">
                            <label class="crm-label">Contact</label>
                            <select name="contact_id" id="edit_contact_id" class="crm-input"><option value="">— None —</option>@foreach($contacts as $c)<option value="{{ $c->id }}">{{ $c->full_name }}</option>@endforeach</select>
                        </div>
                        <div class="col-md-4">
                            <label class="crm-label">Lead</label>
                            <select name="lead_id" id="edit_lead_id" class="crm-input"><option value="">— None —</option>@foreach($leads as $l)<option value="{{ $l->id }}">{{ Str::limit($l->title,35) }}</option>@endforeach</select>
                        </div>
                        <div class="col-md-4">
                            <label class="crm-label">Deal</label>
                            <select name="deal_id" id="edit_deal_id" class="crm-input"><option value="">— None —</option>@foreach($deals as $d)<option value="{{ $d->id }}">{{ Str::limit($d->title,35) }}</option>@endforeach</select>
                        </div>
                        <div class="col-md-6">
                            <label class="crm-label">Assigned To</label>
                            <select name="assigned_to" id="edit_assigned_to" class="crm-input"><option value="">— Select User —</option>@foreach($users as $u)<option value="{{ $u->id }}">{{ $u->name }}</option>@endforeach</select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn-crm-secondary" data-bs-dismiss="modal">Cancel</button>
                <button class="btn-crm-primary" onclick="submitEdit()"><i class="fas fa-save"></i> Update Task</button>
            </div>
        </div>
    </div>
</div>

<!-- View Modal -->
<div class="modal fade crm-modal" id="viewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-eye me-2" style="color:var(--crm-info)"></i>Task details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="viewTaskBody" class="row g-3"></div>
            </div>
            <div class="modal-footer">
                <button class="btn-crm-secondary" data-bs-dismiss="modal">Close</button>
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
            <div class="modal-body"><p style="font-size:14px;color:var(--crm-muted)">Delete task <strong id="delete_name" style="color:var(--crm-text)"></strong>?</p></div>
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
const BASE = '{{ route("admin.tasks.index") }}';
let deleteId = null;
function openModal(id) { new bootstrap.Modal(document.getElementById(id)).show(); }

async function toggleTask(id, btn) {
    const res = await fetch(`${BASE}/${id}/toggle`, { method:'PATCH', headers:{'X-CSRF-TOKEN':CSRF,'Content-Type':'application/json'} });
    const json = await res.json();
    if (json.success) { showToast('Task updated', 'success'); setTimeout(() => location.reload(), 500); }
}

async function viewTask(id) {
    const res = await fetch(`${BASE}/${id}`);
    const t = await res.json();
    const container = document.getElementById('viewTaskBody');
    container.innerHTML = `
        <div class="col-md-6">
            <div class="crm-card" style="padding:18px;">
                <h6 style="font-weight:700;margin-bottom:.75rem">Task info</h6>
                <div style="font-size:14px;color:#334155"><strong>Title:</strong> ${t.title}</div>
                <div style="font-size:14px;color:#475569;margin-top:.5rem"><strong>Status:</strong> ${t.status ? t.status.replace('_',' ') : '—'}</div>
                <div style="font-size:14px;color:#475569;margin-top:.5rem"><strong>Priority:</strong> ${t.priority ? t.priority.charAt(0).toUpperCase() + t.priority.slice(1) : '—'}</div>
                <div style="font-size:14px;color:#475569;margin-top:.5rem"><strong>Due:</strong> ${t.due_date ? t.due_date : '—'}</div>
                <div style="font-size:14px;color:#475569;margin-top:.5rem"><strong>Value:</strong> ${t.value ?? '—'}</div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="crm-card" style="padding:18px;">
                <h6 style="font-weight:700;margin-bottom:.75rem">Related</h6>
                <div style="font-size:14px;color:#334155"><strong>Contact:</strong> ${t.contact ? t.contact.full_name : '—'}</div>
                <div style="font-size:14px;color:#334155;margin-top:.5rem"><strong>Lead:</strong> ${t.lead ? t.lead.title : '—'}</div>
                <div style="font-size:14px;color:#334155;margin-top:.5rem"><strong>Deal:</strong> ${t.deal ? t.deal.title : '—'}</div>
                <div style="font-size:14px;color:#334155;margin-top:.5rem"><strong>Assigned:</strong> ${t.assigned_user ? t.assigned_user.name : '—'}</div>
            </div>
        </div>
        <div class="col-12">
            <div class="crm-card" style="padding:18px;">
                <h6 style="font-weight:700;margin-bottom:.75rem">Notes</h6>
                <div style="font-size:14px;color:#475569;white-space:pre-wrap">${t.description || 'No details added.'}</div>
            </div>
        </div>
    `;
    openModal('viewModal');
}

async function submitAdd() {
    clearFormErrors('addForm');
    const data = Object.fromEntries(new FormData(document.getElementById('addForm')));
    const res = await fetch(BASE, { method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF}, body:JSON.stringify(data) });
    const json = await res.json();
    if (!res.ok) { showFormErrors(json.errors,'addForm'); return; }
    bootstrap.Modal.getInstance(document.getElementById('addModal')).hide();
    showToast(json.message,'success'); setTimeout(() => location.reload(), 800);
}

async function editTask(id) {
    const res = await fetch(`${BASE}/${id}/edit`);
    const t = await res.json();
    document.getElementById('edit_id').value = t.id;
    ['title','description','status','priority'].forEach(f => { const el=document.getElementById(`edit_${f}`); if(el) el.value=t[f]??''; });
    document.getElementById('edit_contact_id').value = t.contact_id ?? '';
    document.getElementById('edit_lead_id').value = t.lead_id ?? '';
    document.getElementById('edit_deal_id').value = t.deal_id ?? '';
    document.getElementById('edit_assigned_to').value = t.assigned_to ?? '';
    document.getElementById('edit_due_date').value = t.due_date ? t.due_date.substring(0,10) : '';
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

function deleteTask(id, name) { deleteId=id; document.getElementById('delete_name').textContent=name; openModal('deleteModal'); }

async function confirmDelete() {
    const res = await fetch(`${BASE}/${deleteId}`, { method:'DELETE', headers:{'X-CSRF-TOKEN':CSRF} });
    const json = await res.json();
    bootstrap.Modal.getInstance(document.getElementById('deleteModal')).hide();
    showToast(json.message,'success'); setTimeout(() => location.reload(), 800);
}
</script>
@endpush
