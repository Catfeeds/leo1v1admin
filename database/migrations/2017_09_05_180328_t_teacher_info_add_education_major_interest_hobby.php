<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTeacherInfoAddEducationMajorInterestHobby extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_teacher_info', function( Blueprint $table)
        {
            t_field($table->string("education", 128),"学历");
            t_field($table->string("major", 128),"专业");
            t_field($table->string("interest"),"兴趣");
            t_field($table->string("hobby"),"爱好");
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
