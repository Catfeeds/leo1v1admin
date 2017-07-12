<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TLessonInfoAddLessonEndTodoFlag extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('t_lesson_info',function( Blueprint $table)
        {
            \App\Helper\Utils::comment_field(
                $table->integer("lesson_end_todo_flag")->default(0),"0 课堂结束未发送讲义等, 1 已处理");
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
