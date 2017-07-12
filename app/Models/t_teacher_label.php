<?php
namespace App\Models;
use \App\Enums as E;
class t_teacher_label extends \App\Models\Zgen\z_t_teacher_label
{
	public function __construct()
	{
		parent::__construct();
	}

    public $field_id1_name="lessonid";

    public $field_table_name="xxx.sss";


    public function get_info_by_teacherid($teacherid,$tea_arr=[]){
        $where_arr=[
            ["teacherid=%u",$teacherid,-1]
        ];
        if(!empty($tea_arr)){
            $this->where_arr_teacherid($where_arr,"teacherid", $tea_arr);
        }

        $sql = $this->gen_sql_new("select * from %s where %s",self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_list($sql);
    }

    public function get_info_by_lessonid($lessonid, $userid, $label_origin = null){
        $where_arr=[
            ["tl.lessonid=%u",$lessonid ],
            ["l.userid=%u",$userid ],
            ["label_origin =%u ",$label_origin ]
        ];
        $sql = $this->gen_sql_new(
            "select tl.* from  %s tl "
            . " left join %s l on tl.lessonid= l.lessonid where %s",
            self::DB_TABLE_NAME,
            t_lesson_info::DB_TABLE_NAME,
            $where_arr);
        return $this->main_get_row($sql);
    }


    public function evaluate_row_delete ( $lessonid, $label_origin =1  ) {

        $where_arr=[
            "lessonid=$lessonid",
            "label_origin = $label_origin"
        ];
        $sql = $this->gen_sql_new("delete from %s where %s",self::DB_TABLE_NAME,$where_arr);

        return $this->main_update($sql);
    }

    public function get_parent_rate_info($teacherid){
        $where_arr=[
            ["teacherid=%u",$teacherid,-1],
            "label_origin=1"
        ];
        $sql =$this->gen_sql_new("select avg(level) level,count(*) num from %s where %s",self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_row($sql);
    }
}











