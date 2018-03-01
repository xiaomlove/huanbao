<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('key')->comment('唯一码');
            $table->string('name')->default('')->comment('用户名');
            $table->string('email')->comment('邮箱');
            $table->string('password')->comment('密码');
            $table->rememberToken()->comment('记住我凭证');
            $table->string('avatar')->default("")->comment('头像');
            $table->integer('point_counts')->default(0)->comment('积分数');
            $table->integer('topic_counts')->default(0)->comment('发帖数');
            $table->integer('comment_counts')->default(0)->comment('回复数');
            $table->integer('following_counts')->default(0)->comment('关注数');
            $table->integer('fans_counts')->default(0)->comment('粉丝数');
            $table->dateTime('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));

            $table->unique('key', 'uk_key');
            $table->unique('name', 'uk_name');
            $table->unique('email', 'uk_email');
            $table->index('password', 'idx_password');
            $table->index('remember_token', 'idx_remember_token');
            $table->index('point_counts', 'idx_point_counts');
            $table->index('topic_counts', 'idx_topic_counts');
            $table->index('comment_counts', 'idx_comment_counts');
            $table->index('following_counts', 'idx_following_counts');
            $table->index('fans_counts', 'idx_fans_counts');

            $table->index('created_at', 'idx_created_at');
            $table->index('updated_at', 'idx_updated_at');
        });
        \DB::statement("ALTER TABLE `users` comment '用户表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
