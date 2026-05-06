@extends('layouts.app')
@section('title', 'Dashboard - Tread CRM')
@section('page_title', 'Dashboard')
@section('page_subtitle', 'Welcome back to your CRM control center.')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    /* ── Global font ─────────────────────────────────── */
    body,
    .card, .card-body, .card-header,
    .list-group-item,
    h1, h2, h3, h4, h5, h6,
    p, small, span, a, button, .btn {
        font-family: 'Plus Jakarta Sans', sans-serif !important;
    }

    /* ── Stat colors ─────────────────────────────────── */
    .stat-change-up   { color: #198754; }
    .stat-change-down { color: #dc3545; }

    /* ── Page header ─────────────────────────────────── */
    .page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        flex-wrap: nowrap;          /* keep title + buttons on one line */
    }
    .page-header-left {
        flex: 1 1 0;
        min-width: 0;
    }
    .page-header-left .page-title {
        font-size: 1.25rem;
        font-weight: 700;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-bottom: 0.15rem;
    }
    .page-header-left .page-subtitle {
        font-size: 0.78rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-bottom: 0;
        color: #6c757d;
    }
    .page-actions {
        display: flex;
        gap: 0.4rem;
        flex-shrink: 0;
    }
    /* Shrink buttons on mobile so they fit beside the title */
    @media (max-width: 575.98px) {
        .page-actions .btn-crm-primary,
        .page-actions .btn-crm-secondary {
            font-size: 0.72rem !important;
            padding: 0.3rem 0.55rem !important;
            white-space: nowrap;
        }
    }

    /* ── Stat cards ──────────────────────────────────── */
    .stat-card-body {
        padding: 0.85rem 0.6rem !important;
    }
    .stat-avatar {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 0.6rem;
        font-size: 1rem;
    }
    .stat-number {
        font-size: 1.35rem !important;  /* fixed size that won't overflow on 390px */
        font-weight: 800 !important;
        line-height: 1.15;
        word-break: keep-all;           /* never break ₱5,424,776 mid-number */
        overflow-wrap: normal;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        display: block;
    }
    .stat-label {
        font-size: 0.7rem !important;
        color: #6c757d;
        margin-bottom: 0.3rem;
        line-height: 1.2;
    }
    .stat-change {
        font-size: 0.68rem !important;
        font-weight: 600;
        line-height: 1.3;
    }

    /* ── Chart wrapper ───────────────────────────────── */
    .chart-wrapper {
        position: relative;
        height: 240px;
    }
    @media (max-width: 575.98px) {
        .chart-wrapper { height: 180px; }
    }
    .chart-wrapper canvas {
        position: absolute;
        inset: 0;
        width: 100% !important;
        height: 100% !important;
    }

    /* ── Card headers ────────────────────────────────── */
    .card-header h5 { font-size: 0.85rem !important; }

    /* ── Pipeline ────────────────────────────────────── */
    .pipeline-item { min-width: 0; font-size: 0.8rem; }

    /* ── Activity rows ───────────────────────────────── */
    .activity-row { flex-wrap: nowrap; gap: 0.5rem; align-items: center; }
    .activity-text { min-width: 0; flex: 1 1 0; }
    .activity-text h6 { font-size: 0.82rem !important; margin-bottom: 0.1rem; }
    .activity-text small { font-size: 0.68rem; word-break: break-word; }
    .activity-badge-col { flex-shrink: 0; }

    /* ── Task rows ───────────────────────────────────── */
    .task-title { font-size: 0.82rem !important; }
    .task-badge-wrap { flex-shrink: 0; }

    /* ── Top customer cards ──────────────────────────── */
    .customer-card-body {
        padding: 0.75rem 0.5rem !important;
        text-align: center;
    }
    .customer-avatar {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 0.5rem;
        font-size: 0.8rem;
        font-weight: 700;
        color: #fff;
    }
    .customer-name { font-size: 0.78rem !important; font-weight: 700; }
    .customer-company { font-size: 0.68rem !important; color: #6c757d; }
    .customer-value { font-size: 0.82rem !important; font-weight: 700; }
</style>
@endpush

@section('content')
<div class="container-fluid py-3 py-md-4">

    {{-- ─── Welcome Header ──────────────────────────────────────────────── --}}
    <div class="page-header mb-4 mb-md-5">
        <div class="page-header-left">
            <h1 class="page-title">
                <i class="fas fa-chart-line me-2 text-primary"></i>Dashboard
            </h1>
            <p class="page-subtitle">
                Welcome back, {{ auth()->user()->name }}!
            </p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.dashboard') }}" class="btn-crm-secondary">
                <i class="fas fa-sync-alt"></i> Refresh
            </a>
            <a href="{{ route('admin.leads.index') }}" class="btn-crm-primary">
                <i class="fas fa-plus"></i> New Lead
            </a>
        </div>
    </div>

    {{-- ─── Summary Cards ────────────────────────────────────────────────── --}}
    <div class="row g-2 g-md-4 mb-4 mb-md-5">

        {{-- Total Customers --}}
        <div class="col-6 col-xl-3">
            <div class="card card-tread border-0 shadow-sm h-100">
                <div class="card-body stat-card-body text-center">
                    <div class="stat-avatar bg-success bg-opacity-10 text-success">
                        <i class="fas fa-users"></i>
                    </div>
                    <span class="stat-number text-success">{{ number_format($stats['totalCustomers']) }}</span>
                    <p class="stat-label mb-1">Total Customers</p>
                    @if($stats['customersGrowth'] >= 0)
                        <span class="stat-change stat-change-up">
                            <i class="fas fa-arrow-up"></i> {{ $stats['customersGrowth'] }}% from last month
                        </span>
                    @else
                        <span class="stat-change stat-change-down">
                            <i class="fas fa-arrow-down"></i> {{ abs($stats['customersGrowth']) }}% from last month
                        </span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Total Leads --}}
        <div class="col-6 col-xl-3">
            <div class="card card-tread border-0 shadow-sm h-100">
                <div class="card-body stat-card-body text-center">
                    <div class="stat-avatar bg-warning bg-opacity-10 text-warning">
                        <i class="fas fa-bullseye"></i>
                    </div>
                    <span class="stat-number text-warning">{{ number_format($stats['totalLeads']) }}</span>
                    <p class="stat-label mb-1">Total Leads</p>
                    @if($stats['leadsGrowth'] >= 0)
                        <span class="stat-change stat-change-up">
                            <i class="fas fa-arrow-up"></i> {{ $stats['leadsGrowth'] }}% growth
                        </span>
                    @else
                        <span class="stat-change stat-change-down">
                            <i class="fas fa-arrow-down"></i> {{ abs($stats['leadsGrowth']) }}% decline
                        </span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Total Deals --}}
        <div class="col-6 col-xl-3">
            <div class="card card-tread border-0 shadow-sm h-100">
                <div class="card-body stat-card-body text-center">
                    <div class="stat-avatar bg-info bg-opacity-10 text-info">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <span class="stat-number text-info">{{ number_format($stats['totalDeals']) }}</span>
                    <p class="stat-label mb-1">Total Deals</p>
                    @if($stats['dealsGrowth'] >= 0)
                        <span class="stat-change stat-change-up">
                            <i class="fas fa-arrow-up"></i> {{ $stats['dealsGrowth'] }}% increase
                        </span>
                    @else
                        <span class="stat-change stat-change-down">
                            <i class="fas fa-arrow-down"></i> {{ abs($stats['dealsGrowth']) }}% decrease
                        </span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Total Revenue --}}
        <div class="col-6 col-xl-3">
            <div class="card card-tread border-0 shadow-sm h-100">
                <div class="card-body stat-card-body text-center">
                    <div class="stat-avatar bg-primary bg-opacity-10 text-primary">
                        <i class="fas fa-peso-sign"></i>
                    </div>
                    {{-- Use PHP to format to short form (e.g. ₱5.4M) when large --}}
                    @php
                        $rev = $stats['totalRevenue'];
                        $revDisplay = $rev >= 1000000
                            ? '₱' . number_format($rev / 1000000, 1) . 'M'
                            : ($rev >= 1000 ? '₱' . number_format($rev / 1000, 1) . 'K' : '₱' . number_format($rev, 0));
                    @endphp
                    <span class="stat-number text-primary">{{ $revDisplay }}</span>
                    <p class="stat-label mb-1">Total Revenue</p>
                    @if($stats['revenueGrowth'] >= 0)
                        <span class="stat-change stat-change-up">
                            <i class="fas fa-arrow-up"></i> +{{ $stats['revenueGrowth'] }}% vs last month
                        </span>
                    @else
                        <span class="stat-change stat-change-down">
                            <i class="fas fa-arrow-down"></i> {{ abs($stats['revenueGrowth']) }}% vs last month
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ─── Charts Row ───────────────────────────────────────────────────── --}}
    <div class="row g-3 g-md-4 mb-4 mb-md-5">

        {{-- Monthly Revenue Chart --}}
        <div class="col-12 col-xl-8">
            <div class="card card-tread border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom d-flex align-items-center justify-content-between
                            flex-wrap gap-2">
                    <h5 class="card-title mb-0 fw-semibold">
                        <i class="fas fa-chart-line me-2 text-primary"></i>Monthly Revenue (Won Deals)
                    </h5>
                    <span class="text-muted small">Last 6 months</span>
                </div>
                <div class="card-body p-3 p-md-4">
                    {{-- chart-wrapper controls canvas height responsively --}}
                    <div class="chart-wrapper">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Leads Pipeline --}}
        <div class="col-12 col-xl-4">
            <div class="card card-tread border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom">
                    <h5 class="card-title mb-0 fw-semibold">
                        <i class="fas fa-filter me-2 text-warning"></i>Leads Pipeline
                    </h5>
                </div>
                <div class="card-body p-3 p-md-4">
                    <div class="d-flex align-items-center mb-4">
                        <div class="flex-grow-1">
                            <h6 class="mb-1 fw-bold">Conversion Rate</h6>
                            <div class="progress" style="height:8px;">
                                <div class="progress-bar bg-success"
                                     style="width:{{ $pipeline['conversionRate'] }}%"></div>
                            </div>
                            <small class="text-success fw-semibold">
                                {{ $pipeline['conversionRate'] }}%
                                ({{ $pipeline['closedLeads'] }}/{{ $stats['totalLeads'] }})
                            </small>
                        </div>
                    </div>
                    <div class="list-group list-group-flush">
                        @foreach($pipeline['stages'] as $stage)
                        @php
                            $pct = $stats['totalLeads'] > 0
                                ? round(($stage['count'] / $stats['totalLeads']) * 100) : 0;
                            $bar = match($stage['status']) {
                                'new'         => 'bg-warning',
                                'contacted'   => 'bg-info',
                                'negotiating' => 'bg-primary',
                                'closed'      => 'bg-success',
                                'lost'        => 'bg-danger',
                                default       => 'bg-secondary',
                            };
                        @endphp
                        <div class="list-group-item px-0 border-0 py-2 pipeline-item
                            {{ $stage['status'] === 'closed' ? 'fw-bold' : '' }}">
                            <div class="d-flex justify-content-between">
                                <span class="{{ $stage['status'] === 'closed' ? 'text-success' : 'text-muted' }}">
                                    {{ ucfirst($stage['status']) }}
                                </span>
                                <span class="fw-bold">{{ $stage['count'] }}</span>
                            </div>
                            <div class="progress mt-1" style="height:4px;">
                                <div class="progress-bar {{ $bar }}" style="width:{{ $pct }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ─── Activity & Tasks ─────────────────────────────────────────────── --}}
    <div class="row g-3 g-md-4 mb-4 mb-md-5">

        {{-- Recent Activities --}}
        <div class="col-12 col-lg-6">
            <div class="card card-tread border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="card-title mb-0 fw-semibold">
                        <i class="fas fa-bell me-2 text-info"></i>Recent Activity
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($recentActivities as $activity)
                        <div class="list-group-item px-3 px-md-4 py-3 border-0">
                            <div class="d-flex activity-row align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="{{ $activity['iconBg'] }} rounded-circle p-2">
                                        <i class="{{ $activity['icon'] }}"></i>
                                    </div>
                                </div>
                                <div class="activity-text ms-2">
                                    <h6 class="mb-1 text-truncate">{{ $activity['title'] }}</h6>
                                    <small class="text-muted">
                                        {{ $activity['subtitle'] }} • {{ $activity['time']->diffForHumans() }}
                                    </small>
                                </div>
                                <div class="activity-badge-col ms-2 flex-shrink-0">
                                    <span class="badge {{ $activity['badgeClass'] }}">{{ $activity['badge'] }}</span>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="list-group-item px-4 py-4 border-0 text-muted text-center">
                            No recent activity found.
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- Upcoming Tasks --}}
        <div class="col-12 col-lg-6">
            <div class="card card-tread border-0 shadow-sm">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center
                            flex-wrap gap-2">
                    <h5 class="card-title mb-0 fw-semibold">
                        <i class="fas fa-tasks me-2 text-warning"></i>Upcoming Tasks
                    </h5>
                    <a href="{{ route('admin.tasks.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($upcomingTasks as $task)
                        @php
                            $dueDate    = \Carbon\Carbon::parse($task->due_date);
                            $isToday    = $dueDate->isToday();
                            $isTomorrow = $dueDate->isTomorrow();
                            $isOverdue  = $dueDate->isPast() && !$isToday;

                            $badgeClass = match(true) {
                                $isOverdue  => 'bg-danger',
                                $isToday    => 'bg-warning text-dark',
                                $isTomorrow => 'bg-info text-dark',
                                default     => 'bg-secondary',
                            };
                            $badgeLabel = match(true) {
                                $isOverdue  => 'Overdue',
                                $isToday    => 'Today',
                                $isTomorrow => 'Tomorrow',
                                default     => $dueDate->format('D d M'),
                            };
                        @endphp
                        <div class="list-group-item px-3 px-md-4 py-3 border-0
                            {{ $isOverdue ? 'bg-danger bg-opacity-10' : '' }}">
                            <div class="d-flex justify-content-between align-items-start gap-2">
                                <div class="min-width-0">
                                    <h6 class="mb-1 text-truncate">{{ $task->title }}</h6>
                                    <small class="text-muted">
                                        @if($task->customer)
                                            {{ $task->customer->first_name }} {{ $task->customer->last_name }}
                                        @elseif($task->lead)
                                            {{ $task->lead->name }}
                                        @else
                                            No customer
                                        @endif
                                    </small>
                                </div>
                                <div class="task-badge-wrap">
                                    <span class="badge {{ $badgeClass }}">{{ $badgeLabel }}</span>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="list-group-item px-4 py-4 border-0 text-muted text-center">
                            No upcoming tasks. 🎉
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- Top Contacts by Deal Value --}}
        <div class="col-12">
            <div class="card card-tread border-0 shadow-sm">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center
                            flex-wrap gap-2">
                    <h5 class="card-title mb-0 fw-semibold">
                        <i class="fas fa-star me-2 text-warning"></i>Top Customers by Deal Value
                    </h5>
                    <a href="{{ route('admin.customers.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body p-2 p-md-3">
                    <div class="row g-2 g-md-3">
                        @php
                            $avatarColors = ['bg-primary','bg-success','bg-warning','bg-info'];
                        @endphp
                        @forelse($topCustomers as $i => $customer)
                        {{-- 2-up on phones, 4-up on large screens --}}
                        <div class="col-6 col-lg-3">
                            <div class="card h-100 border-0 bg-light">
                                <div class="customer-card-body card-body">
                                    <div class="customer-avatar {{ $avatarColors[$i % 4] }}">
                                        {{ strtoupper(substr($customer->first_name,0,1) . substr($customer->last_name,0,1)) }}
                                    </div>
                                    <h6 class="customer-name mb-1 text-truncate">
                                        {{ $customer->first_name }} {{ $customer->last_name }}
                                    </h6>
                                    <p class="customer-company mb-1 text-truncate">{{ $customer->company ?? '—' }}</p>
                                    <div class="customer-value text-primary mb-1">
                                        @php
                                            $dv = $customer->deals_sum_value ?? 0;
                                            echo $dv >= 1000000
                                                ? '₱'.number_format($dv/1000000,1).'M'
                                                : ($dv >= 1000 ? '₱'.number_format($dv/1000,1).'K' : '₱'.number_format($dv,0));
                                        @endphp
                                    </div>
                                    <small class="text-success" style="font-size:0.68rem;">
                                        {{ $customer->deals_count ?? 0 }}
                                        {{ Str::plural('deal', $customer->deals_count ?? 0) }}
                                    </small>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12 text-center text-muted py-4">No contact data available.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection


@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('salesChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: {!! json_encode($chartData['labels']) !!},
        datasets: [{
            label: 'Revenue (₱)',
            data: {!! json_encode($chartData['values']) !!},
            borderColor: '#0d6efd',
            backgroundColor: 'rgba(13,110,253,0.08)',
            borderWidth: 2.5,
            pointBackgroundColor: '#0d6efd',
            pointRadius: 4,
            tension: 0.4,
            fill: true,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: ctx => ' ₱' + ctx.parsed.y.toLocaleString()
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: v => '₱' + v.toLocaleString(),
                    // Fewer ticks on small screens
                    maxTicksLimit: window.innerWidth < 576 ? 4 : 6,
                }
            },
            x: {
                ticks: {
                    // Abbreviate labels on mobile to avoid overlap
                    callback: function(val, index) {
                        const label = this.getLabelForValue(val);
                        return window.innerWidth < 576 ? label.substring(0, 3) : label;
                    }
                }
            }
        }
    }
});
</script>
@endpush