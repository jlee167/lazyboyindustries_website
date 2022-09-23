<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SoftXSS
{
    /**
     * Replace script tag brackets with HTML escape strings
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $inputArray = $request->all();
        foreach($inputArray as $key => &$input) {
            $input = str_replace("<script>", "&lt;script&gt;", $input);
            $input = str_replace("</script>", "&lt;/script&gt;", $input);
        }
        $request->merge($inputArray);
        return $next($request);
    }
}
