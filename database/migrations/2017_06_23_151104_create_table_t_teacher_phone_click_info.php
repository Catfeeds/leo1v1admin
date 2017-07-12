<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTTeacherPhoneClickInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_weiyi.t_teacher_phone_click_info', function (Blueprint $table){
            t_field($table->integer("adminid"),"adminid");
            t_field($table->integer("click_time"),"时间,以天计");
            t_field($table->integer("num"),"点击次数");
            $table->primary(["adminid","click_time"]);
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
