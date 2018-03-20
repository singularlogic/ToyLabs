<?php

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
Route::group([
    'prefix' => 'v1',
], function () {
    Route::post('login', 'API\PassportController@login');
    Route::post('social/{provider}', 'SocialAuthController@passportCallback');
});

Route::group([
    'prefix'     => 'v1',
    'middleware' => 'auth:api',
], function () {
    Route::post('logout', 'API\PassportController@logout');
    Route::get('user', 'API\PassportController@getDetails');

    Route::get('file/{id}', ['as' => 'file', 'uses' => 'FileController@get']);
    Route::get('thumb/{id}.jpg', ['as' => 'file', 'uses' => 'FileController@getThumb']);

    Route::get('ar-models', 'API\PassportController@getModels');
    Route::get('ar-model/{id}/download', 'API\PassportController@downloadModel');
    Route::get('ar-model/{id}/questions', 'API\PassportController@getQuestions');
    Route::post('ar-model/{id}/feedback', 'API\PassportController@postFeedback');
});
