<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHuisuoJishiTable extends Migration
{
    protected $table = "huisuo_jishi_bases";
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->increments('id');
            $table->char('key', 36)->comment('唯一码');
            $table->integer('tid')->comment('关联帖子');
            $table->string('type')->comment('类型');
            $table->string('name')->comment('名称');
            $table->string('short_name')->comment('简称/工号');
            $table->string('province')->nullable()->comment('省');
            $table->string('city')->nullable()->comment('市');
            $table->string('district')->nullable()->comment('区');
            $table->string('address')->nullable()->comment('地址');
            $table->string('background_image')->nullable()->comment('背景图');

            $table->unique('key', 'uk_key');
            $table->index('tid', 'idx_tid');
            $table->index('name', 'idx_name');
            $table->index('short_name', 'idx_short_name');
            $table->index(['province', 'city', 'district'], 'idx_address');

            $table->dateTime('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));

            $table->index('created_at', 'idx_created_at');
            $table->index('updated_at', 'idx_updated_at');
        });
        \DB::statement("ALTER TABLE `{$this->table}` comment 'HS&JS基本信息表'");
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
