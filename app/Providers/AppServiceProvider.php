<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Carbon::setLocale('zh');//设置Carbon使用中文
        Schema::defaultStringLength(191);//应该限制索引长度，不应该限制字符长度。坑爹这里不支持设置索引长度
        //记录sql语句
        $this->logSql();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
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
}
