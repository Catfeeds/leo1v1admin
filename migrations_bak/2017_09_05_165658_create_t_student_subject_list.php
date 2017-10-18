<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTStudentSubjectList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_weiyi.t_student_subject_list', function( Blueprint $table)
        {
            t_field($table->integer("userid"),"学生");
            t_field($table->integer("subject"),"科目");
            t_field($table->integer("editionid"),"教材版本");
            $table->primary(["userid","subject"]);


        });

        Schema::table('db_weiyi.t_student_info', function( Blueprint $table)
        {
            t_field($table->integer("province"),"省");
            t_field($table->string("city",128),"市");
            t_field($table->string("area",128),"区");
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
