<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
function add_field($filed_item,$comment) {
    \App\Helper\Utils::comment_field($filed_item ,$comment);
}



class TOrderInfoFromTestLessonId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t_order_info', function( Blueprint $table)
        {
            add_field($table->integer("from_test_lesson_id"),"来自哪节试听课");
            $table->index("from_test_lesson_id");
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
