<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TCourseOrderAddTime extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('t_course_order', function( Blueprint $table)
        {
            \App\Helper\Utils::comment_field(
                $table->integer("add_time"),
                "添加时间");
            
            $table->index("add_time");
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
