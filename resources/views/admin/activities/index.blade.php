@extends('layouts.app')
@section('title', 'Activities - Tread CRM')
@section('page_title', 'Activities')
@section('page_subtitle', 'Track all interactions and communications with customers and leads.')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">

<style>
/* ─── Corporate Font System ─── */
:root {
    --font-sans: 'DM Sans', ui-sans-serif, system-ui, sans-serif;
    --font-mono: 'DM Mono', ui-monospace, monospace;

    /* Palette */
    --act-blue:    #1e6fff;
    --act-blue-lt: #e8f0fe;
    --act-border:  #e2e8f0;
    --act-surface: #f8fafc;
    --act-text:    #1a202c;
    --act-muted:   #64748b;
    --act-radius:  10px;

    /* Timeline */
    --tl-line: #dde3ed;
    --tl-dot:  16px;
    --tl-gap:  28px;
}

body,
.crm-card,
.crm-input,
.crm-label,
.modal-content,
.btn-crm-primary,
.btn,
h1, h2, h3, h4, h5, h6,
p, span, small, label, select, input, textarea {
    font-family: var(--font-sans) !important;
}

code, pre, .font-mono {
    font-family: var(--font-mono) !important;
}

/* ─── Page Header ─── */
.page-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 24px;
}

.page-title {
    font-size: clamp(1.25rem, 2.5vw, 1.6rem);
    font-weight: 700;
    letter-spacing: -0.02em;
    color: var(--act-text);
    margin: 0;
}

.page-subtitle {
    font-size: 0.875rem;
    color: var(--act-muted);
    margin: 4px 0 0;
    font-weight: 400;
}

.page-actions {
    flex-shrink: 0;
}

/* ─── Stats Grid ─── */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 14px;
    margin-bottom: 24px;
}

@media (max-width: 1024px) {
    .stats-grid { grid-template-columns: repeat(2, 1fr); }
}

@media (max-width: 480px) {
    .stats-grid { grid-template-columns: repeat(2, 1fr); gap: 10px; }
}

.stat-card {
    background: #fff;
    border: 1px solid var(--act-border);
    border-radius: var(--act-radius);
    padding: 16px;
    display: flex;
    align-items: center;
    gap: 14px;
    transition: box-shadow 0.18s, border-color 0.18s;
}

.stat-card:hover {
    border-color: var(--act-blue);
    box-shadow: 0 0 0 3px var(--act-blue-lt);
}

.stat-icon {
    width: 44px;
    height: 44px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    color: #fff;
    flex-shrink: 0;
}

.stat-icon.bg-primary  { background: #1e6fff; }
.stat-icon.bg-info     { background: #0ea5e9; }
.stat-icon.bg-success  { background: #22c55e; }
.stat-icon.bg-warning  { background: #f59e0b; }

.stat-label  { font-size: 0.72rem; color: var(--act-muted); font-weight: 500; text-transform: uppercase; letter-spacing: 0.04em; }
.stat-value  { font-size: 1.35rem; font-weight: 700; color: var(--act-text); line-height: 1.1; }

/* ─── Filter Card ─── */
.filter-card {
    background: #fff;
    border: 1px solid var(--act-border);
    border-radius: var(--act-radius);
    padding: 20px;
    margin-bottom: 20px;
}

.filter-grid {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1fr 2fr auto;
    gap: 12px;
    align-items: end;
}

@media (max-width: 1100px) {
    .filter-grid { grid-template-columns: repeat(3, 1fr); }
    .filter-apply { grid-column: 1 / -1; }
}

@media (max-width: 640px) {
    .filter-grid { grid-template-columns: 1fr 1fr; }
    .filter-apply { grid-column: 1 / -1; }
    .filter-date  { grid-column: 1 / -1; }
    .filter-search{ grid-column: 1 / -1; }
}

.crm-label {
    display: block;
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--act-muted);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 6px;
}

.crm-input {
    width: 100%;
    padding: 9px 12px;
    border: 1px solid var(--act-border);
    border-radius: 7px;
    font-size: 0.875rem;
    color: var(--act-text);
    background: #fff;
    transition: border-color 0.15s, box-shadow 0.15s;
    appearance: none;
    -webkit-appearance: none;
    line-height: 1.5;
}

.crm-input:focus {
    outline: none;
    border-color: var(--act-blue);
    box-shadow: 0 0 0 3px var(--act-blue-lt);
}

.date-range {
    display: flex;
    gap: 8px;
}

.date-range .crm-input {
    min-width: 0;
}

/* ─── Primary Button ─── */
.btn-crm-primary {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    background: var(--act-blue);
    color: #fff;
    border: none;
    border-radius: 8px;
    padding: 10px 18px;
    font-size: 0.875rem;
    font-weight: 600;
    font-family: var(--font-sans) !important;
    cursor: pointer;
    white-space: nowrap;
    transition: background 0.15s, transform 0.1s;
}

.btn-crm-primary:hover {
    background: #1557d6;
    transform: translateY(-1px);
}

.btn-apply {
    width: 100%;
    justify-content: center;
}

/* ─── Activity Timeline Card ─── */
.timeline-card {
    background: #fff;
    border: 1px solid var(--act-border);
    border-radius: var(--act-radius);
    overflow: hidden;
}

.timeline-card-header {
    padding: 18px 22px;
    border-bottom: 1px solid var(--act-border);
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 8px;
}

.timeline-card-title {
    font-size: 1rem;
    font-weight: 700;
    color: var(--act-text);
    margin: 0;
}

.timeline-card-sub {
    font-size: 0.8rem;
    color: var(--act-muted);
    margin: 2px 0 0;
}

.timeline-card-body {
    padding: 24px 22px;
}

/* ─── Timeline ─── */
.timeline {
    position: relative;
    padding-left: var(--tl-gap);
}

.timeline::before {
    content: '';
    position: absolute;
    left: calc(var(--tl-dot) / 2 - 1px);
    top: 0;
    bottom: 0;
    width: 2px;
    background: var(--tl-line);
    border-radius: 2px;
}

.timeline-item {
    position: relative;
    margin-bottom: 24px;
}

.timeline-item:last-child {
    margin-bottom: 0;
}

.timeline-marker {
    position: absolute;
    left: calc(-1 * var(--tl-gap) + (var(--tl-gap) - var(--tl-dot)) / 2);
    top: 8px;
    width: var(--tl-dot);
    height: var(--tl-dot);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 7px;
    box-shadow: 0 0 0 3px #fff, 0 0 0 4px var(--tl-line);
    z-index: 1;
}

.timeline-content {
    background: var(--act-surface);
    border: 1px solid var(--act-border);
    border-radius: 9px;
    padding: 14px 16px;
    transition: border-color 0.15s, box-shadow 0.15s;
}

.timeline-content:hover {
    border-color: #c7d4e8;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
}

/* ─── Timeline Content Inner Layout ─── */
.tl-top {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 10px;
    flex-wrap: wrap;
    margin-bottom: 8px;
}

.tl-type {
    font-size: 0.875rem;
    font-weight: 700;
    color: var(--act-text);
    margin: 0 0 2px;
}

.tl-meta {
    font-size: 0.75rem;
    color: var(--act-muted);
}

.tl-right {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-shrink: 0;
}

.tl-relation {
    font-size: 0.75rem;
    font-weight: 500;
    text-align: right;
}

.tl-description {
    font-size: 0.855rem;
    color: #374151;
    line-height: 1.55;
    margin: 0;
}

/* ─── Dropdown ─── */
.dropdown-toggle.p-0 {
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
}

.dropdown-menu {
    font-family: var(--font-sans) !important;
    font-size: 0.85rem;
    border: 1px solid var(--act-border);
    box-shadow: 0 8px 24px rgba(0,0,0,0.1);
    border-radius: 9px;
    padding: 6px;
}

.dropdown-item {
    border-radius: 6px;
    padding: 7px 12px;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 8px;
}

/* ─── Empty State ─── */
.empty-state {
    text-align: center;
    padding: 48px 20px;
}

.empty-state i {
    display: block;
    margin-bottom: 16px;
    opacity: 0.35;
}

.empty-state h5 {
    font-size: 1rem;
    font-weight: 700;
    color: var(--act-text);
    margin-bottom: 8px;
}

.empty-state p {
    font-size: 0.875rem;
    color: var(--act-muted);
    max-width: 340px;
    margin: 0 auto 20px;
}

/* ─── Pagination ─── */
.crm-pagination-bar {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
    padding: 16px 22px;
    border-top: 1px solid var(--act-border);
    flex-wrap: wrap;
}
.crm-pg-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 34px;
    height: 34px;
    padding: 0 10px;
    border-radius: 7px;
    font-size: 0.82rem;
    font-weight: 600;
    font-family: var(--font-sans);
    line-height: 1;
    text-decoration: none;
    border: 1px solid var(--act-border);
    background: #fff;
    color: var(--act-text);
    cursor: pointer;
    transition: background 0.13s, border-color 0.13s, color 0.13s;
    white-space: nowrap;
    user-select: none;
}
.crm-pg-btn:hover {
    background: var(--act-blue-lt);
    border-color: var(--act-blue);
    color: var(--act-blue);
    text-decoration: none;
}
.crm-pg-btn.active {
    background: var(--act-blue);
    border-color: var(--act-blue);
    color: #fff;
    cursor: default;
    pointer-events: none;
}
.crm-pg-btn.disabled {
    opacity: 0.35;
    cursor: default;
    pointer-events: none;
}
.crm-pg-dots {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 28px;
    height: 34px;
    font-size: 0.85rem;
    color: var(--act-muted);
    user-select: none;
}

/* ─── Modal ─── */
.modal-content {
    border-radius: 12px;
    border: none;
    box-shadow: 0 20px 60px rgba(0,0,0,0.18);
}

.modal-header {
    padding: 18px 22px;
    border-bottom: 1px solid var(--act-border);
}

.modal-title {
    font-size: 1rem;
    font-weight: 700;
    color: var(--act-text);
}

.modal-body {
    padding: 22px;
}

.modal-footer {
    padding: 14px 22px;
    border-top: 1px solid var(--act-border);
    gap: 10px;
}

/* ─── Responsive tweaks ─── */
@media (max-width: 640px) {
    .timeline-card-body { padding: 16px 14px; }
    .timeline { padding-left: 22px; }
    .tl-top { flex-direction: column; gap: 8px; }
    .tl-right { width: 100%; justify-content: space-between; }
    .modal-dialog { margin: 10px; }
    .filter-card { padding: 14px; }
}
</style>
@endpush

@section('content')

<!-- ─── Page Header ─── -->
<div class="page-header">
    <div class="page-header-left">
        <h1 class="page-title">
            <i class="fas fa-history me-2 text-primary"></i>Activities
        </h1>
        <p class="page-subtitle">Track all interactions and communications with customers and leads.</p>
    </div>
    <div class="page-actions">
        <button type="button" class="btn-crm-primary" onclick="createActivity()">
            <i class="fas fa-plus"></i> Log Activity
        </button>
    </div>
</div>

<!-- ─── Stats Cards ─── -->
<div class="stats-grid mb-4">
    <div class="stat-card">
        <div class="stat-icon bg-primary">
            <i class="fas fa-chart-bar"></i>
        </div>
        <div>
            <div class="stat-label">Total</div>
            <div class="stat-value">{{ $stats['total'] }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon bg-info">
            <i class="fas fa-phone"></i>
        </div>
        <div>
            <div class="stat-label">Calls</div>
            <div class="stat-value">{{ $stats['calls'] }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon bg-success">
            <i class="fas fa-handshake"></i>
        </div>
        <div>
            <div class="stat-label">Meetings</div>
            <div class="stat-value">{{ $stats['meetings'] }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon bg-warning">
            <i class="fas fa-envelope"></i>
        </div>
        <div>
            <div class="stat-label">Emails</div>
            <div class="stat-value">{{ $stats['emails'] }}</div>
        </div>
    </div>
</div>

<!-- ─── Filters ─── -->
<div class="filter-card mb-4">
    <form method="GET">
        <div class="filter-grid">
            <!-- Search -->
            <div class="filter-search">
                <label class="crm-label">Search</label>
                <input type="text" class="crm-input" name="search"
                       value="{{ request('search') }}" placeholder="Search activities…">
            </div>

            <!-- Type -->
            <div>
                <label class="crm-label">Type</label>
                <select class="crm-input" name="activity_type">
                    <option value="">All Types</option>
                    <option value="call"      {{ request('activity_type') === 'call'      ? 'selected' : '' }}>Call</option>
                    <option value="email"     {{ request('activity_type') === 'email'     ? 'selected' : '' }}>Email</option>
                    <option value="meeting"   {{ request('activity_type') === 'meeting'   ? 'selected' : '' }}>Meeting</option>
                    <option value="note"      {{ request('activity_type') === 'note'      ? 'selected' : '' }}>Note</option>
                    <option value="task"      {{ request('activity_type') === 'task'      ? 'selected' : '' }}>Task</option>
                    <option value="follow_up" {{ request('activity_type') === 'follow_up' ? 'selected' : '' }}>Follow-up</option>
                    <option value="other"     {{ request('activity_type') === 'other'     ? 'selected' : '' }}>Other</option>
                </select>
            </div>

            <!-- Customer -->
            <div>
                <label class="crm-label">Customer</label>
                <select class="crm-input" name="customer_id">
                    <option value="">All Customers</option>
                    @foreach($customers as $customer)
                    <option value="{{ $customer->id }}"
                        {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                        {{ $customer->full_name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Lead -->
            <div>
                <label class="crm-label">Lead</label>
                <select class="crm-input" name="lead_id">
                    <option value="">All Leads</option>
                    @foreach($leads as $lead)
                    <option value="{{ $lead->id }}"
                        {{ request('lead_id') == $lead->id ? 'selected' : '' }}>
                        {{ $lead->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Date Range -->
            <div class="filter-date">
                <label class="crm-label">Date Range</label>
                <div class="date-range">
                    <input type="date" class="crm-input" name="date_from"
                           value="{{ request('date_from') }}" placeholder="From">
                    <input type="date" class="crm-input" name="date_to"
                           value="{{ request('date_to') }}" placeholder="To">
                </div>
            </div>

            <!-- Apply Button -->
            <div class="filter-apply">
                <button type="submit" class="btn-crm-primary btn-apply">
                    <i class="fas fa-filter"></i> Apply
                </button>
            </div>
        </div>
    </form>
</div>

<!-- ─── Activity Timeline ─── -->
<div class="timeline-card">
    <div class="timeline-card-header">
        <div>
            <h5 class="timeline-card-title">Activity Timeline</h5>
            <p class="timeline-card-sub">Chronological view of all customer and lead interactions.</p>
        </div>
        @if($activities->total() > 0)
        <span class="badge bg-light text-secondary fw-semibold"
              style="font-family:var(--font-sans);font-size:.75rem;padding:6px 10px;border-radius:6px;border:1px solid var(--act-border);">
            {{ $activities->total() }} {{ Str::plural('record', $activities->total()) }}
        </span>
        @endif
    </div>

    <div class="timeline-card-body">
        <div class="timeline">
            @forelse($activities as $activity)
            <div class="timeline-item">
                <div class="timeline-marker bg-{{ $activity->color }}">
                    <i class="{{ $activity->icon }}"></i>
                </div>

                <div class="timeline-content">
                    <div class="tl-top">
                        <div>
                            <p class="tl-type">{{ $activity->activity_type_label }}</p>
                            <span class="tl-meta">
                                {{ $activity->activity_date
                                    ? $activity->activity_date->format('M d, Y \a\t h:i A')
                                    : 'No date' }}
                                &middot; by {{ $activity->createdBy?->name ?? 'Unknown' }}
                            </span>
                        </div>

                        <div class="tl-right">
                            @if($activity->customer || $activity->lead)
                            <div class="tl-relation">
                                @if($activity->customer)
                                <span class="text-primary">
                                    <i class="fas fa-user me-1"></i>{{ $activity->customer->full_name }}
                                </span>
                                @elseif($activity->lead)
                                <span class="text-warning">
                                    <i class="fas fa-bullseye me-1"></i>{{ $activity->lead->name }}
                                </span>
                                @endif
                            </div>
                            @endif

                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle p-0"
                                        type="button"
                                        data-bs-toggle="dropdown"
                                        aria-expanded="false"
                                        style="min-width:32px">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="#"
                                           onclick="editActivity({{ $activity->id }}); return false">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                    </li>
                                    @if(auth()->user()->isAdminOrManager() || $activity->user_id === auth()->id())
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item text-danger" href="#"
                                           onclick="deleteActivity({{ $activity->id }}); return false">
                                            <i class="fas fa-trash"></i> Delete
                                        </a>
                                    </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>

                    <p class="tl-description">{{ $activity->description }}</p>
                </div>
            </div>
            @empty
            <div class="empty-state">
                <i class="fas fa-history fa-3x text-muted"></i>
                <h5>No activities logged</h5>
                <p>Start tracking your customer interactions by logging your first activity.</p>
                <button type="button" class="btn-crm-primary" onclick="createActivity()">
                    <i class="fas fa-plus"></i> Log First Activity
                </button>
            </div>
            @endforelse
        </div>
    </div>

    {{-- ─── Pagination ─── --}}
    @if($activities->hasPages())
    @php
        $currentPage = $activities->currentPage();
        $lastPage    = $activities->lastPage();
        $window = collect(range(1, $lastPage))->filter(function($p) use ($currentPage, $lastPage) {
            return $p === 1 || $p === $lastPage || abs($p - $currentPage) <= 1;
        })->values();
    @endphp
    <div class="crm-pagination-bar">

        {{-- Prev --}}
        @if($activities->onFirstPage())
            <span class="crm-pg-btn disabled">&#8249;</span>
        @else
            <a class="crm-pg-btn" href="{{ $activities->previousPageUrl() }}">&#8249;</a>
        @endif

        {{-- Page numbers with gap dots --}}
        @php $prev = null; @endphp
        @foreach($window as $page)
            @if($prev !== null && $page - $prev > 1)
                <span class="crm-pg-dots">&hellip;</span>
            @endif
            @if($page === $currentPage)
                <span class="crm-pg-btn active">{{ $page }}</span>
            @else
                <a class="crm-pg-btn" href="{{ $activities->url($page) }}">{{ $page }}</a>
            @endif
            @php $prev = $page; @endphp
        @endforeach

        {{-- Next --}}
        @if($currentPage >= $lastPage)
            <span class="crm-pg-btn disabled">&#8250;</span>
        @else
            <a class="crm-pg-btn" href="{{ $activities->nextPageUrl() }}">&#8250;</a>
        @endif

    </div>
    @endif
</div>

<!-- ─── Activity Modal ─── -->
<div class="modal fade" id="activityModal" tabindex="-1" aria-labelledby="activityModalTitle" aria-modal="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="activityModalTitle">Log Activity</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="activityForm">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="crm-label">Activity Type *</label>
                            <select class="crm-input" name="activity_type" required>
                                <option value="note">Note</option>
                                <option value="call">Phone Call</option>
                                <option value="email">Email</option>
                                <option value="meeting">Meeting</option>
                                <option value="task">Task</option>
                                <option value="follow_up">Follow-up</option>
                                <option value="other">Other</option>
                            </select>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="crm-label">Date &amp; Time *</label>
                            <input type="datetime-local" class="crm-input" name="date" required>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="crm-label">Related Customer</label>
                            <select class="crm-input" name="customer_id" id="activityCustomerSelect">
                                <option value="">Select Customer (Optional)</option>
                                @foreach($customers as $customer)
                                <option value="{{ $customer->id }}">
                                    {{ $customer->full_name }} ({{ $customer->email }})
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="crm-label">Related Lead</label>
                            <select class="crm-input" name="lead_id" id="activityLeadSelect">
                                <option value="">Select Lead (Optional)</option>
                                @foreach($leads as $lead)
                                <option value="{{ $lead->id }}">
                                    {{ $lead->name }} ({{ $lead->email }})
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="crm-label">Description *</label>
                            <textarea class="crm-input" name="description" rows="4" required
                                      placeholder="Describe the activity in detail…"></textarea>
                        </div>

                        <div class="col-12" id="metadataSection" style="display:none">
                            <label class="crm-label">Additional Details (JSON)</label>
                            <textarea class="crm-input" name="metadata" rows="3"
                                      placeholder='{"duration": "30min", "outcome": "successful"}'
                                      style="font-family:var(--font-mono);font-size:.82rem"></textarea>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-crm-primary">
                        <i class="fas fa-save me-1"></i> Log Activity
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const activityModal = new bootstrap.Modal(document.getElementById('activityModal'));
    const activityForm  = document.getElementById('activityForm');

    let isEditing        = false;
    let editingActivityId = null;

    /* ── Toast helper ── */
    function showToast(message, type = 'success') {
        const wrap = document.createElement('div');
        wrap.style.cssText = 'position:fixed;top:20px;right:20px;z-index:9999;';
        wrap.innerHTML = `
            <div class="toast align-items-center text-white bg-${type === 'success' ? 'success' : 'danger'} border-0" role="alert">
                <div class="d-flex">
                    <div class="toast-body">${message}</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>`;
        document.body.appendChild(wrap);
        const toast = new bootstrap.Toast(wrap.querySelector('.toast'), { delay: 4000 });
        toast.show();
        setTimeout(() => wrap.remove(), 4500);
    }

    /* ── Create ── */
    function createActivity() {
        isEditing        = false;
        editingActivityId = null;
        document.getElementById('activityModalTitle').textContent = 'Log Activity';
        activityForm.reset();

        const now = new Date();
        activityForm.querySelector('[name="date"]').value = now.toISOString().slice(0, 16);
        document.getElementById('metadataSection').style.display = 'none';
        activityModal.show();
    }

    /* ── Edit ── */
    function editActivity(activityId) {
        isEditing        = true;
        editingActivityId = activityId;
        document.getElementById('activityModalTitle').textContent = 'Edit Activity';

        fetch(`/admin/activities/${activityId}`)
            .then(r => r.json())
            .then(activity => {
                activityForm.querySelector('[name="activity_type"]').value = activity.activity_type || '';
                activityForm.querySelector('[name="description"]').value   = activity.description   || '';
                activityForm.querySelector('[name="date"]').value          = activity.activity_date
                    ? activity.activity_date.slice(0, 16) : '';
                activityForm.querySelector('[name="customer_id"]').value   = activity.customer_id || '';
                activityForm.querySelector('[name="lead_id"]').value       = activity.lead_id     || '';

                if (activity.metadata) {
                    activityForm.querySelector('[name="metadata"]').value =
                        JSON.stringify(activity.metadata, null, 2);
                    document.getElementById('metadataSection').style.display = 'block';
                }
                activityModal.show();
            })
            .catch(() => showToast('Failed to load activity', 'danger'));
    }

    /* ── Expose globals ── */
    window.createActivity = createActivity;
    window.editActivity   = editActivity;

    window.deleteActivity = function (activityId) {
        if (!confirm('Are you sure you want to delete this activity?')) return;
        fetch(`/admin/activities/${activityId}`, {
            method:  'DELETE',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
        })
        .then(r => r.json())
        .then(data => data.success
            ? (showToast('Activity deleted.'), setTimeout(() => location.reload(), 1200))
            : showToast('Delete failed', 'danger'))
        .catch(() => showToast('Network error', 'danger'));
    };

    /* ── Form submit ── */
    activityForm.addEventListener('submit', function (e) {
        e.preventDefault();

        const data = {
            activity_type: activityForm.querySelector('[name="activity_type"]').value,
            description:   activityForm.querySelector('[name="description"]').value.trim(),
            date:          activityForm.querySelector('[name="date"]').value,
            customer_id:   activityForm.querySelector('[name="customer_id"]').value || null,
            lead_id:       activityForm.querySelector('[name="lead_id"]').value     || null,
        };

        const metadataVal = activityForm.querySelector('[name="metadata"]').value.trim();
        if (metadataVal) {
            try {
                data.metadata = JSON.parse(metadataVal);
            } catch {
                showToast('Metadata must be valid JSON', 'danger');
                return;
            }
        }

        const url    = isEditing ? `/admin/activities/${editingActivityId}` : '/admin/activities';
        const method = isEditing ? 'PATCH' : 'POST';

        fetch(url, {
            method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(data)
        })
        .then(response => {
            if (!response.ok) {
                return response.text().then(text => {
                    throw new Error(`Server responded with ${response.status}: ${text.substring(0, 300)}`);
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                activityModal.hide();
                showToast(data.message || 'Activity saved successfully!', 'success');
                setTimeout(() => location.reload(), 1200);
            } else {
                showToast('Validation failed. Please check your input.', 'danger');
            }
        })
        .catch(err => showToast(err.message || 'Failed to save activity', 'danger'));
    });

});
</script>
@endpush