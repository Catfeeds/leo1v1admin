<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TMonthDefType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create("db_weiyi_admin.t_month_def_type", function(Blueprint $table) {
            t_field($table->increments('id'), 'id');
            t_field($table->integer('month_def_type'), '月定义类型');
            t_field($table->integer('def_time'), '定义时间');
            t_field($table->integer('start_time'), '开始时间');
            t_field($table->integer('end_time'), '结束时间');
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
