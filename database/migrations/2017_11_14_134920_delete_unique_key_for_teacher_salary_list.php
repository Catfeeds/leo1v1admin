<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteUniqueKeyForTeacherSalaryList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table("db_weiyi.t_teacher_salary_list", function(Blueprint $table){
            $table->dropUnique('unique_key'); // 删除 `unique_key` ('teacherid', 'pay_time')
            $table->index('pay_time');
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
