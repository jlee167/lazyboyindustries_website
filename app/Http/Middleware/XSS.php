<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class XSS
{
    /**
     * Middleware for XSS defense layer.
     * Strip <Script> tags from request body.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $inputs = $request->all();

        foreach($inputs as $key => &$input) {
           $input = strip_tags($input);
        }
        $request->merge($inputs);
        return $next($request);
    }
}
