<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTStudentIntroduceCreate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_student_introduce_create', function (Blueprint $table) {
            t_comment($table, "转介绍创建信息");
            t_field($table->string("userid"), "转介绍用户id");
            t_field($table->integer("adminid"), "添加人id");
            t_field($table->integer("add_time"), "添加时间");
            $table->index('userid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_student_introduce_create');
    }
}
