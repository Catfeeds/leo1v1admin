<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TParentLuckDrawInWxAddTestFlag extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table("db_weiyi.t_parent_luck_draw_in_wx", function(Blueprint $table) {
            t_field($table->tinInteger("is_test_flag"), "测试账号标示 0:正常用户 1:测试用户");
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
