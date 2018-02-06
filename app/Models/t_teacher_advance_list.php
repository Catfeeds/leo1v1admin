<?php
namespace App\Models;
use \App\Enums as E;
class t_teacher_advance_list extends \App\Models\Zgen\z_t_teacher_advance_list
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_info_by_teacher_money_type($start_time,$teacher_money_type,$teacherid=-1){
        $where_arr=[
            ["a.start_time = %u",$start_time,0],
            ["a.teacher_money_type=%u",$teacher_money_type,-1],
            ["a.teacherid = %u",$teacherid,-1]
        ];
        $sql = $this->gen_sql_new("select a.*,t.level real_level from %s a left join %s t on a.teacherid = t.teacherid where %s",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $where_arr);
        return $this->main_get_list($sql);

    }
    public function get_info_by_time($page_info,$start_time,$teacher_money_type,$teacherid,$accept_flag,$fulltime_flag=-1,$is_test_user=-1,$require_flag=1,$show_all=0,$withhold_require_flag=-1){
        $where_arr=[
            ["start_time = %u",$start_time,0],
            ["t.teacher_money_type=%u",$teacher_money_type,-1],
            ["a.teacherid = %u",$teacherid,-1],
            ["a.accept_flag = %u",$accept_flag,-1],
            ["t.is_test_user = %u",$is_test_user,-1],
            //  "m.account_role not in (4,9) or m.account_role is null"
        ];
        if($show_all==0){
            $where_arr[]="m.account_role not in (4,9) or m.account_role is null";
        }
        if($fulltime_flag==0){
            $where_arr[] = "(m.account_role <> 5 or m.account_role is null)";
        }elseif($fulltime_flag==1){
            $where_arr[] = "m.account_role =5";
        }
        if($require_flag==1){
            $where_arr[]= "a.require_time>0";
        }elseif($require_flag==2){
            $where_arr[]= "a.require_time=0";
        }
        if($withhold_require_flag==1){
            $where_arr[]= "a.withhold_require_time>0";
        }elseif($require_flag==2){
            $where_arr[]= "a.withhold_require_time=0";
        }

        /*elseif($fulltime_flag==2){
            $where_arr[] = "m.account_role =5 and fulltime_teacher_type=2";
            }*/
        $sql = $this->gen_sql_new("select a.*,t.realname,m.create_time become_member_time,t.level real_level,m.uid "
                                  ." from %s a left join %s t on a.teacherid = t.teacherid"
                                  ." left join %s m on t.phone = m.phone"
                                  ." where %s order by total_score desc",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list_by_page($sql,$page_info,500);
    }

    public function get_info_by_time_new($page_info,$teacher_money_type,$teacherid,$accept_flag,$fulltime_flag=-1,$start_time){
        $where_arr=[
            ["start_time = %u",$start_time,0],
            ["t.teacher_money_type=%u",$teacher_money_type,-1],
            ["a.teacherid = %u",$teacherid,-1],
            ["a.accept_flag = %u",$accept_flag,-1],
            'm.account_role in (4,9)',
            //  'm.del_flag=0'
        ];
        if($fulltime_flag==0){
            $where_arr[] = "(m.account_role <> 5 or m.account_role is null)";
        }elseif($fulltime_flag==1){
            $where_arr[] = "m.account_role =5 and fulltime_teacher_type=1";
        }elseif($fulltime_flag==2){
            $where_arr[] = "m.account_role =5 and fulltime_teacher_type=2";
        }
        $sql = $this->gen_sql_new("select a.*,"
                                  ."t.realname,"
                                  ."m.create_time become_member_time "
                                  ." from %s a "
                                  ."left join %s t on a.teacherid = t.teacherid"
                                  ." left join %s m on t.phone = m.phone"
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list_by_page($sql,$page_info);
    }

    public function get_advance_success_list($start_time){
        $where_arr=[
            ["a.start_time = %u",$start_time,0],
            "a.accept_flag =1",
            "(m.account_role is null or m.account_role not in (4,5,9))"
        ];
        $sql = $this->gen_sql_new("select a.* "
                                  ." from %s a left join %s t on a.teacherid = t.teacherid"
                                  ." left join %s m on t.phone = m.phone and m.del_flag=0"
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);

    }

    public function get_hand_add_list($start_time,$hand_flag,$fulltime_flag,$fulltime_teacher_type=-1){
        $where_arr=[
            ["ta.start_time = %u",$start_time,0],
            ["ta.hand_flag = %u",$hand_flag,0],
            ["m.fulltime_teacher_type=%u",$fulltime_teacher_type,-1]
        ];
        if($fulltime_flag==0){
            $where_arr[] = "(m.account_role <> 5 or m.account_role is null)";
        }elseif($fulltime_flag==1){
             $where_arr[] = "m.account_role =5";
        }
        $sql = $this->gen_sql_new("select ta.* "
                                  ." from %s ta left join %s t on ta.teacherid = t.teacherid"
                                  ." left join %s m on t.phone = m.phone"
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);


    }

    public function get_all_advance_teacher(){
        $sql = $this->gen_sql_new("select distinct a.teacherid "
                                  ."from %s a left join %s t on a.teacherid=t.teacherid"
                                  ." where t.is_test_user=0 and a.accept_flag=1",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME
        );
        $arr=$this->main_get_list($sql);
        $list=[];
        foreach($arr as $val){
            $list[]=$val["teacherid"];
        }
        return $list;
    }

    public function get_teacher_advance_require_detail_data($start_time,$teacher_money_type=6){
        $where_arr=[
            ["start_time = %u",$start_time,0],
            ["teacher_money_type = %u",$teacher_money_type,0],
        ];
        $sql = $this->gen_sql_new("select sum(if(require_time>0,1,0)) advance_require_num,"
                                  ." sum(if(require_time>0 and advance_first_trial_flag=0,1,0)) first_advance_no_deal_num,"
                                  ." sum(if(require_time>0 and advance_first_trial_flag=1,1,0)) first_advance_agree_num,"
                                  ." sum(if(require_time>0 and advance_first_trial_flag=2,1,0)) first_advance_refund_num,"
                                  ." sum(if(require_time>0 and accept_flag=0 and advance_first_trial_flag=1,1,0)) second_advance_no_deal_num,"
                                  ." sum(if(require_time>0 and accept_flag=1,1,0)) second_advance_agree_num,"
                                  ." sum(if(require_time>0 and accept_flag=2,1,0)) second_advance_refund_num,"
                                  ." sum(if(withhold_require_time,1,0)) withhold_require_num,"
                                  ." sum(if(withhold_require_time>0 and withhold_first_trial_flag=0,1,0)) first_withhold_no_deal_num,"
                                  ." sum(if(withhold_require_time>0 and withhold_first_trial_flag=1,1,0)) first_withhold_agree_num,"
                                  ." sum(if(withhold_require_time>0 and withhold_first_trial_flag=2,1,0)) first_withhold_refund_num,"
                                  ." sum(if(withhold_require_time>0 and withhold_final_trial_flag=0 and withhold_first_trial_flag=1,1,0))  second_withhold_no_deal_num,"
                                  ." sum(if(withhold_require_time>0 and withhold_final_trial_flag=1,1,0))  second_withhold_agree_num,"
                                  ." sum(if(withhold_require_time>0 and withhold_final_trial_flag=2,1,0))  second_withhold_refund_num"
                                  ." from %s"
                                  ." where %s  ",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_row($sql);
    }

    public function update_first_advance_deal_info_all($advance_first_trial_flag,$advance_first_trial_adminid,$advance_first_trial_time,$start_time,$teacher_money_type){
        if($advance_first_trial_flag==2){
            $str = " ,accept_flag=2,accept_time=".time();
        }else{
            $str="";
        }

        $sql = $this->gen_sql_new("update %s set advance_first_trial_flag=%u,advance_first_trial_adminid=%u,advance_first_trial_time=%u %s where require_time>0 and advance_first_trial_flag=0 and start_time=%u and teacher_money_type=%u",
                                  self::DB_TABLE_NAME,
                                  $advance_first_trial_flag,
                                  $advance_first_trial_adminid,
                                  $advance_first_trial_time,
                                  $str,
                                  $start_time,
                                  $teacher_money_type
        );
        return $this->main_update($sql);
    }
    public function update_second_advance_deal_info_all($accept_flag,$accept_adminid,$accept_time,$start_time,$teacher_money_type){

        $sql = $this->gen_sql_new("update %s set accept_flag=%u,accept_adminid=%u,accept_time=%u where require_time>0 and accept_flag=0 and start_time=%u and teacher_money_type=%u and advance_first_trial_flag=1",
                                  self::DB_TABLE_NAME,
                                  $accept_flag,
                                  $accept_adminid,
                                  $accept_time,
                                  $start_time,
                                  $teacher_money_type
        );
        return $this->main_update($sql);
    }
    public function update_first_withhold_deal_info_all($withhold_first_trial_flag,$withhold_first_trial_adminid,$withhold_first_trial_time,$start_time,$teacher_money_type){
        if($withhold_first_trial_flag==2){
            $str = " ,withhold_final_trial_flag=2,withhold_final_trial_time=".time();
        }else{
            $str="";
        }


        $sql = $this->gen_sql_new("update %s set withhold_first_trial_flag=%u,withhold_first_trial_adminid=%u,withhold_first_trial_time=%u %s where withhold_require_time>0 and withhold_first_trial_flag=0 and start_time=%u and teacher_money_type=%u",
                                  self::DB_TABLE_NAME,
                                  $withhold_first_trial_flag,
                                  $withhold_first_trial_adminid,
                                  $withhold_first_trial_time,
                                  $str,
                                  $start_time,
                                  $teacher_money_type
        );
        return $this->main_update($sql);
    }
    public function update_second_withhold_deal_info_all($withhold_final_trial_flag,$withhold_final_trial_adminid,$withhold_final_trial_time,$start_time,$teacher_money_type){

        $sql = $this->gen_sql_new("update %s set withhold_final_trial_flag=%u,withhold_final_trial_adminid=%u,withhold_final_trial_time=%u where withhold_require_time>0 and withhold_final_trial_flag=0 and start_time=%u and teacher_money_type=%u and withhold_first_trial_flag=1",
                                  self::DB_TABLE_NAME,
                                  $withhold_final_trial_flag,
                                  $withhold_final_trial_adminid,
                                  $withhold_final_trial_time,
                                  $start_time,
                                  $teacher_money_type
        );
        return $this->main_update($sql);
    }


    //获取晋升审批同意且未发送推送的老师名单
    public function get_all_accept_no_send_list($start_time,$teacher_money_type){
        $sql = $this->gen_sql_new("select a.level_after,t.wx_openid,t.email,t.teacher_type,a.teacherid,a.level_before  "
                                  ." from %s a left join %s t on a.teacherid = t.teacherid"
                                  ." where a.start_time = %u and a.teacher_money_type=%u and advance_wx_flag=0 and accept_flag=1",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $start_time,
                                  $teacher_money_type

        );
        return $this->main_get_list($sql);
    }

    //扣款审批通过且未处理老师工资的名单
    public function get_no_deal_withhold_info($start_time,$teacher_money_type){
        $sql = $this->gen_sql_new("select a.level_after,t.wx_openid,t.email,t.teacher_type,a.teacherid,a.level_before  "
                                  ." ,a.withhold_money"
                                  ." from %s a left join %s t on a.teacherid = t.teacherid"
                                  ." where a.start_time = %u and a.teacher_money_type=%u and withhold_wx_flag=0 and withhold_final_trial_flag=1",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $start_time,
                                  $teacher_money_type

        );
        return $this->main_get_list($sql);

    }



}
