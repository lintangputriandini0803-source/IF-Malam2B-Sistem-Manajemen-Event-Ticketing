<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'nim'          => 'required|string|max:20',
            'email'        => 'required|string|email|unique:users',
            'organization' => 'required|string|max:255',
            'reason'       => 'required|string',
            'password'     => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name'         => $request->name,
            'nim'          => $request->nim,
            'email'        => $request->email,
            'organization' => $request->organization,
            'reason'       => $request->reason,
            'password'     => Hash::make($request->password),
            'role'         => 'panitia',
            'status'       => 'pending',
        ]);

        return redirect('/')->with('success', 'Pendaftaran berhasil! Tunggu persetujuan admin.');
    }
}
