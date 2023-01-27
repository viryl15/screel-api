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

Route::group(['prefix' => 'v1', 'middleware' => ['cors'],], function ($router) {
    // authentication
    Route::group(['prefix' => 'auth',], function (){
        Route::get('/social-login/{provider}', [\App\Http\Controllers\AuthController::class, 'socialLogin']);
    //    Route::get('/login-github', function () {
    //        return Socialite::driver('github')->stateless()->redirect();
    //    });->middleware('auth:api');

        Route::get('/github-callback', [\App\Http\Controllers\AuthController::class, 'githubLogin1']);
        Route::get('/google-callback', [\App\Http\Controllers\AuthController::class, 'googleLogin']);
        Route::get('/twitter-callback', [\App\Http\Controllers\AuthController::class, 'twitterLogin']);

        Route::post('/github-callback', [\App\Http\Controllers\AuthController::class, 'githubLogin']);

        Route::group(['middleware' => ['auth:api']], function () {
            Route::get('/me', [\App\Http\Controllers\AuthController::class, 'me']);
            Route::get('/{username}', [\App\Http\Controllers\AuthController::class, 'getUserDetails'])->name('user.details.api');
            Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout'])->name('logout.api');
        });
    });
    Route::group(['prefix' => 'public',], function (){
        Route::get('/{username}', [\App\Http\Controllers\AuthController::class, 'getPublicUserDetails'])->name('public.user.details.api');
    });
    Route::group(['prefix' => 'screel', 'middleware' => ['auth:api']], function (){
        Route::post('/store', [\App\Http\Controllers\ScreelController::class, 'store'])->name('screel.store.api');
        Route::delete('/{id}', [\App\Http\Controllers\ScreelController::class, 'deleteScreel'])->name('screel.delete.api');
        Route::get('/user/{id}', [\App\Http\Controllers\ScreelController::class, 'getUserScreels'])->name('user.screels.api');
    });
    Route::group(['prefix' => 'tags', 'middleware' => ['auth:api']], function (){
        Route::get('/', [\App\Http\Controllers\TagController::class, 'index'])->name('tags.api');
    });
    Route::group(['prefix' => 'feeds', 'middleware' => ['auth:api']], function (){
        Route::get('/', [\App\Http\Controllers\ScreelController::class, 'index'])->name('feeds.api');
    });
    Route::group(['prefix' => 'screelers', 'middleware' => ['auth:api']], function (){
        Route::get('/', [\App\Http\Controllers\UserController::class, 'index'])->name('screelers.api');
        Route::post('/follow', [\App\Http\Controllers\UserController::class, 'followScreeler'])->name('screelers.follow.api');
        Route::post('/unfollow', [\App\Http\Controllers\UserController::class, 'unfollowScreeler'])->name('screelers.unfollow.api');
        Route::get('/followers/{username}', [\App\Http\Controllers\UserController::class, 'getFollowers'])->name('screelers.followers.api');
        Route::get('/followings/{username}', [\App\Http\Controllers\UserController::class, 'getFollowings'])->name('screelers.followings.api');
    });
    Route::group(['prefix' => 'features', 'middleware' => ['auth:api']], function (){
        Route::post('/create', [\App\Http\Controllers\ScreelFeatureController::class, 'store'])->name('features.store.api');
    });
});

