@extends('layouts.admin')
@section('title', 'Transaksi')
@section('page-title', 'Transaksi')

@section('content')

<div style="margin-bottom:20px">
    <h1 style="font-size:20px;font-weight:800;color:#111">Semua Transaksi</h1>
    <p style="font-size:13px;color:#9ca3af;margin-top:2px">Riwayat pembelian tiket di platform</p>
</div>

@if($transactions->isEmpty())
<div style="text-align:center;padding:80px 20px;background:white;border-radius:14px">
    <span style="font-size:48px">📋</span>
    <p style="font-size:16px;font-weight:700;color:#374151;margin-top:12px">Belum ada transaksi</p>
    <p style="font-size:13px;color:#9ca3af;margin-top:4px">Transaksi akan muncul setelah ada pembelian tiket.</p>
</div>
@else
<div style="background:white;border-radius:14px;overflow:hidden">
    <table style="width:100%;border-collapse:collapse">
        <thead style="background:#f8fafc">
            <tr>
                <th style="text-align:left;padding:14px 20px;font-size:11px;font-weight:700;color:#9ca3af;text-transform:uppercase">Pembeli</th>
                <th style="text-align:left;padding:14px 20px;font-size:11px;font-weight:700;color:#9ca3af;text-transform:uppercase">Event</th>
                <th style="text-align:left;padding:14px 20px;font-size:11px;font-weight:700;color:#9ca3af;text-transform:uppercase">Tanggal</th>
                <th style="text-align:right;padding:14px 20px;font-size:11px;font-weight:700;color:#9ca3af;text-transform:uppercase">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $tx)
            <tr style="border-top:1px solid #f3f4f6">
                <td style="padding:14px 20px;font-size:13px;color:#111">{{ $tx->user->name ?? '-' }}</td>
                <td style="padding:14px 20px;font-size:13px;color:#374151">{{ $tx->event->title ?? '-' }}</td>
                <td style="padding:14px 20px;font-size:12px;color:#9ca3af">{{ $tx->created_at->format('d M Y') }}</td>
                <td style="padding:14px 20px;font-size:13px;font-weight:700;color:#6B0080;text-align:right">
                    Rp{{ number_format($tx->total_price ?? 0, 0, ',', '.') }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@if($transactions->hasPages())
<div style="margin-top:16px">{{ $transactions->withQueryString()->links() }}</div>
@endif
@endif

@endsection
