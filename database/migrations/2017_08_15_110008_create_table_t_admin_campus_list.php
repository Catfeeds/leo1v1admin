<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTAdminCampusList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_weiyi_admin.t_admin_campus_list', function (Blueprint $table){
            t_field($table->integer("campus_id",true),"校区id");
            t_field($table->integer("campus_name"),"校区名称");
        });

        Schema::table('db_weiyi.t_teacher_info', function( Blueprint $table)
        {
            t_field($table->integer("leave_remove_adminid"),"休课解除设置人");
            t_field($table->integer("leave_remove_time"),"休课解除时间");
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
