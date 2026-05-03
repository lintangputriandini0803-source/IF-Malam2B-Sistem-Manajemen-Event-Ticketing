<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($q2) use ($q) {
                $q2->where('name', 'like', "%{$q}%")
                   ->orWhere('email', 'like', "%{$q}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->latest()->paginate(15);

        return view('admin.index', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email',
            'role'         => 'required|in:admin,panitia,user',
            'password'     => 'required|string|min:8|confirmed',
            'organization' => 'nullable|string|max:255',
        ]);

        User::create([
            'name'         => $request->name,
            'email'        => $request->email,
            'nim'          => '',
            'role'         => $request->role,
            'password'     => Hash::make($request->password),
            'organization' => $request->organization,
            'status'       => $request->role === 'panitia' ? 'approved' : null,
        ]);

        return back()->with('success', "Pengguna {$request->name} berhasil ditambahkan.");
    }

    public function approve(User $user)
    {
        $user->update(['status' => 'approved']);
        return back()->with('success', "Akun {$user->name} berhasil disetujui.");
    }

    public function reject(User $user)
    {
        $user->update(['status' => 'rejected']);
        return back()->with('success', "Akun {$user->name} berhasil ditolak.");
    }
}
