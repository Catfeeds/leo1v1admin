<?php
namespace App\Models;
use \App\Enums as E;
class t_tag_library extends \App\Models\Zgen\z_t_tag_library
{
    public function __construct()
    {
        parent::__construct();
    }
    //@desn:按照检索条件展示产品标签
    public function get_tag_list($tag_l1_sort,$tag_l2_sort,$tag_l3_sort,$tag_name,$page_info){
        $where_arr = [];
        if($tag_l1_sort != '标签一级分类')
            $this->where_arr_add_str_field($where_arr, 'tl.tag_l1_sort', $tag_l1_sort,'');
        if($tag_l2_sort != '标签二级分类')
            $this->where_arr_add_str_field($where_arr, 'tl.tag_l2_sort', $tag_l2_sort,'');
        if($tag_l3_sort != '标签三级分类')
            $this->where_arr_add_str_field($where_arr, 'tl.tag_l3_sort', $tag_l3_sort,'');
        $where_arr[] = ["tl.tag_name like '%%%s%%'", $tag_name, ""];
        $sql = $this->gen_sql_new(
            'select tl.*,mi.account from %s tl'
            .' left join %s mi on tl.manager_id = mi.uid'
            .' where %s',
            self::DB_TABLE_NAME,
            t_manager_info::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list_by_page($sql,$page_info);
    }

    public function get_tag_name_list($tag_l1_sort,$tag_l2_sort){
        $where_arr=[
            ["tag_l1_sort='%s'",$tag_l1_sort,""],
            ["tag_l2_sort='%s'",$tag_l2_sort,""],
        ];
        $sql = $this->gen_sql_new(
            'select * from %s '
            .' where %s',
            self::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list($sql);

    }

    public function get_all_tag_list(){
        $where_arr=[];
        $sql = $this->gen_sql_new(
            'select * from %s '
            .' where %s',
            self::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_cultivation_list(){
        $where_arr=[
            "tag_l1_sort='教学相关'",
            "tag_l2_sort='素质培养'",
        ];
        $sql = $this->gen_sql_new(
            'select * from %s '
            .' where %s',
            self::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_teacher_nature_list(){
            $where_arr=[
                "tag_l1_sort='教师相关'",
                "tag_l2_sort='风格性格'",
            ];
        $sql = $this->gen_sql_new(
            'select * from %s '
            .' where %s',
            self::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_pro_ability_list(){
        $where_arr=[
            "tag_l1_sort='教师相关'",
            "tag_l2_sort='专业能力'",
        ];
        $sql = $this->gen_sql_new(
            'select * from %s '
            .' where %s',
            self::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_class_env_list(){
        $where_arr=[
            "tag_l1_sort='课堂相关'",
            "tag_l2_sort='课堂气氛'",
        ];
        $sql = $this->gen_sql_new(
            'select * from %s '
            .' where %s',
            self::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_courseware_list(){
        $where_arr=[
            "tag_l1_sort='课堂相关'",
            "tag_l2_sort='课件要求'",
        ];
        $sql = $this->gen_sql_new(
            'select * from %s '
            .' where %s',
            self::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list($sql);
    }

    
}
