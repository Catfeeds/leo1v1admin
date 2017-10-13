<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTeacherSwitchMoneyTypeListAddLessonTotal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_teacher_switch_money_type_list', function( Blueprint $table)
        {
            $table->dropColumn("per_money_different");
            t_field($table->string("base_money_different"),"基础工资差别");
            t_field($table->integer("lesson_total"),"总课时");
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
