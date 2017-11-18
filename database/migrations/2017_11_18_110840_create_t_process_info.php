<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTProcessInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_process_info', function (Blueprint $table) {
            t_field($table->increments('process_id'),"流程id");
            t_field($table->string('name'),"流程");
            t_field($table->string('fit_range'),"适用范围");
            t_field($table->string('department'),"职能部门(数字用逗号拼接)");
            t_field($table->string('pro_explain','2000'),"流程说明");
            t_field($table->string('attention'),"注意事项");
            t_field($table->string('pro_img'),"流程图地址");
            t_field($table->integer('create_time'),"创建时间");
            t_field($table->integer('adminid'),"创建者");
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
