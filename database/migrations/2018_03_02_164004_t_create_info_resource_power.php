<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TCreateInfoResourcePower extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
       
        Schema::create('db_weiyi.t_info_resource_power', function(Blueprint $table) {
            $table->increments('id','id');
            t_field($table->integer("resource_id"), "资源类型");
            t_field($table->integer("type_id"), "细分类型");
            t_field($table->string("resource_name"), "资源名字");
            t_field($table->string("type_name"), "细分类型名字");
            t_field($table->integer("consult"), "咨询部");
            t_field($table->integer("assistant"), "助教部");
            t_field($table->integer("market"), "市场部");
            t_field($table->integer("is_del"), "细分类型");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
