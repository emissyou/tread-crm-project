<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Tread CRM - Admin Dashboard')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=Syne:wght@700;800&display=swap" rel="stylesheet">

<style>
:root {
    --sidebar-width: 270px;
    --sidebar-collapsed: 75px;
    --topbar-height: 64px;
    --primary: #5B8DEF;
    --primary-dark: #2f55b0;
    --sidebar-dark: #0f1725;
    --sidebar-mid: #141e2e;
    --sidebar-light: #1a2a3a;
    --nav-text: rgba(255,255,255,0.7);
    --nav-hover: rgba(255,255,255,0.9);
    --ease: 0.3s cubic-bezier(0.4,0,0.2,1);
    --border-color: #dde5f2;
}

*, *::before, *::after { 
    box-sizing: border-box; 
    margin: 0; 
    padding: 0; 
}

body {
    font-family: 'Plus Jakarta Sans', sans-serif;
    background: #f5f7fc;
    overflow-x: hidden;
}

.page-header {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: flex-start;
    gap: 18px;
    background: #ffffff;
    border: 1px solid #e8eef9;
    border-radius: 22px;
    box-shadow: 0 24px 60px rgba(15, 23, 42, 0.08);
    padding: 28px 30px;
    margin-bottom: 28px;
}

.page-header-left {
    flex: 1;
    min-width: 240px;
}

.page-title {
    font-size: 1.8rem;
    font-family: 'Syne', sans-serif;
    font-weight: 800;
    color: #111827;
    margin-bottom: 8px;
    line-height: 1.05;
}

.page-subtitle {
    color: #475569;
    font-size: 0.98rem;
    margin-bottom: 0;
}

.page-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 10px;
    flex-wrap: wrap;
}

.crm-card {
    background: #fff;
    border: 1px solid #e8eef9;
    border-radius: 20px;
    box-shadow: 0 18px 40px rgba(15, 23, 42, 0.06);
}

.crm-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 14px;
    padding: 22px 24px;
    border-bottom: 1px solid #eef2ff;
}

.crm-card-body {
    padding: 24px;
}

.crm-card-footer {
    padding: 18px 24px;
    border-top: 1px solid #eef2ff;
}

.btn-crm-primary {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 0.82rem 1.35rem;
    background: linear-gradient(135deg, #5b8def 0%, #2f55b0 100%);
    border: 0;
    border-radius: 14px;
    color: #fff;
    font-weight: 600;
    transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
}

.btn-crm-primary:hover {
    transform: translateY(-1px);
    background: linear-gradient(135deg, #2f55b0 0%, #5b8def 100%);
}

.btn-crm-secondary {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 0.82rem 1.25rem;
    background: #f8fafc;
    border: 1px solid #dbeafe;
    border-radius: 14px;
    color: #334155;
    font-weight: 600;
}

.btn-crm-danger {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 0.82rem 1.25rem;
    background: #fef2f2;
    border: 1px solid #fecaca;
    border-radius: 14px;
    color: #b91c1c;
    font-weight: 600;
}

.btn-crm-danger:hover {
    background: #fee2e2;
}

.btn-crm-secondary:hover {
    background: #eef2ff;
}

.badge-crm {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.45rem 0.85rem;
    border-radius: 999px;
    font-size: 0.78rem;
    font-weight: 700;
    letter-spacing: 0.01em;
}

.badge-crm.badge-primary { background: #eff6ff; color: #1d4ed8; }
.badge-crm.badge-success { background: #dcfce7; color: #15803d; }
.badge-crm.badge-warning { background: #fef3c7; color: #b45309; }
.badge-crm.badge-info    { background: #e0f2fe; color: #0369a1; }
    .badge-crm.badge-secondary { background: #f8fafc; color: #334155; }
    .badge-crm.badge-purple { background: #ede9fe; color: #7c3aed; }

    .crm-input {
        width: 100%;
        min-height: 44px;
        border: 1px solid #dbe4f0;
        border-radius: 14px;
        padding: 0.9rem 1rem;
        font-size: 0.95rem;
        color: #1f2937;
        background: #fff;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .crm-input:focus {
        border-color: #5b8def;
        box-shadow: 0 0 0 4px rgba(91, 141, 239, 0.12);
        outline: none;
    }

    .crm-label {
        display: block;
        margin-bottom: 0.55rem;
        font-size: 0.82rem;
        font-weight: 700;
        letter-spacing: 0.01em;
        color: #475569;
    }

    .search-bar {
        position: relative;
        flex: 1;
        min-width: 260px;
        max-width: 360px;
    }
    .search-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        pointer-events: none;
    }
    .search-bar .crm-input {
        padding-left: 3rem;
    }

    .stat-card {
        padding: 18px 20px;
        background: #fff;
        border: 1px solid #eef2ff;
        border-radius: 20px;
        box-shadow: 0 18px 30px rgba(15, 23, 42, 0.04);
        min-height: 130px;
    }
    .stat-icon {
        width: 42px;
        height: 42px;
        border-radius: 14px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .stat-value {
        font-size: 1.7rem;
        font-weight: 700;
    }
    .stat-label {
        font-size: 0.9rem;
        color: #64748b;
        margin-top: 6px;
    }

    .empty-state {
        padding: 48px 24px;
        text-align: center;
        color: #475569;
    }
    .empty-state .empty-icon {
        width: 72px;
        height: 72px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 24px;
        background: rgba(91, 141, 239, 0.12);
        color: #3b82f6;
        margin-bottom: 18px;
        font-size: 28px;
    }

    .crm-modal .modal-content {
        border-radius: 24px;
        border: 1px solid rgba(15, 23, 42, 0.06);
    }
    .crm-modal .modal-header,
    .crm-modal .modal-footer {
        border: none;
        padding: 22px 24px;
    }
    .crm-modal .modal-body {
        padding: 0 24px 24px;
    }

    .crm-table th, .crm-table td {
        padding: 16px 18px;
    }
    .crm-table tbody tr:hover {
        background: #f8fbff;
    }

    .crm-card-body > form {
        align-items: center;
    }

    .crm-header-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

.avatar-circle {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 46px;
    height: 46px;
    border-radius: 16px;
    font-size: 0.95rem;
    font-weight: 700;
    color: #fff;
}

.crm-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.95rem;
}

.crm-table th,
.crm-table td {
    padding: 16px 14px;
    border-bottom: 1px solid #eef2ff;
    vertical-align: middle;
}

.crm-table thead th {
    font-weight: 700;
    color: #1f2937;
    background: #f8fbff;
}

/* ══════════════════════════════════════════
   TOPBAR
══════════════════════════════════════════ */
.topbar {
    position: fixed;
    top: 0; left: var(--sidebar-width); right: 0;
    height: var(--topbar-height);
    background: linear-gradient(135deg, #fff 0%, #f9fafc 100%);
    border-bottom: 1px solid var(--border-color);
    display: flex;
    align-items: center;
    justify-content: space-between;
    z-index: 300;
    box-shadow: 0 2px 12px rgba(15,30,70,0.08);
    transition: left var(--ease);
}

.topbar-left {
    display: flex;
    align-items: center;
    gap: 20px;
    padding: 0 28px;
}

.topbar-breadcrumb {
    font-size: 14px;
    color: #4a5f7f;
}

.topbar-breadcrumb strong {
    color: #1a2744;
    font-weight: 600;
}

.topbar-right {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 0 28px;
}

.tb-icon {
    width: 40px; height: 40px;
    border-radius: 12px;
    border: 1.5px solid var(--border-color);
    background: #f8faff;
    display: flex; 
    align-items: center; 
    justify-content: center;
    cursor: pointer;
    color: #4a6080;
    position: relative;
    transition: all 0.2s ease;
    font-size: 15px;
}

.tb-icon:hover { 
    background: #f0f5ff; 
    border-color: var(--primary);
    color: var(--primary);
    transform: translateY(-2px);
}

.notif-badge {
    position: absolute;
    top: 8px; right: 8px;
    width: 8px; height: 8px;
    border-radius: 50%;
    background: #ef4444;
    border: 2px solid #fff;
    box-shadow: 0 0 6px rgba(239, 68, 68, 0.5);
}

/* User Dropdown */
.user-dropdown {
    position: relative;
}

.user-chip {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 6px 14px 6px 6px;
    border-radius: 12px;
    background: linear-gradient(135deg, rgba(91, 141, 239, 0.1), rgba(47, 85, 176, 0.05));
    border: 1.5px solid rgba(91, 141, 239, 0.2);
    cursor: pointer;
    transition: all 0.2s ease;
}

.user-chip:hover {
    background: linear-gradient(135deg, rgba(91, 141, 239, 0.15), rgba(47, 85, 176, 0.1));
    border-color: var(--primary);
}

.user-chip-avatar {
    width: 32px; height: 32px;
    border-radius: 10px;
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    display: flex; 
    align-items: center; 
    justify-content: center;
    font-size: 12px; 
    font-weight: 700; 
    color: #fff;
    flex-shrink: 0;
    box-shadow: 0 3px 12px rgba(91, 141, 239, 0.35);
}

.user-chip-name {
    font-size: 13px;
    font-weight: 600;
    color: #1a2744;
}

.user-chip-dropdown {
    position: absolute;
    top: calc(100% + 10px);
    right: 0;
    background: #fff;
    border: 1px solid var(--border-color);
    border-radius: 14px;
    box-shadow: 0 10px 30px rgba(15, 30, 70, 0.15);
    min-width: 200px;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.2s, visibility 0.2s;
    z-index: 500;
    overflow: hidden;
}

.user-chip:hover + .user-chip-dropdown,
.user-chip-dropdown:hover {
    opacity: 1;
    visibility: visible;
}

.dropdown-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    color: #4a5f7f;
    text-decoration: none;
    border: none;
    background: none;
    width: 100%;
    cursor: pointer;
    transition: background 0.2s;
    font-size: 13px;
    border-bottom: 1px solid #f0f0f0;
}

.dropdown-item:last-child {
    border-bottom: none;
}

.dropdown-item:hover {
    background: #f5f8fc;
    color: var(--primary);
}

.dropdown-item i {
    width: 18px;
    text-align: center;
    color: #999;
}

.dropdown-item:hover i {
    color: var(--primary);
}

/* ══════════════════════════════════════════
   SIDEBAR
══════════════════════════════════════════ */
.sidebar {
    position: fixed;
    top: 0;
    left: 0;
    width: var(--sidebar-width);
    height: 100vh;
    background: linear-gradient(180deg, var(--sidebar-dark) 0%, var(--sidebar-mid) 50%, var(--sidebar-light) 100%);
    display: flex;
    flex-direction: column;
    z-index: 400;
    overflow: hidden;
    transition: width var(--ease);
    box-shadow: 4px 0 28px rgba(10, 20, 50, 0.22);
}

/* ── SIDEBAR HEADER ─────────────────────── */
.sidebar-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 14px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    flex-shrink: 0;
    gap: 12px;
    min-height: 76px;
    overflow: hidden;
}

.sh-brand {
    display: flex;
    align-items: center;
    gap: 12px;
    flex: 1;
    min-width: 0;
    overflow: hidden;
}

.sh-brand-icon {
    width: 42px; 
    height: 42px;
    border-radius: 12px;
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
    display: flex; 
    align-items: center; 
    justify-content: center;
    font-size: 18px; 
    color: #fff;
    flex-shrink: 0;
    box-shadow: 0 4px 14px rgba(91, 141, 239, 0.45);
}

.sh-brand-info {
    overflow: hidden;
    white-space: nowrap;
}

.sh-brand-name {
    font-family: 'Syne', sans-serif;
    font-size: 15px;
    font-weight: 800;
    color: #fff;
    line-height: 1.2;
    overflow: hidden;
    text-overflow: ellipsis;
}

.sh-brand-name em {
    color: rgba(91, 141, 239, 0.8);
    font-style: normal;
    font-weight: 800;
}

.sh-brand-subtitle {
    font-size: 10px;
    color: rgba(255, 255, 255, 0.35);
    margin-top: 2px;
    letter-spacing: 0.5px;
    text-transform: uppercase;
}

/* Hamburger */
.sidebar-toggle {
    width: 40px; 
    height: 40px;
    border-radius: 10px;
    background: rgba(91, 141, 239, 0.15);
    border: 1px solid rgba(91, 141, 239, 0.25);
    display: flex; 
    align-items: center; 
    justify-content: center;
    cursor: pointer;
    color: rgba(255, 255, 255, 0.7);
    flex-shrink: 0;
    transition: all 0.2s ease;
}

.sidebar-toggle:hover {
    background: rgba(91, 141, 239, 0.25);
    border-color: rgba(91, 141, 239, 0.4);
    color: #fff;
}

.hb-bars {
    display: flex;
    flex-direction: column;
    gap: 5px;
    width: 18px;
}

.hb-bars span {
    display: block;
    height: 2.5px;
    border-radius: 2px;
    background: currentColor;
    transition: width 0.25s;
}

.hb-bars span:nth-child(1) { width: 16px; }
.hb-bars span:nth-child(2) { width: 14px; }
.hb-bars span:nth-child(3) { width: 16px; }

body.sidebar-collapsed .hb-bars span { width: 16px !important; }

/* ── NAV ─────────────────────────────────── */
.sidebar-nav {
    flex: 1;
    padding: 18px 10px;
    overflow-y: auto;
    overflow-x: hidden;
    scrollbar-width: thin;
    scrollbar-color: rgba(91, 141, 239, 0.3) transparent;
}

.sidebar-nav::-webkit-scrollbar {
    width: 5px;
}

.sidebar-nav::-webkit-scrollbar-track {
    background: transparent;
}

.sidebar-nav::-webkit-scrollbar-thumb {
    background: rgba(91, 141, 239, 0.3);
    border-radius: 5px;
}

.sidebar-nav::-webkit-scrollbar-thumb:hover {
    background: rgba(91, 141, 239, 0.5);
}

.nav-group { 
    margin-bottom: 24px; 
}

.nav-group:first-child {
    margin-top: 0;
}

.nav-group-label {
    font-size: 10px;
    letter-spacing: 1.8px;
    font-weight: 800;
    color: rgba(255, 255, 255, 0.32);
    padding: 0 12px;
    margin-bottom: 10px;
    white-space: nowrap;
    overflow: hidden;
    transition: opacity var(--ease);
    text-transform: uppercase;
}

.nav-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 12px;
    border-radius: 12px;
    color: var(--nav-text);
    text-decoration: none;
    margin-bottom: 4px;
    position: relative;
    border: 1px solid transparent;
    overflow: hidden;
    transition: all 0.2s ease;
    white-space: nowrap;
}

.nav-item-icon {
    width: 36px; 
    height: 36px;
    border-radius: 10px;
    background: rgba(255, 255, 255, 0.08);
    display: flex; 
    align-items: center; 
    justify-content: center;
    font-size: 14px;
    flex-shrink: 0;
    transition: all 0.2s ease;
}

.nav-item-label {
    font-size: 13px;
    font-weight: 500;
    transition: opacity var(--ease), max-width var(--ease);
    max-width: 180px;
    overflow: hidden;
}

/* Tooltip */
.nav-item-tip {
    position: absolute;
    left: calc(100% + 14px);
    top: 50%;
    transform: translateY(-50%);
    background: rgba(26, 48, 96, 0.95);
    color: #fff;
    font-size: 12px;
    font-weight: 600;
    padding: 6px 12px;
    border-radius: 8px;
    white-space: nowrap;
    pointer-events: none;
    opacity: 0;
    transition: opacity 0.15s;
    z-index: 600;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.35);
    backdrop-filter: blur(4px);
}

.nav-item-tip::before {
    content: '';
    position: absolute;
    right: 100%; 
    top: 50%;
    transform: translateY(-50%);
    border: 5px solid transparent;
    border-right-color: rgba(26, 48, 96, 0.95);
}

.nav-item:hover {
    background: rgba(91, 141, 239, 0.15);
    color: var(--nav-hover);
    transform: translateX(4px);
}

.nav-item:hover .nav-item-icon { 
    background: rgba(91, 141, 239, 0.25);
    color: #b3d1ff;
}

/* Active State */
.nav-item.active {
    background: linear-gradient(120deg, rgba(91, 141, 239, 0.25) 0%, rgba(91, 141, 239, 0.1) 100%);
    border-color: rgba(91, 141, 239, 0.35);
    color: #fff;
}

.nav-item.active .nav-item-icon {
    background: rgba(91, 141, 239, 0.4);
    box-shadow: 0 0 14px rgba(91, 141, 239, 0.3);
    color: #d4e1ff;
}

.nav-item.active::before {
    content: '';
    position: absolute;
    left: 0; 
    top: 20%; 
    height: 60%;
    width: 4px;
    background: linear-gradient(180deg, var(--primary), var(--primary-dark));
    border-radius: 0 4px 4px 0;
    box-shadow: 2px 0 8px rgba(91, 141, 239, 0.4);
}

/* ── SIDEBAR FOOTER ──────────────────────── */
.sidebar-footer {
    padding: 14px 10px 18px;
    border-top: 1px solid rgba(255, 255, 255, 0.08);
    flex-shrink: 0;
}

.sidebar-footer form { margin: 0; }

.logout-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 12px;
    border-radius: 12px;
    color: rgba(255, 255, 255, 0.5);
    background: none;
    border: none;
    cursor: pointer;
    width: 100%;
    white-space: nowrap;
    overflow: hidden;
    transition: all 0.2s ease;
    position: relative;
    font-size: 13px;
    font-weight: 500;
}

.logout-icon {
    width: 36px; 
    height: 36px;
    border-radius: 10px;
    background: rgba(239, 68, 68, 0.1);
    display: flex; 
    align-items: center; 
    justify-content: center;
    font-size: 14px;
    color: rgba(239, 68, 68, 0.6);
    flex-shrink: 0;
    transition: all 0.2s ease;
}

.logout-item:hover {
    background: rgba(239, 68, 68, 0.12);
    color: #fecaca;
}

.logout-item:hover .logout-icon {
    background: rgba(239, 68, 68, 0.2);
    color: #fca5a5;
}

.logout-tip {
    position: absolute;
    left: calc(100% + 14px);
    top: 50%;
    transform: translateY(-50%);
    background: rgba(26, 48, 96, 0.95);
    color: #fff;
    font-size: 12px;
    font-weight: 600;
    padding: 6px 12px;
    border-radius: 8px;
    white-space: nowrap;
    pointer-events: none;
    opacity: 0;
    transition: opacity 0.15s;
    z-index: 600;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.35);
    backdrop-filter: blur(4px);
}

.logout-tip::before {
    content: '';
    position: absolute;
    right: 100%; 
    top: 50%;
    transform: translateY(-50%);
    border: 5px solid transparent;
    border-right-color: rgba(26, 48, 96, 0.95);
}

/* ══════════════════════════════════════════
   MAIN CONTENT
══════════════════════════════════════════ */
.main-wrapper {
    padding-top: var(--topbar-height);
    margin-left: var(--sidebar-width);
    min-height: 100vh;
    transition: margin-left var(--ease);
}

.main-content { 
    padding: 32px 36px; 
}

/* ══════════════════════════════════════════
   COLLAPSED STATE
══════════════════════════════════════════ */
body.sidebar-collapsed .sidebar { 
    width: var(--sidebar-collapsed); 
}

body.sidebar-collapsed .topbar { 
    left: var(--sidebar-collapsed); 
}

body.sidebar-collapsed .main-wrapper { 
    margin-left: var(--sidebar-collapsed); 
}

/* Hide/show elements when collapsed */
body.sidebar-collapsed .sh-brand-info {
    opacity: 0;
    max-width: 0;
    overflow: hidden;
}

body.sidebar-collapsed .sidebar-header {
    justify-content: center;
    min-height: 76px;
}

body.sidebar-collapsed .nav-group-label { 
    opacity: 0; 
    max-width: 0;
}

body.sidebar-collapsed .nav-item {
    justify-content: center;
    padding: 10px 0;
    transform: none !important;
}

body.sidebar-collapsed .nav-item-label {
    opacity: 0;
    max-width: 0;
}

body.sidebar-collapsed .nav-item.active::before { 
    display: none; 
}

body.sidebar-collapsed .logout-item {
    justify-content: center;
    padding: 10px 0;
}

body.sidebar-collapsed .logout-item .nav-item-label {
    opacity: 0;
    max-width: 0;
}

/* Tooltips visible when collapsed */
body.sidebar-collapsed .nav-item:hover .nav-item-tip,
body.sidebar-collapsed .logout-item:hover .logout-tip { 
    opacity: 1; 
}

</style>
@stack('styles')
</head>

<body>

<!-- ════════════════════════════════════════
     TOPBAR
════════════════════════════════════════ -->
<header class="topbar">

    <div class="topbar-left">
        <div class="topbar-breadcrumb">
            <strong>@yield('page_title', 'Dashboard')</strong>
        </div>
    </div>

    <div class="topbar-right">
        <div class="tb-icon" title="Notifications">
            <i class="fas fa-bell"></i>
            <span class="notif-badge"></span>
        </div>
        
        <div class="user-dropdown">
            <div class="user-chip">
                <div class="user-chip-avatar">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <span class="user-chip-name">{{ auth()->user()->name }}</span>
            </div>
            <div class="user-chip-dropdown">
                <a href="#" class="dropdown-item">
                    <i class="fas fa-user-circle"></i>
                    <span>View Profile</span>
                </a>
                <button class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </button>
            </div>
        </div>
    </div>

</header>


<!-- ════════════════════════════════════════
     SIDEBAR
════════════════════════════════════════ -->
<aside class="sidebar">

    <!-- Header: Brand + Hamburger -->
    <div class="sidebar-header">

        <div class="sh-brand">
            <div class="sh-brand-icon">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="sh-brand-info">
                <div class="sh-brand-name">Tread<em>CRM</em></div>
                <div class="sh-brand-subtitle">v2.0</div>
            </div>
        </div>

        <button class="sidebar-toggle" id="sidebarToggle" title="Toggle sidebar">
            <div class="hb-bars">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </button>

    </div>

    <!-- Navigation -->
    @php
        $user = auth()->user();
        $navGroups = [
            [
                'label' => 'Main',
                'items' => [
                    [
                        'label' => 'Dashboard',
                        'url' => route('admin.dashboard'),
                        'icon' => 'fa-chart-line',
                        'active' => request()->routeIs('admin.dashboard'),
                        'tip' => 'Dashboard overview',
                    ],
                ],
            ],
            [
                'label' => 'Pipeline',
                'items' => array_filter([
                    [
                        'label' => 'Reports',
                        'url' => route('admin.reports.index'),
                        'icon' => 'fa-chart-pie',
                        'active' => request()->routeIs('admin.reports.*'),
                        'tip' => 'View Pipeline & Data Reports',
                    ],
                    [
                        'label' => 'Customer',
                        'url' => route('admin.customers.index'),
                        'icon' => 'fa-user',
                        'active' => request()->routeIs('admin.customers.*'),
                        'tip' => 'Customer list and reports',
                    ],
                    [
                        'label' => 'Lead',
                        'url' => route('admin.leads.index'),
                        'icon' => 'fa-bullseye',
                        'active' => request()->routeIs('admin.leads.*'),
                        'tip' => 'Lead pipeline and data',
                    ],
                    [
                        'label' => 'Follow-up',
                        'url' => route('admin.follow-ups.index'),
                        'icon' => 'fa-calendar-check',
                        'active' => request()->routeIs('admin.follow-ups.*'),
                        'tip' => 'Follow-up management',
                    ],
                    [
                        'label' => 'Activity',
                        'url' => route('admin.activities.index'),
                        'icon' => 'fa-history',
                        'active' => request()->routeIs('admin.activities.*'),
                        'tip' => 'Activity log and history',
                    ],
                ]),
            ],
            [
                'label' => 'Management',
                'items' => array_filter([
                    $user->canManageUsers() ? [
                        'label' => 'Users',
                        'url' => route('admin.users.index'),
                        'icon' => 'fa-users-cog',
                        'active' => request()->routeIs('admin.users.*'),
                        'tip' => 'Admin users and roles',
                    ] : null,
                    $user->canConfigureSystem() ? [
                        'label' => 'Settings',
                        'url' => route('admin.settings.index'),
                        'icon' => 'fa-cog',
                        'active' => request()->routeIs('admin.settings.*'),
                        'tip' => 'System configuration',
                    ] : null,
                ]),
            ],
        ];
    @endphp

    <nav class="sidebar-nav">
        @foreach ($navGroups as $group)
            <div class="nav-group">
                <div class="nav-group-label">{{ $group['label'] }}</div>
                @foreach ($group['items'] as $item)
                    <a href="{{ $item['url'] }}" class="nav-item {{ $item['active'] ? 'active' : '' }}">
                        <span class="nav-item-icon"><i class="fas {{ $item['icon'] }}"></i></span>
                        <span class="nav-item-label">{{ $item['label'] }}</span>
                        <span class="nav-item-tip">{{ $item['tip'] }}</span>
                    </a>
                @endforeach
            </div>
        @endforeach
    </nav>

    <!-- Footer: Logout -->
    <div class="sidebar-footer">
        <form id="logout-form" method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-item">
                <span class="logout-icon"><i class="fas fa-sign-out-alt"></i></span>
                <span class="nav-item-label">Logout</span>
                <span class="logout-tip">Logout</span>
            </button>
        </form>
    </div>

</aside>


<!-- ════════════════════════════════════════
     MAIN
════════════════════════════════════════ -->
<div class="main-wrapper">
    <div class="main-content">
        @yield('content')
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
window.CSRF = document.querySelector('meta[name="csrf-token"]').content;
(function () {
    const body   = document.body;
    const toggle = document.getElementById('sidebarToggle');

    // Restore collapsed state
    if (localStorage.getItem('tread_sidebar') === '1') {
        body.classList.add('sidebar-collapsed');
    }

    toggle.addEventListener('click', function () {
        const collapsed = body.classList.toggle('sidebar-collapsed');
        localStorage.setItem('tread_sidebar', collapsed ? '1' : '0');
    });
})();
</script>

@stack('scripts')

</body>
</html>