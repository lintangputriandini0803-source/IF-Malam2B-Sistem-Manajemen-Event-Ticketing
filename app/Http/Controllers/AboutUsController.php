<?php

namespace App\Http\Controllers;

class AboutUsController extends Controller
{
    // Fungsi untuk menyiapkan data (sesuai instruksi poin 4)
    public function getData()
{
    $dataAbout = [
        'nama_project' => 'SIMETIX',
        'deskripsi' => 'Platform Event Management yang memudahkan penyelenggara mengelola event dan peserta mendapatkan tiket secara digital.',
        'layanan' => [
            ['jenis' => 'Early Bird', 'fitur' => 'Harga khusus untuk pendaftar awal.'],
            ['jenis' => 'Normal', 'fitur' => 'Tiket standar untuk peserta umum.'],
            ['jenis' => 'VIP', 'fitur' => 'Fasilitas eksklusif dengan kuota terbatas.'],
        ],
        'tim' => [
            ['nama' => 'Lintang Putri Andini', 'nim' => '331251032'],
            ['nama' => 'Muhammad Rafli Akbar Setiadi', 'nim' => '3312511031'],
            ['nama' => 'Dinda Amalia Nugroho', 'nim' => '3312511039'],
        ],
        'lokasi' => 'Politeknik Negeri Batam'
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
