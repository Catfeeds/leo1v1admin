<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TSellerStudentNewAddTestLessonOptFlag extends Migration
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
            t_field($table->integer("test_lesson_opt_flag"),"测试课堂1测试失败2测试成功");
            $table->index("test_lesson_opt_flag");
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
