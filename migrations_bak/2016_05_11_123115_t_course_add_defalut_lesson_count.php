<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TCourseAddDefalutLessonCount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t_order_info', function (Blueprint $table)
        {
            \App\Helper\Utils::comment_field(
                $table->integer("default_lesson_count"  ),
                "每次课几课时" );
        });

        Schema::table('t_course_order', function (Blueprint $table)
        {
            \App\Helper\Utils::comment_field(
                $table->integer("default_lesson_count"  ),
                "每次课几课时" );
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
        Schema::table('t_order_info', function (Blueprint $table)
        {
            $table->dropColumn("default_lesson_count");
        });

        Schema::table('t_course_order', function (Blueprint $table)
        {
            $table->dropColumn("default_lesson_count");
        });
        //
    }
}
