<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableName = 'comment_details';
        Schema::create($tableName, function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cid')->comment('评论ID');
            $table->text('content')->comment('内容详情');
            
            $table->index('cid', 'idx_cid');
        });
        \DB::statement("ALTER TABLE `$tableName` comment '评论详情表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comment_detail');
    }
}
