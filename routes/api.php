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

//API 路由

Route::group(['namespace' => 'Api', 'as' => 'api.'], function() {

    Route::post('login', 'AuthenticateController@login')->name('login');
    Route::post('token/refresh', 'AuthenticateController@refreshToken')->name('token.refresh');

    $middleware = ['auth:api', 'permission'];
    if (config('app.env') == 'local')
    {
        $middleware = [];
    }

    Route::group(['middleware' => $middleware], function() {
        Route::any("test", "TestController@test")->name('test');
        Route::resource('user', 'UserController');
        Route::resource('forum', 'ForumController');
        Route::resource('topic', 'TopicController');
        Route::resource('comment', 'CommentController');
        Route::post('upload/image', 'UploadController@image')->name('upload.image');
    });
});
