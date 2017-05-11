<?php

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
    return view('home');
});

Route::get('/about', function () {
    return view('about');
});

// Login/Register & Social Logins
Route::get('/login', function () {return view('login');});
Route::get('/login/{provider}', ['as' => 'social.redirect', 'uses' => 'SocialAuthController@redirect']);
Route::get('/login/callback/{provider}', ['as' => 'social.callback', 'uses' => 'SocialAuthController@callback']);
Route::get('/register', function () {return view('register');});
Route::get('/logout', function () {
    Auth::logout();
    return redirect()->to('/');
});
