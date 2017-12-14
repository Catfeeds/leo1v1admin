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

        Schema::table('db_question_new.t_knowledge_level', function (Blueprint $table){
            //$table->dropColumn('father_id');
                //t_field($table->integer('level')->default(0),"0:根部节点");
            //t_field($table->integer('father_id'),"父级id");
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