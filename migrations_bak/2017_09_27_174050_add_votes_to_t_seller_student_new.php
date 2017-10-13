<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVotesToTSellerStudentNew extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t_seller_student_new', function (Blueprint $table) {
            //
            t_field($table->integer("cur_adminid_call_count"),"负责人通话次数");
 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('t_seller_student_new', function (Blueprint $table) {
            //
        });
    }
}
