<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnWidthHeightInAttachmentsTable extends Migration
{
    protected $table = 'attachments';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table($this->table, function (Blueprint $table) {
            $table->integer('width')->nullable()->comment('宽度')->after('size');
            $table->integer('height')->nullable()->comment('高度')->after('width');
            $table->float('latitude', 10, 4)->nullable()->comment('纬度')->after('height');
            $table->float('longitude', 10, 4)->nullable()->comment('经度')->after('latitude');
            $table->string('location')->nullable()->comment('位置')->after('longitude');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table($this->table, function (Blueprint $table) {
            $table->dropIfExists(['width', 'height', 'latitude', 'longitude', 'location']);
        });
    }
}
