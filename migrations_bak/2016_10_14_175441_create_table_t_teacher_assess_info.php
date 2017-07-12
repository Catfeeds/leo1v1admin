<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTTeacherAssessInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('t_teacher_assess', function (Blueprint $table)
        {
            $table->integer("teacherid");
            $table->integer("assess_time");
            $table->integer("assess_adminid");
            $table->string("content");
            $table->integer("assess_res");
            $table->string("advise_reason");
 
            $table->primary(["teacherid", "assess_time" ]);
            $table->index("assess_adminid"  );
        });

        Schema::table('t_teacher_info',function( Blueprint $table)
        {
            \App\Helper\Utils::comment_field(
                $table->integer("assess_num")->default(0),"考核次数");
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
