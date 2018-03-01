<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePreAppointmentsTable extends Migration
{
    protected $table = "pre_appointments";
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
            $table->integer('jishi_id');
            $table->integer('priority')->default(0);

            $table->dateTime('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));

            $table->index('uid', 'idx_uid');
            $table->index('jishi_id', 'idx_jishi_id');
            $table->index('priority', 'idx_priority');
        });
        \DB::statement("ALTER TABLE `{$this->table}` comment '待约记录表'");
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
