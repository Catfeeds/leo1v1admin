<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTTeacherResourceDir extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('db_weiyi.t_teacher_resource_dir', function (Blueprint $table){
            $table->increments('dir_id');
                t_field($table->string("name"),"目录名称");
                t_field($table->integer("teacherid"),"老师id");
                t_field($table->integer("create_time"),"时间");
                t_field($table->integer("pid"),"父id");
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
