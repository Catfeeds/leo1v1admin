<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TVersionControl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
         Schema::create('t_version_control', function (Blueprint $table) {
            t_field($table->increments('id',true),"id");
            t_field($table->string('file_path'),"文件路径");
            t_field($table->string('file_url'),"文件url");
            t_field($table->integer('file_type'),"文件分类");
            t_field($table->integer('is_publish'),"是否发布");
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
