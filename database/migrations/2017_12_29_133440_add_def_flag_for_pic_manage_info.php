<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDefFlagForPicManageInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("db_weiyi.t_pic_manage_info", function(Blueprint $table) {
            t_field($table->tinyInteger("del_flag"), "删除标识 0正常 1删除");
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
