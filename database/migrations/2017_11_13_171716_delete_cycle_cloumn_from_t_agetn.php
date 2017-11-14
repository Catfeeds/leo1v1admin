<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteCycleCloumnFromTAgetn extends Migration
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
            $table->dropColumn('cycle_student_count');
                $table->dropColumn('cycle_test_lesson_count');
                $table->dropColumn('cycle_order_money');
                $table->dropColumn('cycle_member_count');
                $table->dropColumn('cycle_order_count');
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
