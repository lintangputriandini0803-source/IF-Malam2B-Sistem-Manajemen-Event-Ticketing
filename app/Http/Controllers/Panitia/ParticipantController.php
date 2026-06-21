<?php

namespace App\Http\Controllers\Panitia;

use App\Http\Controllers\Controller;
use App\Mail\TicketPurchasedMail;
use App\Models\Event;
use App\Models\Registration;
use App\Exports\PesertaExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ParticipantController extends Controller
{
    public function report(Request $request)
    {
        // Ambil data event  untuk dropdown filter
        $events = Event::where('user_id', auth()->id())->get();

        // Ambil data dari Database
        $query = Registration::whereHas('event', function ($q) {
            $q->where('user_id', auth()->id());
        })->with(['details.ticketType', 'event']);

        // Logika Filter Event
        if ($request->filled('event_id')) {
            $query->where('event_id', $request->event_id);
        }

        // logika filter rentang tanggal berdasarkan waktu regist
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59',
            ]);
        }

        // Logika Pencarian (Search)
        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                      ->orWhere('email', 'like', "%{$q}%")
                      ->orWhere('reg_number', 'like', "%{$q}%");
            });
        }
        // Eksekusi query dengan pagination
        $peserta = $query->latest()->paginate(10)->withQueryString();

        return view('panitia.report_peserta', compact('peserta', 'events'));
    }

    public function exportExcel(Request $request)
    {
        $filters = $request->only(['event_id', 'start_date', 'end_date', 'search']);

        return Excel::download(new PesertaExport(Auth::id(), $filters), 'report_peserta.xlsx');
    }

    /**
     * Halaman daftar pembeli/peserta untuk satu event tertentu, lengkap
     * dengan ringkasan penjualan event tersebut.
     */
    public function index(Request $request, Event $event)
    {
        abort_if($event->user_id !== Auth::id(), 403);

        $event->load('ticketTypes');

        $query = $event->registrations()->with(['ticketType']);

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                      ->orWhere('email', 'like', "%{$q}%")
                      ->orWhere('reg_number', 'like', "%{$q}%");
            });
        }

        $pembeli = $query->latest()->paginate(15)->withQueryString();

        // ─── Summary card ───────────────────────────────────────────────
        $confirmedRegistrations = $event->registrations()->where('status', 'confirmed');

        $totalTerjual   = (clone $confirmedRegistrations)->sum('quantity');
        $totalKuota     = $event->ticketTypes->sum('quota');
        $totalPendapatan = (clone $confirmedRegistrations)->sum('total_price');
        $totalPembeliUnik = (clone $confirmedRegistrations)->distinct('email')->count('email');

        $breakdownPerTipe = $event->ticketTypes->map(function ($tt) {
            return [
                'nama'   => $tt->name,
                'sold'   => $tt->sold,
                'quota'  => $tt->quota,
            ];
        });

        $summary = [
            'total_terjual'    => $totalTerjual,
            'total_kuota'      => $totalKuota,
            'total_pendapatan' => $totalPendapatan,
            'pembeli_unik'     => $totalPembeliUnik,
            'per_tipe'         => $breakdownPerTipe,
        ];

        return view('panitia.participants.index', compact('event', 'pembeli', 'summary'));
    }

    /**
     * Generate ulang PDF tiket (QR) untuk satu registrasi, misalnya untuk
     * dikirim manual ke pembeli yang salah input email saat checkout.
     */
    public function downloadTicket(Event $event, Registration $registration)
    {
        abort_if($event->user_id !== Auth::id(), 403);
        abort_if($registration->event_id !== $event->id, 404);

        // Satu order_ref bisa berisi beberapa baris registrasi (beberapa tipe
        // tiket dalam satu transaksi) — ikutkan semuanya agar PDF konsisten
        // dengan yang dikirim lewat email aslinya.
        $registrations = Registration::where('order_ref', $registration->order_ref)
            ->with(['ticketType', 'event'])
            ->get();

        $tickets = TicketPurchasedMail::buildTicketsFor($registrations);

        $pdf = Pdf::loadView('pdf.ticket', [
            'orderRef'   => $registration->order_ref,
            'event'      => $event,
            'buyer'      => [
                'name'  => $registration->name,
                'email' => $registration->email,
            ],
            'totalPrice' => $registrations->sum('total_price'),
            'tickets'    => $tickets,
        ])->setPaper('a4', 'portrait');

        return $pdf->download('Tiket-' . $registration->order_ref . '.pdf');
    }
}
