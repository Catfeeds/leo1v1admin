<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TCreateSellerLevelMonth extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_weiyi_admin.t_seller_level_month', function (Blueprint $table){
            t_field($table->integer("id",true),"id");
            t_field($table->integer("adminid"),"定级cc");
            t_field($table->integer("month_date"),"定级月份");
            t_field($table->integer("seller_level"),"定级级别");
            t_field($table->integer("create_time"),"创建时间");

            $table->index("adminid");
            $table->index("month_date");
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
