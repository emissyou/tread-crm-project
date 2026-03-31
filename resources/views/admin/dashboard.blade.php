@extends('layouts.app')
@section('title', 'Dashboard - Tread CRM')

@push('styles')
<style>
    .stat-change-up   { color: #198754; }
    .stat-change-down { color: #dc3545; }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">

    {{-- ─── Welcome Header ──────────────────────────────────────────────── --}}
    <div class="row mb-5">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h1 class="h2 fw-bold mb-1">
                        <i class="fas fa-chart-line text-primary me-2"></i>Dashboard
                    </h1>
                    <p class="text-muted mb-0">
                        Welcome back, {{ auth()->user()->name }}! Here's what's happening with your CRM.
                    </p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary">
                        <i class="fas fa-sync-alt me-1"></i>Refresh
                    </a>
                    <a href="{{ route('admin.leads.store') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>New Lead
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- ─── Summary Cards ────────────────────────────────────────────────── --}}
    <div class="row g-4 mb-5">

        {{-- Total Contacts --}}
        <div class="col-xl-3 col-md-6">
            <div class="card card-tread border-0 shadow-sm h-100">
                <div class="card-body text-center p-4">
                    <div class="avatar bg-success bg-opacity-10 text-success rounded-circle mx-auto mb-3 p-3
                                d-flex align-items-center justify-content-center" style="width:70px;height:70px;">
                        <i class="fas fa-users fa-lg"></i>
                    </div>
                    <h3 class="h2 fw-bold text-success mb-1">{{ number_format($stats['totalContacts']) }}</h3>
                    <p class="text-muted mb-2">Total Contacts</p>
                    @if($stats['contactsGrowth'] >= 0)
                        <small class="stat-change-up fw-semibold">
                            <i class="fas fa-arrow-up me-1"></i>{{ $stats['contactsGrowth'] }}% from last month
                        </small>
                    @else
                        <small class="stat-change-down fw-semibold">
                            <i class="fas fa-arrow-down me-1"></i>{{ abs($stats['contactsGrowth']) }}% from last month
                        </small>
                    @endif
                </div>
            </div>
        </div>

        {{-- Total Leads --}}
        <div class="col-xl-3 col-md-6">
            <div class="card card-tread border-0 shadow-sm h-100">
                <div class="card-body text-center p-4">
                    <div class="avatar bg-warning bg-opacity-10 text-warning rounded-circle mx-auto mb-3 p-3
                                d-flex align-items-center justify-content-center" style="width:70px;height:70px;">
                        <i class="fas fa-bullseye fa-lg"></i>
                    </div>
                    <h3 class="h2 fw-bold text-warning mb-1">{{ number_format($stats['totalLeads']) }}</h3>
                    <p class="text-muted mb-2">Total Leads</p>
                    @if($stats['leadsGrowth'] >= 0)
                        <small class="stat-change-up fw-semibold">
                            <i class="fas fa-arrow-up me-1"></i>{{ $stats['leadsGrowth'] }}% growth
                        </small>
                    @else
                        <small class="stat-change-down fw-semibold">
                            <i class="fas fa-arrow-down me-1"></i>{{ abs($stats['leadsGrowth']) }}% decline
                        </small>
                    @endif
                </div>
            </div>
        </div>

        {{-- Total Deals --}}
        <div class="col-xl-3 col-md-6">
            <div class="card card-tread border-0 shadow-sm h-100">
                <div class="card-body text-center p-4">
                    <div class="avatar bg-info bg-opacity-10 text-info rounded-circle mx-auto mb-3 p-3
                                d-flex align-items-center justify-content-center" style="width:70px;height:70px;">
                        <i class="fas fa-handshake fa-lg"></i>
                    </div>
                    <h3 class="h2 fw-bold text-info mb-1">{{ number_format($stats['totalDeals']) }}</h3>
                    <p class="text-muted mb-2">Total Deals</p>
                    @if($stats['dealsGrowth'] >= 0)
                        <small class="stat-change-up fw-semibold">
                            <i class="fas fa-arrow-up me-1"></i>{{ $stats['dealsGrowth'] }}% increase
                        </small>
                    @else
                        <small class="stat-change-down fw-semibold">
                            <i class="fas fa-arrow-down me-1"></i>{{ abs($stats['dealsGrowth']) }}% decrease
                        </small>
                    @endif
                </div>
            </div>
        </div>

        {{-- Total Revenue --}}
        <div class="col-xl-3 col-md-6">
            <div class="card card-tread border-0 shadow-sm h-100">
                <div class="card-body text-center p-4">
                    <div class="avatar bg-primary bg-opacity-10 text-primary rounded-circle mx-auto mb-3 p-3
                                d-flex align-items-center justify-content-center" style="width:70px;height:70px;">
                        <i class="fas fa-dollar-sign fa-lg"></i>
                    </div>
                    <h3 class="h2 fw-bold text-primary mb-2">
                        ${{ number_format($stats['totalRevenue'], 0) }}
                    </h3>
                    <p class="text-muted mb-2">Total Revenue</p>
                    @if($stats['revenueGrowth'] >= 0)
                        <small class="stat-change-up fw-semibold">
                            <i class="fas fa-arrow-up me-1"></i>+{{ $stats['revenueGrowth'] }}% vs last month
                        </small>
                    @else
                        <small class="stat-change-down fw-semibold">
                            <i class="fas fa-arrow-down me-1"></i>{{ abs($stats['revenueGrowth']) }}% vs last month
                        </small>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ─── Charts Row ───────────────────────────────────────────────────── --}}
    <div class="row g-4 mb-5">

        {{-- Monthly Revenue Chart --}}
        <div class="col-xl-8">
            <div class="card card-tread border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom d-flex align-items-center justify-content-between">
                    <h5 class="card-title mb-0 fw-semibold">
                        <i class="fas fa-chart-line me-2 text-primary"></i>Monthly Revenue (Won Deals)
                    </h5>
                    <span class="text-muted small">Last 6 months</span>
                </div>
                <div class="card-body p-4">
                    <canvas id="salesChart" height="120"></canvas>
                </div>
            </div>
        </div>

        {{-- Leads Pipeline --}}
        <div class="col-xl-4">
            <div class="card card-tread border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom">
                    <h5 class="card-title mb-0 fw-semibold">
                        <i class="fas fa-filter me-2 text-warning"></i>Leads Pipeline
                    </h5>
                </div>
                <div class="card-body p-4">
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
                        <div class="list-group-item px-0 border-0 py-2
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
    <div class="row g-4">

        {{-- Recent Activities --}}
        <div class="col-lg-6">
            <div class="card card-tread border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="card-title mb-0 fw-semibold">
                        <i class="fas fa-bell me-2 text-info"></i>Recent Activity
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($recentActivities as $activity)
                        <div class="list-group-item px-4 py-3 border-0">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <div class="{{ $activity['iconBg'] }} rounded-circle p-2">
                                        <i class="{{ $activity['icon'] }}"></i>
                                    </div>
                                </div>
                                <div class="col">
                                    <h6 class="mb-1">{{ $activity['title'] }}</h6>
                                    <small class="text-muted">
                                        {{ $activity['subtitle'] }} • {{ $activity['time']->diffForHumans() }}
                                    </small>
                                </div>
                                <div class="col-auto">
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
        <div class="col-lg-6">
            <div class="card card-tread border-0 shadow-sm">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
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
                        <div class="list-group-item px-4 py-3 border-0
                            {{ $isOverdue ? 'bg-danger bg-opacity-10' : '' }}">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="mb-1">{{ $task->title }}</h6>
                                    <small class="text-muted">
                                        @if($task->contact)
                                            {{ $task->contact->first_name }} {{ $task->contact->last_name }}
                                        @elseif($task->lead)
                                            {{ $task->lead->name }}
                                        @else
                                            No contact
                                        @endif
                                    </small>
                                </div>
                                <div class="align-self-start">
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
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-semibold">
                        <i class="fas fa-star me-2 text-warning"></i>Top Contacts by Deal Value
                    </h5>
                    <a href="{{ route('admin.contacts.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @php
                            $avatarColors = ['bg-primary','bg-success','bg-warning','bg-info'];
                        @endphp
                        @forelse($topContacts as $i => $contact)
                        <div class="col-md-6 col-lg-3">
                            <div class="card h-100 border-0 bg-light">
                                <div class="card-body text-center p-4">
                                    <div class="avatar {{ $avatarColors[$i % 4] }} text-white rounded-circle
                                                mx-auto mb-3 d-flex align-items-center justify-content-center fw-bold"
                                         style="width:60px;height:60px;">
                                        {{ strtoupper(substr($contact->first_name,0,1) . substr($contact->last_name,0,1)) }}
                                    </div>
                                    <h6 class="fw-bold mb-1">{{ $contact->first_name }} {{ $contact->last_name }}</h6>
                                    <p class="text-muted small mb-2">{{ optional($contact->company)->name ?? '—' }}</p>
                                    <div class="h6 fw-bold text-primary mb-1">
                                        ${{ number_format($contact->deals_sum_value ?? 0) }}
                                    </div>
                                    <small class="text-success">
                                        {{ $contact->deals_count ?? 0 }}
                                        {{ Str::plural('deal', $contact->deals_count ?? 0) }}
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
            label: 'Revenue ($)',
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
        maintainAspectRatio: false,   // ← ADD THIS
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: ctx => ' $' + ctx.parsed.y.toLocaleString()
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { callback: v => '$' + v.toLocaleString() }
            }
        }
    }
});
    
    
   
</script>
@endpush