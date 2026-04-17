@extends('layouts.app')
@section('title', 'Settings - Tread CRM')
@section('page_title', 'Settings')
@section('page_subtitle', 'Configure CRM preferences, branding, and security options.')

@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h1 class="page-title"><i class="fas fa-cog me-2 text-success"></i>Settings</h1>
        <p class="page-subtitle">Configure CRM preferences, branding, and security options.</p>
    </div>
    <div class="page-actions">
        <a href="#" class="btn-crm-primary"><i class="fas fa-paint-roller"></i> Customize</a>
    </div>
</div>

<div class="crm-card mb-4">
    <div class="crm-card-header">
        <div>
            <h5 class="mb-1 fw-semibold">System configuration</h5>
            <p class="mb-0 text-muted">Your settings workspace is ready for general preferences, notifications, and security controls.</p>
        </div>
        <span class="badge-crm badge-info">Configured</span>
    </div>
    <div class="crm-card-body">
        <div class="row g-3">
            <div class="col-md-4">
                <div class="crm-card p-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar-circle bg-primary">G</div>
                        <div>
                            <div class="fw-bold">General</div>
                            <div class="text-muted small">Company settings and branding</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="crm-card p-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar-circle bg-success">N</div>
                        <div>
                            <div class="fw-bold">Notifications</div>
                            <div class="text-muted small">Alerts and email preferences</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="crm-card p-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar-circle bg-warning">S</div>
                        <div>
                            <div class="fw-bold">Security</div>
                            <div class="text-muted small">Access control and audit logs</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="crm-card">
    <div class="crm-card-header">
        <h5 class="mb-0 fw-semibold">Quick settings overview</h5>
    </div>
    <div class="crm-card-body">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="crm-card p-3">
                    <h6 class="mb-2 fw-semibold">Company name</h6>
                    <p class="text-muted mb-0">Tread CRM</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="crm-card p-3">
                    <h6 class="mb-2 fw-semibold">Default time zone</h6>
                    <p class="text-muted mb-0">UTC +8</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="crm-card p-3">
                    <h6 class="mb-2 fw-semibold">Support email</h6>
                    <p class="text-muted mb-0">support@treadcrm.local</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
