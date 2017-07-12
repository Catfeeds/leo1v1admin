<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTeacherInfoCheckAdminEx extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t_teacher_info', function( Blueprint $table)
        {
            $table->dropColumn("check_adminid");
        });

        Schema::table('t_teacher_info', function( Blueprint $table)
        {
            \App\Helper\Utils::comment_field(
                $table->integer("check_adminid"), 
                "课时检查 adminid") ;
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
