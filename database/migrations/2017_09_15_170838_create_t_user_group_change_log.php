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
            t_field($table->string("do_adminid"),"操作人");

            $table->index("add_time");
            $table->index("userid");
            $table->index("do_adminid");
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
