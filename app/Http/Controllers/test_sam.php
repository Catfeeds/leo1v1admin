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
        $start = date('Y-m-01', strtotime('-1 month'));
        $end   = date('Y-m-01');
        $end_time = strtotime($end);
        $start_time = strtotime($start);

        $success_through       = $this->t_teacher_info->get_success_through($start_time,$end_time);
        $success_apply         = $this->t_teacher_info->get_success_apply($start_time,$end_time);
        $video_apply           = $this->t_teacher_info->get_video_apply($start_time,$end_time);
        $lesson_apply          = $this->t_teacher_info->get_lesson_apply($start_time,$end_time);
        $ret = [];
        foreach($success_through as $key => $value){
            $ret[$value['phone']] = [
                "phone"           => $value['phone'],
                "teacherid"       => $value['teacherid'],
                "nick"            => $value["nick"],
                "reference"       => $value["reference"],
                "wx_openid"       => $value["wx_openid"],
                "success_through" => $value["sum"],
                "success_apply"   => 0,
                "total_apply"     => 0,
            ];
        }

        foreach($success_apply as $key => $value){
            if(isset($ret[$value['phone']])){
                $ret[$value['phone']]['success_apply'] = $value['sum'];
            }else{
                $ret[$value['phone']] = [
                    "phone"           => $value['phone'],
                    "teacherid"       => $value["teacherid"],
                    "nick"            => $value["nick"],
                    "reference"       => $value["reference"],
                    "wx_openid"       => $value["wx_openid"],
                    "success_through" => 0,
                    "success_apply"   => $value['sum'],
                    "total_apply"     => 0,
                ];
            }
        }

        foreach($video_apply as $key => $value){
            if(isset($ret[$value['phone']])){
                $ret[$value['phone']]['total_apply'] = $value['sum'];
            }else{
                $ret[$value['phone']] = [
                    "phone"           => $value['phone'],
                    "teacherid"       => $value['teacherid'],
                    "nick"            => $value['nick'],
                    "reference"       => $value['reference'],
                    "wx_openid"       => $value["wx_openid"],
                    "success_through" => 0,
                    "success_apply"   => 0,
                    "total_apply"     => $value['sum'],
                ];
            }
        }

        foreach($lesson_apply as $key => $value){
            if(isset($ret[$value['phone']])){
                $ret[$value['phone']]['total_apply'] += $value['sum'];
            }else{
                $ret[$value['phone']] = [
                    "phone"           => $value['phone'],
                    "teacherid"       => $value['teacherid'],
                    "nick"            => $value["nick"],
                    "reference"       => $value["reference"],
                    "wx_openid"       => $value["wx_openid"],
                    "success_through" => 0,
                    "success_apply"   => 0,
                    "total_apply"     => $value['sum'],
                ];
            }
        }


        dd($ret);
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
        $this->t_teacher_lecture_appointment_info->get_fulltime_teacher_enter(1501516800,1504195200);

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
    public function test(){
       $ass_list = $this->t_manager_info->get_adminid_list_by_account_role(1);
       foreach($ass_list as $k=>&$item){
            $ret = $this->t_manager_info->get_assistant_id($k);
            var_dump($ret);
       }

       $ret_info = $this->t_assistant_info->set_assign_lesson_count(61884,0,10);

    }
}
