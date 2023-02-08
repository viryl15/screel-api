<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});
//    ->name('login');

//Route::get('/email-test', function(){
//    $details['email'] = 'v15.viryl15@gmail.com';
//    $details['username'] = 'viryl15';
//    $details['followerUserName'] = 'v15_scott';
//    dispatch(new App\Jobs\SendEmailNewFollowerJob($details));
//    dd('done');
//});
