<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PanitiaMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->check()) {
            return redirect('/')->with('open_login_modal', true);
        }

        $user = auth()->user();

        if (! $user->isPanitia() && ! $user->isAdmin()) {
            abort(403, 'Akses ditolak. Halaman ini hanya untuk Panitia.');
        }

        if ($user->isPanitia() && ! $user->isApproved()) {
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect('/')->with('login_error', 'Akun panitia kamu belum disetujui admin.');
        }

        return $next($request);
    }
}
