<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TDealPptToH5Add extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_deal_ppt_to_h5', function(Blueprint $table) {
            t_field($table->string("uuid"), "uuid标识");
            t_field($table->tinyInteger("is_succ"), "0:未设置 1:成功");
            t_field($table->integer("deal_time"), "处理时间");
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
