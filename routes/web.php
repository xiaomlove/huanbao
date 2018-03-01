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

Route::any('test', 'HomeController@test');


//后台路由
Route::group(['middleware' => ['auth', 'permission']], function() {
    Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'as' => 'admin.'], function() {
        Route::get('/', 'IndexController@index')->name('home');
        Route::resource('user', 'UserController');
        Route::match(['get', 'patch'],'user/permission/{user}', 'UserController@permission')->name('user.permission');
        Route::resource('forum', 'ForumController');
        Route::resource('forumtaxonomy', 'ForumTaxonomyController');
        Route::resource('topic', 'TopicController');
        Route::resource('comment', 'CommentController');
        Route::resource('attachment', 'AttachmentController');
        Route::resource('huisuo', 'HuisuoJishiController');
        Route::resource('jishi', 'HuisuoJishiController');
        Route::resource('huisuojishi', 'HuisuoJishiRelationshipController');
        Route::resource('role', 'RoleController');
        Route::resource('permission', 'PermissionController');
        Route::resource('report', 'ReportController');

    });
    
    Route::get('cnarea/province', 'CommonController@province')->name('cnarea.province');
    Route::get('cnarea/city', 'CommonController@city')->name('cnarea.city');
    Route::get('cnarea/district', 'CommonController@district')->name('cnarea.district');
    Route::any('upload/image', 'UploadController@image')->name("upload.image");
});

