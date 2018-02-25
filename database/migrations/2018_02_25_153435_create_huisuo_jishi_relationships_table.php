<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHuisuoJishiRelationshipsTable extends Migration
{
    protected $table = "huisuo_jishi_relationships";
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->increments('id');
            $table->integer('huisuo_id');
            $table->string('huisuo_short_name');
            $table->integer('jishi_id');
            $table->string('jishi_short_name');
            $table->dateTime('begin_time')->comment('开始时间');
            $table->dateTime('end_time')->nullable()->comment('结束时间');

            $table->dateTime('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));

            $table->index('huisuo_id', 'idx_huisuo_id');
            $table->index('jishi_id', 'idx_jishi_id');
        });
        \DB::statement("ALTER TABLE `{$this->table}` comment 'HS&JS关联表'");
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
