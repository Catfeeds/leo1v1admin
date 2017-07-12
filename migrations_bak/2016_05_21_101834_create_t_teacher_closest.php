<?php


use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTTeacherClosest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
	 Schema::create('t_teacher_closest', function (Blueprint $table) {
             $table->Integer('teacherid');	
             $table->Integer('subject');	
             $table->Integer('grade');	
             $table->Integer('degree');	
             $table->string('introduction');
             $table->primary(['teacherid']);
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
        Schema::drop('t_teacher_closest');
    }
}
