<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use PragmaRX\Google2FALaravel\Tests\Support\User;

class TwoFactorAuth
{
    /**
     * Authenticate user with Google OTP
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = \App\Models\User::where('id', '=', Auth::id())
            ->first();

        Log::info($user->google2fa_active);
        if ($user->google2fa_active === 0) {//google2fa_secret === null) {
            /* This user does not use 2FA feature */
            return $next($request);
        }

        $authCheck = $request->getSession()->get('google2fa.auth_passed');
        if ($authCheck != true) {
            if (!$request->expectsJson())
                return redirect()->guest(route('2fa'));
            else {
                return json_encode([
                    "result" => false,
                    "err"    => "2 Factor Authentication Required"
                ]);
            }
        }
        else {
            return $next($request);
        }
    }
}
