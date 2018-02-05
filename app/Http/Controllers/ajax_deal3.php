<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Input;


class ajax_deal3 extends Controller
{
    use CacheNick;
    use TeaPower;
    use LessonPower;

    public function get_new_seller_student_info() {
        $work_start_time="";
        $adminid= $this->get_account_id();
        $userid_list= $this->get_in_int_list("userid_list");
        $user_admin_assign_time_map= json_decode( $this->get_in_str_val("user_admin_assign_time_map"),true  );
        $now=time(NULL);
        $user_list=[];
        $new_count=0;
        $no_connected_count=0;

        if ( count($userid_list) ==0 || @$userid_list[0] == -1   ) {

        }else{

            $work_start_time=$this->t_admin_work_start_time->get_today_work_start_time($adminid);
            if (!$work_start_time) {
                return $this->output_err("出错,你还没有设置开始工作时间,请重新点击 新例子");
            }

            $user_list=$this->t_seller_student_system_assign_log->get_seller_student_assign_from_type_list($adminid, $userid_list);
            $new_count = $this->t_seller_student_new_b2->get_today_new_count($adminid);
            foreach ($user_list as &$item) {
                $userid=&$item["userid"];
                $admin_assign_time=strtotime( @$user_admin_assign_time_map[$userid] );
                $check_time= max( $work_start_time, $admin_assign_time);
                $show_left_time_flag=false;
                if ($now- $check_time> 3*3600) { //超过3个小时
                    $left_time=6*3600-( $now-$check_time);
                    if ($left_time<0) {
                        $left_time=0;
                    }
                    $hour=floor($left_time/3600);
                    $min=floor(($left_time - $hour*3600)/60  );
                    $time_str= sprintf("%02d:%02d:00",$hour , $min  );
                    $show_left_time_flag=true;
                    $item["left_time_str"]= $time_str;
                }else{
                }
                $item["show_left_time_flag"]= $show_left_time_flag;

                if($item["seller_student_assign_from_type"]==1) {
                    $no_connected_count++;
                }else{
                    // $new_count++;
                }

            }


        }
        $seller_level= $this->t_manager_info->get_seller_level($adminid);

        $hold_config=\App\Helper\Config::get_seller_hold_user_count();
        $max_hold_count = @$hold_config[$seller_level];
        $hold_count=$this->t_seller_student_new_b2->admin_hold_count($adminid);

        return $this->output_succ([
            "user_list"       => $user_list,
            "work_start_time" => $work_start_time ,
            "new_count" => $new_count,
            "no_connected_count" => $no_connected_count,
            "max_hold_count" =>$max_hold_count,
            "hold_count" =>$hold_count,
        ]);
    }

    public function set_work_start_time() {
        $adminid=$this->get_account_id();
        $this->t_admin_work_start_time->add_work_start_time($adminid);
        $this->output_succ();
    }

    //获取一节课所有的信息
    public function get_student_lesson_info_by_lessonid(){
        $lessonid    = $this->get_in_int_val("lessonid");
        $list = $this->t_lesson_info_b3->get_student_all_lesson_info(-1,0,0,$lessonid);
        $list =  $list[0];
        $userid= $list["userid"];
        $all_lesson_list = $this->t_lesson_info_b3->get_student_all_lesson_info($userid,0,0);
        $all_lesson=[];
        foreach($all_lesson_list as $k=>$val){
            $all_lesson[$val["lessonid"]] = $k+1;
        }
        $list["lesson_num"] = $all_lesson[$lessonid];
        $list["lesson_time"] = date("Y-m-d H:i",$list["lesson_start"])."~".date("H:i",$list["lesson_end"]);
        $list["subject_str"] = E\Esubject::get_desc($list["subject"]);
        $list["grade_str"] = E\Egrade::get_desc($list["grade"]);
        if(empty($list["tea_cw_upload_time"]) || $list["tea_cw_upload_time"]>=$list["lesson_start"]){
            $list["cw_status_str"]="未上传";
            $list["preview_status_str"]="—";
        }else{
            $list["cw_status_str"]="已上传";
            $list["preview_status_str"]= E\Eboolean::get_desc($list["preview_status"]);
        }
        $list["cw_url"] = \App\Helper\Utils::gen_download_url($list["tea_cw_url"]);



        //课堂登录情况
        $page_info = $this->get_in_page_info();
        $login_list = $this->t_lesson_info_b3->get_classroom_situation_info($page_info,-1,0,0,-1,-1,1,$lessonid);
        $login = @$login_list["list"][0];
        $login_time = $this->t_lesson_info_b3->get_classroom_situation_info($page_info,-1,0,0,-1,-1,2,$lessonid);
        $login_time = @$login_time[$lessonid];


        if($list["lesson_status"]<2){
            $list["tea_login_num"] = "—";
            $list["stu_login_num"] = "—";
            $list["parent_login_num"] = "—";
            $list["stu_praise"] = "—";
            $list["tea_attend_str"] = "—";
            $list["stu_attend_str"] = "—";
        }else{

            if(in_array($list["lesson_cancel_reason_type"],[2,12,21]) && $list["confirm_flag"]>=2){
                $list["tea_attend_str"] = E\Elesson_cancel_reason_type::get_desc($list["lesson_cancel_reason_type"]);
                $list["stu_attend_str"] = "—";
                $list["tea_login_num"] = "—";
                $list["stu_login_num"] = "—";
                $list["parent_login_num"] = "—";
                $list["stu_praise"] = "—";

            }elseif(in_array($list["lesson_cancel_reason_type"],[1,11,20]) && $list["confirm_flag"]>=2){
                $list["stu_attend_str"] = E\Elesson_cancel_reason_type::get_desc($list["lesson_cancel_reason_type"]);
                $list["tea_attend_str"] = "—";
                $list["tea_login_num"] = "—";
                $list["stu_login_num"] = "—";
                $list["parent_login_num"] = "—";
                $list["stu_praise"] = "—";


            }else{
                // $list["stu_attend_str"] = $list["tea_attend_str"] =E\Elesson_cancel_reason_type::get_desc($list["lesson_cancel_reason_type"]);
                $stu_login_time = @$login_time["stu_login_time"];
                $stu_logout_time = @$login_time["stu_logout_time"];
                $tea_login_time = @$login_time["tea_login_time"];
                $tea_logout_time = @$login_time["tea_logout_time"];
                $lesson_start = ($list["lesson_start"]+59);
                $lesson_end = $list["lesson_end"];
                if($stu_login_time>$lesson_start && $stu_logout_time<$lesson_end){
                    $list["stu_attend_str"]="迟到且早退";
                }elseif($stu_login_time>$lesson_start){
                    $list["stu_attend_str"]="迟到";
                }elseif($stu_logout_time<$lesson_end){
                    $list["stu_attend_str"]="早退";
                }else{
                    $list["stu_attend_str"]="正常";
                }
                if($tea_login_time>$lesson_start && $tea_logout_time<$lesson_end){
                    $list["tea_attend_str"]="迟到且早退";
                }elseif($tea_login_time>$lesson_start){
                    $list["tea_attend_str"]="迟到";
                }elseif($tea_logout_time<$lesson_end){
                    $list["tea_attend_str"]="早退";
                }else{
                    $list["tea_attend_str"]="正常";
                }
                $list["tea_login_num"] = @$login["tea_login_num"];
                $list["stu_login_num"] = @$login["stu_login_num"];
                $list["parent_login_num"] =@$login["parent_login_num"];


            }
        }


        $list['stu_intro']   = json_decode($list['stu_performance'],true);
        $list['stu_point_performance']='';
        if(isset($list['stu_intro']['point_note_list']) && is_array($list['stu_intro']['point_note_list'])){
            foreach(@$list['stu_intro']['point_note_list'] as $val){
                $list['stu_point_performance'].=$val['point_name'].":".$val['point_stu_desc']."。";
            }
        }
        if(isset($list['stu_intro']['stu_comment']) && $list['stu_intro']['stu_comment']!=''){
            if(is_array($list['stu_intro']['stu_comment'])){
                $str = json_encode($list['stu_intro']['stu_comment']);
                $str = $this->get_test_lesson_comment_str($str);
            }else{
                $str = $list['stu_intro']['stu_comment'];
            }
            //   $str = $this->get_test_lesson_comment_str($str);
            $list['stu_point_performance'].=PHP_EOL."总体评价:".$str;
        }
        $list['stu_intro']="";
        if(empty($list["teacher_comment"])){
            $list["teacher_comment"]="—";
        }
        if(empty($list["stu_score"])){
            $list["stu_score"]="—";
        }

        $list["issue_url_str"] = \App\Helper\Utils::gen_download_url($list["issue_url"]);
        $list["finish_url_str"] = \App\Helper\Utils::gen_download_url($list["finish_url"]);
        $list["check_url_str"] = \App\Helper\Utils::gen_download_url($list["check_url"]);
        if(empty($list["issue_url"])){
            $list["issue_url_str"]="";
            $list["finish_url_str"]="";
            $list["check_url_str"]="";
            $list["issue_flag"]="未上传";
            $list["download_flag"]= $list["commit_flag"]= $list["check_flag"]="—";
            $list["stu_check_flag"]="—";

        }else{
            $list["issue_flag"]="已上传";
            // $list["download_flag"]="—";
            if($list["work_status"]>=2){
                $list["commit_flag"]="已提交";
            }else{
                $list["commit_flag"]="未提交";
            }
            if($list["work_status"]>=3){
                $list["check_flag"]="是";
            }else{
                $list["check_flag"]="否";
            }
            if($list["stu_check_time"]>0){
                $list["stu_check_flag"]="已查看"; 
            }else{
                $list["stu_check_flag"]="未查看"; 
            }
            if($list["download_time"]>0){
                $list["download_flag"]="已下载"; 
            }else{
                $list["download_flag"]="未下载"; 
            }



        }
        if($list["lesson_start"]<strtotime("2018-01-26")){
            $list["stu_check_flag"]=$list["download_flag"]="—";
        }

        return $this->output_succ(["data"=>$list]);
    }


    //重置助教薪资信息(测试版本)
    public function reset_assisatnt_performance_data(){
        $adminid    = $this->get_in_int_val("adminid");
        $type    = $this->get_in_int_val("type");
        $start_time    = strtotime($this->get_in_str_val("start_time"));
        $end_time = strtotime("+1 months",$start_time);
        $last_month = strtotime(date('Y-m-01',$start_time-100));
        $ass_last_month = $this->t_month_ass_student_info->get_ass_month_info($last_month);
        $ass_current_month = $this->t_month_ass_student_info->get_ass_month_info($start_time);
        $list = $ass_current_month[$adminid];
        $account = $this->t_manager_info->get_account($adminid);
        $assistantid = $list["assistantid"];
        $update_arr=[];
        //回访信息修改
        //在读学员/在册学员/停课学员信息重置
        if($type==1){
            $stu_info_all = $this->t_student_info->get_ass_stu_info_new($adminid);
            $userid_list = $this->t_student_info->get_read_student_ass_info(0,$assistantid);//在读学员名单

            $registered_userid_list = $this->t_student_info->get_read_student_ass_info(-2,$assistantid);//在册学员名单
            $stop_userid_list = $this->t_student_info->get_read_student_ass_info(2,$assistantid);//停课学员名单
            $end_stu_info_new  = $this->t_student_info->get_end_class_stu_info($start_time,$end_time,$assistantid);
            $update_arr=[
                // "first_lesson_stu_list" =>$first_subject_list,
                "read_student"          =>@$stu_info_all[$adminid]["read_count"],
                "stop_student"          =>@$stu_info_all[$adminid]["stop_count"],
                "userid_list"           =>@$userid_list[$adminid],
                "end_stu_num"           =>@$end_stu_info_new[$adminid]["num"],
                "stop_student_list"       =>@$stop_userid_list[$adminid],
                "registered_student_list" =>@$registered_userid_list[$adminid],               
                "all_student"             =>@$stu_info_all[$adminid]["all_count"]
            ];
        }elseif($type==2){
            $first_subject_list = $list["first_lesson_stu_list"];
            $userid_list_str = $list["userid_list"];
            $registered_student_list_str = $list["registered_student_list"];
            $revisit_reword_per = $this->get_ass_revisit_reword_value($account,$adminid,$start_time,$end_time,$first_subject_list, $userid_list_str,$registered_student_list_str);
            $update_arr=[
                "revisit_reword_per"    =>$revisit_reword_per*100,
            ];

        }elseif($type==4){
            list($performance_cr_new_list,$performance_cr_renew_list,$performance_cc_tran_list)= $this->get_ass_order_list_performance($start_time,$end_time);
            $update_arr=[
               "performance_cc_tran_num"  =>@$performance_cc_tran_list[$adminid]["num"],
               "performance_cc_tran_money"=>@$performance_cc_tran_list[$adminid]["money"],
               "performance_cr_renew_num" =>@$performance_cr_renew_list[$adminid]["num"],
               "performance_cr_renew_money" =>@$performance_cr_renew_list[$adminid]["money"],
                "performance_cr_new_num" =>@$performance_cr_new_list[$adminid]["num"],
                "performance_cr_new_money" =>@$performance_cr_new_list[$adminid]["money"]
            ];

        }elseif($type==3){
            list($first_week,$last_week,$n) = $this->get_seller_week_info($start_time, $end_time);//销售月拆解
            $registered_student_num=$this->get_register_student_list($first_week,$n);//销售月助教在册学生总数获取
            $seller_month_lesson_count = $this->t_manager_info->get_assistant_lesson_count_info($first_week,$last_week+7*86400,$assistantid);//销售月总课时

            $seller_week_stu_num = round(@$registered_student_num[$adminid]);//销售月周平均学生数
            $seller_month_lesson_count = @$seller_month_lesson_count[$adminid]["lesson_count"];//销售月总课时
            $registered_student_list_last = @$ass_last_month[$adminid]["registered_student_list"];
            list($kpi_lesson_count_finish_per,$estimate_month_lesson_count)= $this->get_seller_month_lesson_count_use_info($registered_student_list_last,$seller_week_stu_num,$n,$seller_month_lesson_count);


            //月初预估课时数据补充
            if(empty($estimate_month_lesson_count)){
                $estimate_month_lesson_count=100;
            }

            $update_arr =  [
                "seller_week_stu_num"   =>$seller_week_stu_num,
                "seller_month_lesson_count"=>$seller_month_lesson_count,
                "kpi_lesson_count_finish_per"=>$kpi_lesson_count_finish_per*100,
                "estimate_month_lesson_count" =>$estimate_month_lesson_count,//临时更新一次(月初生成)
            ];

        }elseif($type==5){
            $kk_suc= $this->t_test_lesson_subject->get_ass_kk_tongji_info($start_time,$end_time,$adminid);
            $kk_num = @$kk_suc[$adminid]["lesson_count"];

            $update_arr =  [
                "kk_num"   =>$kk_num,
            ];

        }elseif($type==6){
            $first_subject_list = $this->get_ass_stu_first_lesson_subject_info($start_time,$end_time,$assistantid);
            if(isset($first_subject_list[$adminid]) && !empty($first_subject_list[$adminid])){
                $first_subject_list = json_encode(@$first_subject_list[$adminid]);
            }else{
                $first_subject_list="";
            }
            $update_arr=[
                "first_lesson_stu_list" =>$first_subject_list,
            ];

            $list  = $this->t_lesson_info_b3->get_teacher_student_first_subject_info($start_time,$end_time,-1,$assistantid);
            $i=0;
            foreach($list as $val){
                $id = $val["id"];
                if($id>0){
                    $info =$this->t_teacher_record_list->get_record_info($id);
                    $lessonid =$this->t_teacher_record_list->get_train_lessonid($id);
                    $info .= "lessonid".$lessonid." change to".$val["lessonid"].",时间:".time().";";
                    $this->t_teacher_record_list->field_update_list($id,[
                        "lesson_time" => $val["lesson_start"],
                        "record_info" => $info,
                        "train_lessonid"=>$val["lessonid"]
                    ]);

                }else{
                    $add_time = time()+$i;
                    $this->t_teacher_record_list->row_insert([
                        "teacherid"      => $val["teacherid"],
                        "userid"         => $val["userid"],
                        "lesson_subject" => $val["subject"],
                        "lesson_time"    => $val["lesson_start"],
                        "train_lessonid" => $val["lessonid"],
                        "add_time"       => $add_time,
                        "type"           => 18
                    ]);
                    $i++;
                }
            }


        }




        // $revisit_reword_per = $this->get_ass_revisit_reword_value($account,$adminid,$start_time,$end_time,$first_subject_list,@$userid_list[$adminid],@$registered_userid_list[$adminid]);
        // $kk_suc= $this->t_test_lesson_subject->get_ass_kk_tongji_info($start_time,$end_time,$adminid);
        // $kk_num = @$kk_suc[$adminid]["lesson_count"];
        // list($performance_cr_new_list,$performance_cr_renew_list,$performance_cc_tran_list)= $this->get_ass_order_list_performance($start_time,$end_time);
        // list($first_week,$last_week,$n) = $this->get_seller_week_info($start_time, $end_time);//销售月拆解
        // $registered_student_num=$this->get_register_student_list($first_week,$n);//销售月助教在册学生总数获取
        // $seller_month_lesson_count = $this->t_manager_info->get_assistant_lesson_count_info($first_week,$last_week+7*86400,$assistantid);//销售月总课时
        // $end_stu_info_new  = $this->t_student_info->get_end_class_stu_info($start_time,$end_time,$assistantid);

        // $seller_week_stu_num = round(@$registered_student_num[$adminid]);//销售月周平均学生数
        // $seller_month_lesson_count = @$seller_month_lesson_count[$adminid]["lesson_count"];//销售月总课时
        // $registered_student_list_last = @$ass_last_month[$adminid]["registered_student_list"];
        // list($kpi_lesson_count_finish_per,$estimate_month_lesson_count)= $this->get_seller_month_lesson_count_use_info($registered_student_list_last,$seller_week_stu_num,$n,$seller_month_lesson_count);

        // $revisit_reword_per = $this->get_ass_revisit_reword_value($account,$adminid,$start_time,$end_time,$first_subject_list,@$userid_list[$adminid],@$registered_userid_list[$adminid]);
        // $kk_suc= $this->t_test_lesson_subject->get_ass_kk_tongji_info($start_time,$end_time,$adminid);
        // $kk_num = @$kk_suc[$adminid]["lesson_count"];
        // list($performance_cr_new_list,$performance_cr_renew_list,$performance_cc_tran_list)= $this->get_ass_order_list_performance($start_time,$end_time);
        // list($first_week,$last_week,$n) = $this->get_seller_week_info($start_time, $end_time);//销售月拆解
        // $registered_student_num=$this->get_register_student_list($first_week,$n);//销售月助教在册学生总数获取
        // $seller_month_lesson_count = $this->t_manager_info->get_assistant_lesson_count_info($first_week,$last_week+7*86400,$assistantid);//销售月总课时

        // $seller_week_stu_num = round(@$registered_student_num[$adminid]);//销售月周平均学生数
        // $seller_month_lesson_count = @$seller_month_lesson_count[$adminid]["lesson_count"];//销售月总课时
        // $registered_student_list_last = @$ass_last_month[$adminid]["registered_student_list"];
        // list($kpi_lesson_count_finish_per,$estimate_month_lesson_count)= $this->get_seller_month_lesson_count_use_info($registered_student_list_last,$seller_week_stu_num,$n,$seller_month_lesson_count);


        // //月初预估课时数据补充
        // if(empty($estimate_month_lesson_count)){
        //     $estimate_month_lesson_count=100;
        // }

        // $update_arr =  [
        //     "first_lesson_stu_list" =>$first_subject_list,
        //     "revisit_reword_per"    =>$revisit_reword_per*100,
        //     "seller_week_stu_num"   =>$seller_week_stu_num,
        //     "seller_month_lesson_count"=>$seller_month_lesson_count,
        //     "kpi_lesson_count_finish_per"=>$kpi_lesson_count_finish_per*100,
        //     "estimate_month_lesson_count" =>$estimate_month_lesson_count,//临时更新一次(月初生成)
        //     "performance_cc_tran_num"  =>@$performance_cc_tran_list["performance_cc_tran_num"],
        //     "performance_cc_tran_money"=>@$performance_cc_tran_list["performance_cc_tran_money"],
        //     "performance_cr_renew_num" =>@$performance_cr_renew_list["performance_cr_renew_num"],
        //     "performance_cr_renew_money" =>@$performance_cr_renew_list["performance_cr_renew_money"],
        //     "performance_cr_new_num" =>@$performance_cr_new_list[$adminid]["performance_cr_new_num"],
        //     "performance_cr_new_money" =>@$performance_cr_new_list[$adminid]["performance_cr_new_money"],
        //     "read_student"          =>@$stu_info_all[$adminid]["read_count"],
        //     "stop_student"          =>@$stu_info_all[$adminid]["stop_count"],
        //     // "all_student"           =>$item["all_student"],
        //     // "month_stop_student"    =>$item["month_stop_student"],
        //     // "warning_student"       =>$item["warning_student"],
        //     // "lesson_total"          =>$item["lesson_total"],
        //     // "lesson_ratio"          =>$item["lesson_ratio"],
        //     // "renw_price"            =>$item["renw_price"],
        //     // "renw_student"          =>$item["renw_student"],
        //     // "tran_price"            =>$item["tran_price"],
        //     "kk_num"                =>$kk_num,
        //     "userid_list"           =>@$userid_list[$adminid],
        //     // "refund_student"        =>$item["refund_student"],
        //     // "new_refund_money"      =>$item["new_refund_money"],
        //     // "renw_refund_money"     =>$item["renw_refund_money"],
        //     // "lesson_total_old"      =>$item["lesson_total_old"],
        //     // "read_student_new"      =>$item["read_student_new"],
        //     // "all_student_new"       =>$item["all_student_new"],

        //     // "lesson_money"          =>$item["lesson_money"],
        //     // "new_student"           =>$item["new_student"],
        //     // "new_lesson_count"      =>$item["new_lesson_count"],
        //     "end_stu_num"           =>@$end_stu_info_new[$adminid],
        //     // "lesson_student"        =>$item["lesson_student"],
        //     // "revisit_target"        =>$item["revisit_target"],
        //     // "revisit_real"          => $item["revisit_real"],
        //     // "first_revisit_num"     => $item["first_revisit_num"],
        //     // "un_first_revisit_num"  => $item["un_first_revisit_num"],
        //     // "refund_score"          => $item["refund_score"],
        //     // "lesson_price_avg"      => $item["lesson_price_avg"],
        //     // "student_finsh"         =>$item["student_finish"],
        //     // "tran_num"              =>$item["tran_num"],
        //     // "cc_tran_num"           =>$item["cc_tran_num"],
        //     // "cc_tran_money"           =>$item["cc_tran_money"],

        //     "stop_student_list"       =>@$stop_userid_list[$adminid],
        //     "registered_student_list" =>@$registered_userid_list[$adminid],
        //     // "all_ass_stu_num"         =>$item["all_ass_stu_num"],
        //     // "ass_refund_money"        => $refund_money,
        // ];
        $this->t_month_ass_student_info->get_field_update_arr($adminid,$start_time,1,$update_arr);
        return $this->output_succ();


    }

    //新版薪资 助教续费新签合同/销售转介绍合同 金额/个数计算
    public function get_ass_order_list_performance($start_time,$end_time){
        $ass_order_info = $this->t_order_info->get_assistant_performance_order_info($start_time,$end_time);

        $ass_order_period_list = $this->t_order_info->get_ass_self_order_period_money($start_time,$end_time);//助教自签合同金额(分期80%计算)
        $renew_list=$new_list=[];
        foreach($ass_order_info as $val){
            $contract_type = $val["contract_type"];
            $orderid = $val["orderid"];
            $userid = $val["userid"];
            $price = $val["price"];
            $uid = $val["uid"];
            $real_refund = $val["real_refund"];
            if($contract_type==0){
                $new_list[$orderid]["uid"] = $uid;
                $new_list[$orderid]["userid"] = $userid;
                $new_list[$orderid]["price"] = $price;
                $new_list[$orderid]["orderid"] = $orderid;
                @$new_list[$orderid]["real_refund"] += $real_refund;
            }elseif($contract_type==3){
                $renew_list[$orderid]["uid"] = $uid;
                $renew_list[$orderid]["userid"] = $userid;
                $renew_list[$orderid]["price"] = $price;
                $renew_list[$orderid]["orderid"] = $orderid;
                @$renew_list[$orderid]["real_refund"] += $real_refund;
            }
        }
        $ass_renew_info = $ass_new_info=[];
        foreach($renew_list as $val){
            $orderid = $val["orderid"];
            $userid = $val["userid"];
            //  $price = $val["price"];
            $price = @$ass_order_period_list[$orderid]["reset_money"];
            $uid = $val["uid"];
            $real_refund = $val["real_refund"];
            if(!isset($ass_renew_info[$uid]["user_list"][$userid])){
                $ass_renew_info[$uid]["user_list"][$userid]=$userid;
                @$ass_renew_info[$uid]["num"] +=1;
            }
            @$ass_renew_info[$uid]["money"] += $price-$real_refund;

        }
        foreach($new_list as $val){
            $orderid = $val["orderid"];
            $userid = $val["userid"];
            // $price = $val["price"];
            $price = @$ass_order_period_list[$orderid]["reset_money"];
            $uid = $val["uid"];
            $real_refund = $val["real_refund"];
            if(!isset($ass_new_info[$uid]["user_list"][$userid])){
                $ass_new_info[$uid]["user_list"][$userid]=$userid;
                @$ass_new_info[$uid]["num"] +=1;
            }
            @$ass_new_info[$uid]["money"] += $price-$real_refund;

        }


        //获取销售转介绍合同信息
        $cc_order_list = $this->t_order_info->get_seller_tran_order_info($start_time,$end_time);
        $cc_order_period_list = $this->t_order_info->get_seller_tran_order_period_money($start_time,$end_time);//CC转介绍合同金额(分期80%计算)

        $new_tran_list=[];
        foreach($cc_order_list as $val){
            $orderid = $val["orderid"];
            $userid = $val["userid"];
            $price = $val["price"];
            $uid = $val["uid"];
            $real_refund = $val["real_refund"];
            $new_tran_list[$orderid]["uid"] = $uid;
            $new_tran_list[$orderid]["userid"] = $userid;
            $new_tran_list[$orderid]["price"] = $price;
            $new_tran_list[$orderid]["orderid"] = $orderid;
            @$new_tran_list[$orderid]["real_refund"] += $real_refund;

        }
        $ass_tran_info =[];
        foreach($new_tran_list as $val){
            $orderid = $val["orderid"];
            $userid = $val["userid"];
            // $price = $val["price"];
            $price = @$cc_order_period_list[$orderid]["reset_money"];
            $uid = $val["uid"];
            $real_refund = $val["real_refund"];
            if(!isset($ass_tran_info[$uid]["user_list"][$userid])){
                $ass_tran_info[$uid]["user_list"][$userid]=$userid;
                @$ass_tran_info[$uid]["num"] +=1;
            }
            @$ass_tran_info[$uid]["money"] += $price-$real_refund;

        }

        return [$ass_new_info,$ass_renew_info,$ass_tran_info];

    }

    //每周助教在册学生数量获取
    public function get_register_student_list($first_week,$n){
        $registered_student_num=[];
        for($i=0;$i<$n;$i++){
            $week = $first_week+$i*7*86400;
            $week_edate = $week+7*86400;
            $week_info = $this->t_ass_weekly_info->get_all_info($week);
            foreach($week_info as $val){
                @$registered_student_num[$val["adminid"]] +=@$week_info[$val["adminid"]]["registered_student_num"];
            }
        }
        return $registered_student_num;

    }

    //助教销售月课时消耗相关数据获取
    public function get_seller_month_lesson_count_use_info($registered_student_list,$seller_stu_num,$n,$seller_lesson_count){

        //平均学员数(销售月) $seller_stu_num
        //销售月周数 $n
        //销售月课耗 $seller_lesson_count

        /*课时消耗达成率*/
        if($registered_student_list){
            $registered_student_arr = json_decode($registered_student_list,true);
            $last_stu_num = count($registered_student_arr);//月初在册人员数
            $last_lesson_total = $this->t_week_regular_course->get_lesson_count_all($registered_student_arr);//月初周总课时消耗数
            $estimate_month_lesson_count =$n*$last_lesson_total/$last_stu_num;  //预估月课时消耗总量
        }else{
            $registered_student_arr=[];
            $estimate_month_lesson_count =100;
        }

        if(empty($seller_stu_num)){
            $lesson_count_finish_per=0;
        }else{
            $lesson_count_finish_per= round($seller_lesson_count/$seller_stu_num/$estimate_month_lesson_count*100,2);
        }

        //算出kpi中课时消耗达成率的情况
        if($lesson_count_finish_per>=70){
            $kpi_lesson_count_finish_per = 0.4;
        }else{
            $kpi_lesson_count_finish_per=0;
        }

        return array($kpi_lesson_count_finish_per,$estimate_month_lesson_count);

    }

    //回访绩效分值计算
    public function get_ass_revisit_reword_value($account,$adminid,$start_time,$end_time,$first_lesson_stu_list,$read_student_list,$registered_student_list){
        $month_half = $start_time+15*86400;

        /*回访*/
        $revisit_reword_per = 0.2;//初始值

        //先看第一课回访信息
        if($first_lesson_stu_list){
            $first_lesson_stu_arr = json_decode($first_lesson_stu_list,true);
            foreach($first_lesson_stu_arr as $val){
                $first_userid = $val["userid"];
                $lesson_start = $val["lesson_start"];
                $revisit_end = $lesson_start+86400;
                $revisit_num = $this->t_revisit_info->get_ass_revisit_info_personal($first_userid,$lesson_start,$revisit_end,$account,5);
                if($revisit_num <=0){
                    $revisit_reword_per -=0.05;
                }
                if($revisit_reword_per <=0){
                    break;
                }


            }
        }

        //当前在读学员
        if($read_student_list && $revisit_reword_per >0){
            $read_student_arr = json_decode($read_student_list,true);
            foreach($read_student_arr as $val){
                //先检查是否是本月才开始上课的(获取各科目常规课最早上课时间)
                $first_regular_lesson_time = $this->t_lesson_info_b3->get_stu_first_regular_lesson_time($val);
                $assign_time = $this->t_student_info->get_ass_assign_time($val);

                //检查本月是否上过课
                $month_lesson_flag = $this->t_lesson_info_b3->check_have_lesson_stu($val,$start_time,$end_time);

                if($first_regular_lesson_time>0 && $first_regular_lesson_time<$month_half){
                    if($assign_time < $month_half){
                        $revisit_num = $this->t_revisit_info->get_ass_revisit_info_personal($val,$start_time,$end_time,$account,-2);
                        if($month_lesson_flag==1){
                            if($revisit_num <2){
                                $revisit_reword_per -=0.05*(2-$revisit_num);
                            }

                        }else{
                            if($revisit_num <1){
                                $revisit_reword_per -=0.05;
                            }

                        }
                    }elseif($assign_time>=$month_half && $assign_time <$end_time){
                        $revisit_num = $this->t_revisit_info->get_ass_revisit_info_personal($val,$month_half,$end_time,$account,-2);
                        if($revisit_num <1){
                            $revisit_reword_per -=0.05;
                        }

                    }
                }elseif($first_regular_lesson_time>0 && $first_regular_lesson_time>=$month_half &&  $first_regular_lesson_time<=$end_time){
                    $revisit_num = $this->t_revisit_info->get_ass_revisit_info_personal($val,$month_half,$end_time,$account,-2);
                    if($revisit_num <1){
                        $revisit_reword_per -=0.05;
                    }

                }
                if($revisit_reword_per <=0){
                    break;
                }
            }
        }
        if($revisit_reword_per >0){
            //检查本月带过的历史学生
            $history_list = $this->t_ass_stu_change_list->get_ass_history_list($adminid,$start_time,$end_time);

            foreach($history_list as $val){
                //检查本月是否上过课
                $month_lesson_flag = $this->t_lesson_info_b3->check_have_lesson_stu($val["userid"],$start_time,$end_time);

                $add_time = $val["add_time"];
                if($add_time<$month_half){
                    $revisit_num = $this->t_revisit_info->get_ass_revisit_info_personal($val["userid"],$start_time,$month_half,$account,-2);
                    if($revisit_num <1){
                        $revisit_reword_per -=0.05;
                    }

                }else{
                    $assign_time = $val["assign_ass_time"];
                    if($assign_time <$month_half){
                        $revisit_num = $this->t_revisit_info->get_ass_revisit_info_personal($val["userid"],$start_time,$end_time,$account,-2);
                        if($month_lesson_flag==1){
                            if($revisit_num <2){
                                $revisit_reword_per -=0.05*(2-$revisit_num);
                            }

                        }else{
                            if($revisit_num <1){
                                $revisit_reword_per -=0.05;
                            }

                        }

                    }else{
                        $revisit_num = $this->t_revisit_info->get_ass_revisit_info_personal($val["userid"],$month_half,$end_time,$account,-2);
                        if($revisit_num <1){
                            $revisit_reword_per -=0.05;
                        }

                    }
                }
                if($revisit_reword_per <=0){
                    break;
                }


            }

        }

        if($revisit_reword_per>0 && $registered_student_list){
            //检查未结课学生回访状态(需要剔除在读学员)
            $registered_student_arr = json_decode($registered_student_list,true);
            if($read_student_list){
                $read_student_arr = json_decode($read_student_list,true);
                $registered_student_arr = array_diff($registered_student_arr, $read_student_arr);//获得去除在读学员的数组
            }

            foreach($registered_student_arr as $val){
                //先检查是否是本月才开始上课的(获取各科目常规课最早上课时间)
                $first_regular_lesson_time = $this->t_lesson_info_b3->get_stu_first_regular_lesson_time($val);
                $assign_time = $this->t_student_info->get_ass_assign_time($val);


                if($first_regular_lesson_time>0 && $first_regular_lesson_time<$month_half){
                    if($assign_time < $month_half){
                        $revisit_num = $this->t_revisit_info->get_ass_revisit_info_personal($val,$start_time,$end_time,$account,-2);
                        if($revisit_num <1){
                            $revisit_reword_per -=0.05;
                        }

                    }elseif($assign_time>=$month_half && $assign_time <$end_time){
                        $revisit_num = $this->t_revisit_info->get_ass_revisit_info_personal($val,$month_half,$end_time,$account,-2);
                        if($revisit_num <1){
                            $revisit_reword_per -=0.05;
                        }

                    }
                }elseif($first_regular_lesson_time>0 && $first_regular_lesson_time>=$month_half &&  $first_regular_lesson_time<=$end_time){
                    $revisit_num = $this->t_revisit_info->get_ass_revisit_info_personal($val,$month_half,$end_time,$account,-2);
                    if($revisit_num <1){
                        $revisit_reword_per -=0.05;
                    }

                }
                if($revisit_reword_per <=0){
                    break;
                }




            }


        }
        if($revisit_reword_per <0){
            $revisit_reword_per=0;
        }

        return $revisit_reword_per;
    }

    //生成助教学生第一次课信息(按科目)
    public function get_ass_stu_first_lesson_subject_info($start_time,$end_time,$assistantid){

        $regular_lesson_list = $this->t_lesson_info_b3->get_stu_first_lesson_time_by_subject(-1,$start_time,$end_time,$assistantid);
        $arr_first=[];
        foreach($regular_lesson_list as $vvoo){
            $arr_first[$vvoo["uid"]][]=$vvoo;
        }
        return  $arr_first;

    }



    //编辑助教薪资数据(测试)
    public function update_ass_performace_data(){
        $adminid    = $this->get_in_int_val("adminid");
        $start_time    = strtotime($this->get_in_str_val("start_time"));
        $end_time = strtotime("+1 months",$start_time);
        $last_month = strtotime(date('Y-m-01',$start_time-100));
        $register_num = $this->get_in_int_val("register_num");
        $seller_stu_num = $this->get_in_str_val("seller_stu_num");
        $estimate_month_lesson_count = $this->get_in_str_val("estimate_month_lesson_count");

        $seller_month_lesson_count =  $this->get_in_str_val("seller_month_lesson_count");
        $performance_cr_renew_money =  $this->get_in_str_val("performance_cr_renew_money");
        $performance_cc_tran_money =  $this->get_in_str_val("performance_cc_tran_money");
        $stop_student = $this->get_in_int_val("stop_student");
        $kk_num       = $this->get_in_int_val("kk_num");
        $end_stu_num = $this->get_in_int_val("end_stu_num");
        $performance_cc_tran_num = $this->get_in_int_val("performance_cc_tran_num");

        $this->t_month_ass_student_info->get_field_update_arr($adminid,$start_time,1,[
            "seller_week_stu_num"          => $seller_stu_num,
            "estimate_month_lesson_count"  =>$estimate_month_lesson_count*100,
            "seller_month_lesson_count"    =>$seller_month_lesson_count*100,
            "performance_cr_renew_money"   =>$performance_cr_renew_money*100,
            "performance_cc_tran_money"    =>$performance_cc_tran_money*100,
            "stop_student"                 =>$stop_student,
            "kk_num"                       =>$kk_num,
            "end_stu_num"                  =>$end_stu_num,
            "performance_cc_tran_num"      =>$performance_cc_tran_num,
        ]);
        $adminid_exist = $this->t_month_ass_student_info->get_ass_month_info($last_month,$adminid,1);
        if($adminid_exist){
            $this->t_month_ass_student_info->get_field_update_arr($adminid,$last_month,1,[
                "all_student" =>$register_num
            ]);
        }else{
            $update_arr["adminid"] =$adminid;
            $update_arr["month"]   =$last_month;
            $update_arr["kpi_type"]   =1;
            $update_arr["all_student"]   =$register_num;
            $this->t_month_ass_student_info->row_insert($update_arr);
        }
        return $this->output_succ();

    }


    //测试版,修改回访时间以及是否电话回访
    public function update_revisit_info_test(){
        $revisit_time = $this->get_in_int_val("revisit_time");
        $userid = $this->get_in_int_val("userid");
        $time = strtotime($this->get_in_str_val("time"));
        $call_flag = $this->get_in_int_val("call_flag");
        $type = $this->get_in_int_val("type");
        if($type==1){         
            $this->t_revisit_info->field_update_list_2($userid,$revisit_time,[
                "revisit_time"  =>$time
            ]);
        }else{
            $this->t_revisit_info->field_update_list_2($userid,$revisit_time,[
                "revisit_time"  =>$time,
                "call_phone_id" =>$call_flag
            ]);

        }
        return $this->output_succ();
    }


    //老师晋升审批数量详情获取
    public function get_teacher_advance_require_detail_info(){
        $start_time = $this->get_in_int_val("start_time");
        $list = $this->t_teacher_advance_list->get_teacher_advance_require_detail_data($start_time);
        return $this->output_succ(["data"=>$list]);

    }

    //上传老师名片
    public function upload_teacher_callcard_info(){
        $teacherid = $this->get_in_int_val("teacherid");
        $callcard_url = $this->get_in_str_val("callcard_url");
        $domain = config('admin')['qiniu']['public']['url'];
        if(!$callcard_url){
            return $this->output_err("请上传文件");
        }
        $url =  $domain."/".$callcard_url;
        $this->t_teacher_info->field_update_list($teacherid,[
            "callcard_url"=>$url 
        ]);
        return $this->output_succ();

 
    }

    //新增测试助教(薪资)
    public function add_ass_performance(){
        $assistantid    = $this->get_in_int_val("assistantid");
        $start_time    = strtotime($this->get_in_str_val("start_time"));
        $uid = $this->t_manager_info->get_ass_adminid($assistantid);
        $adminid_exist = $this->t_month_ass_student_info->get_ass_month_info($start_time,$uid,1);
        $update_arr=[];
        if($adminid_exist){
        }else{
            $update_arr["adminid"] =$uid;
            $update_arr["month"]   =$start_time;
            $update_arr["kpi_type"]   =1;
            $this->t_month_ass_student_info->row_insert($update_arr);
            $db_groupid=$this->t_admin_group_user->get_groupid_by_adminid(-1,$uid);
           
            if(!$db_groupid){            
                $this->t_admin_group_user->row_insert([
                    "groupid"   => 38,
                    "adminid"   => $uid,
                ]);
            }
            return $this->output_succ();
            
        }
        return $this->output_succ();

        

    }

    //批量删除课程
    public function cancel_lesson_list(){
        $lessonid_list    = $this->get_in_str_val("lessonid_list");
        $list = json_decode($lessonid_list,true);
        foreach($list as $val){
            $lessonid = $val;           
            $ret = $this->t_lesson_info_b2->cancel_lesson_no_start($lessonid);
            if($ret){
                $this->add_cancel_lesson_operate_info($lessonid);
            }

        }
        return $this->output_succ();

    }

}