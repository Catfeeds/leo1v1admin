<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTestLessonLogList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	   Schema::table('t_seller_student_info', function (Blueprint $table)
       {
           \App\Helper\Utils::comment_field(
               $table->integer("test_lesson_bind_adminid"),
               "试听排课分配人");
       });

	   Schema::create('t_test_lesson_log_list', function (Blueprint $table)
       {
           $table->integer("id",true);
           $table->integer("log_time");
           $table->string("phone",20);
           $table->string("phone_location",20);
           \App\Helper\Utils::comment_field(
               $table->integer("test_lesson_bind_adminid"),
               "试听排课分配人");
           $table->integer("userid");
           $table->string("nick",64);
           $table->string("origin");
           $table->integer("st_application_time");
           $table->integer("st_class_time");
           $table->integer("lessonid");
           $table->integer("teacherid");
           $table->integer("lesson_start");
           $table->integer("lesson_end");
           $table->integer("grade");
           $table->integer("subject");
           $table->string("st_application_id");
           $table->string("user_desc");
           $table->integer("test_lesson_status");
           $table->string("reason");
            
           \App\Helper\Utils::comment_field(
               $table->integer("st_demand"),
               "试听需求");
           //$table->getColumns()
           $table->index("log_time");
           $table->index("userid");
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
	   Schema::table('t_seller_student_info', function (Blueprint $table)
       {
           $table->dropColumn("test_lesson_bind_adminid");
       });


        Schema::drop('t_test_lesson_log_list');
        

        //
    }
}
