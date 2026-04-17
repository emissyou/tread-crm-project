@extends('layouts.app')
@section('title', 'Deals')
@section('breadcrumb', 'Deals')

@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h1 class="page-title"><i class="fas fa-handshake me-2" style="color:var(--crm-success)"></i>Deals</h1>
        <p class="page-subtitle">Manage your sales deals and pipeline stages</p>
    </div>
    <div class="d-flex gap-2">
        <button class="btn-crm-secondary" onclick="toggleView()" id="viewToggleBtn">
            <i class="fas fa-columns"></i> Kanban View
        </button>
        <button class="btn-crm-primary" onclick="openModal('addModal')">
            <i class="fas fa-plus"></i> New Deal
        </button>
    </div>
</div>

<!-- Stats -->
<div class="row g-3 mb-4">
    @php $si = [
        ['label'=>'Total Deals','value'=>$stats['total'],'icon'=>'fa-handshake','color'=>'#3b82f6','bg'=>'rgba(59,130,246,.12)'],
        ['label'=>'Pipeline Value','value'=>'$'.number_format($stats['pipeline_value']),'icon'=>'fa-dollar-sign','color'=>'#f59e0b','bg'=>'rgba(245,158,11,.12)'],
        ['label'=>'Won Deals','value'=>$stats['won'],'icon'=>'fa-trophy','color'=>'#10b981','bg'=>'rgba(16,185,129,.12)'],
        ['label'=>'Revenue Won','value'=>'$'.number_format($stats['won_value']),'icon'=>'fa-chart-line','color'=>'#8b5cf6','bg'=>'rgba(139,92,246,.12)'],
    ]; @endphp
    @foreach($si as $s)
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:{{ $s['bg'] }};color:{{ $s['color'] }}"><i class="fas {{ $s['icon'] }}"></i></div>
            <div class="stat-value" style="color:{{ $s['color'] }}">{{ $s['value'] }}</div>
            <div class="stat-label">{{ $s['label'] }}</div>
        </div>
    </div>
    @endforeach
</div>

<!-- ── LIST VIEW ── -->
<div id="listView">
    <div class="crm-card mb-4">
        <div class="crm-card-body">
            <form method="GET" action="{{ route('admin.deals.index') }}" class="d-flex gap-2 flex-wrap align-items-center">
                <div class="search-bar">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" name="search" class="crm-input" placeholder="Search deals…" value="{{ request('search') }}">
                </div>
                <select name="stage" class="crm-input" style="max-width:170px">
                    <option value="">All Stages</option>
                    @foreach(['prospecting','qualification','proposal','negotiation','closed_won','closed_lost'] as $s)
                        <option value="{{ $s }}" {{ request('stage')==$s?'selected':'' }}>{{ ucwords(str_replace('_',' ',$s)) }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn-crm-primary"><i class="fas fa-filter"></i> Filter</button>
                @if(request()->anyFilled(['search','stage']))
                    <a href="{{ route('admin.deals.index') }}" class="btn-crm-secondary">Clear</a>
                @endif
            </form>
        </div>
    </div>

    <div class="crm-card">
        <div class="crm-card-header">
            <i class="fas fa-list" style="color:var(--crm-success)"></i>
            <h5 class="card-title">All Deals</h5>
            <span class="ms-auto badge-crm badge-success">{{ $deals->total() }} records</span>
        </div>
        @if($deals->count())
        <div style="overflow-x:auto">
            <table class="crm-table">
                <thead>
                    <tr>
                        <th>Deal</th><th>Contact</th><th>Company</th><th>Stage</th>
                        <th>Value</th><th>Probability</th><th>Close Date</th><th>Assigned</th><th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($deals as $deal)
                    <tr>
                        <td style="font-weight:600;max-width:180px">{{ $deal->title }}</td>
                        <td style="font-size:13px">{{ $deal->contact?->full_name ?? '—' }}</td>
                        <td style="font-size:13px">{{ $deal->company?->name ?? '—' }}</td>
                        <td>
                            @php $sb=['prospecting'=>'secondary','qualification'=>'info','proposal'=>'primary','negotiation'=>'warning','closed_won'=>'success','closed_lost'=>'danger'][$deal->stage]??'secondary'; @endphp
                            <span class="badge-crm badge-{{ $sb }}">{{ ucwords(str_replace('_',' ',$deal->stage)) }}</span>
                        </td>
                        <td style="font-weight:700;color:var(--crm-success)">₱{{ number_format($deal->value) }}</td>
                        <td>
                            <div style="display:flex;align-items:center;gap:8px">
                                <div style="flex:1;height:4px;background:var(--crm-border);border-radius:4px">
                                    <div style="width:{{ $deal->probability }}%;height:100%;background:var(--crm-primary);border-radius:4px"></div>
                                </div>
                                <span style="font-size:12px;color:var(--crm-muted);width:30px">{{ $deal->probability }}%</span>
                            </div>
                        </td>
                        <td style="font-size:12px;color:var(--crm-muted)">
                            {{ $deal->expected_close_date ? $deal->expected_close_date->format('M d, Y') : '—' }}
                        </td>
                        <td style="font-size:12px">{{ $deal->assignedUser?->name ?? '—' }}</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    @if(auth()->user()->isAdminOrManager())
                                    <li><a class="dropdown-item" href="#" onclick="editDeal({{ $deal->id }}); return false"><i class="fas fa-pen"></i> Edit</a></li>
                                    @endif
                                    @if(auth()->user()->isAdmin())
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger" href="#" onclick="deleteDeal({{ $deal->id }}, '{{ addslashes($deal->title) }}'); return false"><i class="fas fa-trash"></i> Delete</a></li>
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
            <span style="font-size:12px;color:var(--crm-muted)">Showing {{ $deals->firstItem() }}–{{ $deals->lastItem() }} of {{ $deals->total() }}</span>
            {{ $deals->links() }}
        </div>
        @else
        <div class="empty-state">
            <div class="empty-icon"><i class="fas fa-handshake"></i></div>
            <h5>No deals found</h5>
            <p>Start adding deals to track your sales progress.</p>
            <button class="btn-crm-primary" onclick="openModal('addModal')"><i class="fas fa-plus"></i> Add Deal</button>
        </div>
        @endif
    </div>
</div>

<!-- ── KANBAN VIEW ── -->
<div id="kanbanView" style="display:none">
    @php
    $stages = [
        'prospecting'   => ['label'=>'Prospecting',   'color'=>'#64748b'],
        'qualification' => ['label'=>'Qualification',  'color'=>'#06b6d4'],
        'proposal'      => ['label'=>'Proposal',       'color'=>'#3b82f6'],
        'negotiation'   => ['label'=>'Negotiation',    'color'=>'#f59e0b'],
        'closed_won'    => ['label'=>'Closed Won',     'color'=>'#10b981'],
        'closed_lost'   => ['label'=>'Closed Lost',    'color'=>'#ef4444'],
    ];
    @endphp
    <div style="display:flex;gap:14px;overflow-x:auto;padding-bottom:16px">
        @foreach($stages as $stageKey => $stageInfo)
        <div style="min-width:240px;max-width:240px">
            <div style="background:var(--crm-card);border:1px solid var(--crm-border);border-radius:12px;overflow:hidden">
                <div style="padding:12px 16px;border-bottom:3px solid {{ $stageInfo['color'] }};display:flex;justify-content:space-between;align-items:center">
                    <span style="font-family:'Syne',sans-serif;font-size:13px;font-weight:700;color:{{ $stageInfo['color'] }}">
                        {{ $stageInfo['label'] }}
                    </span>
                    <span style="background:rgba(255,255,255,.08);color:var(--crm-muted);font-size:11px;font-weight:700;padding:2px 8px;border-radius:10px">
                        {{ $kanban[$stageKey]->count() }}
                    </span>
                </div>
                <div style="padding:10px;min-height:120px;display:flex;flex-direction:column;gap:8px">
                    @forelse($kanban[$stageKey] as $deal)
                    <div style="background:var(--crm-bg);border:1px solid var(--crm-border);border-radius:8px;padding:12px;cursor:pointer"
                         onclick="editDeal({{ $deal->id }})">
                        <div style="font-size:13px;font-weight:600;margin-bottom:4px">{{ Str::limit($deal->title,32) }}</div>
                        @if($deal->contact)
                            <div style="font-size:11px;color:var(--crm-muted)">{{ $deal->contact->full_name }}</div>
                        @endif
                        <div style="margin-top:8px;font-size:13px;font-weight:700;color:{{ $stageInfo['color'] }}">
                            ₱{{ number_format($deal->value) }}
                        </div>
                        <div style="margin-top:6px;height:3px;background:var(--crm-border);border-radius:3px">
                            <div style="width:{{ $deal->probability }}%;height:100%;background:{{ $stageInfo['color'] }};border-radius:3px;transition:width .3s"></div>
                        </div>
                        <div style="font-size:10px;color:var(--crm-muted);margin-top:3px">{{ $deal->probability }}% probability</div>
                    </div>
                    @empty
                    <div style="text-align:center;padding:20px;color:var(--crm-muted);font-size:12px">
                        <i class="fas fa-inbox" style="font-size:20px;margin-bottom:6px;display:block"></i>No deals
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- ── Add Modal ── -->
<div class="modal fade crm-modal" id="addModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-handshake me-2" style="color:var(--crm-success)"></i>New Deal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addForm">
                    <div class="row g-3">
                        <div class="col-12"><label class="crm-label">Deal Title *</label><input type="text" name="title" class="crm-input" placeholder="e.g. Enterprise Software Package"></div>
                        <div class="col-md-6">
                            <label class="crm-label">Contact</label>
                            <select name="contact_id" class="crm-input"><option value="">— Select Contact —</option>@foreach($contacts as $c)<option value="{{ $c->id }}">{{ $c->full_name }}</option>@endforeach</select>
                        </div>
                        <div class="col-md-6">
                            <label class="crm-label">Company</label>
                            <select name="company_id" class="crm-input"><option value="">— Select Company —</option>@foreach($companies as $c)<option value="{{ $c->id }}">{{ $c->name }}</option>@endforeach</select>
                        </div>
                        <div class="col-md-6">
                            <label class="crm-label">Linked Lead</label>
                            <select name="lead_id" class="crm-input"><option value="">— Select Lead —</option>@foreach($leads as $l)<option value="{{ $l->id }}">{{ $l->title }}</option>@endforeach</select>
                        </div>
                        <div class="col-md-6">
                            <label class="crm-label">Assigned To</label>
                            <select name="assigned_to" class="crm-input"><option value="">— Select User —</option>@foreach($users as $u)<option value="{{ $u->id }}">{{ $u->name }}</option>@endforeach</select>
                        </div>
                        <div class="col-md-4"><label class="crm-label">Value ($) *</label><input type="number" name="value" class="crm-input" placeholder="10000"></div>
                        <div class="col-md-4">
                            <label class="crm-label">Stage *</label>
                            <select name="stage" class="crm-input">
                                @foreach(['prospecting','qualification','proposal','negotiation','closed_won','closed_lost'] as $s)
                                    <option value="{{ $s }}">{{ ucwords(str_replace('_',' ',$s)) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4"><label class="crm-label">Probability (%)</label><input type="number" name="probability" class="crm-input" placeholder="50" min="0" max="100"></div>
                        <div class="col-md-6"><label class="crm-label">Expected Close Date</label><input type="date" name="expected_close_date" class="crm-input"></div>
                        <div class="col-12"><label class="crm-label">Notes</label><textarea name="notes" class="crm-input" rows="3" placeholder="Any notes…"></textarea></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn-crm-secondary" data-bs-dismiss="modal">Cancel</button>
                <button class="btn-crm-primary" onclick="submitAdd()"><i class="fas fa-save"></i> Save Deal</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade crm-modal" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-pen me-2" style="color:var(--crm-warning)"></i>Edit Deal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editForm">
                    <input type="hidden" id="edit_id">
                    <div class="row g-3">
                        <div class="col-12"><label class="crm-label">Deal Title *</label><input type="text" name="title" id="edit_title" class="crm-input"></div>
                        <div class="col-md-6">
                            <label class="crm-label">Contact</label>
                            <select name="contact_id" id="edit_contact_id" class="crm-input"><option value="">— Select Contact —</option>@foreach($contacts as $c)<option value="{{ $c->id }}">{{ $c->full_name }}</option>@endforeach</select>
                        </div>
                        <div class="col-md-6">
                            <label class="crm-label">Company</label>
                            <select name="company_id" id="edit_company_id" class="crm-input"><option value="">— Select Company —</option>@foreach($companies as $c)<option value="{{ $c->id }}">{{ $c->name }}</option>@endforeach</select>
                        </div>
                        <div class="col-md-4"><label class="crm-label">Value ($)</label><input type="number" name="value" id="edit_value" class="crm-input"></div>
                        <div class="col-md-4">
                            <label class="crm-label">Stage *</label>
                            <select name="stage" id="edit_stage" class="crm-input">
                                @foreach(['prospecting','qualification','proposal','negotiation','closed_won','closed_lost'] as $s)
                                    <option value="{{ $s }}">{{ ucwords(str_replace('_',' ',$s)) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4"><label class="crm-label">Probability (%)</label><input type="number" name="probability" id="edit_probability" class="crm-input" min="0" max="100"></div>
                        <div class="col-md-6"><label class="crm-label">Expected Close Date</label><input type="date" name="expected_close_date" id="edit_expected_close_date" class="crm-input"></div>
                        <div class="col-md-6">
                            <label class="crm-label">Assigned To</label>
                            <select name="assigned_to" id="edit_assigned_to" class="crm-input"><option value="">— Select —</option>@foreach($users as $u)<option value="{{ $u->id }}">{{ $u->name }}</option>@endforeach</select>
                        </div>
                        <div class="col-12"><label class="crm-label">Notes</label><textarea name="notes" id="edit_notes" class="crm-input" rows="3"></textarea></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn-crm-secondary" data-bs-dismiss="modal">Cancel</button>
                <button class="btn-crm-primary" onclick="submitEdit()"><i class="fas fa-save"></i> Update Deal</button>
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
            <div class="modal-body"><p style="font-size:14px;color:var(--crm-muted)">Delete deal <strong id="delete_name" style="color:var(--crm-text)"></strong>?</p></div>
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
const BASE = '{{ route("admin.deals.index") }}';
let deleteId = null, showingKanban = false;
function openModal(id) { new bootstrap.Modal(document.getElementById(id)).show(); }

function toggleView() {
    showingKanban = !showingKanban;
    document.getElementById('listView').style.display = showingKanban ? 'none' : 'block';
    document.getElementById('kanbanView').style.display = showingKanban ? 'block' : 'none';
    document.getElementById('viewToggleBtn').innerHTML = showingKanban
        ? '<i class="fas fa-list"></i> List View'
        : '<i class="fas fa-columns"></i> Kanban View';
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

async function editDeal(id) {
    const res = await fetch(`${BASE}/${id}/edit`);
    const d = await res.json();
    document.getElementById('edit_id').value = d.id;
    ['title','value','stage','probability','notes'].forEach(f => { const el=document.getElementById(`edit_${f}`); if(el) el.value=d[f]??''; });
    document.getElementById('edit_contact_id').value = d.contact_id ?? '';
    document.getElementById('edit_company_id').value = d.company_id ?? '';
    document.getElementById('edit_assigned_to').value = d.assigned_to ?? '';
    document.getElementById('edit_expected_close_date').value = d.expected_close_date ? d.expected_close_date.substring(0,10) : '';
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

function deleteDeal(id, name) { deleteId=id; document.getElementById('delete_name').textContent=name; openModal('deleteModal'); }

async function confirmDelete() {
    const res = await fetch(`${BASE}/${deleteId}`, { method:'DELETE', headers:{'X-CSRF-TOKEN':CSRF} });
    const json = await res.json();
    bootstrap.Modal.getInstance(document.getElementById('deleteModal')).hide();
    showToast(json.message,'success'); setTimeout(() => location.reload(), 800);
}
</script>
@endpush
