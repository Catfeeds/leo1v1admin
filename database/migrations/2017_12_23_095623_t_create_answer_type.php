<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class TCreateAnswerType extends Migration
{
    /**
     * Run the migrations.
     *OrderActivityConfigChangeValue
     * @return void
     */
    public function up()
    {
        // Schema::dropIfExists('db_question_new.t_answer_type');
        // Schema::create('db_question_new.t_answer_type', function (Blueprint $table){
        //     $table->increments('id');
        //         t_field($table->integer('answer_type_no'),"答案序号");
        //         t_field($table->string('name'),"步骤名字");
        //         t_field($table->integer('subject'),"科目id");
        //         t_field($table->integer('open_flag')->default(1),"开启与否");
        // });        
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