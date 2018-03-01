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
            $table->string('key')->comment('唯一码');
            $table->string('title')->comment('标题');
            $table->integer('uid')->comment('用户ID');
            $table->integer('fid')->default(0)->comment('版块ID');
            $table->integer('view_count')->default(0)->comment('阅读数');
            $table->integer('comment_count')->default(0)->comment('评论(回复)数');
            
            $table->integer('last_comment_time')->default(0)->comment('最后评论时间');
            $table->integer('last_comment_id')->default(0)->comment('最后评论ID');

            $table->dateTime('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));

            $table->unique('key', 'uk_key');
            $table->index('title', 'idx_title');
            $table->index('uid', 'idx_uid');
            $table->index('view_count', 'idx_view_count');
            $table->index('comment_count', 'idx_comment_count');
            
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
