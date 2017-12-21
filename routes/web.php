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
    Route::match(['get', 'put', 'patch', 'post'], 'test', 'IndexController@test')->name('admin.test');
});



//后台路由
Route::group(['middleware' => ['auth', 'permission']], function() {
    Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function() {
        Route::get('/', 'IndexController@index')->name('admin.index');
        Route::resource('user', 'UserController');
        Route::resource('forum', 'ForumController');
        Route::resource('topic', 'TopicController');
        Route::resource('comment', 'CommentController');
        Route::resource('attachment', 'AttachmentController');
        Route::resource('huisuo', 'HuisuoJishiController');
        Route::resource('jishi', 'HuisuoJishiController');
        Route::resource('role', 'RoleController');
        Route::resource('permission', 'PermissionController');
        Route::post('upload/image', 'UploadController@image')->name("upload.image");
    });
    
    Route::get('cnarea/province', 'CommonController@province')->name('cnarea.province');
    Route::get('cnarea/city', 'CommonController@city')->name('cnarea.city');
    Route::get('cnarea/district', 'CommonController@district')->name('cnarea.district');
});
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
