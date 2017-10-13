<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTTestRick extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_weiyi_admin.t_test_rick', function(Blueprint $table) {
            t_field($table->increments('id'), 'id');
            t_field($table->string('name'), '名字');
            t_field($table->integer('age'), '年龄');
            t_field($table->integer('grade'), '年级');
            t_field($table->integer('create_tiem'), '创建时间');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::drop('db_weiyi_admin.t_test_rick');
    }
}
