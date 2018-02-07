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
            $is_exist_count = $this->get_is_exist_count_check($userid,$min=1460537365,time());
            $this->row_insert([
                'userid'   => $userid,
                'origin'   => $origin,
                'add_time' => time(NULL),
                'subject'  => $subject,
                'is_exist_count'  => $is_exist_count,
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

    //@desn:获取微信推广信息
    //@param:$field_name 检索项
    //@param:$opt_date_str 检索时间字段名称
    //@param:$start_time $end_time 开始结束时间
    //@param:$origin 渠道名称
    //@param:$origin_ex 渠道key字符串
    //@param:$seller_groupid_ex 销售小组id
    //@param:$adminid_list 管理员id数组
    //@param:$tmk_adminid 管理员id
    //@param:$origin_level 渠道等级
    //@param:$wx_invaild_flag 微信是否可见
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



    //@desn:获取渠道统计[new]
    //@param:$field_name 搜索类型[渠道、年级 ...]
    //@param:$start_time $end_time 开始结束时间
    //@param:$adminid_list 
    //@param:$tmk_adminid tmk检索用
    //@param:$origin_ex 渠道key值检索用
    //@param:$origin 渠道名称检索
    public function get_origin_tongji_info_new( $field_name, $opt_date_str,$start_time,$end_time,$origin,$origin_ex,$seller_groupid_ex,$adminid_list=[],$tmk_adminid=-1){
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
            's.is_test_user = 0'
        ];
        $this->where_arr_add_time_range($where_arr,$opt_date_str,$start_time,$end_time);
        $this->where_arr_add__2_setid_field($where_arr,"tmk_adminid",$tmk_adminid);
        $ret_in_str=$this->t_origin_key->get_in_str_key_list($origin_ex,"s.origin");
        $where_arr[]= $ret_in_str;
        $this->where_arr_adminid_in_list($where_arr,"n.first_seller_adminid",$adminid_list);
        $sql = $this->gen_sql_new(
            "select $field_name as check_value ,count(*) all_count,sum(global_tq_called_flag <>0) tq_called_count,".
            "sum(global_tq_called_flag=0 and seller_student_status =0  ) no_call_count,".
            "sum(n.admin_revisiterid >0) assigned_count,sum( t.seller_student_status = 1) invalid_count,".
            "sum(t.seller_student_status =2) no_connected_count,count(distinct(n.phone)) as heavy_count,".
            "sum(t.seller_student_status =100 and  global_tq_called_flag =2 ) have_intention_a_count,".
            "sum(t.seller_student_status =101 and  global_tq_called_flag =2) have_intention_b_count,".
            "sum(t.seller_student_status =102 and  global_tq_called_flag =2)  have_intention_c_count,".
            "sum( tmk_student_status=3 ) tmk_assigned_count ,sum(global_tq_called_flag =2) as called_num,".
            "sum(global_tq_called_flag=0 ) tq_no_call_count,sum( global_tq_called_flag =1 ) tq_call_fail_count , ".
            "sum( global_tq_called_flag =1 and  n.sys_invaild_flag =1 ) tq_call_fail_invalid_count , ".
            "sum( global_tq_called_flag =2 and  n.sys_invaild_flag =1 ) tq_call_succ_invalid_count  ,".
            "avg( if(add_time<first_call_time , first_call_time-add_time,null) ) avg_first_time, ".
            "sum( global_tq_called_flag =2 and  n.sys_invaild_flag=0  ) tq_call_succ_valid_count,".
            "format(sum(global_tq_called_flag <>2)/count(*)*100,2) consumption_rate,".
            "format(sum(global_tq_called_flag =2)/sum(global_tq_called_flag <>0)*100,2) called_rate,".
            "format(sum(global_tq_called_flag =2 and n.sys_invaild_flag =0)/sum(global_tq_called_flag <>0)*100,2) effect_rate".
            " from %s n ".
            " left join %s s on s.userid = n.userid".
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
    //@desn:获取试听课信息[新版]
    //@param:$group by 字段
    //@param:$opt_date_str 检索时间字段
    //@param:$start_time,$end_time 开始时间，结束时间
    //@param:$origin 渠道名称
    //@param:$seller_groupid_ex 销售分组
    //@param:$adminid_list 负责人
    //@param:$tmk_adminid tmk负责人
    //@param:$distinct 区别标识 0：检索试听数据 1：计算去重试听成功个数
    public function get_lesson_list_new($field_name, $opt_date_str,$start_time,$end_time,$origin,$origin_ex,$seller_groupid_ex,$adminid_list=[],$tmk_adminid=-1,$distinct = 0){
        if($field_name == 'grade')
            $field_name = 'si.grade';
        elseif($field_name == 'origin')
            $field_name = 'tlsr.origin';
        $where_arr=[
            ["tlsr.origin like '%%%s%%' ",$origin,""],
            'tls.require_admin_type=2',
            ['li.lesson_type = %u',2],
            'si.is_test_user = 0'
            // ['tlsr.accept_flag = %u',1]
        ];
        $this->where_arr_add_time_range($where_arr,$opt_date_str,$start_time,$end_time);
        $this->where_arr_add__2_setid_field($where_arr,"ssn.tmk_adminid",$tmk_adminid);
        $ret_in_str=$this->t_origin_key->get_in_str_key_list($origin_ex,"tlsr.origin");
        $where_arr[]= $ret_in_str;
        $this->where_arr_adminid_in_list($where_arr,"ssn.first_seller_adminid",$adminid_list);


        $sql=$this->gen_sql_new(
            "select $field_name  as check_value , count(tlsr.require_id) as require_count, "
            ." count(if(tlsr.accept_flag = 1,tlsr.require_id,null)) as test_lesson_count, "
            ." count(distinct if(tlsr.accept_flag = 1,tls.userid,null)) as distinct_test_count, "
            ." sum(tlssl.success_flag in (0,1 )) as succ_test_lesson_count,"
            ." count(distinct if(tlssl.success_flag in (0,1 ),tls.userid,null)) as distinct_succ_count"
            ." from %s ssn "
            ." left join %s si on ssn.userid = si.userid "
            ." left join %s tls on tls.userid = ssn.userid"
            ." left join %s tlsr on tlsr.test_lesson_subject_id = tls.test_lesson_subject_id"
            ." left join %s tlssl on tlsr.current_lessonid = tlssl.lessonid"
            ." left join %s li on tlsr.current_lessonid=li.lessonid"
            ." where %s group by  check_value ",
            t_seller_student_new::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            t_test_lesson_subject::DB_TABLE_NAME,
            t_test_lesson_subject_require::DB_TABLE_NAME,
            t_test_lesson_subject_sub_list::DB_TABLE_NAME,
            t_lesson_info::DB_TABLE_NAME,
            $where_arr
        );

        return $this->main_get_list($sql);
    }
    //@desn:获取试听课信息[新版]
    //@param:$group by 字段
    //@param:$opt_date_str 检索时间字段
    //@param:$start_time,$end_time 开始时间，结束时间
    //@param:$origin 渠道名称
    //@param:$seller_groupid_ex 销售分组
    //@param:$adminid_list 负责人
    //@param:$tmk_adminid tmk负责人
    public function get_order_list_new($field_name, $opt_date_str,$start_time,$end_time,$origin,$origin_ex,$seller_groupid_ex,$adminid_list=[],$tmk_adminid=-1){
        if($field_name == 'grade')
            $field_name = 'si.grade';
        elseif($field_name == 'origin')
            $field_name = 'oi.origin';
        $where_arr=[
            ["oi.origin like '%%%s%%' ",$origin,""],
            'si.is_test_user = 0',
            ['oi.contract_type = %u',0],
            "oi.contract_status >0 ",
        ];
        $this->where_arr_add_time_range($where_arr,$opt_date_str,$start_time,$end_time);
        $this->where_arr_add__2_setid_field($where_arr,"ssn.tmk_adminid",$tmk_adminid);
        $ret_in_str=$this->t_origin_key->get_in_str_key_list($origin_ex,"oi.origin");
        $where_arr[]= $ret_in_str;
        $this->where_arr_adminid_in_list($where_arr,"ssn.first_seller_adminid",$adminid_list);
        $sql=$this->gen_sql_new(
            "select $field_name as check_value ,count(oi.orderid) as order_count,"
            ." round(sum(oi.price)/100) as order_all_money,count(distinct oi.userid) as user_count"
            ." from %s ssn "
            ." left join %s oi on ssn.userid = oi.userid "
            ." left join %s mi on oi.sys_operator = mi.account "
            ." left join %s si on oi.userid = si.userid "
            ." left join (select * from %s where require_admin_type = %u group by userid ) tls on ssn.userid = tls.userid "
            ." where %s group by  check_value ",
            t_seller_student_new::DB_TABLE_NAME,
            t_order_info::DB_TABLE_NAME,
            t_manager_info::DB_TABLE_NAME,
            t_student_info::DB_TABLE_NAME,
            t_test_lesson_subject::DB_TABLE_NAME,
            2,
            $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_item_list(){
        $where_arr = [];
        $this->where_arr_add_time_range($where_arr, 'o.add_time', $start_time=1512057600, $end_time=1514736000);
        $sql = $this->gen_sql_new(
            " select o.*, "
            ." n.phone "
            ." from %s o "
            ." left join %s n on n.userid=o.userid "
            ." where %s order by o.add_time "
            ,t_seller_student_origin::DB_TABLE_NAME
            ,t_seller_student_new::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_item_exist_list(){
        $where_arr = [
            'is_exist_count>0',
        ];
        $this->where_arr_add_time_range($where_arr, 'o.add_time', $start_time=1512057600, $end_time=1514736000);
        $sql = $this->gen_sql_new(
            " select o.*,"
            ." n.phone,n.orderid,"
            ." l.lessonid,l.lesson_type,l.lesson_start,l.lesson_end,l.lesson_del_flag,"
            ." l.confirm_flag,l.lesson_user_online_status,"
            ." tr.cur_require_adminid adminid, "
            ." o1.order_time,o1.price "
            ." from %s o "
            ." left join %s n on n.userid=o.userid "
            ." join %s l on l.userid=o.userid and l.lesson_type=2 "
            ." join %s tr on tr.current_lessonid=l.lessonid "
            ." left join %s o1 on o1.orderid=n.orderid "
            ." where %s order by o.add_time "
            ,self::DB_TABLE_NAME
            ,t_seller_student_new::DB_TABLE_NAME
            ,t_lesson_info::DB_TABLE_NAME
            ,t_test_lesson_subject_require::DB_TABLE_NAME
            ,t_order_info::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_all_list($start_time,$end_time){
        $where_arr = [];
        $this->where_arr_add_time_range($where_arr, 'add_time', $start_time, $end_time);
        $sql = $this->gen_sql_new(
            " select * "
            ." from %s "
            ." where %s order by add_time "
            ,self::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_is_exist_count_check($userid,$start_time,$end_time){
        $where_arr = [];
        $this->where_arr_add_int_field($where_arr, 'userid', $userid);
        $this->where_arr_add_time_range($where_arr, 'add_time', $start_time, $end_time);
        $sql = $this->gen_sql_new(
            " select count(*) count "
            ." from %s "
            ." where %s "
            ,self::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_item_count($userid,$start_time,$end_time){
        $where_arr = [];
        $this->where_arr_add_int_field($where_arr, 'userid', $userid);
        $this->where_arr_add_time_range($where_arr, 'add_time', $start_time, $end_time);
        $sql = $this->gen_sql_new(
            " select count(*) count "
            ." from %s "
            ." where %s "
            ,self::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_min_add_time($desc='asc'){
        $where_arr = [];
        $sql = $this->gen_sql_new(
            " select add_time "
            ." from %s "
            ." where %s order by add_time %s limit 1 "
            ,self::DB_TABLE_NAME
            ,$where_arr
            ,$desc
        );
        return $this->main_get_value($sql);
    }

    public function get_next_add_time($userid,$add_time){
        $where_arr = [
            "add_time>$add_time",
        ];
        $this->where_arr_add_int_field($where_arr, 'userid', $userid);
        $sql = $this->gen_sql_new(
            " select add_time "
            ." from %s "
            ." where %s order by add_time asc limit 1 "
            ,self::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_last_origin($userid,$add_time){
        $where_arr = [
            "add_time<$add_time",
        ];
        $this->where_arr_add_int_field($where_arr, 'userid', $userid);
        $sql = $this->gen_sql_new(
            " select origin "
            ." from %s "
            ." where %s order by add_time desc limit 1 "
            ,self::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_value($sql);
    }
}
