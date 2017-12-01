<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTResource extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_weiyi.t_resource', function (Blueprint $table){
            $table->increments('resource_id');
                t_field($table->integer("use_type"),"使用对象 1老师 2教研 3咨询");
                t_field($table->integer("resource_type"),"资料类型");
                t_field($table->integer("subject"),"科目");
                t_field($table->integer("grade"),"年级");
                t_field($table->integer("tag_one"),"标签1，与resource_type相关，对应类型可变");
                t_field($table->integer("tag_two"),"标签2，与resource_type相关，对应类型可变");
                t_field($table->integer("tag_three"),"标签3，与resource_type相关，对应类型可变");
                t_field($table->integer("tag_four"),"标签4，与resource_type相关，对应类型可变");
                t_field($table->integer("adminid"),"创建人");
                t_field($table->integer("create_time"),"创建时间");
                t_field($table->integer("is_del"),"删除标识　0否 1删除（回收站可见,可恢复）2永久删除（回收站不显示,不可恢复）");


                $table->index("use_type");
                $table->index("resource_type");
                $table->index("subject");
                $table->index("grade");
                $table->index("create_time");
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
