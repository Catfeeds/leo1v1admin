<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
function add_field($filed_item,$comment) {
    \App\Helper\Utils::comment_field($filed_item ,$comment);
}


class TTestLessonSubjectSubListAlter1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('t_test_lesson_subject_require', function( Blueprint $table)
        {
            $table->dropColumn("lesson_success_flag");
        });



        Schema::table('t_test_lesson_subject_sub_list', function( Blueprint $table)
        {
            add_field($table->integer("orderid"),"订单id");
            $table->index("orderid");
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
