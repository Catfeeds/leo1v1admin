<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTeacherFreetimeForWeekAddCommonLessonConfig extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        //t_teacher_freetime_for_week
        Schema::table('t_teacher_freetime_for_week', function( Blueprint $table)
        {
            
            \App\Helper\Utils::comment_field(
                $table->string("common_lesson_config",8192), //json
                "常规课表 :json:[{ start:1-10:10,length:90,userid:60001  }..]"
                );

        });	

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('t_teacher_freetime_for_week', function( Blueprint $table)
        {
            
            $field=$table->dropColumn("common_lesson_config"); //json

        });	

        //
    }
}
