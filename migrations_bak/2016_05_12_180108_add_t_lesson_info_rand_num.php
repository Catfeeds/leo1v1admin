<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTLessonInfoRandNum extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t_lesson_info', function( Blueprint $table)
        {
            $table->integer("rand_num");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('t_lesson_info', function( Blueprint $table)
        {
            $table->dropColumn("rand_num");
        });
    }
}