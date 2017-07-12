<?php

namespace App\Config;
class page_table {
    static public $config=[
       "tea_manage-tea_lesson_list-0"  =>  [
           "field_list" =>["id",
                           "类型",
                           "上课时段",
                           "老师信息",
                           "学生/渠道",
                           "老师金额",
                           "老师金额2",
                           "课时数",
                           "课时确认",
                           "评价反馈",
           ],

           "row_opt_list" => ["opt-change-price"],

           "filter_list" => [
               "id_start_date", "id_teacherid","id_studentid"
           ],
       ] ,
       "seller_student-student_list_read-0"  =>  [
           "field_list" =>[
                           "id",
                           "序号",
                           "基本信息",
                           "时间",
                           "来源",
                           "负责人",
                           "回访记录",
                           "排课信息",
                           "最后一次回访时间",
           ],

           "row_opt_list" =>[],

           "filter_list" => null ,
           "hide_filter_list" => [
               "id_upload_xls",
               "id_add_user",
               "id_assign_seller_select",
               "id_assign_seller_del",
               "id_upload_xls_jinshuju",
               "id_upload_xls_youzan",

           ],
       ] ,


    ];
    static function get_config($table_key) {
        if (isset (static::$config[$table_key])) {
            return static::$config[$table_key] ;
        }else{
            return null; 
        }
    }
}