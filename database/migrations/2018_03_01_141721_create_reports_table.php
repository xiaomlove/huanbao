<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportsTable extends Migration
{
    protected $table = "reports";
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->increments('id');
            $table->char('key', 36);
            $table->integer('uid');
            $table->integer('tid');
            $table->integer('jishi_id');
            $table->string('jishi_name');
            $table->integer('huisuo_id');
            $table->string('huisuo_name');

            $table->integer('jishi_top_value')->default(-1)->comment('上路分值');
            $table->text('jishi_top_description')->nullable()->comment('上路描述');
            $table->integer('jishi_middle_value')->default(-1)->comment('中路分值');
            $table->text('jishi_middle_description')->nullable()->comment('中路描述');
            $table->integer('jishi_bottom_value')->default(-1)->comment('下路分值');
            $table->text('jishi_bottom_description')->nullable()->comment('下路描述');
            $table->integer('jishi_figure_value')->default(-1)->comment('身材分值');
            $table->text('jishi_figure_description')->nullable()->comment('身材描述');
            $table->integer('jishi_appearance_value')->default(-1)->comment('颜值分值');
            $table->text('jishi_appearance_description')->nullable()->comment('颜值描述');
            $table->integer('jishi_attitude_value')->default(-1)->comment('态度分值');
            $table->text('jishi_attitude_description')->nullable()->comment('态度描述');
            $table->integer('jishi_technique_value')->default(-1)->comment('技术分值');
            $table->text('jishi_technique_description')->nullable()->comment('技术描述');

            $table->integer('huisuo_environment_facility_value')->default(-1)->comment('环境设施分值');
            $table->text('huisuo_environment_facility_description')->nullable()->comment('环境设施描述');
            $table->integer('huisuo_service_attitude_value')->default(-1)->comment('服务态度分值');
            $table->text('huisuo_service_attitude_description')->nullable()->comment('服务态度描述');

            $table->dateTime('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));

            $table->unique('key', 'uk_key');
            $table->unique('tid', 'uk_tid');
            $table->index('uid', 'idx_uid');
            $table->index('jishi_id', 'idx_jishi_id');
            $table->index('jishi_name', 'idx_jishi_name');
            $table->index('huisuo_id', 'idx_huisuo_id');
            $table->index('huisuo_name', 'idx_huisuo_name');

        });
        \DB::statement("ALTER TABLE `{$this->table}` comment '报告表'");
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
