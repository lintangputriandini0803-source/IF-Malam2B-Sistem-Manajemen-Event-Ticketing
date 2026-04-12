<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AboutUsLinnController extends Controller
{
    // Fungsi untuk menyiapkan data (sesuai instruksi poin 4)
    public function getData()
    {
        // Data ini yang akan dikirim ke view [cite: 16]
        $dataBarang = [
            ['id' => 1, 'nama' => 'Visi Project', 'harga' => 'Memudahkan Ticketing'],
            ['id' => 2, 'nama' => 'Ketua Tim', 'harga' => 'Lintang Putri'],
            ['id' => 3, 'nama' => 'Lokasi', 'harga' => 'Batam'],
        ];

        return $dataBarang;
    }

    // Fungsi untuk menampilkan view (sesuai instruksi poin 4)
    public function tampilkan()
    {
        $data = $this->getData();
        // Pastikan nama view 'about' sesuai dengan file .blade.php yang kamu buat
        return view('about', compact('data'));
    }
}