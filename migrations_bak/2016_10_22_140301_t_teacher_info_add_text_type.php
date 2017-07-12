<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTeacherInfoAddTextType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('t_teacher_info', function( Blueprint $table)
        {
            \App\Helper\Utils::comment_field(
                $table->integer("textbook_type"),"教材版本");
            \App\Helper\Utils::comment_field(
                $table->integer("grade_part_ex"),"年级段 1 小学 2 初中 3 高中");
            \App\Helper\Utils::comment_field(
                $table->integer("subject"),"科目");
            \App\Helper\Utils::comment_field(
                $table->integer("putonghua_is_correctly"),"普通话是否标准");
            \App\Helper\Utils::comment_field(
                $table->string("dialect_notes"),"方言备注");
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
