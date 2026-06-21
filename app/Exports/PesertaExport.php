<?php

namespace App\Exports;

use App\Models\Registration;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PesertaExport implements FromCollection, WithHeadings
{
    public function __construct(
        protected int $panitiaId,
        protected array $filters = [],
    ) {
    }

    public function collection()
    {
        $query = Registration::whereHas('event', function ($q) {
            $q->where('user_id', $this->panitiaId);
        })->with(['event']);

        if (! empty($this->filters['event_id'])) {
            $query->where('event_id', $this->filters['event_id']);
        }

        if (! empty($this->filters['start_date']) && ! empty($this->filters['end_date'])) {
            $query->whereBetween('created_at', [
                $this->filters['start_date'] . ' 00:00:00',
                $this->filters['end_date'] . ' 23:59:59',
            ]);
        }

        if (! empty($this->filters['search'])) {
            $q = $this->filters['search'];
            $query->where(function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                      ->orWhere('email', 'like', "%{$q}%")
                      ->orWhere('reg_number', 'like', "%{$q}%");
            });
        }

        return $query->latest()->get()->map(function ($p) {
            return [
                $p->reg_number,
                $p->created_at,
                $p->name,
                $p->nim,
                $p->email,
                $p->phone,
                $p->event->title ?? '-',
                $p->total_price,
                $p->status,
            ];
        });
    }

    public function headings(): array
    {
        return ["Reg Number", "Waktu", "Nama Pembeli", "NIK/NIM", "Email", "No HP", "Event", "Total", "Status"];
    }
}
