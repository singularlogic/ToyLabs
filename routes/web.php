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
Route::get('/feed/{tab?}', ['as' => 'feed', 'uses' => 'NotificationController@feed']);

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

// Notifications
Route::group(['prefix' => 'notifications'], function () {
    Route::get('/', ['as' => 'notifications', 'uses' => 'NotificationController@index']);
    Route::patch('/{id}/read', ['as' => 'notifications.markasread', 'uses' => 'NotificationController@markAsRead']);
    Route::post('/mark-all-read', ['as' => 'notifications.markallread', 'uses' => 'NotificationController@markAllRead']);
});

// Messages
Route::get('/feed/message/{id}', ['as' => 'message.view', 'uses' => 'NotificationController@feed']);
Route::group(['prefix' => 'messages'], function () {
    Route::get('/', ['as' => 'messages', 'uses' => 'MessagesController@index']);
    Route::get('users', ['as' => 'messages.users', 'uses' => 'MessagesController@users']);
    Route::get('unread', ['as' => 'messages.unread', 'uses' => 'MessagesController@unread']);
    Route::post('/', ['as' => 'messages.store', 'uses' => 'MessagesController@store']);
    Route::get('{id}', ['as' => 'messages.show', 'uses' => 'MessagesController@show']);
    Route::put('{id}', ['as' => 'messages.update', 'uses' => 'MessagesController@update']);
});

Route::get('/user/likes', ['as' => 'user.likes', 'uses' => 'UserController@getLikes']);
Route::put('/user/like/{type}/{id}', ['as' => 'user.like', 'uses' => 'UserController@like']);
Route::put('/user/unlike/{type}/{id}', ['as' => 'user.like', 'uses' => 'UserController@unlike']);

// Product/Design/Prototype Details
Route::get('/product/{id}', ['as' => 'product.details', 'uses' => 'ProductController@showProduct']);
Route::get('/design/{id}', ['as' => 'design.details', 'uses' => 'ProductController@showDesign']);
Route::get('/prototype/{id}', ['as' => 'prototype.details', 'uses' => 'ProductController@showPrototype']);
