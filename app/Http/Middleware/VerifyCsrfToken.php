<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    // ممكن تستثني مسارات معينة من التحقق من CSRF
    protected $except = [
        //
    ];
}
