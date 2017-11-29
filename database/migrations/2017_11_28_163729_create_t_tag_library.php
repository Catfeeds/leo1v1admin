<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTTagLibrary extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_tag_library', function (Blueprint $table) {
            t_field($table->increments('tag_id'),"标签id");
            t_field($table->string('tag_name'),"标签名称");
            t_field($table->integer('tag_l1_sort'),"标签一级分类");
            t_field($table->integer('tag_l2_sort'),"标签二级分类");
            t_field($table->integer('tag_weight'),"标签权重值");
            t_field($table->integer('tag_object'),"标签对象");
            t_field($table->string('tag_desc'),"标签定义");
            t_field($table->integer('create_time'),"创建时间");
            t_field($table->integer('manager_id'),"创建人id");
            $table->index('tag_l1_sort');
            $table->index('tag_l2_sort');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('t_tag_library');
    }
}
