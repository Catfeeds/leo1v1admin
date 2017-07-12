<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTeacherInfoTeacherMoneyType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('t_teacher_info', function( Blueprint $table)
        {
            \App\Helper\Utils::comment_field(
                $table->integer("teacher_money_type"),
                "老师工资分类");
        });

        //
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
