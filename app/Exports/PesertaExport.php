<?php

namespace App\Exports;

use App\Models\Registration;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PesertaExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Registration::all()->map(function($p) {
            return [
                $p->reg_number,
                $p->created_at,
                $p->name,
                $p->nim,
                $p->email,
                $p->phone,
                $p->event->name ?? '_',
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
