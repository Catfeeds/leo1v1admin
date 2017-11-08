<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTeacherSalaryListAlterUnique extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_teacher_salary_list', function( Blueprint $table)
        {
            // $table->dropUnique(["teacherid","pay_time"]);
            $table->unique(["teacherid","pay_time"],"unique_key");
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
