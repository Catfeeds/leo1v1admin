<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TSellerLevelGoal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_weiyi_admin.t_seller_level_goal', function (Blueprint $table){
            t_field($table->integer("seller_level"),"等级");
            t_field($table->integer("level_goal"),"等级目标");
            t_field($table->string("level_face"),"等级头像");
            t_field($table->integer("create_time"),"创建时间");
            $table->primary("seller_level");
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
