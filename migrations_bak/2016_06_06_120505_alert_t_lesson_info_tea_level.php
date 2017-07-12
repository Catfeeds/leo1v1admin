<?php


use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlertTLessonInfoTeaLevel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

        Schema::table('t_lesson_info', function( Blueprint $table)
        {
            $table->integer("level");
            $table->string("price");
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
	 Schema::table('t_lesson_info', function( Blueprint $table)
        {
            $table->dropColumn("level");
            $table->dropColumn("price");
        });

    }
}
