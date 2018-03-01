<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppointmentsTable extends Migration
{
    protected $table = "appointments";
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
            $table->integer('huisuo_id');
            $table->string('huisuo_name')->nullable();
            $table->integer('jishi_id');
            $table->string('jishi_name')->nullable();
            $table->dateTime('begin_time')->comment('开始时间');
            $table->dateTime('end_time')->nullable()->comment('结束时间');
            $table->tinyInteger('status')->default(0)->comment('状态，0预约状态未完成，1已出击完成');
            $table->integer('cost')->default(-1)->comment('费用');
            $table->string('remark')->default('')->comment('备注');

            $table->dateTime('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));

            $table->index('uid', 'idx_uid');
            $table->index('huisuo_id', 'idx_huisuo_id');
            $table->index('jishi_id', 'idx_jishi_id');
            $table->index('created_at', 'idx_created_at');
        });
        \DB::statement("ALTER TABLE `{$this->table}` comment '预约记录表'");
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
