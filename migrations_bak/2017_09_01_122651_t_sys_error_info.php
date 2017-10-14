<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TSysErrorInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('db_weiyi_admin.t_sys_error_info', function( Blueprint $table)
        {
            t_field($table->integer("id",true),"");
            t_field($table->integer("add_time"),"时间");
            t_field($table->integer("report_error_from_type"),"错误来自哪里");
            t_field($table->integer("report_error_type"),"错误分类");
            t_field($table->string("error_msg",4096) ,"信息");
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
