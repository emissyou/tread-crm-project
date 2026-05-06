@extends('layouts.app')
@section('title', 'Settings - Tread CRM')
@section('page_title', 'Settings')
@section('page_subtitle', 'Configure CRM preferences, branding, and security options.')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap" rel="stylesheet">

<style>
:root {
    --font-sans:   'DM Sans', ui-sans-serif, system-ui, sans-serif;
    --usr-blue:    #1e6fff;
    --usr-blue-lt: #e8f0fe;
    --usr-border:  #e2e8f0;
    --usr-surface: #f8fafc;
    --usr-text:    #1a202c;
    --usr-muted:   #64748b;
    --usr-radius:  10px;
}

/* Apply DM Sans to everything */
body, h1, h2, h3, h4, h5, h6,
p, span, small, label, a,
input, select, textarea, button,
th, td, li,
.crm-card, .crm-label, .crm-input,
.btn, .btn-crm-primary, .badge, .badge-crm {
    font-family: var(--font-sans) !important;
}

/* Page header */
.page-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 24px;
}
.page-title {
    font-size: clamp(1.2rem, 2.5vw, 1.55rem);
    font-weight: 700;
    letter-spacing: -0.02em;
    color: var(--usr-text);
    margin: 0;
}
.page-subtitle {
    font-size: 0.875rem;
    color: var(--usr-muted);
    margin: 4px 0 0;
    font-weight: 400;
}

/* Primary button */
.btn-crm-primary {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    background: var(--usr-blue) !important;
    color: #fff !important;
    border: none !important;
    border-radius: 8px !important;
    padding: 9px 18px !important;
    font-size: 0.875rem !important;
    font-weight: 600 !important;
    cursor: pointer;
    white-space: nowrap;
    transition: background 0.15s, transform 0.1s;
    text-decoration: none;
}
.btn-crm-primary:hover {
    background: #1557d6 !important;
    transform: translateY(-1px);
    color: #fff !important;
}

/* Cards */
.crm-card {
    border-radius: var(--usr-radius);
    border: 1px solid var(--usr-border);
    background: #fff;
    overflow: hidden;
}
.crm-card-header {
    padding: 18px 22px;
    border-bottom: 1px solid var(--usr-border);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
}
.crm-card-header h5 {
    font-size: 1rem !important;
    font-weight: 700 !important;
    color: var(--usr-text) !important;
    letter-spacing: -0.01em;
    margin: 0 0 2px !important;
}
.crm-card-header p {
    font-size: 0.8rem !important;
    color: var(--usr-muted) !important;
    margin: 0 !important;
}
.crm-card-body { padding: 20px 22px; }

/* Inner setting cards */
.crm-card .crm-card {
    border-radius: 8px;
    transition: border-color 0.15s, box-shadow 0.15s;
}
.crm-card .crm-card:hover {
    border-color: #c7d4e8;
    box-shadow: 0 2px 10px rgba(0,0,0,0.06);
}

/* Avatar circle */
.avatar-circle {
    width: 42px !important;
    height: 42px !important;
    border-radius: 10px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    font-size: 1rem !important;
    font-weight: 700 !important;
    color: #fff !important;
    flex-shrink: 0;
    font-family: var(--font-sans) !important;
}

/* Typography inside cards */
.fw-bold {
    font-size: 0.9rem !important;
    font-weight: 700 !important;
    color: var(--usr-text) !important;
    letter-spacing: -0.01em;
}
.fw-semibold {
    font-weight: 600 !important;
    color: var(--usr-text) !important;
}
.text-muted.small {
    font-size: 0.78rem !important;
    color: var(--usr-muted) !important;
    font-weight: 400;
}
.text-muted.mb-0 {
    font-size: 0.875rem !important;
    color: var(--usr-muted) !important;
}
h6.fw-semibold {
    font-size: 0.8rem !important;
    font-weight: 700 !important;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: var(--usr-muted) !important;
    margin-bottom: 6px !important;
}
p.text-muted.mb-0 {
    font-size: 0.9rem !important;
    font-weight: 500;
    color: var(--usr-text) !important;
}

/* Badge */
.badge-crm {
    display: inline-flex;
    align-items: center;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.72rem;
    font-weight: 700;
    letter-spacing: 0.04em;
    font-family: var(--font-sans) !important;
}
.badge-info { background: #dbeafe; color: #2563eb; }

/* Responsive grid */
.row.g-3, .row.g-4 {
    display: grid !important;
    grid-template-columns: repeat(3, 1fr) !important;
    gap: 14px !important;
    margin: 0 !important;
}
.row.g-3 > [class*="col"],
.row.g-4 > [class*="col"] {
    width: 100% !important;
    max-width: 100% !important;
    flex: unset !important;
    padding: 0 !important;
}

@media (max-width: 900px) {
    .row.g-3, .row.g-4 { grid-template-columns: repeat(2, 1fr) !important; }
}
@media (max-width: 560px) {
    .row.g-3, .row.g-4 { grid-template-columns: 1fr !important; }
    .page-header { flex-direction: column; }
}
</style>
@endpush

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