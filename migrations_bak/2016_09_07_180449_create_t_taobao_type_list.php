<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTTaobaoTypeList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * cid        淘宝的分类ID
         * parent_cid cid的父类cid 若是根id 则parent_cid为0
         * name       分类名称
         * sort_order 分类排序
         * type       分类的类型(可能会有其他类别) 0 默认值 1 首页显示 
         * status     是否在1对1课程的分类中显示此分类 0 不显示 1 显示
         */
        Schema::create('t_taobao_type_list',function(Blueprint $table)
        {
            $table->integer('cid');
            $table->integer('parent_cid')->default(0);
            $table->string('name');
            $table->integer('sort_order');
            \App\Helper\Utils::comment_field($table->integer("type")->default(0),"分类的类型 0 默认值 1 首页显示") ;
            \App\Helper\Utils::comment_field($table->integer("status")->default(1),"是否显示此分类 0 不显示 1 显示") ;
            
            $table->primary(["cid","parent_cid"]);
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
