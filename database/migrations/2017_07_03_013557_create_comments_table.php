<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('key')->comment('唯一码');
            $table->integer('uid')->comment('用户ID');
            $table->integer('tid')->comment('帖子ID');
            $table->integer('pid')->default(0)->comment('父级评论ID');
            $table->integer('root_id')->default(0)->comment('一级评论ID，只有楼中楼才有');
            $table->integer('floor_num')->default(-1)->comment('楼层号，正常楼层才有');

            $table->integer('comment_count')->default(0)->comment('评论数');
            $table->integer('like_count')->default(0)->comment('被顶数');
            $table->integer('dislike_count')->default(0)->comment('被踩数');
            $table->integer('favor_count')->default(0)->comment('收藏数');
            
            $table->dateTime('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));

            $table->unique('key', 'uk_key');
            $table->index('uid', 'idx_uid');
            $table->index('tid', 'idx_tid');
            $table->index('pid', 'idx_pid');
            $table->index('root_id', 'idx_root_id');
            $table->index('floor_num', 'idx_floor_num');
            
            $table->index('comment_count', 'idx_comment_count');
            $table->index('like_count', 'idx_like_count');
            $table->index('dislike_count', 'idx_dislike_count');
            $table->index('favor_count', 'idx_favor_count');
            
            $table->index('created_at', 'idx_created_at');
            $table->index('updated_at', 'idx_updated_at');
        });
        \DB::statement("ALTER TABLE `comments` comment '评论表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments');
    }
}
