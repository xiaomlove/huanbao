<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTopicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('topics', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->comment('标题');
            $table->integer('uid')->comment('用户ID');
            $table->integer('fid')->default(0)->comment('版块ID');
            $table->integer('view_count')->default(0)->comment('阅读数');
            $table->integer('comment_count')->default(0)->comment('评论数');
            $table->integer('like_count')->default(0)->comment('被顶数');
            $table->integer('dislike_count')->default(0)->comment('被踩数');
            $table->integer('favor_count')->default(0)->comment('收藏数');
            $table->integer('last_comment_time')->default(0)->comment('最后评论时间');
            $table->integer('last_comment_id')->default(0)->comment('最后评论ID');
            $table->tinyInteger('is_sticky')->default(0)->comment('是否置顶');
            
            $table->dateTime('created_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->dateTime('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            
            $table->index('title', 'idx_title');
            $table->index('uid', 'idx_uid');
            $table->index('view_count', 'idx_view_count');
            $table->index('comment_count', 'idx_comment_count');
            $table->index('like_count', 'idx_like_count');
            $table->index('dislike_count', 'idx_dislike_count');
            $table->index('favor_count', 'idx_favor_count');
            $table->index('last_comment_time', 'idx_last_comment_time');
            $table->index('created_at', 'idx_created_at');
            $table->index('updated_at', 'idx_updated_at');
        });
        \DB::statement("ALTER TABLE `topics` comment '帖子表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('topics');
    }
}
