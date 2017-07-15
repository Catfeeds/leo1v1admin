<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

class main_page extends Controller
{

    var $switch_tongji_database_flag = true;
    use CacheNick;
    function __construct()  {
        parent::__construct();
    }

    public  function admin() {
        $adminid = $this->get_account_id();
        list($start_time,$end_time)=$this->get_in_date_range_day(0);

        //得到短信信息
        $sms_list=$this->t_sms_msg->tongji_type_get_list($start_time,$end_time);
        $lesson_info=$this->t_lesson_info->tongji_count($start_time, $end_time);
        $record_server_list=$this->t_lesson_info->tongji_record_server_info($start_time, $end_time);

        foreach ($sms_list as &$item)  {
            E\Esms_type::set_item_value_str($item, "type");
        }

        $sys_info=[
            ["课时审查时间节点",\App\Helper\Config::get_lesson_confirm_start_time()],
        ];


        return $this->pageView(__METHOD__ ,null, [
            "sms_list" => $sms_list,
            "sys_info" => $sys_info,
            "lesson_info" => $lesson_info,
            "record_server_list" => $record_server_list,
        ]);
    }


    public function seller()
    {
        list($start_time,$end_time)= $this->get_in_date_range_month(date("Y-m-01" )  );
        $adminid=$this->get_account_id();
        /* if($adminid==349){
            $adminid=397;
            }*/
        $self_groupid = $this->t_admin_group_user->get_groupid_by_adminid(2 , $adminid );
        $get_self_adminid = $this->t_admin_group_name->get_master_adminid($self_groupid);
        if($adminid == $get_self_adminid){
            $is_group_leader_flag = 1;
        }else{
            $is_group_leader_flag = 0;
        }
        $self_info= $this->t_order_info->get_1v1_order_seller($this->get_account(),
            $start_time,$end_time );

        $ret_info= $this->t_order_info->get_1v1_order_seller_list($start_time,$end_time);

        $groupid =$this->get_in_int_val("groupid",-1);

        if($groupid == -1) {
            $groupid=$this->t_admin_group_user->get_groupid_by_adminid(2 , $adminid );
            $group_name="组内";
        }else{
            $group_name=$this->t_admin_group_name->get_group_name($groupid);
        }

        $group_self_list = $this->t_order_info->get_1v1_order_seller_list_group_self($start_time,$end_time,$groupid);
        $group_list      = $this->t_order_info->get_1v1_order_seller_list_group($start_time,$end_time);

        foreach ($ret_info["list"] as $key=> &$item) {
            $item["index"]=$key+1;
            $item["all_price"] =sprintf("%.2f", $item["all_price"]  );

        }
        $self_top_info =$this->t_tongji_seller_top_info->get_admin_top_list( $adminid,  $start_time );
        $this->get_in_int_val("self_groupid",$self_groupid);
        $this->get_in_int_val("is_group_leader_flag",$is_group_leader_flag);

        //得到预期的试听成功数
        //date("y-m-01");

        // 本月签单率
        list($start_time,$end_time)=$this->get_in_date_range_month(date("Y-m-01") );
        $tongji_type=$this->get_in_int_val("tongji_type",5, E\Etongji_type::class); //5 代表查询本月签单率
        $ret_info_num = $this->t_tongji_seller_top_info->get_list_top($tongji_type,$start_time);
        foreach($ret_info_num as &$item) {
            $this->cache_set_item_account_nick($item);
            $item['value'] = $item['value'].'%';
        }


        $day= strtotime(date("Y-m-d") );
        $w=  date("w") ;
        if ($w==0)  $w=7;

        if (in_array($w,[5,6,7])) {
            $hw_start_time=$day-($w-5)*86400;
            $hw_end_time= $hw_start_time + 3*86400;
        }else{
            $hw_start_time=$day-($w-1)*86400;
            $hw_end_time= $hw_start_time + 4*86400;
        }



        $half_week_info= $this->t_order_info->get_1v1_order_seller_list($hw_start_time,$hw_end_time, [-1],"limit 5" );


        foreach ($half_week_info["list"] as $key=> &$item) {
            $item["all_price"] =sprintf("%.2f", $item["all_price"]  );
        }

        $self_top_info =$this->t_tongji_seller_top_info->get_admin_top_list( $adminid,  $start_time );
        // dd($self_top_info);
        return $this->pageView(__METHOD__, $ret_info, [
            "ret_info_num"           => $ret_info_num,
            "group_list"             => $group_list,
            "group_self_list"        => $group_self_list ,
            "group_name"             => $group_name,
            "self_info"              => $self_info,
            "half_week_info"         => $half_week_info["list"],
            "self_top_info"          => $self_top_info,
            "self_groupid"           => $self_groupid,
            "is_group_leader_flag"   => $is_group_leader_flag,
            "test_lesson_need_count" => $this->t_seller_month_money_target->get_test_lesson_count($adminid,date("Y-m-01") ),
        ]);
    }
    public  function assistant() {
        $end_time = strtotime( date("Y-m-d") );
        $end_time_date = date("Y-m-d") ;
        $start_time   = $end_time-100*86400;
        $start_time_date  = date("Y-m-d",$start_time);
        $assistantid=$this->t_assistant_info->get_assistantid( $this->get_account() );
        if($assistantid==0){
            $assistantid = -1;
        }
        $ret_info = $this->t_gift_consign->get_consign_status_list($start_time,$end_time,$assistantid);

        //
        list($start_time,$end_time) = $this->get_in_date_range( date("Y-m-01",time(NULL)) ,0 );
        $this->t_lesson_info->switch_tongji_database();
        $lesson_count_list=$this->t_lesson_info->get_confirm_lesson_list($start_time,$end_time);
        //

        $lesson_all=0;$user_all=0;
        foreach($lesson_count_list['list'] as &$item ){
            $item["assistant_nick"] =$this->cache_get_assistant_nick($item["assistantid"]);
            $lesson_all += $item["lesson_count"];
            $user_all += $item["user_count"];
        }
        $xs = !empty($user_all)?round($lesson_all/$user_all/100,1):0;
        $stu_info=$this->t_student_info->tongji_assisent($assistantid);

        $this->t_assistant_info->switch_tongji_database();
        $assistant_renew_list = $this->t_assistant_info->get_all_assistant_renew($start_time,$end_time);
        $all_money_ass = 0;
        foreach($assistant_renew_list as  &$val){
            $val['bye_total']=$val['all_total']-$val['give_total'];
            $all_money_ass += $val["all_price"];
        }
        #dd($assistant_renew);exit;
        return $this->pageView(__METHOD__ ,null, [
            "ret_info" => $ret_info,
            "end_time" => $end_time_date,
            "start_time" => $start_time_date,
            "assistantid" => $assistantid,
            "lesson_count_list" => $lesson_count_list,
            "stu_info" => $stu_info,
            "assistant_renew_list" => $assistant_renew_list,
            "all_money_ass"=>$all_money_ass,
            "lesson_all"   =>$lesson_all,
            "user_all"     =>$user_all,
            "xs"           =>$xs
        ]);

    }

    public  function assistant_leader() {
        $account_id = $this->get_account_id();
        $main_type = 1;
        $is_master = $this->t_admin_main_group_name->check_is_master($main_type,$account_id);
        if($is_master>0 || $account_id==349 ){
            $up_master_adminid=-1;
        }else{
            $up_master_adminid=0;
        }
        list($start_time,$end_time) = $this->get_in_date_range( date("Y-m-01",time(NULL)) ,0 );

        //$start = date('Y-m-d',$start_time);

        $this->t_manager_info->switch_tongji_database();
        $target_info = $this->t_manager_info->get_assistant_month_target_info($start_time);

        $assistant_stu_info=$this->t_manager_info->get_assistant_stu_info_new($end_time);
        $except_revisit_info=[];
        $stu_is_read=[];
        foreach($assistant_stu_info as $item){
            $uid = $item["uid"];
            $ass_assign_time = $item["ass_assign_time"];
            if($ass_assign_time < $start_time){
                @$except_revisit_info[$uid] +=4;
            }else{
                @$except_revisit_info[$uid] ++;
            }
            @$stu_is_read[$uid]++;
        }

        $revisit_info = $this->t_manager_info->tongji_assistant_revisit_info($start_time,$end_time);
        foreach($revisit_info as $k=>&$item){
            $item["except_revisit_count"] = @$except_revisit_info[$k];
            $item["revisit_per"] = !empty($item["except_revisit_count"])?round($item["revisit_count"]/$item["except_revisit_count"],4)*100:0;
        }
        $lesson_count_list = $this->t_manager_info->get_assistant_lesson_count_info($start_time,$end_time);
        foreach($lesson_count_list as $k=>&$item){
            $item["stu_is_read"]=@$stu_is_read[$k];
            $item["read_xs"] = !empty($item["stu_is_read"])?round($item["lesson_count"]/$item["stu_is_read"]):0;
            $item["lesson_xs"] = round($item["lesson_count"]/$item["user_count"]);
        }

        $assistant_renew_list = $this->t_manager_info->get_all_assistant_renew_new($start_time,$end_time);
        $all_money_ass = 0;
        foreach($assistant_renew_list as  &$val){
            $val['buy_total']=$val['all_total']-$val['give_total'];
            $all_money_ass += $val["all_price"];
        }

        $jk_stu = $this ->t_manager_info->get_assistant_jk_stu_info();
        $assistant_admin_info = $this->t_manager_info->get_assistant_admin_member_list($up_master_adminid,$account_id);


        $admin_list= &$assistant_admin_info['list'] ;


        foreach ($admin_list as $vk=>&$val){
            $adminid=$val['adminid'];
            $val['admin_revisiterid'] = $adminid ;
            $ret_item=@$revisit_info[$adminid];
            $val['revisit_count'] = @$ret_item['revisit_count'];
            $val['except_revisit_count'] = @$ret_item['except_revisit_count'];
            $val['revisit_per'] = @$ret_item['revisit_per'];
            $val['no_call'] = @$ret_item['no_call'];
            $lesson_item = @$lesson_count_list[$adminid];
            $val['lesson_count'] = @$lesson_item['lesson_count'];
            $val['user_count'] = @$lesson_item['user_count'];
            $val['stu_is_read'] = @$lesson_item['stu_is_read'];
            $val['read_xs'] = @$lesson_item['read_xs'];
            $val['lesson_xs'] = @$lesson_item['lesson_xs'];
            $renew_item = @$assistant_renew_list[$adminid];
            $val['all_student'] = @$renew_item['all_student'];
            $val['all_price'] = @$renew_item['all_price'];
            $val['give_total'] = @$renew_item['give_total'];
            $val['buy_total'] = @$renew_item['buy_total'];
            $val['jk_num'] = @$jk_stu[$adminid]['jk_num'];
            $val['lesson_target'] = @$target_info["list"][$adminid]['lesson_target'];

        }
        $ret_info=\App\Helper\Common::gen_admin_member_data($assistant_admin_info['list']);
        foreach( $ret_info as &$item ) {
            E\Emain_type::set_item_value_str($item);
            if($item["level"] != "l-4"){
                $item["revisit_per"] = !empty($item["except_revisit_count"])?round($item["revisit_count"]/$item["except_revisit_count"],4)*100:0;
                $item["read_xs"] = !empty($item["stu_is_read"])?round($item["lesson_count"]/$item["stu_is_read"]):0;
                $item["lesson_xs"] =  !empty($item["user_count"])?round($item["lesson_count"]/$item["user_count"]):0;

                $item["lesson_target"]="";
            }else{
                $item["target_per"] =  (!empty($item["lesson_target"])?round($item["read_xs"]/$item["lesson_target"],4):0)."%";
            }

        }


        return $this->pageView(__METHOD__,\App\Helper\Utils::list_to_page_info($ret_info),["data_ex_list"=>$ret_info]);

        // dd($ret_info);

    }


    public function jw_teacher(){
        list($start_time,$end_time) = $this->get_in_date_range( date("Y-m-01",time(NULL)) ,0 );
        // $this->t_test_lesson_subject_require->switch_tongji_database();
        // $res        = $this->t_test_lesson_subject_sub_list->get_teat_lesson_transfor_info($start_time,$end_time);

        $none_total = $this->t_test_lesson_subject_require->get_none_total_info($start_time,$end_time);
        $no_assign_total = $this->t_test_lesson_subject_require->get_no_assign_total_info($start_time,$end_time);
        $all_total  = 0;

        $month_start = strtotime(date("Y-m-01",time(NULL)));
        // $revisit_info = $this->t_teacher_info->get_jw_assign_teacher_info($start_time,$end_time);
        //重新 开发 JIM TODO
        //$res        = $this->t_test_lesson_subject_require->get_teat_lesson_transfor_info($start_time,$end_time);
        $res=[] ;
        $ret_info   = $this->t_test_lesson_subject_require->get_jw_teacher_test_lesson_info($start_time,$end_time);
        $tra_info =  $this->t_jw_teacher_month_plan_lesson_info->get_info_by_month_new($start_time);
        foreach($ret_info as &$item){
            $item["all_count"] = $item["all_count"]-$item["back_other_count"];

            if($start_time == strtotime(date("2017-01-01"))){
                $s = strtotime(date("2017-01-01 00:00:00"));
                $e = strtotime(date("2017-01-03 12:00:00"));
                $bc_info   = $this->t_test_lesson_subject_require->get_jw_teacher_test_lesson_info_bc($s,$e);
                $item["all_count"] += @$bc_info[$item["accept_adminid"]]["all_count"];
                $item["set_count"] += @$bc_info[$item["accept_adminid"]]["set_count"];
                $item["gz_count"] += @$bc_info[$item["accept_adminid"]]["gz_count"];
                $item["back_count"] += @$bc_info[$item["accept_adminid"]]["back_count"];
                $item["un_count"] += @$bc_info[$item["accept_adminid"]]["un_count"];
            }
            // $item["tra_per"] = $item["all_count"]==0?"0":(round($item["tra_count"]/$item["all_count"],4)*100);
            $item["tra_count"] = @$tra_info[$item["accept_adminid"]]["tran_count"];
            $item["tra_count_ass"] = @$tra_info[$item["accept_adminid"]]["tran_count_ass"];
            $item["tra_count_seller"] = @$tra_info[$item["accept_adminid"]]["tran_count_seller"];
            $item["tra_per_str"] = @$tra_info[$item["accept_adminid"]]["tran_per"];
            $item["set_per"] = $item["all_count"]==0?"无":(round($item["set_count"]/$item["all_count"],4)*100)."%";


            $all_total += $item["set_count"];
        }

        $day_start = strtotime(date("Y-m-d",time()));
        $day_end = $day_start+86400;
        $test_lesson_info = $this->t_test_lesson_subject_require->get_test_lesson_info_time($day_start,$day_end);
        $cur_num = 0;
        foreach($test_lesson_info as &$val){
            $h_s = $val["h"];
            $h_e = $h_s+1;
            if($h_s<10){
                $h_s="0".$h_s;
            }
            if($h_e<10){
                $h_e="0".$h_e;
            }

            $val["hour"] = $h_s.":00 -".$h_e.":00";
            $cur_num +=$val["num"];

        }
        $week_start = $day_start+86400;
        $week_end = $day_start+7*86400;
        $day_arr=[];
        $week_test_lesson = $this->t_test_lesson_subject_require->get_test_lesson_info_time_week($week_start,$week_end);
        $test_week=[];
        $test_num=[];
        foreach($week_test_lesson as $ss){
            $day = date("m-d",$ss["stu_request_test_lesson_time"]);
            $h_s = date("H",$ss["stu_request_test_lesson_time"]);
            $h_e = $h_s+1;
            if($h_e<10){
                $h_e="0".$h_e;
            }

            $hour = $h_s.":00 -".$h_e.":00";
            @$test_week[$day][$hour]++;
            @$test_num[$day]++;
        }

        //dd($test_week);
        //$lecture_info = $this->t_teacher_lecture_appointment_info->get_teacher_lecture_tongji($start_time,$end_time);
         \App\Helper\Utils::order_list( $ret_info,"all_count", 0 );
         return $this->pageView(__METHOD__ ,null, [
             "ret_info" => $ret_info,
             "all_total" => $all_total,
             "none_total"=> $none_total,
             "no_assign_total"=> $no_assign_total,
             "test_lesson_info"=>$test_lesson_info,
             "test_week"=>$test_week,
             "cur_num"   =>$cur_num,
             "test_num"  =>$test_num
         ]);
    }

    public function zs_teacher(){
        list($start_time,$end_time) = $this->get_in_date_range( date("Y-m-01",time(NULL)) ,0 );

        $this->t_teacher_lecture_appointment_info->switch_tongji_database();
        $all_total = $video_total=$suc_total=$fail_total=0;
        $ret_info  = $this->t_teacher_lecture_appointment_info->tongji_teacher_lecture_appoiment_info_by_accept_adminid($start_time,$end_time);
        $list      = $this->t_teacher_lecture_info->tongji_teacher_info_by_accept_adminid($start_time,$end_time);
        $suc_list  = $this->t_teacher_lecture_info->tongji_suc_teacher_info_by_accept_adminid($start_time,$end_time);
        $fail_list = $this->t_teacher_lecture_info->tongji_fail_teacher_info_by_accept_adminid($start_time,$end_time);

        foreach($ret_info as $k=>&$item){
            $accept_adminid       = $item["accept_adminid"];
            @$item["video_count"] = $list[$accept_adminid]["video_count"];
            @$item["suc_count"]   = $suc_list[$accept_adminid]["suc_count"];
            @$item["fail_count"]  = $fail_list[$accept_adminid]["fail_count"];
            $all_total   += $item["all_count"];
            $video_total += $item["video_count"];
            $suc_total   += $item["suc_count"];
            $fail_total  += $item["fail_count"];
            $item["video_per"] = ($item["all_count"])==0?"0":(round($item["video_count"]/($item["all_count"]),4)*100);
            $item["suc_per"] = ($item["suc_count"]+$item["fail_count"])==0?"0":(round($item["suc_count"]/($item["suc_count"]+$item["fail_count"]),4)*100);
        }
        \App\Helper\Utils::order_list( $ret_info,"suc_per", 0 );
        $res_subject = $this->t_teacher_lecture_info->get_lecture_info_by_subject_new($start_time,$end_time);
        $video_succ_subject = $this->t_teacher_lecture_info->get_lecture_info_by_subject_new($start_time,$end_time,1);
        $one_subject = $this->t_teacher_record_list->get_all_interview_count_by_subject($start_time,$end_time,-1);
        $one_succ_subject = $this->t_teacher_record_list->get_all_interview_count_by_subject($start_time,$end_time,1);        
        $all_subject=["subject_str"=>"总计"];$all_grade=["grade_ex_str"=>"总计"];     
        foreach($one_subject as $k=>$val){
            if(isset($res_subject[$k])){
                $res_subject[$k]["all_count"] +=$val["all_count"];
                $res_subject[$k]["all_num"] +=$val["all_num"];
            }else{
                $res_subject[$k]=$val;
            }
        }
        foreach($res_subject as $key=>&$t){
            @$t["succ"] +=$video_succ_subject[$key]["all_count"];
            @$t["succ"] +=$one_succ_subject[$key]["all_count"];
            @$t["succ_num"] +=$video_succ_subject[$key]["all_num"];
            @$t["succ_num"] +=$one_succ_subject[$key]["all_num"];

            E\Esubject::set_item_value_str($t,"subject");
            $teacher_list = $this->t_teacher_lecture_info->get_teacher_list_passed("",$start_time,$end_time,$t["subject"]);
            $teacher_arr = $this->t_teacher_record_list->get_teacher_train_passed("",$start_time,$end_time,$t["subject"]);
            foreach($teacher_arr as $k=>$l){
                if(!isset($teacher_list[$k])){
                    $teacher_list[$k]=$k; 
                }
            }
            $t["train_num"] = $this->t_lesson_info_b2->get_all_train_num($start_time,$end_time,$teacher_list,-1,false);
            $t["train_succ"] = $this->t_lesson_info_b2->get_all_train_num($start_time,$end_time,$teacher_list,1,false);
            $t["succ_per"] = !empty($t["all_count"])?round($t["succ"]/$t["all_count"]*100,2):0;
            $t["succ_num_per"] = !empty($t["all_num"])?round($t["succ_num"]/$t["all_num"]*100,2):0;
            $t["train_per"] = !empty($t["train_num"])?round($t["train_succ"]/$t["train_num"]*100,2):0;
            @$all_subject["succ"] +=$t["succ"];
            @$all_subject["succ_num"] +=$t["succ_num"];
            @$all_subject["all_count"] +=$t["all_count"];
            @$all_subject["all_num"] +=$t["all_num"];
            @$all_subject["train_num"] +=$t["train_num"];
            @$all_subject["train_succ"] +=$t["train_succ"];

        }
        $all_subject["succ_per"] = !empty($all_subject["all_count"])?round($all_subject["succ"]/$all_subject["all_count"]*100,2):0;
        $all_subject["succ_num_per"] = !empty($all_subject["all_num"])?round($all_subject["succ_num"]/$all_subject["all_num"]*100,2):0;
        $all_subject["train_per"] = !empty($all_subject["train_num"])?round($all_subject["train_succ"]/$all_subject["train_num"]*100,2):0;

        
        
        $res_grade = $this->t_teacher_lecture_info->get_lecture_info_by_grade($start_time,$end_time);
        $video_succ_grade = $this->t_teacher_lecture_info->get_lecture_info_by_grade($start_time,$end_time,1);
        $one_grade = $this->t_teacher_record_list->get_all_interview_count_by_grade($start_time,$end_time,-1);
        $one_succ_grade = $this->t_teacher_record_list->get_all_interview_count_by_grade($start_time,$end_time,1);        
        foreach($one_grade as $k=>$val){
            if(isset($res_grade[$k])){
                $res_grade[$k]["all_count"] +=$val["all_count"];
                $res_grade[$k]["all_num"] +=$val["all_num"];
            }else{
                $res_grade[$k]=$val;
            }
        }
        foreach($res_grade as $key=>&$i){
            @$i["succ"] +=$video_succ_grade[$key]["all_count"];
            @$i["succ"] +=$one_succ_grade[$key]["all_count"];
            @$i["succ_num"] +=$video_succ_grade[$key]["all_num"];
            @$i["succ_num"] +=$one_succ_grade[$key]["all_num"];
            $teacher_list = $this->t_teacher_lecture_info->get_teacher_list_passed("",$start_time,$end_time,-1,-1,-1,-1,"",$i["grade_ex"]);
            $teacher_arr = $this->t_teacher_record_list->get_teacher_train_passed("",$start_time,$end_time,-1,-1,-1,-1,"",$i["grade_ex"]);
            foreach($teacher_arr as $k=>$l){
                if(!isset($teacher_list[$k])){
                    $teacher_list[$k]=$k; 
                }
            }
            $i["train_num"] = $this->t_lesson_info_b2->get_all_train_num($start_time,$end_time,$teacher_list,-1);
            $i["train_succ"] = $this->t_lesson_info_b2->get_all_train_num($start_time,$end_time,$teacher_list,1);
            $i["succ_per"] = !empty($i["all_count"])?round($i["succ"]/$i["all_count"]*100,2):0;
            $i["succ_num_per"] = !empty($i["all_num"])?round($i["succ_num"]/$i["all_num"]*100,2):0;
            $i["train_per"] = !empty($i["train_num"])?round($i["train_succ"]/$i["train_num"]*100,2):0;
            @$all_grade["succ"] +=$i["succ"];
            @$all_grade["succ_num"] +=$i["succ_num"];
            @$all_grade["all_count"] +=$i["all_count"];
            @$all_grade["all_num"] +=$i["all_num"];
            @$all_grade["train_num"] +=$i["train_num"];
            @$all_grade["train_succ"] +=$i["train_succ"];



            E\Egrade::set_item_value_str($i,"grade_ex");
        }
        $all_grade["succ_per"] = !empty($all_grade["all_count"])?round($all_grade["succ"]/$all_grade["all_count"]*100,2):0;
        $all_grade["succ_num_per"] = !empty($all_grade["all_num"])?round($all_grade["succ_num"]/$all_grade["all_num"]*100,2):0;
        $all_grade["train_per"] = !empty($all_grade["train_num"])?round($all_grade["train_succ"]/$all_grade["train_num"]*100,2):0;

        // array_push($res_grade,$all_grade);
        // array_push($res_subject,$all_subject);

                  
        $list = $this->t_teacher_lecture_info->get_lecture_info_by_time_new(
            -1,$start_time,$end_time,-1,-1,-1,"");
        $arr = $this->t_teacher_record_list->get_train_teacher_interview_info(
            -1,$start_time,$end_time,-1,-1,-1,"");
        foreach($arr["list"] as $k=>$val){
            if(isset($list["list"][$k])){
                $list["list"][$k]["all_count"] += $val["all_count"]; 
                $list["list"][$k]["all_num"] += $val["all_num"]; 
            }else{
                $list["list"][$k]= $val;
            }
        }
                
        foreach($list["list"] as &$item){
            $account = $item["account"];
            $teacher_list = $this->t_teacher_lecture_info->get_teacher_list_passed($account,$start_time,$end_time);
            $teacher_arr = $this->t_teacher_record_list->get_teacher_train_passed($account,$start_time,$end_time);
            foreach($teacher_arr as $k=>$val){
                if(!isset($teacher_list[$k])){
                    $teacher_list[$k]=$k; 
                }
            }
                
            $item["suc_count"] = count($teacher_list);
            $item["pass_per"] = (round($item["suc_count"]/$item["all_count"],2))*100;
            $item["all_pass_per"] = (round($item["suc_count"]/$item["all_num"],2))*100;
            $res = $this->t_lesson_info->get_test_leson_info_by_teacher_list($teacher_list);
            $item["all_lesson"] = $res["all_lesson"];
            $item["have_order"] = $res["have_order"];
            $item["order_per"] =  $item["all_lesson"]==0?0:((round($item["have_order"]/$item["all_lesson"],2))*100);
        }
        
        $all_item=["account" => "全部"];
        $sum_field_list = [
            "all_num",
            "all_count",
            "suc_count",
            "pass_per",
            "all_pass_per",
            "ave_time",
            "all_lesson",
            "have_order",
            "order_per"
        ];

        \App\Helper\Utils::list_add_sum_item($list["list"], $all_item,$sum_field_list );

        $teacher_list_ex = $this->t_teacher_lecture_info->get_teacher_list_passed("",$start_time,$end_time);
        $teacher_arr_ex = $this->t_teacher_record_list->get_teacher_train_passed("",$start_time,$end_time);
        foreach($teacher_arr_ex as $k=>$val){
            if(!isset($teacher_list_ex[$k])){
                $teacher_list_ex[$k]=$k; 
            }
        }  
        
        $all_tea_ex = count($teacher_list_ex);
        $train_all = $this->t_lesson_info_b2->get_all_train_num($start_time,$end_time,$teacher_list_ex,-1);
        $train_succ = $this->t_lesson_info_b2->get_all_train_num($start_time,$end_time,$teacher_list_ex,1);
        $train_succ_per = round($train_succ/$train_all*100,2)."%";


        foreach($list["list"] as &$item1){
            if($item1["account"]=="全部"){
                $item1["pass_per"] = @$item1["all_count"]==0?0:(round($all_tea_ex/@$item1["all_count"],2))*100;
                $item1["order_per"] =@$item1["all_lesson"]==0?0:(round(@$item1["have_order"]/@$item1["all_lesson"],2))*100;
                $item1["all_pass_per"] = (round( @$item1["suc_count"]/$item1["all_num"],2))*100;
                $item1["succ_num"] =  $all_tea_ex;
                $item1["train_all"] = $train_all;
                $item1["train_succ"] = $train_succ;
                $item1["train_per"] =  $train_succ_per;
                $data = $item1;
            }
        }

        $lecture_identity = $this->t_teacher_lecture_info->get_lecture_info_by_identity($start_time,$end_time);
        $lecture_identity_succ = $this->t_teacher_lecture_info->get_lecture_info_by_identity($start_time,$end_time,1);
        $train_identity = $this->t_teacher_record_list->get_all_interview_count_by_identity($start_time,$end_time,-1);
        $train_identity_succ = $this->t_teacher_record_list->get_all_interview_count_by_identity($start_time,$end_time,1);        
        foreach($train_identity as $k=>$val){
            if(isset($lecture_identity[$k])){
                $lecture_identity[$k]["all_count"] +=$val["all_count"];
                $lecture_identity[$k]["all_num"] +=$val["all_num"];
            }else{
                $lecture_identity[$k]=$val;
            }
        }
        foreach($lecture_identity as $key=>&$n){
            @$n["succ"] +=$lecture_identity_succ[$key]["all_count"];
            @$n["succ"] +=$train_identity_succ[$key]["all_count"];
            @$n["succ_num"] +=$lecture_identity_succ["all_num"];
            @$n["succ_num"] +=$train_identity_succ[$key]["all_num"];
            $teacher_list = $this->t_teacher_lecture_info->get_teacher_list_passed("",$start_time,$end_time,-1,-1,-1,$n["identity"]);
            $teacher_arr = $this->t_teacher_record_list->get_teacher_train_passed("",$start_time,$end_time,-1,-1,-1,$n["identity"]);
            foreach($teacher_arr as $k=>$l){
                if(!isset($teacher_list[$k])){
                    $teacher_list[$k]=$k; 
                }
            }
            $n["train_num"] = $this->t_lesson_info_b2->get_all_train_num($start_time,$end_time,$teacher_list,-1);
            $n["train_succ"] = $this->t_lesson_info_b2->get_all_train_num($start_time,$end_time,$teacher_list,1);
            $n["succ_per"] = !empty($n["all_count"])?round($n["succ"]/$n["all_count"]*100,2):0;
            $n["succ_num_per"] = !empty($n["all_num"])?round($n["succ_num"]/$n["all_num"]*100,2):0;
            $n["train_per"] = !empty($n["train_num"])?round($n["train_succ"]/$n["train_num"]*100,2):0;

            E\Eidentity::set_item_value_str($n,"identity");
            if($n["identity"]==127){
                unset($lecture_identity[$key]);
            }
        }

       
        return $this->pageView(__METHOD__ ,null, [
            "ret_info"    => $ret_info,
            "all_total"   => $all_total,
            "video_total" => $video_total,
            "suc_total"   => $suc_total,
            "res_grade"   =>$res_grade,
            "res_subject" =>$res_subject,
            "data"        =>$data,
            "res_identity"=>$lecture_identity
        ]);
    }

    public function assistant_new() {
        $this->t_lesson_info->switch_tongji_database();
        $this->t_month_ass_student_info->switch_tongji_database();
        $this->t_test_lesson_subject->switch_tongji_database();

        list($start_time,$end_time) = $this->get_in_date_range(0,0,0,[],3);
        $opt_date_type = $this->get_in_int_val("opt_date_type",3);
        //dd($opt_date_type);

        if($opt_date_type==1){
            $cur_start = strtotime(date('Y-m-01',$start_time));
            $mid_time = strtotime(date('Y-m-15',$start_time));
            $cur_end = strtotime(date('Y-m-01',$mid_time+30*86400));
        }else if($opt_date_type==2){
            $cur_start = strtotime(date('Y-m-01',$end_time));
            $mid_time = strtotime(date('Y-m-15',$end_time));
            $cur_end = strtotime(date('Y-m-01',$mid_time+30*86400));
        }else if($opt_date_type==3){
            $cur_start = $start_time;
            $cur_end = $end_time;
        }
        $last_month = strtotime(date('Y-m-01',$cur_start-100));
        $month_start = strtotime(date("Y-m-01",time(NULL)));
        $account_id = $this->get_account_id();
        if($account_id==349){
            $account_id = 297;
        }
        // $account_id = 324;
        //$master_adminid = $this->t_admin_group_user->get_master_adminid_by_adminid($account_id);
        //$up_master_adminid = $this->t_admin_main_group_name->get_up_group_adminid( $master_adminid);
        $ass_last_month = $this->t_month_ass_student_info->get_ass_month_info($last_month);
        $lesson_count_list_old=[];
        $ass_list = $this->t_manager_info->get_adminid_list_by_account_role(1);

        $lesson_target = $this->t_ass_group_target->get_rate_target($cur_start);

        $kk_require= $this->t_test_lesson_subject->get_ass_kk_tongji_all_info($start_time,$end_time);
        // @$kk_suc= $this->t_test_lesson_subject->get_ass_kk_tongji_info($start_time,$end_time);
        $stu_info_all = $this->t_student_info->get_ass_stu_info_new();
        $ass_month= $this->t_month_ass_student_info->get_ass_month_info($cur_start);
        $assistant_renew_list = $this->t_manager_info->get_all_assistant_renew_list_new($start_time,$end_time);
        foreach($ass_list as $k=>&$item){
            $item["warning_student"]  = isset($ass_month[$k]["warning_student"])?$ass_month[$k]["warning_student"]:0;
            $item["read_student"]     = isset($ass_month[$k]["read_student"])?$ass_month[$k]["read_student"]:0;
            $item["stop_student"]     = isset($ass_month[$k]["stop_student"])?$ass_month[$k]["stop_student"]:0;
            $item["all_student"]      = isset($ass_month[$k]["all_student"])?$ass_month[$k]["all_student"]:0;
            $item["month_stop_student"]  = isset($ass_month[$k]["month_stop_student"])?$ass_month[$k]["month_stop_student"]:0;
            $item["lesson_ratio"]  = isset($ass_month[$k]["lesson_ratio"])?$ass_month[$k]["lesson_ratio"]:0;
            $item["lesson_total"]  = isset($ass_month[$k]["lesson_total"])?$ass_month[$k]["lesson_total"]/100:0;
            $item["renw_price"]  = isset($assistant_renew_list[$k]["renw_price"])?$assistant_renew_list[$k]["renw_price"]/100:0;
           
            $item["tran_price"]  = isset($assistant_renew_list[$k]["tran_price"])?$assistant_renew_list[$k]["tran_price"]/100:0;
            $item["renw_student"]  = isset($assistant_renew_list[$k]["all_student"])?$assistant_renew_list[$k]["all_student"]:0;
            $item["refund_student"]  = isset($ass_month[$k]["refund_student"])?$ass_month[$k]["refund_student"]:0;
            $item["read_student_last"]  = isset($ass_month[$k]["read_student_last"])?@$ass_month[$k]["read_student_last"]:0;
            $item["all_price"]     = $item["renw_price"]+$item["tran_price"];
            $item["lesson_target"]         = $lesson_target;
            $item["lesson_per"]            = !empty($item["lesson_target"])?round($item["lesson_ratio"]/$item["lesson_target"],4)*100:0;
            $item["renw_target"]           = @$ass_last_month[$k]["warning_student"]*0.8*8000;
            $item["renw_per"]              = !empty($item["renw_target"])?round($item["all_price"]/$item["renw_target"],4)*100:0;
            $item["renw_stu_target"]       = ceil(@$ass_last_month[$k]["warning_student"]*0.8);
            $item["renw_stu_per"]          = !empty($item["renw_stu_target"])?round($item["renw_student"]/$item["renw_stu_target"],4)*100:0;
            $item["kk_suc"]                = isset($ass_month[$k]["kk_num"])?$ass_month[$k]["kk_num"]:0;
            //$item["kk_suc"]                =@$kk_suc[$k]["lesson_count"];
            $item["kk_require"]            =@$kk_require[$k]["all_count"];
            $item["except_num"]            =@$stu_info_all[$k]["except_num"];
            $item["except_count"]            =@$stu_info_all[$k]["except_count"];
            $item["lesson_total_old"]  = !empty(@$ass_last_month[$k]["lesson_total_old"])?@$ass_last_month[$k]["lesson_total_old"]/100:(round($item["read_student_last"]*$item["lesson_ratio"],1));          

        }

        if(in_array(date("d",$start_time),[28,29,30,31])){
            foreach($ass_month as $ks=>$vss){
                $userid_list = json_decode($vss["userid_list_last"],true);
                if(empty($userid_list)){
                    $userid_list=[];
                }

                $lesson_count_list_old[$ks]=$this->t_manager_info->get_assistant_lesson_count_old($start_time,$end_time,$ks,$userid_list);
            }

            foreach($ass_list as $k=>&$dal){
                $dal["lesson_ratio"]  = !empty(@$ass_last_month[$k]["read_student"])?round(@$lesson_count_list_old[$k]/@$ass_last_month[$k]["read_student"]/100,1):0;
                $dal["lesson_total_old"]      = @$lesson_count_list_old[$k]/100;
            }
        }

        $stu_info = @$ass_list[$account_id];

        if($opt_date_type==2){
            $date_week = \App\Helper\Utils::get_week_range($start_time,1);
        }else{
            $date_week = \App\Helper\Utils::get_week_range(time(),1);
        }
        $lstart = $date_week["sdate"];
        $lend = $date_week["edate"];
        $stu_info["week_stu_num"] = $this->t_lesson_info->get_week_stu_num_info($lstart,$lend,$account_id);


        $lesson_list =  $renw_list =  $ass_list;
        \App\Helper\Utils::order_list( $lesson_list,"lesson_ratio", 0 );
        \App\Helper\Utils::order_list( $renw_list,"renw_price", 0 );

        return $this->pageView(__METHOD__ ,null, [
            "stu_info" => $stu_info,
            "lesson_list"  =>$lesson_list,
            "renw_list"   =>$renw_list
        ]);


            // dd($stu_info);
            //dd($up_master_adminid);

    }

    public function assistant_leader_new() {
        $this->t_lesson_info->switch_tongji_database();
        $this->t_month_ass_student_info->switch_tongji_database();
        $this->t_test_lesson_subject->switch_tongji_database();

        list($start_time,$end_time) = $this->get_in_date_range(0,0,0,[],3);

        $opt_date_type = $this->get_in_int_val("opt_date_type",3);

        if($opt_date_type==1){
            $cur_start = strtotime(date('Y-m-01',$start_time));
            $mid_time = strtotime(date('Y-m-15',$start_time));
            $cur_end = strtotime(date('Y-m-01',$mid_time+30*86400));
        }else if($opt_date_type==2){
            $cur_start = strtotime(date('Y-m-01',$end_time));
            $mid_time = strtotime(date('Y-m-15',$end_time));
            $cur_end = strtotime(date('Y-m-01',$mid_time+30*86400));
        }else if($opt_date_type==3){
            $cur_start = $start_time;
            $cur_end = $end_time;
        }
        $last_month  = strtotime(date('Y-m-01',$cur_start-100));
        $month_start = strtotime(date("Y-m-01",time(NULL)));
        $account_id  = $this->get_account_id();
        $master_adminid    = $this->t_admin_group_user->get_master_adminid_by_adminid($account_id);
        $up_master_adminid = $this->t_admin_main_group_name->get_up_group_adminid( $master_adminid);
        $ass_last_month    = $this->t_month_ass_student_info->get_ass_month_info($last_month);

        $lesson_count_list_old=[];
        $ass_list = $this->t_manager_info->get_adminid_list_by_account_role(1);

        $lesson_target     = $this->t_ass_group_target->get_rate_target($cur_start);
        $kk_require        = $this->t_test_lesson_subject->get_ass_kk_tongji_all_info($start_time,$end_time);
        $lesson_money_list = $this->t_manager_info->get_assistant_lesson_money_info($start_time,$end_time);
        $stu_info_all      = $this->t_student_info->get_ass_stu_info_new();
        $ass_month= $this->t_month_ass_student_info->get_ass_month_info($cur_start);
        $assistant_renew_list = $this->t_manager_info->get_all_assistant_renew_list_new($start_time,$end_time);
        $new_info = $this->t_student_info->get_new_assign_stu_info($start_time,$end_time);
        foreach($ass_list as $k=>&$item){
            $item["warning_student"]  = isset($ass_month[$k]["warning_student"])?$ass_month[$k]["warning_student"]:0;
            $item["read_student"]     = isset($ass_month[$k]["read_student"])?$ass_month[$k]["read_student"]:0;
            $item["stop_student"]     = isset($ass_month[$k]["stop_student"])?$ass_month[$k]["stop_student"]:0;
            $item["all_student"]      = isset($ass_month[$k]["all_student"])?$ass_month[$k]["all_student"]:0;
            $item["month_stop_student"]  = isset($ass_month[$k]["month_stop_student"])?$ass_month[$k]["month_stop_student"]:0;
            $item["lesson_ratio"]  = isset($ass_month[$k]["lesson_ratio"])?$ass_month[$k]["lesson_ratio"]:0;
            $item["lesson_total"]  = isset($ass_month[$k]["lesson_total"])?$ass_month[$k]["lesson_total"]/100:0;
            $item["renw_price"]  = isset($assistant_renew_list[$k]["renw_price"])?$assistant_renew_list[$k]["renw_price"]/100:0;
            $item["tran_price"]  = isset($assistant_renew_list[$k]["tran_price"])?$assistant_renew_list[$k]["tran_price"]/100:0;

            //$item["renw_student"]  = isset($ass_month[$k]["renw_student"])?$ass_month[$k]["renw_student"]:0;
            $item["renw_student"]  = isset($assistant_renew_list[$k]["all_student"])?$assistant_renew_list[$k]["all_student"]:0;
            $item["read_student_last"]  = isset($ass_month[$k]["read_student_last"])?@$ass_month[$k]["read_student_last"]:0;
            $item["all_price"]     = $item["renw_price"]+$item["tran_price"];
            $item["lesson_target"]         = $lesson_target;
            $item["lesson_per"]            = !empty($item["lesson_target"])?round($item["lesson_ratio"]/$item["lesson_target"],4)*100:0;
            $item["renw_target"]           = @$ass_last_month[$k]["warning_student"]*0.8*8000;
            $item["renw_per"]              = !empty($item["renw_target"])?round($item["all_price"]/$item["renw_target"],4)*100:0;
            $item["renw_stu_target"]       = ceil(@$ass_last_month[$k]["warning_student"]*0.8);
            $item["renw_stu_per"]          = !empty($item["renw_stu_target"])?round($item["renw_student"]/$item["renw_stu_target"],4)*100:0;
            $item["kk_suc"]                = isset($ass_month[$k]["kk_num"])?$ass_month[$k]["kk_num"]:0;
            //$item["kk_suc"]                =@$kk_suc[$k]["lesson_count"];
            $item["kk_require"]            =@$kk_require[$k]["all_count"];
            $item["except_num"]            =@$stu_info_all[$k]["except_num"];
            $item["except_count"]            =@$stu_info_all[$k]["except_count"];
            $item["lesson_money"]          = @$lesson_money_list[$k]["lesson_price"]/100;
                  
            $item["lesson_total_old"]  = !empty(@$ass_last_month[$k]["lesson_total_old"])?@$ass_last_month[$k]["lesson_total_old"]/100:(round($item["read_student_last"]*$item["lesson_ratio"],1));   
            $item["refund_student"]  = isset($ass_month[$k]["refund_student"])?$ass_month[$k]["refund_student"]:0;
            $item["new_refund_money"]  = isset($ass_month[$k]["new_refund_money"])?$ass_month[$k]["new_refund_money"]/100:0;
            $item["renw_refund_money"]  = isset($ass_month[$k]["renw_refund_money"])?$ass_month[$k]["renw_refund_money"]/100:0;
            $item["new_student"]  = isset($new_info[$k]["num"])?$new_info[$k]["num"]:0;
            $item["new_lesson_count"]  = isset($new_info[$k]["lesson_count"])?$new_info[$k]["lesson_count"]/100:0;

        }

        
        if(in_array(date("d",$start_time),[28,29,30,31])){
            foreach($ass_month as $ks=>$vss){
                $userid_list = json_decode($vss["userid_list_last"],true);
                if(empty($userid_list)){
                    $userid_list=[];
                }
               
                $lesson_count_list_old[$ks]=$this->t_manager_info->get_assistant_lesson_count_old($start_time,$end_time,$ks,$userid_list);
            }

            foreach($ass_list as $k=>&$dal){
                $dal["lesson_ratio"]  = !empty(@$ass_last_month[$k]["read_student"])?round(@$lesson_count_list_old[$k]/@$ass_last_month[$k]["read_student"]/100,1):0;
                $dal["lesson_total_old"]      = @$lesson_count_list_old[$k]/100;
            }
        }

        // dd($ass_list);
        $ass_list1 = $ass_list2 = $ass_list3 =   $ass_list;
        $ass_leader_arr = $this->t_admin_group_name->get_leader_list(1);
        $ass_group=[];
        foreach($ass_list1 as $key=>$val){
            // echo $key;
            $master_adminid_ass = $this->t_admin_group_user->get_master_adminid_by_adminid($key);
            @$ass_group[$master_adminid_ass]["warning_student"]  += $val["warning_student"];
            @$ass_group[$master_adminid_ass]["read_student"]     += $val["read_student"];
            @$ass_group[$master_adminid_ass]["stop_student"]     += $val["stop_student"];
            @$ass_group[$master_adminid_ass]["all_student"]      += $val["all_student"];
            @$ass_group[$master_adminid_ass]["month_stop_student"]  += $val["month_stop_student"];
            @$ass_group[$master_adminid_ass]["lesson_total"]     += $val["lesson_total"];
            @$ass_group[$master_adminid_ass]["lesson_money"]     += $val["lesson_money"];
            @$ass_group[$master_adminid_ass]["lesson_total_old"]     += $val["lesson_total_old"];
            @$ass_group[$master_adminid_ass]["tran_price"]       += $val["tran_price"];
            @$ass_group[$master_adminid_ass]["renw_price"]       += $val["renw_price"];
            @$ass_group[$master_adminid_ass]["all_price"]        += $val["all_price"];
            @$ass_group[$master_adminid_ass]["renw_student"]     += $val["renw_student"];
            @$ass_group[$master_adminid_ass]["read_student_last"]  += $val["read_student_last"];
            @$ass_group[$master_adminid_ass]["renw_target"]           += $val["renw_target"];
            @$ass_group[$master_adminid_ass]["renw_stu_target"]       += $val["renw_stu_target"];
            @$ass_group[$master_adminid_ass]["kk_suc"]       += $val["kk_suc"];
            @$ass_group[$master_adminid_ass]["kk_require"]       += $val["kk_require"];
            @$ass_group[$master_adminid_ass]["except_num"]       += $val["except_num"];
            @$ass_group[$master_adminid_ass]["except_count"]       += $val["except_count"];
            @$ass_group[$master_adminid_ass]["refund_student"]       += $val["refund_student"];
            @$ass_group[$master_adminid_ass]["new_refund_money"]       += $val["new_refund_money"];
            @$ass_group[$master_adminid_ass]["renw_refund_money"]       += $val["renw_refund_money"];
            @$ass_group[$master_adminid_ass]["new_student"]       += $val["new_student"];
            @$ass_group[$master_adminid_ass]["new_lesson_count"]       += $val["new_lesson_count"];
        }

         foreach($ass_group as $key=>&$v){
            $v["account"] = $this->t_manager_info->get_account($key);
            $v["lesson_ratio"]          = !empty($v["read_student_last"])?round($v["lesson_total_old"]/$v["read_student_last"],1):0;
            $v["lesson_target"]         =$lesson_target;
            $v["lesson_per"]            =!empty($v["lesson_target"])?round($v["lesson_ratio"]/$v["lesson_target"],4)*100:0;
            $v["renw_per"]             =!empty($v["renw_target"])?round($v["all_price"]/$v["renw_target"],4)*100:0;
            $v["renw_stu_per"]            =!empty($v["renw_stu_target"])?round($v["renw_student"]/$v["renw_stu_target"],4)*100:0;

        }
        unset($ass_group[0]);

        // dd($ass_group);
        $account_id=297;
        $stu_info=@$ass_group[$account_id];
        $ass_list_group=[];
        foreach($ass_list3 as $k=>$item2){
            $ass_master_adminid = $this->t_admin_group_user->get_master_adminid_by_adminid($k);
            if($ass_master_adminid == $account_id){
                $ass_list_group[$k]=$item2;
            }
        }
        \App\Helper\Utils::order_list( $ass_list_group,"lesson_ratio", 0 );

       
        \App\Helper\Utils::order_list( $ass_list,"lesson_ratio", 0 );
        \App\Helper\Utils::order_list( $ass_group,"lesson_ratio", 0 );


        return $this->pageView(__METHOD__ ,null, [
            "stu_info" => @$stu_info,
            "ass_list"  =>@$ass_list,
            // "ass_group"   =>@$ass_group[$account_id],
            "ass_list_group" =>@$ass_list_group
        ]);


       
    }

    public function assistant_main_leader_new() {
        $this->t_lesson_info->switch_tongji_database();
        $this->t_month_ass_student_info->switch_tongji_database();
        $this->t_test_lesson_subject->switch_tongji_database();

        list($start_time,$end_time) = $this->get_in_date_range(0,0,0,[],3);

        $opt_date_type = $this->get_in_int_val("opt_date_type",3);

        if($opt_date_type==1){
            $cur_start = strtotime(date('Y-m-01',$start_time));
            $mid_time = strtotime(date('Y-m-15',$start_time));
            $cur_end = strtotime(date('Y-m-01',$mid_time+30*86400));
        }else if($opt_date_type==2){
            $cur_start = strtotime(date('Y-m-01',$end_time));
            $mid_time = strtotime(date('Y-m-15',$end_time));
            $cur_end = strtotime(date('Y-m-01',$mid_time+30*86400));
        }else if($opt_date_type==3){
            $cur_start = $start_time;
            $cur_end = $end_time;
        }
        $last_month  = strtotime(date('Y-m-01',$cur_start-100));
        $month_start = strtotime(date("Y-m-01",time(NULL)));
        $account_id  = $this->get_account_id();
        $master_adminid    = $this->t_admin_group_user->get_master_adminid_by_adminid($account_id);
        $up_master_adminid = $this->t_admin_main_group_name->get_up_group_adminid( $master_adminid);
        $ass_last_month    = $this->t_month_ass_student_info->get_ass_month_info($last_month);

        $lesson_count_list_old=[];
        $ass_list = $this->t_manager_info->get_adminid_list_by_account_role(1);

        $lesson_target     = $this->t_ass_group_target->get_rate_target($cur_start);
        $kk_require        = $this->t_test_lesson_subject->get_ass_kk_tongji_all_info($start_time,$end_time);
        $lesson_money_list = $this->t_manager_info->get_assistant_lesson_money_info($start_time,$end_time);
        $stu_info_all      = $this->t_student_info->get_ass_stu_info_new();
        $ass_month= $this->t_month_ass_student_info->get_ass_month_info($cur_start);
        $assistant_renew_list = $this->t_manager_info->get_all_assistant_renew_list_new($start_time,$end_time);
        $new_info = $this->t_student_info->get_new_assign_stu_info($start_time,$end_time);
        foreach($ass_list as $k=>&$item){
            $item["warning_student"]  = isset($ass_month[$k]["warning_student"])?$ass_month[$k]["warning_student"]:0;
            $item["read_student"]     = isset($ass_month[$k]["read_student"])?$ass_month[$k]["read_student"]:0;
            $item["stop_student"]     = isset($ass_month[$k]["stop_student"])?$ass_month[$k]["stop_student"]:0;
            $item["all_student"]      = isset($ass_month[$k]["all_student"])?$ass_month[$k]["all_student"]:0;
            $item["month_stop_student"]  = isset($ass_month[$k]["month_stop_student"])?$ass_month[$k]["month_stop_student"]:0;
            $item["lesson_ratio"]  = isset($ass_month[$k]["lesson_ratio"])?$ass_month[$k]["lesson_ratio"]:0;
            $item["lesson_total"]  = isset($ass_month[$k]["lesson_total"])?$ass_month[$k]["lesson_total"]/100:0;
            $item["renw_price"]  = isset($assistant_renew_list[$k]["renw_price"])?$assistant_renew_list[$k]["renw_price"]/100:0;
            $item["tran_price"]  = isset($assistant_renew_list[$k]["tran_price"])?$assistant_renew_list[$k]["tran_price"]/100:0;

            $item["renw_student"]  = isset($assistant_renew_list[$k]["all_student"])?$assistant_renew_list[$k]["all_student"]:0;

            $item["read_student_last"]  = isset($ass_month[$k]["read_student_last"])?@$ass_month[$k]["read_student_last"]:0;
            $item["all_price"]     = $item["renw_price"]+$item["tran_price"];
            $item["lesson_target"]         = $lesson_target;
            $item["lesson_per"]            = !empty($item["lesson_target"])?round($item["lesson_ratio"]/$item["lesson_target"],4)*100:0;
            $item["renw_target"]           = @$ass_last_month[$k]["warning_student"]*0.8*8000;
            $item["renw_per"]              = !empty($item["renw_target"])?round($item["all_price"]/$item["renw_target"],4)*100:0;
            $item["renw_stu_target"]       = ceil(@$ass_last_month[$k]["warning_student"]*0.8);
            $item["renw_stu_per"]          = !empty($item["renw_stu_target"])?round($item["renw_student"]/$item["renw_stu_target"],4)*100:0;
            $item["kk_suc"]                = isset($ass_month[$k]["kk_num"])?$ass_month[$k]["kk_num"]:0;
            $item["kk_require"]            =@$kk_require[$k]["all_count"];
            $item["except_num"]            =@$stu_info_all[$k]["except_num"];
            $item["except_count"]            =@$stu_info_all[$k]["except_count"];
            $item["lesson_money"]          = @$lesson_money_list[$k]["lesson_price"]/100;
            //$item["lesson_total_old"]  = intval($item["read_student_last"]*$item["lesson_ratio"]);
            $item["lesson_total_old"]  = !empty(@$ass_last_month[$k]["lesson_total_old"])?@$ass_last_month[$k]["lesson_total_old"]/100:(round($item["read_student_last"]*$item["lesson_ratio"],1));   
            $item["refund_student"]  = isset($ass_month[$k]["refund_student"])?$ass_month[$k]["refund_student"]:0;
            $item["new_refund_money"]  = isset($ass_month[$k]["new_refund_money"])?$ass_month[$k]["new_refund_money"]/100:0;
            $item["renw_refund_money"]  = isset($ass_month[$k]["renw_refund_money"])?$ass_month[$k]["renw_refund_money"]/100:0;
            $item["new_student"]  = isset($new_info[$k]["num"])?$new_info[$k]["num"]:0;
            $item["new_lesson_count"]  = isset($new_info[$k]["lesson_count"])?$new_info[$k]["lesson_count"]/100:0;

        

        }
        if(in_array(date("d",$start_time),[28,29,30,31])){
            foreach($ass_last_month as $ks=>$vss){
                $userid_list = json_decode($vss["userid_list_last"],true);
                if(empty($userid_list)){
                    $userid_list=[];
                }
               
                $lesson_count_list_old[$ks]=$this->t_manager_info->get_assistant_lesson_count_old($start_time,$end_time,$ks,$userid_list);
            }

            foreach($ass_list as $k=>&$dal){
                $dal["lesson_ratio"]  = !empty(@$ass_last_month[$k]["read_student"])?round(@$lesson_count_list_old[$k]/@$ass_last_month[$k]["read_student"]/100,1):0;
                $dal["lesson_total_old"]      = @$lesson_count_list_old[$k]/100;
            }
        }

     
        // dd($ass_list);
        $ass_list1 = $ass_list2 = $ass_list3 =   $ass_list;
        $ass_leader_arr = $this->t_admin_group_name->get_leader_list(1);
        $ass_group=[];
        foreach($ass_list1 as $key=>$val){
            // echo $key;
            $master_adminid_ass = $this->t_admin_group_user->get_master_adminid_by_adminid($key);
            @$ass_group[$master_adminid_ass]["warning_student"]  += $val["warning_student"];
            @$ass_group[$master_adminid_ass]["read_student"]     += $val["read_student"];
            @$ass_group[$master_adminid_ass]["stop_student"]     += $val["stop_student"];
            @$ass_group[$master_adminid_ass]["all_student"]      += $val["all_student"];
            @$ass_group[$master_adminid_ass]["month_stop_student"]  += $val["month_stop_student"];
            @$ass_group[$master_adminid_ass]["lesson_total"]     += $val["lesson_total"];
            @$ass_group[$master_adminid_ass]["lesson_money"]     += $val["lesson_money"];
            @$ass_group[$master_adminid_ass]["lesson_total_old"]     += $val["lesson_total_old"];
            @$ass_group[$master_adminid_ass]["tran_price"]       += $val["tran_price"];
            @$ass_group[$master_adminid_ass]["renw_price"]       += $val["renw_price"];
            @$ass_group[$master_adminid_ass]["all_price"]        += $val["all_price"];
            @$ass_group[$master_adminid_ass]["renw_student"]     += $val["renw_student"];
            @$ass_group[$master_adminid_ass]["read_student_last"]  += $val["read_student_last"];
            @$ass_group[$master_adminid_ass]["renw_target"]           += $val["renw_target"];
            @$ass_group[$master_adminid_ass]["renw_stu_target"]       += $val["renw_stu_target"];
            @$ass_group[$master_adminid_ass]["kk_suc"]       += $val["kk_suc"];
            @$ass_group[$master_adminid_ass]["kk_require"]       += $val["kk_require"];
            @$ass_group[$master_adminid_ass]["except_num"]       += $val["except_num"];
            @$ass_group[$master_adminid_ass]["except_count"]       += $val["except_count"];
            @$ass_group[$master_adminid_ass]["refund_student"]       += $val["refund_student"];
            @$ass_group[$master_adminid_ass]["new_refund_money"]       += $val["new_refund_money"];
            @$ass_group[$master_adminid_ass]["renw_refund_money"]       += $val["renw_refund_money"];
            @$ass_group[$master_adminid_ass]["new_student"]       += $val["new_student"];
            @$ass_group[$master_adminid_ass]["new_lesson_count"]       += $val["new_lesson_count"];


        }

        foreach($ass_group as $key=>&$v){
            $v["account"] = $this->t_manager_info->get_account($key);
            $v["lesson_ratio"]          = !empty($v["read_student_last"])?round($v["lesson_total_old"]/$v["read_student_last"],1):0;
            $v["lesson_target"]         =$lesson_target;
            $v["lesson_per"]            =!empty($v["lesson_target"])?round($v["lesson_ratio"]/$v["lesson_target"],4)*100:0;
            $v["renw_per"]             =!empty($v["renw_target"])?round($v["all_price"]/$v["renw_target"],4)*100:0;
            $v["renw_stu_per"]            =!empty($v["renw_stu_target"])?round($v["renw_student"]/$v["renw_stu_target"],4)*100:0;

        }
        unset($ass_group[0]);

        $stu_info=[];
        foreach($ass_list2 as $item1){
            @$stu_info["warning_student"]  += @$item1["warning_student"];
            @$stu_info["read_student"]     += @$item1["read_student"];
            @$stu_info["stop_student"]     += @$item1["stop_student"];
            @$stu_info["all_student"]      += @$item1["all_student"];
            @$stu_info["month_stop_student"]  += @$item1["month_stop_student"];
            @$stu_info["lesson_total"]     += @$item1["lesson_total"];
            @$stu_info["lesson_money"]     += @$item1["lesson_money"];
            @$stu_info["lesson_total_old"]     += @$item1["lesson_total_old"];
            @$stu_info["tran_price"]       += @$item1["tran_price"];
            @$stu_info["renw_price"]       += @$item1["renw_price"];
            @$stu_info["all_price"]        += @$item1["all_price"];
            @$stu_info["renw_student"]     += @$item1["renw_student"];
            @$stu_info["read_student_last"]  += @$item1["read_student_last"];
            @$stu_info["kk_suc"]          += @$item1["kk_suc"];
            @$stu_info["kk_require"]          += @$item1["kk_require"];
            @$stu_info["except_count"]          += @$item1["except_count"];
            @$stu_info["except_num"]          += @$item1["except_num"];
            @$stu_info["refund_student"]          += @$item1["refund_student"];
            @$stu_info["new_refund_money"]          += @$item1["new_refund_money"];
            @$stu_info["renw_refund_money"]          += @$item1["renw_refund_money"];
            @$stu_info["new_student"]          += @$item1["new_student"];
            @$stu_info["new_lesson_count"]          += @$item1["new_lesson_count"];
            //$item["lesson_per"]            = !empty($item["lesson_target"])?round($item["lesson_ratio"]/$item["lesson_target"],4)*100:0;
            @$stu_info["renw_target"]           += @$item1["renw_target"];
            //$item["renw_per"]              = !empty($item["renw_target"])?round($item["renw_price"]/$item["renw_target"],4)*100:0;
            @$stu_info["renw_stu_target"]       += @$item1["renw_stu_target"];
            //$item["renw_stu_per"]          = !empty($item["renw_stu_target"])?round($item["renw_student"]/$item["renw_stu_target"],4)*100:0;
        }
        $stu_info["lesson_ratio"]          = !empty($stu_info["read_student_last"])?round($stu_info["lesson_total_old"]/$stu_info["read_student_last"],1):0;
        $stu_info["lesson_target"]         =$lesson_target;
        $stu_info["lesson_per"]            =!empty($stu_info["lesson_target"])?round($stu_info["lesson_ratio"]/$lesson_target,4)*100:0;
        $stu_info["renw_per"]             =!empty($stu_info["renw_target"])?round($stu_info["all_price"]/$stu_info["renw_target"],4)*100:0;
        $stu_info["renw_stu_per"]            =!empty($stu_info["renw_stu_target"])?round($stu_info["renw_student"]/$stu_info["renw_stu_target"],4)*100:0;
       

        \App\Helper\Utils::order_list( $ass_list,"lesson_ratio", 0 );
        \App\Helper\Utils::order_list( $ass_group,"lesson_ratio", 0 );


        return $this->pageView(__METHOD__ ,null, [
            "stu_info" => @$stu_info,
            "ass_list"  =>@$ass_list,
            "ass_group"   =>@$ass_group,
            "ass_list_group" =>@$ass_list_group
        ]);


       
    }




}
