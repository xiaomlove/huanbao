<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableForum extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forums', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('版块名称');
            $table->text('description')->comment('描述');
            $table->integer('pid')->default(0)->comment('父级ID');
            $table->integer('display_order')->default(0)->comment('显示顺序');
            $table->timestamps();
            $table->unique('name', 'uk_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('forums');
    }
}
