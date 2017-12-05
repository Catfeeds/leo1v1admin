<?php
namespace App\Models;
use \App\Enums as E;
class t_order_lesson_list extends \App\Models\Zgen\z_t_order_lesson_list
{
	public function __construct()
	{
		parent::__construct();
	}

    public function order_lesson_list($userid,$competition_flag,$page_num){
        $sql=$this->gen_sql_new("select ol.orderid,l.lessonid,lesson_start,lesson_end,l.teacherid,l.grade,"
                                ." ol.price,ol.lesson_count, l.subject,"
                                ." l.lesson_cancel_reason_type,l.lesson_cancel_reason_next_lesson_time, "
                                ." l.confirm_adminid,l.confirm_time,confirm_reason,l.confirm_flag,o.contract_type "
                                ." from %s ol"
                                ." left join %s l on l.lessonid=ol.lessonid "
                                ." left join %s o on o.orderid=ol.orderid"
                                ." where ol.userid =%u "
                                ." and l.competition_flag=%u"
                                ." and lesson_del_flag=0 "
                                ." order by lesson_end desc,o.orderid desc"
                                ,self::DB_TABLE_NAME
                                ,t_lesson_info::DB_TABLE_NAME
                                ,t_order_info::DB_TABLE_NAME
                                ,$userid
                                ,$competition_flag
        );
        return $this->main_get_list_by_page($sql,$page_num);
    }

    public function get_simple_order_info($lessonid){
        $sql=$this->gen_sql_new("select ol.orderid,ol.lesson_count,o.contract_status,o.contract_type,o.lesson_left"
                                ." l.lesson_status,l.lesson_end"
                                ." from %s ol"
                                ." left join %s o on o.orderid=ol.orderid"
                                ." left join %s l on l.lessonid=ol.lessonid"
                                ." where ol.lessonid=%u"
                                ." and lesson_del_flag=0 "
                                ,self::DB_TABLE_NAME
                                ,t_order_info::DB_TABLE_NAME
                                ,t_lesson_info::DB_TABLE_NAME
                                ,$lessonid
        );
        return $this->main_get_row($sql);
    }

    public function check_lessonid($lessonid){
        $sql = $this->gen_sql_new("select count(1) from %s where lessonid=%u"
                                ,self::DB_TABLE_NAME
                                ,$lessonid
        );
        return $this->main_get_value($sql);
    }

    /**
     * 每堂课可能会属于不同的合同
     */
    public function get_lesson_info($lessonid){
        $where_arr=[
            ["lessonid=%u",$lessonid,0]
        ];
        $sql = $this->gen_sql_new("select orderid,lessonid,lesson_count "
                                  ." from %s"
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_all_lesson_money($start_time,$end_time){
        $where_arr = [
            ["l.lesson_start>%u",$start_time,0],
            ["l.lesson_start<%u",$end_time,0],
            "lesson_type in (0,1,3)",
            "s.is_test_user=0"
        ];
        $sql = $this->gen_sql_new("select sum(price)/100 as all_money  "
                                  ." from %s l force index(lesson_type_and_start)"
                                  ." left join %s ol on l.lessonid=ol.lessonid"
                                  ." left join %s s on l.userid=s.userid"
                                  ." where %s"
                                  ." group by l.lessonid"
                                  ,t_lesson_info::DB_TABLE_NAME
                                  ,self::DB_TABLE_NAME
                                  ,t_student_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_value($sql);
    }

    // public function get_lesson_info_tmp($last_normal_id){
    //     $sql = $this->gen_sql_new("  select l.teacherid, l.subject, l.userid from %s l "
    //                               ." where l.lessonid = %d"
    //                               ,t_lesson_info::DB_TABLE_NAME
    //                               ,$last_normal_id
    //     );

    //     return $this->main_get_row($sql);
    // }

    public function get_last_lessonid($subject,$userid,$grade,$lesson_start){
        $sql = $this->gen_sql_new("  select l.teacherid from %s l  "
                                  ." where l.lesson_type in (0,1,3) and l.subject=$subject and l.userid=$userid  and l.lesson_start>$lesson_start order by l.lesson_start asc limit 1 "
                                  ,t_lesson_info::DB_TABLE_NAME
        );

        return $this->main_get_value($sql);
    }



    // public function get_last_lessonid($orderid){
    //     $sql = $this->gen_sql_new("  select ol.lessonid from %s ol left join %s l on l.lessonid=ol.lessonid where l.lesson_del_flag=0 and ol.orderid=%s order by lessonid asc limit 1 "
    //                               ,self::DB_TABLE_NAME
    //                               ,t_lesson_info::DB_TABLE_NAME
    //                               ,$orderid
    //     );

    //     return $this->main_get_value($sql);
    // }



}
