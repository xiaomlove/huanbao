<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserFollowingsTable extends Migration
{
    protected $table = "user_followings";
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->increments('id');
            $table->integer('uid');
            $table->string('target_type')->comment('类型，如user,hs,js,topic');
            $table->integer('target_id');

            $table->dateTime('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));

            $table->unique(['uid', 'target_type', 'target_id'], 'uk_uid_target');
            $table->index('target_id', 'idx_target_id');
        });
        \DB::statement("ALTER TABLE `{$this->table}` comment '用户关注表'");
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
