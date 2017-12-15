<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTeacherResourceAddDirId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("db_weiyi.t_teacher_resource", function(Blueprint $table) {
            t_field($table->integer("dir_id"), "目录id");

            $table->index("file_title");
            $table->index("dir_id");

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
