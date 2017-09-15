<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTMainMajorGroupNameMonth extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_weiyi_admin.t_main_major_group_name_month', function( Blueprint $table)
        {
            $table->increments("groupid","分组id");
            // t_field($table->integer("main_type"),"部门类型");
            // t_field($table->string("group_name"),"组名");
            // t_field($table->integer("master_adminid"),"总监id");
            // t_field($table->string("main_assign_percent"),"组名");
            // t_field($table->integer("campus_id"),"校区id");

            $table->index(["groupid","month"],'main_type_gid');
            $table->index("main_type");
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
