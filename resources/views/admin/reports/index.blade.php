@extends('layouts.app')

@section('title', 'Pipeline - Sales Analytics')

@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h1 class="page-title">Pipeline</h1>
        <p class="page-subtitle">Reports</p>
    </div>
    <div class="page-actions">
        <select class="crm-input" style="max-width: 150px;" onchange="window.location.href='?period='+this.value">
            <option value="7" {{ $period == 7 ? 'selected' : '' }}>Last 7 days</option>
            <option value="30" {{ $period == 30 ? 'selected' : '' }}>Last 30 days</option>
            <option value="90" {{ $period == 90 ? 'selected' : '' }}>Last 90 days</option>
            <option value="365" {{ $period == 365 ? 'selected' : '' }}>Last year</option>
        </select>
    </div>
</div>

<!-- Summary Stats -->
<div class="row g-3 mb-4">
    <div class="col-md-6 col-lg-3">
        <div class="stat-card">
            <div style="display:flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <div class="stat-value">{{ $summary['total_customers'] }}</div>
                    <div class="stat-label">Total Customers</div>
                </div>
                <div class="stat-icon" style="background: rgba(59, 130, 246, 0.12); color: #3b82f6;">
                    <i class="fas fa-users"></i>
                </div>
            </div>
            @if($summary['new_customers'] > 0)
                <div style="font-size: 0.8rem; color: #10b981; margin-top: 8px;">+{{ $summary['new_customers'] }} new</div>
            @endif
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="stat-card">
            <div style="display:flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <div class="stat-value">{{ $summary['total_leads'] }}</div>
                    <div class="stat-label">Total Leads</div>
                </div>
                <div class="stat-icon" style="background: rgba(168, 85, 247, 0.12); color: #a855f7;">
                    <i class="fas fa-bullseye"></i>
                </div>
            </div>
            @if($summary['new_leads'] > 0)
                <div style="font-size: 0.8rem; color: #10b981; margin-top: 8px;">+{{ $summary['new_leads'] }} new</div>
            @endif
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="stat-card">
            <div style="display:flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <div class="stat-value">₱{{ number_format($summary['total_revenue'], 0) }}</div>
                    <div class="stat-label">Pipeline Value</div>
                </div>
                <div class="stat-icon" style="background: rgba(34, 197, 94, 0.12); color: #22c55e;">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="stat-card">
            <div style="display:flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <div class="stat-value">{{ $summary['conversion_rate'] }}%</div>
                    <div class="stat-label">Conversion Rate</div>
                </div>
                <div class="stat-icon" style="background: rgba(249, 115, 22, 0.12); color: #f97316;">
                    <i class="fas fa-percentage"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Lead Status Distribution -->
<div class="row g-4 mb-4">
    <div class="col-lg-4">
        <div class="crm-card">
            <div class="crm-card-header">
                <h5 style="margin: 0;">Leads by Status</h5>
            </div>
            <div class="crm-card-body">
                @forelse($leadsByStatus as $status => $item)
                    <div style="margin-bottom: 12px;">
                        <div style="display:flex; justify-content: space-between; margin-bottom: 4px;">
                            <span style="font-size: 0.9rem; color: #475569;">{{ ucfirst($status) }}</span>
                            <span style="font-weight: 600; color: #1f2937;">{{ $item->count }}</span>
                        </div>
                        <div style="width: 100%; height: 6px; background: #eef2ff; border-radius: 3px; overflow: hidden;">
                            <div style="width: {{ ($item->count / $summary['total_leads'] * 100) ?? 0 }}%; height: 100%; background: #5b8def;"></div>
                        </div>
                    </div>
                @empty
                    <p style="color: #94a3b8; text-align: center; padding: 20px 0;">No leads data</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="crm-card">
            <div class="crm-card-header">
                <h5 style="margin: 0;">Leads by Source</h5>
            </div>
            <div class="crm-card-body">
                @forelse($leadsBySource as $source => $item)
                    <div style="margin-bottom: 12px;">
                        <div style="display:flex; justify-content: space-between; margin-bottom: 4px;">
                            <span style="font-size: 0.9rem; color: #475569;">{{ ucfirst($source) }}</span>
                            <span style="font-weight: 600; color: #1f2937;">{{ $item->count }}</span>
                        </div>
                        <div style="width: 100%; height: 6px; background: #fef3c7; border-radius: 3px; overflow: hidden;">
                            <div style="width: {{ ($item->count / $summary['total_leads'] * 100) ?? 0 }}%; height: 100%; background: #f97316;"></div>
                        </div>
                    </div>
                @empty
                    <p style="color: #94a3b8; text-align: center; padding: 20px 0;">No source data</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="crm-card">
            <div class="crm-card-header">
                <h5 style="margin: 0;">Leads by Priority</h5>
            </div>
            <div class="crm-card-body">
                @forelse($leadsByPriority as $priority => $item)
                    <div style="margin-bottom: 12px;">
                        <div style="display:flex; justify-content: space-between; margin-bottom: 4px;">
                            <span style="font-size: 0.9rem; color: #475569;">{{ ucfirst($priority) }}</span>
                            <span style="font-weight: 600; color: #1f2937;">{{ $item->count }}</span>
                        </div>
                        <div style="width: 100%; height: 6px; background: #dcfce7; border-radius: 3px; overflow: hidden;">
                            <div style="width: {{ ($item->count / $summary['total_leads'] * 100) ?? 0 }}%; height: 100%; background: #10b981;"></div>
                        </div>
                    </div>
                @empty
                    <p style="color: #94a3b8; text-align: center; padding: 20px 0;">No priority data</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Additional Metrics -->
<div class="row g-4 mb-4">
    <div class="col-lg-6">
        <div class="crm-card">
            <div class="crm-card-header">
                <h5 style="margin: 0;">Top Customers</h5>
            </div>
            <div class="crm-card-body">
                @forelse($topCustomers as $customer)
                    <div style="padding: 12px; border-bottom: 1px solid #eef2ff; display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <div style="font-weight: 600; color: #1f2937;">{{ $customer->first_name }} {{ $customer->last_name }}</div>
                            <div style="font-size: 0.85rem; color: #64748b;">{{ $customer->email }}</div>
                        </div>
                        <span class="badge badge-crm badge-info">{{ $customer->leads_count }} leads</span>
                    </div>
                @empty
                    <p style="color: #94a3b8; text-align: center; padding: 20px 0;">No customer data</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="crm-card">
            <div class="crm-card-header">
                <h5 style="margin: 0;">Activity Summary</h5>
            </div>
            <div class="crm-card-body">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div style="padding: 16px; background: #f0f9ff; border-radius: 12px;">
                        <div style="font-size: 1.5rem; font-weight: 700; color: #0369a1;">{{ $summary['total_activities'] }}</div>
                        <div style="font-size: 0.85rem; color: #0c4a6e; margin-top: 4px;">Total Activities</div>
                    </div>
                    <div style="padding: 16px; background: #fef3c7; border-radius: 12px;">
                        <div style="font-size: 1.5rem; font-weight: 700; color: #b45309;">{{ $summary['total_followups'] }}</div>
                        <div style="font-size: 0.85rem; color: #92400e; margin-top: 4px;">Total Follow-ups</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Data Tables -->
<div class="row g-4 mb-4">
    <div class="col-lg-6">
        <div class="crm-card">
            <div class="crm-card-header">
                <h5 style="margin: 0;">Recent Leads</h5>
            </div>
            <div class="crm-card-body" style="padding: 0;">
                <table class="table crm-table" style="margin: 0;">
                    <thead style="background: #f8fafc; border-bottom: 1px solid #eef2ff;">
                        <tr>
                            <th style="font-weight: 600; font-size: 0.85rem;">Lead Name</th>
                            <th style="font-weight: 600; font-size: 0.85rem;">Status</th>
                            <th style="font-weight: 600; font-size: 0.85rem;">Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentLeads as $lead)
                            <tr>
                                <td>
                                    <div style="font-weight: 500; color: #1f2937;">{{ $lead->name }}</div>
                                    <div style="font-size: 0.85rem; color: #64748b;">{{ $lead->email ?? $lead->phone }}</div>
                                </td>
                                <td>
                                    <span class="badge badge-crm badge-{{ $loop->index % 2 == 0 ? 'primary' : 'success' }}">
                                        {{ ucfirst($lead->status) }}
                                    </span>
                                </td>
                                <td style="font-weight: 600; color: #1f2937;">
                                    ₱{{ $lead->expected_value ? number_format($lead->expected_value, 0) : '—' }}
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" style="text-align: center; padding: 24px; color: #94a3b8;">No leads yet</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="crm-card">
            <div class="crm-card-header">
                <h5 style="margin: 0;">Recent Activities</h5>
            </div>
            <div class="crm-card-body" style="padding: 0;">
                <table class="table crm-table" style="margin: 0;">
                    <thead style="background: #f8fafc; border-bottom: 1px solid #eef2ff;">
                        <tr>
                            <th style="font-weight: 600; font-size: 0.85rem;">Description</th>
                            <th style="font-weight: 600; font-size: 0.85rem;">Type</th>
                            <th style="font-weight: 600; font-size: 0.85rem;">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentActivities as $activity)
                            <tr>
                                <td>
                                    <div style="font-weight: 500; color: #1f2937;">{{ Str::limit($activity->description, 25) }}</div>
                                </td>
                                <td style="font-size: 0.85rem;">{{ ucfirst($activity->activity_type) }}</td>
                                <td style="font-size: 0.85rem; color: #64748b;">
                                    {{ $activity->activity_date->format('M d') }}
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" style="text-align: center; padding: 24px; color: #94a3b8;">No activities yet</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Follow-ups Table -->
<div class="row g-4">
    <div class="col-12">
        <div class="crm-card">
            <div class="crm-card-header">
                <h5 style="margin: 0;">Recent Follow-ups</h5>
            </div>
            <div class="crm-card-body" style="padding: 0;">
                <table class="table crm-table" style="margin: 0;">
                    <thead style="background: #f8fafc; border-bottom: 1px solid #eef2ff;">
                        <tr>
                            <th style="font-weight: 600; font-size: 0.85rem;">Title</th>
                            <th style="font-weight: 600; font-size: 0.85rem;">Status</th>
                            <th style="font-weight: 600; font-size: 0.85rem;">Due Date</th>
                            <th style="font-weight: 600; font-size: 0.85rem;">Assignee</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentFollowUps as $followUp)
                            <tr>
                                <td>
                                    <div style="font-weight: 500; color: #1f2937;">{{ Str::limit($followUp->title, 40) }}</div>
                                    <div style="font-size: 0.85rem; color: #64748b;">{{ $followUp->description ? Str::limit($followUp->description, 50) : '—' }}</div>
                                </td>
                                <td>
                                    <span class="badge badge-crm badge-{{ $followUp->status == 'completed' ? 'success' : 'warning' }}">
                                        {{ ucfirst($followUp->status) }}
                                    </span>
                                </td>
                                <td style="font-size: 0.85rem; color: #64748b;">
                                    {{ $followUp->due_date->format('M d, Y') }}
                                </td>
                                <td style="font-size: 0.85rem;">{{ $followUp->user?->name ?? '—' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" style="text-align: center; padding: 24px; color: #94a3b8;">No follow-ups yet</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
<div class="row g-3 mb-3">
    <div class="col-md-8">
        <div class="crm-card" style="height:100%">
            <div class="crm-card-header">
                <h6 class="mb-0 fw-semibold"><i class="fas fa-chart-line me-2 text-primary"></i>Monthly Revenue</h6>
                <span class="text-muted small">Last 6 months</span>
            </div>
            <div class="crm-card-body">
                <canvas id="revenueChart" height="100"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="crm-card" style="height:100%">
            <div class="crm-card-header">
                <h6 class="mb-0 fw-semibold"><i class="fas fa-chart-pie me-2 text-warning"></i>Leads by Status</h6>
            </div>
            <div class="crm-card-body">
                <canvas id="leadsChart" height="160"></canvas>
                <div class="mt-4">
                    @foreach(['new'=>'#f59e0b','contacted'=>'#06b6d4','negotiating'=>'#8b5cf6','closed'=>'#10b981','lost'=>'#ef4444'] as $status => $color)
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="d-flex align-items-center gap-2">
                            <span class="d-inline-block" style="width:10px;height:10px;border-radius:50%;background:{{ $color }}"></span>
                            <span class="small text-muted">{{ ucfirst($status) }}</span>
                        </div>
                        <span class="small fw-semibold">{{ $leadsByStatus[$status]->count ?? 0 }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row 2 -->
<div class="row g-3 mb-3">
    <div class="col-md-6">
        <div class="crm-card">
            <div class="crm-card-header">
                <h6 class="mb-0 fw-semibold"><i class="fas fa-bar-chart me-2 text-success"></i>Deals by Stage</h6>
            </div>
            <div class="crm-card-body">
                <canvas id="dealsChart" height="130"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="crm-card">
            <div class="crm-card-header">
                <h6 class="mb-0 fw-semibold"><i class="fas fa-users me-2 text-info"></i>New Customers per Month</h6>
            </div>
            <div class="crm-card-body">
                <canvas id="contactsChart" height="130"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Tables Row -->
<div class="row g-3">
    <!-- Top Customers -->
    <div class="col-md-5">
        <div class="crm-card">
            <div class="crm-card-header">
                <i class="fas fa-star" style="color:var(--crm-warning)"></i>
                <h5 class="card-title">Top Customers by Deals</h5>
            </div>
            <div style="overflow-x:auto">
                <table class="crm-table">
                    <thead><tr><th>#</th><th>Customer</th><th>Deals</th><th>Revenue</th></tr></thead>
                    <tbody>
                        @forelse($topCustomers as $i => $c)
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
                                ₱{{ number_format($c->total_value ?? 0) }}
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
                    <thead><tr><th>Deal</th><th>Customer</th><th>Stage</th><th>Value</th><th>Date</th></tr></thead>
                    <tbody>
                        @forelse($recentDeals as $d)
                        <tr>
                            <td style="font-weight:600;font-size:13px;max-width:150px">{{ Str::limit($d->title,30) }}</td>
                            <td style="font-size:12px;color:var(--crm-muted)">{{ $d->customer?->full_name ?? '—' }}</td>
                            <td>
                                @php $sb=['prospecting'=>'secondary','qualification'=>'info','proposal'=>'primary','negotiation'=>'warning','closed_won'=>'success','closed_lost'=>'danger'][$d->stage]??'secondary'; @endphp
                                <span class="badge-crm badge-{{ $sb }}" style="font-size:10px">{{ ucwords(str_replace('_',' ',$d->stage)) }}</span>
                            </td>
                            <td style="font-weight:700;color:var(--crm-success);font-size:13px">₱{{ number_format($d->value) }}</td>
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
            label: 'Customers',
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
