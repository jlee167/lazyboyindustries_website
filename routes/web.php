<?php


use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\PasswordReset;

use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\StreamController;
use App\Http\Controllers\CommerceController;
use App\Http\Controllers\PeerController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Redirect;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/* Middleware Macros */
const FULL_SECURITY_CHECK = ['xss', 'auth', 'verified', '2fa'];
const FULL_SECURITY_CHECK_SOFT = ['xss-soft', 'auth', 'verified', '2fa'];


/* -------------------------------------------------------------------------- */
/*                            Authentication Routes                           */
/* -------------------------------------------------------------------------- */

Route::get('/home', function(Request $request){
    return view('main');
});

Route::get('/login/redirect/views/{view}', function (Request $request, $view) {
    $redirectUrl = str_replace('/login/redirect', '', $request->fullUrl());
    return redirect()->intended($redirectUrl);
})->middleware(FULL_SECURITY_CHECK);

Route::get('/login/redirect/views/{commerce_view}/{product_id}', function (Request $request, $view) {
    $redirectUrl = str_replace('/login/redirect', '', $request->fullUrl());
    return redirect()->intended($redirectUrl);
})->middleware(FULL_SECURITY_CHECK);


Route::get('/views/login', function (Request $request) {
    if ($request->has('redirect')) {
        $redirectUrl = $request->input('redirect');
    } else {
        $origin = request()->headers->get('origin');
        $referrer = request()->headers->get('referer');
        $redirectUrl = $origin ? $origin : $referrer;
    }

    if (Auth::check()){
        return redirect("/");
    }
    else {
        return view('login', ['redirectUrl' => $redirectUrl]);
    }
})->middleware('xss')->name('login');


Route::get('/views/google2fa', function () {
    return view('google2fa');
})->middleware(['xss', 'auth', 'verified'])->name('2fa');


Route::get('/views/register', function () {
    if (Auth::check())
        return redirect()->intended();
    else
        return view('register');
})->middleware('xss')->name('register');


Route::post(
    '/logout',
    [LoginController::class, 'logout']
);

Route::post(
    '/auth',
    [LoginController::class, 'authenticate']
)->middleware('xss');


Route::get('/auth_state', function (Request $request) {
    return response([], 200);
})->middleware(['auth']);


Route::post(
    '/auth/kakao',
    [LoginController::class, 'authWithKakao']
)->middleware('xss');

Route::post(
    '/auth/google',
    [LoginController::class, 'authWithGoogle']
)->middleware('xss');

Route::get(
    '/members/2fa-key',
    [UserController::class, 'enable2FA']
)->middleware(['xss', 'auth', 'verified']);

Route::delete(
    '/members/2fa-key',
    [UserController::class, 'disable2FA']
)->middleware(['xss', 'auth', 'verified']);

Route::put(
    '/members/password',
    [UserController::class, 'changePassword']
)->middleware(['xss', 'auth']);;

Route::post(
    '/auth/2fa',
    [LoginController::class, 'authWithGoogle2FA']
)->middleware(['xss', 'auth', 'verified']);


Route::post(
    '/auth/2fa/activate',
    [LoginController::class, 'activateGoogle2FA']
)->middleware(['xss', 'auth', 'verified']);


Route::get(
    '/email/resend',
    [UserController::class, 'resendEmail']
)->middleware(['xss', 'auth', 'throttle:6,1'])
->name('verification.send');


Route::get('/email/verify', function () {
    return view('verify-email');
})->name('verification.notice');


Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/views/main');
})->middleware(['auth'])
->name('verification.verify');

Route::get('/forgot-password', function () {
    return view('recover-password');
})->middleware('guest')->name('password.request');


Route::post('/forgot-password', function (Request $request) {

    Log::info($request);

    $request->validate(['email' => 'required|email']);

    $status = Password::sendResetLink(
        $request->only('email')
    );

    Log::info(Password::RESET_LINK_SENT);
    Log::info($status);

    return $status === Password::RESET_LINK_SENT
                ? back()->with(['status' => __($status)])
                : back()->withErrors(['email' => __($status)]);
})->middleware('guest')->name('password.email');


Route::get('/reset-password/{token}', function ($token) {
    return view('reset-password', ['token' => $token]);
})->middleware('guest')->name('password.reset');


Route::post('/reset-password', function (Request $request) {

    Log::info($request);

    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:8|confirmed',
    ]);

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
            $user->forceFill([
                'password' => Hash::make($password)
            ]);//->setRememberToken(Str::random(60));

            $user->save();

            event(new PasswordReset($user));
        }
    );

    Log::info($status);

    return $status === Password::PASSWORD_RESET
                ? redirect()->route('login')->with('status', __($status))
                : back()->withErrors(['email' => [__($status)]]);
})->middleware('guest')->name('password.update');


Route::get(
    '/self/webtoken',
    [StreamController::class, 'getMyWebToken']
)->middleware(FULL_SECURITY_CHECK);

Route::post(
    '/self/webtoken',
    [UserController::class, 'refreshWebToken']
)->middleware(FULL_SECURITY_CHECK);

Route::get(
    '/{stream_id}/webtoken',
    [StreamController::class, 'getStreamWebToken']
)->middleware(FULL_SECURITY_CHECK);


/* -------------------------------------------------------------------------- */
/*                           /Authentication Routes                           */
/* -------------------------------------------------------------------------- */





/* -------------------------------------------------------------------------- */
/*                                 View Routes                                */
/* -------------------------------------------------------------------------- */


Route::get('/', function () {
    return redirect()->route('main');
});

Route::get('/views/main', function () {
    return view('main');
})->name("main");

Route::get('/views/broadcast', function () {
    return view('broadcast');
})->middleware(FULL_SECURITY_CHECK);

Route::get('/views/emergency_broadcast', function () {
    return view('emergency_broadcast');
});

Route::get('/views/createpost', function () {
    return view('createpost');
})->middleware(FULL_SECURITY_CHECK);

Route::get('/views/peers', function () {
    return view('peers');
})->middleware(FULL_SECURITY_CHECK);

Route::get('/views/sales/{product_id}', function () {
    return view('sales');
});

Route::get('/views/emergency_broadcast/{stream_id}', function () {
    return view('emergency_broadcast');
});

Route::get('/views/cart', function () {
    return view('cart');
})->middleware(FULL_SECURITY_CHECK);

Route::get('/views/purchase_history', function () {
    return view('purchase_history');
})->middleware(FULL_SECURITY_CHECK);


Route::get('/views/user-info', function () {
    return view('user-info');
})->middleware(FULL_SECURITY_CHECK);


/* Routes for views that do not require authentication */
Route::get('/views/{php_view_file}', function ($php_view_file) {
    return view($php_view_file);
});

/* -------------------------------------------------------------------------- */
/*                                /View Routes                                */
/* -------------------------------------------------------------------------- */





/* -------------------------------------------------------------------------- */
/*                               REST API Routes                              */
/* -------------------------------------------------------------------------- */


/* --------------------------- Server status check -------------------------- */
Route::get('/ping', function () {
    return 'Lazyboy Auth Server is up and running!';
});

/* -------------------------- User information CRUD ------------------------- */
Route::get(
    '/members/{username}',
    [UserController::class, 'getUser']
)->middleware(FULL_SECURITY_CHECK);

Route::post(
    '/members/{username}',
    [UserController::class, 'registerUser']
);

Route::post(
    '/members',
    [UserController::class, 'updateUser']
)->middleware(['auth']);

Route::delete(
    '/members',
    [UserController::class, 'deleteUser']
)->middleware(['xss', 'auth']);

/* Todo: change to PUT method */
Route::post(
    '/members/profile_image',
    [UserController::class, 'updateUserImage']
)->middleware(FULL_SECURITY_CHECK);


Route::get(
    '/self/profile_image',
    [UserController::class, 'getUserImage']
);

Route::get(
    '/self/uid',
    function () {
        if (!Auth::check())
            return null;
        return Auth::id();
    }
)->middleware(['auth', 'verified']);

Route::get(
    '/self',
    [UserController::class, 'getSelfProfile']
)->middleware(['xss', 'auth']);

Route::get(
    '/members/uid/{username}',
    [UserController::class, 'getUserId']
)->middleware(['auth', 'verified']);




/* ------------------------------- Forum CRUD ------------------------------- */
Route::get(
    '/forum/{forum_name}/post/{post_id}',
    [ForumController::class, 'getPost']
);

Route::post(
    '/forum/{forum_name}/post',
    [ForumController::class, 'createPost']
);

Route::put(
    '/forum/{forum_name}/post/{post_id}',
    [ForumController::class, 'updatePost']
);

Route::delete(
    '/forum/{forum_name}/post/{post_id}',
    [ForumController::class, 'deletePost']
);


Route::post(
    '/forum/{forum_name}/post/{post_id}/like',
    [ForumController::class, 'togglePostLike']
)->middleware(FULL_SECURITY_CHECK);

Route::get(
    '/forum/comment/{comment_id}',
    [ForumController::class, 'getComment']
)->middleware(['auth', 'verified', '2fa']);

Route::post(
    '/forum/comment',
    [ForumController::class, 'postComment']
)->middleware(['auth', 'verified', '2fa']);

Route::put(
    '/forum/comment/{comment_id}',
    [ForumController::class, 'updateComment']
)->middleware(['auth', 'verified', '2fa']);

Route::delete(
    '/forum/comment/{comment_id}',
    [ForumController::class, 'deleteComment']
)->middleware(['auth', 'verified', '2fa']);

Route::get(
    '/forum/{forum_name}/page/{page}/{keyword?}',
    [ForumController::class, 'getPage']
)->middleware('xss');


Route::get(
    '/forum/{forum_name}/page/{page}/tag/{tag}',
    [ForumController::class, 'getPageByTag']
)->middleware('xss');


Route::get(
    '/forum/all_forums/top_posts',
    [ForumController::class, 'getTopPosts']
);

Route::get(
    '/forum/all_forums/trending_posts',
    [ForumController::class, 'getTrendingPosts']
);


/* ------------------------- Guardianship Management ------------------------ */
Route::get(
    '/members/guardian/all',
    [PeerController::class, 'getGuardians']
)->middleware(FULL_SECURITY_CHECK);

Route::post(
    '/members/guardian/{username}',
    [PeerController::class, 'addGuardian']
)->middleware(FULL_SECURITY_CHECK);


Route::delete(
    '/members/guardian/{uid}',
    [PeerController::class, 'deleteGuardian']
)->middleware(FULL_SECURITY_CHECK);


Route::get(
    '/members/protected/all',
    [PeerController::class, 'getProtecteds']
)->middleware(FULL_SECURITY_CHECK);


Route::post(
    '/members/protected/{username}',
    [PeerController::class, 'addProtected']
)->middleware(FULL_SECURITY_CHECK);


Route::delete(
    '/members/protected/{uid}',
    [PeerController::class, 'deleteProtected']
)->middleware(FULL_SECURITY_CHECK);



Route::put(
    '/peer_request',
    [PeerController::class, 'respondPeerRequest']
)->middleware(FULL_SECURITY_CHECK);

Route::get(
    '/pending_requests',
    [PeerController::class, 'getPendingRequests']
)->middleware(FULL_SECURITY_CHECK);


/* ---------------------------- Emergency Actions --------------------------- */
Route::post(
    '/emergency/report',
    [UserController::class, 'emergencyReport']
)->middleware(FULL_SECURITY_CHECK);
Route::get(
    '/emergency/{username}/status',
    [PeerController::class,   'getStatus']
)->middleware(FULL_SECURITY_CHECK);

Route::get(
    '/stream/{uid_protected}/web_token',
    [StreamController::class, 'getWebToken']
)->middleware(FULL_SECURITY_CHECK);
Route::get(
    '/stream/{uid_protected}',
    [StreamController::class, 'findStreamByUid']
)->middleware(FULL_SECURITY_CHECK);


/* ----------------------------- E-Commerce API ----------------------------- */
Route::get(
    '/product/info/{product_id}',
    [CommerceController::class, 'getProductInfo']
);

Route::post(
    '/product/order',
    [CommerceController::class, 'purchase']
)->middleware(FULL_SECURITY_CHECK);

Route::post(
    '/product/cart/item',
    [CommerceController::class, 'addToCart']
)->middleware(FULL_SECURITY_CHECK);

Route::get(
    '/product/cart',
    [CommerceController::class, 'getCart']
)->middleware(FULL_SECURITY_CHECK);

Route::post(
    '/product/order/cart/all',
    [CommerceController::class, 'orderAllInCart']
)->middleware(['auth', 'verified', '2fa']); /* @todo: xss check */

Route::get(
    '/product/order/history',
    [CommerceController::class, 'getPurchaseHistory']
)->middleware(FULL_SECURITY_CHECK);

Route::get(
    '/credits',
    [CommerceController::class, 'getCredits']
)->middleware(FULL_SECURITY_CHECK);

Route::delete(
    '/product/cart/item/{product_id}',
    [CommerceController::class, 'deleteCartItem']
)->middleware(FULL_SECURITY_CHECK);

Route::post(
    '/product/review',
    [CommerceController::class, 'submitReview']
)->middleware(FULL_SECURITY_CHECK);

Route::get(
    '/product/review',
     [CommerceController::class, 'getReview']
)->middleware(FULL_SECURITY_CHECK);

/* -------------------------------------------------------------------------- */
/*                              /Rest API Routes                              */
/* -------------------------------------------------------------------------- */




/* -------------------------------------------------------------------------- */
/*                              Debugging Routes                              */
/* -------------------------------------------------------------------------- */


use App\Models\User;
Route::get('/test/{username}', function(){
    return User::where('username', '=', "testuser1")->get();
});


if (env('APP_ENV') != 'production') {

    /* SQL Injection Simulation */
    route::post('/sqltest', function (Request $request) {
        $mysqli = new mysqli("localhost", "root", "", "lazyboyserver");
        // Check connection
        if ($mysqli->connect_errno) {
            echo "Failed to connect to MySQL: " . $mysqli->connect_error;
            exit();
        }
        //return "SELECT id FROM users where username = '{$request->input("query")}';";
        // Perform query
        if ($result = $mysqli->query("SELECT id FROM users where username = '{$request->input("query")}';")) {
            while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                $myArray[] = $row;
            }
            echo json_encode($myArray);
        } else {
            echo 'NOOOO';
        }

        $mysqli->close();
    });
}


/* -------------------------------------------------------------------------- */
/*                             /Debugging Routes                              */
/* -------------------------------------------------------------------------- */
