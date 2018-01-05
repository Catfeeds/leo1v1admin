<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVotes2ToTTeacherApproveReferToData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t_teacher_approve_refer_to_data', function (Blueprint $table) {
            //
            t_field($table->integer("all_test_lesson_count") ,"所有试听课次数");
            t_field($table->integer("all_regular_lesson_count") ,"所有常规课次数");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('t_teacher_approve_refer_to_data', function (Blueprint $table) {
            //
        });
    }
}
