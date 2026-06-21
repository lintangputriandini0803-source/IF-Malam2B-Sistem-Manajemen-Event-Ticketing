<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Barryvdh\DomPDF\Facade\Pdf;

class TicketPurchasedMail extends Mailable
{
    use Queueable, SerializesModels;

    /** @var array Daftar tiket individual: ['code' => ..., 'qr' => data-uri, 'ticket_name' => ..., 'reg' => Registration] */
    public array $tickets;

    /**
     * @param  string      $orderRef
     * @param  Collection  $registrations  Semua baris Registration untuk order_ref ini (sudah eager-load ticketType & event)
     * @param  array       $buyer          ['name' => ..., 'email' => ..., 'nim' => ..., 'phone' => ...]
     * @param  float       $totalPrice
     */
    public function __construct(
        public string $orderRef,
        public Collection $registrations,
        public array $buyer,
        public float $totalPrice,
    ) {
        $this->tickets = self::buildTicketsFor($this->registrations);
    }

    public function envelope(): Envelope
    {
        $event = $this->registrations->first()->event;

        return new Envelope(
            subject: 'Tiket Anda - ' . $event->title . ' (SIMETIX)',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.ticket-purchased',
            with: [
                'orderRef'   => $this->orderRef,
                'event'      => $this->registrations->first()->event,
                'buyer'      => $this->buyer,
                'totalPrice' => $this->totalPrice,
                'tickets'    => $this->tickets,
            ],
        );
    }

    public function attachments(): array
    {
        $pdf = Pdf::loadView('pdf.ticket', [
            'orderRef'   => $this->orderRef,
            'event'      => $this->registrations->first()->event,
            'buyer'      => $this->buyer,
            'totalPrice' => $this->totalPrice,
            'tickets'    => $this->tickets,
        ])->setPaper('a4', 'portrait');

        return [
            Attachment::fromData(fn () => $pdf->output(), 'Tiket-' . $this->orderRef . '.pdf')
                ->withMime('application/pdf'),
        ];
    }

    /**
     * Pecah setiap baris registrasi (yang bisa quantity > 1) menjadi tiket-tiket individual,
     * masing-masing dengan kode unik (format sama dengan yang dipakai TicketScanController)
     * dan QR code yang sudah di-generate sebagai base64 data-uri (tanpa API eksternal).
     */
    public static function buildTicketsFor(Collection $registrations): array
    {
        $tickets = [];

        foreach ($registrations as $reg) {
            for ($i = 0; $i < $reg->quantity; $i++) {
                $code = $reg->reg_number . '-' . str_pad($i + 1, 2, '0', STR_PAD_LEFT);

                $result = (new Builder(
                    writer: new PngWriter(),
                    data: $code,
                    size: 220,
                    margin: 4,
                ))->build();

                $tickets[] = [
                    'code'        => $code,
                    'qr'          => $result->getDataUri(),
                    'ticket_name' => $reg->ticketType->name,
                    'reg'         => $reg,
                ];
            }
        }

        return $tickets;
    }
}
