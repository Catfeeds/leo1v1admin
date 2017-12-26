<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTeachingCoreDataAddFulltimeTeacherData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        
        Schema::table('db_weiyi.t_teaching_core_data', function( Blueprint $table)
        {
            $table->dropColumn("platform_teacher_count'");
            t_field($table->integer("platform_teacher_count"),"平台上课老师总人数");

            

            
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
