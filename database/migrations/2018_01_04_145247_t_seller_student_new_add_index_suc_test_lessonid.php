<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TSellerStudentNewAddIndexSucTestLessonid extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_seller_student_new', function( Blueprint $table)
        {
            $table->index("last_succ_test_lessonid",'suc_lessonid');
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
