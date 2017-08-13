<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttachmentRelationship extends Migration
{
    protected $table = 'attachment_relationships';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->increments('id');
            $table->string('target_type')->comment('关联目标类型');
            $table->integer('target_id')->comment('关联目标ID');
            $table->integer('attachment_id')->comment('附件ID');
            $table->integer('priority')->default(0)->comment('优先级，值越大优先级越高');
            
            $table->dateTime('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            
            $table->unique(['target_id', 'target_type', 'attachment_id'], 'uk_target');
            $table->index('attachment_id', 'idx_attachment');
            
            $table->index('created_at', 'idx_created_at');
            $table->index('updated_at', 'idx_updated_at');
        });
        \DB::statement("ALTER TABLE `{$this->table}` comment '附件关联表'");
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
