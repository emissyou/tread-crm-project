@extends('layouts.app')
@section('title', 'Reports')
@section('breadcrumb', 'Reports')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
<style>
.report-card { background:var(--crm-card);border:1px solid var(--crm-border);border-radius:14px;padding:22px; }
.kpi-value { font-family:'Syne',sans-serif;font-size:26px;font-weight:800; }
.kpi-label { font-size:12px;color:var(--crm-muted);margin-top:4px; }
.kpi-change { font-size:12px;font-weight:600;margin-top:6px; }
</style>
@endpush

@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h1 class="page-title"><i class="fas fa-chart-bar me-2" style="color:var(--crm-purple)"></i>Reports</h1>
        <p class="page-subtitle">Analytics and business insights</p>
    </div>
    <form method="GET" action="{{ route('admin.reports.index') }}" class="d-flex gap-2 align-items-center">
        <label class="crm-label mb-0" style="white-space:nowrap">Period:</label>
        <select name="period" class="crm-input" style="max-width:150px" onchange="this.form.submit()">
            <option value="7"  {{ $period==7?'selected':'' }}>Last 7 days</option>
            <option value="30" {{ $period==30?'selected':'' }}>Last 30 days</option>
            <option value="90" {{ $period==90?'selected':'' }}>Last 90 days</option>
            <option value="365"{{ $period==365?'selected':'' }}>Last 12 months</option>
        </select>
    </form>
</div>

<!-- KPI Grid -->
<div class="row g-3 mb-4">
    @php
    $kpis = [
        ['label'=>'Total Contacts',   'value'=>number_format($summary['total_contacts']),   'sub'=>'+'.$summary['new_contacts'].' new',     'color'=>'#3b82f6','icon'=>'fa-users'],
        ['label'=>'Total Leads',      'value'=>number_format($summary['total_leads']),       'sub'=>'+'.$summary['new_leads'].' new',        'color'=>'#f59e0b','icon'=>'fa-bullseye'],
        ['label'=>'Conversion Rate',  'value'=>$summary['conversion_rate'].'%',              'sub'=>'Leads closed',                          'color'=>'#10b981','icon'=>'fa-percent'],
        ['label'=>'Won Deals',        'value'=>number_format($summary['won_deals']),         'sub'=>'of '.$summary['total_deals'].' total',  'color'=>'#8b5cf6','icon'=>'fa-trophy'],
        ['label'=>'Total Revenue',    'value'=>'$'.number_format($summary['total_revenue']), 'sub'=>'From won deals',                        'color'=>'#10b981','icon'=>'fa-dollar-sign'],
        ['label'=>'Pipeline Value',   'value'=>'$'.number_format($summary['pipeline_value']),'sub'=>'Active deals',                         'color'=>'#06b6d4','icon'=>'fa-funnel-dollar'],
        ['label'=>'Tasks Completed',  'value'=>number_format($summary['tasks_completed']),   'sub'=>$summary['tasks_overdue'].' overdue',    'color'=>'#f59e0b','icon'=>'fa-check-circle'],
        ['label'=>'Lost Deals',       'value'=>number_format($summary['lost_deals']),        'sub'=>'Closed lost',                           'color'=>'#ef4444','icon'=>'fa-times-circle'],
    ];
    @endphp
    @foreach($kpis as $k)
    <div class="col-6 col-md-3">
        <div class="report-card">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:12px">
                <div class="kpi-value" style="color:{{ $k['color'] }}">{{ $k['value'] }}</div>
                <div style="width:38px;height:38px;border-radius:10px;background:{{ $k['color'] }}22;display:flex;align-items:center;justify-content:center;color:{{ $k['color'] }};font-size:15px">
                    <i class="fas {{ $k['icon'] }}"></i>
                </div>
            </div>
            <div class="kpi-label">{{ $k['label'] }}</div>
            <div class="kpi-change" style="color:{{ $k['color'] }}">{{ $k['sub'] }}</div>
        </div>
    </div>
    @endforeach
</div>

<!-- Charts Row 1 -->
<div class="row g-3 mb-3">
    <div class="col-md-8">
        <div class="report-card" style="height:100%">
            <h6 style="font-family:'Syne',sans-serif;font-weight:700;margin-bottom:16px">
                <i class="fas fa-chart-line me-2" style="color:var(--crm-primary)"></i>Monthly Revenue (Last 6 Months)
            </h6>
            <canvas id="revenueChart" height="100"></canvas>
        </div>
    </div>
    <div class="col-md-4">
        <div class="report-card" style="height:100%">
            <h6 style="font-family:'Syne',sans-serif;font-weight:700;margin-bottom:16px">
                <i class="fas fa-chart-pie me-2" style="color:var(--crm-warning)"></i>Leads by Status
            </h6>
            <canvas id="leadsChart" height="160"></canvas>
            <div style="margin-top:12px">
                @foreach(['new'=>'#f59e0b','contacted'=>'#06b6d4','negotiating'=>'#8b5cf6','closed'=>'#10b981','lost'=>'#ef4444'] as $status => $color)
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <div class="d-flex align-items-center gap-2">
                        <div style="width:10px;height:10px;border-radius:50%;background:{{ $color }}"></div>
                        <span style="font-size:12px">{{ ucfirst($status) }}</span>
                    </div>
                    <span style="font-size:12px;font-weight:600">{{ $leadsByStatus[$status]->count ?? 0 }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Charts Row 2 -->
<div class="row g-3 mb-3">
    <div class="col-md-6">
        <div class="report-card">
            <h6 style="font-family:'Syne',sans-serif;font-weight:700;margin-bottom:16px">
                <i class="fas fa-bar-chart me-2" style="color:var(--crm-success)"></i>Deals by Stage
            </h6>
            <canvas id="dealsChart" height="130"></canvas>
        </div>
    </div>
    <div class="col-md-6">
        <div class="report-card">
            <h6 style="font-family:'Syne',sans-serif;font-weight:700;margin-bottom:16px">
                <i class="fas fa-users me-2" style="color:var(--crm-info)"></i>New Contacts per Month
            </h6>
            <canvas id="contactsChart" height="130"></canvas>
        </div>
    </div>
</div>

<!-- Tables Row -->
<div class="row g-3">
    <!-- Top Contacts -->
    <div class="col-md-5">
        <div class="crm-card">
            <div class="crm-card-header">
                <i class="fas fa-star" style="color:var(--crm-warning)"></i>
                <h5 class="card-title">Top Contacts by Deals</h5>
            </div>
            <div style="overflow-x:auto">
                <table class="crm-table">
                    <thead><tr><th>#</th><th>Contact</th><th>Deals</th><th>Revenue</th></tr></thead>
                    <tbody>
                        @forelse($topContacts as $i => $c)
                        <tr>
                            <td style="font-weight:700;color:var(--crm-muted)">{{ $i+1 }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar-circle" style="background:{{ ['#3b82f6','#10b981','#f59e0b','#8b5cf6','#06b6d4'][$i%5] }};color:#fff;width:30px;height:30px;font-size:11px">
                                        {{ $c->initials }}
                                    </div>
                                    <div>
                                        <div style="font-size:13px;font-weight:600">{{ $c->full_name }}</div>
                                        <div style="font-size:11px;color:var(--crm-muted)">{{ $c->company }}</div>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge-crm badge-primary">{{ $c->deals_count }}</span></td>
                            <td style="font-weight:700;color:var(--crm-success);font-size:13px">
                                ${{ number_format($c->total_value ?? 0) }}
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" style="text-align:center;color:var(--crm-muted);padding:20px">No data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Deals -->
    <div class="col-md-7">
        <div class="crm-card">
            <div class="crm-card-header">
                <i class="fas fa-handshake" style="color:var(--crm-success)"></i>
                <h5 class="card-title">Recent Deals</h5>
                <a href="{{ route('admin.deals.index') }}" class="ms-auto" style="font-size:12px;color:var(--crm-primary)">View All</a>
            </div>
            <div style="overflow-x:auto">
                <table class="crm-table">
                    <thead><tr><th>Deal</th><th>Contact</th><th>Stage</th><th>Value</th><th>Date</th></tr></thead>
                    <tbody>
                        @forelse($recentDeals as $d)
                        <tr>
                            <td style="font-weight:600;font-size:13px;max-width:150px">{{ Str::limit($d->title,30) }}</td>
                            <td style="font-size:12px;color:var(--crm-muted)">{{ $d->contact?->full_name ?? '—' }}</td>
                            <td>
                                @php $sb=['prospecting'=>'secondary','qualification'=>'info','proposal'=>'primary','negotiation'=>'warning','closed_won'=>'success','closed_lost'=>'danger'][$d->stage]??'secondary'; @endphp
                                <span class="badge-crm badge-{{ $sb }}" style="font-size:10px">{{ ucwords(str_replace('_',' ',$d->stage)) }}</span>
                            </td>
                            <td style="font-weight:700;color:var(--crm-success);font-size:13px">${{ number_format($d->value) }}</td>
                            <td style="font-size:11px;color:var(--crm-muted)">{{ $d->created_at->format('M d') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" style="text-align:center;color:var(--crm-muted);padding:20px">No deals</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Leads by Source -->
    <div class="col-12">
        <div class="crm-card">
            <div class="crm-card-header">
                <i class="fas fa-broadcast-tower" style="color:var(--crm-info)"></i>
                <h5 class="card-title">Leads by Source</h5>
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
                    <div class="col-md-6">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span style="font-size:13px;min-width:80px;font-weight:500">{{ ucfirst($src->source) }}</span>
                            <div style="flex:1;height:8px;background:var(--crm-border);border-radius:4px">
                                <div style="width:{{ $pct }}%;height:100%;background:{{ $color }};border-radius:4px;transition:width .6s ease"></div>
                            </div>
                            <span style="font-size:13px;font-weight:700;color:{{ $color }};min-width:24px;text-align:right">{{ $src->count }}</span>
                        </div>
                    </div>
                    @empty
                    <div class="col-12" style="text-align:center;color:var(--crm-muted);padding:20px">No source data</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
Chart.defaults.color = '#64748b';
Chart.defaults.borderColor = '#252a3a';
Chart.defaults.font.family = "'DM Sans', sans-serif";

const chartOpts = {
    plugins: { legend: { display: false }, tooltip: { backgroundColor: '#1c2030', borderColor: '#252a3a', borderWidth: 1, titleColor: '#e2e8f0', bodyColor: '#94a3b8', padding: 10 } },
    scales: {
        x: { grid: { color: 'rgba(255,255,255,.04)' }, ticks: { font: { size: 11 } } },
        y: { grid: { color: 'rgba(255,255,255,.04)' }, ticks: { font: { size: 11 } } }
    }
};

// Revenue Chart
const revData = @json($monthlyRevenue);
new Chart(document.getElementById('revenueChart'), {
    type: 'bar',
    data: {
        labels: revData.map(d => d.label),
        datasets: [{
            label: 'Revenue',
            data: revData.map(d => d.revenue),
            backgroundColor: 'rgba(59,130,246,.3)',
            borderColor: '#3b82f6',
            borderWidth: 2,
            borderRadius: 6,
        }]
    },
    options: { ...chartOpts, plugins: { ...chartOpts.plugins, legend: { display: false } } }
});

// Leads Pie Chart
const leadsData = @json($leadsByStatus);
new Chart(document.getElementById('leadsChart'), {
    type: 'doughnut',
    data: {
        labels: ['New','Contacted','Negotiating','Closed','Lost'],
        datasets: [{
            data: [
                leadsData.new?.count ?? 0,
                leadsData.contacted?.count ?? 0,
                leadsData.negotiating?.count ?? 0,
                leadsData.closed?.count ?? 0,
                leadsData.lost?.count ?? 0,
            ],
            backgroundColor: ['#f59e0b','#06b6d4','#8b5cf6','#10b981','#ef4444'],
            borderWidth: 0,
        }]
    },
    options: { cutout: '65%', plugins: { legend: { display: false } }, maintainAspectRatio: true }
});

// Deals by Stage Bar Chart
const dealsData = @json($dealsByStage);
const stageLabels = { prospecting:'Prospecting', qualification:'Qualify', proposal:'Proposal', negotiation:'Negotiate', closed_won:'Won', closed_lost:'Lost' };
const stageColors = { prospecting:'#64748b', qualification:'#06b6d4', proposal:'#3b82f6', negotiation:'#f59e0b', closed_won:'#10b981', closed_lost:'#ef4444' };
new Chart(document.getElementById('dealsChart'), {
    type: 'bar',
    data: {
        labels: dealsData.map(d => stageLabels[d.stage] ?? d.stage),
        datasets: [{
            label: 'Deals',
            data: dealsData.map(d => d.count),
            backgroundColor: dealsData.map(d => (stageColors[d.stage] ?? '#64748b') + '44'),
            borderColor:     dealsData.map(d => stageColors[d.stage] ?? '#64748b'),
            borderWidth: 2, borderRadius: 6,
        }]
    },
    options: { ...chartOpts, indexAxis: 'y' }
});

// Contacts per Month
const contactsData = @json($monthlyContacts);
new Chart(document.getElementById('contactsChart'), {
    type: 'line',
    data: {
        labels: contactsData.map(d => d.label),
        datasets: [{
            label: 'Contacts',
            data: contactsData.map(d => d.count),
            borderColor: '#10b981',
            backgroundColor: 'rgba(16,185,129,.1)',
            borderWidth: 2,
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#10b981',
            pointRadius: 4,
        }]
    },
    options: chartOpts
});
</script>
@endpush
