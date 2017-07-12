<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTTeacherLectureInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::drop("t_teacher_lecture_info");
        Schema::create('t_teacher_lecture_info', function (Blueprint $table)
        {
            $table->integer("id",true);
            $table->string("nick",50);
            $table->string("face",100);
            $table->string("phone",16);
            $table->integer("grade");
            $table->integer("subject");
            $table->string("title",500);
            $table->string("audio",100);
            $table->string("draw",100);
            $table->integer("real_begin_time");
            $table->integer("real_end_time");
            $table->string("identity_image",100);
            $table->tinyInteger("role");

            $table->index("phone","phone");
            $table->index(["grade","subject"],"grade,subject");
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
