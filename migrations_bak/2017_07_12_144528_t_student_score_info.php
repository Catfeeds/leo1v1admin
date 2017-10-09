<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TStudentScoreInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
            Schema::create('db_weiyi.t_student_score_info', function (Blueprint $table){
                t_field($table->integer("id",true),"id");
                t_field($table->integer("userid"),"");
                t_field($table->integer("create_time"),"创建时间");
                t_field($table->integer("create_adminid"),"添加人");
                t_field($table->integer("subject"),"科目");
                t_field($table->integer("stu_score_type"),"测验分类");
                t_field($table->integer("stu_score_time"),"测验时间");
                t_field($table->integer("score"),"分数");
                t_field($table->string("rank"),"排名");
                t_field($table->string("file_url"),"");

                $table->index("userid");
                $table->index("create_adminid");

            });
        //
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
