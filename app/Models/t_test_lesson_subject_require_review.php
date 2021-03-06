<?php
namespace App\Models;
use \App\Enums as E;
class t_test_lesson_subject_require_review extends \App\Models\Zgen\z_t_test_lesson_subject_require_review
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_all_list($page_info,$adminid,$user_info){
        $where_arr = [];
        if(!in_array($adminid,[831,898])){
            $where_arr[] = " r.group_adminid=$adminid or r.master_adminid=$adminid ";
        }
        if ($user_info >0 ) {
            if  ($user_info < 10000) {
                $where_arr[]=[  "t1.uid=%u", $user_info, "" ] ;
            }else{
                $where_arr[]=[  "t1.phone like '%%%s%%'", $user_info, "" ] ;
            }
        }else{
            if ($user_info!=""){
                $where_arr[]=array( "(t1.account like '%%%s%%' or  t1.name like '%%%s%%')",
                                    array(
                                        $this->ensql($user_info),
                                        $this->ensql($user_info)));
            }
        }

        $sql=$this->gen_sql_new (" select r.*,"
                                 ." s.phone,s.nick "
                                 ." from %s r "
                                 ." left join %s s on s.userid = r.userid"
                                 ." left join %s t1 on t1.uid = r.adminid"
                                 ." where %s "
                                 ,self::DB_TABLE_NAME
                                 ,t_student_info::DB_TABLE_NAME
                                 ,t_manager_info::DB_TABLE_NAME
                                 ,$where_arr
        );
        return $this->main_get_list_by_page($sql,$page_info);
    }

    public function get_row_by_adminid_userid($adminid,$userid){
        $where_arr = [
            ['adminid = %u',$adminid,-1],
            ['userid = %u',$userid,-1],
            'group_suc_flag = 1',
            'master_suc_flag = 1',
        ];
        $sql=$this->gen_sql_new (" select * "
                                 ." from %s "
                                 ." where %s limit 1 "
                                 ,self::DB_TABLE_NAME
                                 ,$where_arr
        );
        return $this->main_get_row($sql);
    }

    public function get_week_test_lesson_count($adminid,$start_time,$end_time){
        $where_arr = [];
        $this->where_arr_add_int_field($where_arr,'adminid',$adminid);
        $this->where_arr_add_time_range($where_arr,'create_time',$start_time,$end_time);
        $sql = $this->gen_sql_new(
            " select id,adminid,userid "
            ." from %s "
            ." where %s "
            ,self::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_list($sql);
    }

}
