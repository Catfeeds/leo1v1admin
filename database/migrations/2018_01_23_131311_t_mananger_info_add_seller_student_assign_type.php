<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TManangerInfoAddSellerStudentAssignType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table("db_weiyi_admin.t_manager_info", function( Blueprint $table)
        {
            t_field($table->integer("seller_student_assign_type") ,"分配规则,0:抢单,1:系统分配");
        });


        Schema::create("db_weiyi_admin.t_seller_student_system_assign_count_log", function( Blueprint $table)
        {
            t_comment($table, "系统分配例子日志" );
            t_field($table->integer("logtime",true) ,"运行时间");
            t_field($table->integer("new_count") ,"可分配的新例子数");
            t_field($table->integer("new_count_assigned") ,"此次分配的新例子数");
            t_field($table->integer("no_connected_count") ,"可分配的未拨通例子");
            t_field($table->integer("no_connected_count_assigned") ,"此次分配的未拨通例子");

        });

        //
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
