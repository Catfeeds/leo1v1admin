<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTeacherClosestAlterPrimaryKey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t_teacher_closest', function( Blueprint $table)
        {
            $table->dropPrimary();
            $table->primary(["teacherid","subject","grade"]);
        });

        Schema::table('t_test_lesson_assign_teacher', function( Blueprint $table)
        {
            \App\Helper\Utils::comment_field(
                $table->integer("assign_adminid"),
                "派单者");
        });

        Schema::table('t_wx_openid_bind', function( Blueprint $table)
        {
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
        //
    }
}
