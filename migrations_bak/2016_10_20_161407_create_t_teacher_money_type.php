<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTTeacherMoneyType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_teacher_money_type',function(Blueprint $table)
        {
            $table->integer('teacher_money_type');
            $table->integer('level');
            $table->integer('grade');
            $table->integer('money');
            $table->integer('type')->default(1);

            $table->primary(["teacher_money_type","level","grade"]);
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
