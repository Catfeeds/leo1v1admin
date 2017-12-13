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
        
        //Schema::dropIfExists('db_question_new.t_knowledge_point');
        Schema::table('db_question_new.t_knowledge_point', function (Blueprint $table){
            t_field($table->integer('level')->default(0),"知识点层级");
            t_field($table->integer('father_id')->default(0),"父级id");
            t_field($table->string('father_other')->default(null),"可能对应的其他父级id");
            t_field($table->integer('grade'),"知识点对应的年级id");
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