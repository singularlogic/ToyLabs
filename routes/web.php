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
Route::get('/file/{id}', ['as' => 'file', 'uses' => 'FileController@get']);

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
    Route::put('/organization/{id}/join', ['as' => 'organization.join', 'uses' => 'ProfileController@joinOrganization']);
    Route::put('/organization/{id}/leave', ['as' => 'organization.leave', 'uses' => 'ProfileController@leaveOrganization']);

    // Products/Designs/Prototypes
    Route::get('/product/create', ['as' => 'product.create', 'uses' => 'ProductController@create']);
    Route::post('/product/create', ['as' => 'product.create.post', 'uses' => 'ProductController@doCreate']);
    Route::get('/product/{id}/edit', ['as' => 'product.edit', 'uses' => 'ProductController@edit']);
    Route::post('/product/{id}/edit', ['as' => 'product.edit.post', 'uses' => 'ProductController@doEdit']);

    Route::get('/product/{id}/marketanalysis', ['as' => 'product.analysis', 'uses' => 'MarketAnalysisController@index']);
    Route::get('/product/{id}/designs', ['as' => 'product.designs', 'uses' => 'DesignController@productDesigns']);
    Route::get('/product/{id}/prototypes', ['as' => 'product.prototypes', 'uses' => 'PrototypeController@productPrototypes']);

    Route::get('/product/{id}/designs/create', ['as' => 'design.create', 'uses' => 'DesignController@create']);
    Route::post('/product/{id}/designs/create', ['as' => 'design.create.post', 'uses' => 'DesignController@doCreate']);
    Route::get('/design/{id}/edit', ['as' => 'design.edit', 'uses' => 'DesignController@edit']);
    Route::post('/design/{id}/edit', ['as' => 'design.edit.post', 'uses' => 'DesignController@doEdit']);

    Route::get('/product/{id}/prototypes/create/{design_id}', ['as' => 'prototype.makeprototype', 'uses' => 'DesignController@makePrototype']);
    Route::post('/product/{id}/prototypes/create/{design_id}', ['as' => 'prototype.makeprototype.post', 'uses' => 'PrototypeController@doCreate']);
    Route::get('/product/{id}/prototypes/create', ['as' => 'prototype.create', 'uses' => 'PrototypeController@create']);
    Route::post('/product/{id}/prototypes/create', ['as' => 'prototype.create.post', 'uses' => 'PrototypeController@doCreate']);
    Route::get('/prototype/{id}/edit', ['as' => 'prototype.edit', 'uses' => 'PrototypeController@edit']);
    Route::post('/prototype/{id}/edit', ['as' => 'prototype.edit.post', 'uses' => 'PrototypeController@doEdit']);

    // Partner Matching
    Route::get('/organizations/search', ['as' => 'organization.search', 'uses' => 'PartnerMatchingController@organizationSearch']);
    Route::get('/{type}/{id}/collaborate', ['as' => 'collaborate', 'uses' => 'PartnerMatchingController@index'])->where('type', 'design|prototype');
    Route::post('/partner/search', ['as' => 'collaborate.search', 'uses' => 'PartnerMatchingController@search']);
    Route::get('/contact/{org_id}/{type}/{id}', ['as' => 'collaborate.contact', 'uses' => 'PartnerMatchingController@contact'])->where('type', 'design|prototype');

    // Files
    Route::post('/file/upload', ['as' => 'file.upload', 'uses' => 'FileController@upload']);
    Route::delete('/file/delete', ['as' => 'file.delete', 'uses' => 'FileController@delete']);
    Route::delete('/attachment/remove', ['as' => 'attachment.delete', 'uses' => 'FileController@remove']);
});

// Public Profiles
Route::get('/organizations', ['as' => 'organizations', 'uses' => 'ProfileController@organizations']);
Route::get('/organization/{id}', ['as' => 'organization.profile', 'uses' => 'ProfileController@organizationProfile']);

// Notifications
Route::group(['prefix' => 'notifications'], function () {
    Route::get('/', ['as' => 'notifications', 'uses' => 'NotificationController@index']);
    Route::patch('/{id}/read', ['as' => 'notifications.markasread', 'uses' => 'NotificationController@markAsRead']);
    Route::post('/mark-all-read', ['as' => 'notifications.markallread', 'uses' => 'NotificationController@markAllRead']);
    Route::patch('/{id}/accept', ['as' => 'notifications.accept', 'uses' => 'NotificationController@accept']);
    Route::patch('/{id}/decline', ['as' => 'notifications.decline', 'uses' => 'NotificationController@decline']);
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
Route::post('/user/comment', ['as' => 'user.comment.post', 'uses' => 'UserController@postComment']);
Route::delete('/user/comment/{id}', ['as' => 'user.comment.delete', 'uses' => 'UserController@deleteComment']);

// Product/Design/Prototype Details
Route::get('/product/{id}', ['as' => 'product.details', 'uses' => 'ProductController@showProduct']);
Route::get('/design/{id}', ['as' => 'design.details', 'uses' => 'ProductController@showDesign']);
Route::get('/prototype/{id}', ['as' => 'prototype.details', 'uses' => 'ProductController@showPrototype']);

// API
Route::get('/api/states/{id}', 'APIController@countryStates');
Route::get('/api/cities/{id}', 'APIController@stateCities');
