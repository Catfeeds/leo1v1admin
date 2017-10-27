<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVotesToTAgent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t_agent', function (Blueprint $table) {
            //
            t_field($table->integer("cycle_student_count"),'学员量[无下限限制下级]');
            t_field($table->integer("cycle_test_lesson_count"),'试听量[无下限限制下级]');
            t_field($table->integer("cycle_order_money"),'签单金额[无下限限制下级][分]');
            t_field($table->integer("cycle_member_count"),'会员量[无下限限制下级]');
            t_field($table->integer("cycle_order_count"),'所有学员量[无下限限制下级]');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('t_agent', function (Blueprint $table) {
            //
        });
    }
}
