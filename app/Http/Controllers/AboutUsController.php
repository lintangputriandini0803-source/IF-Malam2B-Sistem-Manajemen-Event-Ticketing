<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AboutUsLinnController extends Controller
{
    // Fungsi untuk menyiapkan data (sesuai instruksi poin 4)
    public function getData()
{
    $dataAbout = [
        'nama_project' => 'SIMETIX',
        'deskripsi' => 'Solusi manajemen event untuk UKM kampus dalam mempublikasikan acara dan mengelola pendaftaran secara otomatis.',
        'layanan' => [
            ['jenis' => 'Early Bird', 'fitur' => 'Harga khusus untuk pendaftar awal.'],
            ['jenis' => 'Normal', 'fitur' => 'Tiket standar untuk peserta umum.'],
            ['jenis' => 'VIP', 'fitur' => 'Fasilitas eksklusif dengan kuota terbatas.'],
        ],
        'tim' => [
            ['nama' => 'Lintang Putri Andini', 'nim' => '331251032'],
            ['nama' => 'Muhammad Rafli Akbar Setiadi', 'nim' => '3312511031'],
            ['nama' => 'Dinda Amalia Nugroho', 'nim' => '3312511039'],
        ]
    ];

    return $dataAbout;
}

    // Fungsi untuk menampilkan view (sesuai instruksi poin 4)
    public function tampilkan()
    {
        $data = $this->getData();
        return view('about', compact('data'));
    }
}