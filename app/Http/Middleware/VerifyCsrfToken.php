<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     * Added since CSRF isn't required on the test.
     *
     * @var array
     */
    protected $except = [
        'register',
        'login',
        'order',
    ];
}
