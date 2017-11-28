<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTestLessonSubjectRequireAddIndexAcceptFlag extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_weiyi.t_test_lesson_subject', function (Blueprint $table){
            $table->index("require_admin_type");
        });
        Schema::create('db_weiyi.t_test_lesson_subject_require', function (Blueprint $table){
            $table->index("accept_flag");
        });
        Schema::create('db_weiyi.t_lesson_info', function (Blueprint $table){
            $table->index("lesson_user_online_status");
        });
        Schema::create('db_weiyi_admin.t_flow', function (Blueprint $table){
            $table->index("flow_status");
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
