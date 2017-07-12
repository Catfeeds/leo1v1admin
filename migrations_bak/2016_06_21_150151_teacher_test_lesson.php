<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TeacherTestLesson extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('t_teacher_info', function( Blueprint $table)
        {
            \App\Helper\Utils::comment_field(
                $table->string("wx_openid")->nullable()
                ,"微信openid");
            $table->unique("wx_openid");
        });

	   Schema::create('t_teacher_closest_grade', function (Blueprint $table)
       {
           $table->integer("teacherid");
           $table->integer("grade");
           $table->primary(["teacherid", "grade"]);
        });
	   Schema::create('t_teacher_closest_subject', function (Blueprint $table)
       {
           $table->integer("teacherid");
           $table->integer("subject");
           $table->primary(["teacherid", "subject"]);
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
