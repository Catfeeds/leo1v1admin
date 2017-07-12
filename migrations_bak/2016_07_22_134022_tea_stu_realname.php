<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TeaStuRealname extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::table('t_student_info', function( Blueprint $table)
        {
            \App\Helper\Utils::comment_field(
                $table->string("realname",32),
                "真实姓名");
            $table->index("nick");
            $table->index("realname");
        });

        Schema::table('t_teacher_info', function( Blueprint $table)
        {
            \App\Helper\Utils::comment_field(
                $table->string("realname",32),
                "真实姓名");
            $table->index("nick");
            $table->index("realname");
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
        //
    }
}
