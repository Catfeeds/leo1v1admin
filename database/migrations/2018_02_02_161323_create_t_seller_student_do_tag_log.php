<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTSellerStudentDoTagLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_weiyi.t_seller_student_do_tag_log', function(Blueprint $table) {
            t_comment($table, "CC 标记资源日志表");
            t_field($table->increments("id"), "");
            t_field($table->integer("add_time"), "标记时间");
            t_field($table->integer("adminid"), "销售ID");
            t_field($table->integer("userid"), "学生ID");
            t_field($table->integer("tag_flag"), "标记类别");

            $table->index('userid');
            $table->index('adminid');
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
