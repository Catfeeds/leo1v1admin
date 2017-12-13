<?php
namespace App\Models;
use \App\Enums as E;
/**

 * @property t_test_lesson_subject  $t_test_lesson_subject

 * @property t_seller_student_origin  $t_seller_student_origin


 * @property t_student_info  $t_student_info

 * @property t_origin_key  $t_origin_key
 * @property t_admin_main_group_name  $t_admin_main_group_name
 */

class t_seller_student_origin extends \App\Models\Zgen\z_t_seller_student_origin
{
    public function __construct()
    {
        parent::__construct();

    }

    public function check_and_add($userid,$origin,$subject ) {
        $ret_row = $this->field_get_list_2($userid,$origin, "*");
        if($ret_row){
            return false;
        }else{
            $this->row_insert([
                'userid'   => $userid,
                'origin'   => $origin,
                'add_time' => time(NULL),
                'subject'  => $subject,
            ]);
            return true;
        }
    }
    public function get_user_revisit_count_info($start_time,$end_time){
        $where_arr=[
            ["o.add_time >= %u",$start_time,-1],
            ["o.add_time <= %u",$end_time,-1],
        ];
        $sql = $this->gen_sql_new("select o.add_time,tq_called_flag,global_tq_called_flag ".
                                  " from %s o left join %s n on o.userid = n.userid".
                                  " where %s",
                                  self::DB_TABLE_NAME,
                                  t_seller_student_new::DB_TABLE_NAME,
                                  $where_arr

        );
        return $this->main_get_list($sql);
    }


    public function get_origin_tongji_info_single( $field_name, $opt_date_str,$start_time,$end_time,$origin,$origin_ex,$origin_level){
        switch ( $field_name ) {
        case  "grade" :
            $field_name="s.grade";
            break;
        default:
            break;
        }
        $where_arr=[
            ["origin like '%%%s%%' ",$origin,""],
        ];
        $this->where_arr_add_time_range($where_arr,$opt_date_str,$start_time,$end_time);
        $ret_in_str=$this->t_origin_key->get_in_str_key_list($origin_ex,"s.origin");
        $where_arr[]= $ret_in_str;
        $this->where_arr_add_int_or_idlist($where_arr,"origin_level",$origin_level);

        $sql = $this->gen_sql_new("select $field_name as check_value ,count(*) all_count,sum(global_tq_called_flag <>0) tq_called_count,sum(global_tq_called_flag=0 ) tq_no_call_count, sum(global_tq_called_flag=0 and global_seller_student_status =0  ) no_call_count,sum(n.admin_revisiterid >0) assigned_count,sum( global_seller_student_status = 1) invalid_count,sum(global_seller_student_status =2) no_connected_count,sum(global_seller_student_status =100) have_intention_a_count,sum(global_seller_student_status =101) have_intention_b_count,sum(global_seller_student_status =102)  have_intention_c_count,  sum( global_tq_called_flag =1 ) tq_call_fail_count ,sum( global_tq_called_flag =2 and  global_seller_student_status=1 ) tq_call_succ_invalid_count, sum( tmk_student_status=3 ) tmk_valid_count ".
                                  " from %s n "
                                  ." left join %s s on s.userid = n.userid"
                                  . " where %s group by  check_value ",
                                  t_seller_student_new::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list_as_page($sql,function($item) {
            return $item["check_value"];
        });

    }

    public function get_origin_tongji_info( $field_name, $opt_date_str,$start_time,$end_time,$origin,$origin_ex,$seller_groupid_ex,$adminid_list=[],$tmk_adminid=-1){
        switch ( $field_name ) {
        case  "grade" :
            $field_name="s.grade";
            break;
        default:
            break;
        }
        $where_arr=[
            ["origin like '%%%s%%' ",$origin,""],
            'require_admin_type=2',
            // 'origin_assistantid>0' //test
        ];
        $this->where_arr_add_time_range($where_arr,$opt_date_str,$start_time,$end_time);
        $this->where_arr_add__2_setid_field($where_arr,"tmk_adminid",$tmk_adminid);
        $ret_in_str=$this->t_origin_key->get_in_str_key_list($origin_ex,"s.origin");
        $where_arr[]= $ret_in_str;
        $this->where_arr_adminid_in_list($where_arr,"n.first_seller_adminid",$adminid_list);
        $sql = $this->gen_sql_new(
            "select $field_name as check_value ,count(*) all_count,sum(global_tq_called_flag <>0) tq_called_count, sum(global_tq_called_flag=0 and seller_student_status =0  ) no_call_count,sum(n.admin_revisiterid >0) assigned_count,sum( t.seller_student_status = 1) invalid_count,sum(t.seller_student_status =2) no_connected_count,"
            . "sum(t.seller_student_status =100 and  global_tq_called_flag =2 ) have_intention_a_count,sum(t.seller_student_status =101 and  global_tq_called_flag =2) have_intention_b_count,sum(t.seller_student_status =102 and  global_tq_called_flag =2)  have_intention_c_count,  "

            ." sum( tmk_student_status=3 ) tmk_assigned_count ,"
            . " sum(global_tq_called_flag=0 ) tq_no_call_count,  "
            . " sum( global_tq_called_flag =1 ) tq_call_fail_count , "
            . "sum( global_tq_called_flag =1 and  n.sys_invaild_flag =1 ) tq_call_fail_invalid_count , "
            . "sum( global_tq_called_flag =2 and  n.sys_invaild_flag =1 ) tq_call_succ_invalid_count  ,"
            . "avg( if(   add_time<first_call_time , first_call_time-add_time,null) ) avg_first_time, "
            . "sum( global_tq_called_flag =2 and  n.sys_invaild_flag=0  ) tq_call_succ_valid_count  "
            // ."sum()"
            ." from %s n "
            ." left join %s s on s.userid = n.userid".
            " left join %s t on t.userid= n.userid ".
            // " left join %s a on a.phone= n.phone ".
            // " left join %s ao on ao.aid= a.id ".
            " where %s group by  check_value ",
            t_seller_student_new::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            t_test_lesson_subject::DB_TABLE_NAME,
            // t_agent::DB_TABLE_NAME,
            // t_agent_order::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list_as_page($sql,function($item) {
            return $item["check_value"];
        });
    }



    public function get_origin_tongji_info_not_intention( $field_name, $opt_date_str,$start_time,$end_time,$origin,$origin_ex,$seller_groupid_ex,$adminid_list=[],$tmk_adminid=-1){
        switch ( $field_name ) {
        case  "grade" :
            $field_name="s.grade";
            break;
        default:
            break;
        }


        $where_arr=[
            ["origin like '%%%s%%' ",$origin,""],
            'require_admin_type=2',
        ];
        $this->where_arr_add_time_range($where_arr,$opt_date_str,$start_time,$end_time);
        $this->where_arr_add__2_setid_field($where_arr,"tmk_adminid",$tmk_adminid);
        $ret_in_str=$this->t_origin_key->get_in_str_key_list($origin_ex,"s.origin");
        $where_arr[]= $ret_in_str;
        $this->where_arr_adminid_in_list($where_arr,"t.require_adminid",$adminid_list);

        $sql = $this->gen_sql_new("select $field_name as check_value ,count(*) all_count,sum(global_tq_called_flag <>0) tq_called_count,sum(global_tq_called_flag=0 ) tq_no_call_count, sum(global_tq_called_flag=0 and seller_student_status =0  ) no_call_count,sum(n.admin_revisiterid >0) assigned_count,sum( t.seller_student_status = 1) invalid_count,sum(t.seller_student_status =2) no_connected_count,  sum( global_tq_called_flag =1 ) tq_call_fail_count ,sum( global_tq_called_flag =2 and  t.seller_student_status=1 ) tq_call_succ_invalid_count  ,"
                                  . " sum( tmk_student_status=3 ) tmk_assigned_count  ".
                                  " from %s n "
                                  ."left join %s s on s.userid = n.userid".
                                  " left join %s t on t.userid= n.userid ".
                                  " where %s group by  check_value ",
                                  t_seller_student_new::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list_as_page($sql,function($item) {
            return $item["check_value"];
        });

    }






    public function get_origin_detail_info($opt_date_str, $start_time,$end_time,$origin,$origin_ex,$seller_groupid_ex,$adminid_list=[],$tmk_adminid=-1){

        $this->switch_tongji_database();

        $where_arr=[
            ["s.origin like '%%%s%%' ",$origin,""],
            'require_admin_type=2',
        ];

        $this->where_arr_add_time_range($where_arr,$opt_date_str,$start_time,$end_time);
        $ret_in_str=$this->t_origin_key->get_in_str_key_list($origin_ex,"s.origin");
        $adminid_in_str = $this->t_admin_main_group_name->get_in_str_adminid_list($seller_groupid_ex,"n.admin_revisiterid");
        $where_arr[]= $ret_in_str;
        $where_arr[]= $adminid_in_str;

        $this->where_arr_add__2_setid_field($where_arr,"tmk_adminid",$tmk_adminid);
        $this->where_arr_adminid_in_list($where_arr,"t.require_adminid",$adminid_list);

        $sql = $this->gen_sql_new(
            "select t.subject,t.grade,n.has_pad,n.phone_location ,s.origin_level,".
            " if (o.pay_time>0 and o.contract_type in (0,3) and o.contract_status>0,1,0) as order_user".
            " from %s n left join %s s on s.userid=n.userid".
            " left join %s t on t.userid = n.userid ".
            " left join %s o on t.userid = o.userid ".
            " where %s",
            t_seller_student_new::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            t_test_lesson_subject::DB_TABLE_NAME,
            t_order_info::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_rejoin_user_list( $page_num,$origin_ex, $start_time, $end_time, $need_count,$seller_student_status )  {
        $ret_in_str=$this->t_origin_key->get_in_str_key_list($origin_ex,"b.origin");
        $where_arr=[
            $ret_in_str,
        ];
        $this->where_arr_add_int_field($where_arr,"seller_student_status",$seller_student_status);

        $this->where_arr_add_time_range($where_arr,"b.add_time",$start_time,$end_time);

        $sql=$this->gen_sql_new(
            ["select  a.origin ,a.add_time, a.userid , s.nick ,n.phone, n.has_pad ,s.grade,a.origin  , n.admin_revisiterid  , count(*) count, n.last_revisit_time ",
             "from %s a ",
             "left join %s s on s.userid = a.userid  ",
             "left join %s n on n.userid = a.userid  ",
             " where a.userid in (select userid from %s b where %s  )  group by a.userid   having  count= $need_count ",
            ],
            self::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            t_seller_student_new::DB_TABLE_NAME,
            //t_test_lesson_subject::DB_TABLE_NAME,
            self::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list_by_page($sql,$page_num,10,true );
    }

    public function get_rejoin_count_list($origin_ex, $start_time, $end_time)  {
        $ret_in_str=$this->t_origin_key->get_in_str_key_list($origin_ex,"b.origin");
        $where_arr=[
            $ret_in_str,
        ];
        $this->where_arr_add_time_range($where_arr,"b.add_time",$start_time,$end_time);
        $sql=$this->gen_sql_new(
            "select  userid , count(*) as count  "
            ." from  %s a "
            . " where  a.userid in (select b.userid from %s b where %s  )     group by a.userid   ",
            self::DB_TABLE_NAME,
            self::DB_TABLE_NAME,
            $where_arr
        );
        $list=$this->main_get_list($sql);
        $count_map=[];
        foreach ( $list as $item ){
            $count=$item["count"];
            $count_map[$count] = @$count_map[$count]+1;
        }
        $count_list=[];
        foreach ($count_map as $key=> $value ) {
            $count_list[]=["rejoin_count" => $key, "count" =>$value ];
        }
        return $count_list;
    }

    public function get_user_rejoin_list($userid )
    {
        $sql = $this->gen_sql_new("select  o.*, seller_student_status from %s  o"
                                  ." left  join  %s t on (o.userid=t.userid and o.subject =t.subject )"
                                  . " where o.userid=%u  " ,
                                  self::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  $userid );
        return $this->main_get_list_as_page($sql);
    }
    public function get_origin_user_list($page_info, $start_time, $end_time, $userid, $origin,$origin_ex) {
        $where_arr=[];
        if ($userid >0) {
            $this->where_arr_add_int_field($where_arr,"so.userid", $userid );
        }else{
            $this->where_arr_add_str_field($where_arr,"so.origin", $origin );
            $this->where_arr_add_time_range($where_arr,"so.add_time",$start_time,$end_time);
        }

        $ret_in_str=$this->t_origin_key->get_in_str_key_list($origin_ex,"so.origin");
        $where_arr[]= $ret_in_str;
        $sql=$this->gen_sql_new(
            "select so.* ,s.origin as cur_origin,  n.phone "
            . " from %s  so    "
            . "left join  %s n on so.userid= n.userid "
            . "left join  %s s on s.userid= n.userid "
            ." where  %s order by so.add_time desc "
            , self::DB_TABLE_NAME
            , t_seller_student_new::DB_TABLE_NAME
            ,t_student_info::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_list_by_page($sql,$page_info);

    }


    public function get_origin_tongji_info_one( $field_name, $opt_date_str,$start_time,$end_time,$origin,$origin_ex,$seller_groupid_ex,$adminid_list=[],$tmk_adminid=-1){
        switch ( $field_name ) {
        case  "grade" :
            $field_name="s.grade";
            break;
        default:
            break;
        }


        $where_arr=[
            ["origin like '%%%s%%' ",$origin,""],
            'require_admin_type=2',
        ];
        $this->where_arr_add_time_range($where_arr,$opt_date_str,$start_time,$end_time);
        $this->where_arr_add__2_setid_field($where_arr,"tmk_adminid",$tmk_adminid);
        $ret_in_str=$this->t_origin_key->get_in_str_key_list($origin_ex,"s.origin");
        $where_arr[]= $ret_in_str;
        $this->where_arr_adminid_in_list($where_arr,"t.require_adminid",$adminid_list);

        $sql = $this->gen_sql_new("select $field_name as check_value ,count(*) all_count,  sum( global_tq_called_flag =1 ) tq_call_fail_count ,sum( global_tq_called_flag =2 and  t.seller_student_status=1 ) tq_call_succ_invalid_count  ".
                                  " from %s n "
                                  ."left join %s s on s.userid = n.userid".
                                  " left join %s t on t.userid= n.userid ".
                                  " where %s group by  check_value ",
                                  t_seller_student_new::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list_as_page($sql,function($item) {
            return $item["check_value"];
        });

    }
    public function  del_by_userid($userid) {
        $sql= $this->gen_sql_new("delete from %s where userid=$userid", self::DB_TABLE_NAME);
        return $this->main_update($sql);
    }


    public function get_tmk_tongji_info( $field_name, $opt_date_str,$start_time,$end_time,$origin,$origin_ex,$seller_groupid_ex,$adminid_list=[],$tmk_adminid=-1, $origin_level=-1,$wx_invaild_flag){

        $this->switch_tongji_database();


        switch ( $field_name ) {
        case  "grade" :
            $field_name="s.grade";
            break;
        default:
            // break;
        }

        $where_arr=[
            ["origin like '%%%s%%' ",$origin,""],
            'require_admin_type=2',
            'tmk_adminid>0'

        ];
        $this->where_arr_add_time_range($where_arr,$opt_date_str,$start_time,$end_time);
        $this->where_arr_add__2_setid_field($where_arr,"tmk_adminid",$tmk_adminid);
        $ret_in_str=$this->t_origin_key->get_in_str_key_list($origin_ex,"s.origin");
        $where_arr[]= $ret_in_str;
        $this->where_arr_adminid_in_list($where_arr,"t.require_adminid",$adminid_list);
        $this->where_arr_add_int_or_idlist($where_arr,"s.origin_level",$origin_level);
        //wx
        $this->where_arr_add_int_field($where_arr,"wx_invaild_flag",$wx_invaild_flag);



        $sql = $this->gen_sql_new("select $field_name as check_value ,count(*) all_count,sum(n.admin_revisiterid >0) assigned_count, sum(global_tq_called_flag <>0) tq_called_count,sum(global_tq_called_flag=0 ) tq_no_call_count, sum(global_tq_called_flag=0 and seller_student_status =0  ) no_call_count,sum( t.seller_student_status = 1) invalid_count,sum(t.seller_student_status =2) no_connected_count,sum(t.seller_student_status =100 and  global_tq_called_flag =2 ) have_intention_a_count,sum(t.seller_student_status =101 and  global_tq_called_flag =2) have_intention_b_count,sum(t.seller_student_status =102 and  global_tq_called_flag =2)  have_intention_c_count,  sum( global_tq_called_flag =1 ) tq_call_fail_count, sum(t.seller_student_status =101 and  global_tq_called_flag =2) have_intention_b_count,sum(t.seller_student_status =102 and  global_tq_called_flag =2)  have_intention_c_count,"
                                  ." sum( tmk_student_status=3 ) tmk_assigned_count  "
                                  ." from %s n "
                                  ." left join %s s on s.userid = n.userid".
                                  " left join %s t on t.userid= n.userid ".
                                  " where %s group by  check_value ",
                                  t_seller_student_new::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list_as_page($sql,function($item) {
            return $item["check_value"];
        });
    }





    public function get_tmk_count_info( $field_name, $opt_date_str,$start_time,$end_time,$origin,$origin_ex,$seller_groupid_ex,$adminid_list=[],$tmk_adminid=-1){
        switch ( $field_name ) {
        case  "grade" :
            $field_name="s.grade";
            break;
        default:
            break;
        }

        $where_arr=[
            ["origin like '%%%s%%' ",$origin,""],
            'require_admin_type=2',
        ];
        // $this->where_arr_add_time_range($where_arr,$opt_date_str,$start_time,$end_time);
        $this->where_arr_add_time_range($where_arr,"lesson_start",$start_time,$end_time);
        $this->where_arr_add__2_setid_field($where_arr,"tmk_adminid",$tmk_adminid);
        $ret_in_str=$this->t_origin_key->get_in_str_key_list($origin_ex,"s.origin");
        $where_arr[]= $ret_in_str;
        $this->where_arr_adminid_in_list($where_arr,"t.require_adminid",$adminid_list);

        $sql = $this->gen_sql_new("select $field_name as check_value ,count(*) as tmk_count "
                                  ." from %s n "
                                  // ." left join %s tl on tl.userid = s.userid"
                                  ." left join %s s on s.userid = n.userid".
                                  " left join %s tl on tl.userid = s.userid".

                                  " left join %s t on t.userid= n.userid ".
                                  " where %s group by  check_value ",
                                  // t_lesson_info::DB_TABLE_NAME,
                                  t_seller_student_new::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,

                                  t_student_info::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list_as_page($sql,function($item) {
            return $item["check_value"];
        });
    }



    public function get_origin_tongji_info_vaild( $field_name, $opt_date_str,$start_time,$end_time,$origin,$origin_ex){
        switch ( $field_name ) {
        case  "grade" :
            $field_name="s.grade";
            break;
        default:
            break;
        }
        $where_arr=[
            ["origin like '%%%s%%' ",$origin,""],
        ];
        $this->where_arr_add_time_range($where_arr,$opt_date_str,$start_time,$end_time);
        $ret_in_str=$this->t_origin_key->get_in_str_key_list($origin_ex,"s.origin");
        $where_arr[]= $ret_in_str;

        $sql = $this->gen_sql_new("select $field_name as check_value ,count(*) all_count,sum(global_tq_called_flag <>0) tq_called_count,sum(global_tq_called_flag=0 ) tq_no_call_count, sum(global_tq_called_flag=0 and global_seller_student_status =0  ) no_call_count,sum(n.admin_revisiterid >0) assigned_count,sum( global_seller_student_status = 1) invalid_count,sum(global_seller_student_status =2) no_connected_count,sum(global_seller_student_status =100) have_intention_a_count,sum(global_seller_student_status =101) have_intention_b_count,sum(global_seller_student_status =102)  have_intention_c_count,  sum( global_tq_called_flag =1 ) tq_call_fail_count ,sum( global_tq_called_flag =2 and  n.origin_vaild_flag=2 ) tq_call_succ_invalid_count,sum( global_tq_called_flag =2 and  n.origin_vaild_flag=1 ) tq_call_succ_valid_count   ".
                                  " from %s n "
                                  ." left join %s s on s.userid = n.userid"
                                  . " where %s group by  check_value ",
                                  t_seller_student_new::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list_as_page($sql,function($item) {
            return $item["check_value"];
        });

    }




    public function get_origin_tongji_info_for_jy( $field_name, $opt_date_str,$start_time,$end_time,$origin,$origin_ex,$seller_groupid_ex,$adminid_list=[],$tmk_adminid=-1){
        $where_arr=[
            'require_admin_type=2',
        ];
        $this->where_arr_add_time_range($where_arr,$opt_date_str,$start_time,$end_time);
        $ret_in_str=$this->t_origin_key->get_in_str_key_list($origin_ex,"s.origin");
        $where_arr[]= $ret_in_str;
        // $this->where_arr_adminid_in_list($where_arr,"n.first_seller_adminid",$adminid_list);
        $sql = $this->gen_sql_new(
            "select $field_name as check_value "
            ." from %s n "
            ." left join %s s on s.userid = n.userid".
            " left join %s t on t.userid= n.userid ".
            " where %s group by  check_value ",
            t_seller_student_new::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            t_test_lesson_subject::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list_as_page($sql,function($item) {
            return $item["check_value"];
        });
    }

    public function get_origin_by_userid($userid){
        $sql = "select origin from db_weiyi.t_seller_student_origin where userid = $userid";
        return $this->main_get_value($sql);
    }

    public function get_count_origin($userid){
        $sql = "select count(*) as count from db_weiyi.t_seller_student_origin where userid = $userid ";
        return $this->main_get_value($sql);
    }



}
