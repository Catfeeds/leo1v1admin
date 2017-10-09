<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTYxyxCustomType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_weiyi.t_yxyx_custom_type', function( Blueprint $table)
        {
            $table->increments("custom_type_id");

            t_field($table->string("type_name", 128),"标签名称");
            t_field($table->integer("adminid"),"管理员id");
            t_field($table->integer("create_time"),"添加时间");

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
