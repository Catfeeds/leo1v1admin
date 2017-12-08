<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TQuestionChange extends Migration
{
    /**
     * Run the migrations.
     *OrderActivityConfigChangeValue
     * @return void
     */
    public function up()
    {
        

        Schema::table('db_question_new.t_question', function (Blueprint $table){
            t_field($table->integer('score')->default(null),"分数");
            t_field($table->integer('open_flag')->default(1),"1:开,0:关");
        });


        Schema::table('db_question_new.t_answer', function (Blueprint $table){
            t_field($table->integer('score')->default(null),"每个步骤的分数");
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