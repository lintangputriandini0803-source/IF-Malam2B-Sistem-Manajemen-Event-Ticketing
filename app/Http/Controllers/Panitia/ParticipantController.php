<?php

namespace App\Http\Controllers\Panitia;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ParticipantController extends Controller
{
    public function report()
{
    // Contoh data dummy untuk belajar (nanti bisa diganti query database)
    $peserta = [
        ['nama' => 'Lintang Putri', 'email' => 'lintang83@simetix.com', 'status' => 'Lunas'],
        ['nama' => 'Muhammad Rafli', 'email' => 'rafliiu@simetix.com', 'status' => 'Pending'],
        ['nama' => 'Dinda Amalia', 'email' => 'dindanugroho@simetix.com', 'status' => 'Lunas'],
    ];

    return view('panitia.report_peserta', compact('peserta'));
}
}
