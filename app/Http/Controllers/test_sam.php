<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Cookie;
use App\Http\Controllers\Controller;
use \App\Libs;
use \App\Config as C;
use \App\Enums as E;
use App\Helper\Utils;

class test_sam  extends Controller
{
    use CacheNick;
    use TeaPower;
    public function lesson_list()
    {
        list($start_time,$end_time)=$this->get_in_date_range(0,0,0,null,1);
        $acc         = $this->get_account();
        $adminid     = $this->get_account_id();
        $right_list  = $this->get_tea_subject_and_right_by_adminid($adminid);
        $tea_subject = $right_list["tea_subject"];
        $tea_right   = $right_list["tea_right"];
        $qz_flag     = $right_list["qz_flag"];
        if($adminid==486 || $adminid==478){
             $tea_subject= "";
        }

        $account_info = $this->t_manager_info->get_teacher_info_by_adminid($adminid);
        if($account_info["teacherid"]>0){
            $is_tea=1;
        }else{
            $is_tea = 0;
        }

        $studentid       = $this->get_in_int_val('studentid', -1);
        $teacherid       = $this->get_in_int_val('teacherid', -1);
        $confirm_flag    = $this->get_in_enum_list(E\Econfirm_flag::class);
        $seller_adminid  = $this->get_in_int_val("seller_adminid",-1);
        $lesson_status   = $this->get_in_int_val("lesson_status",-1);
        $assistantid     = $this->get_in_assistantid(-1);
        $grade           = $this->get_in_enum_list(E\Egrade::class);
        $test_seller_id  = $this->get_in_int_val("test_seller_id",-1 );
        $has_performance = $this->get_in_int_val("has_performance",-1 );
        $fulltime_flag = $this->get_in_int_val("fulltime_flag",-1 );
        $lesson_user_online_status   = $this->get_in_e_set_boolean(-1,"lesson_user_online_status");

        $lesson_type_default = Cookie::get("lesson_type")==null?-1: Cookie::get("lesson_type") ;
        $subject_default     = Cookie::get("subject")==null?-1: Cookie::get("subject");

        $lesson_type               = $this->get_in_int_val('lesson_type', $lesson_type_default);
        $subject                   = $this->get_in_int_val('subject', $subject_default);
        $lesson_count              = $this->get_in_int_val('lesson_count', -1 );
        $lesson_cancel_reason_type = $this->get_in_int_val('lesson_cancel_reason_type', -1 );
        $lesson_del_flag           = $this->get_in_int_val('lesson_del_flag', -1 );
        $has_video_flag            = $this->get_in_e_boolean(-1,"has_video_flag");

        $is_with_test_user = $this->get_in_int_val('is_with_test_user', 0);
        $lessonid          = $this->get_in_lessonid(-1);
        $origin            = $this->get_in_str_val("origin");
        $page_num          = $this->get_in_page_num();
        if ($lessonid ==0) {
            $lessonid= $this->t_lesson_info->get_lessonid_by_lesson_str( $this->get_in_str_val("lessonid"));
        }

        $ret_info = $this->t_lesson_info->get_lesson_condition_list_ex(
            $start_time,$end_time, $teacherid,$studentid, $lessonid ,
            $lesson_type ,$subject,$is_with_test_user,$seller_adminid,$page_num,
            $confirm_flag,$assistantid,$lesson_status,$test_seller_id,$has_performance,
            $origin,$grade,$lesson_count,$lesson_cancel_reason_type,$tea_subject,
            $has_video_flag, $lesson_user_online_status,$fulltime_flag,$lesson_del_flag
        );
        //     dd($ret_info);

        $lesson_list       = array();
        $lesson_status_cfg = array( 0 => "未上", 1 => "进行",2 => "结束",3=>"终结");
        $lesson_cw_cfg     = array( 0 => "未传", 1=> "已传" );
        $lesson_hw_cfg     = array( 0 =>"未布置", 1=>"已布置",2=>"学生已做", 3=>"老师已批" ,4 => "教研已看",5=>"助教已看");
        $lesson_quiz_cfg   = array( 0 => "未传", 1=> "已传" );
        $lesson_comment    = array( 1=>"很差", 2=>"差", 3=>"一般", 4 => "好",5=>"很好");

        $lesson_count_all      = 0;
        $lesson_count_fail_all = 0;
        $lesson_deduct_key     = E\Elesson_deduct::$v2s_map;
        $lesson_deduct_info    = E\Elesson_deduct::$desc_map;
        $price_all             = 0;
        $start_index           = \App\Helper\Utils::get_start_index_from_ret_info($ret_info);
        foreach( $ret_info['list'] as $i=> &$item){
            $item["number"] = $start_index+$i;
            $stu_id         = $item["stu_id"];

            \App\Helper\Utils::unixtime2date_for_item($item,"lesson_cancel_reason_next_lesson_time");
            $new_test_listen=   "不是";
            if ($item["lesson_type"] == E\Econtract_type::V_2 ) {
                $has_order       = false;
                $new_test_listen = $has_order?"不是":"是";
            }

            if($item['tea_rate_time']==0){
                $item['performance_status'] = 0;
                $item['performance']        = '未评价';
            }else{
                $item['performance_status'] = 1;
                $item['performance']        = '已评价';
                $item['tea_has_update']     = $item['stu_performance']==''?1:0;
            }

            $item["new_test_listen"] = $new_test_listen;
            $item['lesson_time' ]    = \App\Helper\Utils::fmt_lesson_time($item["lesson_start"] ,$item["lesson_end"]);

            E\Elesson_cancel_reason_type::set_item_value_str($item);
            \App\Helper\Common::set_item_enum_flow_status($item ,"require_lesson_success_flow_status" );

            $item['audio']                  = "http://admin_yb1v1.com/common_ex/get_qiniu_file?file=".\App\Helper\Common::encode_str($item['audio']);    //audio加密
            $item['lesson_end_str' ]        = date('Y-m-d H:i',$item['lesson_end']);
            $item['real_begin_time_str' ]   = date('Y-m-d H:i',$item['real_begin_time']);
            $item['lesson_status_str']      = $lesson_status_cfg[ $item['lesson_status' ] ];
            $item['lesson_vedio_flag']      = $item['audio'] && $item["draw"] ? 1:0   ;
            $item['lesson_vedio_flag_str']  = E\Eboolean::get_desc( $item['lesson_vedio_flag'] );
            $item['stu_cw_status_str']      = $lesson_cw_cfg[ $item['stu_cw_status' ] ];
            $item['tea_cw_status_str']      = $lesson_cw_cfg[ $item['tea_cw_status' ] ];
            $item['work_status_str']        = $lesson_hw_cfg[ $item['work_status' ]?:0 ];
            $item['lesson_quiz_status_str'] = $this->get_lesson_quiz_cfg($item['lesson_quiz_status'], $item['lesson_type']);
            $item['is_complained_str']      = E\Eboolean::get_desc ($item['is_complained' ]) ;
            $item['homework_url']           = $this->get_work_url($item);
            $item['lesson_type_str']        = E\Econtract_type::get_desc($item["lesson_type"]) ;
            $item['level']                  = E\Elevel::get_desc($item["level"]) ;
            $item['teacher_score']        = sprintf("%.2f",($item["teacher_effect"]+$item["teacher_quality"]+$item["teacher_interact"])/3 );
            $item["tea_nick"]             = $this->cache_get_teacher_nick($item["teacherid"]);
            $item["require_admin_nick"]   = $this->cache_get_account_nick($item["require_adminid"]);
            $item["teacher_comment"]      = trim($item["teacher_comment"]);
            $item['teacher_effect_str']   = @$lesson_comment[ $item['teacher_effect'] ];
            $item['teacher_quality_str']  = @$lesson_comment[ $item['teacher_quality'] ];
            $item['teacher_interact_str'] = @$lesson_comment[ $item['teacher_interact'] ];
            $item['stu_stability_str']    = @$lesson_comment[ $item['stu_stability'] ];
            $item['lesson_diff']          = $item['lesson_end'] - $item['lesson_start'];
            $item["lesson_user_online_status_str"] = \App\Helper\Common::get_set_boolean_color_str(
                $item["lesson_user_online_status"]
            );
            $item["room_name"]=\App\Helper\Utils::gen_roomid_name($item["lesson_type"], $item["courseid"], $item["lesson_num"] );

            if ($item["test_lesson_origin"]) {
                $item["origin"]= $item["test_lesson_origin"];
            }

            \App\Helper\Utils::unixtime2date_for_item($item,"confirm_time");
            $item["confirm_admin_nick"] = $this->cache_get_account_nick($item["confirm_adminid"]);
            E\Econfirm_flag::set_item_value_str($item);
            E\Egrade::set_item_value_str($item);
            E\Esubject::set_item_value_str($item);
            if(!empty($item["ass_test_lesson_type"])){
                E\Eass_test_lesson_type::set_item_value_str($item);
            }else{
                $item["ass_test_lesson_type_str"]="";
            }

            $item["lesson_user_online_status_str"] = \App\Helper\Common::get_set_boolean_color_str(
                $item["lesson_user_online_status"]
            );
            if ($item["lesson_status"] == 2) {
                if ($item["confirm_flag"] ==2 || $item["confirm_flag"] ==3 ) {
                    $lesson_count_fail_all+= $item["lesson_count"];
                }else {
                    $lesson_count_all+= $item["lesson_count"];
                }
            }

            $item['lesson_deduct']="";
            foreach($lesson_deduct_key as $deduct_key => $deduct_val){
                if($item[$deduct_val]>0){
                    $item['lesson_deduct'] .= $lesson_deduct_info[$deduct_key]."|";
                }
            }
            $item['lesson_deduct']=trim($item['lesson_deduct'],"|");
            E\Eboolean::set_item_value_str($item,"lesson_del_flag");
        }

        $seller_list      = $this->t_admin_group->get_admin_list_by_gorupid(E\Eaccount_role::V_1 );
        $adminid          = $this->get_account_id();
        $self_groupid     = $this->t_admin_group_user->get_groupid_by_adminid(2 , $adminid );
        $get_self_adminid = $this->t_admin_group_name->get_master_adminid($self_groupid);
        if($adminid == $get_self_adminid){
            $is_group_leader_flag = 1;
        }else{
            $is_group_leader_flag = 0;
        }

        
        $ret_str  = $this->pageView(__METHOD__,$ret_info, [
            "lesson_count_all"      => $lesson_count_all,
            "lesson_count_fail_all" => $lesson_count_fail_all,
            "seller_list"           => $seller_list,
            "self_groupid"          => $self_groupid,
            "is_group_leader_flag"  => $is_group_leader_flag,
            "is_tea"                => $is_tea,
            "adminid"               => $adminid,
            "acc"                   => $acc,
        ]);
        $response = new \Illuminate\Http\Response($ret_str);

        return  $response->withCookie(cookie('lesson_type',$lesson_type , 45000))
          ->withCookie(cookie('subject', $subject, 45000));
    }
    
    private function get_lesson_quiz_cfg($lesson_quiz_status, $lesson_type)
    {
        if ($lesson_type < 3001) {
            return '无需上传';
        }

        if ($lesson_quiz_status == 0) {
            return '未上传';
        } else {
            return '已上传';
        }
    }

    private function get_work_url($work_value)
    {
        switch($work_value['work_status']) {
        case 1:
            return $work_value['issue_url'];
        case 2:
            return $work_value['finish_url'];
        case 3:
            return $work_value['check_url'];
        case 4:
            return $work_value['tea_research_url'];
        case 5:
            return $work_value['ass_research_url'];
        default:
            return '';
        }
    }

    public function tt(){
        // $this->t_manager_info->field_update_list($uid,$set_field_arr);
        $this->t_manager_info->row_insert($arr);
    }

    public function manager_list()
    {
        $this->get_in_int_val("assign_groupid", -1);
        $this->get_in_int_val("assign_account_role", -1);

        $creater_adminid = $this->get_in_int_val("creater_adminid", -1);

        $adminid           = $this->get_in_adminid(-1);
        $uid               = $this->get_in_int_val('uid',0);
        $user_info         = trim($this->get_in_str_val('user_info', ''));
        $has_question_user = $this->get_in_int_val('has_question_user', 0);
        $del_flag          = $this->get_in_int_val('del_flag', 0);
        $page_info         = $this->get_in_page_info();
        $account_role      = $this->get_in_int_val('account_role', -1);
        $cardid            = $this->get_in_int_val('cardid', -1);
        $day_new_user_flag = $this->get_in_boolean_val("day_new_user_flag", -1);
        $tquin             = $this->get_in_int_val('tquin', -1);
        $seller_level      = $this->get_in_el_seller_level();
        if(!$cardid){
            $cardid = -1;
        }

        $ret_info = $this->t_manager_info->get_all_manager(
            $page_info,
            $uid,
            $user_info,
            $has_question_user,
            $creater_adminid,
            $account_role,
            $del_flag,
            $cardid,
            $tquin,
            $day_new_user_flag,
            $seller_level,
            $adminid);
        /* "select
                 call_phone_type,   // 拨打电话类型
                 call_phone_passwd, //拨打电话密码
                 fingerprint1 ,     //指纹1
                 ytx_phone,         //云通讯电话
                 wx_id,             //微信号
                 up_adminid,        //上级ID
                 day_new_user_flag, //是否每天可获得新例子
                 account_role,      //角色
                 creater_adminid,   //创建者ID
                 t1.uid,            //用户ID
                 t1.del_flag,       //删除
                 t1.account,        //用户账户account用户名
                 t1.seller_level,   //咨询师等级
                 name,              //真实姓名
                 nickname,          //null->2
                 email,             //电子邮箱
                 phone,             //手机号码
                 password,          //密码->2
                 permission,        //---->1
                 tquin,             //TQ adminid 
                 wx_openid ,
                 cardid,
                 become_full_member_flag,
                 main_department
              from
                 db_weiyi_admin.t_manager_info t1
              left join
                 db_weiyi_admin.t_admin_users t2
              on
                 t1.uid=t2.id
              left join
                 db_weiyi_admin.t_wx_user_info t_wx
              on
                 t1.wx_openid =t_wx.openid
              where
                 t1.account not like 'c\_%' and
                 t1.account not like 'q\_%' and
                 true and t1.del_flag=0
              order by
                 t1.uid desc"

         */
        //dd($ret_info);
        $group_list = $this->t_authority_group->get_auth_groups();
        $group_map = [];
        foreach($group_list as $group_item){
            $group_map[$group_item['groupid']] = $group_item['group_name'];
        }

        foreach($ret_info['list'] as &$item){
            $arr = explode(',', $item['permission']);
            $arr_zh_yi = '';
            foreach($arr as $arr_eve){
                $int_eve = (int)$arr_eve;
                $arr_zh_yi .= @$group_map[$int_eve].",";
            }
            $init_passwd = md5(md5($item['account'])."#aaron");
            $item['reset_passwd_flag'] = ($init_passwd != $item['password']) ? "是":"<font color=red>否</font>";
            $item['permission'] = $arr_zh_yi;

            $this->cache_set_item_account_nick($item, "creater_adminid", "creater_admin_nick");
            $this->cache_set_item_account_nick($item, "up_adminid", "up_admin_nick");
            E\Eaccount_role::set_item_value_str($item);
            E\Eseller_level::set_item_value_str($item);
            E\Edepartment::set_item_value_str($item);
            E\Eboolean::set_item_value_str($item, "become_full_member_flag");

            $item['del_flag_str'] = ($item['del_flag'] == 0) ? "在职" : "离职";

            if($item['seller_level_str'] == -1){
                $item["seller_level_str"] = "未设置";
            }

            E\Eboolean::set_item_value_simple_str($item, "day_new_user_flag");


        }
        return $this->pageView(__METHOD__, $ret_info);
    }
}