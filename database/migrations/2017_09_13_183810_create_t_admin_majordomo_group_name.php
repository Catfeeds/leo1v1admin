<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTAdminMajordomoGroupName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_weiyi_admin.t_admin_majordomo_group_name', function( Blueprint $table)
        {
            $table->increments("groupid","分组id");
            t_field($table->integer("main_type"),"部门类型");
            t_field($table->string("group_name"),"组名");
            t_field($table->integer("master_adminid"),"总监id");
            t_field($table->string("main_assign_percent"),"组名");
            t_field($table->integer("campus_id"),"校区id");

            $table->index(["main_type","groupid"]);
        });

        Schema::table('db_weiyi_admin.t_admin_main_group_name', function( Blueprint $table)
        {
            t_field($table->integer("up_groupid"),"上级groupid");
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
