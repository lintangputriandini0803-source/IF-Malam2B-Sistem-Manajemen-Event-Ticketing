<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * URI yang dikecualikan dari verifikasi CSRF.
     * Midtrans mengirim POST notification dari server mereka,
     * sehingga tidak bisa menyertakan CSRF token.
     */
    protected $except = [
        '/midtrans/notification',
    ];
}
