<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->check()) {
            return redirect('/')->with('open_login_modal', true);
        }

        if (! auth()->user()->isAdmin()) {
            abort(403, 'Akses ditolak. Halaman ini hanya untuk Admin.');
        }

        return $next($request);
    }
}
