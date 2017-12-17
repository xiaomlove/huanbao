<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHuisuoJishiRelationshipsTable extends Migration
{
    protected $table = 'huisuo_jishi_relationships';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->increments('id');
            $table->integer('huisuo_id')->comment('会所ID');
            $table->integer('jishi_id')->comment('技师ID');
            $table->integer('priority')->default(0)->comment('优先级，值越大优先级越高');
            
            $table->dateTime('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            
            $table->unique(['huisuo_id', 'jishi_id'], 'uk_huisuo_jishi');
            $table->index('jishi_id', 'idx_jishi');
            
            $table->index('created_at', 'idx_created_at');
            $table->index('updated_at', 'idx_updated_at');
        });
        \DB::statement("ALTER TABLE `{$this->table}` comment '会所技师关联表'");
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
