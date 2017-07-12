<?php


use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlertTLessonInfoPriceInt extends Migration
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
            $table->dropColumn("price");
            $table->integer("tea_price");
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
            $table->dropColumn("tea_price");
        });

    }
}
