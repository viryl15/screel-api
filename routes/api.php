<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'auth', 'middleware' => ['cors', 'web'],], function ($router) {
    // connect the user
    Route::get('/social-login/{provider}', [\App\Http\Controllers\AuthController::class, 'socialLogin']);
//    Route::get('/login-github', function () {
//        return Socialite::driver('github')->stateless()->redirect();
//    });->middleware('auth:api');

    Route::get('/github-callback', [\App\Http\Controllers\AuthController::class, 'githubLogin1']);
    Route::get('/google-callback', [\App\Http\Controllers\AuthController::class, 'googleLogin']);
    Route::get('/twitter-callback', [\App\Http\Controllers\AuthController::class, 'twitterLogin']);

    Route::post('/github-callback', [\App\Http\Controllers\AuthController::class, 'githubLogin']);

    Route::group(['middleware' => ['cors', 'auth:api']], function (){
        Route::get('/me', [\App\Http\Controllers\AuthController::class, 'me']);
        Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout'])->name('logout.api');
    });
});

