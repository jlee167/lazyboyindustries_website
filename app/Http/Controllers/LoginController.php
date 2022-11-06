<?php

namespace App\Http\Controllers;

use APP\Models\User;
use App\Repositories\User\UserRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PragmaRX\Google2FA\Google2FA;

class LoginController extends BaseController
{

    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }



    /**
     * Authenticate users with credentials extracted from HTTP request
     *
     * @param  Request $request
     * @return Response
     */
    public function authenticate(Request $request)
    {
        if (Auth::check()) {
            return response(null, 200);
        } else {
            return $this->authWithUname($request);
        }
    }


    /**
     * Check if current user is authenticated.
     *
     * @return bool true if logged in. false if not.
     */
    public static function getAuthState()
    {
        if (Auth::check()) {
            return true;
        } else {
            return false;
        }

    }


    /**
     * Authenticate with Username
     *
     * @param  Request $request
     * @return Response
     */
    public function authWithUname(Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');

        if (Auth::attempt(['username' => $username, 'password' => $password])) {
            Log::info("[AUTH]" . $username . "Logged in (200)");
            return response(null, 200);
        } else {
            return response(null, 401);
        }
    }


    /**
     * Authenticate with Kakao OAuth2
     *
     * @param  Request $request
     * @return Response
     */
    public function authWithKakao(Request $request)
    {
        try {
            $kakaoUser = $this->userRepository->getKakaoUser($request->accessToken);
            $user = DB::table('users')
                ->where('auth_provider', '=', 'Kakao')
                ->where('uid_oauth', '=', $kakaoUser['uid'])
                ->first();

            if (!$user) {
                return response(null, 404);
            }

            if (Auth::loginUsingId($user->id)) {
                Log::info("[AUTH] Kakao User" . $kakaoUser['uid'] . "Logged in (200)");
                return response(null, 200);
            } else {
                return response(null, 401);
            }

        } catch (\Exception $e) {
            return response(null, 500);
        }
    }


    /**
     * Authenticate with Google OAuth2
     *
     * @param  Request $request
     * @return Response
     */
    public function authWithGoogle(Request $request)
    {
        /* JWT Lib for PHP */
        \Firebase\JWT\JWT::$leeway = 5;

        try {
            $client = new \Google_Client();
            $client->setClientId(env('GOOGLE_APP_KEY', ""));
            $client->setClientSecret(env('GOOGLE_SECRET', ""));
            if ($request->accessToken) {
                $payload = $client->verifyIdToken($request->accessToken);
            }
        } catch (\Exception $e) {
            return response([], 401);
        }

        if ($payload) { // Success
            $uid = $payload['sub'];
            $email = $payload['email'];
            $verified = $payload['email_verified'];
            $name = $payload['name'];
            $profile_picture = $payload['picture'];
        } else { // Invalid ID token
            return response("PHP server error: Invalid access token\n", 401);
        }

        if ($verified == 0) {
            return response('Google token is not verified \n', 401);
        }

        $user = DB::table('users')
            ->where('auth_provider', '=', 'Google')
            ->where('uid_oauth', '=', $payload['sub'])
            ->first();

        if ($user == null) {
            return response([
                "token" => Auth::check(),
                "href" => route('main'),
                "authenticated" => false,
                "error" => "User does not exist!",
            ], 404);
        }

        try {
            if (Auth::loginUsingId($user->id)) {
                Log::info("[AUTH] Google User" . $user->id . "Logged in (200)");
                return response([
                    "token" => Auth::check(),
                    "href" => route('main'),
                    "authenticated" => true,
                    "redirectUrl" => $request->session()->get('intendedUrl'),
                ], 200);
            } else {
                //@Todo
            }
        } catch (\Exception $e) {
            return response([
                "authenticated" => false,
                "error" => "Server Error: Unexpected exception",
            ], 500);
        }
    }


    /**
     * Verify Google 2FA Secret
     *
     * @param  Request $request
     * @return Response
     */
    public function authWithGoogle2FA(Request $request)
    {

        try {
            $google2fa = (new \PragmaRX\Google2FAQRCode\Google2FA());
            $isAuthenticated = $google2fa->verifyKey(Auth::user()->google2fa_secret, $request->secret);
            if ($isAuthenticated == true) {
                $authenticator = app(\PragmaRX\Google2FALaravel\Google2FA::class)->boot($request);
                $authenticator->login();

                Log::info("[AUTH] User " . Auth::id() . " passed Google 2FA (200)");

                return response([
                    'result' => true,
                    "redirectUrl" => redirect()->intended()->getTargetUrl(),
                ]);
            }
        } catch (Exception $e) {
            return response([
                'result' => false,
                'error' => $e,
            ], 401);
        }

        return response([
            'result' => false,
            'error' => "Wrong Google OTP number!",
        ], 401);
    }


    /**
     * Verify Google 2FA Secret
     *
     * @param  Request $request
     * @return Response
     */
    public function activateGoogle2FA(Request $request)
    {
        DB::beginTransaction();

        $user = User::where('id', '=', (string) Auth::id())
            ->first();

        if ($user->google2fa_active) {
            return response(null, 200);
        }

        $google2fa = (new \PragmaRX\Google2FAQRCode\Google2FA());
        $isAuthenticated = $google2fa->verifyKey(Auth::user()->google2fa_secret, $request->secret);

        if ($isAuthenticated) {
            $user->google2fa_active = 1;
            $user->save();
            DB::commit();
            return response(null, 200);
        } else {
            DB::rollBack();
            return response(null, 401);
        }
    }


    /**
     * Logout from session
     *
     * @param  Request $request
     * @return Response
     */
    public function logout(Request $request)
    {
        try {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return response([], 200);
        } catch (Exception $e) {
            return response(['message' => $e->getMessage()], 500);
        }
    }
}
