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
            $table->string('key')->comment('文件key');
            $table->bigInteger('size')->comment('文件大小');
            $table->integer('width')->nullable()->comment('宽度');
            $table->integer('height')->nullable()->comment('高度');
            $table->float('latitude', 10, 4)->nullable()->comment('纬度');
            $table->float('longitude', 10, 4)->nullable()->comment('经度');
            $table->string('location')->nullable()->comment('位置');
            
            $table->dateTime('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            
            $table->index('uid', 'idx_uid');
            $table->index('size', 'idx_size');
            $table->unique('key', 'uk_key');
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
