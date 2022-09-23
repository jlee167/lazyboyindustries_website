<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        '*',
        'http://www.lazyboyindustries.com/auth',
        'http://www.lazyboyindustries.com/ping',
        'http://49.50.172.17:80/auth',
        'http://49.50.172.17:80/ping',
        'http://127.0.0.1:3001/*'
        //
    ];
}
