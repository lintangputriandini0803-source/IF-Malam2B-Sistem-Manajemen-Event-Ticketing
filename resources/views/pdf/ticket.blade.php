<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Tiket {{ $orderRef }}</title>
<style>
    @page { margin: 24px; }
    body { font-family: 'Helvetica', Arial, sans-serif; color: #1a1a1a; font-size: 12px; }

    .header { background:#6B0080; color:#fff; padding:16px 20px; border-radius:10px; margin-bottom:18px; }
    .header h1 { margin:0; font-size:18px; }
    .header p { margin:4px 0 0; font-size:11px; opacity:0.9; }

    .order-info { width:100%; margin-bottom:20px; border-collapse:collapse; }
    .order-info td { padding:4px 0; font-size:11px; color:#444; }
    .order-info td.label { color:#888; width:140px; }

    .ticket-box {
        border:1px solid #e3d9ec;
        border-radius:10px;
        padding:14px;
        margin-bottom:14px;
        width:100%;
        page-break-inside: avoid;
    }
    .ticket-table { width:100%; border-collapse:collapse; }
    .ticket-table td { vertical-align:middle; }
    .qr-cell { width:100px; }
    .qr-cell img { width:90px; height:90px; }
    .info-cell { padding-left:14px; }

    .tag { font-size:10px; color:#888; text-transform:uppercase; letter-spacing:0.5px; }
    .event-title { font-size:14px; font-weight:bold; margin:2px 0 4px; }
    .meta { font-size:10.5px; color:#666; margin:1px 0; }
    .code { display:inline-block; margin-top:6px; font-family: 'Courier New', monospace; font-size:11px;
            background:#f4eefb; color:#6B0080; padding:3px 8px; border-radius:6px; font-weight:bold; }

    .footer-note { margin-top:10px; font-size:10px; color:#999; text-align:center; }
</style>
</head>
<body>

    <div class="header">
        <h1>SIMETIX &mdash; Tiket Elektronik</h1>
        <p>Tunjukkan QR code di bawah saat masuk lokasi acara. Setiap kode hanya berlaku 1 kali scan.</p>
    </div>

    <table class="order-info">
        <tr>
            <td class="label">Referensi Pemesanan</td>
            <td><strong>{{ $orderRef }}</strong></td>
        </tr>
        <tr>
            <td class="label">Nama Pemesan</td>
            <td>{{ $buyer['name'] }}</td>
        </tr>
        <tr>
            <td class="label">Email</td>
            <td>{{ $buyer['email'] }}</td>
        </tr>
        <tr>
            <td class="label">Total Pembayaran</td>
            <td><strong>Rp {{ number_format($totalPrice, 0, ',', '.') }}</strong></td>
        </tr>
    </table>

    @foreach ($tickets as $index => $t)
        <div class="ticket-box">
            <table class="ticket-table">
                <tr>
                    <td class="qr-cell">
                        <img src="{{ $t['qr'] }}" alt="QR Code">
                    </td>
                    <td class="info-cell">
                        <div class="tag">Tiket {{ $index + 1 }} dari {{ count($tickets) }} &middot; {{ $t['ticket_name'] }}</div>
                        <div class="event-title">{{ strtoupper($event->title) }}</div>
                        <div class="meta">📅 {{ $event->getFormattedDateRange() }}</div>
                        <div class="meta">📍 {{ $event->location }}</div>
                        <div class="code">{{ $t['code'] }}</div>
                    </td>
                </tr>
            </table>
        </div>
    @endforeach

    <p class="footer-note">Dicetak otomatis oleh sistem SIMETIX &mdash; {{ now()->format('d F Y H:i') }}</p>

</body>
</html>
