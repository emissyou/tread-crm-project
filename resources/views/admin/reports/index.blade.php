@extends('layouts.app')

@section('title', 'Reports - Sales Analytics')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    /* ── Global font ───────────────────────────────────────── */
    *, body, .crm-card, .crm-card-header, .crm-card-body,
    .stat-card, .stat-value, .stat-label,
    h1,h2,h3,h4,h5,h6,p,span,a,button,.btn,
    table, th, td, small, input, select {
        font-family: 'Plus Jakarta Sans', sans-serif !important;
    }

    /* ── Page header ───────────────────────────────────────── */
    .page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
    }
    .page-header-left { flex: 1 1 0; min-width: 0; }
    .page-title {
        font-size: 1.4rem;
        font-weight: 800;
        margin: 0 0 0.1rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .page-subtitle {
        font-size: 0.8rem;
        color: #64748b;
        margin: 0;
    }
    .page-actions {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    /* ── Export buttons ────────────────────────────────────── */
    .btn-export {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.4rem 0.9rem;
        border-radius: 8px;
        font-size: 0.78rem;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: opacity .15s, transform .1s;
        white-space: nowrap;
        text-decoration: none;
    }
    .btn-export:hover { opacity: .85; transform: translateY(-1px); }
    .btn-export:active { transform: translateY(0); }
    .btn-export-csv  { background: #10b981; color: #fff; }
    .btn-export-pdf  { background: #ef4444; color: #fff; }

    /* ── Period select ─────────────────────────────────────── */
    .crm-input {
        font-size: 0.8rem;
        padding: 0.4rem 0.7rem;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        background: #fff;
        color: #1f2937;
        max-width: 145px;
    }

    /* ── Stat cards ────────────────────────────────────────── */
    .stat-card {
        background: #fff;
        border-radius: 14px;
        padding: 1rem;
        box-shadow: 0 1px 6px rgba(0,0,0,.06);
        height: 100%;
    }
    .stat-value {
        font-size: 1.3rem;
        font-weight: 800;
        color: #1f2937;
        line-height: 1.2;
        word-break: keep-all;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .stat-label {
        font-size: 0.72rem;
        color: #64748b;
        margin-top: 2px;
    }
    #report-page .stat-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
    }

    /* ── CRM cards ─────────────────────────────────────────── */
    .crm-card {
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 1px 6px rgba(0,0,0,.06);
        overflow: hidden;
        height: 100%;
    }
    .crm-card-header {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.85rem 1rem;
        border-bottom: 1px solid #f1f5f9;
        flex-wrap: wrap;
    }
    .crm-card-header h5,
    .crm-card-header h6 { margin: 0; font-size: 0.85rem; font-weight: 700; }
    .crm-card-body { padding: 1rem; }

    /* ── Tables ────────────────────────────────────────────── */
    .crm-table { width: 100%; border-collapse: collapse; font-size: 0.8rem; }
    .crm-table th {
        font-weight: 600;
        font-size: 0.75rem;
        color: #64748b;
        background: #f8fafc;
        padding: 0.6rem 0.75rem;
        border-bottom: 1px solid #eef2ff;
        white-space: nowrap;
    }
    .crm-table td { padding: 0.6rem 0.75rem; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
    .crm-table tbody tr:last-child td { border-bottom: none; }
    .crm-table tbody tr:hover { background: #fafbff; }
    .table-scroll { overflow-x: auto; -webkit-overflow-scrolling: touch; }

    /* ── Badges ────────────────────────────────────────────── */
    .badge-crm {
        display: inline-block;
        padding: 0.2rem 0.55rem;
        border-radius: 6px;
        font-size: 0.68rem;
        font-weight: 600;
    }
    .badge-primary   { background: rgba(59,130,246,.12); color: #2563eb; }
    .badge-success   { background: rgba(16,185,129,.12); color: #059669; }
    .badge-warning   { background: rgba(245,158,11,.12); color: #d97706; }
    .badge-danger    { background: rgba(239,68,68,.12);  color: #dc2626; }
    .badge-info      { background: rgba(6,182,212,.12);  color: #0891b2; }
    .badge-secondary { background: rgba(100,116,139,.12);color: #475569; }

    /* ── Avatar circle ─────────────────────────────────────── */
    .avatar-circle {
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        flex-shrink: 0;
    }

    /* ── Chart wrappers ────────────────────────────────────── */
    .chart-wrap { position: relative; }
    .chart-wrap canvas { max-width: 100%; }

    /* ── Responsive tweaks ─────────────────────────────────── */
    @media (max-width: 575.98px) {
        .page-title   { font-size: 1.1rem; }
        .stat-value   { font-size: 1.1rem; }
        .crm-card-body{ padding: 0.75rem; }

        /* Stack export buttons neatly */
        .page-actions { width: 100%; }
        .page-actions .btn-export,
        .page-actions .crm-input { flex: 1 1 auto; justify-content: center; }
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-3 py-md-4" id="report-page">

    {{-- ─── Page Header ─────────────────────────────────────────────────── --}}
    <div class="page-header">
        <div class="page-header-left">
            <h1 class="page-title">
                <i class="fas fa-chart-bar me-2 text-primary"></i>Reports
            </h1>
            <p class="page-subtitle">Sales analytics &amp; performance overview</p>
        </div>
        <div class="page-actions">
            <select class="crm-input" onchange="window.location.href='?period='+this.value">
                <option value="7"   {{ $period == 7   ? 'selected' : '' }}>Last 7 days</option>
                <option value="30"  {{ $period == 30  ? 'selected' : '' }}>Last 30 days</option>
                <option value="90"  {{ $period == 90  ? 'selected' : '' }}>Last 90 days</option>
                <option value="365" {{ $period == 365 ? 'selected' : '' }}>Last year</option>
            </select>
            <button class="btn-export btn-export-csv" onclick="exportCSV()">
                <i class="fas fa-file-csv"></i> Export CSV
            </button>
            <button class="btn-export btn-export-pdf" onclick="exportPDF()">
                <i class="fas fa-file-pdf"></i> Export PDF
            </button>
        </div>
    </div>

    {{-- ─── Summary Stats ───────────────────────────────────────────────── --}}
    <div class="row g-2 g-md-3 mb-4" id="report-summary">
        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:0.5rem;">
                    <div style="min-width:0;">
                        <div class="stat-value">{{ $summary['total_customers'] }}</div>
                        <div class="stat-label">Total Customers</div>
                    </div>
                    <div class="stat-icon" style="background:rgba(59,130,246,.12);color:#3b82f6;">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
                @if($summary['new_customers'] > 0)
                    <div style="font-size:0.72rem;color:#10b981;margin-top:6px;font-weight:600;">
                        +{{ $summary['new_customers'] }} new
                    </div>
                @endif
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:0.5rem;">
                    <div style="min-width:0;">
                        <div class="stat-value">{{ $summary['total_leads'] }}</div>
                        <div class="stat-label">Total Leads</div>
                    </div>
                    <div class="stat-icon" style="background:rgba(168,85,247,.12);color:#a855f7;">
                        <i class="fas fa-bullseye"></i>
                    </div>
                </div>
                @if($summary['new_leads'] > 0)
                    <div style="font-size:0.72rem;color:#10b981;margin-top:6px;font-weight:600;">
                        +{{ $summary['new_leads'] }} new
                    </div>
                @endif
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:0.5rem;">
                    <div style="min-width:0;">
                        @php
                            $rv = $summary['total_revenue'];
                            $rvFmt = $rv >= 1000000 ? '₱'.number_format($rv/1000000,1).'M'
                                   : ($rv >= 1000    ? '₱'.number_format($rv/1000,1).'K'
                                                     : '₱'.number_format($rv,0));
                        @endphp
                        <div class="stat-value">{{ $rvFmt }}</div>
                        <div class="stat-label">Pipeline Value</div>
                    </div>
                    <div class="stat-icon" style="background:rgba(34,197,94,.12);color:#22c55e;">
                        <i class="fas fa-chart-line"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:0.5rem;">
                    <div style="min-width:0;">
                        <div class="stat-value">{{ $summary['conversion_rate'] }}%</div>
                        <div class="stat-label">Conversion Rate</div>
                    </div>
                    <div class="stat-icon" style="background:rgba(249,115,22,.12);color:#f97316;">
                        <i class="fas fa-percentage"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ─── Charts Row 1 ────────────────────────────────────────────────── --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-md-8">
            <div class="crm-card">
                <div class="crm-card-header">
                    <i class="fas fa-chart-line text-primary"></i>
                    <h6>Monthly Revenue</h6>
                    <span class="text-muted small ms-auto">Last 6 months</span>
                </div>
                <div class="crm-card-body">
                    <div class="chart-wrap"><canvas id="revenueChart" height="110"></canvas></div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="crm-card">
                <div class="crm-card-header">
                    <i class="fas fa-chart-pie text-warning"></i>
                    <h6>Leads by Status</h6>
                </div>
                <div class="crm-card-body">
                    <div class="chart-wrap"><canvas id="leadsChart" height="150"></canvas></div>
                    <div class="mt-3">
                        @foreach(['new'=>'#f59e0b','contacted'=>'#06b6d4','negotiating'=>'#8b5cf6','closed'=>'#10b981','lost'=>'#ef4444'] as $status => $color)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="d-flex align-items-center gap-2">
                                <span style="width:9px;height:9px;border-radius:50%;background:{{ $color }};display:inline-block;"></span>
                                <span style="font-size:0.75rem;color:#64748b;">{{ ucfirst($status) }}</span>
                            </div>
                            <span style="font-size:0.75rem;font-weight:700;">{{ $leadsByStatus[$status]->count ?? 0 }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ─── Charts Row 2 ────────────────────────────────────────────────── --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-md-6">
            <div class="crm-card">
                <div class="crm-card-header">
                    <i class="fas fa-bar-chart text-success"></i>
                    <h6>Deals by Stage</h6>
                </div>
                <div class="crm-card-body">
                    <div class="chart-wrap"><canvas id="dealsChart" height="130"></canvas></div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="crm-card">
                <div class="crm-card-header">
                    <i class="fas fa-users text-info"></i>
                    <h6>New Customers per Month</h6>
                </div>
                <div class="crm-card-body">
                    <div class="chart-wrap"><canvas id="contactsChart" height="130"></canvas></div>
                </div>
            </div>
        </div>
    </div>

    {{-- ─── Lead Distribution ───────────────────────────────────────────── --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-md-4">
            <div class="crm-card">
                <div class="crm-card-header"><h6>Leads by Status</h6></div>
                <div class="crm-card-body">
                    @forelse($leadsByStatus as $status => $item)
                        <div style="margin-bottom:10px;">
                            <div style="display:flex;justify-content:space-between;margin-bottom:4px;">
                                <span style="font-size:0.8rem;color:#475569;">{{ ucfirst($status) }}</span>
                                <span style="font-weight:700;font-size:0.8rem;color:#1f2937;">{{ $item->count }}</span>
                            </div>
                            <div style="width:100%;height:5px;background:#eef2ff;border-radius:3px;overflow:hidden;">
                                <div style="width:{{ $summary['total_leads'] > 0 ? ($item->count/$summary['total_leads']*100) : 0 }}%;height:100%;background:#5b8def;border-radius:3px;"></div>
                            </div>
                        </div>
                    @empty
                        <p style="color:#94a3b8;text-align:center;padding:16px 0;font-size:0.8rem;">No leads data</p>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="crm-card">
                <div class="crm-card-header"><h6>Leads by Source</h6></div>
                <div class="crm-card-body">
                    @forelse($leadsBySource as $source => $item)
                        <div style="margin-bottom:10px;">
                            <div style="display:flex;justify-content:space-between;margin-bottom:4px;">
                                <span style="font-size:0.8rem;color:#475569;">{{ ucfirst($source) }}</span>
                                <span style="font-weight:700;font-size:0.8rem;color:#1f2937;">{{ $item->count }}</span>
                            </div>
                            <div style="width:100%;height:5px;background:#fef3c7;border-radius:3px;overflow:hidden;">
                                <div style="width:{{ $summary['total_leads'] > 0 ? ($item->count/$summary['total_leads']*100) : 0 }}%;height:100%;background:#f97316;border-radius:3px;"></div>
                            </div>
                        </div>
                    @empty
                        <p style="color:#94a3b8;text-align:center;padding:16px 0;font-size:0.8rem;">No source data</p>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="crm-card">
                <div class="crm-card-header"><h6>Leads by Priority</h6></div>
                <div class="crm-card-body">
                    @forelse($leadsByPriority as $priority => $item)
                        <div style="margin-bottom:10px;">
                            <div style="display:flex;justify-content:space-between;margin-bottom:4px;">
                                <span style="font-size:0.8rem;color:#475569;">{{ ucfirst($priority) }}</span>
                                <span style="font-weight:700;font-size:0.8rem;color:#1f2937;">{{ $item->count }}</span>
                            </div>
                            <div style="width:100%;height:5px;background:#dcfce7;border-radius:3px;overflow:hidden;">
                                <div style="width:{{ $summary['total_leads'] > 0 ? ($item->count/$summary['total_leads']*100) : 0 }}%;height:100%;background:#10b981;border-radius:3px;"></div>
                            </div>
                        </div>
                    @empty
                        <p style="color:#94a3b8;text-align:center;padding:16px 0;font-size:0.8rem;">No priority data</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- ─── Activity Summary + Top Customers ───────────────────────────── --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-md-6">
            <div class="crm-card">
                <div class="crm-card-header"><h6>Top Customers</h6></div>
                <div class="crm-card-body" style="padding:0;">
                    @forelse($topCustomers as $customer)
                        <div style="padding:0.65rem 1rem;border-bottom:1px solid #f1f5f9;display:flex;justify-content:space-between;align-items:center;gap:0.5rem;">
                            <div style="min-width:0;">
                                <div style="font-weight:600;font-size:0.82rem;color:#1f2937;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                    {{ $customer->first_name }} {{ $customer->last_name }}
                                </div>
                                <div style="font-size:0.72rem;color:#64748b;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                    {{ $customer->email }}
                                </div>
                            </div>
                            <span class="badge-crm badge-info" style="flex-shrink:0;">{{ $customer->leads_count }} leads</span>
                        </div>
                    @empty
                        <p style="color:#94a3b8;text-align:center;padding:20px;font-size:0.8rem;">No customer data</p>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="crm-card">
                <div class="crm-card-header"><h6>Activity Summary</h6></div>
                <div class="crm-card-body">
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                        <div style="padding:1rem;background:#f0f9ff;border-radius:10px;">
                            <div style="font-size:1.3rem;font-weight:800;color:#0369a1;">{{ $summary['total_activities'] }}</div>
                            <div style="font-size:0.75rem;color:#0c4a6e;margin-top:4px;">Total Activities</div>
                        </div>
                        <div style="padding:1rem;background:#fef3c7;border-radius:10px;">
                            <div style="font-size:1.3rem;font-weight:800;color:#b45309;">{{ $summary['total_followups'] }}</div>
                            <div style="font-size:0.75rem;color:#92400e;margin-top:4px;">Total Follow-ups</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ─── Recent Data Tables ──────────────────────────────────────────── --}}
    <div class="row g-3 mb-4" id="export-tables">
        {{-- Recent Leads --}}
        <div class="col-12 col-lg-6">
            <div class="crm-card">
                <div class="crm-card-header"><h6>Recent Leads</h6></div>
                <div class="table-scroll">
                    <table class="crm-table">
                        <thead>
                            <tr>
                                <th>Lead Name</th>
                                <th>Status</th>
                                <th>Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentLeads as $lead)
                                <tr>
                                    <td>
                                        <div style="font-weight:600;color:#1f2937;font-size:0.8rem;">{{ $lead->name }}</div>
                                        <div style="font-size:0.7rem;color:#64748b;">{{ $lead->email ?? $lead->phone }}</div>
                                    </td>
                                    <td>
                                        <span class="badge-crm badge-{{ $loop->index % 2 == 0 ? 'primary' : 'success' }}">
                                            {{ ucfirst($lead->status) }}
                                        </span>
                                    </td>
                                    <td style="font-weight:700;color:#1f2937;font-size:0.8rem;">
                                        ₱{{ $lead->expected_value ? number_format($lead->expected_value, 0) : '—' }}
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="3" style="text-align:center;padding:20px;color:#94a3b8;">No leads yet</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Recent Activities --}}
        <div class="col-12 col-lg-6">
            <div class="crm-card">
                <div class="crm-card-header"><h6>Recent Activities</h6></div>
                <div class="table-scroll">
                    <table class="crm-table">
                        <thead>
                            <tr>
                                <th>Description</th>
                                <th>Type</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentActivities as $activity)
                                <tr>
                                    <td style="font-weight:500;color:#1f2937;font-size:0.8rem;">
                                        {{ Str::limit($activity->description, 30) }}
                                    </td>
                                    <td style="font-size:0.78rem;">{{ ucfirst($activity->activity_type) }}</td>
                                    <td style="font-size:0.75rem;color:#64748b;">
                                        {{ $activity->activity_date->format('M d') }}
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="3" style="text-align:center;padding:20px;color:#94a3b8;">No activities yet</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Recent Follow-ups --}}
        <div class="col-12">
            <div class="crm-card">
                <div class="crm-card-header"><h6>Recent Follow-ups</h6></div>
                <div class="table-scroll">
                    <table class="crm-table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Status</th>
                                <th>Due Date</th>
                                <th>Assignee</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentFollowUps as $followUp)
                                <tr>
                                    <td>
                                        <div style="font-weight:600;color:#1f2937;font-size:0.8rem;">{{ Str::limit($followUp->title, 40) }}</div>
                                        <div style="font-size:0.7rem;color:#64748b;">{{ $followUp->description ? Str::limit($followUp->description, 50) : '—' }}</div>
                                    </td>
                                    <td>
                                        <span class="badge-crm badge-{{ $followUp->status == 'completed' ? 'success' : 'warning' }}">
                                            {{ ucfirst($followUp->status) }}
                                        </span>
                                    </td>
                                    <td style="font-size:0.75rem;color:#64748b;">
                                        {{ $followUp->due_date->format('M d, Y') }}
                                    </td>
                                    <td style="font-size:0.78rem;">{{ $followUp->user?->name ?? '—' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" style="text-align:center;padding:20px;color:#94a3b8;">No follow-ups yet</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Top Customers by Deals --}}
        <div class="col-12 col-md-5">
            <div class="crm-card">
                <div class="crm-card-header">
                    <i class="fas fa-star" style="color:#f59e0b;"></i>
                    <h6>Top Customers by Deals</h6>
                </div>
                <div class="table-scroll">
                    <table class="crm-table">
                        <thead><tr><th>#</th><th>Customer</th><th>Deals</th><th>Revenue</th></tr></thead>
                        <tbody>
                            @forelse($topCustomers as $i => $c)
                            <tr>
                                <td style="font-weight:700;color:#94a3b8;font-size:0.75rem;">{{ $i+1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar-circle" style="background:{{ ['#3b82f6','#10b981','#f59e0b','#8b5cf6','#06b6d4'][$i%5] }};color:#fff;width:28px;height:28px;font-size:10px;">
                                            {{ $c->initials }}
                                        </div>
                                        <div>
                                            <div style="font-size:0.78rem;font-weight:600;">{{ $c->full_name }}</div>
                                            <div style="font-size:0.68rem;color:#64748b;">{{ $c->company }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="badge-crm badge-primary">{{ $c->deals_count }}</span></td>
                                <td style="font-weight:700;color:#10b981;font-size:0.78rem;">
                                    @php
                                        $tv = $c->total_value ?? 0;
                                        echo $tv >= 1000000 ? '₱'.number_format($tv/1000000,1).'M'
                                           : ($tv >= 1000    ? '₱'.number_format($tv/1000,1).'K'
                                                             : '₱'.number_format($tv,0));
                                    @endphp
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" style="text-align:center;color:#94a3b8;padding:20px;">No data</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Recent Deals --}}
        <div class="col-12 col-md-7">
            <div class="crm-card">
                <div class="crm-card-header">
                    <i class="fas fa-handshake" style="color:#10b981;"></i>
                    <h6>Recent Deals</h6>
                    <a href="{{ route('admin.deals.index') }}" class="ms-auto" style="font-size:0.72rem;color:#3b82f6;">View All</a>
                </div>
                <div class="table-scroll">
                    <table class="crm-table">
                        <thead><tr><th>Deal</th><th>Customer</th><th>Stage</th><th>Value</th><th>Date</th></tr></thead>
                        <tbody>
                            @forelse($recentDeals as $d)
                            <tr>
                                <td style="font-weight:600;font-size:0.78rem;max-width:130px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                    {{ Str::limit($d->title, 28) }}
                                </td>
                                <td style="font-size:0.72rem;color:#64748b;">{{ $d->customer?->full_name ?? '—' }}</td>
                                <td>
                                    @php $sb=['prospecting'=>'secondary','qualification'=>'info','proposal'=>'primary','negotiation'=>'warning','closed_won'=>'success','closed_lost'=>'danger'][$d->stage]??'secondary'; @endphp
                                    <span class="badge-crm badge-{{ $sb }}" style="font-size:0.65rem;">{{ ucwords(str_replace('_',' ',$d->stage)) }}</span>
                                </td>
                                <td style="font-weight:700;color:#10b981;font-size:0.78rem;">₱{{ number_format($d->value) }}</td>
                                <td style="font-size:0.68rem;color:#64748b;">{{ $d->created_at->format('M d') }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="5" style="text-align:center;color:#94a3b8;padding:20px;">No deals</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Leads by Source bars --}}
        <div class="col-12">
            <div class="crm-card">
                <div class="crm-card-header">
                    <i class="fas fa-broadcast-tower" style="color:#06b6d4;"></i>
                    <h6>Leads by Source</h6>
                </div>
                <div class="crm-card-body">
                    <div class="row g-2">
                        @forelse($leadsBySource as $src)
                        @php
                            $max = $leadsBySource->max('count');
                            $pct = $max > 0 ? ($src->count / $max * 100) : 0;
                            $colors = ['web'=>'#3b82f6','referral'=>'#10b981','social'=>'#8b5cf6','email'=>'#f59e0b','event'=>'#06b6d4','direct'=>'#ef4444','other'=>'#64748b'];
                            $color = $colors[$src->source] ?? '#64748b';
                        @endphp
                        <div class="col-12 col-md-6">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span style="font-size:0.78rem;min-width:72px;font-weight:600;color:#475569;">{{ ucfirst($src->source) }}</span>
                                <div style="flex:1;height:7px;background:#f1f5f9;border-radius:4px;">
                                    <div style="width:{{ $pct }}%;height:100%;background:{{ $color }};border-radius:4px;transition:width .5s ease;"></div>
                                </div>
                                <span style="font-size:0.78rem;font-weight:700;color:{{ $color }};min-width:22px;text-align:right;">{{ $src->count }}</span>
                            </div>
                        </div>
                        @empty
                        <div class="col-12" style="text-align:center;color:#94a3b8;padding:16px;font-size:0.8rem;">No source data</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js"></script>

{{-- CSV export via jsPDF + SheetJS --}}
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js"></script>

<script>
/* ── Chart defaults ────────────────────────────────────────────── */
Chart.defaults.color          = '#64748b';
Chart.defaults.borderColor    = '#f1f5f9';
Chart.defaults.font.family    = "'Plus Jakarta Sans', sans-serif";

const tooltipDefaults = {
    backgroundColor : '#1c2030',
    borderColor     : '#2d3650',
    borderWidth     : 1,
    titleColor      : '#e2e8f0',
    bodyColor       : '#94a3b8',
    padding         : 10,
};
const scaleDefaults = {
    x: { grid: { color: 'rgba(0,0,0,.04)' }, ticks: { font: { size: 11 } } },
    y: { grid: { color: 'rgba(0,0,0,.04)' }, ticks: { font: { size: 11 } } },
};

/* ── Revenue Bar ───────────────────────────────────────────────── */
const revData = @json($monthlyRevenue);
new Chart(document.getElementById('revenueChart'), {
    type: 'bar',
    data: {
        labels  : revData.map(d => d.label),
        datasets: [{
            label          : 'Revenue',
            data           : revData.map(d => d.revenue),
            backgroundColor: 'rgba(59,130,246,.25)',
            borderColor    : '#3b82f6',
            borderWidth    : 2,
            borderRadius   : 6,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: { legend: { display: false }, tooltip: { ...tooltipDefaults, callbacks: { label: c => ' ₱'+c.parsed.y.toLocaleString() } } },
        scales: { ...scaleDefaults, y: { ...scaleDefaults.y, ticks: { callback: v => '₱'+v.toLocaleString(), font: { size: 11 } } } }
    }
});

/* ── Leads Doughnut ────────────────────────────────────────────── */
const leadsData = @json($leadsByStatus);
new Chart(document.getElementById('leadsChart'), {
    type: 'doughnut',
    data: {
        labels  : ['New','Contacted','Negotiating','Closed','Lost'],
        datasets: [{
            data           : [leadsData.new?.count??0, leadsData.contacted?.count??0, leadsData.negotiating?.count??0, leadsData.closed?.count??0, leadsData.lost?.count??0],
            backgroundColor: ['#f59e0b','#06b6d4','#8b5cf6','#10b981','#ef4444'],
            borderWidth    : 0,
        }]
    },
    options: {
        cutout: '65%',
        responsive: true,
        plugins: { legend: { display: false }, tooltip: tooltipDefaults }
    }
});

/* ── Deals by Stage ────────────────────────────────────────────── */
const dealsData   = @json($dealsByStage);
const stageLabels = { prospecting:'Prospecting', qualification:'Qualify', proposal:'Proposal', negotiation:'Negotiate', closed_won:'Won', closed_lost:'Lost' };
const stageColors = { prospecting:'#64748b', qualification:'#06b6d4', proposal:'#3b82f6', negotiation:'#f59e0b', closed_won:'#10b981', closed_lost:'#ef4444' };
new Chart(document.getElementById('dealsChart'), {
    type: 'bar',
    data: {
        labels  : dealsData.map(d => stageLabels[d.stage] ?? d.stage),
        datasets: [{
            label          : 'Deals',
            data           : dealsData.map(d => d.count),
            backgroundColor: dealsData.map(d => (stageColors[d.stage]??'#64748b')+'33'),
            borderColor    : dealsData.map(d => stageColors[d.stage]??'#64748b'),
            borderWidth    : 2,
            borderRadius   : 5,
        }]
    },
    options: {
        indexAxis: 'y',
        responsive: true,
        plugins: { legend: { display: false }, tooltip: tooltipDefaults },
        scales: scaleDefaults,
    }
});

/* ── Customers Line ────────────────────────────────────────────── */
const contactsData = @json($monthlyContacts);
new Chart(document.getElementById('contactsChart'), {
    type: 'line',
    data: {
        labels  : contactsData.map(d => d.label),
        datasets: [{
            label          : 'Customers',
            data           : contactsData.map(d => d.count),
            borderColor    : '#10b981',
            backgroundColor: 'rgba(16,185,129,.1)',
            borderWidth    : 2,
            fill           : true,
            tension        : 0.4,
            pointBackgroundColor: '#10b981',
            pointRadius    : 4,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false }, tooltip: tooltipDefaults },
        scales: scaleDefaults,
    }
});

/* ── Export CSV ────────────────────────────────────────────────── */
function exportCSV() {
    const wb = XLSX.utils.book_new();

    // Summary sheet
    const summarySheet = XLSX.utils.aoa_to_sheet([
        ['Metric', 'Value'],
        ['Total Customers', {{ $summary['total_customers'] }}],
        ['New Customers',   {{ $summary['new_customers'] }}],
        ['Total Leads',     {{ $summary['total_leads'] }}],
        ['New Leads',       {{ $summary['new_leads'] }}],
        ['Pipeline Value',  {{ $summary['total_revenue'] }}],
        ['Conversion Rate', '{{ $summary['conversion_rate'] }}%'],
        ['Total Activities',{{ $summary['total_activities'] }}],
        ['Total Follow-ups',{{ $summary['total_followups'] }}],
    ]);
    XLSX.utils.book_append_sheet(wb, summarySheet, 'Summary');

    // Tables: grab every crm-table inside #export-tables
    document.querySelectorAll('#export-tables .crm-table').forEach((table, idx) => {
        const ws = XLSX.utils.table_to_sheet(table);
        const sheetName = (table.closest('.crm-card')?.querySelector('h6')?.innerText?.trim() || 'Sheet '+(idx+1)).substring(0,31);
        XLSX.utils.book_append_sheet(wb, ws, sheetName);
    });

    XLSX.writeFile(wb, 'tread-crm-report.xlsx');
}

/* ── Export PDF ────────────────────────────────────────────────── */
async function exportPDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF({ orientation: 'portrait', unit: 'mm', format: 'a4' });

    const primary = [59, 130, 246];
    const pageW   = doc.internal.pageSize.getWidth();
    let y = 18;

    // Header bar
    doc.setFillColor(...primary);
    doc.rect(0, 0, pageW, 14, 'F');
    doc.setTextColor(255, 255, 255);
    doc.setFontSize(12);
    doc.setFont('helvetica', 'bold');
    doc.text('Tread CRM — Reports', 10, 9.5);
    doc.setFontSize(8);
    doc.setFont('helvetica', 'normal');
    doc.text(new Date().toLocaleDateString('en-PH', { year:'numeric', month:'long', day:'numeric' }), pageW - 10, 9.5, { align: 'right' });
    doc.setTextColor(30, 30, 30);

    // Summary KPIs
    doc.setFontSize(10);
    doc.setFont('helvetica', 'bold');
    doc.text('Summary', 10, y);
    y += 4;

    const kpis = [
        ['Total Customers', '{{ $summary['total_customers'] }}'],
        ['Total Leads',     '{{ $summary['total_leads'] }}'],
        ['Pipeline Value',  '{{ number_format($summary['total_revenue'], 0) }}'],
        ['Conversion Rate', '{{ $summary['conversion_rate'] }}%'],
        ['Total Activities','{{ $summary['total_activities'] }}'],
        ['Total Follow-ups','{{ $summary['total_followups'] }}'],
    ];

    doc.autoTable({
        startY    : y,
        head      : [['Metric', 'Value']],
        body      : kpis,
        theme     : 'striped',
        headStyles: { fillColor: primary, fontSize: 8, fontStyle: 'bold' },
        bodyStyles: { fontSize: 8 },
        margin    : { left: 10, right: 10 },
    });
    y = doc.lastAutoTable.finalY + 8;

    // Tables
    const tables = document.querySelectorAll('#export-tables .crm-table');
    for (const table of tables) {
        const title = table.closest('.crm-card')?.querySelector('h6')?.innerText?.trim() ?? '';
        const heads = [...table.querySelectorAll('thead th')].map(th => th.innerText.trim());
        const rows  = [...table.querySelectorAll('tbody tr')].map(tr =>
            [...tr.querySelectorAll('td')].map(td => td.innerText.trim())
        );

        if (rows.length === 0 || (rows.length === 1 && rows[0].join('').includes('No '))) continue;

        if (y > 240) { doc.addPage(); y = 18; }

        doc.setFontSize(9);
        doc.setFont('helvetica', 'bold');
        doc.setTextColor(...primary);
        doc.text(title, 10, y);
        doc.setTextColor(30, 30, 30);
        y += 3;

        doc.autoTable({
            startY    : y,
            head      : [heads],
            body      : rows,
            theme     : 'striped',
            headStyles: { fillColor: primary, fontSize: 7, fontStyle: 'bold' },
            bodyStyles: { fontSize: 7 },
            margin    : { left: 10, right: 10 },
        });
        y = doc.lastAutoTable.finalY + 8;
    }

    doc.save('tread-crm-report.pdf');
}
</script>
@endpush