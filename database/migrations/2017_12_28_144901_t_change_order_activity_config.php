<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class TChangeOrderActivityConfig extends Migration
{
    /**
     * Run the migrations.
     *OrderActivityConfigChangeValue
     * @return void
     */
    public function up()
    {
        Schema::table('db_weiyi.t_order_activity_config', function (Blueprint $table){
            t_field($table->integer('is_need_share_wechat')->default(0),"是否需要分享微信");
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