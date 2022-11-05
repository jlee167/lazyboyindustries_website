<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Credit;
use App\Repositories\CreditRepository;
use App\Repositories\UserRepository;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use PragmaRX\Google2FA\Google2FA;


class UserController extends BaseController
{

    private $userRepository;
    private $creditRepository;


    public function __construct(UserRepository $userRepository,
                                CreditRepository $creditRepository)
    {
        $this->userRepository = $userRepository;
        $this->creditRepository = $creditRepository;
    }



    /**
     * Retrieve a user's information based on username
     *
     * @param  Illuminate\Http\Request $request
     * @param  string $username
     * @return Illuminate\Http\Response
     */
    public function getUser(Request $request, string $username)
    {
        if (!Auth::check()) {
            return response(null, 401);
        }

        if (Auth::user() == $username) {
            $user = $this->userRepository->getByUsername($username);
            /* @Todo: return only relevant information */
            return response($user, 200);
        } else {
            return response(null, 404);
        }
    }




    /**
     * Get current user's user profile.
     *
     * @param  Illuminate\Http\Request $request
     * @return Illuminate\Http\Response
     */
    public function getSelfProfile(Request $request)
    {
        $user = $this->userRepository->getByID(Auth::id());
        $is2FAenabled = ($user->google2fa_active === 1);

        return response([
            'id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
            'stream_key' => $user->stream_key,
            'status' => $user->status,
            'is2FAenabled' => $is2FAenabled,
            'auth_provider' => $user->auth_provider,
            'image_url' => $user->image_url,
        ], 200);
    }




    /**
     * Resend email verification request to requesting user
     *
     * @param  Illuminate\Http\Request $request
     * @return Illuminate\Http\Response
     */
    public function resendEmail(Request $request)
    {
        /* Send email verification link */
        $request->user()->sendEmailVerificationNotification();
        return response(null, 200);
    }




    /**
     * Register a new user into database.
     *
     * @param  Illuminate\Http\Request $request
     * @param  string $username
     * @return Illuminate\Http\Response
     */
    public function registerUser(Request $request, string $username)
    {
        DB::beginTransaction();

        if (empty($request->image_url)) {
            $request->imageurl = User::getDefaultImageUrl();
        }

        /* Validate inputs. Return Error if invalid input is detected */
        try {
            $validated = $request->validate([
                'username' => ['min:8'],
                'password' => ['min:8'],
                'email' => ['email:rfc,dns'],
            ]);
        } catch (Exception $e) {
            return response([
                'error' => 'Invalid user data!. Please retry with valid email,
                            password (min 8 characters), username(min 8 characters)',
            ], 409);
        }


        try {
            Http::get(env('STREAMING_SERVER', 'http://127.0.0.1:3001') . "/ping/");

            /* Filter duplicate username or email*/
            $duplicateUser = User::where('username', '=', $username)
                ->orWhere('email', '=', $request->email)
                ->first();

            if ($duplicateUser) {
                if ($username === $duplicateUser->username) {
                    return response([
                        'error' => 'Existing Username!',
                    ], 409);
                }

                if ($request->email === $duplicateUser->email) {
                    return response([
                        'error' => 'Existing Email!',
                    ], 409);
                }

            }

            $uid_oauth = '';
            if ($request->auth_provider != null) {
                if ($request->auth_provider == 'Google') {
                    \Firebase\JWT\JWT::$leeway = 5;
                    $user = $this->userRepository->getGoogleUser($request->accessToken);
                    $uid_oauth = $user['uid'];
                } else if ($request->auth_provider == 'Kakao') {
                    $user = $this->userRepository->getKakaoUser($request->accessToken);
                    $uid_oauth = $user['uid'];
                }

                $duplicateOauthUser = User::where('uid_oauth', '=', $uid_oauth)
                    ->where('auth_provider', '=', $request->auth_provider)
                    ->first();
                if ($duplicateOauthUser) {
                    return response([
                        'error' => 'Already registered social login account!',
                    ], 409);
                }
            }

            /* Register user into database */
            $streamKey = str_random(32);
            $user = new User([
                'username' => $username,
                'password' => Hash::make($request->password),
                'email' => $request->email,
                'auth_provider' => $request->auth_provider,
                'uid_oauth' => $uid_oauth,
                'stream_key' => $streamKey,
                'image_url' => $request->imageurl,
            ]);
            $user->save();
            $user = User::where('username', '=', $username)
                ->first();


            /* Give welcome credits to the user */
            $this->creditRepository->set(
                userID: $user->id,
                value: 1000000
            );
            // $credits = new Credit([
            //     'uid' => $user->id,
            //     'credits' => 1000000,
            // ]);
            // $credits->save();

            /* Generate stream private key and register it in Database */
            $response = Http::get(env('STREAMING_SERVER', null)
                . "/jwtgen/"
                . $user->id
                . "/"
                . $streamKey);
            $token = $response->json()['token'];
            DB::table("stream_webtokens")
                ->insert([
                    "uid_protected" => $user->id,
                    "uid_guardian" => $user->id,
                    "token" => $token,
                ]);



            if ($user) {
                DB::commit();
                /* Send email verification link */
                event(new Registered($user));

                Auth::attempt([
                    'username' => $user->username,
                    'password' => $request->password,
                ]);

                return response(null, 200);
            } else {
                DB::rollback();
                return response([
                    'error' => "Already Registered",
                ], 409);
            }

        } catch (QueryException $e) {
            DB::rollback();
            Log::error($e);
            return response([
                'error' => "Unexpected database error occured.
                            Admin will look into it soon.",
            ], 500);
        } catch (Exception $e) {
            DB::rollback();
            Log::error($e);
            return response([
                'error' => $e->getMessage(),
            ], 500);
        }
    }




    /**
     * Update current user's personal information.
     *
     * @param  Illuminate\Http\Request $request
     * @return Illuminate\Http\Response
     */
    public function updateUser(Request $request)
    {
        DB::beginTransaction();

        try {
            if (array_key_exists("imgFile", $_FILES))
                $this->userRepository->updateImage(Auth::id());
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            return response(null, 500);
        }

        DB::commit();
        return response([], 200);
    }




    /**
     * Delete a user from database
     *
     * @param  Illuminate\Http\Request $request
     * @return Illuminate\Http\Response
     */
    public function deleteUser(Request $request)
    {
        DB::beginTransaction();
        try {
            $this->userRepository->delete(Auth::id());
            DB::commit();
            Log::info("Deleted user " . Auth::id());

            Auth::logout();
            $request->session()->invalidate();
            return response(null, 200);
        } catch (Exception $e) {
            DB::rollBack();
            LOG::error("Error deleting user ". Auth::id() . ".\n"
                        . $e->getMessage());
            return response(['error' => "Unexpected error: contact admin!"], 500);
        }
    }




    /**
     * Create Google2FA secret and return corresponding QR Code to user..
     * This function just creates secret.
     * 2FA authentication will not be activated until user hits activation route.
     *
     *
     * @param  Illuminate\Http\Request $request
     * @return Illuminate\Http\Response
     */
    public function enable2FA(Request $request)
    {
        DB::beginTransaction();

        $user = $this->userRepository->getByID(Auth::id());
        if ($user->google2fa_active === 0) {//($user->google2fa_secret === null) {
            $google2fa = (new \PragmaRX\Google2FAQRCode\Google2FA());
            $secret = $google2fa->generateSecretKey();
            //$user->google2fa_secret = $google2fa->generateSecretKey();
            //$user->save();

            $this->userRepository->enable2FA(Auth::id(), $secret);

            $qrCodeUrl = $google2fa->getQRCodeInline(
                'Lazyboy Industries',
                'lazyboyindustries.main@gmail.com',
                $secret
            );

            DB::commit();
            return response([
                'qrCodeUrl' => $qrCodeUrl,
            ], 200);
        } else {
            DB::rollback();
            return response([
                'error' => "You already have Google OTP key",
            ], 409);
        }
    }




    /**
     * Remove 2FA feature from current user's account.
     * Previous 2FA credential will be removed from database.
     *
     * @param  Illuminate\Http\Request $request
     * @return Illuminate\Http\Response
     */
    public function disable2FA(Request $request)
    {
        try {
            $this->userRepository->disable2FA(Auth::id());
            return response(null, 200);
        } catch (Exception $e) {
            return response(null, 500);
        }
    }




    /**
     * Change user password
     *
     * @Todo: enforce max password change limit per day
     * @param  Illuminate\Http\Request $request
     * @return Illuminate\Http\Response
     */
    public function changePassword(Request $request)
    {
        DB::beginTransaction();

        $user = $this->userRepository->getByID(Auth::id());
        if (!Hash::check($request->currentPassword, $user->password)) {
            return response([
                'error' => "Incorrect Password",
            ], 401);
        }

        if ($request->newPassword !== $request->confirmPassword) {
            DB::rollback();
            return json_encode([
                'error' => "Password confirmation error. Please check Confirm Password input.",
            ], 401);
        }

        /* Store new password in database */
        $this->userRepository->changePassword(Auth::id(), $request->newPassword);
        DB::commit();

        /* Clear current session */
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Log::info("User". $user->username . "changed password");
        return json_encode(null, 200);
    }


    /* -------------------------------------------------------------------------- */
    /*                      Emergency system management APIs                      */
    /* -------------------------------------------------------------------------- */

    /**
     * File emergency report
     *
     * @return  void
     *
     * @deprecated
     *      Not used by web server anymore.
     *      Stream server implements this functionality instead.
     */
    public static function emergencyReport(Request $request)
    {
        DB::table('reports')
            ->insert([
                'uid' => Auth::id(),
                'status' => 'DANGER_URGENT_RESPONSE',
                'response' => 'RESPONSE_REQUIRED',
                'stream_key' =>
                json_decode(DB::table('users')
                        ->select('stream_key')
                        ->where('id', Auth::id())
                        ->get())[0]->stream_key,
                'responders' => strval(
                    DB::table('guardianship')
                        ->select('uid_guardian')
                        ->where('uid_protected', Auth::id())
                        ->get()
                ),
            ]);

        $result = DB::table('users')
            ->where('id', Auth::id())
            ->update(['status' => 'DANGER_URGENT']);

        return response($result, 200);
    }




    // /**
    //  * getStatus
    //  *
    //  * @param  string $uid
    //  * @return Illuminate\Http\Response
    //  */
    // public static function getStatus(string $uid)
    // {
    //     if (
    //         !empty(DB::table('guardianship')
    //             ->where('uid_guardian', Auth::id())
    //             ->where('uid_protected', $uid)
    //             ->get())
    //         or
    //         Auth::id() == $uid
    //     ) {
    //         return json_encode(DB::table('reports')
    //                 ->select('status')
    //                 ->where('uid', $uid)
    //                 ->get());
    //     }
    // }




    /**
     * Update current user's profile image.
     * Replace old image file with newer one in profile image repository.
     *
     * @param  Illuminate\Http\Request $request
     * @return Illuminate\Http\Response
     */
    public function updateUserImage(Request $request)
    {
        $filename = 'userimg_' . Auth::id();
        $imgFile = fopen("./images/users/profile/" . $filename, "w");
        $user = $this->userRepository->getByID(Auth::id());
        // $user = User::where('id', '=', Auth::id())
        //     ->first();
        $user->image_url = "/images/users/profile/" . $filename;
        $user->save();
        return response(null, 200);
    }




    /**
     * Return current user's profile image URUL.
     *
     * @param  Illuminate\Http\Request $request
     * @return Illuminate\Http\Response
     */
    public function getUserImage(Request $request)
    {
        if (!Auth::check()) {
            return response([], 204);
        }

        $user = Auth::user();
        if ($user) {
            return response(['url' => $user->image_url], 200);
        }
    }

}
