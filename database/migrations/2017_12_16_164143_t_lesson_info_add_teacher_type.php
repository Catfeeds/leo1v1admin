<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TLessonInfoAddTeacherType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        $check_exists = Schema::hasColumn('t_lesson_info','teacher_type');
        if(!$check_exists){
            Schema::table('db_weiyi.t_lesson_info', function( Blueprint $table)
            {
                t_field($table->integer("teacher_type"),"老师类型 具体见枚举类 teacher_type");
            });
            echo "migrate succ";
        }else{
            echo "column has exists";
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('db_weiyi.t_lesson_info', function( Blueprint $table)
        {
            $table->dropColumn("teachcer_type");
        });
    }
}
