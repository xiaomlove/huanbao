<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHuisuoJishisTable extends Migration
{
    protected $table = 'huisuo_jishis';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->increments('id');
            $table->string('creator')->default('')->comment('创建者');
            $table->string('name')->comment('名称');
            $table->string('type_flag')->comment('类型标识, huisuo为会所，jishi为技师');
            $table->string('cover')->default('')->comment('封面图片');
            $table->string('province')->comment('省份');
            $table->string('city')->comment('城市');
            $table->string('district')->comment('区域');
            $table->string('address')->comment('详细地址');
            $table->text('description')->comment('描述');
            $table->integer('age')->default(0)->comment('年龄');
            $table->integer('price')->default(0)->comment('平均价格');
            $table->integer('score_1')->default(-1)->comment('项目1得分');
            $table->integer('score_2')->default(-1)->comment('项目2得分');
            $table->integer('score_3')->default(-1)->comment('项目3得分');
            $table->integer('score_4')->default(-1)->comment('项目4得分');
            $table->integer('score_5')->default(-1)->comment('项目5得分');
            $table->integer('score_6')->default(-1)->comment('项目6得分');
            
            $table->index('name', 'idx_name');
            $table->index('creator', 'idx_creator');
            $table->index('province', 'idx_province');
            $table->index('city', 'idx_city');
            $table->index('district', 'idx_district');
            $table->index('age', 'idx_age');
            $table->index('price', 'idx_price');
            $table->index('score_1', 'idx_score_1');
            $table->index('score_2', 'idx_score_2');
            $table->index('score_3', 'idx_score_3');
            $table->index('score_4', 'idx_score_4');
            $table->index('score_5', 'idx_score_5');
            $table->index('score_6', 'idx_score_6');
            
            $table->dateTime('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            
            $table->index('created_at', 'idx_created_at');
            $table->index('updated_at', 'idx_updated_at');
        });
        \DB::statement("ALTER TABLE `{$this->table}` comment '会所+技师表'");
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
