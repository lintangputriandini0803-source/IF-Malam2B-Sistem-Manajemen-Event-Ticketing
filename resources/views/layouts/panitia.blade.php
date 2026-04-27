<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - SIMETIX Panitia</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        * { font-family: 'Plus Jakarta Sans', sans-serif; }

        :root {
            --sidebar-w: 200px;
            --purple: #6B0080;
            --purple-dark: #4a005a;
            --purple-light: #f5eeff;
            --accent: #22c55e;
        }

        body { background: #f4f4f6; }

        /* SIDEBAR */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--purple-dark);
            min-height: 100vh;
            position: fixed;
            top: 0; left: 0;
            display: flex;
            flex-direction: column;
            z-index: 40;
        }

        .sidebar-brand {
            padding: 18px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar-nav { padding: 16px 12px; flex: 1; }

        .nav-label {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.1em;
            color: rgba(255,255,255,0.35);
            padding: 0 8px;
            margin-bottom: 6px;
            text-transform: uppercase;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px;
            border-radius: 8px;
            color: rgba(255,255,255,0.65);
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            margin-bottom: 2px;
            transition: all 0.15s;
        }

        .nav-item:hover { background: rgba(255,255,255,0.08); color: white; }
        .nav-item.active { background: var(--purple); color: white; font-weight: 600; }
        .nav-item svg { width: 16px; height: 16px; flex-shrink: 0; }

        .sidebar-footer {
            padding: 14px 16px;
            border-top: 1px solid rgba(255,255,255,0.08);
        }

        .user-chip {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .avatar {
            width: 32px; height: 32px;
            border-radius: 50%;
            background: var(--purple);
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 13px; color: white;
            flex-shrink: 0;
        }

        /* TOPBAR */
        .topbar {
            margin-left: var(--sidebar-w);
            background: #4a005a;
            height: 54px;
            display: flex;
            align-items: center;
            padding: 0 28px;
            position: sticky;
            top: 0;
            z-index: 30;
            gap: 10px;
        }

        /* CONTENT */
        .content {
            margin-left: var(--sidebar-w);
            padding: 28px;
            min-height: calc(100vh - 54px);
        }

        /* STAT CARD */
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
        }

        .stat-icon {
            width: 40px; height: 40px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
        }

        /* EVENT ROW */
        .event-row {
            background: white;
            border-radius: 12px;
            padding: 16px 20px;
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 10px;
            transition: box-shadow 0.15s;
        }
        .event-row:hover { box-shadow: 0 4px 16px rgba(0,0,0,0.08); }

        .event-thumb {
            width: 100px; height: 70px;
            border-radius: 10px;
            object-fit: cover;
            flex-shrink: 0;
            background: #e5e7eb;
            position: relative;
            overflow: hidden;
        }

        .badge-status {
            position: absolute;
            top: 6px; left: 6px;
            font-size: 9px;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 4px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .badge-draft { background: #374151; color: white; }
        .badge-published { background: #16a34a; color: white; }
        .badge-cancelled { background: #dc2626; color: white; }

        .date-badge {
            position: absolute;
            bottom: 6px; left: 6px;
            background: white;
            border-radius: 6px;
            padding: 2px 6px;
            font-size: 10px;
            font-weight: 700;
            color: #111;
            text-align: center;
            line-height: 1.2;
        }

        /* DROPDOWN */
        .dropdown { position: relative; }
        .dropdown-menu {
            display: none;
            position: absolute;
            right: 0; top: 100%;
            background: white;
            border-radius: 10px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.12);
            min-width: 150px;
            z-index: 50;
            padding: 6px;
            margin-top: 4px;
        }
        .dropdown.open .dropdown-menu { display: block; }
        .dropdown-item {
            display: block;
            padding: 8px 12px;
            font-size: 13px;
            color: #374151;
            border-radius: 6px;
            text-decoration: none;
            cursor: pointer;
        }
        .dropdown-item:hover { background: #f3f4f6; }
        .dropdown-item.danger { color: #dc2626; }
        .dropdown-item.danger:hover { background: #fef2f2; }

        /* TAB */
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
        .tab-btn.active { background: var(--purple-light); color: var(--purple); }
        .tab-btn:hover:not(.active) { background: #f3f4f6; }
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

        <a href="#" class="nav-item">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            Settings
        </a>

        <a href="{{ route('home') }}" class="nav-item">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9"/>
            </svg>
            Lihat Website
        </a>
    </nav>

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
