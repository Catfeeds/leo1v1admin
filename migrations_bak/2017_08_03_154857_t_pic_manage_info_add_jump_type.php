<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TPicManageInfoAddJumpType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_pic_manage_info', function( Blueprint $table)
        {
            t_field($table->tinyInteger("jump_type"),"图片跳转类型  0  网页 1  视频");
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
