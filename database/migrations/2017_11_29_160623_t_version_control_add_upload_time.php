<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TVersionControlAddUploadTime extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::dropIfExists('t_version_control');
        Schema::create('db_weiyi.t_version_control', function (Blueprint $table){
            t_field($table->increments('id',true),"id");
            t_field($table->string('file_path'),"文件路径");
            t_field($table->string('file_url'),"文件url");
            t_field($table->integer('file_type'),"文件分类");
            t_field($table->integer('is_publish'),"是否发布");
            t_field($table->integer('publish_time'),"发布time");
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
