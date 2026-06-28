@extends('layouts.admin')
@section('title', 'Transaksi')
@section('page-title', 'Transaksi')
@section('page-sub')

@section('content')
    @push('styles')
    @endpush

    <div>

        {{-- PAGE HEADER --}}
        <div class="tx-head">
            <div>
                <h1>Semua Transaksi</h1>

            </div>
            <div class="btn-row">
                <a href="{{ route('admin.transactions.export', request()->query()) }}" class="btn btn-outline">
                    <svg style="width:14px;height:14px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Export Data
                </a>
                <a href="{{ route('admin.transactions.export-panitia', request()->query()) }}" class="btn btn-outline">
                    <svg style="width:14px;height:14px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Export Laporan Panitia
                </a>
            </div>
        </div>

        {{-- STAT CARDS --}}
        <div class="stat-grid">
            <div class="sc c-p">
                <div class="sc-ico">💰</div>
                <div class="sc-lbl">Total Akumulasi</div>
                <div class="sc-val" style="font-size:18px">Rp{{ number_format($totalGMV, 0, ',', '.') }}</div>
                <div class="sc-sub">Akumulasi nilai transaksi </div>
            </div>
            <div class="sc c-g">
                <div class="sc-ico">📊</div>
                <div class="sc-lbl">Status Transaksi</div>
                <div class="split">
                    <div class="sp-item"><span class="sp-num clr-g">{{ $totalSuccess }}</span><span
                            class="sp-lbl">Berhasil</span></div>
                    <div class="sp-item"><span class="sp-num clr-w">{{ $totalPending }}</span><span
                            class="sp-lbl">Pending</span></div>
                    <div class="sp-item"><span class="sp-num clr-r">{{ $totalFailed }}</span><span
                            class="sp-lbl">Batal</span></div>
                </div>
                <div class="sc-sub" style="margin-top:8px">Total: {{ $totalSuccess + $totalPending + $totalFailed }} transaksi
                </div>
            </div>
            <div class="sc c-b">
               <div class="sc-ico">🏦</div>
<div class="sc-lbl">Revenue Platform (Fee 2,5%)</div>
<div class="sc-val" style="font-size:18px;color:var(--bl)">
    Rp{{ number_format($totalRevenue, 0, ',', '.') }}
</div>
<div class="sc-sub">
    2,5% dari Total GMV
</div>
            </div>
            <div class="sc c-o">
                <div class="sc-ico">🏷️</div>
                <div class="sc-lbl">Dana ke Panitia (Estimasi)</div>
                <div class="sc-val" style="font-size:18px;color:var(--ok)">Rp{{ number_format($totalPayout, 0, ',', '.') }}
                </div>
                <div class="sc-sub">Akumulasi dikurangi fee platform</div>
            </div>
        </div>

        {{-- FILTER BAR --}}
        <div class="fbar">
            <form method="GET" action="{{ route('admin.transactions.index') }}">
                <div class="fbar-inner">
                    <div class="fg fw">
                        <label>Cari Transaksi</label>
                        <div class="sw">
                            <span class="sw-ico">🔍</span>
                            <input type="text" name="search" placeholder="Nama, email, no. registrasi, order ref..."
                                value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="fg">
                        <label>Dari Tanggal</label>
                        <input type="date" name="date_from" value="{{ request('date_from') }}">
                    </div>
                    <div class="fg">
                        <label>Sampai Tanggal</label>
                        <input type="date" name="date_to" value="{{ request('date_to') }}">
                    </div>
                    <div class="fg">
                        <label>Status</label>
                        <select name="status">
                            <option value="">Semua Status</option>
                            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>✅ Berhasil</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>❌ Dibatalkan
                            </option>
                        </select>
                    </div>
                    <div class="fg">
                        <label>Filter Event</label>
                        <select name="event_id">
                            <option value="">Semua Event</option>
                            @foreach($events as $ev)
                                <option value="{{ $ev->id }}" {{ request('event_id') == $ev->id ? 'selected' : '' }}>
                                    {{ Str::limit($ev->title, 32) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn-f">Terapkan</button>
                    @if(request()->hasAny(['search', 'date_from', 'date_to', 'status', 'event_id']))
                        <a href="{{ route('admin.transactions.index') }}" class="btn-rst">Reset</a>
                    @endif
                </div>
            </form>
        </div>

        {{-- TABLE --}}
        <div class="tcard">
            <div class="tcard-head">
                <div>
                    <div class="tc-title">📋 Log Transaksi Detail</div>
                    <div class="tc-count">
                        @if(method_exists($transactions, 'total'))
                            {{ number_format($transactions->total()) }} transaksi ditemukan
                            @if($transactions->lastPage() > 1)· Hal
                            {{ $transactions->currentPage() }}/{{ $transactions->lastPage() }}@endif
                        @else
                            {{ $transactions->count() }} transaksi
                        @endif
                    </div>
                </div>
                <div style="display:flex;gap:6px;flex-wrap:wrap">
                    @php $today = now()->toDateString();
                        $week = now()->startOfWeek()->toDateString();
                    $month = now()->startOfMonth()->toDateString(); @endphp
                    <a href="{{ route('admin.transactions.index', array_merge(request()->except(['date_from', 'date_to']), ['date_from' => $today, 'date_to' => $today])) }}"
                        class="btn-sm">Hari ini</a>
                    <a href="{{ route('admin.transactions.index', array_merge(request()->except(['date_from', 'date_to']), ['date_from' => $week, 'date_to' => $today])) }}"
                        class="btn-sm">Minggu ini</a>
                    <a href="{{ route('admin.transactions.index', array_merge(request()->except(['date_from', 'date_to']), ['date_from' => $month, 'date_to' => $today])) }}"
                        class="btn-sm">Bulan ini</a>
                </div>
            </div>

            @if($transactions->isEmpty())
                <div class="empty">
                    <div class="ei">📭</div>
                    <div class="et">Tidak ada transaksi ditemukan</div>
                    <div class="es">Coba ubah filter atau rentang tanggal yang kamu gunakan.</div>
                </div>
            @else
                <div class="tscroll">
                    <table class="tt">
                        <thead>
                            <tr>
                                <th>No. Registrasi</th>
                                <th>Pembeli &amp; Kontak</th>
                                <th>Event &amp; Penyelenggara</th>
                                <th>Tipe Tiket</th>
                                <th>Metode</th>
                                <th>Status</th>
                                <th>Waktu</th>
                                <th style="text-align:right">Total</th>
                                <th>Bukti</th>
                                <th style="text-align:center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transactions as $tx)
                                            @php
                                                $ticketType = $tx->ticketType;
                                                $event = optional($ticketType)->event;
                                                $panitia = optional($event)->user;
                                                $status = $tx->status ?? 'pending';
                                                $payMethod = strtolower($tx->payment_method ?? '');

                                                [$sbClass, $sbLabel] = match ($status) {
                                                    'confirmed' => ['sb-ok', 'Berhasil'],
                                                    'pending' => ['sb-pn', 'Pending'],
                                                    'cancelled' => ['sb-ca', 'Dibatalkan'],
                                                    default => ['sb-pn', ucfirst($status)],
                                                };
                                                [$mClass, $mIcon, $mLabel] = match ($payMethod) {
                                                    default => ['b-def', '💳', $payMethod ? ucwords(str_replace('_', ' ', $payMethod)) : '—'],
                                                };
                                                $isOk = $status === 'confirmed';
                                                $fee = $isOk ? 2000 * $tx->quantity : 0;
                                            @endphp
                                            <tr>
                                                <td>
                                                    <div class="tx-id">{{ $tx->reg_number }}</div>
                                                    @if($tx->order_ref)
                                                    <div class="tx-ref">Ref: {{ $tx->order_ref }}</div>@endif
                                                </td>
                                                <td>
                                                    <div class="by-name">{{ $tx->name }}</div>
                                                    <div class="by-ct">{{ $tx->email }}</div>
                                                    @if($tx->phone)
                                                    <div class="by-ct">📱 {{ $tx->phone }}</div>@endif
                                                    @if($tx->nim)
                                                    <div class="by-ct">NIM: {{ $tx->nim }}</div>@endif
                                                </td>
                                                <td>
                                                    <div class="ev-name">{{ $event ? Str::limit($event->title, 30) : '—' }}</div>
                                                    <div class="ev-org">👤 {{ $panitia ? $panitia->name : '—' }}</div>
                                                </td>
                                                <td>
                                                    <div style="font-size:13px;font-weight:600;color:var(--g900)">
                                                        {{ optional($ticketType)->name ?? '—' }}</div>
                                                    <div style="font-size:11px;color:var(--g400);margin-top:2px">× {{ $tx->quantity }} tiket
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge {{ $mClass }}">{{ $mIcon }} {{ $mLabel }}</span>
                                                    @if($tx->virtual_account)
                                                        <div style="font-size:10.5px;color:var(--g400);margin-top:3px;font-family:monospace">VA:
                                                            {{ $tx->virtual_account }}</div>
                                                    @endif
                                                </td>
                                                <td><span class="sbadge {{ $sbClass }}"><span class="dot"></span>{{ $sbLabel }}</span></td>
                                                <td>
                                                    <div class="tm-main">{{ $tx->created_at->format('d M Y') }}</div>
                                                    <div class="tm-sub">{{ $tx->created_at->format('H:i') }} WIB</div>
                                                </td>
                                                <td>
                                                    <div class="tx-amt">Rp{{ number_format($tx->total_price, 0, ',', '.') }}</div>
                                                    @if($fee > 0)
                                                    <div class="tx-fee">Fee: Rp{{ number_format($fee, 0, ',', '.') }}</div>@endif
                                                </td>
                                                <td>
                                                    @php $proof = $tx->payment_proof ?? null; @endphp
                                                    @if($proof)
                                                        <a href="#" class="btn-sm"
                                                            onclick="openProof('{{ asset('storage/' . $proof) }}','{{ $tx->reg_number }}');return false;">🖼
                                                            Lihat</a>
                                                    @elseif($payMethod === 'manual' && $status === 'pending')
                                                        <span style="font-size:11px;color:var(--er);font-weight:600">Belum ada</span>
                                                    @else
                                                        <span style="font-size:12px;color:var(--g300)">—</span>
                                                    @endif
                                                </td>
                                                <td style="text-align:center">
                                                    <a href="#" class="btn-sm" onclick="showDetail({{ json_encode([
                                    'reg' => $tx->reg_number,
                                    'ref' => $tx->order_ref ?? '—',
                                    'name' => $tx->name,
                                    'email' => $tx->email,
                                    'phone' => $tx->phone ?? '—',
                                    'nim' => $tx->nim ?? '—',
                                    'event' => $event ? $event->title : '—',
                                    'org' => $panitia ? $panitia->name : '—',
                                    'ticket' => optional($ticketType)->name ?? '—',
                                    'qty' => $tx->quantity,
                                    'method' => $mLabel,
                                    'va' => $tx->virtual_account ?? '—',
                                    'total' => 'Rp' . number_format($tx->total_price, 0, ',', '.'),
                                    'fee' => 'Rp' . number_format($fee, 0, ',', '.'),
                                    'status' => $sbLabel,
                                    'date' => $tx->created_at->format('d M Y H:i'),
                                ]) }});return false;">Detail</a>
                                                </td>
                                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if(method_exists($transactions, 'hasPages') && $transactions->hasPages())
                    <div class="pg-wrap">{{ $transactions->withQueryString()->links() }}</div>
                @endif
            @endif
        </div>

        {{-- REKONSILIASI --}}
        <div class="recon">
            <div class="recon-head">
                <div>
                    <div class="rh-title">💼 Laporan Rekonsiliasi &amp; Pencairan (Payout)</div>
                    <div class="rh-sub">Ringkasan keuangan berdasarkan filter aktif — untuk audit &amp; transfer ke panitia
                    </div>
                </div>
                <div style="display:flex;gap:8px;flex-wrap:wrap">
                    <a href="{{ route('admin.transactions.export', request()->query()) }}" class="btn btn-outline"
                        style="font-size:12px;padding:7px 13px">
                        📥 Export Rekonsiliasi CSV
                    </a>
                    <a href="{{ route('admin.transactions.export-panitia', request()->query()) }}" class="btn btn-outline"
                        style="font-size:12px;padding:7px 13px">
                        📥 Export Pembagian per Panitia
                    </a>
                </div>
            </div>
            <div class="prow">
                <div class="pi">
                    <div class="pi-lbl">GMV Terkonfirmasi</div>
                    <div class="pi-val cp">Rp{{ number_format($totalGMV, 0, ',', '.') }}</div>
                    <div class="pi-sub">Dari {{ $totalSuccess }} transaksi berhasil</div>
                </div>
                <div class="pi">
                    <div class="pi-lbl">Potongan Fee Platform (2,5%)</div>
                    <div class="pi-val cb">Rp{{ number_format($totalRevenue, 0, ',', '.') }}</div>
                    <div class="pi-sub">2,5% × Rp{{ number_format($totalGMV, 0, ',', '.') }}</div>
                </div>
                <div class="pi">
                    <div class="pi-lbl">Dana Bersih ke Panitia</div>
                    <div class="pi-val cg">Rp{{ number_format($totalPayout, 0, ',', '.') }}</div>
                    <div class="pi-sub">GMV dikurangi biaya layanan</div>
                </div>
                <div class="pi">
                    <div class="pi-lbl">Breakdown Status</div>
                    <div class="split-sm">
                        <div class="ss-item">
                            <div class="n clr-g">{{ $totalSuccess }}</div>
                            <div class="l">Berhasil</div>
                        </div>
                        <div class="ss-item">
                            <div class="n clr-w">{{ $totalPending }}</div>
                            <div class="l">Pending</div>
                        </div>
                        <div class="ss-item">
                            <div class="n clr-r">{{ $totalFailed }}</div>
                            <div class="l">Batal</div>
                        </div>
                    </div>
                    @php $tot = $totalSuccess + $totalPending + $totalFailed; @endphp
                    <div class="pi-sub" style="margin-top:6px">Konversi: {{ $tot > 0 ? round(($totalSuccess / $tot) * 100) : 0 }}%
                        sukses</div>
                </div>
            </div>
        </div>

    </div>

    {{-- MODAL: BUKTI BAYAR --}}
    <div class="modal-ov" id="proofModal" onclick="if(event.target===this)closeM('proofModal')">
        <div class="modal-box">
            <button class="modal-x" onclick="closeM('proofModal')">✕</button>
            <div class="modal-title">Bukti Transfer Pembayaran</div>
            <div class="modal-sub" id="proofRef">—</div>
            <img src="" id="proofImg" class="modal-img" alt="Bukti Transfer">
        </div>
    </div>

    {{-- MODAL: DETAIL --}}
    <div class="modal-ov" id="detailModal" onclick="if(event.target===this)closeM('detailModal')">
        <div class="modal-box" style="max-width:480px">
            <button class="modal-x" onclick="closeM('detailModal')">✕</button>
            <div class="modal-title">Detail Transaksi</div>
            <div class="modal-sub" id="detailRef" style="margin-bottom:16px">—</div>
            <div id="detailBody" style="display:grid;grid-template-columns:1fr 1fr;gap:12px 20px"></div>
        </div>
    </div>

    @push('scripts')
        <script>
            function openProof(src, reg) {
                document.getElementById('proofImg').src = src;
                document.getElementById('proofRef').textContent = 'Registrasi: ' + reg;
                document.getElementById('proofModal').classList.add('open');
            }
            function showDetail(d) {
                document.getElementById('detailRef').textContent = d.reg + (d.ref !== '—' ? ' · ' + d.ref : '');
                var fields = [
                    ['Nama Pembeli', d.name], ['Email', d.email], ['Telepon', d.phone], ['NIM/NIP', d.nim],
                    ['Event', d.event], ['Penyelenggara', d.org], ['Tipe Tiket', d.ticket],
                    ['Jumlah Tiket', d.qty + ' tiket'], ['Metode Bayar', d.method],
                    ['Virtual Account', d.va], ['Total Bayar', d.total], ['Fee Platform', d.fee],
                    ['Status', d.status], ['Waktu', d.date]
                ];
                document.getElementById('detailBody').innerHTML = fields.map(function (f) {
                    return '<div><div style="font-size:10.5px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:.5px;margin-bottom:3px">' + f[0] + '</div>'
                        + '<div style="font-size:13px;font-weight:600;color:#111827">' + (f[1] || '—') + '</div></div>';
                }).join('');
                document.getElementById('detailModal').classList.add('open');
            }
            function closeM(id) {
                document.getElementById(id).classList.remove('open');
                if (id === 'proofModal') document.getElementById('proofImg').src = '';
            }
            document.addEventListener('keydown', function (e) { if (e.key === 'Escape') { closeM('proofModal'); closeM('detailModal'); } });
        </script>
    @endpush

@endsection