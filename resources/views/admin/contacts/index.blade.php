<!-- @extends('layouts.app')
@section('title', 'Customers')
@section('breadcrumb', 'Customers')

@section('content')

{{-- Scoped styles injected inline so they always load regardless of @stack support --}}
<style>
@import url('https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400;0,500;0,600;0,700&family=DM+Mono:wght@400;500&display=swap');

/* ── Design tokens (scoped to .cx wrapper) ── */
.cx {
    --cx-bg:         #f4f6fa;
    --cx-surface:    #ffffff;
    --cx-border:     #e3e7ef;
    --cx-border-2:   #cdd3df;
    --cx-text:       #0f172a;
    --cx-muted:      #64748b;
    --cx-faint:      #94a3b8;
    --cx-blue:       #2563eb;
    --cx-blue-dk:    #1d4ed8;
    --cx-blue-bg:    #eff6ff;
    --cx-green:      #059669;
    --cx-green-bg:   #ecfdf5;
    --cx-amber:      #d97706;
    --cx-amber-bg:   #fefce8;
    --cx-violet:     #7c3aed;
    --cx-violet-bg:  #f5f3ff;
    --cx-red:        #dc2626;
    --cx-red-bg:     #fef2f2;
    --cx-r:          8px;
    --cx-r-lg:       12px;
    --cx-shadow:     0 1px 3px rgba(0,0,0,.07), 0 1px 2px rgba(0,0,0,.04);
    --cx-shadow-md:  0 4px 16px rgba(0,0,0,.08), 0 2px 6px rgba(0,0,0,.04);
    --cx-shadow-lg:  0 16px 48px rgba(0,0,0,.12), 0 4px 14px rgba(0,0,0,.06);
    font-family: 'Instrument Sans', system-ui, sans-serif;
    color: var(--cx-text);
}

/* ─── Reset inside wrapper ─── */
.cx *, .cx *::before, .cx *::after { box-sizing: border-box; }

/* ─── Page Header ─── */
.cx-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 24px;
}
.cx-header-left h1 {
    font-size: 20px;
    font-weight: 700;
    letter-spacing: -.3px;
    margin: 0 0 2px;
    color: var(--cx-text);
    display: flex;
    align-items: center;
    gap: 8px;
}
.cx-header-left h1 i { color: var(--cx-blue); font-size: 18px; }
.cx-header-left p  { font-size: 13px; color: var(--cx-muted); margin: 0; }
.cx-header-actions { display: flex; gap: 8px; }

/* ─── Buttons ─── */
.cx-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    border-radius: var(--cx-r);
    font-size: 13px;
    font-weight: 600;
    font-family: inherit;
    cursor: pointer;
    border: none;
    text-decoration: none;
    white-space: nowrap;
    transition: all .15s;
    line-height: 1;
}
.cx-btn-primary {
    background: var(--cx-blue);
    color: #fff !important;
    box-shadow: 0 1px 3px rgba(37,99,235,.3);
}
.cx-btn-primary:hover {
    background: var(--cx-blue-dk);
    box-shadow: 0 4px 12px rgba(37,99,235,.35);
    transform: translateY(-1px);
    text-decoration: none;
}
.cx-btn-ghost {
    background: var(--cx-surface);
    color: var(--cx-text) !important;
    border: 1.5px solid var(--cx-border);
}
.cx-btn-ghost:hover {
    border-color: var(--cx-border-2);
    background: var(--cx-bg);
    transform: translateY(-1px);
    text-decoration: none;
}
.cx-btn-danger {
    background: var(--cx-red-bg);
    color: var(--cx-red) !important;
    border: 1.5px solid #fecaca;
}
.cx-btn-danger:hover { background: #fee2e2; border-color: #f87171; }

/* ─── Stat Grid ─── */
.cx-stats {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 14px;
    margin-bottom: 20px;
}
@media (max-width: 900px) { .cx-stats { grid-template-columns: repeat(2, 1fr); } }

.cx-stat {
    background: var(--cx-surface);
    border: 1px solid var(--cx-border);
    border-radius: var(--cx-r-lg);
    padding: 18px 20px 16px;
    box-shadow: var(--cx-shadow);
    position: relative;
    overflow: hidden;
    transition: transform .18s, box-shadow .18s;
}
.cx-stat:hover { transform: translateY(-2px); box-shadow: var(--cx-shadow-md); }
.cx-stat::before {
    content: '';
    position: absolute;
    inset: 0 0 auto 0;
    height: 3px;
    background: var(--s-color);
    border-radius: var(--cx-r-lg) var(--cx-r-lg) 0 0;
}
.cx-stat-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 12px;
}
.cx-stat-icon {
    width: 38px; height: 38px;
    border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    background: var(--s-bg);
    color: var(--s-color);
    font-size: 15px;
}
.cx-stat-num {
    font-size: 28px;
    font-weight: 700;
    letter-spacing: -1px;
    color: var(--cx-text);
    line-height: 1;
    margin-bottom: 3px;
}
.cx-stat-label {
    font-size: 12px;
    font-weight: 500;
    color: var(--cx-muted);
}

/* ─── Filter Bar ─── */
.cx-filter {
    background: var(--cx-surface);
    border: 1px solid var(--cx-border);
    border-radius: var(--cx-r-lg);
    padding: 14px 18px;
    margin-bottom: 18px;
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
    box-shadow: var(--cx-shadow);
}
.cx-search {
    position: relative;
    flex: 1;
    min-width: 200px;
}
.cx-search i {
    position: absolute;
    left: 12px; top: 50%;
    transform: translateY(-50%);
    color: var(--cx-faint);
    font-size: 12px;
    pointer-events: none;
}
.cx-input {
    width: 100%;
    padding: 8px 12px 8px 34px;
    border: 1.5px solid var(--cx-border);
    border-radius: var(--cx-r);
    font-size: 13px;
    font-family: inherit;
    color: var(--cx-text);
    background: var(--cx-bg);
    outline: none;
    transition: border-color .15s, box-shadow .15s;
}
.cx-input:focus {
    border-color: var(--cx-blue);
    box-shadow: 0 0 0 3px rgba(37,99,235,.1);
    background: #fff;
}
.cx-select {
    padding: 8px 12px;
    border: 1.5px solid var(--cx-border);
    border-radius: var(--cx-r);
    font-size: 13px;
    font-family: inherit;
    color: var(--cx-text);
    background: var(--cx-bg);
    outline: none;
    cursor: pointer;
    min-width: 148px;
    transition: border-color .15s, box-shadow .15s;
}
.cx-select:focus {
    border-color: var(--cx-blue);
    box-shadow: 0 0 0 3px rgba(37,99,235,.1);
    background: #fff;
}
.cx-clear {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    color: var(--cx-muted);
    font-size: 12.5px;
    font-weight: 500;
    text-decoration: none;
    padding: 4px 8px;
    border-radius: var(--cx-r);
    transition: color .12s, background .12s;
}
.cx-clear:hover { color: var(--cx-red); background: var(--cx-red-bg); text-decoration: none; }

/* ─── Table Card ─── */
.cx-card {
    background: var(--cx-surface);
    border: 1px solid var(--cx-border);
    border-radius: var(--cx-r-lg);
    box-shadow: var(--cx-shadow);
    overflow: hidden;
}
.cx-card-head {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 16px 20px;
    border-bottom: 1px solid var(--cx-border);
    background: #fafbfd;
}
.cx-card-head h5 {
    font-size: 14px;
    font-weight: 700;
    margin: 0;
    color: var(--cx-text);
}
.cx-card-head .spacer { flex: 1; }
.cx-badge-count {
    background: var(--cx-blue-bg);
    color: var(--cx-blue);
    font-size: 11.5px;
    font-weight: 700;
    padding: 2px 10px;
    border-radius: 20px;
    font-family: 'DM Mono', monospace;
}

/* ─── Table ─── */
.cx-table { width: 100%; border-collapse: collapse; }
.cx-table thead th {
    padding: 10px 14px;
    font-size: 10.5px;
    font-weight: 700;
    letter-spacing: .7px;
    text-transform: uppercase;
    color: var(--cx-faint);
    background: #f8fafc;
    border-bottom: 1px solid var(--cx-border);
    white-space: nowrap;
}
.cx-table thead th:first-child { padding-left: 20px; }
.cx-table thead th:last-child  { padding-right: 20px; text-align: right; }

.cx-table tbody tr {
    border-bottom: 1px solid var(--cx-border);
    transition: background .1s;
}
.cx-table tbody tr:last-child { border-bottom: none; }
.cx-table tbody tr:hover { background: #f8fafd; }

.cx-table tbody td {
    padding: 13px 14px;
    font-size: 13px;
    vertical-align: middle;
}
.cx-table tbody td:first-child { padding-left: 20px; }
.cx-table tbody td:last-child  { padding-right: 20px; }

/* ─── Avatar ─── */
.cx-avatar {
    width: 34px; height: 34px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 12px; font-weight: 700;
    flex-shrink: 0;
}
.cx-contact-name { font-weight: 600; font-size: 13px; color: var(--cx-text); }
.cx-contact-role { font-size: 11.5px; color: var(--cx-faint); margin-top: 1px; }

/* ─── Status badges ─── */
.cx-status {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 11.5px;
    font-weight: 600;
    padding: 3px 9px;
    border-radius: 20px;
    white-space: nowrap;
}
.cx-status::before {
    content: '';
    width: 5px; height: 5px;
    border-radius: 50%;
    background: currentColor;
    opacity: .65;
    flex-shrink: 0;
}
.cx-s-customer { background: var(--cx-green-bg);  color: var(--cx-green); }
.cx-s-lead     { background: var(--cx-amber-bg);  color: var(--cx-amber); }
.cx-s-prospect { background: var(--cx-blue-bg);   color: var(--cx-blue);  }
.cx-s-inactive { background: #f1f5f9; color: #64748b; }

/* ─── Action icon buttons ─── */
.cx-actions { display: inline-flex; gap: 8px; justify-content: flex-end; flex-wrap: wrap; }
.cx-icon-btn {
    width: 36px; height: 36px;
    border-radius: 14px;
    border: 1.5px solid var(--cx-border);
    background: var(--cx-surface);
    color: var(--cx-faint);
    display: inline-flex; align-items: center; justify-content: center;
    font-size: 13px; cursor: pointer;
    transition: all .15s ease;
}
.cx-icon-btn:hover { transform: translateY(-1px); border-color: #cbd5e1; }
.cx-icon-btn.edit { color: #2563eb; }
.cx-icon-btn.edit:hover { background: rgba(37,99,235,.08); border-color: #93c5fd; }
.cx-icon-btn.del { color: #dc2626; }
.cx-icon-btn.del:hover  { background: rgba(239,68,68,.1); border-color: #fca5a5; }

/* ─── Table footer ─── */
.cx-table-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 20px;
    border-top: 1px solid var(--cx-border);
    font-size: 12px;
    color: var(--cx-muted);
    flex-wrap: wrap;
    gap: 10px;
    background: #fafbfd;
}

/* ─── Empty state ─── */
.cx-empty {
    padding: 72px 24px;
    text-align: center;
    color: var(--cx-muted);
}
.cx-empty-icon {
    width: 68px; height: 68px;
    border-radius: 50%;
    border: 2px dashed var(--cx-border-2);
    background: var(--cx-bg);
    display: flex; align-items: center; justify-content: center;
    font-size: 24px; color: var(--cx-faint);
    margin: 0 auto 16px;
}
.cx-empty h5 { font-size: 15px; font-weight: 700; color: var(--cx-text); margin: 0 0 6px; }
.cx-empty p  { font-size: 13px; margin: 0 0 18px; }

/* ─── Modals ─── */
.cx-modal .modal-content {
    border: 1px solid var(--cx-border) !important;
    border-radius: var(--cx-r-lg) !important;
    box-shadow: var(--cx-shadow-lg) !important;
    font-family: 'Instrument Sans', system-ui, sans-serif;
    overflow: hidden;
}
.cx-modal .modal-header {
    padding: 18px 22px 14px;
    border-bottom: 1px solid var(--cx-border) !important;
    background: #f8fafc !important;
}
.cx-modal .modal-title {
    font-size: 15px;
    font-weight: 700;
    color: var(--cx-text);
    display: flex;
    align-items: center;
    gap: 8px;
}
.cx-modal .modal-body  { padding: 22px; background: var(--cx-surface); }
.cx-modal .modal-footer {
    padding: 14px 22px;
    border-top: 1px solid var(--cx-border) !important;
    background: #f8fafc !important;
    display: flex;
    justify-content: flex-end;
    gap: 8px;
}

/* ─── Form fields ─── */
.cx-field { display: flex; flex-direction: column; gap: 4px; }
.cx-label {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .5px;
    color: var(--cx-muted);
}
.cx-field-input {
    padding: 8px 12px;
    border: 1.5px solid var(--cx-border) !important;
    border-radius: var(--cx-r);
    font-size: 13px;
    font-family: 'Instrument Sans', system-ui, sans-serif;
    color: var(--cx-text);
    background: var(--cx-bg);
    outline: none;
    transition: border-color .15s, box-shadow .15s;
    width: 100%;
    box-shadow: none !important;
}
.cx-field-input:focus {
    border-color: var(--cx-blue) !important;
    box-shadow: 0 0 0 3px rgba(37,99,235,.1) !important;
    background: #fff;
}
.cx-field-input.invalid { border-color: var(--cx-red) !important; }
.cx-field-error {
    font-size: 11.5px;
    color: var(--cx-red);
    display: none;
}
.cx-field-error.show { display: block; }

/* Form section dividers */
.cx-form-section {
    font-size: 10.5px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .7px;
    color: var(--cx-faint);
    padding-bottom: 8px;
    border-bottom: 1px solid var(--cx-border);
    margin-top: 4px;
}

/* ─── Delete modal ─── */
.cx-delete-body {
    display: flex;
    gap: 14px;
    align-items: flex-start;
}
.cx-delete-icon {
    width: 42px; height: 42px;
    border-radius: 50%;
    background: var(--cx-red-bg);
    color: var(--cx-red);
    display: flex; align-items: center; justify-content: center;
    font-size: 16px;
    flex-shrink: 0;
}
.cx-delete-text h6  { font-size: 14.5px; font-weight: 700; margin: 0 0 4px; color: var(--cx-text); }
.cx-delete-text p   { font-size: 13px; color: var(--cx-muted); margin: 0; line-height: 1.5; }

/* ─── Mono font util ─── */
.cx-mono { font-family: 'DM Mono', monospace; font-size: 12px; }
</style>

{{-- ══════════════════════════════════════════ --}}
{{-- All content scoped inside .cx wrapper     --}}
{{-- ══════════════════════════════════════════ --}}
<div class="cx">

    {{-- ── Page Header ── --}}
    <div class="cx-header">
        <div class="cx-header-left">
            <h1><i class="fas fa-users"></i> Customers</h1>
            <p>Manage your customers, leads, and prospects from one dashboard.</p>
        </div>
        <div class="cx-header-actions">
            <a href="{{ route('admin.contacts.export') }}" class="cx-btn cx-btn-ghost">
                <i class="fas fa-file-arrow-down"></i> Export Customers
            </a>
            <button class="cx-btn cx-btn-primary" onclick="openModal('addModal')">
                <i class="fas fa-plus"></i> New Customer
            </button>
        </div>
    </div>

    {{-- ── Stat Cards ── --}}
    @php
    $statItems = [
        ['label'=>'Total Customers','value'=>$stats['total'],    'icon'=>'fa-users',      'color'=>'#2563eb','bg'=>'#dbeafe'],
        ['label'=>'Customers',      'value'=>$stats['customer'], 'icon'=>'fa-user-check', 'color'=>'#059669','bg'=>'#d1fae5'],
        ['label'=>'Leads',          'value'=>$stats['lead'],     'icon'=>'fa-bullseye',   'color'=>'#d97706','bg'=>'#fef9c3'],
        ['label'=>'Prospects',      'value'=>$stats['prospect'], 'icon'=>'fa-user-clock', 'color'=>'#7c3aed','bg'=>'#ede9fe'],
    ];
    @endphp
    <div class="cx-stats">
        @foreach($statItems as $s)
        <div class="cx-stat" style="--s-color:{{ $s['color'] }};--s-bg:{{ $s['bg'] }}">
            <div class="cx-stat-top">
                <div class="cx-stat-icon"><i class="fas {{ $s['icon'] }}"></i></div>
            </div>
            <div class="cx-stat-num">{{ number_format($s['value']) }}</div>
            <div class="cx-stat-label">{{ $s['label'] }}</div>
        </div>
        @endforeach
    </div>

    {{-- ── Filter Bar ── --}}
    <div class="cx-filter">
        <form method="GET" action="{{ route('admin.contacts.index') }}" style="display:contents">
            <div class="cx-search">
                <i class="fas fa-search"></i>
                <input type="text" name="search" class="cx-input"
                       placeholder="Search by name, email, company…"
                       value="{{ request('search') }}">
            </div>
            <select name="status" class="cx-select">
                <option value="">All Statuses</option>
                @foreach(['customer'=>'Customer','lead'=>'Lead','prospect'=>'Prospect','inactive'=>'Inactive'] as $val => $lbl)
                    <option value="{{ $val }}" {{ request('status') == $val ? 'selected' : '' }}>{{ $lbl }}</option>
                @endforeach
            </select>
            <button type="submit" class="cx-btn cx-btn-primary" style="padding:8px 16px">
                <i class="fas fa-filter"></i> Filter
            </button>
            @if(request('search') || request('status'))
                <a href="{{ route('admin.contacts.index') }}" class="cx-clear">
                    <i class="fas fa-xmark"></i> Clear
                </a>
            @endif
        </form>
    </div>

    {{-- ── Table Card ── --}}
    <div class="cx-card">
        <div class="cx-card-head">
            <i class="fas fa-address-book" style="color:#2563eb;font-size:14px"></i>
            <h5>All Customers</h5>
            <div class="spacer"></div>
            <span class="cx-badge-count">{{ $contacts->total() }} records</span>
        </div>

        @if($contacts->count())
        <div style="overflow-x:auto">
            <table class="cx-table">
                <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Company</th>
                        <th>Status</th>
                        <th>Location</th>
                        <th>Added</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $colors = ['#3b82f6','#10b981','#f59e0b','#8b5cf6','#ef4444','#06b6d4','#ec4899','#84cc16'];
                    $bgs    = ['#dbeafe','#d1fae5','#fef3c7','#ede9fe','#fee2e2','#cffafe','#fce7f3','#dcfce7'];
                    @endphp
                    @foreach($contacts as $contact)
                    @php
                        $ci  = $contact->id % 8;
                        $col = $colors[$ci];
                        $bg  = $bgs[$ci];
                        $badgeClass = match($contact->status) {
                            'customer' => 'cx-s-customer',
                            'lead'     => 'cx-s-lead',
                            'prospect' => 'cx-s-prospect',
                            default    => 'cx-s-inactive',
                        };
                    @endphp
                    <tr>
                        <td>
                            <div style="display:flex;align-items:center;gap:10px">
                                <div class="cx-avatar" style="background:{{ $bg }};color:{{ $col }}">
                                    {{ $contact->initials }}
                                </div>
                                <div>
                                    <div class="cx-contact-name">{{ $contact->full_name }}</div>
                                    @if($contact->job_title)
                                    <div class="cx-contact-role">{{ $contact->job_title }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            <a href="mailto:{{ $contact->email }}"
                               style="color:#64748b;font-size:12.5px;text-decoration:none;transition:color .12s"
                               onmouseover="this.style.color='#2563eb'"
                               onmouseout="this.style.color='#64748b'">
                                {{ $contact->email }}
                            </a>
                        </td>
                        <td class="cx-mono" style="color:#94a3b8">{{ formatPhilippinePhone($contact->phone) }}</td>
                        <td style="font-size:13px;font-weight:500;color:#334155">{{ $contact->company ?? '—' }}</td>
                        <td><span class="cx-status {{ $badgeClass }}">{{ ucfirst($contact->status) }}</span></td>
                        <td style="font-size:12px;color:#94a3b8">
                            {{ collect([$contact->city, $contact->country])->filter()->join(', ') ?: '—' }}
                        </td>
                        <td class="cx-mono" style="color:#94a3b8">{{ $contact->created_at->format('M d, Y') }}</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="{{ route('admin.contacts.show', $contact->id) }}"><i class="fas fa-eye"></i> View</a></li>
                                    @if(auth()->user()->canManageCustomersAndLeads())
                                    <li><a class="dropdown-item" href="#" onclick="editContact({{ $contact->id }}); return false"><i class="fas fa-pen"></i> Edit</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger" href="#" onclick="deleteCustomer({{ $contact->id }}, '{{ addslashes($contact->full_name) }}'); return false"><i class="fas fa-trash"></i> Delete</a></li>
                                    @endif
                                </ul>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="cx-table-footer">
            <span>Showing <strong>{{ $contacts->firstItem() }}–{{ $contacts->lastItem() }}</strong> of <strong>{{ $contacts->total() }}</strong> customers</span>
            {{ $contacts->links() }}
        </div>

        @else
        <div class="cx-empty">
            <div class="cx-empty-icon"><i class="fas fa-users"></i></div>
            <h5>No customers found</h5>
            <p>Add your first customer or adjust the filters above.</p>
            <button class="cx-btn cx-btn-primary" onclick="openModal('addModal')">
                <i class="fas fa-plus"></i> Add Customer
            </button>
        </div>
        @endif
    </div>

    {{-- ── Add Modal ── --}}
    <div class="modal fade cx-modal" id="addModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="modal-title">
                        <i class="fas fa-user-plus" style="color:#2563eb"></i>
                        New Customer
                    </span>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addForm">
                        @csrf
                        <div class="row g-3">
                            <div class="col-12"><div class="cx-form-section">Personal Info</div></div>
                            <div class="col-md-6">
                                <div class="cx-field">
                                    <label class="cx-label">First Name <span style="color:#dc2626">*</span></label>
                                    <input type="text" name="first_name" class="cx-field-input" placeholder="John">
                                    <span class="cx-field-error" id="add_err_first_name"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="cx-field">
                                    <label class="cx-label">Last Name <span style="color:#dc2626">*</span></label>
                                    <input type="text" name="last_name" class="cx-field-input" placeholder="Doe">
                                    <span class="cx-field-error" id="add_err_last_name"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="cx-field">
                                    <label class="cx-label">Email <span style="color:#dc2626">*</span></label>
                                    <input type="email" name="email" class="cx-field-input" placeholder="john@example.com">
                                    <span class="cx-field-error" id="add_err_email"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="cx-field">
                                    <label class="cx-label">Phone</label>
                                    <input type="text" name="phone" class="cx-field-input" placeholder="+63 9XX XXX XXXX">
                                </div>
                            </div>
                            <div class="col-12"><div class="cx-form-section">Work Details</div></div>
                            <div class="col-md-6">
                                <div class="cx-field">
                                    <label class="cx-label">Job Title</label>
                                    <input type="text" name="job_title" class="cx-field-input" placeholder="Sales Manager">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="cx-field">
                                    <label class="cx-label">Company</label>
                                    <input type="text" name="company" class="cx-field-input" placeholder="Acme Corp">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="cx-field">
                                    <label class="cx-label">Status <span style="color:#dc2626">*</span></label>
                                    <select name="status" class="cx-field-input">
                                        <option value="prospect">Prospect</option>
                                        <option value="lead">Lead</option>
                                        <option value="customer">Customer</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="cx-field">
                                    <label class="cx-label">City</label>
                                    <input type="text" name="city" class="cx-field-input" placeholder="New York">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="cx-field">
                                    <label class="cx-label">Country</label>
                                    <input type="text" name="country" class="cx-field-input" placeholder="USA">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="cx-field">
                                    <label class="cx-label">Notes</label>
                                    <textarea name="notes" class="cx-field-input" rows="3"
                                              placeholder="Anything worth noting…" style="resize:vertical"></textarea>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="cx-btn cx-btn-ghost" data-bs-dismiss="modal">Cancel</button>
                    <button class="cx-btn cx-btn-primary" onclick="submitAdd()">
                        <i class="fas fa-check"></i> Save Customer
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Edit Modal ── --}}
    <div class="modal fade cx-modal" id="editModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="modal-title">
                        <i class="fas fa-pen" style="color:#d97706"></i>
                        Edit Customer
                    </span>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm">
                        @csrf @method('PUT')
                        <input type="hidden" name="contact_id" id="edit_id">
                        <div class="row g-3">
                            <div class="col-12"><div class="cx-form-section">Personal Info</div></div>
                            <div class="col-md-6">
                                <div class="cx-field">
                                    <label class="cx-label">First Name <span style="color:#dc2626">*</span></label>
                                    <input type="text" name="first_name" id="edit_first_name" class="cx-field-input">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="cx-field">
                                    <label class="cx-label">Last Name <span style="color:#dc2626">*</span></label>
                                    <input type="text" name="last_name" id="edit_last_name" class="cx-field-input">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="cx-field">
                                    <label class="cx-label">Email <span style="color:#dc2626">*</span></label>
                                    <input type="email" name="email" id="edit_email" class="cx-field-input">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="cx-field">
                                    <label class="cx-label">Phone</label>
                                    <input type="text" name="phone" id="edit_phone" class="cx-field-input">
                                </div>
                            </div>
                            <div class="col-12"><div class="cx-form-section">Work Details</div></div>
                            <div class="col-md-6">
                                <div class="cx-field">
                                    <label class="cx-label">Job Title</label>
                                    <input type="text" name="job_title" id="edit_job_title" class="cx-field-input">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="cx-field">
                                    <label class="cx-label">Company</label>
                                    <input type="text" name="company" id="edit_company" class="cx-field-input">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="cx-field">
                                    <label class="cx-label">Status <span style="color:#dc2626">*</span></label>
                                    <select name="status" id="edit_status" class="cx-field-input">
                                        <option value="prospect">Prospect</option>
                                        <option value="lead">Lead</option>
                                        <option value="customer">Customer</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="cx-field">
                                    <label class="cx-label">City</label>
                                    <input type="text" name="city" id="edit_city" class="cx-field-input">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="cx-field">
                                    <label class="cx-label">Country</label>
                                    <input type="text" name="country" id="edit_country" class="cx-field-input">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="cx-field">
                                    <label class="cx-label">Notes</label>
                                    <textarea name="notes" id="edit_notes" class="cx-field-input" rows="3"
                                              style="resize:vertical"></textarea>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="cx-btn cx-btn-ghost" data-bs-dismiss="modal">Cancel</button>
                    <button class="cx-btn cx-btn-primary" onclick="submitEdit()">
                        <i class="fas fa-check"></i> Update Customer
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Delete Modal ── --}}
    <div class="modal fade cx-modal" id="deleteModal" tabindex="-1">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="modal-title" style="color:#dc2626">
                        <i class="fas fa-triangle-exclamation" style="color:#dc2626"></i>
                        Delete Customer
                    </span>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="cx-delete-body">
                        <div class="cx-delete-icon"><i class="fas fa-trash"></i></div>
                        <div class="cx-delete-text">
                            <h6>Are you sure?</h6>
                            <p>You're permanently deleting <strong id="delete_name"></strong>. This cannot be undone.</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="cx-btn cx-btn-ghost" data-bs-dismiss="modal">Cancel</button>
                    <button class="cx-btn cx-btn-danger" onclick="confirmDelete()">
                        <i class="fas fa-trash"></i> Delete Customer
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>{{-- /.cx --}}
@endsection

@push('scripts')
<script>
const BASE = '{{ route("admin.contacts.index") }}';
let deleteId = null;

function openModal(id) {
    new bootstrap.Modal(document.getElementById(id)).show();
}

async function submitAdd() {
    clearErrors('addForm', 'add');
    const data = Object.fromEntries(new FormData(document.getElementById('addForm')));
    try {
        const res  = await fetch(BASE, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify(data)
        });
        const json = await res.json();
        if (!res.ok) { showErrors(json.errors, 'addForm', 'add'); return; }
        bootstrap.Modal.getInstance(document.getElementById('addModal')).hide();
        showToast(json.message, 'success');
        setTimeout(() => location.reload(), 800);
    } catch { showToast('Something went wrong.', 'error'); }
}

async function editContact(id) {
    try {
        const res = await fetch(`${BASE}/${id}/edit`);
        const c   = await res.json();
        document.getElementById('edit_id').value = c.id;
        ['first_name','last_name','email','phone','job_title','company','status','city','country','notes']
            .forEach(f => { const el = document.getElementById(`edit_${f}`); if (el) el.value = c[f] ?? ''; });
        openModal('editModal');
    } catch { showToast('Failed to load contact.', 'error'); }
}

async function submitEdit() {
    clearErrors('editForm', 'edit');
    const id   = document.getElementById('edit_id').value;
    const data = Object.fromEntries(new FormData(document.getElementById('editForm')));
    data._method = 'PUT';
    try {
        const res  = await fetch(`${BASE}/${id}`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify(data)
        });
        const json = await res.json();
        if (!res.ok) { showErrors(json.errors, 'editForm', 'edit'); return; }
        bootstrap.Modal.getInstance(document.getElementById('editModal')).hide();
        showToast(json.message, 'success');
        setTimeout(() => location.reload(), 800);
    } catch { showToast('Something went wrong.', 'error'); }
}

function deleteCustomer(id, name) {
    deleteId = id;
    document.getElementById('delete_name').textContent = name;
    openModal('deleteModal');
}

async function viewCustomer(id) {
    try {
        const res = await fetch(`${BASE}/${id}`);
        const c   = await res.json();
        document.getElementById('view_full_name').textContent = c.full_name;
        document.getElementById('view_status').textContent = c.status.charAt(0).toUpperCase() + c.status.slice(1);
        document.getElementById('view_email').textContent = c.email || '—';
        document.getElementById('view_phone').textContent = c.phone || '—';
        document.getElementById('view_company').textContent = c.company || '—';
        document.getElementById('view_job_title').textContent = c.job_title || '—';
        document.getElementById('view_location').textContent = [c.city, c.country].filter(Boolean).join(', ') || '—';
        document.getElementById('view_created_at').textContent = new Date(c.created_at).toLocaleDateString();
        document.getElementById('view_notes').textContent = c.notes || '—';
        openModal('viewModal');
    } catch {
        showToast('Failed to load customer details.', 'error');
    }
}

async function confirmDelete() {
    try {
        const res  = await fetch(`${BASE}/${deleteId}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': CSRF }
        });
        const json = await res.json();
        bootstrap.Modal.getInstance(document.getElementById('deleteModal')).hide();
        showToast(json.message, 'success');
        setTimeout(() => location.reload(), 800);
    } catch { showToast('Something went wrong.', 'error'); }
}

function clearErrors(formId, prefix) {
    document.getElementById(formId).querySelectorAll('.cx-field-input')
        .forEach(el => el.classList.remove('invalid'));
    document.getElementById(formId).querySelectorAll('.cx-field-error')
        .forEach(el => { el.textContent = ''; el.classList.remove('show'); });
}

function showErrors(errors, formId, prefix) {
    if (!errors) return;
    Object.entries(errors).forEach(([field, msgs]) => {
        const input = document.querySelector(`#${formId} [name="${field}"]`);
        if (input) input.classList.add('invalid');
        const errEl = document.getElementById(`${prefix}_err_${field}`);
        if (errEl) { errEl.textContent = msgs[0]; errEl.classList.add('show'); }
    });
}
</script>
@endpush -->