<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTActivityChristmas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_weiyi.t_activity_christmas', function(Blueprint $table) {
            t_field($table->increments("id"), "市场部圣诞节活动");
            t_field($table->integer("christmas_price_type"), "圣诞节中奖类型");
            t_field($table->integer("parentid"), "抽奖人id");
            t_field($table->integer("win_time"), "中奖时间");
            t_field($table->integer("use_time"), "使用时间");
            t_field($table->tinyInteger("is_use_flag"), "是否使用 0:未使用 1:已使用");

            $table->index('parentid');
            $table->index('win_time');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
