<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class TCreateKnowledgeLevel extends Migration
{
    /**
     * Run the migrations.
     *OrderActivityConfigChangeValue
     * @return void
     */
    public function up()
    {
        
        // Schema::create('db_question_new.t_knowledge_level', function (Blueprint $table){
        //     $table->increments('id');
        //         t_field($table->integer('knowledge_id'),"1:开启 0:关闭");
        //         t_field($table->string('father_id'),"父级id");
        // });
        
        // Schema::table('db_question_new.t_knowledge_point', function (Blueprint $table){
        //     $table->dropColumn('father_other');
        //         $table->dropColumn('father_id');
        //         $table->dropColumn('level');
        //         t_field($table->integer('open_flag')->default(1),"1:开启 0:关闭");
        // });

        // Schema::table('db_question_new.t_knowledge_level', function (Blueprint $table){
        //     $table->dropColumn('father_id');
        //         t_field($table->integer('level')->default(0),"0:根部节点");
        //     t_field($table->integer('father_id'),"父级id");
        // });
        Schema::dropIfExists('db_question_new.t_question_type');
        Schema::create('db_question_new.t_question_type', function (Blueprint $table){
            $table->increments('id');
                t_field($table->integer('subject'),"科目");
                t_field($table->string('name'),"题型名字");
                t_field($table->integer('open_flag')->default(1),"是否开启");
        });

        // Schema::table('db_question_new.t_textbook', function (Blueprint $table){
        //     t_field($table->integer('open_flag')->default(1),"是否开启");
        // });

        // Schema::table('db_question_new.t_question', function (Blueprint $table){
        //     t_field($table->string('question_resource_name')->default(null),"题目来源名字");
        //     t_field($table->integer('question_resource_type')->default(0),"题目来源类型");
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