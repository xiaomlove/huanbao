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
    return view('welcome');
});
//注册常用认证路由，如注册，登录/退出，找回密码。@see Illuminate\Routing\Router::auth()
Auth::routes();

Route::get('home', 'HomeController@index')->name('home');
Route::get('api', function() {
    return file_get_contents(resource_path('views/d520fd5be6e91bafcae36178781ed907.html'));
});

Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function() {
    Route::match(['get', 'put', 'patch', 'post'], 'test', 'IndexController@test');
});



//后台路由
Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => 'auth'], function() {
    Route::get('/', 'IndexController@index')->name('admin.index');
    Route::resource('user', 'UserController');
    Route::resource('forum', 'ForumController');
    Route::resource('topic', 'TopicController');
    Route::resource('comment', 'CommentController');
});