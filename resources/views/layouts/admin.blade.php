<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') - SIMETIX Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        * { font-family: 'Plus Jakarta Sans', sans-serif; }

        :root {
            --sidebar-w: 210px;
            --navy: #0f172a;
            --navy-light: #1e293b;
            --accent: #6B0080;
            --accent-light: #f5eeff;
        }

        body { background: #f1f5f9; }

        .sidebar {
            width: var(--sidebar-w);
            background: var(--navy);
            min-height: 100vh;
            position: fixed;
            top: 0; left: 0;
            display: flex;
            flex-direction: column;
            z-index: 40;
        }

        .sidebar-brand {
            padding: 18px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.06);
            display: flex; align-items: center; gap: 10px;
        }

        .admin-badge {
            font-size: 9px;
            font-weight: 800;
            letter-spacing: .1em;
            color: white;
            background: #6B0080;
            padding: 2px 7px;
            border-radius: 4px;
            text-transform: uppercase;
        }

        .sidebar-nav { padding: 14px 10px; flex: 1; }

        .nav-label {
            font-size: 10px; font-weight: 700;
            letter-spacing: .1em; color: rgba(255,255,255,.3);
            padding: 0 10px; margin: 12px 0 5px;
            text-transform: uppercase;
        }

        .nav-item {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 12px; border-radius: 8px;
            color: rgba(255,255,255,.6);
            font-size: 13px; font-weight: 500;
            text-decoration: none; margin-bottom: 2px;
            transition: all .15s;
        }
        .nav-item:hover { background: rgba(255,255,255,.07); color: white; }
        .nav-item.active { background: #6B0080; color: white; font-weight: 600; }
        .nav-item svg { width: 15px; height: 15px; flex-shrink: 0; }

        .sidebar-footer {
            padding: 14px 16px;
            border-top: 1px solid rgba(255,255,255,.06);
        }

        .topbar {
            margin-left: var(--sidebar-w);
            background: white;
            height: 56px;
            display: flex; align-items: center;
            padding: 0 28px;
            position: sticky; top: 0; z-index: 30;
            border-bottom: 1px solid #e2e8f0;
            gap: 10px;
        }

        .content {
            margin-left: var(--sidebar-w);
            padding: 28px;
            min-height: calc(100vh - 56px);
        }

        /* Dropdown */
        .dropdown { position: relative; }
        .dropdown-menu {
            display: none;
            position: absolute; right: 0; top: 100%;
            background: white; border-radius: 10px;
            box-shadow: 0 8px 24px rgba(0,0,0,.12);
            min-width: 150px; z-index: 50;
            padding: 6px; margin-top: 4px;
        }
        .dropdown.open .dropdown-menu { display: block; }
        .dropdown-item {
            display: block; padding: 8px 12px;
            font-size: 13px; color: #374151;
            border-radius: 6px; text-decoration: none; cursor: pointer;
        }
        .dropdown-item:hover { background: #f3f4f6; }
        .dropdown-item.danger { color: #dc2626; }
        .dropdown-item.danger:hover { background: #fef2f2; }

        /* Tab */
        .tab-btn {
            padding: 7px 16px; border-radius: 8px;
            font-size: 13px; font-weight: 600; color: #6b7280;
            cursor: pointer; transition: all .15s;
            border: none; background: none;
        }
        .tab-btn.active { background: var(--accent-light); color: var(--accent); }
        .tab-btn:hover:not(.active) { background: #f3f4f6; }
    </style>
</head>
<body>

<!-- SIDEBAR -->
<aside class="sidebar">
    <div class="sidebar-brand">
        <img src="{{ asset('img/logo.png') }}" class="h-7" alt="logo">
        <div>
            <span class="text-white font-bold text-base">SIMETIX</span>
            <div class="mt-0.5"><span class="admin-badge">Admin</span></div>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-label">Overview</div>

        <a href="{{ route('admin.dashboard') }}"
           class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            Dashboard
        </a>

        <div class="nav-label">Manajemen</div>

        <a href="{{ route('admin.users.index') }}"
           class="nav-item {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            Pengguna
        </a>

        <a href="{{ route('admin.events.index') }}"
           class="nav-item {{ request()->routeIs('admin.events*') ? 'active' : '' }}">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            Semua Event
        </a>

        <a href="{{ route('admin.transactions.index') }}"
           class="nav-item {{ request()->routeIs('admin.transactions*') ? 'active' : '' }}">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            Transaksi
        </a>

        <div class="nav-label">Sistem</div>

        <a href="{{ route('admin.settings') }}"
           class="nav-item {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            Pengaturan
        </a>

        <a href="{{ route('home') }}" class="nav-item">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9"/>
            </svg>
            Lihat Website
        </a>
    </nav>

    <div class="sidebar-footer">
        <div style="display:flex;align-items:center;gap:10px">
            <div style="width:32px;height:32px;border-radius:50%;background:#6B0080;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:13px;color:white;flex-shrink:0">
                {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
            </div>
            <div style="overflow:hidden">
                <p class="text-white text-xs font-semibold truncate">{{ auth()->user()->name ?? 'Admin' }}</p>
                <p style="color:rgba(255,255,255,.4);font-size:11px" class="truncate">Super Admin</p>
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
    <div style="flex:1">
        <span style="font-size:15px;font-weight:700;color:#0f172a">@yield('page-title', 'Dashboard')</span>
        <span style="font-size:12px;color:#9ca3af;margin-left:8px">@yield('page-sub', '')</span>
    </div>
    <div style="display:flex;align-items:center;gap:10px">
        {{-- NOTIFICATION BELL --}}

    </div>
</div>

<!-- CONTENT -->
<main class="content">
    @if(session('success'))
    <div style="background:#f0fdf4;border:1px solid #bbf7d0;color:#16a34a;padding:12px 16px;border-radius:10px;font-size:13px;margin-bottom:20px;display:flex;align-items:center;gap:8px">
        <svg style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div style="background:#fef2f2;border:1px solid #fecaca;color:#dc2626;padding:12px 16px;border-radius:10px;font-size:13px;margin-bottom:20px">
        ⚠️ {{ session('error') }}
    </div>
    @endif

    @yield('content')
</main>

<script>
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
