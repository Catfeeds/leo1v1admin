<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTOrderStudentMonthList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::dropIfExists('db_weiyi_admin.t_order_student_month_list');
        Schema::create('db_weiyi_admin.t_order_student_month_list', function (Blueprint $table) {
            t_field($table->integer('month'),"月份");
            t_field($table->string('origin',64),"渠道");
            t_field($table->integer('leads_num'),"leads数");
            t_field($table->integer('test_num'),"试听数");
          
            t_field($table->string('test_transfor_per',16),"试听转化率");
            t_field($table->string('order_transfor_per',16),"签单转化率");

            t_field($table->integer('order_stu_num'),"下单学生数");
           
            $table->primary(["month","origin"]);
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
