<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTUserGroupChangeLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_weiyi_admin.t_user_group_change_log', function( Blueprint $table)
        {
            $table->increments("id","分组id");
            t_field($table->integer("add_time"),"添加时间");
            t_field($table->integer("userid"),"被添加组员id");
            t_field($table->string("group_name"),"组名");
            t_field($table->integer("master_adminid"),"总监id");
            t_field($table->integer("main_assign_percent"),"分配比率");

            // $table->index(["groupid","month"],'main_type_gid');
            // $table->index("main_type");
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
