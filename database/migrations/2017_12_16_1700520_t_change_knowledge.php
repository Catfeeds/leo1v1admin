<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class TChangeKnowledge extends Migration
{
    /**
     * Run the migrations.
     *OrderActivityConfigChangeValue
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('db_question_new.t_answer');
        Schema::create('db_question_new.t_answer', function (Blueprint $table){
            $table->increments('answer_id');
                t_field($table->integer('question_id'),"答案id");
                t_field($table->integer('difficult')->default(0),"难度");
                t_field($table->integer('step'),"步骤");
                t_field($table->integer('score'),"分数");
                t_field($table->integer('answer_type')->default(1),"1:答案,2:解题步骤,3:解析过程");
                t_field($table->string('detail',4096)->nullable(),"具体答案");
        });

        Schema::dropIfExists('db_question_new.t_knowledge_level');
        Schema::create('db_question_new.t_knowledge_level', function (Blueprint $table){
            $table->increments('id');
                t_field($table->integer('knowledge_id'),"知识点id");
                t_field($table->integer('father_id'),"父级id");
        });

        Schema::dropIfExists('db_question_new.t_knowledge_point');
        Schema::create('db_question_new.t_knowledge_point', function (Blueprint $table){
            $table->increments('knowledge_id','知识点id');
                t_field($table->string('title'),"知识点标题");
                t_field($table->integer('subject'),"知识点科目");
                t_field($table->integer('open_flag')->default(1),"0:关闭 1:开启");
                t_field($table->string('detail',4096),"知识点详情解读");
        });

        Schema::dropIfExists('db_question_new.t_question');
        Schema::create('db_question_new.t_question', function (Blueprint $table){
            $table->increments('question_id');
                t_field($table->string('title'),"标题");
                t_field($table->integer('subject'),"科目");
                t_field($table->integer('score'),"分数");
                t_field($table->integer('open_flag')->default(1),"0:关闭 1:开启");
                t_field($table->integer('difficult')->default(1),"难度");
                t_field($table->integer('question_type'),"题型");
                t_field($table->string('detail',4096)->nullable(),"具体问题");
        });

        Schema::dropIfExists('db_question_new.t_question_knowledge');
        Schema::create('db_question_new.t_question_knowledge', function (Blueprint $table){
            $table->increments('id');
                t_field($table->integer('knowledge_id'),"知识点id");
                t_field($table->integer('question_id')->default(0),"答案id");
                t_field($table->integer('answer_id')->default(0),"答案id");
                t_field($table->integer('type')->default(1),"1:题目对应的知识点,2:答案对应的知识点");
                t_field($table->integer('difficult')->default(0),"难度");
        });

        Schema::dropIfExists('db_question_new.t_textbook');
        Schema::create('db_question_new.t_textbook', function (Blueprint $table){
            $table->increments('textbook_id');
                t_field($table->string('name'),"教材名字");
        });

        Schema::dropIfExists('db_question_new.t_textbook_knowledge');
        Schema::create('db_question_new.t_textbook_knowledge', function (Blueprint $table){
            $table->increments('id');
                t_field($table->integer('textbook_id'),"知识点id");
                t_field($table->integer('subject'),"科目");
                t_field($table->integer('grade'),"年级");
                t_field($table->integer('knowledge_id'),"知识点id");
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