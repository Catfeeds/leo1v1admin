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
       //every day
        /**  @var   $this \App\Console\thiss\thisController */
        $start_time = strtotime(date("Y-m-d",time()-100));
        $end_time=time();
        $subject=-1;
        $tea_subject="";
        $teacher_list_ex = $this->t_teacher_lecture_info->get_teacher_list_passed("",$start_time,$end_time,$subject,-1,-1,-1,$tea_subject);
        $teacher_arr_ex = $this->t_teacher_record_list->get_teacher_train_passed("",$start_time,$end_time,$subject,-1,-1,-1,$tea_subject);
        foreach($teacher_arr_ex as $k=>$val){
            if(!isset($teacher_list_ex[$k])){
                $teacher_list_ex[$k]=$k;
            }
        }
        $video_real =  $this->t_teacher_lecture_info->get_lecture_info_by_all(
            $subject,$start_time,$end_time,-1,-1,-1,$tea_subject,-2);

        $one_real = $this->t_teacher_record_list->get_train_teacher_interview_info_all(
            $subject,$start_time,$end_time,-1,-1,-1,$tea_subject,-2);
        @$video_real["all_count"] += $one_real["all_count"];

        $all_tea_ex = count($teacher_list_ex);

        //模拟试听总计
        $train_first_all = $this->t_teacher_record_list->get_trial_train_lesson_all($start_time,$end_time,1,$subject);
        if(empty($train_first_all["pass_num"])){
            $train_first_all["pass_num"]=0;
        }
        $train_second_all = $this->t_teacher_record_list->get_trial_train_lesson_all($start_time,$end_time,2,$subject);

        //第一次试听/第一次常规总计
        $test_first_all = $this->t_teacher_record_list->get_test_regular_lesson_all($start_time,$end_time,1,$subject);
        $regular_first_all = $this->t_teacher_record_list->get_test_regular_lesson_all($start_time,$end_time,3,$subject);
        $all_num = $video_real["all_count"]+$train_first_all["all_num"]+$test_first_all+$regular_first_all+$train_second_all["all_num"];

        //第五次试听
        $test_five_all = $this->t_teacher_record_list->get_test_regular_lesson_all($start_time,$end_time,2,$subject);
        //第五次常规
        $regular_five_all = $this->t_teacher_record_list->get_test_regular_lesson_all($start_time,$end_time,4,$subject);


        $arr=["name"=>"总计","real_num"=>$video_real["all_count"],"suc_count"=>$all_tea_ex,"train_first_all"=>$train_first_all["all_num"],"train_first_pass"=>$train_first_all["pass_num"],"train_second_all"=>$train_second_all["all_num"],"test_first"=>$test_first_all,"regular_first"=>$regular_first_all,"all_num"=>$all_num];

        //$admin_list = [349];
        //$admin_list = [72,349,448,329];
        $admin_list = 944;
        dd($arr);
        foreach($admin_list as $yy){
            $this->t_manager_info->send_wx_todo_msg_by_adminid ($yy,"质检日报","质监月项目进度汇总","\n面试数通过人数:".$all_tea_ex."/".$video_real["all_count"]."\n模拟试听审核数(一审):".$train_first_all["pass_num"]."/".$train_first_all["all_num"]."\n模拟试听审核数(二审):".$train_second_all["all_num"]."\n第一次试听审核:".$test_first_all."\n第一次常规审核:".$regular_first_all,"http://admin.yb1v1.com/main_page/quality_control?date_type_config=undefined&date_type=null&opt_date_type=0&start_time=".$date."&end_time=".$date."&subject=-1 ");
        }
    }



     public function  tt(){
        $this->switch_tongji_database();


        $start_time = strtotime(date("Y-m-d",time()-100));
        $end_time=time();
        $subject = $this->get_in_int_val("subject",-1);
        $date = date("Y-m-d",time()-100);
        $account_role = 9;
        $kpi_flag = 1;
        $teacher_info = $this->t_manager_info->get_adminid_list_by_account_role($account_role);//return->uid,account,nick,name
        foreach($teacher_info as $kk=>$vv){
            if(in_array($kk,[992,891,486,871,1058,1080])){
                unset($teacher_info[$kk]);
            }
        }
        // $teacher_info[349]= ["uid"=>349,"account"=>"jack","name"=>"jack"];
        $tea_subject = "";

        //面试人数
        $real_info = $this->t_teacher_lecture_info->get_lecture_info_by_time_new(
            $subject,$start_time,$end_time,-1,-1,-1,$tea_subject,-2);
        $real_arr = $this->t_teacher_record_list->get_train_teacher_interview_info(
            $subject,$start_time,$end_time,-1,-1,-1,$tea_subject,-2);
        foreach($real_arr["list"] as $p=>$pp){
            if(isset($real_info["list"][$p])){
                $real_info["list"][$p]["all_count"] += $pp["all_count"];
                $real_info["list"][$p]["all_num"] += $pp["all_num"];
            }else{
                $real_info["list"][$p]= $pp;
            }

        }
        //模拟试听审核
        $train_first = $this->t_teacher_record_list->get_trial_train_lesson_first($start_time,$end_time,1,$subject);
        $train_second = $this->t_teacher_record_list->get_trial_train_lesson_first($start_time,$end_time,2,$subject);

        //第一次试听/第一次常规
        $test_first = $this->t_teacher_record_list->get_test_regular_lesson_first($start_time,$end_time,1,$subject);
        $test_first_per = $this->t_teacher_record_list->get_test_regular_lesson_first_per($start_time,$end_time,1,$subject);


         //第5次试听
        $test_five = $this->t_teacher_record_list->get_test_regular_lesson_first($start_time,$end_time,2,$subject);

        $test_five_per = $this->t_teacher_record_list->get_test_regular_lesson_first_per($start_time,$end_time,2,$subject);
        //第一次常规
        //dd($test_first_per);
        $regular_first = $this->t_teacher_record_list->get_test_regular_lesson_first($start_time,$end_time,3,$subject);
        $regular_first_per = $this->t_teacher_record_list->get_test_regular_lesson_first_per($start_time,$end_time,3,$subject);

        //第5次常规
        $regular_five = $this->t_teacher_record_list->get_test_regular_lesson_first($start_time,$end_time,4,$subject);

        $regular_five_per = $this->t_teacher_record_list->get_test_regular_lesson_first_per($start_time,$end_time,4,$subject);


        $all_count=0;
        $total_test_first_per = 0;
        $total_test_five_per = 0;
        $total_test_first_num = 0;
        $total_test_five_num = 0;
        $total_regular_first_per = 0;
        $total_regular_first_num = 0;
        $total_regular_five_per = 0;
        $total_regular_five_num = 0;
        $real_num = $suc_count = $train_first_all= $train_first_pass = $train_second_all = $test_first_all = $regular_first_all=$test_five_all=$regular_five_all=0;
        foreach($teacher_info as &$item){
            $item["real_num"] = isset($real_info["list"][$item["account"]])?$real_info["list"][$item["account"]]["all_count"]:0;
            $account = $item["account"];
            $teacher_list = $this->t_teacher_lecture_info->get_teacher_list_passed($account,$start_time,$end_time,$subject,-1,-1,-1,$tea_subject);
            $teacher_arr = $this->t_teacher_record_list->get_teacher_train_passed($account,$start_time,$end_time,$subject,-1,-1,-1,$tea_subject);
            foreach($teacher_arr as $k=>$val){
                if(!isset($teacher_list[$k])){
                    $teacher_list[$k]=$k;
                }
            }

            $item["suc_count"] = count($teacher_list);
            $item["train_first_all"] = isset($train_first[$account])?$train_first[$account]["all_num"]:0;
            $item["train_first_pass"] = isset($train_first[$account])?$train_first[$account]["pass_num"]:0;
            $item["train_second_all"] = isset($train_second[$account])?$train_second[$account]["all_num"]:0;

            $item["test_first"] = isset($test_first[$account])?$test_first[$account]["all_num"]:0;
            $item["test_first_per"] = isset($test_first_per[$account])?round($test_first_per[$account]["all_time"]/$test_first_per[$account]["all_num"]):0;
            if($item["test_first_per"] > 0){
                $total_test_first_per += $test_first_per[$account]["all_time"];
                $total_test_first_num += $test_first_per[$account]["all_num"];
            }

            $item["test_first_per_str"] = "";
            if($item["test_first_per"]){
                if($item["test_first_per"]/60>0){
                    $item["test_first_per_str"] = round($item["test_first_per"]/60)."分".($item["test_first_per"]%60)."秒";
                }else{
                    $item["test_first_per_str"] .= "秒";
                }
            }


            $item["test_five"] = isset($test_five[$account])?$test_five[$account]["all_num"]:0;
            $item["test_five_per"] = isset($test_five_per[$account])?round($test_five_per[$account]["all_time"]/$test_five_per[$account]["all_num"]):0;
            if($item["test_five_per"] > 0){
                $total_test_five_per += $test_five_per[$account]["all_time"];
                $total_test_five_num += $test_five_per[$account]["all_num"];
            }

            $item["test_five_per_str"] = "";
            if($item["test_five_per"]){
                if($item["test_five_per"]/60>0){
                    $item["test_five_per_str"] = round($item["test_five_per"]/60)."分".($item["test_five_per"]%60)."秒";
                }else{
                    $item["test_five_per_str"] .= "秒";
                }
            }


            $item["regular_first"] = isset($regular_first[$account])?$regular_first[$account]["all_num"]:0;
            $item["regular_first_per"] = isset($regular_first_per[$account])?round($regular_first_per[$account]["all_time"]/$regular_first_per[$account]["all_num"]):0;
            if($item["regular_first_per"]){
                $total_regular_first_per += $regular_first_per[$account]["all_time"];
                $total_regular_first_num += $regular_first_per[$account]["all_num"];
            }
            $item["regular_first_per_str"] = "";
            if($item["regular_first_per"]){
                if($item["regular_first_per"]/60>0){
                    $item["regular_first_per_str"] = round($item["regular_first_per"]/60)."分".($item["regular_first_per"]%60)."秒";
                }else{
                    $item["regular_first_per_str"] .= "秒";
                }
            }

            $item["regular_five"] = isset($regular_five[$account])?$regular_five[$account]["all_num"]:0;
            $item["regular_five_per"] = isset($regular_five_per[$account])?round($regular_five_per[$account]["all_time"]/$regular_five_per[$account]["all_num"]):0;
            if($item["regular_five_per"]){
                $total_regular_five_per += $regular_five_per[$account]["all_time"];
                $total_regular_five_num += $regular_five_per[$account]["all_num"];
            }
            $item["regular_five_per_str"] = "";
            if($item["regular_five_per"]){
                if($item["regular_five_per"]/60>0){
                    $item["regular_five_per_str"] = round($item["regular_five_per"]/60)."分".($item["regular_five_per"]%60)."秒";
                }else{
                    $item["regular_five_per_str"] .= "秒";
                }
            }



            $item["all_num"] = $item["real_num"]+ $item["train_first_all"]+$item["train_second_all"]+ $item["test_first"]+ $item["regular_first"]+$item["test_five"]+$item["regular_five"];
            if($item["uid"]==481){
                $item["all_num"] +=7;
            }
            $item["all_target_num"] = 250;
            if(in_array($item["uid"],[486,754,1011,329])){
                $item["all_target_num"]=150;
            }elseif(in_array($item["uid"],[913,923,892])){
                $item["all_target_num"]=400;
            }elseif(in_array($item["uid"],[478])){
                 $item["all_target_num"]=50;
            }elseif(in_array($item["uid"],[895,683])){
                 $item["all_target_num"]=100;
            }

            $all_count +=$item["all_target_num"];
            $item["per"] = round($item["all_num"]/$item["all_target_num"]*100,2);
            if($kpi_flag==1){
                $real_num += $item["real_num"];
                $suc_count += $item["suc_count"];
                $train_first_all += $item["train_first_all"];
                $train_first_pass += $item["train_first_pass"];
                $train_second_all += $item["train_second_all"];
                $test_first_all += $item["test_first"];
                $test_five_all += $item["test_five"];
                $regular_first_all += $item["regular_first"];
                $regular_five_all += $item["regular_five"];
            }
        }
        if($kpi_flag==1){
            $arr=[];
            $arr=["name"=>"总计",
            ];
            $arr["real_num"] = $real_num;
            $arr["suc_count"] = $suc_count;
            $arr["train_first_all"] = $train_first_all;
            $arr["train_first_pass"] = $train_first_pass;
            $arr["train_second_all"] = $train_second_all;
            $arr["test_first"] = $test_first_all;
            $arr["test_five"] = $test_five_all;
            $arr["regular_first"] = $regular_first_all;
            $arr["regular_five"] = $regular_five_all;
            $arr["all_num"] = $real_num+$train_first_all+$test_first_all+$regular_first_all+$train_second_all+$test_five_all+$regular_five_all;
        }

        $num = count($teacher_info);
        // $all_count = ($num-2)*250+300;
        if($all_count){
            $arr["per"] = round($arr["all_num"]/$all_count*100,2);
        }else{
            $arr["per"] = 0;
        }

        $arr["all_target_num"] = $all_count;
        array_unshift($teacher_info,$arr);
        $admin_list = [944];
        foreach($admin_list as $yy){
          foreach ($teacher_info as $key => $value) {
             $this->t_manager_info->send_wx_todo_msg_by_adminid (
                $yy,
                "质检日报",
                "质监月项目进度汇总",
                "\n面试数通过人数:".
                $value['real_num']."/".
                $value['suc_count'].
                "\n模拟试听审核数(一审):".$value['train_first_all']."/".$value['train_first_pass'].
                "\n模拟试听审核数(二审):".$value['train_second_all'].
                "\n第一次试听审核:".$value['test_first'].
                "\n第一次常规审核:".$value['regular_first'],
                "\n总体完成率:".$value['per'].'%',
                "http://admin.yb1v1.com/main_page/quality_control?date_type_config=undefined&date_type=null&opt_date_type=0&start_time=".$date."&end_time=".$date."&subject=-1 ");
            }
        }


     
    }        
}