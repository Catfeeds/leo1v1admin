<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TPhoneInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_weiyi.t_phone_info', function( Blueprint $table)
        {
            t_comment($table, "手机归属地详情表" );
            t_field($table->integer("id",true) ,"手机号前7位");
            t_field($table->string("province",32),"省份");
            t_field($table->string("city",64),"城市");
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
