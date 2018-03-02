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
});

$middleware = ['auth:api', 'permission'];
if (config('app.env') == 'local')
{
    $middleware = [];
}
Route::group(['middleware' => $middleware, 'as' => 'api.'], function() {
    Route::group(['namespace' => 'Api'], function() {
        Route::any("test", "TestController@test")->name('test');
        Route::resource('user', 'UserController');
        Route::resource('forum', 'ForumController');
        Route::resource('topic', 'TopicController');
        Route::resource('comment', 'CommentController');
        Route::resource('commentcomment', 'CommentCommentController');
        Route::post('token/refresh', 'AuthenticateController@refreshToken')->name('token.refresh');
        Route::post('logout', 'AuthenticateController@logout')->name('logout');
    });
    Route::get('cnarea/province', 'CommonController@province')->name('cnarea.province');
    Route::get('cnarea/city', 'CommonController@city')->name('cnarea.city');
    Route::get('cnarea/district', 'CommonController@district')->name('cnarea.district');
    Route::any('upload/image', 'UploadController@image')->name("upload.image");
});