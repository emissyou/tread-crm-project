<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Tread CRM - Admin Dashboard')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=Syne:wght@700;800&display=swap" rel="stylesheet">

<style>
:root {
    --sidebar-width: 262px;
    --sidebar-collapsed: 70px;
    --topbar-height: 58px;
    --accent: #5B8DEF;
    --sidebar-dark: #0d1a2e;
    --sidebar-mid: #142344;
    --sidebar-light: #1c3060;
    --nav-text: rgba(255,255,255,0.65);
    --ease: 0.26s cubic-bezier(0.4,0,0.2,1);
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

body {
    font-family: 'Plus Jakarta Sans', sans-serif;
    background: #edf1f9;
    overflow-x: hidden;
}

/* ══════════════════════════════════════════
   TOPBAR
══════════════════════════════════════════ */
.topbar {
    position: fixed;
    top: 0; left: 260px; right: 0;
    height: var(--topbar-height);
    background: #fff;
    border-bottom: 1px solid #dde5f2;
    display: flex;
    align-items: center;
    z-index: 300;
    box-shadow: 0 1px 10px rgba(15,30,70,0.06);
}

/* Brand zone — mirrors sidebar width */
.topbar-brand {
    display: flex;
    align-items: center;
    gap: 10px;
    text-decoration: none;
    width: var(--sidebar-width);
    padding: 0 18px;
    flex-shrink: 0;
    overflow: hidden;
    transition: width var(--ease);
}

.brand-icon {
    width: 34px; height: 34px;
    border-radius: 9px;
    background: linear-gradient(135deg, #5B8DEF 0%, #2f55b0 100%);
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: 13px;
    flex-shrink: 0;
    box-shadow: 0 3px 10px rgba(91,141,239,0.4);
}

.brand-text {
    font-family: 'Syne', sans-serif;
    font-weight: 800;
    font-size: 16.5px;
    color: #1a2744;
    white-space: nowrap;
    transition: opacity var(--ease), max-width var(--ease);
    max-width: 160px;
    overflow: hidden;
}

.brand-text em { color: var(--accent); font-style: normal; }

/* Topbar right */
.topbar-right {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-left: auto;
    padding-right: 20px;
}

.tb-icon {
    width: 36px; height: 36px;
    border-radius: 9px;
    border: 1.5px solid #dde5f2;
    background: #f4f7fd;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer;
    color: #4a6080;
    position: relative;
    transition: background 0.15s, border-color 0.15s, color 0.15s;
    font-size: 13px;
}

.tb-icon:hover { background: #e6edff; border-color: var(--accent); color: var(--accent); }

.notif-badge {
    position: absolute;
    top: 6px; right: 6px;
    width: 7px; height: 7px;
    border-radius: 50%;
    background: #f05252;
    border: 1.5px solid #fff;
}

/* User chip */
.user-chip {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 4px 13px 4px 5px;
    border-radius: 100px;
    background: #f4f7fd;
    border: 1.5px solid #dde5f2;
    cursor: pointer;
    transition: border-color 0.15s;
}

.user-chip:hover { border-color: var(--accent); }

.user-chip-avatar {
    width: 27px; height: 27px;
    border-radius: 50%;
    background: linear-gradient(135deg, #5B8DEF, #2f55b0);
    display: flex; align-items: center; justify-content: center;
    font-size: 10px; font-weight: 700; color: #fff;
    flex-shrink: 0;
}

.user-chip-name {
    font-size: 12.5px;
    font-weight: 600;
    color: #1a2744;
    white-space: nowrap;
}

/* ══════════════════════════════════════════
   SIDEBAR
══════════════════════════════════════════ */
.sidebar {
    position: fixed;
    top: 0;
    left: 0;
    width: var(--sidebar-width);
    height: calc(100vh - var(--topbar-height));
    background: linear-gradient(170deg, var(--sidebar-dark) 0%, var(--sidebar-mid) 55%, var(--sidebar-light) 100%);
    display: flex;
    flex-direction: column;
    z-index: 200;
    overflow: hidden;
    transition: width var(--ease);
    box-shadow: 3px 0 24px rgba(10,20,50,0.18);
}

/* ── SIDEBAR HEADER ─────────────────────── */
.sidebar-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 12px;
    border-bottom: 1px solid rgba(255,255,255,0.07);
    flex-shrink: 0;
    gap: 10px;
    min-height: 68px;
    overflow: hidden;
}

/* Profile block */
.sh-profile {
    display: flex;
    align-items: center;
    gap: 10px;
    overflow: hidden;
    flex: 1;
    min-width: 0;
    transition: opacity var(--ease), max-width var(--ease);
}

.sh-avatar {
    width: 38px; height: 38px;
    border-radius: 10px;
    background: linear-gradient(135deg, #5B8DEF, #2f55b0);
    display: flex; align-items: center; justify-content: center;
    font-weight: 700; font-size: 13px; color: #fff;
    flex-shrink: 0;
    box-shadow: 0 3px 10px rgba(91,141,239,0.45);
}

.sh-info {
    overflow: hidden;
    white-space: nowrap;
}

.sh-name {
    font-size: 13px;
    font-weight: 700;
    color: #fff;
    line-height: 1.2;
    overflow: hidden;
    text-overflow: ellipsis;
}

.sh-role {
    font-size: 10px;
    color: rgba(255,255,255,0.4);
    margin-top: 2px;
    letter-spacing: 0.3px;
}

/* ── HAMBURGER (always visible inside sidebar-header) ── */
.sidebar-toggle {
    width: 34px; height: 34px;
    border-radius: 9px;
    background: rgba(255,255,255,0.07);
    border: 1px solid rgba(255,255,255,0.1);
    display: flex; align-items: center; justify-content: center;
    cursor: pointer;
    color: rgba(255,255,255,0.55);
    flex-shrink: 0;
    transition: background 0.18s, color 0.18s, border-color 0.18s;
}

.sidebar-toggle:hover {
    background: rgba(91,141,239,0.22);
    border-color: rgba(91,141,239,0.45);
    color: #fff;
}

/* Animated bars */
.hb-bars {
    display: flex;
    flex-direction: column;
    gap: 4px;
    width: 15px;
}

.hb-bars span {
    display: block;
    height: 2px;
    border-radius: 2px;
    background: currentColor;
    transition: width 0.22s, opacity 0.22s;
}

.hb-bars span:nth-child(1) { width: 15px; }
.hb-bars span:nth-child(2) { width: 10px; }
.hb-bars span:nth-child(3) { width: 15px; }

/* When collapsed: equalize bars */
body.sidebar-collapsed .hb-bars span { width: 15px !important; }

/* ── NAV ─────────────────────────────────── */
.sidebar-nav {
    flex: 1;
    padding: 14px 9px;
    overflow-y: auto;
    overflow-x: hidden;
    scrollbar-width: none;
}

.sidebar-nav::-webkit-scrollbar { display: none; }

.nav-group { margin-bottom: 20px; }

.nav-group-label {
    font-size: 9.5px;
    letter-spacing: 1.5px;
    font-weight: 700;
    color: rgba(255,255,255,0.28);
    padding: 0 10px;
    margin-bottom: 6px;
    white-space: nowrap;
    overflow: hidden;
    transition: opacity var(--ease);
}

.nav-item {
    display: flex;
    align-items: center;
    gap: 11px;
    padding: 9px 10px;
    border-radius: 11px;
    color: var(--nav-text);
    text-decoration: none;
    margin-bottom: 2px;
    position: relative;
    border: 1px solid transparent;
    overflow: hidden;
    transition: background 0.18s, color 0.18s, transform 0.18s, padding var(--ease), justify-content var(--ease);
    white-space: nowrap;
}

.nav-item-icon {
    width: 31px; height: 31px;
    border-radius: 9px;
    background: rgba(255,255,255,0.055);
    display: flex; align-items: center; justify-content: center;
    font-size: 12px;
    flex-shrink: 0;
    transition: background 0.18s, box-shadow 0.18s;
}

.nav-item-label {
    font-size: 13px;
    font-weight: 500;
    transition: opacity var(--ease), max-width var(--ease);
    max-width: 160px;
    overflow: hidden;
}

/* Tooltip */
.nav-item-tip {
    position: absolute;
    left: calc(100% + 12px);
    top: 50%;
    transform: translateY(-50%);
    background: #1a3060;
    color: #fff;
    font-size: 11.5px;
    font-weight: 600;
    padding: 5px 10px;
    border-radius: 8px;
    white-space: nowrap;
    pointer-events: none;
    opacity: 0;
    transition: opacity 0.14s;
    z-index: 500;
    box-shadow: 0 4px 14px rgba(0,0,0,0.3);
}

.nav-item-tip::before {
    content: '';
    position: absolute;
    right: 100%; top: 50%;
    transform: translateY(-50%);
    border: 5px solid transparent;
    border-right-color: #1a3060;
}

.nav-item:hover {
    background: rgba(255,255,255,0.075);
    color: #fff;
    transform: translateX(3px);
}

.nav-item:hover .nav-item-icon { background: rgba(91,141,239,0.18); }

/* Active */
.nav-item.active {
    background: linear-gradient(120deg, rgba(91,141,239,0.2) 0%, rgba(91,141,239,0.07) 100%);
    border-color: rgba(91,141,239,0.28);
    color: #fff;
}

.nav-item.active .nav-item-icon {
    background: rgba(91,141,239,0.3);
    box-shadow: 0 0 12px rgba(91,141,239,0.25);
    color: #b0caff;
}

.nav-item.active::after {
    content: '';
    position: absolute;
    left: 0; top: 25%; height: 50%;
    width: 3px;
    background: var(--accent);
    border-radius: 0 3px 3px 0;
}

/* ── SIDEBAR FOOTER ──────────────────────── */
.sidebar-footer {
    padding: 10px 9px 14px;
    border-top: 1px solid rgba(255,255,255,0.07);
    flex-shrink: 0;
}

.sidebar-footer form { margin: 0; }

.logout-item {
    display: flex;
    align-items: center;
    gap: 11px;
    padding: 9px 10px;
    border-radius: 11px;
    color: rgba(255,255,255,0.42);
    background: none;
    border: none;
    cursor: pointer;
    width: 100%;
    white-space: nowrap;
    overflow: hidden;
    transition: background 0.18s, color 0.18s, padding var(--ease);
    position: relative;
}

.logout-item:hover {
    background: rgba(240,82,82,0.1);
    color: #f87171;
}

.logout-icon {
    width: 31px; height: 31px;
    border-radius: 9px;
    background: rgba(240,82,82,0.1);
    display: flex; align-items: center; justify-content: center;
    font-size: 12px;
    color: rgba(240,82,82,0.65);
    flex-shrink: 0;
    transition: background 0.18s;
}

.logout-item:hover .logout-icon {
    background: rgba(240,82,82,0.2);
    color: #f87171;
}

.logout-tip {
    position: absolute;
    left: calc(100% + 12px);
    top: 50%;
    transform: translateY(-50%);
    background: #1a3060;
    color: #fff;
    font-size: 11.5px;
    font-weight: 600;
    padding: 5px 10px;
    border-radius: 8px;
    white-space: nowrap;
    pointer-events: none;
    opacity: 0;
    transition: opacity 0.14s;
    z-index: 500;
    box-shadow: 0 4px 14px rgba(0,0,0,0.3);
}

.logout-tip::before {
    content: '';
    position: absolute;
    right: 100%; top: 50%;
    transform: translateY(-50%);
    border: 5px solid transparent;
    border-right-color: #1a3060;
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

.main-content { padding: 28px 30px; }

/* ══════════════════════════════════════════
   COLLAPSED STATE
══════════════════════════════════════════ */
body.sidebar-collapsed .sidebar { width: var(--sidebar-collapsed); }

body.sidebar-collapsed .topbar-brand {
    width: var(--sidebar-collapsed);
    padding: 0;
    justify-content: center;
}

body.sidebar-collapsed .brand-text {
    opacity: 0;
    max-width: 0;
}

body.sidebar-collapsed .main-wrapper { margin-left: var(--sidebar-collapsed); }

/* Hide profile, center toggle */
body.sidebar-collapsed .sh-profile {
    opacity: 0;
    max-width: 0;
    overflow: hidden;
}

body.sidebar-collapsed .sidebar-header {
    justify-content: center;
}

/* Nav collapsed */
body.sidebar-collapsed .nav-group-label { opacity: 0; }

body.sidebar-collapsed .nav-item {
    justify-content: center;
    padding: 9px 0;
    transform: none !important;
}

body.sidebar-collapsed .nav-item-label {
    opacity: 0;
    max-width: 0;
}

body.sidebar-collapsed .nav-item.active::after { display: none; }

/* Logout collapsed */
body.sidebar-collapsed .logout-item {
    justify-content: center;
    padding: 9px 0;
}

body.sidebar-collapsed .logout-item .nav-item-label {
    opacity: 0;
    max-width: 0;
}

/* Tooltips on hover */
body.sidebar-collapsed .nav-item:hover .nav-item-tip,
body.sidebar-collapsed .logout-item:hover .logout-tip { opacity: 1; }

</style>
</head>

<body>

<!-- ════════════════════════════════════════
     TOPBAR
════════════════════════════════════════ -->
<header class="topbar">

    <a href="{{ route('admin.dashboard') }}" class="topbar-brand">
        <div class="brand-icon"><i class="fas fa-chart-line"></i></div>
        <div class="brand-text">Tread <em>CRM</em></div>
    </a>

    <div class="topbar-right">
        <div class="tb-icon" title="Notifications">
            <i class="fas fa-bell"></i>
            <span class="notif-badge"></span>
        </div>
        <div class="user-chip">
            <div class="user-chip-avatar">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </div>
            <span class="user-chip-name">{{ auth()->user()->name }}</span>
        </div>
    </div>

</header>


<!-- ════════════════════════════════════════
     SIDEBAR
════════════════════════════════════════ -->
<aside class="sidebar">

    <!-- Header: profile left, hamburger right -->
    <div class="sidebar-header">

        <div class="sh-profile">
            <div class="sh-avatar">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </div>
            <div class="sh-info">
                <div class="sh-name">{{ auth()->user()->name }}</div>
                <div class="sh-role">Administrator</div>
            </div>
        </div>

        <!-- Hamburger — always visible, right-aligned -->
        <button class="sidebar-toggle" id="sidebarToggle" title="Toggle sidebar">
            <div class="hb-bars">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </button>

    </div>

    <!-- Nav -->
    <nav class="sidebar-nav">

        <div class="nav-group">
            <div class="nav-group-label">MAIN</div>
            <a href="{{ route('admin.dashboard') }}"
               class="nav-item @if(request()->routeIs('admin.dashboard')) active @endif">
                <span class="nav-item-icon"><i class="fas fa-house"></i></span>
                <span class="nav-item-label">Dashboard</span>
                <span class="nav-item-tip">Dashboard</span>
            </a>
        </div>

        <div class="nav-group">
            <div class="nav-group-label">MANAGEMENT</div>
            <a href="/admin/contacts"
               class="nav-item @if(request()->is('admin/contacts*')) active @endif">
                <span class="nav-item-icon"><i class="fas fa-user"></i></span>
                <span class="nav-item-label">Contacts</span>
                <span class="nav-item-tip">Contacts</span>
            </a>
            <a href="/admin/companies"
               class="nav-item @if(request()->is('admin/companies*')) active @endif">
                <span class="nav-item-icon"><i class="fas fa-building"></i></span>
                <span class="nav-item-label">Companies</span>
                <span class="nav-item-tip">Companies</span>
            </a>
            <a href="/admin/leads"
               class="nav-item @if(request()->is('admin/leads*')) active @endif">
                <span class="nav-item-icon"><i class="fas fa-bullseye"></i></span>
                <span class="nav-item-label">Leads</span>
                <span class="nav-item-tip">Leads</span>
            </a>
            <a href="/admin/deals"
               class="nav-item @if(request()->is('admin/deals*')) active @endif">
                <span class="nav-item-icon"><i class="fas fa-handshake"></i></span>
                <span class="nav-item-label">Deals</span>
                <span class="nav-item-tip">Deals</span>
            </a>
        </div>

        <div class="nav-group">
            <div class="nav-group-label">OPERATIONS</div>
            <a href="/admin/tasks"
               class="nav-item @if(request()->is('admin/tasks*')) active @endif">
                <span class="nav-item-icon"><i class="fas fa-list-check"></i></span>
                <span class="nav-item-label">Tasks</span>
                <span class="nav-item-tip">Tasks</span>
            </a>
            <a href="/admin/calendar"
               class="nav-item @if(request()->is('admin/calendar*')) active @endif">
                <span class="nav-item-icon"><i class="fas fa-calendar-days"></i></span>
                <span class="nav-item-label">Calendar</span>
                <span class="nav-item-tip">Calendar</span>
            </a>
        </div>

        <div class="nav-group">
            <div class="nav-group-label">INSIGHTS</div>
            <a href="/admin/reports"
               class="nav-item @if(request()->is('admin/reports*')) active @endif">
                <span class="nav-item-icon"><i class="fas fa-chart-pie"></i></span>
                <span class="nav-item-label">Reports</span>
                <span class="nav-item-tip">Reports</span>
            </a>
        </div>

    </nav>

    <!-- Logout -->
    <div class="sidebar-footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-item">
                <span class="logout-icon"><i class="fas fa-right-from-bracket"></i></span>
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
<script>
(function () {
    const body   = document.body;
    const toggle = document.getElementById('sidebarToggle');

    // Restore saved preference without flash
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