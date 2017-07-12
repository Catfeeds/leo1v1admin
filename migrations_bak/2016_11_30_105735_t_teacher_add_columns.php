<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
function add_field($filed_item,$comment) {
    \App\Helper\Utils::comment_field($filed_item ,$comment);
}

class TTeacherAddColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table("t_teacher_info",function(Blueprint $table){
            add_field($table->tinyInteger("identity")->default(0),"老师身份 0 未设置 1 在校学生 2 在职老师");
        });

        Schema::table("t_teacher_lecture_info",function(Blueprint $table){
            add_field($table->string("reason",5000),"确认情况的原因");
            add_field($table->string("resume_url",100),"简历地址");
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
