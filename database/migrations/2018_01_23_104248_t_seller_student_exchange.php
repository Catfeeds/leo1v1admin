<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TSellerStudentExchange extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        /*
        Schema::create("db_weiyi_admin.t_seller_student_exchange", function( Blueprint $table)
        {
            //表注释
            t_comment($table, "例子流转表" );
            //字段以及注释
            t_field($table->integer("id",true) );
            t_field($table->integer("logtime"), "操作" );
            t_field($table->integer("userid") );
            t_field($table->integer("seller_student_exchange_type"), "流转类型" );
            t_field($table->integer("adminid") ,"流转给谁");

            $table->index(["adminid", "logtime"],"adminid");
            $table->index(["userid"],"userid");
        });
        */


        Schema::create("db_weiyi_admin.t_seller_student_system_assign_log", function( Blueprint $table)
        {
            //表注释
            t_comment($table, "例子系统分配表" );
            //字段以及注释
            t_field($table->integer("id",true), "" );
            t_field($table->integer("logtime"), "操作" );
            t_field($table->integer("userid"), "" );
            t_field($table->integer("seller_student_assign_from_type"),"分配源:0:新例子, 1:未拨通" );
            t_field($table->integer("adminid") ,"流转给谁");
            t_field($table->integer("call_count") ,"拨打次数");
            t_field($table->integer("called_flag") ,"拨通情况");
            t_field($table->integer("call_time") ,"拨打时长:拨通情况下是拨通时长");

            $table->index(["adminid", "logtime"],"adminid");
            $table->index(["userid"],"userid");

        });


        Schema::table("db_weiyi.t_seller_student_new", function( Blueprint $table)
        {
            t_field($table->integer("seller_student_assign_type") ,"分配规则,0:抢单,1:系统分配");
        });
        /*
        const C_detail_id='detail_id';
        const C_new_count_id='new_count_id';
        const C_get_time='get_time';
        const C_get_desc='get_desc';
        const C_userid='userid';
        */


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
