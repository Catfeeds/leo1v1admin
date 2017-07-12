<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TStudentInfoAddAutoFlagSecond extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('t_student_info',function( Blueprint $table)
        {
            \App\Helper\Utils::comment_field(
                $table->integer("is_auto_set_type_flag")->default(0),"是否系统自动更新学生类型,0系统自动,1,手动修改");
            
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
