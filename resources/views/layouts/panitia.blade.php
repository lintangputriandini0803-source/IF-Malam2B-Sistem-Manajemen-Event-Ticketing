<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - SIMETIX Panitia</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        * { font-family: 'Plus Jakarta Sans', sans-serif; box-sizing: border-box; }

        :root {
            --sidebar-w: 210px;
            --purple: #6B0080;
            --purple-dark: #3d0049;
            --purple-mid: #4a005a;
            --purple-light: #f7f0ff;
            --accent: #22c55e;
        }

        body {
            background: #f0ecf5;
            background-image: radial-gradient(ellipse at 0% 0%, rgba(107,0,128,0.04) 0%, transparent 60%);
        }

        /* ── SIDEBAR ── */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--purple-dark);
            background-image: linear-gradient(180deg, #4a005a 0%, #3a0047 100%);
            min-height: 100vh;
            position: fixed;
            top: 0; left: 0;
            display: flex;
            flex-direction: column;
            z-index: 40;
            border-right: 1px solid rgba(255,255,255,0.04);
            box-shadow: 2px 0 20px rgba(0,0,0,0.2);
        }

        .sidebar-brand {
            padding: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.07);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar-nav { padding: 14px 10px; flex: 1; }

        .nav-label {
            font-size: 9.5px;
            font-weight: 700;
            letter-spacing: 0.12em;
            color: rgba(255,255,255,0.28);
            padding: 0 10px;
            margin-bottom: 6px;
            margin-top: 4px;
            text-transform: uppercase;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px;
            border-radius: 10px;
            color: rgba(255,255,255,0.6);
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            margin-bottom: 2px;
            transition: all 0.18s;
            position: relative;
        }
        .nav-item:hover {
            background: rgba(255,255,255,0.07);
            color: rgba(255,255,255,0.92);
        }
        .nav-item.active {
            background: rgba(255,255,255,0.12);
            color: white;
            font-weight: 600;
            box-shadow: inset 0 0 0 1px rgba(255,255,255,0.1);
        }
        .nav-item.active::before {
            content: '';
            position: absolute;
            left: 0; top: 50%;
            transform: translateY(-50%);
            width: 3px; height: 18px;
            background: rgba(255,255,255,0.7);
            border-radius: 0 3px 3px 0;
        }
        .nav-item svg { width: 16px; height: 16px; flex-shrink: 0; opacity: 0.8; }
        .nav-item.active svg, .nav-item:hover svg { opacity: 1; }

        .sidebar-footer {
            padding: 14px 16px;
            border-top: 1px solid rgba(255,255,255,0.07);
            background: rgba(0,0,0,0.1);
        }

        .user-chip { display: flex; align-items: center; gap: 10px; }

        .avatar {
            width: 32px; height: 32px;
            border-radius: 8px;
            background: rgba(255,255,255,0.15);
            border: 1px solid rgba(255,255,255,0.2);
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 13px; color: white;
            flex-shrink: 0;
        }

        /* ── TOPBAR ── */
        .topbar {
            margin-left: var(--sidebar-w);
            background: white;
            border-bottom: 1px solid rgba(107,0,128,0.08);
            height: 52px;
            display: flex;
            align-items: center;
            padding: 0 28px;
            position: sticky;
            top: 0;
            z-index: 30;
            box-shadow: 0 1px 12px rgba(0,0,0,0.05);
        }

        /* ── CONTENT ── */
        .content {
            margin-left: var(--sidebar-w);
            padding: 24px 28px;
            min-height: calc(100vh - 52px);
        }

        /* ── STAT CARD ── */
        .stat-card {
            background: white;
            border-radius: 14px;
            padding: 20px;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            border: 1px solid rgba(107,0,128,0.06);
            box-shadow:
                0 1px 3px rgba(0,0,0,0.05),
                0 4px 12px rgba(107,0,128,0.05);
            transition: box-shadow 0.2s, transform 0.2s;
        }
        .stat-card:hover {
            box-shadow:
                0 4px 16px rgba(107,0,128,0.1),
                0 1px 4px rgba(0,0,0,0.06);
            transform: translateY(-2px);
        }

        .stat-icon {
            width: 42px; height: 42px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
        }

        /* ── EVENT ROW ── */
        .event-row {
            background: white;
            border-radius: 12px;
            padding: 14px 18px;
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 8px;
            transition: box-shadow 0.18s, transform 0.18s;
            border: 1px solid rgba(107,0,128,0.04);
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        }
        .event-row:hover {
            box-shadow: 0 4px 16px rgba(107,0,128,0.1);
            transform: translateX(2px);
        }

        .event-thumb {
            width: 88px; height: 62px;
            border-radius: 8px;
            object-fit: cover;
            flex-shrink: 0;
            background: #f0ecf5;
            position: relative;
            overflow: hidden;
        }

        .badge-status {
            position: absolute;
            top: 5px; left: 5px;
            font-size: 8px;
            font-weight: 700;
            padding: 2px 6px;
            border-radius: 4px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .badge-draft     { background: rgba(55,65,81,0.85); color: white; }
        .badge-published { background: rgba(22,163,74,0.9); color: white; }
        .badge-cancelled { background: rgba(220,38,38,0.85); color: white; }

        .date-badge {
            position: absolute;
            bottom: 4px; left: 4px;
            background: rgba(255,255,255,0.95);
            border-radius: 5px;
            padding: 2px 5px;
            font-size: 9px;
            font-weight: 700;
            color: #111;
            line-height: 1.2;
        }

        /* ── DROPDOWN ── */
        .dropdown { position: relative; }
        .dropdown-menu {
            display: none;
            position: absolute;
            right: 0; top: 100%;
            background: white;
            border-radius: 12px;
            box-shadow:
                0 8px 24px rgba(0,0,0,0.1),
                0 2px 8px rgba(107,0,128,0.08);
            border: 1px solid rgba(107,0,128,0.08);
            min-width: 150px;
            z-index: 50;
            padding: 6px;
            margin-top: 6px;
        }
        .dropdown.open .dropdown-menu { display: block; }
        .dropdown-item {
            display: block;
            padding: 8px 12px;
            font-size: 13px;
            color: #374151;
            border-radius: 8px;
            text-decoration: none;
            cursor: pointer;
            transition: background 0.15s;
        }
        .dropdown-item:hover { background: #f7f0ff; color: #6B0080; }
        .dropdown-item.danger { color: #dc2626; }
        .dropdown-item.danger:hover { background: #fef2f2; color: #dc2626; }

        /* ── TAB ── */
        .tab-btn {
            padding: 7px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            color: #6b7280;
            cursor: pointer;
            transition: all 0.15s;
            border: none; background: none;
        }
        .tab-btn.active {
            background: #f7f0ff;
            color: var(--purple);
            box-shadow: inset 0 0 0 1px rgba(107,0,128,0.12);
        }
        .tab-btn:hover:not(.active) { background: #f3f4f6; color: #374151; }

        /* ── PAGE HEADER ── */
        .page-header {
            font-size: 22px;
            font-weight: 800;
            color: #1a0020;
            letter-spacing: -0.02em;
            margin-bottom: 6px;
        }
        .page-subheader {
            font-size: 13px;
            color: #9ca3af;
            margin-bottom: 24px;
        }
    </style>
</head>
<body>

<!-- SIDEBAR -->
<aside class="sidebar">
    <!-- Brand -->
    <div class="sidebar-brand">
        <img src="{{ asset('img/logo.png') }}" class="h-8" alt="logo">
        <span class="text-white font-bold text-lg">SIMETIX</span>
    </div>

    <!-- Nav -->
    <nav class="sidebar-nav">
        <div class="nav-label">Overview</div>

        <a href="{{ route('panitia.dashboard') }}"
           class="nav-item {{ request()->routeIs('panitia.dashboard') ? 'active' : '' }}">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            Dashboard
        </a>

        <a href="{{ route('panitia.events.index') }}"
           class="nav-item {{ request()->routeIs('panitia.events*') ? 'active' : '' }}">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            Event
        </a>

        <a href="{{ route('panitia.report_peserta') }}"
            class="nav-item {{ request()->routeIs('panitia.report_peserta') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width:20px; height:20px">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Report Peserta
        </a>

        <a href="#" class="nav-item">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            Settings
        </a>

    <!-- User footer -->
    <div class="sidebar-footer">
        <div class="user-chip">
            <div class="avatar">{{ strtoupper(substr(auth()->user()->name ?? 'P', 0, 1)) }}</div>
            <div style="overflow:hidden">
                <p class="text-white text-xs font-semibold truncate">{{ auth()->user()->name ?? 'Panitia' }}</p>
                <p style="color:rgba(255,255,255,0.45); font-size:11px" class="truncate">{{ auth()->user()->organization ?? '' }}</p>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}" class="mt-3">
            @csrf
            <button type="submit"
                    class="w-full text-left text-xs py-1.5 px-2 rounded-lg text-red-300 hover:bg-red-900/30 hover:text-red-200 transition flex items-center gap-2">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Sign Out
            </button>
        </form>
    </div>
</aside>

<!-- TOPBAR -->
<div class="topbar">
    <span class="text-white font-semibold text-sm">@yield('page-title', 'Dashboard')</span>
</div>

<!-- CONTENT -->
<main class="content">
    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm mb-5 flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        {{ session('success') }}
    </div>
    @endif

    @yield('content')
</main>

<script>
    // Dropdown toggle
    document.addEventListener('click', function(e) {
        document.querySelectorAll('.dropdown.open').forEach(d => {
            if (!d.contains(e.target)) d.classList.remove('open');
        });
        const btn = e.target.closest('[data-dropdown]');
        if (btn) {
            const dd = btn.closest('.dropdown');
            dd.classList.toggle('open');
        }
    });
</script>

@stack('scripts')
</body>
</html>
