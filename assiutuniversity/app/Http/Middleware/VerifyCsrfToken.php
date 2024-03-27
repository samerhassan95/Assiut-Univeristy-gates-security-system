<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    // protected $addHttpCookie = true;
    protected $except = [
        '/v1/allstaff',
        '/v1/studentvisits',
        '/v1/studentvisits/*',
        '/v1/staffvisits',
        'v1/students/updateSerial/*',
    ];
}
