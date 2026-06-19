<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Tiket Anda - SIMETIX</title>
</head>
<body style="margin:0;padding:0;background:#f3eef8;font-family:Arial,Helvetica,sans-serif;">
<div style="max-width:560px;margin:0 auto;padding:24px 16px;">

    <div style="background:#6B0080;border-radius:16px 16px 0 0;padding:24px;text-align:center;">
        <h1 style="color:#fff;font-size:20px;margin:0;">SIMETIX</h1>
    </div>

    <div style="background:#fff;border-radius:0 0 16px 16px;padding:24px;">
        <h2 style="font-size:18px;color:#1a1a1a;margin-top:0;">Terima kasih, {{ $buyer['name'] }}! 🎉</h2>
        <p style="font-size:14px;color:#444;line-height:1.6;">
            Pembayaran Anda untuk event <strong>{{ strtoupper($event->title) }}</strong> telah kami terima dan
            dikonfirmasi. Tiket digital Anda terlampir dalam bentuk PDF pada email ini — tunjukkan QR code pada
            tiket tersebut saat masuk ke lokasi acara.
        </p>

        <table style="width:100%;border-collapse:collapse;margin:20px 0;font-size:13px;color:#444;">
            <tr>
                <td style="padding:6px 0;color:#888;">Referensi Pemesanan</td>
                <td style="padding:6px 0;text-align:right;font-weight:bold;">{{ $orderRef }}</td>
            </tr>
            <tr>
                <td style="padding:6px 0;color:#888;">Event</td>
                <td style="padding:6px 0;text-align:right;font-weight:bold;">{{ $event->title }}</td>
            </tr>
            <tr>
                <td style="padding:6px 0;color:#888;">Tanggal</td>
                <td style="padding:6px 0;text-align:right;">{{ $event->getFormattedDateRange() }}</td>
            </tr>
            <tr>
                <td style="padding:6px 0;color:#888;">Lokasi</td>
                <td style="padding:6px 0;text-align:right;">{{ $event->location }}</td>
            </tr>
            <tr>
                <td style="padding:6px 0;color:#888;">Jumlah Tiket</td>
                <td style="padding:6px 0;text-align:right;">{{ count($tickets) }}</td>
            </tr>
            <tr>
                <td style="padding:10px 0 0;color:#888;border-top:1px solid #eee;">Total Pembayaran</td>
                <td style="padding:10px 0 0;text-align:right;font-weight:bold;color:#6B0080;border-top:1px solid #eee;">
                    Rp {{ number_format($totalPrice, 0, ',', '.') }}
                </td>
            </tr>
        </table>

        <p style="font-size:12px;color:#999;line-height:1.6;">
            Simpan tiket PDF ini baik-baik. Setiap QR code hanya bisa dipakai satu kali untuk masuk lokasi acara.
            Jika ada pertanyaan, balas email ini atau hubungi panitia event.
        </p>
    </div>

    <p style="text-align:center;font-size:11px;color:#aaa;margin-top:16px;">
        Email ini dikirim otomatis oleh SIMETIX, mohon tidak membalas jika tidak diperlukan.
    </p>
</div>
</body>
</html>
