<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class OrderActivityConfigChangeValue extends Migration
{
    /**
     * Run the migrations.
     *OrderActivityConfigChangeValue
     * @return void
     */
    public function up()
    {
        Schema::table('db_weiyi_admin.t_web_page_trace_log', function (Blueprint $table){
            t_field($table->integer('share_wx_flag')->default(0),"是否分享朋友圈");
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