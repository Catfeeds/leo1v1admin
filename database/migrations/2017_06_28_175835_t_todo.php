<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTodo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

        Schema::drop('db_weiyi_admin.t_todo');
        //
        Schema::create('db_weiyi_admin.t_todo', function( Blueprint $table)
        {
            t_field($table->integer("todoid",true),"todoid");
            t_field($table->integer("todo_type"),"todo 类型");
            t_field($table->integer("create_time"),"创建时间");
            t_field($table->integer("start_time"),"开始时间");
            t_field($table->integer("end_time"),"结束时间");
            t_field($table->integer("adminid"),"负责人");
            t_field($table->string("msg",4096),"信息");
            t_field($table->integer("from_key_int"),"外部键");
            t_field($table->integer("from_key2_int"),"外部键2");

            t_field($table->integer("todo_status"),"todo 状态");
            t_field($table->integer("todo_status_time"),"todo 状态 设置时间");

            $table->index(["adminid","create_time"]);
            $table->index(["adminid","start_time"]);
            $table->index(["create_time"]);
            $table->index(["start_time"]);
            $table->unique(["todo_type","from_key_int","from_key2_int"],"from_key");
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
