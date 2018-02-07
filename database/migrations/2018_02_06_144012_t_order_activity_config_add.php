<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TOrderActivityConfigAdd extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_order_activity_config', function( Blueprint $table)
        {
            t_field($table->integer("success_test_lesson_start"),"试听成功开始日期");
            t_field($table->integer("success_test_lesson_end"),"试听成功结束日期");           
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
