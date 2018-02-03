<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TActivityUsuallyAddUseFlag extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_activity_usually', function(Blueprint $table) {
            t_field($table->tinyInteger("use_flag"), "是否使用微信个人二维码 0:不使用 1:使用");
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
