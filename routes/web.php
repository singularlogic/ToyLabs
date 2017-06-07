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

Route::get('/', 'HomeController@index')->name('home');
Route::get('/about', function () {return view('about');})->name('about');

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

Route::get('/project/{id}', ['as' => 'project.guestview', 'uses' => 'ProjectController@guestview']);

// Routes for logged users
Route::group(['middleware' => 'auth'], function () {
    // Dashboard
    Route::get('/dashboard', ['as' => 'dashboard', 'uses' => 'DashboardController@show']);
    // Personal Profile
    Route::get('/profile/edit', ['as' => 'profile.edit', 'uses' => 'ProfileController@showPersonalProfile']);
    Route::post('/profile/edit', ['as' => 'profile.edit.post', 'uses' => 'ProfileController@savePersonalProfile']);
    // Organization Profile
    Route::get('/organization/edit', ['as' => 'organization.edit.mine', 'uses' => 'ProfileController@showMyOrganizationProfile']);
    Route::get('/organization/{id}/edit', ['as' => 'organization.edit', 'uses' => 'ProfileController@showOrganizationProfile']);
    Route::post('/organization/edit', ['as' => 'organization.edit.post', 'uses' => 'ProfileController@saveOrganizationProfile']);
});
