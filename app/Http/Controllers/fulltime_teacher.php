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
        $account_info['fulltime_teacher_type'] =$teacher_info['fulltime_teacher_type'];
        
        $account_info['post'] =7;
        $account_info['main_department']=2;
        if((time() - $account_info["create_time"])<55*86400){
            return $this->error_view(
                [
                    "转正考核需在入职55天以后才能提交"
                ]
            );

        }
        //获取试用期内月平均课时消耗数和设置评分
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
        $account_info["order_per"]= $this->get_fulltime_teacher_test_lesson_score($teacherid,$account_info["create_time"],time());

        $account_info["order_per_score"] = round(0.25*$account_info["order_per"]*2);
        if($account_info["order_per_score"]>=20){
            $account_info["order_per_score"]=20;
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
            $account_info["order_per_score"]=20;
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
        return $this->Pageview(__METHOD__,null,[
            "account_info"  =>$account_info,
            "ret_info"      =>$ret_info,
            "positive_info" =>$positive_info,
            "positive_type_old" =>$positive_fail_type,
            "check_is_late" =>$check_is_late
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
        return $this->Pageview(__METHOD__,$ret_info);
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
        if(empty($data["master_deal_flag"])) $data['master_deal_flag_str']="未审核";
        $data['main_master_deal_flag_str']      = E\Eaccept_flag::get_desc($data["main_master_deal_flag"]);
        if(empty($data["main_master_deal_flag"])) $data['main_master_deal_flag_str']="未审核";

        return $this->output_succ(["data"=>$data]);
    }

    public function fulltime_teacher_count(){
        $this->switch_tongji_database();
        list($start_time,$end_time) = $this->get_in_date_range(0,0,0,[],3);
    }

}
