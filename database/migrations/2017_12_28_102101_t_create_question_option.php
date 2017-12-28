<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class TCreateQuestionOption extends Migration
{
    /**
     * Run the migrations.
     *OrderActivityConfigChangeValue
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('db_weiyi.t_question_option');
        Schema::create('db_question_new.t_question_option', function (Blueprint $table){
            $table->increments('id');
                t_field($table->integer('question_id'),"题目id");
                t_field($table->string('option_name'),"选项");
                t_field($table->string('option_text'),"选项内容");
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