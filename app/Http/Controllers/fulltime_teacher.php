<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use \App\Enums as E;
use \App\Helper\Config;
use Illuminate\Support\Facades\Cookie ;

class fulltime_teacher extends Controller
{
    use CacheNick;
    use TeaPower;

    public function full_assessment_list(){
        $adminid = $this->get_account_id();
        //  $adminid=713; //WUhan
        // $adminid=920; //Shanghai
        //print_r($adminid);
        $adminid = $this->get_in_int_val("fulltime_adminid",$adminid);
        $this->set_in_value("tea_adminid",$adminid);
        $tea_adminid = $this->get_in_int_val("tea_adminid");
        $teacher_info = $this->t_manager_info->get_teacher_info_by_adminid($adminid);
        if(!empty($teacher_info)){
            $teacherid = $teacher_info["teacherid"];
        }else{
            $teacherid=1;
        }
        $account_info = $this->t_manager_info->field_get_list($adminid,"create_time,name,post,main_department");
        if(!empty($teacher_info) && $teacher_info["train_through_new_time"] >0 && $teacher_info["train_through_new_time"] <$account_info["create_time"] ){
            $account_info["create_time"] = $teacher_info["train_through_new_time"];
        }
        //添加全职老师类型
        $account_info['fulltime_teacher_type'] = $teacher_info['fulltime_teacher_type'];
        $account_info['post'] = 7;
        $account_info['main_department'] = 2;
        if((time() - $account_info["create_time"])<55*86400){
            return $this->error_view([
                "转正考核需在入职55天以后才能提交"
            ]);
        }

        //获取试用期内月平均课时消耗数和设置评分
        $start_time = $account_info['create_time'];
        $per_start = time()-92*86400;
        if($start_time >=$per_start){
            $per_start = $start_time;
        }
        $end_time   = time();
        $n = ($end_time - $per_start)/86400/31;
        $qz_tea_arr = array("$teacherid");
        $lesson_count = $this->t_lesson_info_b2->get_teacher_lesson_count_list($per_start,$end_time,$qz_tea_arr);
        $val = $teacher_info;
        $val["lesson_count"]     = isset($lesson_count[$val["teacherid"]])?$lesson_count[$val["teacherid"]]["lesson_all"]/100:0;
        $val["lesson_count_avg"] = round($val["lesson_count"]/$n,2);
        $account_info['lesson_count_avg'] = $val['lesson_count_avg'];
        //        $account_info["lesson_count_avg_score"] = round($account_info['lesson_count_avg']*0.3125);
        $account_info["lesson_count_avg_score"] = round($account_info['lesson_count_avg']*0.25);
        if($account_info["lesson_count_avg_score"]>=20){//25->20
            $account_info["lesson_count_avg_score"]=20;
        }
               
        if((time() - $account_info["create_time"])>60*86400){
            $time_flag=1;
        }else{
            $time_flag=0;
        }
        $this->set_in_value("time_flag",$time_flag);
        $time_flag = $this->get_in_int_val("time_flag");

        $account_info['post_str']          = E\Epost::get_desc($account_info['post'] );
        $account_info['main_department_str']      = E\Emain_department::get_desc($account_info['main_department']);
        // $lesson_info  = $this->t_lesson_info_b2->get_teacher_test_lesson_order_info($teacherid,$account_info["create_time"],time());
        // $account_info["order_per"] =!empty($lesson_info["person_num"])?round($lesson_info["have_order"]/$lesson_info["person_num"]*100,2):0;
        $account_info["order_per"]= $this->get_fulltime_teacher_test_lesson_score($teacherid, $per_start,time());

        //$account_info["order_per_score"] = round(0.25*$account_info["order_per"]*2);

        $account_info["order_per_score"] = round(0.625*$account_info["order_per"]);
        if($account_info["order_per_score"]>=25){//20->25
            $account_info["order_per_score"]=25;
        }

        $date_week                         = \App\Helper\Utils::get_week_range(time(),1);
        $week_start = $date_week["sdate"]-14*86400;
        $week_end = $date_week["sdate"]+21*86400;
        $normal_stu_num = $this->t_lesson_info_b2->get_tea_stu_num_list_personal($teacherid,$week_start,$week_end);
 
        // $normal_stu_num = $this->t_week_regular_course->get_tea_stu_num_list_new($teacherid);
        $account_info["stu_num"] = $normal_stu_num["num"];
        if( $account_info["stu_num"]>=15){
            $account_info["stu_num_score"] =15;
        }else{
            $account_info["stu_num_score"] =$account_info["stu_num"];
        }
        $account_info["stu_lesson_total"] = round($normal_stu_num["lesson_all"]/300);
        /* $rate_info = $this->t_teacher_label->get_parent_rate_info($teacherid);
        if($rate_info["num"] >0){
            $account_info["lesson_level"] = $rate_info["level"];
        }else{
            $account_info["lesson_level"]=5;
        }
        if($account_info["lesson_level"]>=3){
            $account_info["lesson_level_score"] = $account_info["lesson_level"];
        }else{
            $account_info["lesson_level_score"]=0;
            }*/
        if( $account_info["stu_lesson_total"]>=50){
            $account_info["stu_lesson_total_score"]=10;
        }elseif( $account_info["stu_lesson_total"]>=40){
            $account_info["stu_lesson_total_score"]=8;
        }elseif( $account_info["stu_lesson_total"]>=30){
            $account_info["stu_lesson_total_score"]=6;
        }elseif( $account_info["stu_lesson_total"]>=20){
            $account_info["stu_lesson_total_score"]=4;
        }elseif( $account_info["stu_lesson_total"]>=10){
            $account_info["stu_lesson_total_score"]=2;
        }else{
             $account_info["stu_lesson_total_score"]=0;
        }
               
        if($adminid==349 || $adminid==99){
            $account_info["order_per_score"]=25;
            $account_info["stu_num_score"]=15;
            $account_info["stu_lesson_total_score"]=5; 
        }
        
        $account_info["result_score"] = $account_info["order_per_score"]+$account_info["lesson_count_avg_score"];
       
        $ret_info = $this->t_fulltime_teacher_assessment_list->get_all_info_by_adminid($adminid);
        $positive_info=[];
        if($ret_info){
            $ret_info["assess_time_str"] = $ret_info["assess_time"]>0?date("Y-m-d H:i:s",$ret_info["assess_time"]):"";
            $ret_info["assess_admin_nick"] =  $ret_info["assess_adminid"]>0?$this->t_manager_info->get_name($ret_info["assess_adminid"]):"";
            $ret_info["result_score_new"] = $ret_info["complaint_refund_score"]+$account_info["order_per_score"]+$account_info["lesson_count_avg_score"]+$ret_info["lesson_level_score"];
            $ret_info["total_score_new"] = $ret_info["result_score_new"]-$ret_info["result_score"]+$ret_info["total_score"];

                     
            if( $ret_info["total_score_new"] >= 95){
                $ret_info["rate_stars_new"] = 5;
            }else if( $ret_info["total_score_new"] >= 88){
                $ret_info["rate_stars_new"] = 4;
            }else if( $ret_info["total_score_new"] >= 80){
                $ret_info["rate_stars_new"] = 3;
            }else if( $ret_info["total_score_new"] >= 70){
                $ret_info["rate_stars_new"] = 2;
            }else{
                $ret_info["rate_stars_new"] = 1;
            }

            $positive_info = $this->t_fulltime_teacher_positive_require_list->get_all_info_by_assess_id($ret_info["id"]);
        }
        $positive_fail_type = $this->t_fulltime_teacher_positive_require_list->get_fail_require_positive_type($adminid);
        $check_is_late = $this->t_fulltime_teacher_positive_require_list->check_is_late($adminid);
        // dd($check_is_late);
        //dd($positive_fail_type);
        $acc = $this->get_account();
        $this->set_in_value("acc",$acc);
        $acc = $this->get_in_str_val("acc");

        return $this->Pageview(__METHOD__,null,[
            "account_info"  =>$account_info,
            "ret_info"      =>$ret_info,
            "positive_info" =>$positive_info,
            "positive_type_old" =>$positive_fail_type,
            "check_is_late" =>$check_is_late,
            "acc"           =>$acc
        ]);
    }

    public function get_admin_user_info(){
        $adminid = $this->get_in_int_val("adminid");
        $positive_type = $this->get_in_int_val("positive_type");
        //$adminid=99;
        //$positive_type=2;
        $data = $this->t_manager_info->get_fulltime_teacher_admin_info($adminid);
        $data['post'] =7;
        $data['main_department']=2;

        if($positive_type==1){
            $data["positive_time"] = $data["create_time"]+90*86400;
        }elseif($positive_type==2){
            $tt =  $data["create_time"]+60*86400;
            if($tt>time()){
                $data["positive_time"] = $tt;
            }else{
                $data["positive_time"]= time();
            }
        }elseif($positive_type==3 || $positive_type==4){
             $data["positive_time"] = $data["create_time"]+120*86400;
        }
        $data["create_time_str"] = date("Y-m-d",$data["create_time"]);
        $data["positive_time_str"] = date("Y-m-d",$data["positive_time"]);
        $data['post_str']          = E\Epost::get_desc($data['post'] );
        $data['main_department_str']      = E\Emain_department::get_desc($data['main_department']);        
        $data['positive_type_str']      = E\Epositive_type::get_desc($positive_type);        
        $data["level_str"] = E\Elevel::v2s($data["level"]);
        $data["positive_level"] = $data["level"]+1;
        $data["positive_level_str"] = E\Elevel::v2s($data["positive_level"]);

        return $this->output_succ(["data"=>$data]);
    }

    public function get_fulltime_teacher_assessment_info(){
        $id = $this->get_in_int_val("id");
        $data = $this->t_fulltime_teacher_assessment_list->field_get_list($id,"*");
        return $this->output_succ(["data"=>$data]);
    }

    public function get_fulltime_teacher_assessment_info_by_adminid(){
        $adminid = $this->get_in_int_val("adminid");
        $teacher_info = $this->t_manager_info->get_teacher_info_by_adminid($adminid);
        if(!empty($teacher_info)){
            $teacherid = $teacher_info["teacherid"];
        }else{
            $teacherid=1;
        }
        $account_info = $this->t_manager_info->field_get_list($adminid,"create_time,name,post,main_department");  
        
        $start_time = $account_info['create_time'];
        $end_time   = time();
        $n = ($end_time - $start_time)/86400/31;
        $qz_tea_arr = array("$teacherid");
        $lesson_count = $this->t_lesson_info_b2->get_teacher_lesson_count_list($start_time,$end_time,$qz_tea_arr);
        $val = $teacher_info;
        $val["lesson_count"]     = isset($lesson_count[$val["teacherid"]])?$lesson_count[$val["teacherid"]]["lesson_all"]/100:0;
        $val["lesson_count_avg"] = round($val["lesson_count"]/$n,2);
        $account_info['lesson_count_avg'] = $val['lesson_count_avg'];
        $account_info["lesson_count_avg_score"] = round($account_info['lesson_count_avg']*0.3125);
        if($account_info["lesson_count_avg_score"]>=25){
            $account_info["lesson_count_avg_score"]=25;
        }
  
        $account_info["order_per"]= $this->get_fulltime_teacher_test_lesson_score($teacherid,$account_info["create_time"],time());

        $account_info["order_per_score"] = round(0.25*$account_info["order_per"]*2);
        if($account_info["order_per_score"]>=20){
            $account_info["order_per_score"]=20;
        }


        return $this->output_succ(["data"=>$account_info]);
    }

    public function fulltime_teacher_assessment_positive_info_master(){
        $this->set_in_value("main_flag",1);
        return $this->fulltime_teacher_assessment_positive_info();
    }

    public function fulltime_teacher_assessment_positive_info(){
        $adminid = $this->get_in_int_val("adminid",-1);
        $main_flag = $this->get_in_int_val("main_flag",-1);

        $become_full_member_flag = $this->get_in_int_val("become_full_member_flag",0);
        $fulltime_teacher_type = $this->get_in_int_val("fulltime_teacher_type", -1);
        $page_info= $this->get_in_page_info();
        $ret_info = $this->t_manager_info->get_fulltime_teacher_assessment_positive_info($page_info,$adminid,$become_full_member_flag,$main_flag,$fulltime_teacher_type);
        foreach($ret_info["list"] as &$item){
            \App\Helper\Utils::unixtime2date_for_item($item,"create_time","_str","Y-m-d");
            \App\Helper\Utils::unixtime2date_for_item($item,"become_full_member_time","_str","Y-m-d");
            \App\Helper\Utils::unixtime2date_for_item($item,"assess_time","_str");
            \App\Helper\Utils::unixtime2date_for_item($item,"master_assess_time","_str");
            \App\Helper\Utils::unixtime2date_for_item($item,"add_time","_str");
            \App\Helper\Utils::unixtime2date_for_item($item,"main_master_assess_time","_str");
            $this->cache_set_item_account_nick($item,"assess_adminid","assess_admin_nick");
            $this->cache_set_item_account_nick($item,"mater_adminid","mater_admin_nick");
            $this->cache_set_item_account_nick($item,"main_mater_adminid","main_mater_admin_nick");

            E\Eaccept_flag::set_item_value_str($item,"master_deal_flag");
            E\Eaccept_flag::set_item_value_str($item,"main_master_deal_flag");
            E\Eboolean::set_item_value_str($item,"become_full_member_flag");
            E\Epositive_type::set_item_value_str($item,"positive_type");
        }
        return $this->Pageview(__METHOD__,$ret_info,[
            "main_flag" => $main_flag
        ]);
    }

    public function get_fulltime_teacher_pisitive_require_info(){
        $id = $this->get_in_int_val("id");
        $data = $this->t_fulltime_teacher_positive_require_list->field_get_list($id,"*");

        $data['post_str']            = E\Epost::get_desc($data['post'] );
        $data['main_department_str'] = E\Emain_department::get_desc($data['main_department']);
        $data["name"] = $this->t_manager_info->get_name($data["adminid"]);
        $data["email"] = $this->t_manager_info->get_email($data["adminid"]);
        $data["level_str"] = E\Elevel::v2s($data["level"]);
        $data["positive_level_str"] = E\Elevel::v2s($data["positive_level"]);
        $data["create_time_str"] = date("Y-m-d",$data["create_time"]);
        $data["positive_time_str"] = date("Y-m-d",$data["positive_time"]);
        $data['positive_type_str']      = E\Epositive_type::get_desc($data["positive_type"]);        
        $data['master_deal_flag_str']      = E\Eaccept_flag::get_desc($data["master_deal_flag"]);        
        $data['base_money']      /= 100;
        if(empty($data["master_deal_flag"])) $data['master_deal_flag_str']="未审核";
        $data['main_master_deal_flag_str']      = E\Eaccept_flag::get_desc($data["main_master_deal_flag"]);
        if(empty($data["main_master_deal_flag"])) $data['main_master_deal_flag_str']="未审核";

        return $this->output_succ(["data"=>$data]);
    }

    public function fulltime_teacher_count(){
        $this->switch_tongji_database();
        $this->check_and_switch_tongji_domain();
        $actual_flag = $this->get_in_int_val("actual_flag",1);
        $fulltime_teacher_type = $this->get_in_int_val("fulltime_teacher_type", -1);

        list($start_time,$end_time) = $this->get_in_date_range(0,0,0,[],3);
        if($actual_flag==1){
            
        

            $list=$this->t_teaching_core_data->get_all_info(-1,$start_time);
            foreach($list["list"] as &$item){
                $item["fulltime_teacher_lesson_count"] =  $item["fulltime_teacher_lesson_count"]/100;
                $item["platform_teacher_lesson_count"] =  $item["platform_teacher_lesson_count"]/100;
                $item["part_teacher_lesson_count"] =  $item["platform_teacher_lesson_count"]-$item["fulltime_teacher_lesson_count"];
                $item["part_teacher_cc_lesson"] = $item["platform_teacher_cc_lesson"]-$item["fulltime_teacher_cc_lesson"];
                $item["part_teacher_cc_order"] = $item["platform_teacher_cc_order"]-$item["fulltime_teacher_cc_order"];
                if($fulltime_teacher_type==1){
                    $item["fulltime_teacher_lesson_count"] =  $item["fulltime_teacher_lesson_count_shanghai"]/100;
                    $item["fulltime_teacher_count"] = $item["fulltime_teacher_count_shanghai"];
                    $item["fulltime_teacher_cc_lesson"] = $item["fulltime_teacher_cc_lesson_shanghai"];
                    $item["fulltime_teacher_cc_order"] = $item["fulltime_teacher_cc_order_shanghai"];
                    $item["fulltime_normal_stu_num"]=$item["fulltime_normal_stu_num_shanghai"];
                    $item["fulltime_teacher_student"] = $item["fulltime_teacher_student_shanghai"];
                }elseif($fulltime_teacher_type==2){
                    $item["fulltime_teacher_lesson_count"] =  $item["fulltime_teacher_lesson_count_wuhan"]/100;
                    $item["fulltime_teacher_count"] = $item["fulltime_teacher_count_wuhan"];
                    $item["fulltime_teacher_cc_lesson"] = $item["fulltime_teacher_cc_lesson_wuhan"];
                    $item["fulltime_teacher_cc_order"] = $item["fulltime_teacher_cc_order_wuhan"];
                    $item["fulltime_normal_stu_num"]=$item["fulltime_normal_stu_num_wuhan"];
                    $item["fulltime_teacher_student"] = $item["fulltime_teacher_student_wuhan"];
                }

                $item['fulltime_teacher_pro'] = $item['platform_teacher_count']>0?round($item["fulltime_teacher_count"]*100/$item['platform_teacher_count'],2):0;
                $item["fulltime_teacher_cc_per"] = !empty($item["fulltime_teacher_cc_lesson"])?round($item["fulltime_teacher_cc_order"]/$item["fulltime_teacher_cc_lesson"]*100,2):0;
                $item["platform_teacher_cc_per"] = !empty($item["platform_teacher_cc_lesson"])?round($item["platform_teacher_cc_order"]/$item["platform_teacher_cc_lesson"]*100,2):0;

                $item["part_teacher_cc_per"] = !empty($item["part_teacher_cc_lesson"])?round($item["part_teacher_cc_order"]/$item["part_teacher_cc_lesson"]*100,2):0;
                $item["fulltime_teacher_lesson_count_per"] = !empty($item["platform_teacher_lesson_count"])?round($item["fulltime_teacher_lesson_count"]/$item["platform_teacher_lesson_count"]*100,2):0;
                $item["fulltime_normal_stu_pro"] = !empty($item["platform_normal_stu_num"])?round($item["fulltime_normal_stu_num"]/$item["platform_normal_stu_num"]*100,2):0;
                $item["fulltime_teacher_student_pro"] = !empty($item["platform_teacher_student"])?round($item["fulltime_teacher_student"]/$item["platform_teacher_student"]*100,2):0;


            }
            $ret = @$list["list"][0];
        }else{          
            $lesson_end_time = $this->get_test_lesson_end_time($end_time);


            $m = date("m",$start_time);
            $n = ($end_time - $start_time)/86400/31;
            $d = ($end_time - $start_time)/86400;

            $ret_info  = $this->t_manager_info->get_research_teacher_list_new(5,$fulltime_teacher_type);
            $qz_tea_arr=[];
            foreach($ret_info as $yy=>$item){
                if($item["teacherid"] != 97313){
                    $qz_tea_arr[] =$item["teacherid"];
                }else{
                    unset($ret_info[$yy]);
                }
            }

            $list = $ret_info;
            $qz_tea_list  = $this->t_lesson_info->get_qz_test_lesson_info_list($qz_tea_arr,$start_time, $lesson_end_time);
            $qz_tea_list_kk = $this->t_lesson_info->get_qz_test_lesson_info_list2($qz_tea_arr,$start_time, $lesson_end_time);
            $qz_tea_list_hls = $this->t_lesson_info->get_qz_test_lesson_info_list3($qz_tea_arr,$start_time, $lesson_end_time);
            if(time()>= $start_time){
                $week_time = time();
            }else{
                $week_time = $end_time;
            }
            $date_week                         = \App\Helper\Utils::get_week_range($week_time,1);

            //   $date_week                         = \App\Helper\Utils::get_week_range(time(),1);
            $week_start = $date_week["sdate"]-14*86400;
            $week_end = $date_week["sdate"]+21*86400;
            $normal_stu_num1 = $this->t_lesson_info_b2->get_tea_stu_num_list($qz_tea_arr,$week_start,$week_end);
            $lesson_count = $this->t_lesson_info_b2->get_teacher_lesson_count_list($start_time,$end_time,$qz_tea_arr);
            $date                              = \App\Helper\Utils::get_month_range(time(),1);
            $teacher_lesson_count_total        = $this->t_lesson_info->get_teacher_lesson_count_total(time(),$date["edate"],$qz_tea_arr,1);
            $tran_avg= $lesson_avg=[];
            foreach($ret_info as &$item){
                $item["cc_lesson_num"] =  isset($qz_tea_list[$item["teacherid"]])?$qz_tea_list[$item["teacherid"]]["all_lesson"]:0;
                $item["cc_order_num"] =  isset($qz_tea_list[$item["teacherid"]])?$qz_tea_list[$item["teacherid"]]["order_num"]:0;
                $item["kk_lesson_num"] =  isset($qz_tea_list_kk[$item["teacherid"]])?$qz_tea_list_kk[$item["teacherid"]]["all_lesson"]:0;
                $item["kk_order_num"] =  isset($qz_tea_list_kk[$item["teacherid"]])?$qz_tea_list_kk[$item["teacherid"]]["order_num"]:0;
                $item["hls_lesson_num"] =  isset($qz_tea_list_hls[$item["teacherid"]])?$qz_tea_list_hls[$item["teacherid"]]["all_lesson"]:0;
                $item["hls_order_num"] =  isset($qz_tea_list_hls[$item["teacherid"]])?$qz_tea_list_hls[$item["teacherid"]]["order_num"]:0;
                $item["cc_per"] = !empty($item["cc_lesson_num"])?round($item["cc_order_num"]/$item["cc_lesson_num"]*100,2):0;
                $item["kk_per"] = !empty($item["kk_lesson_num"])?round($item["kk_order_num"]/$item["kk_lesson_num"]*100,2):0;
                $item["hls_per"] = !empty($item["hls_lesson_num"])?round($item["hls_order_num"]/$item["hls_lesson_num"]*100,2):0;
                $item["lesson_all"] = $item["cc_lesson_num"]+$item["kk_lesson_num"]+$item["hls_lesson_num"];
                $item["order_all"] = $item["cc_order_num"]+$item["kk_order_num"]+$item["hls_order_num"];
                $item["all_per"] = !empty($item["lesson_all"])?round($item["order_all"]/$item["lesson_all"]*100,2):0;
                $item["lesson_per"] = $item["lesson_all"]/$d*100;
                if( $item["lesson_per"]>100){
                    $item["lesson_per"]=100;
                }
                $item["cc_score"] = round($item["cc_per"]*0.75,2);
                $item["all_score"] = round($item["all_per"]*0.15,2);
                if($item["cc_lesson_num"]>10){
                    $cc_num=10;
                }else{
                    $cc_num = $item["cc_lesson_num"];
                }
                @$tran_avg["cc_lesson_num"] +=$item["cc_lesson_num"];
                @$tran_avg["cc_order_num"] +=$item["cc_order_num"];
                @$tran_avg["lesson_all"] +=$item["lesson_all"];
                @$tran_avg["order_all"] +=$item["order_all"];
            }
            if($m>6 && $m <9){
                $m1 =264;$m2=252;$m3=228;
            }else{
                $m1 =220;$m2=210;$m3=190;
            }
            foreach($list as &$val){
                $val["normal_stu"] = isset($normal_stu_num1[$val["teacherid"]])?$normal_stu_num1[$val["teacherid"]]["num"]:0;
                $val["week_count"] = isset($normal_stu_num1[$val["teacherid"]])?round($normal_stu_num1[$val["teacherid"]]["lesson_all"]/500):0;
                $val["lesson_count"] = isset($lesson_count[$val["teacherid"]])?$lesson_count[$val["teacherid"]]["lesson_all"]/100:0;
                $val["lesson_count_avg"] = round($val["lesson_count"]/$n,2);
                $grade = $this->t_teacher_info->get_grade_part_ex($val["teacherid"]);
                if($grade==1){
                    $num=$m1;
                }elseif($grade==2){
                    $num=$m2;
                }elseif($grade==3){
                    $num=$m3;
                }elseif($grade==4 || $grade==6){

                    $s = $this->t_lesson_info_b2->get_teacher_lesson_grade_count($start_time,$end_time,$val["teacherid"],1);
                    $m = $this->t_lesson_info_b2->get_teacher_lesson_grade_count($start_time,$end_time,$val["teacherid"],2);
                    $per = !empty($s+$m)?$s/($s+$m):0;
                    if($per >= 0.3){
                        $num=$m1;
                    }else{
                        $num=$m2;
                    }
                }elseif($grade==5 || $grade==7){
                    $s = $this->t_lesson_info_b2->get_teacher_lesson_grade_count($start_time,$end_time,$val["teacherid"],2);
                    $m = $this->t_lesson_info_b2->get_teacher_lesson_grade_count($start_time,$end_time,$val["teacherid"],3);
                    $per = !empty($s+$m)?$s/($s+$m):0;
                    if($per >= 0.3){
                        $num=$m2;
                    }else{
                        $num=$m3;
                    }
                }else{
                    $num=200;
                }
                $val["lesson_per"] = round($val["lesson_count"]/$num*100,2);
                $val["lesson_per_month"] = round($val["lesson_count"]/$num/$n*100,2);
                $val["lesson_count_left"] = isset($teacher_lesson_count_total[$val["teacherid"]])?$teacher_lesson_count_total[$val["teacherid"]]["lesson_total"]:0;
                @$lesson_avg["normal_stu"] +=$val["normal_stu"];
                @$lesson_avg["week_count"] +=$val["week_count"];
                @$lesson_avg["lesson_count"] +=$val["lesson_count"];
                @$lesson_avg["lesson_count_avg"] +=$val["lesson_count_avg"];
                @$lesson_avg["lesson_per"] +=$val["lesson_per"];
                @$lesson_avg["lesson_per_month"] +=$val["lesson_per_month"];
                @$lesson_avg["lesson_count_left"] +=$val["lesson_count_left"];
            }
            \App\Helper\Utils::order_list( $list,"lesson_per_month", 0);
            $tran_count = count($ret_info);
            $lesson_count = count($list);
            $tran_all = $tran_avg;
            $lesson_all = $lesson_avg;
            foreach($tran_avg as $pp=>&$rr){
                $rr = round($rr/$tran_count,2);
            }
            $tran_avg["cc_per"] = !empty($tran_avg["cc_lesson_num"])?round($tran_avg["cc_order_num"]/$tran_avg["cc_lesson_num"]*100,2):0;
            $tran_avg["all_per"] = !empty($tran_avg["lesson_all"])?round($tran_avg["order_all"]/$tran_avg["lesson_all"]*100,2):0;
            $tran_all["cc_per"] = !empty($tran_all["cc_lesson_num"])?round($tran_all["cc_order_num"]/$tran_all["cc_lesson_num"]*100,2):0;
            $tran_all["all_per"] = !empty($tran_all["lesson_all"])?round($tran_all["order_all"]/$tran_all["lesson_all"]*100,2):0;
            $arr = [];
            foreach ($ret_info as $key => $value) {
                $arr[] = $value['teacherid'];
            }
            $full_num = $this->t_manager_info->get_fulltime_teacher_num($end_time,$fulltime_teacher_type);
            //-------------------------------------------------------------------------------------
            //$ret['fulltime_teacher_count'] =  count($arr);//全职老师总人数
            $ret['fulltime_teacher_count'] =  $full_num;//全职老师总人数
            $fulltime_lesson_count = $this->t_teacher_info->get_teacher_list(1,$start_time,$end_time,1,$fulltime_teacher_type);//统计全职老师总人数/课时

            // $ret['fulltime_teacher_student'] =$lesson_all['normal_stu']; //全职老师所带学生总数
            $ret['fulltime_teacher_student'] =$fulltime_lesson_count["stu_num"]; //全职老师所带学生总数
            $ret['fulltime_teacher_lesson_count'] =@$lesson_all['lesson_count'];//全职老师完成的课耗总数
            $ret['fulltime_teacher_cc_per']  = $tran_all['cc_per'];//全职老师cc转化率
            $ret['fulltime_teacher_cc_lesson']  = @$tran_all["cc_lesson_num"];//全职老师cc转化率
            $ret['fulltime_teacher_cc_order']  = @$tran_all["cc_order_num"];//全职老师cc转化率
            $train_through_new = 1;
            $ret_platform_teacher_lesson_count = $this->t_teacher_info->get_teacher_list(1,$start_time,$end_time);//统计平台老师总人数/课时
            $ret['platform_teacher_count'] = $ret_platform_teacher_lesson_count["tea_num"];//统计平台老师总人数
            if($ret['platform_teacher_count']){
                $ret['fulltime_teacher_pro'] = round($ret['fulltime_teacher_count']*100/$ret['platform_teacher_count'],2);
            }else{
                $ret['fulltime_teacher_pro']=0;
            }
            $type = 0;
            $platform_teacher_student = $this->t_student_info->get_total_student_num($type);//统计平台学生数
            $ret['platform_teacher_student'] = $platform_teacher_student[0]['platform_teacher_student'];
            $ret['fulltime_teacher_student_pro'] = round($ret['fulltime_teacher_student']*100/$ret['platform_teacher_student'],2);
            $test_person_num_total= $this->t_lesson_info->get_teacher_test_person_num_list_total( $start_time,$lesson_end_time);
            $ret['platform_teacher_lesson_count'] = round($ret_platform_teacher_lesson_count["lesson_count"]/100);//全职老师完成的课耗总数
            if($ret['platform_teacher_lesson_count'] != 0){
                $ret['fulltime_teacher_lesson_count_per'] = round($ret['fulltime_teacher_lesson_count']*100/$ret['platform_teacher_lesson_count'],2);
            }else{
                $ret['fulltime_teacher_lesson_count_per'] = 0;
            }
            if($test_person_num_total['person_num'] != 0){
                $ret['platform_teacher_cc_per']  = round(100*$test_person_num_total['have_order'] / $test_person_num_total['person_num'],2);//全职老师cc转化率
            }else{
                $ret['platform_teacher_cc_per'] = 0;
            }
            $ret['platform_teacher_cc_lesson']  = $test_person_num_total['person_num'];
            $ret['platform_teacher_cc_order']  = $test_person_num_total['have_order'];
            $ret['part_teacher_lesson_count'] =  @$ret["platform_teacher_lesson_count"]-@$ret["fulltime_teacher_lesson_count"];
            $ret['part_teacher_cc_lesson'] = @$ret["platform_teacher_cc_lesson"]-@$ret["fulltime_teacher_cc_lesson"];
            $ret['part_teacher_cc_order'] = @$ret["platform_teacher_cc_order"]-@$ret["fulltime_teacher_cc_order"];
            $ret['part_teacher_cc_per']  =$ret['part_teacher_cc_lesson']>0?round(100*$ret['part_teacher_cc_order']/$ret['part_teacher_cc_lesson'],2):0;//全职老师cc转化率

            $date_week                         = \App\Helper\Utils::get_week_range(time(),1);
            $week_start = $date_week["sdate"]-14*86400;
            $week_end = $date_week["sdate"]+21*86400;
            // $ret_info  = $this->t_manager_info->get_research_teacher_list_new(5);
            // $qz_tea_arr=[];
            // foreach($ret_info as $yy=>$item){
            //     if($item["teacherid"] != 97313){
            //         $qz_tea_arr[] =$item["teacherid"];
            //     }else{
            //         unset($ret_info[$yy]);
            //     }
            // }
            $normal_stu_num = $this->t_lesson_info_b2->get_tea_stu_num_list($qz_tea_arr,$week_start,$week_end);
            foreach($normal_stu_num as $val){
                @$ret['fulltime_normal_stu_num'] +=$val["num"];
            }
            $normal_stu_num_all = $this->t_lesson_info_b2->get_tea_stu_num_list([],$week_start,$week_end,false);
            foreach($normal_stu_num_all as $val){
                @$ret['platform_normal_stu_num'] +=$val["num"];
            }

       
            //  $platform_normal_stu_list = $this->t_teacher_info->get_teacher_list(1,$week_start,$week_end);
            //  $ret['platform_normal_stu_num'] =$platform_normal_stu_list["stu_num"];
            $ret['fulltime_normal_stu_pro'] =  @$ret['platform_normal_stu_num']>0?round(100*@$ret['fulltime_normal_stu_num']/@$ret['platform_normal_stu_num'],2):0;
        }


        return $this->pageView(__METHOD__ ,null, [
            "ret_info" => @$ret,
        ]);
    }


    public function get_fulltime_teacher_train_lesson_list(){
        $this->switch_tongji_database();
        list($start_time,$end_time) = $this->get_in_date_range(0,0,0,[],3);
        $page_info = $this->get_in_page_info();
        $adminid = $this->get_account_id();
        // $adminid =1008;
        $teacher_info = $this->t_manager_info->get_teacher_info_by_adminid($adminid);
        $teacherid = @$teacher_info["teacherid"];
        $ret_info = $this->t_lesson_info_b2->get_fulltime_teacher_train_lesson_list($page_info,$start_time,$end_time,$teacherid);
        foreach($ret_info["list"] as &$item){
            $item["lesson_start_str"]=date("Y-m-d H:i",$item["lesson_start"]); 
        }
        return $this->pageView(__METHOD__ ,$ret_info);
    }


    /**
     * @author    sam
     * @function  武汉全职老师面试数据
     */
    public function fulltime_teacher_data(){
        list($start_time,$end_time) = $this->get_in_date_range(0,0,0,[],3);
        //已经移交ajax2处理
        /*
        //本月数据-----------------------------------------------------
        $apply_num   = $this->t_teacher_lecture_appointment_info->get_fulltime_teacher_count($start_time,$end_time); //成功注册人数
        //一面到面人数
        $arrive_num  = $this->t_teacher_lecture_appointment_info->get_fulltime_teacher_arrive($start_time,$end_time);//面试人数
        $video_num   = $this->t_teacher_lecture_appointment_info->get_fulltime_teacher_arrive_video($start_time,$end_time);//视频试讲人数
        
        //一面通过人数
        $arrive_through_num = $this->t_teacher_lecture_appointment_info->get_fulltime_teacher_arrive_through($start_time,$end_time);//面试通过人数
        $video_through_num  = $this->t_teacher_lecture_appointment_info->get_fulltime_teacher_arrive_video_through($start_time,$end_time);//视频试讲通过人数
        //二面通过人数
        $second_through_num  = $this->t_teacher_lecture_appointment_info->get_fulltime_teacher_arrive_second_through($start_time,$end_time);
        //入职人数
        $enter_num = $this->t_teacher_lecture_appointment_info->get_fulltime_teacher_enter($start_time,$end_time); //入职人数

        //累计数据-----------------------------------------------------
        $start_time = 1498838400;
        $apply_total = $this->t_teacher_lecture_appointment_info->get_fulltime_teacher_total($start_time,$end_time); //累计注册人数
        
        $arrive_num_total  = $this->t_teacher_lecture_appointment_info->get_fulltime_teacher_arrive($start_time,$end_time);//累计面试人数
        $video_num_total   = $this->t_teacher_lecture_appointment_info->get_fulltime_teacher_arrive_video($start_time,$end_time);//累计视频试讲人数
        //累计一面到面人数
        //        $first_total = $arrive_num_total + $video_num_total;//累计一面人数
        //累计一面通过人数
        $video_through_num_total  = $this->t_teacher_lecture_appointment_info->get_fulltime_teacher_arrive_video_through($start_time,$end_time);//视频试讲通过人数
        $arrive_through_num_total = $this->t_teacher_lecture_appointment_info->get_fulltime_teacher_arrive_through($start_time,$end_time);//面试通过人数
        //$second_total = $video_through_num_total + $arrive_through_num_total;
        //累计二面通过人数
        $second_through_num_total  = $this->t_teacher_lecture_appointment_info->get_fulltime_teacher_arrive_second_through($start_time,$end_time);
        //累计入职人数
        $enter_num_total = $this->t_teacher_lecture_appointment_info->get_fulltime_teacher_enter($start_time,$end_time); //入职人数
    	$ret_info['apply_num'] = $apply_num[0]['apply_num'];
        $ret_info['arrive_num'] = $arrive_num[0]['arrive_count'] + $video_num[0]['video_num'];
        $ret_info['arrive_through'] = $arrive_through_num[0]['arrive_through_count'] + $video_through_num[0]['video_through_num'];
        $ret_info['second_through'] = $second_through_num[0]['through_num'];
        $ret_info['enter_num']      = $enter_num[0]['num'];

        $ret_info['apply_total'] = $apply_total[0]['apply_total'];//累计注册人数
        $ret_info['arrive_num_total'] = $arrive_num_total[0]['arrive_count'] + $video_num_total[0]['video_num'];//累计一面
        $ret_info['arrive_through_total'] = $arrive_throught_num_total[0]['arrive_through_count'] + $video_through_num_total[0]['video_through_num'];
        $ret_info['second_through_total'] = $second_through_num_total[0]['through_num'];
        $ret_info['enter_num_total']      = $enter_num_total[0]['num'];

        //一面到面率
        if($ret_info['apply_total']){
            $ret_info['arrive_num_per'] = round(100*$ret_info['arrive_num_total']/$ret_info['apply_total'],2);
        }else{
            $ret_info['arrive_num_per'] = 0;
        }
        //一面通过率
        if($ret_info['arrive_num_total']){
            $ret_info['arrive_through_per'] = round(100*$ret_info['arr'])
        }
        /*
        if($ret_info['apply_total']){
            $ret_info['arrive_num_per'] = round(100*$ret_info['arrive_num']/$ret_info['apply_total'],2);
            $ret_info['arrive_through_per'] = round(100*$ret_info['arrive_through']/$ret_info['apply_total'],2);
            $ret_info['second_through_per'] = round(100*$ret_info['second_through']/$ret_info['apply_total'],2);
            $ret_info['enter_num_per'] = round(100*$ret_info['enter_num']/$ret_info['apply_total'],2);
        }else{
            $ret_info['arrive_num_per'] = 0;
            $ret_info['arrive_through_per'] = 0;
            $ret_info['second_through_per'] = 0;
            $ret_info['enter_num_per'] = 0;
        }
        */
    	return $this->pageView(__METHOD__);
    }


    /**
     * @author    jack
     * @function  全职老师考勤
     */
    public function fulltime_teacher_work_attendance_info(){
        list($start_time,$end_time) = $this->get_in_date_range(0,0,0,[],3);
        $adminid= $this->get_in_int_val("adminid",480 );
        $date_list=\App\Helper\Common::get_date_time_list($start_time, $end_time-1);
        $ret_info=$this->t_admin_card_log->get_list( 1, $start_time,$end_time,$adminid,100000,5 );
        $teacher_info = $this->t_manager_info->get_teacher_info_by_adminid($adminid);
        $teacherid = @$teacher_info["teacherid"];

        foreach ($ret_info["list"] as $item ) {
            $logtime=$item["logtime"];
            $opt_date=date("Y-m-d",$logtime);
            $date_item= &$date_list[$opt_date];
            if (!isset($date_item["start_logtime"])) {
                $date_item["start_logtime"]=$logtime;
                $date_item["end_logtime"]=$logtime;
            }else{
                if ($date_item["start_logtime"] > $logtime  ) {
                    $date_item["start_logtime"] = $logtime;
                }
                if ($date_item["end_logtime"] < $logtime  ) {
                    $date_item["end_logtime"] = $logtime;
                }
            }
        }

        $today_time = strtotime(date("Y-m-d",time()));
        foreach( $date_list as  &$d_item ) {
            $year=date("Y",$start_time);
            $day_time = strtotime($year."-".$d_item["title"]);
            $w = date("w",$day_time);
            $check_holiday = $this->t_festival_info->check_is_holiday($day_time);

            if (isset ( $d_item["start_logtime"]) ){
                $d_item["work_time"]=  $d_item["end_logtime"] -  $d_item["start_logtime"] ;
                $d_item["work_time_str"] =\App\Helper\Common::get_time_format( $d_item["work_time"]  );
                \App\Helper\Utils::unixtime2date_for_item($d_item,"start_logtime", "_str","H:i:s");
                \App\Helper\Utils::unixtime2date_for_item($d_item,"end_logtime" ,"_str", "H:i:s");    
            }
            // $d_item["error_flag"]=true;
            // $d_item["error_flag_str"] ="是";

            // if(!$check_holiday && in_array($w,[0,3,4,5,6]) && $adminid>0 && !empty($ret_info["list"]) && $day_time<$today_time){
            //     $check_holiday_flag = $this->t_fulltime_teacher_attendance_list->check_is_in_holiday($teacherid,$day_time);
            //     if(!$check_holiday_flag){
            //         $id = $this->t_fulltime_teacher_attendance_list->check_is_exist($teacherid,$day_time);
            //         if($id>0){

            //             $attendance_info = $this->t_fulltime_teacher_attendance_list->field_get_list($id,"attendance_time,attendance_type,off_time,delay_work_time");
            //             $attendance_type = $attendance_info["attendance_type"];
            //             if($attendance_type==2){
            //                 if (isset ( $d_item["start_logtime"]) ){              
            //                     $off_time = $attendance_info["off_time"]==0?($day_time+9.5*3600):$attendance_info["off_time"];                              
            //                     $delay_time = $attendance_info["delay_work_time"]==0?($day_time+18.5*3600):$attendance_info["delay_work_time"];
            //                     if($off_time < $d_item["start_logtime"] ||  $delay_time> $d_item["end_logtime"]){
            //                         $d_item["error_flag"]=true;
            //                         $d_item["error_flag_str"] ="是"; 
            //                     }

            //                 }else{
            //                     $d_item["error_flag"]=true;
            //                     $d_item["error_flag_str"] ="是";
            //                 }

            //             }
                       
 
            //         }else{
            //             if (isset ( $d_item["start_logtime"]) ){              
            //                 $off_time = $day_time+9.5*3600;                              
            //                 $delay_time = $day_time+18.5*3600;
            //                 if($off_time < $d_item["start_logtime"] ||  $delay_time> $d_item["end_logtime"]){
            //                     $d_item["error_flag"]=true;
            //                     $d_item["error_flag_str"] ="是"; 
            //                 }
            //                 // $d_item["error_flag"]= ($d_item["work_time"] < 9*3600);
            //                 // if ($d_item["error_flag"]) {
            //                 //     $d_item["error_flag_str"] ="是";
            //                 // }
            //             }else{
            //                 $d_item["error_flag"]=true;
            //                 $d_item["error_flag_str"] ="是";
            //             }
 
            //         }
            //     }
                
            // }
        }

        return $this->pageView(__METHOD__, \App\Helper\Utils::list_to_page_info($date_list) );

    }

    public function fulltime_teacher_attendance_info_month(){
        list($start_time,$end_time)= $this->get_in_date_range(0,0,0,[],3 );
        $attendance_type=-1;
        $account_role=-1;
        $teacherid = $this->get_in_int_val("teacherid",-1);
        $adminid = $this->get_in_int_val("adminid",-1);
        $fulltime_teacher_type = $this->get_in_int_val("fulltime_teacher_type", -1);
        $seller_groupid_ex    = $this->get_in_str_val('seller_groupid_ex', "");
        $adminid_list = $this->t_admin_main_group_name->get_adminid_list_new($seller_groupid_ex);
        $adminid_right=[0=>"全职老师",1=>"",2=>"",3=>""];

        $month_start = strtotime(date("Y-m-01",time()));
        if($start_time>=$month_start){
            $ret=[];
        }else{
            $ret=[];
            $list = $this->t_fulltime_teacher_attendance_list->get_fulltime_teacher_attendance_list_new($start_time,$end_time,$attendance_type,$teacherid,$adminid,$account_role,$fulltime_teacher_type,$adminid_list);
            foreach($list as $val){
                $uid = $val["adminid"]; 
                $teacherid = $val["teacherid"];
                $ret[$uid]["realname"] = $val["realname"];
                $ret[$uid]["adminid"] = $val["adminid"];
                $ret[$uid]["teacherid"] = $val["teacherid"];
                $w = date("w",$val["attendance_time"]);
                if(in_array($val["attendance_type"],[0,2]) && $w!=1 && $w !=2){
                    @$ret[$uid]["need_work_day"]++;

                    $off_time = $val["off_time"]==0?($val["attendance_time"]+18.5*3600):$val["off_time"];
                    $delay_time = $val["delay_work_time"]==0?($val["attendance_time"]+9.5*3600):$val["delay_work_time"];
                    if($val["card_start_time"]>0){                                           
                        if($delay_time <$val["card_start_time"]){
                            @$ret[$uid]["late_num"]++;
                            @$ret[$uid]["late_time"] +=$val["card_start_time"]-$delay_time;
                        }
                        if($off_time > $val["card_end_time"]){
                            @$ret[$uid]["early_num"]++;
                            @$ret[$uid]["early_time"] +=$off_time-$val["card_end_time"];
                        }
                    }else{
                        @$ret[$uid]["no_attend_num"]++;
                    }

                }
                if($val["card_start_time"]>0){
                    @$ret[$uid]["real_work_day"]++;
                }
                if($val["attendance_type"]==3){
                    @$ret[$uid]["holiday_day"]++;
                }
            }
            foreach($ret as &$item){
                $item["late_time"] = round(@$item["late_time"]/3600,2);
                $item["early_time"] = round(@$item["early_time"]/3600,2);
            }           
            
 
        }
        return $this->pageView(__METHOD__, \App\Helper\Utils::list_to_page_info($ret),[
            "start" =>date("Y-m-d",$start_time),
            "end" =>date("Y-m-d",$end_time),
            "adminid_right"     => $adminid_right
        ] );
       
       
    }
}
