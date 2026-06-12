<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') - SIMETIX Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* ── Stat Cards ── */
        .db-cards {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 24px
        }

        @media(max-width:900px) {
            .db-cards {
                grid-template-columns: repeat(2, 1fr)
            }
        }

        .db-card {
            background: #fff;
            border-radius: 14px;
            padding: 20px 20px 16px;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            box-shadow: 0 1px 4px rgba(0, 0, 0, .06)
        }

        .db-card-info p.label {
            font-size: 10px;
            font-weight: 700;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: .07em;
            margin: 0
        }

        .db-card-info p.val {
            font-size: 26px;
            font-weight: 800;
            color: #111827;
            margin: 4px 0 2px;
            line-height: 1
        }

        .db-card-info p.sub {
            font-size: 12px;
            color: #22c55e;
            font-weight: 600;
            margin: 0
        }

        .db-card-icon {
            width: 42px;
            height: 42px;
            border-radius: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0
        }

        /* ── Main Grid ── */
        .db-main {
            display: grid;
            grid-template-columns: 1fr 300px;
            gap: 20px
        }

        @media(max-width:1000px) {
            .db-main {
                grid-template-columns: 1fr
            }
        }

        /* ── Chart Card ── */
        .db-chart-card {
            background: #fff;
            border-radius: 14px;
            padding: 22px 22px 16px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, .06)
        }

        .db-chart-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px
        }

        .db-chart-header h2 {
            font-size: 15px;
            font-weight: 700;
            color: #111827;
            margin: 0
        }

        .db-chart-period {
            font-size: 12px;
            font-weight: 600;
            color: #6b7280;
            background: #f3f4f6;
            padding: 5px 12px;
            border-radius: 20px;
            border: none;
            cursor: pointer
        }

        .db-legend {
            display: flex;
            gap: 16px;
            margin-bottom: 10px
        }

        .db-legend-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: #6b7280;
            font-weight: 500
        }

        .db-legend-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%
        }

        .db-legend-rect {
            width: 14px;
            height: 10px;
            border-radius: 3px;
            opacity: .5
        }

        /* ── Sub Stats ── */
        .db-sub-stats {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-top: 16px
        }

        .db-sub-stat {
            background: #f9fafb;
            border-radius: 10px;
            padding: 14px 16px
        }

        .db-sub-stat p.label {
            font-size: 10px;
            font-weight: 700;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: .06em;
            margin: 0
        }

        .db-sub-stat p.val {
            font-size: 20px;
            font-weight: 800;
            color: #111827;
            margin: 4px 0 2px
        }

        .db-sub-stat p.sub {
            font-size: 11px;
            color: #22c55e;
            font-weight: 600;
            margin: 0
        }

        /* ── Right Panel ── */
        .db-right {
            display: flex;
            flex-direction: column;
            gap: 16px
        }

        .db-panel {
            background: #fff;
            border-radius: 14px;
            padding: 20px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, .06)
        }

        .db-panel h2 {
            font-size: 15px;
            font-weight: 700;
            color: #111827;
            margin: 0 0 14px
        }

        /* Role rows */
        .role-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 9px 0;
            border-bottom: 1px solid #f3f4f6
        }

        .role-row:last-child {
            border-bottom: none;
            padding-bottom: 0
        }

        .role-dot {
            width: 9px;
            height: 9px;
            border-radius: 50%;
            margin-right: 8px
        }

        .role-label {
            display: flex;
            align-items: center;
            font-size: 13px;
            color: #374151;
            font-weight: 500
        }

        .role-badge {
            font-size: 12px;
            font-weight: 700;
            padding: 2px 12px;
            border-radius: 20px
        }

        /* Quick actions */
        .qa-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 14px;
            border-radius: 10px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 8px
        }

        .qa-btn:last-child {
            margin-bottom: 0
        }

        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        * {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        :root {
            --p: #6B0080;
            --p2: #8a00a8;
            --pp: #f5eeff;
            --pm: #e2c8f0;
            --ok: #059669;
            --ok-bg: #ecfdf5;
            --wn: #d97706;
            --wn-bg: #fffbeb;
            --er: #dc2626;
            --er-bg: #fef2f2;
            --bl: #2563eb;
            --bl-bg: #eff6ff;
            --g50: #f9fafb;
            --g100: #f3f4f6;
            --g200: #e5e7eb;
            --g300: #d1d5db;
            --g400: #9ca3af;
            --g500: #6b7280;
            --g700: #374151;
            --g900: #111827;
            --r: 12px;
            --sh: 0 1px 3px rgba(0, 0, 0, .06), 0 1px 2px rgba(0, 0, 0, .04);
            --shm: 0 4px 16px rgba(107, 0, 128, .09), 0 1px 4px rgba(0, 0, 0, .05)
        }

        * {
            box-sizing: border-box
        }

        .tx-head {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 26px
        }

        .tx-head h1 {
            font-size: 21px;
            font-weight: 800;
            color: var(--g900);
            margin: 0 0 3px;
            letter-spacing: -.4px
        }

        .tx-head p {
            font-size: 13px;
            color: var(--g400);
            margin: 0
        }

        .btn-row {
            display: flex;
            gap: 8px;
            flex-wrap: wrap
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            font-family: inherit;
            border: none;
            transition: all .15s;
            white-space: nowrap
        }

        .btn-primary {
            background: var(--p);
            color: #fff;
            box-shadow: 0 2px 8px rgba(107, 0, 128, .22)
        }

        .btn-primary:hover {
            background: var(--p2);
            transform: translateY(-1px)
        }

        .btn-outline {
            background: #fff;
            color: var(--p);
            border: 1.5px solid var(--pm);
            box-shadow: var(--sh)
        }

        .btn-outline:hover {
            background: var(--pp);
            border-color: var(--p)
        }

        /* STAT CARDS */
        .stat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 22px
        }

        .sc {
            background: #fff;
            border-radius: var(--r);
            padding: 20px 22px;
            box-shadow: var(--sh);
            border: 1px solid var(--g100);
            position: relative;
            overflow: hidden;
            transition: transform .15s, box-shadow .15s
        }

        .sc:hover {
            transform: translateY(-2px);
            box-shadow: var(--shm)
        }

        .sc::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            border-radius: var(--r) var(--r) 0 0
        }

        .sc.c-p::before {
            background: linear-gradient(90deg, var(--p), #c84de0)
        }

        .sc.c-g::before {
            background: linear-gradient(90deg, var(--ok), #34d399)
        }

        .sc.c-b::before {
            background: linear-gradient(90deg, var(--bl), #60a5fa)
        }

        .sc.c-o::before {
            background: linear-gradient(90deg, #059669, #6ee7b7)
        }

        .sc-lbl {
            font-size: 10.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .6px;
            color: var(--g400);
            margin-bottom: 8px
        }

        .sc-val {
            font-size: 22px;
            font-weight: 800;
            letter-spacing: -.7px;
            color: var(--g900);
            line-height: 1
        }

        .sc-sub {
            font-size: 11.5px;
            color: var(--g400);
            margin-top: 5px
        }

        .sc-ico {
            position: absolute;
            right: 18px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 28px;
            opacity: .1
        }

        .split {
            display: flex;
            gap: 16px;
            margin-top: 6px;
            flex-wrap: wrap
        }

        .sp-item {
            display: flex;
            flex-direction: column;
            gap: 2px
        }

        .sp-num {
            font-size: 19px;
            font-weight: 800;
            letter-spacing: -.5px
        }

        .sp-lbl {
            font-size: 10px;
            color: var(--g400);
            font-weight: 700;
            letter-spacing: .3px
        }

        .clr-g {
            color: var(--ok)
        }

        .clr-r {
            color: var(--er)
        }

        .clr-w {
            color: var(--wn)
        }

        /* FILTER */
        .fbar {
            background: #fff;
            border-radius: var(--r);
            padding: 16px 20px;
            box-shadow: var(--sh);
            border: 1px solid var(--g100);
            margin-bottom: 20px
        }

        .fbar-inner {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            align-items: flex-end
        }

        .fg {
            display: flex;
            flex-direction: column;
            gap: 5px;
            flex: 1;
            min-width: 140px
        }

        .fg label {
            font-size: 10.5px;
            font-weight: 700;
            color: var(--g500);
            text-transform: uppercase;
            letter-spacing: .5px
        }

        .fg input,
        .fg select {
            padding: 8px 11px;
            border: 1.5px solid var(--g200);
            border-radius: 8px;
            font-size: 13px;
            font-family: inherit;
            color: var(--g700);
            background: var(--g50);
            outline: none;
            width: 100%;
            transition: border-color .15s, box-shadow .15s
        }

        .fg input:focus,
        .fg select:focus {
            border-color: var(--p);
            box-shadow: 0 0 0 3px rgba(107, 0, 128, .07);
            background: #fff
        }

        .fg.fw {
            flex: 2;
            min-width: 210px
        }

        .sw {
            position: relative
        }

        .sw-ico {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--g400);
            font-size: 13px;
            pointer-events: none
        }

        .sw input {
            padding-left: 32px
        }

        .btn-f {
            padding: 8px 18px;
            background: var(--p);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            font-family: inherit;
            transition: background .15s;
            white-space: nowrap;
            align-self: flex-end
        }

        .btn-f:hover {
            background: var(--p2)
        }

        .btn-rst {
            padding: 8px 13px;
            background: #fff;
            color: var(--g500);
            border: 1.5px solid var(--g200);
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            font-family: inherit;
            transition: all .15s;
            white-space: nowrap;
            align-self: flex-end;
            text-decoration: none;
            display: inline-flex;
            align-items: center
        }

        .btn-rst:hover {
            border-color: var(--g400);
            color: var(--g700)
        }

        /* TABLE */
        .tcard {
            background: #fff;
            border-radius: var(--r);
            box-shadow: var(--sh);
            border: 1px solid var(--g100);
            overflow: hidden
        }

        .tcard-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 20px;
            border-bottom: 1px solid var(--g100);
            gap: 12px;
            flex-wrap: wrap
        }

        .tc-title {
            font-size: 14px;
            font-weight: 700;
            color: var(--g700)
        }

        .tc-count {
            font-size: 12px;
            color: var(--g400);
            margin-top: 2px
        }

        .tscroll {
            overflow-x: auto
        }

        table.tt {
            width: 100%;
            border-collapse: collapse;
            min-width: 920px
        }

        table.tt thead tr {
            background: var(--g50)
        }

        table.tt th {
            text-align: left;
            padding: 10px 14px;
            font-size: 10.5px;
            font-weight: 700;
            color: var(--g400);
            text-transform: uppercase;
            letter-spacing: .6px;
            white-space: nowrap;
            border-bottom: 1px solid var(--g100)
        }

        table.tt td {
            padding: 12px 14px;
            font-size: 13px;
            color: var(--g700);
            border-bottom: 1px solid var(--g100);
            vertical-align: middle
        }

        table.tt tbody tr {
            transition: background .1s
        }

        table.tt tbody tr:hover {
            background: var(--g50)
        }

        table.tt tbody tr:last-child td {
            border-bottom: none
        }

        .tx-id {
            font-family: 'Courier New', monospace;
            font-size: 11.5px;
            color: var(--p);
            font-weight: 700;
            white-space: nowrap
        }

        .tx-ref {
            font-size: 10.5px;
            color: var(--g400);
            margin-top: 2px
        }

        .by-name {
            font-weight: 700;
            color: var(--g900);
            font-size: 13px
        }

        .by-ct {
            font-size: 11.5px;
            color: var(--g400);
            margin-top: 2px
        }

        .ev-name {
            font-weight: 600;
            color: var(--g900);
            font-size: 13px
        }

        .ev-org {
            font-size: 11px;
            color: var(--g400);
            margin-top: 2px
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 9px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            white-space: nowrap
        }

        .b-bank {
            background: #eff6ff;
            color: #1d4ed8
        }

        .b-ew {
            background: #fdf4ff;
            color: #7e22ce
        }

        .b-cc {
            background: #f0fdf4;
            color: #15803d
        }

        .b-man {
            background: #fffbeb;
            color: #92400e
        }

        .b-def {
            background: var(--g100);
            color: var(--g500)
        }

        .sbadge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11.5px;
            font-weight: 700;
            white-space: nowrap
        }

        .sbadge .dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            flex-shrink: 0
        }

        .sb-ok {
            background: var(--ok-bg);
            color: var(--ok)
        }

        .sb-ok .dot {
            background: var(--ok)
        }

        .sb-pn {
            background: var(--wn-bg);
            color: var(--wn)
        }

        .sb-pn .dot {
            background: var(--wn);
            animation: blink 1.4s infinite
        }

        .sb-ca {
            background: var(--er-bg);
            color: var(--er)
        }

        .sb-ca .dot {
            background: var(--er)
        }

        @keyframes blink {

            0%,
            100% {
                opacity: 1
            }

            50% {
                opacity: .3
            }
        }

        .tm-main {
            font-size: 12.5px;
            font-weight: 600;
            color: var(--g700)
        }

        .tm-sub {
            font-size: 11px;
            color: var(--g400);
            margin-top: 2px
        }

        .tx-amt {
            font-family: 'Courier New', monospace;
            font-size: 13px;
            font-weight: 700;
            color: var(--p);
            white-space: nowrap;
            text-align: right
        }

        .tx-fee {
            font-size: 11px;
            color: var(--g400);
            margin-top: 2px;
            text-align: right
        }

        .btn-sm {
            padding: 4px 10px;
            border-radius: 7px;
            border: 1.5px solid var(--g200);
            background: #fff;
            font-size: 11px;
            font-weight: 700;
            color: var(--g500);
            cursor: pointer;
            font-family: inherit;
            transition: all .12s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 4px
        }

        .btn-sm:hover {
            border-color: var(--p);
            color: var(--p);
            background: var(--pp)
        }

        /* RECONCILE */
        .recon {
            background: #fff;
            border-radius: var(--r);
            box-shadow: var(--sh);
            border: 1px solid var(--g100);
            margin-top: 22px;
            overflow: hidden
        }

        .recon-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 20px;
            border-bottom: 1px solid var(--g100);
            gap: 12px;
            flex-wrap: wrap
        }

        .rh-title {
            font-size: 14px;
            font-weight: 800;
            color: var(--g900)
        }

        .rh-sub {
            font-size: 12px;
            color: var(--g400);
            margin-top: 2px
        }

        .prow {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr))
        }

        .pi {
            padding: 20px 22px;
            border-right: 1px solid var(--g100)
        }

        .pi:last-child {
            border-right: none
        }

        .pi-lbl {
            font-size: 10.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .6px;
            color: var(--g400);
            margin-bottom: 6px
        }

        .pi-val {
            font-size: 21px;
            font-weight: 800;
            letter-spacing: -.5px
        }

        .pi-sub {
            font-size: 11.5px;
            color: var(--g400);
            margin-top: 4px
        }

        .pi-val.cp {
            color: var(--p)
        }

        .pi-val.cg {
            color: var(--ok)
        }

        .pi-val.cb {
            color: var(--bl)
        }

        .split-sm {
            display: flex;
            gap: 14px;
            flex-wrap: wrap;
            margin-top: 4px
        }

        .ss-item .n {
            font-size: 19px;
            font-weight: 800
        }

        .ss-item .l {
            font-size: 10.5px;
            color: var(--g400);
            font-weight: 700
        }

        /* EMPTY */
        .empty {
            text-align: center;
            padding: 80px 24px
        }

        .empty .ei {
            font-size: 52px
        }

        .empty .et {
            font-size: 16px;
            font-weight: 800;
            color: var(--g700);
            margin: 14px 0 6px
        }

        .empty .es {
            font-size: 13px;
            color: var(--g400)
        }

        /* PAGINATION */
        .pg-wrap {
            padding: 14px 20px;
            border-top: 1px solid var(--g100)
        }

        /* MODAL */
        .modal-ov {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .55);
            z-index: 9999;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(4px)
        }

        .modal-ov.open {
            display: flex
        }

        .modal-box {
            background: #fff;
            border-radius: 16px;
            padding: 24px;
            max-width: 520px;
            width: 94%;
            box-shadow: 0 20px 60px rgba(0, 0, 0, .2);
            position: relative;
            animation: m-in .2s ease
        }

        @keyframes m-in {
            from {
                opacity: 0;
                transform: scale(.94) translateY(10px)
            }

            to {
                opacity: 1;
                transform: none
            }
        }

        .modal-x {
            position: absolute;
            top: 14px;
            right: 14px;
            background: var(--g100);
            border: none;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            cursor: pointer;
            font-size: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--g500);
            transition: background .12s
        }

        .modal-x:hover {
            background: var(--g200)
        }

        .modal-title {
            font-size: 16px;
            font-weight: 800;
            color: var(--g900);
            margin-bottom: 3px
        }

        .modal-sub {
            font-size: 12px;
            color: var(--g400);
            margin-bottom: 16px
        }

        .modal-img {
            width: 100%;
            border-radius: 10px;
            border: 1px solid var(--g200);
            max-height: 400px;
            object-fit: contain;
            background: var(--g50)
        }

        @media(max-width:640px) {
            .stat-grid {
                grid-template-columns: 1fr 1fr
            }

            .tx-head {
                flex-direction: column;
                align-items: flex-start
            }
        }


        :root {
            --sidebar-w: 210px;
            --navy: #0f172a;
            --navy-light: #1e293b;
            --accent: #6B0080;
            --accent-light: #f5eeff;
        }

        body {
            background: #f1f5f9;
        }

        .sidebar {
            width: var(--sidebar-w);
            background: var(--navy);
            min-height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            display: flex;
            flex-direction: column;
            z-index: 40;
        }

        .sidebar-brand {
            padding: 18px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
            display: flex;
            align-items: center;
            gap: 10px;
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

        .sidebar-nav {
            padding: 14px 10px;
            flex: 1;
        }

        .nav-label {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: .1em;
            color: rgba(255, 255, 255, .3);
            padding: 0 10px;
            margin: 12px 0 5px;
            text-transform: uppercase;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px;
            border-radius: 8px;
            color: rgba(255, 255, 255, .6);
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            margin-bottom: 2px;
            transition: all .15s;
        }

        .nav-item:hover {
            background: rgba(255, 255, 255, .07);
            color: white;
        }

        .nav-item.active {
            background: #6B0080;
            color: white;
            font-weight: 600;
        }

        .nav-item svg {
            width: 15px;
            height: 15px;
            flex-shrink: 0;
        }

        .sidebar-footer {
            padding: 14px 16px;
            border-top: 1px solid rgba(255, 255, 255, .06);
        }

        .topbar {
            margin-left: var(--sidebar-w);
            background: white;
            height: 56px;
            display: flex;
            align-items: center;
            padding: 0 28px;
            position: sticky;
            top: 0;
            z-index: 30;
            border-bottom: 1px solid #e2e8f0;
            gap: 10px;
        }

        .content {
            margin-left: var(--sidebar-w);
            padding: 28px;
            min-height: calc(100vh - 56px);
        }

        /* Dropdown */
        .dropdown {
            position: relative;
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            right: 0;
            top: 100%;
            background: white;
            border-radius: 10px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, .12);
            min-width: 150px;
            z-index: 50;
            padding: 6px;
            margin-top: 4px;
        }

        .dropdown.open .dropdown-menu {
            display: block;
        }

        .dropdown-item {
            display: block;
            padding: 8px 12px;
            font-size: 13px;
            color: #374151;
            border-radius: 6px;
            text-decoration: none;
            cursor: pointer;
        }

        .dropdown-item:hover {
            background: #f3f4f6;
        }

        .dropdown-item.danger {
            color: #dc2626;
        }

        .dropdown-item.danger:hover {
            background: #fef2f2;
        }

        /* Tab */
        .tab-btn {
            padding: 7px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            color: #6b7280;
            cursor: pointer;
            transition: all .15s;
            border: none;
            background: none;
        }

        .tab-btn.active {
            background: var(--accent-light);
            color: var(--accent);
        }

        .tab-btn:hover:not(.active) {
            background: #f3f4f6;
        }
    </style>
    @stack('styles')
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
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Dashboard
            </a>

            <div class="nav-label">Manajemen</div>

            <a href="{{ route('admin.users.index') }}"
                class="nav-item {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                Pengguna
            </a>

            <a href="{{ route('admin.events.index') }}"
                class="nav-item {{ request()->routeIs('admin.events*') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Semua Event
            </a>

            <a href="{{ route('admin.transactions.index') }}"
                class="nav-item {{ request()->routeIs('admin.transactions*') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                Transaksi
            </a>

            <div class="nav-label">Sistem</div>

            <a href="{{ route('admin.settings') }}"
                class="nav-item {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Pengaturan
            </a>

                    </nav>

        <div class="sidebar-footer">
            <div style="display:flex;align-items:center;gap:10px">
                <div
                    style="width:32px;height:32px;border-radius:50%;background:#6B0080;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:13px;color:white;flex-shrink:0">
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
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

        </div>
    </div>

    <!-- CONTENT -->
    <main class="content">
        @if(session('success'))
            <div
                style="background:#f0fdf4;border:1px solid #bbf7d0;color:#16a34a;padding:12px 16px;border-radius:10px;font-size:13px;margin-bottom:20px;display:flex;align-items:center;gap:8px">
                <svg style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div
                style="background:#fef2f2;border:1px solid #fecaca;color:#dc2626;padding:12px 16px;border-radius:10px;font-size:13px;margin-bottom:20px">
                ⚠️ {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

    <script>
        document.addEventListener('click', function (e) {
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