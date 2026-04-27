<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SettingsController extends Controller
{
    public function index()
    {
        // Default settings — bisa diperluas ke database/config nanti
        $settings = [
            'contact_email'       => config('mail.from.address', ''),
            'app_description'     => 'Platform event & ticketing Politeknik Batam',
            'feature_registration'=> true,
            'feature_payment'     => true,
            'feature_panitia_reg' => true,
            'feature_maintenance' => false,
        ];

        return view('admin.settings', compact('settings'));
    }

    public function update(Request $request)
    {
        // Simpan ke config/session — bisa dikembangkan ke DB
        return back()->with('success', 'Pengaturan platform berhasil disimpan.');
    }

    public function profile(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
        ]);

        Auth::user()->update([
            'name'  => $request->name,
            'email' => $request->email,
        ]);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function password(Request $request)
    {
        $request->validate([
            'current_password'      => 'required',
            'password'              => 'required|min:8|confirmed',
        ]);

        if (! Hash::check($request->current_password, Auth::user()->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini salah.']);
        }

        Auth::user()->update(['password' => $request->password]);

        return back()->with('success', 'Password berhasil diubah.');
    }
}
