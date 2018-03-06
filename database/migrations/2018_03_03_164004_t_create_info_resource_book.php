<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TCreateInfoResourceBook extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
       
        Schema::create('db_weiyi.t_info_resource_book', function(Blueprint $table) {
            $table->increments('id','id');
            t_field($table->integer("subject"), "科目");
            t_field($table->integer("grade"), "年级");
            t_field($table->integer("province"), "省份id");
            t_field($table->string("province_name"), "省份名字");
            t_field($table->integer("city"), "城市id");
            t_field($table->string("city_name"), "城市名字");
            t_field($table->integer("book"), "教材id");
            t_field($table->string("book_name"), "教材名字");
            t_field($table->integer("is_del"), "是否删除");
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
