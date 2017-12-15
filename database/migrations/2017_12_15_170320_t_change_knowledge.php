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
        
 
        Schema::table('db_question_new.t_knowledge_point', function (Blueprint $table){
            //$table->dropColumn('grade');
            // $table->increments('knowledge_id','知识点id');
            //     t_field($table->string('title'),"知识点标题");
            //     t_field($table->integer('subject'),"知识点科目 1:数学 2:语文 3:英语 4:物理 5:化学 6:生物 7:历史 8:政治 9:地理");
            //     t_field($table->string('detail',4096),"知识点详情解读");
            // t_field($table->integer('level')->default(0),"知识点层级");
            // t_field($table->integer('father_id')->default(0),"父级id");
            // t_field($table->string('father_other')->default(null),"可能对应的其他父级id");
        });

        Schema::table('db_question_new.t_answer', function (Blueprint $table){
            // $table->dropColumn('knowledge_id');
            // t_field($table->integer('answer_type')->default(1),"1:答案,2:解题步骤,3:解析过程");
        });

        Schema::table('db_question_new.t_question_knowledge', function (Blueprint $table){
            //$table->dropColumn('difficult');
                // t_field($table->integer('answer_id')->default(null),"答案id");
                // t_field($table->integer('type')->default(1),"1:题目对应的知识点,2:答案对应的知识点");
        });

        Schema::table('db_question_new.t_knowledge_level', function (Blueprint $table){
            // $table->dropColumn('level');
            // t_field($table->integer('answer_type')->default(1),"1:答案,2:解题步骤,3:解析过程");
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