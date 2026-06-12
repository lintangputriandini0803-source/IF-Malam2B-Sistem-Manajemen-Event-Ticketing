<?php
namespace App\Http\Controllers\Panitia;

use App\Models\Registration;
use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\TicketScan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketScanController extends Controller
{
    // Halaman pilih event + scan
    public function index()
    {
        $events = Event::where('user_id', Auth::id())->get();
        return view('panitia.scan.index', compact('events'));
    }
    public function list(Request $request)
{
    $eventId = $request->event_id;

    // Pastikan event milik panitia ini
    $event = Event::where('id', $eventId)
                  ->where('user_id', Auth::id())
                  ->first();

    if (!$event) {
        return response()->json(['scans' => []]);
    }

    $scans = \App\Models\TicketScan::where('event_id', $eventId)
        ->orderByDesc('scanned_at')
        ->get()
        ->map(function ($scan) {
            // Ambil nama dari registrasi
            $parts = explode('-', $scan->ticket_code);
            $regNumber = implode('-', array_slice($parts, 0, 3));
            $registration = \App\Models\Registration::where('reg_number', $regNumber)->first();
            return [
                'ticket_code' => $scan->ticket_code,
                'name'        => $registration ? $registration->name : '-',
                'scanned_at'  => $scan->scanned_at->format('d/m/Y H:i:s'),
            ];
        });

        return response()->json(['scans' => $scans]);
        }
    // API endpoint: validasi & simpan scan
    public function scan(Request $request)
{
    $request->validate([
        'ticket_code' => 'required|string',
        'event_id'    => 'required|exists:events,id',
    ]);

    $ticketCode = strtoupper(trim($request->ticket_code));
    $eventId    = $request->event_id;

    // Pastikan event milik panitia ini
    $event = Event::where('id', $eventId)
                  ->where('user_id', Auth::id())
                  ->first();

    if (!$event) {
        return response()->json([
            'status'  => 'error',
            'message' => 'Event tidak ditemukan atau bukan milik Anda.',
        ], 403);
    }

    // Kode tiket format: EVT-2026-000001-01
    // Strip suffix urutan tiket → ambil reg_number: EVT-2026-000001
    $parts = explode('-', $ticketCode);
    if (count($parts) < 4) {
        return response()->json([
            'status'  => 'error',
            'message' => 'Format kode tiket tidak valid.',
        ], 422);
    }

    // Ambil reg_number = 3 bagian pertama: EVT-2026-000001
    $regNumber = implode('-', array_slice($parts, 0, 3));

    // Cari registrasi
    $registration = \App\Models\Registration::where('reg_number', $regNumber)->first();

    if (!$registration) {
        return response()->json([
            'status'  => 'error',
            'message' => 'Kode tiket tidak valid.',
        ], 404);
    }

    // Pastikan tiket milik event yang dipilih
    if ($registration->event_id != $eventId) {
        return response()->json([
            'status'  => 'error',
            'message' => 'Tiket ini bukan untuk event yang dipilih.',
        ], 422);
    }

    // Pastikan status registrasi confirmed
    if ($registration->status !== 'confirmed') {
        return response()->json([
            'status'  => 'error',
            'message' => 'Tiket belum dikonfirmasi pembayarannya.',
        ], 422);
    }

    // Cek duplikasi scan
    $alreadyScanned = \App\Models\TicketScan::where('ticket_code', $ticketCode)->first();
    if ($alreadyScanned) {
        return response()->json([
            'status'     => 'duplicate',
            'message'    => 'Tiket sudah digunakan pada ' . $alreadyScanned->scanned_at,
        ], 409);
    }

    // Simpan scan
    \App\Models\TicketScan::create([
        'ticket_code' => $ticketCode,
        'event_id'    => $eventId,
        'scanned_at'  => now(),
    ]);

   return response()->json([
    'status'      => 'success',
    'message'     => 'Tiket valid! Selamat datang, ' . $registration->name . '.',
    'ticket_code' => $ticketCode,
    'name'        => $registration->name,
    'scanned_at'  => now()->format('d/m/Y H:i:s'),
    'event'       => $event->title,
    ]);


}}

