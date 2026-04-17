<!-- @extends('layouts.app')
@section('title', 'Customer Details')
@section('page_title', $contact->full_name)
@section('breadcrumb', 'Customers / Details')

@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h1 class="page-title"><i class="fas fa-user-circle me-2" style="color:var(--crm-primary)"></i>{{ $contact->full_name }}</h1>
        <p class="page-subtitle">Customer overview and engagement details</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.contacts.index') }}" class="btn-crm-secondary">
            <i class="fas fa-arrow-left"></i> Back to customers
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="crm-card">
            <div class="crm-card-header">
                <i class="fas fa-id-card" style="color:var(--crm-primary)"></i>
                <h5 class="card-title">Customer profile</h5>
            </div>
            <div class="crm-card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <strong>Name</strong>
                        <div>{{ $contact->full_name }}</div>
                    </div>
                    <div class="col-md-6">
                        <strong>Status</strong>
                        <div>{{ ucfirst($contact->status) }}</div>
                    </div>
                    <div class="col-md-6">
                        <strong>Email</strong>
                        <div><a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a></div>
                    </div>
                    <div class="col-md-6">
                        <strong>Phone</strong>
                        <div>{{ formatPhilippinePhone($contact->phone) }}</div>
                    </div>
                    <div class="col-md-6">
                        <strong>Company</strong>
                        <div>{{ $contact->company ?? '—' }}</div>
                    </div>
                    <div class="col-md-6">
                        <strong>Job title</strong>
                        <div>{{ $contact->job_title ?? '—' }}</div>
                    </div>
                    <div class="col-md-6">
                        <strong>Location</strong>
                        <div>{{ collect([$contact->city, $contact->country])->filter()->join(', ') ?: '—' }}</div>
                    </div>
                    <div class="col-md-6">
                        <strong>Added</strong>
                        <div>{{ $contact->created_at->format('M d, Y') }}</div>
                    </div>
                    <div class="col-12">
                        <strong>Notes</strong>
                        <div style="min-height:140px; padding:16px; border-radius:16px; background:#f8fbff; color:#334155; white-space:pre-wrap;">{{ $contact->notes ?: 'No notes added yet.' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="crm-card mb-4">
            <div class="crm-card-header">
                <i class="fas fa-chart-simple" style="color:var(--crm-success)"></i>
                <h5 class="card-title">Engagement summary</h5>
            </div>
            <div class="crm-card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <div class="text-muted" style="font-size:12px">Leads</div>
                        <div style="font-size:24px;font-weight:700">{{ $contact->leads->count() }}</div>
                    </div>
                    <div>
                        <span class="badge-crm badge-info">Active</span>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <div class="text-muted" style="font-size:12px">Deals</div>
                        <div style="font-size:24px;font-weight:700">{{ $contact->deals->count() }}</div>
                    </div>
                    <div>
                        <span class="badge-crm badge-success">{{ $contact->deals->sum('value') ? '$'.number_format($contact->deals->sum('value')) : '—' }}</span>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted" style="font-size:12px">Tasks</div>
                        <div style="font-size:24px;font-weight:700">{{ $contact->tasks->count() }}</div>
                    </div>
                    <div>
                        <span class="badge-crm badge-warning">Open</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="crm-card">
            <div class="crm-card-header">
                <i class="fas fa-info-circle" style="color:var(--crm-warning)"></i>
                <h5 class="card-title">Quick actions</h5>
            </div>
            <div class="crm-card-body">
                <a href="{{ route('admin.contacts.index') }}" class="btn-crm-secondary w-100">
                    <i class="fas fa-list"></i> Return to list
                </a>
            </div>
        </div>
    </div>
</div>
@endsection -->
