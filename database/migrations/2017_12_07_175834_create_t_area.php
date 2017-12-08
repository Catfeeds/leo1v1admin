<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTArea extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_weiyi.t_area', function (Blueprint $table){
            $table->increments('area_id');
                t_field($table->string("name",50),"名称");
                t_field($table->integer("parent_id"),"");
                t_field($table->string("short_name",50),"");
                t_field($table->integer("level"),"等级");
                t_field($table->string("city_code",10),"区电话");
                t_field($table->string("zip_code",10),"邮政");
                t_field($table->string("merger_name"),"全称");
                t_field($table->string("long",20),"经度");
                t_field($table->string("lat",20),"纬度");
                t_field($table->string("pinyin",50),"拼音");

                $table->index("parent_id");
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
