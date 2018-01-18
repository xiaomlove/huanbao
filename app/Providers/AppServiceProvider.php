<?php

namespace App\Providers;

use App\Models\CommentDetail;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;
use App\Models\AttachmentRelationship;
use App\Models\Comment;
use App\User;
use App\Models\HuisuoJishi;
use App\Models\ContactRelationship;
use App\Observers\CommentDetailObserver;
use App\Observers\UserObserver;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \Carbon\Carbon::setLocale('zh');//设置Carbon使用中文
        \Illuminate\Support\Facades\Schema::defaultStringLength(191);//应该限制索引长度，不应该限制字符长度。坑爹这里不支持设置索引长度
        
        //记录sql语句
        $this->logSql();
        
        //监听jwt事件
        $this->listenJWTEvents();
        
        //自定义多态关联
        $this->customMorphMap();

        //监听ORM事件
        $this->listenORMEvent();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(\Faker\Generator::class, function() {
            return \Faker\Factory::create('zh_CN');
        });
    }
    
    /**
     * 手工记录sql
     */
    private function logSql()
    {
        $whiteList = [
            'select * from `users` where `id` = ? limit 1',
            'select * from `users` where `id` = ? and `remember_token` = ? limit 1',
        ];
        \DB::listen(function($query) use ($whiteList) {
            if (in_array($query->sql, $whiteList))
            {
                return;
            }
            $log = sprintf(
                "%s, sql: %s, bindngs: %s, timeuse: %sms %s",
                date('Y-m-d H:i:s'),
                $query->sql,
                json_encode($query->bindings, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES),
                $query->time,
                PHP_EOL
            );
            file_put_contents(storage_path() . '/logs/sql.log', $log, FILE_APPEND);
        });
    }
    
    private function listenJWTEvents()
    {
        \Event::listen('tymon.jwt.*', function($error) {
            if ($error != 'tymon.jwt.valid')
            {
                return response()->json(normalize($error, \Input::all()), 400);
            }
        });
    }
    
    private function customMorphMap()
    {
        Relation::morphMap([
            AttachmentRelationship::TARGET_TYPE_COMMENT_DETAIL => CommentDetail::class,
            AttachmentRelationship::TARGET_TYPE_USER_AVATAR => User::class,

            ContactRelationship::OWNER_TYPE_HUISUO => HuisuoJishi::class,
            ContactRelationship::OWNER_TYPE_JISHI => HuisuoJishi::class,
        ]);
    }

    private function listenORMEvent()
    {
        CommentDetail::observe(CommentDetailObserver::class);
        User::observe(UserObserver::class);
    }
}
