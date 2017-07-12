<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTongji extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('db_weiyi_admin.t_tongji', function (Blueprint $table)
        {
            $table->increments('id');
            $table->integer('log_date');

            //报课数
            $table->float('new_course_count');  //
            $table->float('old_course_count'); //

            //消耗课时
            $table->integer('new_lesson_count');
            $table->integer('old_lesson_count');
            $table->integer('test_lesson_count');
            $table->integer('money');
            $table->integer('real_money');

            //
            $table->integer('test_free_count'); //免费试听人数
            $table->integer('test_money_count'); //付费试听人数
            $table->integer('test_money'); //付费试听总金额

            //人数
            $table->integer('new_count'); //新增
            $table->integer('next_count'); //续费

            $table->integer('old_count'); //老学生
            $table->integer('stop_count'); //停课
            $table->integer('finish_count'); //结课
            //$table->string('all_count'); //总共

            $table->index("log_date",  "log_date" );
        });

        Schema::create('db_weiyi_admin.t_tongji_user', function (Blueprint $table)
        {
            $table->increments('id');
            $table->integer('log_date');
            $table->integer('userid');
            $table->integer('course_count');
            $table->integer('lesson_count');
            $table->integer('money');
            $table->integer('real_money');
            $table->integer('type'); //新增 续费 停课 
            $table->string('origin');

            $table->index("log_date" ,"userid", "log_date__userid" );
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
