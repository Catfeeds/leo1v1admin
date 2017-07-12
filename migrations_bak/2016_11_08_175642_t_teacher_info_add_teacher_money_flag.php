<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

function add_field($filed_item,$comment) {
    \App\Helper\Utils::comment_field($filed_item ,$comment);
}
class TTeacherInfoAddTeacherMoneyFlag extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //0 800以下　1多卡发放　2扣２％劳务费
        Schema::table('t_teacher_info', function( Blueprint $table)
        {
            add_field($table->tinyInteger("teacher_money_flag"),"老师工资发放类型");
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
