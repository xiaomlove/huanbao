<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateForumTaxonomiesTable extends Migration
{
    protected $table = 'forum_taxonomies';
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
            $table->string('name')->comment('名称');
            $table->string('icon')->nullable()->comment('图标');
            $table->integer('display_order')->default(0)->comment('显示顺序');

            $table->dateTime('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));

            $table->unique('key', 'uk_key');
            $table->index('display_order', 'idx_display_order');
        });
        \DB::statement("ALTER TABLE {$this->table} comment '版块分类表'");
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
