<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTMarketDepartmentActivity extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //


        Schema::dropIfExists('db_weiyi.t_market_department_activity');

        Schema::create('db_weiyi.t_market_department_activity', function (Blueprint $table){
            $table->increments('id'); 
            t_field($table->string("openid"),"用户openid");
            t_field($table->integer("share_time"),"分享时间");
            t_field($table->integer("type"),"活动类型");
            t_field($table->integer("create_time"),"创建时间");

            $table->index("openid");
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
