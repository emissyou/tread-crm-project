@extends('layouts.app')
@section('title', 'Leads')
@section('breadcrumb', 'Leads')

@push('styles')
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=DM+Mono:wght@400;500&display=swap">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
    /* ── Font: match Customer page ── */
    #leads-page,
    #leads-page *:not(i) {
        font-family: 'DM Sans', sans-serif !important;
    }

    /* ── Stats grid: 2-col mobile, 3-col md, 6-col lg ── */
    #leads-page .row.g-3.mb-4 {
        display: grid !important;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.75rem !important;
    }
    @media (min-width: 576px) {
        #leads-page .row.g-3.mb-4 { grid-template-columns: repeat(3, 1fr); }
    }
    @media (min-width: 992px) {
        #leads-page .row.g-3.mb-4 { grid-template-columns: repeat(6, 1fr); }
    }
    #leads-page .row.g-3.mb-4 > * {
        width: 100% !important;
        max-width: 100% !important;
        padding: 0 !important;
    }

    /* ── Stat cards ── */
    #leads-page .stat-card {
        background: #fff;
        border: 1px solid #eef2ff;
        border-radius: 16px;
        padding: 1rem;
        box-shadow: 0 2px 8px rgba(15,23,42,.05);
    }
    #leads-page .stat-icon {
        border-radius: 10px;
        display: inline-flex !important;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        width: 38px;
        height: 38px;
        font-size: 16px;
    }
    #leads-page .stat-icon i {
        display: inline-block !important;
        visibility: visible !important;
        opacity: 1 !important;
    }
    #leads-page .stat-value {
        font-weight: 800;
        line-height: 1.1;
        margin-top: 8px;
    }
    #leads-page .stat-label {
        font-size: 0.72rem;
        color: #64748b;
        margin-top: 3px;
        font-weight: 500;
    }

    /* ── Filter form responsive ── */
    #leads-page .crm-card-body form {
        display: flex;
        flex-wrap: nowrap;
        gap: 0.5rem;
        align-items: flex-end;
        width: 100%;
    }
    #leads-page .search-bar {
        flex: 1 1 0;
        min-width: 0;
    }
    #leads-page .crm-card-body form .crm-input[name="status"],
    #leads-page .crm-card-body form .crm-input[name="priority"] {
        flex: 0 0 140px;
        width: 140px;
        min-width: 0;
    }
    #leads-page .crm-card-body form .btn-crm-primary,
    #leads-page .crm-card-body form .btn-crm-secondary {
        flex-shrink: 0;
        white-space: nowrap;
    }
    @media (max-width: 575px) {
        #leads-page .crm-card-body form {
            flex-wrap: wrap;
        }
        #leads-page .search-bar {
            flex: 1 1 100%;
        }
        #leads-page .crm-card-body form .crm-input[name="status"],
        #leads-page .crm-card-body form .crm-input[name="priority"] {
            flex: 1 1 calc(50% - 0.25rem);
            width: auto;
        }
    }

    /* ── Table responsive ── */
    #leads-page .crm-table-wrap {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    #leads-page .crm-table {
        min-width: 700px;
    }

    /* ── Mobile card view for rows ── */
    @media (max-width: 575px) {
        #leads-page .crm-table thead { display: none; }
        #leads-page .crm-table,
        #leads-page .crm-table tbody,
        #leads-page .crm-table tr,
        #leads-page .crm-table td { display: block; width: 100%; }
        #leads-page .crm-table tr {
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            margin-bottom: 0.75rem;
            padding: 0.75rem;
            background: #fff;
            box-shadow: 0 1px 4px rgba(0,0,0,.06);
        }
        #leads-page .crm-table td {
            border: none;
            padding: 0.3rem 0;
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
            font-size: 0.82rem;
        }
        #leads-page .crm-table td::before {
            content: attr(data-label);
            font-weight: 600;
            font-size: 0.68rem;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: #9ca3af;
            min-width: 72px;
            padding-top: 2px;
            flex-shrink: 0;
        }
    }

    /* ── Pagination ── */
    .leads-pagination-wrap {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: 0;
        padding: 0.85rem 1rem;
        border-top: 1px solid #eef2ff;
    }
    /* Tailwind nav structure */
    .leads-pagination-wrap nav {
        display: flex;
        justify-content: center;
        width: 100%;
    }
    .leads-pagination-wrap nav > div {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-wrap: wrap;
        gap: 0.3rem;
        width: 100%;
    }
    .leads-pagination-wrap nav span,
    .leads-pagination-wrap nav a {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        min-width: 36px !important;
        height: 36px !important;
        padding: 0 0.55rem !important;
        border-radius: 8px !important;
        border: 1px solid #e5e7eb !important;
        background: #fff !important;
        color: #374151 !important;
        font-size: 0.85rem !important;
        font-weight: 500 !important;
        text-decoration: none !important;
        transition: all .15s !important;
        line-height: 1 !important;
    }
    .leads-pagination-wrap nav a:hover {
        background: #f3f4f6 !important;
        border-color: #d1d5db !important;
        color: #111827 !important;
    }
    .leads-pagination-wrap nav span[aria-current="page"] > span {
        background: #2563eb !important;
        border-color: #2563eb !important;
        color: #fff !important;
        box-shadow: 0 2px 6px rgba(37,99,235,.3) !important;
        border-radius: 8px !important;
        min-width: 36px !important;
        height: 36px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        padding: 0 0.55rem !important;
    }
    .leads-pagination-wrap nav span[aria-disabled="true"] > span {
        background: #f9fafb !important;
        border-color: #e5e7eb !important;
        color: #c0c5ce !important;
        cursor: not-allowed !important;
    }
    .leads-pagination-wrap nav span:not([aria-current]):not([aria-label]):not([aria-disabled]) {
        border-color: transparent !important;
        background: transparent !important;
        cursor: default !important;
        color: #9ca3af !important;
    }
    /* Bootstrap 5 ul.pagination structure */
    .leads-pagination-wrap .pagination {
        display: flex !important;
        flex-wrap: wrap !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 0.3rem !important;
        margin: 0 !important;
        padding: 0 !important;
        list-style: none !important;
        width: 100% !important;
    }
    .leads-pagination-wrap .pagination .page-item { list-style: none !important; }
    .leads-pagination-wrap .pagination .page-link {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        min-width: 36px !important;
        height: 36px !important;
        padding: 0 0.55rem !important;
        border-radius: 8px !important;
        border: 1px solid #e5e7eb !important;
        background: #fff !important;
        color: #374151 !important;
        font-size: 0.85rem !important;
        font-weight: 500 !important;
        text-decoration: none !important;
        transition: all .15s !important;
        line-height: 1 !important;
        box-shadow: none !important;
    }
    .leads-pagination-wrap .pagination .page-link:hover {
        background: #f3f4f6 !important;
        border-color: #d1d5db !important;
        color: #111827 !important;
        z-index: auto !important;
    }
    .leads-pagination-wrap .pagination .page-item.active .page-link {
        background: #2563eb !important;
        border-color: #2563eb !important;
        color: #fff !important;
        box-shadow: 0 2px 6px rgba(37,99,235,.3) !important;
    }
    .leads-pagination-wrap .pagination .page-item.disabled .page-link {
        background: #f9fafb !important;
        border-color: #e5e7eb !important;
        color: #c0c5ce !important;
        cursor: not-allowed !important;
        pointer-events: none !important;
    }
    .leads-pagination-wrap svg {
        width: 14px !important;
        height: 14px !important;
        display: block !important;
        flex-shrink: 0 !important;
        pointer-events: none;
    }
    @media (max-width: 480px) {
        .leads-pagination-wrap nav span,
        .leads-pagination-wrap nav a,
        .leads-pagination-wrap .pagination .page-link {
            min-width: 30px !important;
            height: 30px !important;
            font-size: 0.78rem !important;
            border-radius: 6px !important;
        }
        .leads-pagination-wrap nav span[aria-current="page"] > span {
            min-width: 30px !important;
            height: 30px !important;
            border-radius: 6px !important;
        }
        .leads-pagination-wrap svg { width: 12px !important; height: 12px !important; }
    }

    /* ── Layout toggle ── */
    #leads-page .layout-toggle {
        display: flex;
        align-items: center;
        gap: 0.2rem;
        background: #f1f5f9;
        border-radius: 10px;
        padding: 3px;
    }
    #leads-page .layout-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.35rem;
        padding: 0.32rem 0.7rem;
        border-radius: 8px;
        border: none;
        background: transparent;
        color: #64748b;
        font-size: 0.78rem;
        font-weight: 600;
        cursor: pointer;
        transition: all .15s;
        font-family: 'DM Sans', sans-serif;
    }
    #leads-page .layout-btn.active {
        background: #fff;
        color: #2563eb;
        box-shadow: 0 1px 4px rgba(0,0,0,.1);
    }
    #leads-page .layout-btn:hover:not(.active) { color: #374151; }

    /* ── Kanban board ── */
    #leads-page .kanban-board {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 0.75rem;
        padding: 1rem;
        overflow-x: auto;
    }
    @media (max-width: 991px) {
        #leads-page .kanban-board { grid-template-columns: repeat(3, minmax(210px, 1fr)); }
    }
    @media (max-width: 575px) {
        #leads-page .kanban-board { grid-template-columns: repeat(2, minmax(180px, 1fr)); }
    }
    #leads-page .kanban-col {
        background: #f8fafc;
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        display: flex;
        flex-direction: column;
        min-width: 0;
    }
    #leads-page .kanban-col-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.7rem 0.9rem 0.6rem;
        border-bottom: 2px solid;
        border-radius: 14px 14px 0 0;
    }
    #leads-page .kanban-col-title {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .06em;
    }
    #leads-page .kanban-col-count {
        font-size: 0.7rem;
        font-weight: 700;
        border-radius: 20px;
        padding: 1px 7px;
        background: rgba(0,0,0,.07);
    }
    #leads-page .kanban-cards {
        padding: 0.6rem;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        flex: 1;
        min-height: 60px;
    }
    #leads-page .kanban-card {
        background: #fff;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        padding: 0.7rem 0.75rem;
        box-shadow: 0 1px 3px rgba(0,0,0,.05);
        cursor: pointer;
        transition: box-shadow .15s, transform .1s;
    }
    #leads-page .kanban-card:hover {
        box-shadow: 0 4px 14px rgba(0,0,0,.1);
        transform: translateY(-1px);
    }
    #leads-page .kanban-card-title {
        font-size: 0.81rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 0.3rem;
        line-height: 1.35;
    }
    #leads-page .kanban-card-meta {
        font-size: 0.71rem;
        color: #64748b;
        display: flex;
        flex-direction: column;
        gap: 0.18rem;
    }
    #leads-page .kanban-card-value {
        font-size: 0.76rem;
        font-weight: 700;
        color: #10b981;
        margin-top: 0.3rem;
    }
    #leads-page .kanban-card-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 0.45rem;
        padding-top: 0.4rem;
        border-top: 1px solid #f1f5f9;
        gap: 0.25rem;
    }
    #leads-page .kanban-empty {
        text-align: center;
        color: #94a3b8;
        font-size: 0.73rem;
        padding: 0.85rem 0.5rem;
    }

    /* ── Page header responsive ── */
    @media (max-width: 575px) {
        #leads-page .page-header { flex-direction: column; gap: 0.75rem; }
        #leads-page .page-header .btn-crm-primary { width: 100%; justify-content: center; }
        #leads-page .crm-card-body form .btn-crm-primary,
        #leads-page .crm-card-body form .btn-crm-secondary { flex: 1 1 auto; justify-content: center; }
    }
</style>
@endpush

@section('content')
<div id="leads-page">
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
        <span class="ms-auto badge-crm badge-warning me-3">{{ $leads->total() }} records</span>
        <div class="layout-toggle" role="group" aria-label="View layout">
            <button class="layout-btn active" id="btnTable" onclick="setLayout('table')">
                <i class="fas fa-table-list"></i> Table
            </button>
            <button class="layout-btn" id="btnKanban" onclick="setLayout('kanban')">
                <i class="fas fa-columns"></i> Kanban
            </button>
        </div>
    </div>
    @if($leads->count())
    <div id="tableView">
    <div class="crm-table-wrap">
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
                    <td data-label="Lead" style="font-weight:600;max-width:200px">{{ $lead->name }}</td>
                    <td data-label="Contact" style="font-size:13px">
                        @if($lead->customer)
                            <div>{{ $lead->customer->first_name }} {{ $lead->customer->last_name }}</div>
                            <div style="font-size:11px;color:var(--crm-muted)">{{ $lead->customer->email }}</div>
                        @else <span style="color:var(--crm-muted)">—</span> @endif
                    </td>
                    <td data-label="Company" style="font-size:13px">{{ $lead->email ?? '—' }}</td>
                    <td data-label="Source"><span class="badge-crm badge-secondary">{{ $lead->source ?? '—' }}</span></td>
                    <td data-label="Priority">
                        @php $pb=['high'=>'danger','medium'=>'warning','low'=>'success'][$lead->priority]??'secondary'; @endphp
                        <span class="badge-crm badge-{{ $pb }}">{{ ucfirst($lead->priority) }}</span>
                    </td>
                    <td data-label="Status">
                        @php $sb=['new'=>'warning','contacted'=>'info','negotiating'=>'purple','closed'=>'success','lost'=>'danger'][$lead->status]??'secondary'; @endphp
                        <span class="badge-crm badge-{{ $sb }}">{{ ucfirst($lead->status) }}</span>
                    </td>
                    <td data-label="Value" style="font-weight:600;color:var(--crm-success)">
                        {{ $lead->expected_value ? '₱'.number_format($lead->expected_value) : '—' }}
                    </td>
                    <td data-label="Follow Up" style="font-size:12px">
                        @php $priority_color = ['high'=>'danger', 'medium'=>'warning', 'low'=>'info'][$lead->priority]??'secondary'; @endphp
                        <span class="badge-crm badge-{{ $priority_color }}">{{ ucfirst($lead->priority) }}</span>
                    </td>
                    <td data-label="Assigned" style="font-size:12px">{{ $lead->assignedUser?->name ?? '—' }}</td>
                    <td data-label="Actions" style="width: 80px; padding: 8px 4px !important;">
                            <div class="dropdown dropdown-actions">
                                <button class="btn btn-sm btn-light dropdown-toggle p-1 shadow-sm" 
                                        type="button" 
                                        data-bs-toggle="dropdown" 
                                        data-bs-auto-close="outside"
                                        aria-expanded="false"
                                        title="Actions"
                                        style="min-width: 32px; height: 32px; line-height: 1;">
                                    <i class="fas fa-ellipsis-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-lg">
                                    <li><a class="dropdown-item" href="#" onclick="viewLead({{ $lead->id }});return false;"><i class="fas fa-eye me-2"></i>View</a></li>
                                    @if(auth()->user()->canManageCustomersAndLeads() || (auth()->user()->isSalesStaff() && $lead->assigned_user_id === auth()->id()))
                                    <li><a class="dropdown-item" href="#" onclick="editLead({{ $lead->id }});return false;"><i class="fas fa-pen me-2"></i>Edit</a></li>
                                    @endif
                                    @if(auth()->user()->canDeleteLeads())
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger fw-medium" href="#" onclick="deleteLead({{ $lead->id }}, '{{ addslashes($lead->name) }}');return false;"><i class="fas fa-trash me-2"></i>Delete</a></li>
                                    @endif
                                </ul>
                            </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="leads-pagination-wrap">
        {{ $leads->onEachSide(1)->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>
    <div class="text-center text-muted small py-2" style="font-size:0.78rem">
        Showing {{ $leads->firstItem() }}–{{ $leads->lastItem() }} of {{ $leads->total() }} results
    </div>
    </div>{{-- /#tableView --}}

    {{-- KANBAN VIEW --}}
    <div id="kanbanView" style="display:none;">
        @php
            $kanbanCols = [
                'new'         => ['label'=>'New',         'color'=>'#f59e0b', 'bg'=>'#fffbeb'],
                'contacted'   => ['label'=>'Contacted',   'color'=>'#06b6d4', 'bg'=>'#ecfeff'],
                'negotiating' => ['label'=>'Negotiating', 'color'=>'#8b5cf6', 'bg'=>'#f5f3ff'],
                'closed'      => ['label'=>'Closed',      'color'=>'#10b981', 'bg'=>'#ecfdf5'],
                'lost'        => ['label'=>'Lost',        'color'=>'#ef4444', 'bg'=>'#fef2f2'],
            ];
            $leadsByStatus = $leads->groupBy('status');
        @endphp
        <div class="kanban-board">
            @foreach($kanbanCols as $statusKey => $col)
            @php $colLeads = $leadsByStatus->get($statusKey, collect()); @endphp
            <div class="kanban-col">
                <div class="kanban-col-header" style="border-color:{{ $col['color'] }};background:{{ $col['bg'] }}">
                    <span class="kanban-col-title" style="color:{{ $col['color'] }}">{{ $col['label'] }}</span>
                    <span class="kanban-col-count" style="color:{{ $col['color'] }}">{{ $colLeads->count() }}</span>
                </div>
                <div class="kanban-cards">
                    @forelse($colLeads as $lead)
                    <div class="kanban-card" onclick="viewLead({{ $lead->id }})">
                        <div class="kanban-card-title">{{ $lead->name }}</div>
                        <div class="kanban-card-meta">
                            @if($lead->customer)
                                <span><i class="fas fa-user" style="width:11px;opacity:.55"></i> {{ $lead->customer->first_name }} {{ $lead->customer->last_name }}</span>
                            @endif
                            @if($lead->email)
                                <span><i class="fas fa-envelope" style="width:11px;opacity:.55"></i> {{ $lead->email }}</span>
                            @endif
                        </div>
                        @if($lead->expected_value)
                            <div class="kanban-card-value"><i class="fas fa-peso-sign" style="font-size:.7rem"></i> {{ number_format($lead->expected_value) }}</div>
                        @endif
                        <div class="kanban-card-footer">
                            @php $pb=['high'=>'danger','medium'=>'warning','low'=>'success'][$lead->priority]??'secondary'; @endphp
                            <span class="badge-crm badge-{{ $pb }}" style="font-size:.65rem;padding:2px 7px">{{ ucfirst($lead->priority) }}</span>
                            <span style="font-size:.7rem;color:#94a3b8;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:90px">
                                <i class="fas fa-user-circle"></i>
                                {{ $lead->assignedUser?->name ?? 'Unassigned' }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="kanban-empty">
                        <i class="fas fa-inbox" style="display:block;font-size:1.1rem;margin-bottom:4px;opacity:.3"></i>No leads
                    </div>
                    @endforelse
                </div>
            </div>
            @endforeach
        </div>
    </div>{{-- /#kanbanView --}}
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
                            <label class="crm-label">Lead Name *</label>
                            <input type="text" name="name" class="crm-input" placeholder="e.g. TechVision Annual License Renewal">
                        </div>
                        <div class="col-md-6">
                            <label class="crm-label">Email</label>
                            <input type="email" name="email" class="crm-input" placeholder="lead@example.com">
                        </div>
                        <div class="col-md-6">
                            <label class="crm-label">Phone</label>
                            <input type="tel" name="phone" class="crm-input" placeholder="+1-555-0000">
                        </div>
                        <div class="col-md-6">
                            <label class="crm-label">Customer (Optional)</label>
                            <select name="customer_id" class="crm-input">
                                <option value="">— Select Customer —</option>
                                @foreach($users as $u)
                                    <option value="{{ $u->id }}">{{ $u->name }}</option>
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
                            <label class="crm-label">Estimated Value (₱)</label>
                            <input type="number" name="expected_value" class="crm-input" placeholder="5000">
                        </div>
                        <div class="col-md-4">
                            <label class="crm-label">Assigned To</label>
                            <select name="assigned_user_id" class="crm-input">
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
                        <div class="col-12">
                            <label class="crm-label">Lead Name *</label>
                            <input type="text" name="name" id="edit_name" class="crm-input" placeholder="e.g. TechVision Annual License Renewal">
                        </div>
                        <div class="col-md-6">
                            <label class="crm-label">Email</label>
                            <input type="email" name="email" id="edit_email" class="crm-input" placeholder="lead@example.com">
                        </div>
                        <div class="col-md-6">
                            <label class="crm-label">Phone</label>
                            <input type="tel" name="phone" id="edit_phone" class="crm-input" placeholder="+1-555-0000">
                        </div>
                        <div class="col-md-6">
                            <label class="crm-label">Customer (Optional)</label>
                            <select name="customer_id" id="edit_customer_id" class="crm-input">
                                <option value="">— Select Customer —</option>
                                @foreach($users as $u)
                                    <option value="{{ $u->id }}">{{ $u->name }}</option>
                                @endforeach
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
                        <div class="col-md-4"><label class="crm-label">Estimated Value (₱)</label><input type="number" name="expected_value" id="edit_expected_value" class="crm-input" placeholder="5000"></div>
                        <div class="col-md-4">
                            <label class="crm-label">Assigned To</label>
                            <select name="assigned_user_id" id="edit_assigned_user_id" class="crm-input">
                                <option value="">— Select User —</option>
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

<!-- View Modal -->
<div class="modal fade crm-modal" id="viewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-eye me-2" style="color:var(--crm-warning)"></i>Lead details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="viewLeadBody" class="row g-3"></div>
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
            <div class="modal-body"><p style="font-size:14px;color:var(--crm-muted)">Delete lead <strong id="delete_name" style="color:var(--crm-text)"></strong>?</p></div>
            <div class="modal-footer">
                <button class="btn-crm-secondary" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-danger" onclick="confirmDelete()"><i class="fas fa-trash"></i> Delete</button>
            </div>
        </div>
    </div>
</div>
</div>{{-- #leads-page --}}
@endsection

@push('scripts')
<script>
// Global variables
const BASE = '{{ route("admin.leads.index") }}';
let deleteId = null;

// Show toast notification
function showToast(message, type = 'success') {
    const toastHTML = `
        <div class="toast align-items-center text-white bg-${type === 'success' ? 'success' : 'danger'} border-0" role="alert" aria-live="assertive" aria-atomic="true" style="position: fixed; bottom: 20px; right: 20px; z-index: 9999;">
            <div class="d-flex">
                <div class="toast-body">${message}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    `;

    const toastContainer = document.createElement('div');
    toastContainer.innerHTML = toastHTML;
    document.body.appendChild(toastContainer.firstElementChild);

    const toastEl = document.querySelector('.toast');
    const toast = new bootstrap.Toast(toastEl, { delay: 4000 });
    toast.show();

    toastEl.addEventListener('hidden.bs.toast', () => toastEl.remove());
}

// Clear validation errors
function clearFormErrors(formId) {
    const form = document.getElementById(formId);
    if (!form) return;
    form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    form.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
}

// Show validation errors
function showFormErrors(errors, formId) {
    const form = document.getElementById(formId);
    if (!form) return;

    Object.keys(errors).forEach(field => {
        const input = form.querySelector(`[name="${field}"]`);
        if (input) {
            input.classList.add('is-invalid');
            const div = document.createElement('div');
            div.className = 'invalid-feedback';
            div.textContent = errors[field][0];
            input.parentNode.appendChild(div);
        }
    });
}

function openModal(id) {
    new bootstrap.Modal(document.getElementById(id)).show();
}

// ==================== ADD LEAD ====================
async function submitAdd() {
    clearFormErrors('addForm');

    const form = document.getElementById('addForm');
    const data = Object.fromEntries(new FormData(form));

    try {
        const res = await fetch(BASE, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(data)
        });

        const json = await res.json();

        if (!res.ok) {
            if (json.errors) showFormErrors(json.errors, 'addForm');
            else showToast(json.message || 'Failed to create lead', 'danger');
            return;
        }

        bootstrap.Modal.getInstance(document.getElementById('addModal')).hide();
        showToast(json.message || 'Lead created successfully!', 'success');
        setTimeout(() => location.reload(), 1000);
    } catch (e) {
        console.error(e);
        showToast('Network error. Please try again.', 'danger');
    }
}

// ==================== EDIT LEAD ====================
async function editLead(id) {
    try {
        const res = await fetch(`${BASE}/${id}/edit`);
        if (!res.ok) throw new Error('Failed to load lead');

        const lead = await res.json();

        // Fill the edit form
        document.getElementById('edit_id').value = lead.id || '';
        document.getElementById('edit_name').value = lead.name || '';
        document.getElementById('edit_email').value = lead.email || '';
        document.getElementById('edit_phone').value = lead.phone || '';
        document.getElementById('edit_source').value = lead.source || '';
        document.getElementById('edit_status').value = lead.status || 'new';
        document.getElementById('edit_priority').value = lead.priority || 'medium';
        document.getElementById('edit_expected_value').value = lead.expected_value || '';
        document.getElementById('edit_notes').value = lead.notes || '';

        // Select dropdowns
        const customerSelect = document.getElementById('edit_customer_id');
        if (customerSelect) customerSelect.value = lead.customer_id || '';

        const assignedSelect = document.getElementById('edit_assigned_user_id');
        if (assignedSelect) assignedSelect.value = lead.assigned_user_id || '';

        openModal('editModal');
    } catch (e) {
        console.error(e);
        showToast('Failed to load lead data for editing.', 'danger');
    }
}

async function submitEdit() {
    clearFormErrors('editForm');

    const id = document.getElementById('edit_id').value.trim();
    if (!id) {
        showToast('Lead ID is missing!', 'danger');
        return;
    }

    const form = document.getElementById('editForm');
    const formData = new FormData(form);
    formData.append('_method', 'PATCH');   // or 'PUT'

    const requestUrl = `${BASE}/${id}`;

    try {
        const res = await fetch(requestUrl, {
            method: 'POST',   // Important: still POST when using _method
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                // Do NOT set Content-Type when using FormData
            },
            body: formData
        });

        let json = {};
        try {
            json = await res.json();
        } catch (e) {}

        if (!res.ok) {
            if (json.errors) {
                showFormErrors(json.errors, 'editForm');
                showToast('Please fix the validation errors.', 'danger');
            } else {
                showToast(json.message || `Error (${res.status})`, 'danger');
            }
            return;
        }

        bootstrap.Modal.getInstance(document.getElementById('editModal')).hide();
        showToast('Lead updated successfully!', 'success');
        setTimeout(() => location.reload(), 800);

    } catch (e) {
        console.error(e);
        showToast('Network error. Please check console.', 'danger');
    }
}

// ==================== VIEW LEAD ====================
async function viewLead(id) {
    try {
        const res = await fetch(`${BASE}/${id}`);
        if (!res.ok) throw new Error('Failed to load lead');

        const l = await res.json();

        const container = document.getElementById('viewLeadBody');
        container.innerHTML = `
            <div class="col-md-6">
                <div class="crm-card" style="padding:18px;">
                    <h6 style="font-weight:700;margin-bottom:.75rem">Lead Information</h6>
                    <div style="font-size:14px;color:#334155"><strong>Name:</strong> ${l.name || '—'}</div>
                    <div style="font-size:14px;color:#475569;margin-top:.5rem"><strong>Email:</strong> ${l.email || '—'}</div>
                    <div style="font-size:14px;color:#475569;margin-top:.5rem"><strong>Phone:</strong> ${l.phone || '—'}</div>
                    <div style="font-size:14px;color:#475569;margin-top:.5rem"><strong>Status:</strong> ${l.status ? l.status.charAt(0).toUpperCase() + l.status.slice(1) : '—'}</div>
                    <div style="font-size:14px;color:#475569;margin-top:.5rem"><strong>Priority:</strong> ${l.priority ? l.priority.charAt(0).toUpperCase() + l.priority.slice(1) : '—'}</div>
                    <div style="font-size:14px;color:#475569;margin-top:.5rem"><strong>Expected Value:</strong> ${l.expected_value ? '₱' + Number(l.expected_value).toLocaleString() : '—'}</div>
                    <div style="font-size:14px;color:#475569;margin-top:.5rem"><strong>Source:</strong> ${l.source || '—'}</div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="crm-card" style="padding:18px;">
                    <h6 style="font-weight:700;margin-bottom:.75rem">Related Info</h6>
                    <div style="font-size:14px;color:#334155"><strong>Customer:</strong> ${l.customer ? `${l.customer.first_name} ${l.customer.last_name}` : '—'}</div>
                    <div style="font-size:14px;color:#334155;margin-top:.5rem"><strong>Assigned To:</strong> ${l.assigned_user ? l.assigned_user.name : '—'}</div>
                    <div style="font-size:14px;color:#475569;margin-top:.5rem"><strong>Created At:</strong> ${l.created_at ? l.created_at.substring(0,10) : '—'}</div>
                </div>
            </div>
            <div class="col-12">
                <div class="crm-card" style="padding:18px;">
                    <h6 style="font-weight:700;margin-bottom:.75rem">Notes</h6>
                    <div style="font-size:14px;color:#475569;white-space:pre-wrap">${l.notes || 'No notes added.'}</div>
                </div>
            </div>
        `;

        openModal('viewModal');
    } catch (e) {
        console.error(e);
        showToast('Failed to load lead details.', 'danger');
    }
}

// ==================== DELETE LEAD ====================
function deleteLead(id, name) {
    deleteId = id;
    document.getElementById('delete_name').textContent = name;
    openModal('deleteModal');
}

async function confirmDelete() {
    try {
        const res = await fetch(`${BASE}/${deleteId}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
        });

        const json = await res.json();

        bootstrap.Modal.getInstance(document.getElementById('deleteModal')).hide();

        if (res.ok) {
            showToast(json.message || 'Lead deleted successfully!', 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast(json.message || 'Failed to delete lead', 'danger');
        }
    } catch (e) {
        console.error(e);
        showToast('Network error while deleting.', 'danger');
    }
}
// ==================== LAYOUT TOGGLE ====================
function setLayout(mode) {
    const tableView  = document.getElementById('tableView');
    const kanbanView = document.getElementById('kanbanView');
    const btnTable   = document.getElementById('btnTable');
    const btnKanban  = document.getElementById('btnKanban');

    if (!tableView || !kanbanView) return;

    if (mode === 'kanban') {
        tableView.style.display  = 'none';
        kanbanView.style.display = 'block';
        btnKanban.classList.add('active');
        btnTable.classList.remove('active');
    } else {
        tableView.style.display  = 'block';
        kanbanView.style.display = 'none';
        btnTable.classList.add('active');
        btnKanban.classList.remove('active');
    }
    try { localStorage.setItem('leadsLayout', mode); } catch(e) {}
}

document.addEventListener('DOMContentLoaded', function () {
    try {
        const saved = localStorage.getItem('leadsLayout');
        if (saved === 'kanban') setLayout('kanban');
    } catch(e) {}
});
</script>
@endpush