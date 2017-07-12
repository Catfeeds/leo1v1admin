<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTFieldChangeList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('t_field_modified_list', function (Blueprint $table){
            t_field($table->integer("id",true),"id");
            t_field($table->string("db"),"表");
            t_field($table->integer("modified_time"),"修改时间");
            t_field($table->string("field"),"字段");
            t_field($table->string("last_value"),"修改前的值");
            t_field($table->string("cur_value"),"修改后的值");
            t_field($table->integer("adminid"),"操作人");
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
