<?php

namespace App\Http\Controllers;

use App\Models\User; // Penting: Tambahkan ini agar model User terbaca
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // Penting: Tambahkan ini agar Hash::make bisa jalan

class RegisterController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'no_hp' => 'required|string|max:15',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // 2. Simpan Data ke Database
        User::create([
            'nama_lengkap' => $request->nama_lengkap,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'password' => Hash::make($request->password), // Enkripsi password
        ]);

        // 3. Redirect ke halaman login dengan pesan sukses
        return redirect('/login')->with('success', 'Registrasi Berhasil!');
    }
}