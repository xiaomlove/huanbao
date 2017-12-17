<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContactRelationshipsTable extends Migration
{
    protected $table = 'contact_relationships';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->increments('id');
            $table->string('owner_type')->comment('拥有者类型');
            $table->integer('owner_id')->comment('拥有者ID');
            $table->integer('contact_id')->comment('联系方式ID');
            $table->integer('priority')->default(0)->comment('优先级');
            
            $table->unique(['owner_id', 'contact_id', 'owner_type'], 'uk_type_owner_contact');
            $table->index('priority', 'idx_priority');
            
            $table->dateTime('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            
            $table->index('created_at', 'idx_created_at');
            $table->index('updated_at', 'idx_updated_at');
        });
        \DB::statement("ALTER TABLE `{$this->table}` comment '联系方式关联表'");
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
