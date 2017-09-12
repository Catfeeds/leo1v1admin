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

    public function get_teacher_switch_list($teacherid,$teacher_money_type,$batch,$status){
        $where_arr = [
            ["teacherid=%u",$teacherid,-1],
            ["teacher_money_type=%u",$teacher_money_type,-1],
            ["batch=%u",$batch,-1],
            ["status=%u",$status,-1],
        ];
        $sql = $this->gen_sql_new("select id,teacherid,teacher_money_type,level,new_level,batch,status,realname,"
                                  ." put_time,confirm_time,new_teacher_money_type,all_money_different,base_money_different,"
                                  ." lesson_total"
                                  ." from %s "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
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
        return $this->main_get_list($sql);

    }


}
