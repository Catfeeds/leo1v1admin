<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TSellerGetNewLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_weiyi_admin.t_seller_get_new_log', function (Blueprint $table){
            t_field($table->integer("id",true),"id");
            t_field($table->integer("adminid"),"抢单人");
            t_field($table->integer("userid"),"userid");
            t_field($table->integer("called_count"),"抢单人拨通次数");
            t_field($table->integer("no_called_count"),"抢单人未拨通次数");
            t_field($table->integer("create_time"),"抢单时间");
            $table->index("create_time");
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
