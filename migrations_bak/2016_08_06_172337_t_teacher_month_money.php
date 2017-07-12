<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTeacherMonthMoney extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        //
        Schema::create('db_weiyi.t_teacher_month_money', function (Blueprint $table)
        {
            $table->integer('logtime');
            $table->integer('teacherid');
            $table->integer('all_count');
            $table->integer('l1v1_count');
            $table->integer('test_count');

            $table->integer('money_all_count');
            $table->integer('money_l1v1_count');
            $table->integer('money_test_count');

            $table->integer('confirm_flag');
            $table->integer('confirm_time');
            $table->integer('confirm_adminid');

            $table->integer('pay_flag');
            $table->integer('pay_time');
            $table->integer('pay_adminid');


            $table->primary(["logtime","teacherid"]);
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::drop('db_weiyi.t_teacher_month_money');
        //
    }
}
