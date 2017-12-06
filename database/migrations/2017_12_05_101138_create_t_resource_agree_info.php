<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTResourceAgreeInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_weiyi.t_resource_agree_info', function (Blueprint $table){
            $table->increments('agree_id');
                t_field($table->integer("resource_type"),"资料类型");
                t_field($table->integer("subject"),"科目");
                t_field($table->integer("grade"),"年级");
                t_field($table->integer("tag_one"),"标签1，与resource_type相关，对应类型可变");
                t_field($table->integer("tag_two"),"标签2，与resource_type相关，对应类型可变");
                t_field($table->integer("tag_three"),"标签3，与resource_type相关，对应类型可变");
                t_field($table->integer("tag_four"),"标签4，与resource_type相关，对应类型可变");
                t_field($table->integer("is_ban"),"是否禁用 0否 1禁用");
                t_field($table->integer("lock_adminid"),"禁用者");
                t_field($table->integer("lock_time"),"禁用时间");
                t_field($table->integer("agree_adminid"),"开放者");
                t_field($table->integer("agree_time"),"开放时间");


                $table->index("resource_type");
                $table->index("subject");
                $table->index("grade");
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
