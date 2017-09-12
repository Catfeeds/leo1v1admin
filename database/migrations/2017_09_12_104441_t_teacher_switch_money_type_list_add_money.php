<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTeacherSwitchMoneyTypeListAddMoney extends Migration
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
            t_field($table->string("per_money_different"),"平均每课时工资差别");
            t_field($table->string("all_money_different"),"总工资差别");
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
        Schema::table('db_weiyi.t_teacher_switch_money_type_list', function( Blueprint $table)
        {
            $table->dropColumn();
            t_field($table->string("per_money_different"),"平均每课时工资差别");
            t_field($table->string("all_money_different"),"总工资差别");
        });
    }
}
