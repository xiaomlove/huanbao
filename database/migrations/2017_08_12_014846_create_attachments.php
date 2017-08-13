<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttachments extends Migration
{
    protected $table = 'attachments';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->increments('id');
            $table->integer('uid')->comment('用户UID');
            $table->string('mime_type')->comment('媒体类型');
            $table->string('dirname')->comment('保存目录名');
            $table->string('basename')->comment('基本名称，包含后缀');
            $table->bigInteger('size')->comment('文件大小');
            
            $table->dateTime('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            
            $table->index('uid', 'idx_uid');
            $table->index('size', 'idx_size');
            $table->index('basename', 'idx_basename');
            $table->index('created_at', 'idx_created_at');
            $table->index('updated_at', 'idx_updated_at');
        });
        \DB::statement("ALTER TABLE `{$this->table}` comment '附件表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->table);
    }
}
