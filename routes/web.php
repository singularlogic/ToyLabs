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
Route::get('/login', ['as' => 'login', 'uses' => 'Auth\\LoginController@showLoginForm']);
Route::post('/login', ['as' => 'login.post', 'uses' => 'Auth\\LoginController@login']);
Route::get('/logout', ['as' => 'logout', 'uses' => 'Auth\\LoginController@logout']);

Route::get('/register', ['as' => 'register', 'uses' => 'Auth\\RegisterController@showRegistrationForm']);
Route::post('/register', ['as' => 'register.post', 'uses' => 'Auth\\RegisterController@register']);

Route::post('/password/email', ['as' => 'password.email', 'uses' => 'Auth\\ForgotPasswordController@sendResetLinkEmail']);
Route::get('/password/reset', ['as' => 'password.request', 'uses' => 'Auth\\ForgotPasswordController@showLinkRequestForm']);
Route::post('/password/reset', ['as' => 'password.request.post', 'uses' => 'Auth\\ResetPasswordController@reset']);
Route::get('/password/reset/{token}', ['as' => 'password.reset', 'uses' => 'Auth\\ResetPasswordController@showResetForm']);

Route::get('/login/{provider}', ['as' => 'social.redirect', 'uses' => 'SocialAuthController@redirect']);
Route::get('/login/callback/{provider}', ['as' => 'social.callback', 'uses' => 'SocialAuthController@callback']);
