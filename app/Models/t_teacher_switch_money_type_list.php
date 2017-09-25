<?php
namespace App\Models;
use \App\Enums as E;
class t_teacher_switch_money_type_list extends \App\Models\Zgen\z_t_teacher_switch_money_type_list
{
	public function __construct()
	{
		parent::__construct();
	}

    public function check_is_exists($teacherid){
        $where_arr = [
            ["teacherid=%u",$teacherid,-1]
        ];
        $sql = $this->gen_sql_new("select 1"
                                  ." from %s "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_teacher_switch_list($teacherid,$teacher_money_type,$batch,$status,$month_time=-1,$not_start=0,$not_end=0){
        $where_arr = [
            ["teacherid=%u",$teacherid,-1],
            ["teacher_money_type=%u",$teacher_money_type,-1],
            ["batch=%u",$batch,-1],
            ["status=%u",$status,-1],
            ["month_time=%u",$month_time,-1],
        ];
        $not_sql = "true";
        if($not_start>0 && $not_end>0){
            $not_where = [
                ["lesson_start>%u",$not_start,0],
                ["lesson_start<%u",$not_end,0],
                "lesson_type in (0,1,3)",
                "lesson_del_flag=0",
                "confirm_flag!=2",
            ];
            $not_sql = $this->gen_sql_new("not exists (select 1 from %s where sw.teacherid=teacherid and %s)"
                                          ,t_lesson_info::DB_TABLE_NAME
                                          ,$not_where
            );
        }
        $sql = $this->gen_sql_new("select id,teacherid,teacher_money_type,level,new_level,batch,status,realname,"
                                  ." put_time,confirm_time,new_teacher_money_type,all_money_different,base_money_different,"
                                  ." lesson_total,month_time"
                                  ." from %s sw"
                                  ." where %s"
                                  ." and %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
                                  ,$not_sql
        );
        return $this->main_get_list($sql);
    }

    public function get_teacher_info_by_id($id){
        $where_arr = [
            ["id=%u",$id,0],
        ];
        $sql = $this->gen_sql_new("select teacherid,teacher_money_type,new_teacher_money_type,level,new_level"
                                  ." from %s "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_row($sql);
    }

    public function get_id_by_teacherid($teacherid){
        $where_arr = [
            ["teacherid=%u",$teacherid,-1],
        ];
        $sql = $this->gen_sql_new("select id "
                                  ." from %s "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_need_reset_list(){
        $lesson_start = strtotime("2017-8-1");
        $lesson_end   = strtotime("2017-9-1");
        $where_arr = [
            ["lesson_start>%u",$lesson_start,0],
            ["lesson_start<%u",$lesson_end,0],
            "batch in (1,2)",
            "lesson_type in (0,1,3)"
        ];
        $sql = $this->gen_sql_new("select l.teacherid,l.teacher_money_type,l.level"
                                  ." from %s sw"
                                  ." left join %s l on sw.teacherid=l.teacherid"
                                  ." where %s "
                                  ." group by l.teacherid"
                                  ,self::DB_TABLE_NAME
                                  ,t_lesson_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }

}
