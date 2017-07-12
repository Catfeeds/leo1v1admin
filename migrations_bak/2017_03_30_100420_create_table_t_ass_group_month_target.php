<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTAssGroupMonthTarget extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_weiyi_admin.t_ass_group_target', function (Blueprint $table){
            t_field($table->integer("month"),"月开始时间");
            t_field($table->string("rate_target"),"系数");
            $table->primary("month");
        });

        Schema::table('db_weiyi_admin.t_assistant_month_target', function ($table) {
            $table->dropColumn("lesson_target");
        });

        Schema::table('db_weiyi_admin.t_assistant_month_target', function( Blueprint $table)
        {
            t_field($table->string("lesson_target"),"月度系数");
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
