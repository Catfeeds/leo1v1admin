<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TResourceFileAdd extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_resource_file', function( Blueprint $table)
        {
            t_field($table->string("uuid"),"h5资源 id");
            t_field($table->integer("uuid_status"),"h5资源 转化状态 0:失败 1:成功");
            t_field($table->string("zip_url"),"h5页面压缩包链接(七牛) ");
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
