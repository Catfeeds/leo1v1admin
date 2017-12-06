<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TQuestion extends Migration
{
    /**
     * Run the migrations.
     *OrderActivityConfigChangeValue
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('db_question_new.t_question');

        Schema::create('db_question_new.t_question', function (Blueprint $table){
            $table->increments('question_id','题目id');
            t_field($table->string('title'),"题目标题");
            t_field($table->integer('subject'),"科目 1:数学 2:语文 3:英语 4:物理 5:化学 6:生物 7:历史 8:政治 9:地理");
            t_field($table->string('detail',4096),"题目详情");

        });

        Schema::dropIfExists('db_question_new.t_knowledge_point');

        Schema::create('db_question_new.t_knowledge_point', function (Blueprint $table){
            $table->increments('knowledge_id','知识点id');
                t_field($table->string('title'),"知识点标题");
                t_field($table->integer('subject'),"知识点科目 1:数学 2:语文 3:英语 4:物理 5:化学 6:生物 7:历史 8:政治 9:地理");
                t_field($table->string('detail',4096),"知识点详情解读");

        });

        Schema::dropIfExists('db_question_new.t_answer');

        Schema::create('db_question_new.t_answer', function (Blueprint $table){
            $table->increments('answer_id','答案id');
                t_field($table->integer('question_id'),"对应的题目id");
                t_field($table->integer('knowledge_id'),"对应的知识点id");
                t_field($table->integer('difficult'),"难易程度 1简单 2中等 3难");
                t_field($table->integer('step'),"答案步骤");
                t_field($table->string('detail',4096),"答案详情");
        });

        Schema::dropIfExists('db_question_new.t_question_knowledge');

        Schema::create('db_question_new.t_question_knowledge', function (Blueprint $table){
            $table->increments('id','题目对应的知识点id');
                t_field($table->integer('question_id'),"对应的题目id");
                t_field($table->integer('knowledge_id'),"对应的知识点id");
                t_field($table->integer('difficult'),"难易程度 1简单 2中等 3难");
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
