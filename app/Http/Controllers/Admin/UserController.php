<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

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
