<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\SupportController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/auth_state', function (Request $request) {
    if (Auth::check())
        return response(200, []);
    else
        return response(401, []);
});


/* ---------------------------- Support requests ---------------------------- */
Route::post(
    '/support_request',
    [SupportController::class, 'requestSupport']
);
