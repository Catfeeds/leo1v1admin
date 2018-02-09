<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use App\Helper\Utils;

class tongji2 extends Controller
{
    use CacheNick;
    use TeaPower;
    var $switch_tongji_database_flag = true;

    public function ass_all( ) {
        list($start_time,$end_time)= $this->get_in_date_range_week( 0 );
        $data_list=[];

        $add_data_list=function ( $key, $value ) use( &$data_list )  {
            $data_list[]= ["field_name" => $key, "value" =>  $value    ];
        };



        $seller_groupid_ex    = $this->get_in_str_val('seller_groupid_ex', "");
        $require_adminid_list = $this->t_admin_main_group_name->get_adminid_list_new($seller_groupid_ex);

        $add_data_list("预计结课学员", "test " ) ;

        //续费
        $contract_type=E\Econtract_type::V_3;
        $order_info=$this->t_order_info->get_total_order_info($start_time,$end_time,$require_adminid_list,$contract_type);
        $order_info["all_price"]*=1;
        $order_info["pre_price"]= intval( \App\Helper\Common::div_safe( $order_info["all_price"],$order_info["all_count"]));

        $add_data_list("续费数量", $order_info["all_count"] ) ;

        $add_data_list("续费率", "test" ) ;
        $add_data_list("续费总金额", $order_info["all_price"] ) ;
        $add_data_list("平均单笔", $order_info["pre_price"] ) ;




        $add_data_list("转介绍", "") ;

        $origin_info=$this->t_seller_student_new->get_origin_ass_count( $start_time,$end_time,$require_adminid_list );
        $add_data_list("转介绍数量", $origin_info["count"] ) ;
        //
        $contract_type=E\Econtract_type::V_0;
        $order_info=$this->t_order_info->get_total_order_info($start_time,$end_time,$require_adminid_list,$contract_type);

        $order_info["pre_price"]= intval( \App\Helper\Common::div_safe( $order_info["all_price"],$order_info["all_count"]));
        $add_data_list("转介绍成单数量", $order_info["all_count"]) ;
        $add_data_list("转介绍总金额", intval($order_info["all_price"] )) ;
        $add_data_list("平均单笔", $order_info["pre_price"] ) ;
        $require_info=$this->t_test_lesson_subject_require->get_ass_require_info($start_time,$end_time,$require_adminid_list);

        $add_data_list("扩课", "") ;
        $add_data_list("扩科课次", intval($require_info["all_count"]) ) ;
        $add_data_list("成单数量", intval( $require_info["succ_count"]) ) ;
        $add_data_list("待跟进人数", intval( $require_info["all_count"] -$require_info["succ_count"] - $require_info["fail_count"] ) ) ;
        $add_data_list("未成单数量", intval(  $require_info["fail_count"]) ) ;


        $add_data_list("到课率", "") ;

        $lesson_info=$this->t_lesson_info->get_ass_lesson_info($start_time,$end_time, $require_adminid_list);

        $add_data_list("应完成课程", $lesson_info["all_lesson_count"]/100 );
        $add_data_list("实际完成课程", ($lesson_info[ "succ_all_lesson_count"] ) /100) ;
        $add_data_list("教师请假课程", $lesson_info["tea_fail_all_lesson_count"]/100);
        $add_data_list("学生请假课程", $lesson_info["stu_fail_all_lesson_count"]/100);
        $add_data_list("其他情况", ($lesson_info["fail_all_lesson_count"]-  $lesson_info["tea_fail_all_lesson_count"]- $lesson_info["stu_fail_all_lesson_count"] )/100);

        $add_data_list("到课率",
                       intval(\App\Helper\Common::div_safe(
                           ($lesson_info[ "all_lesson_count"]-$lesson_info["fail_all_lesson_count"]),
                           $lesson_info[ "all_lesson_count"])*100));
        //



        return $this->pageView(__METHOD__, \App\Helper\Utils::list_to_page_info($data_list)  );

    }

    public function order_info() {
        list($start_time,$end_time)= $this->get_in_date_range("2015-01-01",0);

    }
    public  function  valid_user_money_info() {
        list($start_time,$end_time)= $this->get_in_date_range("2015-01-01",0,0,[
            0 => array( "add_time", "注册")
        ]);


        $origin_ex         = $this->get_in_str_val('origin_ex', "");
        $grade = $this->get_in_e_grade(-1);
        $phone_location = $this->get_in_str_val("phone_location","");
        $origin_from_user_flag = $this->get_in_e_boolean(-1,"origin_from_user_flag");
        $competition_flag = $this->get_in_e_boolean(-1,"competition_flag");
        $subject = $this->get_in_e_subject(-1);
        $userid_query_arr=[
            "start_time" => $start_time,
            "end_time" => $end_time,
            "origin_from_user_flag" => $origin_from_user_flag,
            "origin_ex" => $origin_ex,
            "subject" => $subject,
            "phone_location" => $phone_location,
            "grade" => $grade,
            "competition_flag" => $competition_flag,
        ];
        list($check_start_time,$check_end_time)= $this->get_in_date_range(0,0,0,[
            0 => array( "check_time", "统计时间")],3,1);
        //$check_fie
        $check_field_id =$this->get_in_int_val("check_field_id",1);
        $check_field_config=[
            1=> ["年级","grade", E\Egrade::class ],
            2=> ["科目","subject", E\Esubject::class ],
            3=> ["地区","phone_location", "" ],
            4=> ["渠道","origin", "" ],
        ];

        $data_map=[];

        $check_item=$check_field_config[$check_field_id];
        $field_name= $check_item[1];
        $field_class_name= $check_item[2];
        $add_date_map=function( $field_data_list, $field_data_name_list  ) use( &$data_map) {
            foreach ( $field_data_list as $item ) {
                $check_value=$item["check_value"];
                if (!isset($data_map[$check_value] ) ) {
                    $data_map[$check_value]=["check_value"=> $check_value ];
                }
                if (!is_array($field_data_name_list  ) ) {
                    $field_data_name_list  =[ $field_data_name_list   ];
                }
                foreach  ( $field_data_name_list as  $field_data_name )  {
                    $data_map[$check_value][$field_data_name]= intval(  $item[$field_data_name]*100)/100;
                }
            }
        };

        //	平均消耗课时
        $this->t_student_info->switch_tongji_database();
        //$lesson_info=$this->t_student_info->tongji_get_lesson_start_by_field_name( $field_name,$userid_query_arr,$check_start_time, $check_end_time  );
        //$add_date_map($lesson_info, "avg_lesson_count") ;

        //续费单笔	续费次数	续费人数
        $contract_type_3_list= $this->t_student_info->tongji_get_contract_type_3_by_field_name( $field_name,$userid_query_arr,$check_start_time, $check_end_time  );
        $func=function() use(&$contract_type_3_list)  {
            foreach ($contract_type_3_list as &$item ) {
                $item["contract_type_3_avg_money"] =$item[  "contract_type_3_all_money"]/  $item["contract_type_3_count"];
            }
        } ;
        $func();
        $add_date_map( $contract_type_3_list, [
            "contract_type_3_avg_money", "contract_type_3_count",  "contract_type_3_all_money", "contract_type_3_user_count"
        ]) ;


        //转介绍人数	转介绍成功人数
        //
        //(
        $origin_user_list= $lesson_info=$this->t_student_info->tongji_get_origin_user_by_field_name( $field_name,$userid_query_arr,$check_start_time, $check_end_time  );

        $add_date_map( $origin_user_list, [
            "origin_user_count", "succ_origin_user_count"
        ]) ;



        //reset title
        foreach ($data_map as &$item ) {
            if($field_class_name ) {
                $item["title"]= $field_class_name::get_desc($item["check_value"]);
            }else{
                $item["title"]= $item["check_value"];
            }
            if ($field_name=="origin") {
                $item["origin"]= $item["title"];
            }
        }

        if ($field_name=="origin") {
            $data_map=  (new tongji_ss ())->gen_origin_data($data_map,[  "avg_lesson_count", "contract_type_3_avg_money" ] );
        }else{
            $all_item=["title" => "全部"];
            \App\Helper\Utils::list_add_sum_item(
                $data_map, $all_item,
                [
                    "contract_type_3_count",
                    "contract_type_3_user_count",
                    "origin_user_count",
                    "succ_origin_user_count",

                ]);
        }


        return $this->pageView(__METHOD__,\App\Helper\Utils::list_to_page_info($data_map),[
            "field_name" => $field_name
        ]);

    }

    public  function  valid_user_region()
    {
        list($start_time,$end_time)= $this->get_in_date_range("2015-01-01",0);
        $origin_ex         = $this->get_in_str_val('origin_ex', "");
        $grade = $this->get_in_e_grade(-1);
        $phone_location = $this->get_in_str_val("phone_location","");
        $origin_from_user_flag = $this->get_in_e_boolean(-1,"origin_from_user_flag");

        $competition_flag = $this->get_in_e_boolean(-1,"competition_flag");

        $subject = $this->get_in_e_subject(-1);
        $userid_query_arr=[
            "start_time" => $start_time,
            "end_time" => $end_time,
            "origin_from_user_flag" => $origin_from_user_flag,
            "origin_ex" => $origin_ex,
            "subject" => $subject,
            "phone_location" => $phone_location,
            "grade" => $grade,
            "competition_flag" => $competition_flag,
        ];

        $data_list=$this->t_student_info->tongji_get_region_info( $userid_query_arr,  "phone_location" );

        $reg_map=[ ];
        $count_all=0;
        foreach ($data_list as $item ) {
            $key= substr( $item["phone_location"],0,-6);
            if($key){
                $reg_map[$key]=@$reg_map[$key] +  $item["count"] ;
            }
            $count_all+= $item["count"];

        }

        $list=[];
        foreach($reg_map as $key=>$count){
            $list[]=[ "region" => $key, "count" => $count, "percent"=>intval($count*100/$count_all ) ];
        }

        usort( $list , function( $a, $b){
            return \App\Helper\Common::sort_value_desc_func($a["count"],$b["count"] );
        } );


        $grade_list=$this->t_student_info->tongji_get_region_info( $userid_query_arr, "grade" );
        foreach( $grade_list  as &$g_item ) {
            E\Egrade::set_item_value_str($g_item);
            $g_item["percent"]= intval($g_item["count"]*100/$count_all ) ;
        }


        $subject_list=$this->t_student_info-> tongji_get_subject( $userid_query_arr );
        $subject_map=[];
        $competition_flag_map=[];
        $user_map=[];

        $subject_all_count=0;
        foreach ($subject_list as  $s_item) {
            $competition_flag1=$s_item["competition_flag"];
            $competition_flag_map[$competition_flag1] =@$competition_flag_map[$competition_flag1]+1;

            $subject1=$s_item["subject"];
            $userid=$s_item["userid"];
            $subject_map[$subject1] = @$subject_map[$subject1] +1;
            $user_map[$userid] = @$user_map[$userid] +1;
            $subject_all_count++;
        }
        //科目
        $subject_list=[];
        foreach($subject_map as $key=>$count){
            $subject_list[]=[ "subject" => $key, "subject_str" => E\Esubject::get_desc($key), "count" => $count, "percent"=>intval($count*100/$subject_all_count) ];
        }

        usort( $subject_list, function( $a, $b){
            return \App\Helper\Common::sort_value_desc_func($a["count"],$b["count"] );
        } );

        $user_subject_count_map=[];
        foreach($user_map as $key=>$count){
            $user_subject_count_map[$count]= @$user_subject_count_map[$count]+1;
        }

        $user_subject_count_list=[];
        foreach( $user_subject_count_map as $key=>$count){
            $user_subject_count_list[]=[ "subject_count" => $key,  "count" => $count, "percent"=>intval($count*100/$count_all ) ];
        }

        usort( $user_subject_count_list, function( $a, $b){
            return \App\Helper\Common::sort_value_desc_func($a["count"],$b["count"] );
        } );

        //渠道分布
        $origin_list=$this->t_student_info->tongji_get_region_info( $userid_query_arr, "s.origin" );

        //是否转介绍
        $origin_userid_list=$this->t_student_info->tongji_get_region_info(  $userid_query_arr, "origin_userid" );
        $no_origin_userid_count=0;
        foreach ($origin_userid_list  as $item ) {
            if ($item["origin_userid"] ==0 ) {
                $no_origin_userid_count= $item["count"];
                break;
            }
        }


        $origin_list =  (new tongji_ss ())->gen_origin_data($origin_list);







        return $this->pageView(__METHOD__,\App\Helper\Utils::list_to_page_info($list), [
            "grade_list" => $grade_list,
            "subject_list" => $subject_list,
            "user_count" => $count_all ,
            "user_subject_count_list" => $user_subject_count_list,
            "origin_list" => $origin_list,
            "no_origin_userid_count" => $no_origin_userid_count,
            "competition_flag_map" => $competition_flag_map,
        ]);
    }

    public function self_seller_month_money_list() {
        $this->set_in_value("adminid" , $this->get_account_id());
        list($start_time,$end_time )= $this->get_in_date_range_month(0);
        $check_start_time=strtotime("2017-04-01");
        if ($start_time < $check_start_time  ) {
            return $this->output_err("不可查看");
        }

        return $this->seller_month_money_list();
    }

    public function seller_month_money_list() {
        $adminid=$this->get_in_adminid(-1);
        $common_new = new \App\Http\Controllers\common_ex;
        $group_adminid_list = $common_new->get_group_adminid_list();
        //$ret_info= $this->t_manager_info->get_admin_member_list(  E\Emain_type::V_2,$adminid );
        list($start_time,$end_time )= $this->get_in_date_range_month(0);
        $month = strtotime( date("Y-m-01", $start_time));

        $ret_info= $this->t_manager_info->get_admin_member_list_new($month ,E\Emain_type::V_2,$adminid,$group_adminid_list);
        $admin_list=&$ret_info["list"];
        $account_role= E\Eaccount_role::V_2;
        $order_user_list=$this->t_order_info->get_admin_list($start_time,$end_time,$account_role,$group_adminid_list);
        $map=[];
        foreach($ret_info["list"] as $item ) {
            $map[$item["adminid"] ]=true;
            // $sys_operator = $item["account"];
            // $sort_money = $this->t_order_info->get_sort_order_count_money($sys_operator,$start_time,$end_time);
            // $item["stage_money"] = @$sort_moeny["stage_money"];
            // $item["no_stage_money"] = @$sort_moeny["no_stage_money"];
        }
        //unset($item);
        foreach($order_user_list as $item ) {
            if(!@$map[$item["adminid"]] ) {
                if ($adminid == -1  && $adminid==  $item["adminid"]   ) {
                    $ret_info["list"][]=["adminid" => $item["adminid"] ];
                }
            }
        }
        // $admin_list=\App\Helper\Common::gen_admin_member_data($admin_list, [],0, strtotime( date("Y-m-01",$start_time )));
        $admin_list=\App\Helper\Common::gen_admin_member_data_new($admin_list, [],0, strtotime( date("Y-m-01",$start_time )),$group_adminid_list);
        foreach( $admin_list as &$item ) {
            $item["become_member_time"] = isset($item["become_member_time"])?(isset($item["create_time"])?($item["become_member_time"]>$item["create_time"]?$item["become_member_time"]:$item["create_time"]):0):0;
            $item["leave_member_time"] = isset($item["leave_member_time"])?$item["leave_member_time"]:0;
            $item["del_flag"] = isset($item["del_flag"])?$item["del_flag"]:0;
            E\Emain_type::set_item_value_str($item);
            E\Eseller_level::set_item_value_str($item);
            if($item['level'] == "l-5" ){
                \App\Helper\Utils::unixtime2date_for_item($item,"become_member_time",'','Y-m-d');
                \App\Helper\Utils::unixtime2date_for_item($item,"leave_member_time",'','Y-m-d');
                $item["del_flag_str"] = \App\Helper\Common::get_boolean_color_str($item["del_flag"]);
            }else{
                $item["become_member_time"] = '';
                $item["leave_member_time"] = '';
                $item["del_flag_str"] = '';
            }
        }
        //周试听成功自定义时间
        list($week[E\Eweek_order::V_1],$week[E\Eweek_order::V_2],$week[E\Eweek_order::V_3],$week[E\Eweek_order::V_4],$ret_week[E\Eweek_order::V_1],$ret_week[E\Eweek_order::V_2],$ret_week[E\Eweek_order::V_3],$ret_week[E\Eweek_order::V_4]) = [[],[],[],[],'','','',''];
        $week_info = $this->t_month_def_type->get_month_week_time($month);
        foreach($week_info as $item_k){
            $week_order = $item_k['week_order'];
            $start_time = date('m/d',$item_k['start_time']);
            $end_time = date('m/d',$item_k['end_time']-1);
            if($week_order == E\Eweek_order::V_1){
                $week[E\Eweek_order::V_1][] = $start_time.'-'.$end_time;
            }elseif($week_order == E\Eweek_order::V_2){
                $week[E\Eweek_order::V_2][] = $start_time.'-'.$end_time;
            }elseif($week_order == E\Eweek_order::V_3){
                $week[E\Eweek_order::V_3][] = $start_time.'-'.$end_time;
            }elseif($week_order == E\Eweek_order::V_4){
                $week[E\Eweek_order::V_4][] = $start_time.'-'.$end_time;
            }
        }
        foreach($week as $key=>$item_w){
            foreach($item_w as $key_n=>$info){
                if($key_n>0){
                    $ret_week[$key] = $ret_week[$key].','.$info;
                }else{
                    $ret_week[$key] = $info;
                }
            }
        }

        return $this->pageView(__METHOD__,$ret_info,[
            'first_week'=>$ret_week[E\Eweek_order::V_1],
            'second_week'=>$ret_week[E\Eweek_order::V_2],
            'third_week'=>$ret_week[E\Eweek_order::V_3],
            'four_week'=>$ret_week[E\Eweek_order::V_4],
        ]);
    }

    public function test_lesson_frist_call_time_master(){
        $adminid = $this->get_account_id();
        if($adminid==349 || $adminid==99){
            $adminid=314;
        }
        $master_info = $this->t_admin_group_name->get_all_info_by_master_adminid(2,$adminid);
        if(!$master_info){
            return $this->error_view(
                [
                   "本页面只供销售主管查看!"
                ]
            );

        }
        $master_flag = "销售,".$master_info["master_group_name"].",".$master_info["group_name"].",";
        // dd($master_flag);
        $this->set_in_value("master_flag",$master_flag);
        return $this->test_lesson_frist_call_time();
        // dd($master_info);
    }

    public function test_lesson_frist_call_time() {
        list($start_time, $end_time) = $this->get_in_date_range_day(-1);
        $page_num                    = $this->get_in_page_num();
        $seller_groupid_ex           = $this->get_in_str_val('seller_groupid_ex', "销售,,,");
        $master_flag                 = $this->get_in_str_val('master_flag',"");
        if($master_flag){
            $seller_groupid_ex = $master_flag;
        }
        $require_adminid_list        = $this->t_admin_main_group_name->get_adminid_list_new($seller_groupid_ex);
        $lesson_user_online_status   = $this->get_in_e_set_boolean(-1,"lesson_user_online_status");
        $test_assess_flag   = $this->get_in_e_set_boolean(-1,"test_assess_flag");

        list( $order_in_db_flag, $order_by_str, $order_field_name,$order_type )
            =$this->get_in_order_by_str([],"lesson_start asc",[
                "duration" => "(min(tq.start_time) -l.lesson_start ) ",
                "last_tq_call_time" => "max(tq.start_time)" ,
                "tq_call_count" => "sum(tq.duration)" ,
                "tq_call_time" => "min( start_time)",
                "tq_call_all_time" => "sum(tq.duration)",
            ] );
        $ret_info = $this->t_lesson_info_b2-> get_test_lesson_first_list($page_num, $order_by_str ,$start_time,$end_time  ,$require_adminid_list, $lesson_user_online_status,$test_assess_flag );
        $call_count=0;
        $call_15min_count=0;
        $call_time_all=0;
        $no_call_count=0;
        foreach ($ret_info["list"]  as &$item ) {
            if ( $item["tq_call_time"] ) {
                $item["duration"]= $item["tq_call_time"] - $item["lesson_start"]  ;
                $item["duration_str"]= \App\Helper\Common::get_time_format( $item["duration"]);
                $call_count++;
                if ($item["duration"]<55*60) { //一
                    $call_15min_count++;
                }
                $call_time_all+=$item["duration"];
            }else{
                $item["duration"]= -1;
                $item["duration_str"]= "无" ;
                $no_call_count++;
            }

            $item["price"]/=100;
            if ($item["price"]==0){
                $item["price"] ="--";
            }


            $item["tq_call_all_time"] = \App\Helper\Common::get_time_format($item["tq_call_all_time"] );


            \App\Helper\Utils::unixtime2date_for_item($item,"lesson_start");
            \App\Helper\Utils::unixtime2date_for_item($item,"lesson_end");
            \App\Helper\Utils::unixtime2date_for_item($item,"tq_call_time");
            \App\Helper\Utils::unixtime2date_for_item($item,"last_tq_call_time");
            \App\Helper\Utils::unixtime2date_for_item($item,"order_time");
            E\Eset_boolean::set_item_value_str($item,"lesson_user_online_status");

            $item["lesson_user_online_status_str"]=\App\Helper\Common::get_set_boolean_color_str( $item["lesson_user_online_status"] );


            $this->cache_set_item_account_nick($item,"cur_require_adminid","cur_require_admin_nick");


        }

        return $this->pageView(__METHOD__,$ret_info,[
            "no_call_count" => $no_call_count,
            "call_count" => $call_count,
            "call_15min_count" => $call_15min_count,
            "avg_call_duration" => \App\Helper\Common::get_time_format(
                \App\Helper\Common::div_safe( $call_time_all,$call_count)),
        ]);
    }

    public function check_up_group_adminid(){
        $adminid = $this->get_account_id();
        $groupid = $this->t_admin_group_name->get_groupid_by_master_adminid($adminid);
        $up_groupid = $this->t_admin_main_group_name->get_groupid_by_master_adminid($adminid);
        $ret = 0;
        if($groupid || $up_groupid){
            $ret = 1;
        }
        return $ret;
    }

    public function seller_student_admin_list() {
        $this->check_and_switch_tongji_domain();
        $del_flag=$this->get_in_e_boolean(-1,"del_flag");
        $ret_info=$this->t_seller_student_new->admin_list($del_flag);
        foreach ($ret_info["list"]  as  &$item) {
            E\Eboolean::set_item_value_simple_str($item,"del_flag");
        }
        return $this->pageView(__METHOD__,$ret_info);
    }


    public function seller_active() {

        list($start_time,$end_time )= $this->get_in_date_range_day(0);

        $ret_info= $this->t_manager_info->get_admin_member_list(  E\Emain_type::V_2,$adminid );

        $obj_list=&$ret_info["list"];
        //得到tq 通时

        foreach ($tl_info["list"] as $tl_item) {
            $k=$tl_item["adminid"];
            \App\Helper\Utils::array_item_init_if_nofind($obj_list,$k, ["adminid"=>$k] );
            $obj_list[$k]["tq_time"]=$tl_item["tq_time"];

        }



        $admin_list=\App\Helper\Common::gen_admin_member_data($admin_list);

        foreach( $admin_list as &$item ) {
            E\Emain_type::set_item_value_str($item);
        }

        return $this->pageView(__METHOD__,$ret_info);
    }


    public function referral_count(){
        $sum_field_list=[
            "total_num",
            "price_num",
            "orderid_num",
            "contract_num",
            "userid_num",
        ];

        $order_field_arr =  array_merge(["account" ] ,$sum_field_list );

        $group_adminid   = $this->get_in_int_val("group_adminid",-1);

        list( $order_in_db_flag, $order_by_str, $order_field_name,$order_type )
            =$this->get_in_order_by_str($order_field_arr ,"account desc");

        $group_list   = $this->t_admin_group_name->get_group_list(2);
        $groupid      = $this->get_in_int_val("groupid", -1);
        list($start_time,$end_time)= $this->get_in_date_range_month( 0 );
        $origin_ex           = $this->get_in_str_val("origin_ex");

        // 处理例子总数
        $group_field = "origin_assistantid";
        $ret_info    = $this->t_student_info->get_referral_info( $group_field,$start_time, $end_time );

        $admin_info = $this->t_manager_info->get_admin_member_list();
        $admin_list = &$admin_info['list'] ;
        if ($group_adminid >0) {
            $groupid=$this->t_admin_group_name->get_groupid_by_master_adminid($group_adminid);
            $mark_user_map= $this->t_admin_group_user->get_user_map($groupid);
        }

        foreach ($admin_list as $vk=>&$val){
            $adminid=$val['adminid'];
            if (!isset($ret_info[$adminid ] )
                || ( $group_adminid >0 &&  !isset($mark_user_map[ $adminid ] ) )  )  {
                unset( $admin_list[$vk] );
            }else{

                $val['admin_revisiterid'] = $adminid ;
                $ret_item=@$ret_info[$adminid];
                $val['total_num']         = @$ret_item['total_num'];
                $val['price_num']         = @$ret_item['price_num'];
                $val['orderid_num']       = @$ret_item['orderid_num'];
                $val['userid_num']        = @$ret_item['userid_num'];
            }
        }
        $ret_info=\App\Helper\Common::gen_admin_member_data($admin_info['list']);
        // dd($ret_info);
        foreach( $ret_info as &$item ) {
            E\Emain_type::set_item_value_str($item);
            $item['price_num']  = @$item['price_num']/100;
        }
        return $this->pageView(__METHOD__,\App\Helper\Utils::list_to_page_info($ret_info),["data_ex_list"=>$ret_info]);

    }

    public function change_week_value($value){
        return date('Y-m-d',$value);
    }
    public function seller_week_lesson_master(){
        $this->set_in_value("group_adminid",$this->get_account_id());
        return $this->seller_week_lesson();
    }

    public function seller_week_lesson(){
        list($start_time, $end_time) = $this->get_in_date_range_week(0);
        $group_adminid = $this->get_in_int_val("group_adminid",-1);
        $adminid_list=null;

        $adminid           = $this->get_account_id();
        $adminid_right     = $this->get_seller_adminid_and_right();
        $seller_groupid_ex = $this->get_in_str_val('seller_groupid_ex', "");
        $adminid_list = $this->t_admin_main_group_name->get_adminid_list_new($seller_groupid_ex);

        if($group_adminid >0) {
            $groupid       = $this->t_admin_group_name->get_groupid_by_master_adminid($group_adminid);
            $mark_user_map = $this->t_admin_group_user->get_user_map($groupid);
            foreach($mark_user_map as $key=>$v){
                $adminid_list[] =$v;
            }
        }

        $week_start_time = [$start_time,$start_time + 3600*24,$start_time+3600*24*2,$start_time+3600*24*3,$start_time+3600*24*4,$start_time+3600*24*5,$start_time+3600*24*6];
        $week_info       = array_map(array($this,'change_week_value'),$week_start_time);

        $week_item_list =[];
        foreach( $week_info as $week_date ){
            $week_item_list[$week_date]  =["lesson_count"=>0,"require_lesson_count"=>0,"need_lesson_count"=>0 ] ;
        }
        $ret_map = array();
        $lesson_info      = $this->t_lesson_info_b2->get_seller_week_lesson($start_time,$end_time,$adminid_list);
        $require_list     = $this->t_test_lesson_subject_require->get_require_noset_lesson_count($start_time,$end_time,$adminid_list);
        $need_list = $this->t_test_lesson_subject_require->get_need_require_lesson_count($start_time,$end_time,$adminid_list);
        foreach($lesson_info as $l_item ) {
            $adminid     = $l_item['adminid'];
            $lesson_date = date("Y-m-d", $l_item["lesson_start" ]);
            if(!isset($ret_map[$adminid])){
                $ret_map[$adminid] = $week_item_list;
            }
            $ret_map[$adminid][$lesson_date]["lesson_count"] ++;
        }
        foreach($require_list as $require_item ) {
            $adminid  = $require_item['adminid'];
            $opt_date = date("Y-m-d", $require_item["opt_time" ]);
            if(!isset($ret_map[$adminid])){
                $ret_map[$adminid] = $week_item_list;
            }
            $ret_map[$adminid][$opt_date]["require_lesson_count"] ++;
        }
        foreach($need_list as $need_item){
            $adminid = $need_item['adminid'];
            $opt_date=  date("Y-m-d", $need_item["opt_time" ]);
            if(!isset($ret_map[$adminid])){
                $ret_map[$adminid] = $week_item_list;
            }
            $ret_map[$adminid][$opt_date]["need_lesson_count"] ++;
        }
        $ret_list=[];
        foreach( $ret_map  as $adminid=> $r_item)  {
            $item=["adminid" =>  $adminid  ,
                   "v_week_lesson_count" => 0 ,
                   "v_week_require_lesson_count"=>0,
                   "v_week_all_lesson_count"=>0,
                   "v_week_need_lesson_count"=>0,
            ] ;
            $i=0;
            $all_info=[];
            foreach( $r_item as  $v_item ) {
                $item["v_$i"."_lesson_count" ]         = $v_item["lesson_count"];
                $item["v_$i"."_require_lesson_count" ] = $v_item["require_lesson_count"];
                $item["v_$i"."_all_lesson_count" ]     = $v_item["lesson_count"]+$v_item["require_lesson_count"];
                $item["v_$i"."_need_lesson_count" ]    = $v_item["need_lesson_count"]-$item["v_$i"."_all_lesson_count" ];

                $item["v_week_lesson_count"]         += $item["v_$i"."_lesson_count" ];
                $item["v_week_require_lesson_count"] += $item["v_$i"."_require_lesson_count" ];
                $item["v_week_all_lesson_count"]     += $item["v_$i"."_all_lesson_count" ];
                $item["v_week_need_lesson_count"]    += $item["v_$i"."_need_lesson_count" ];

                $i++;
            }
            $ret_list[]= $item;
        }
        $ret_list=\App\Helper\Common::gen_admin_member_data($ret_list ,[],0, strtotime( date("Y-m-01",$start_time )   ));
        foreach( $ret_list as &$ad_item){
            E\Emain_type::set_item_value_str($ad_item);
        }
        return $this->pageView(__METHOD__, \App\Helper\Utils::list_to_page_info($ret_list)  , [
            "adminid_right" => $adminid_right,
            "adminid"       => $adminid,
            "week_info"     => $week_info]
        );
    }

    public function seller_week_lesson_call_master(){
        $this->set_in_value("group_adminid",$this->get_account_id());
        $adminid_right     = $this->get_seller_adminid_and_right();
        $seller_groupid_ex= join(",",$adminid_right);
        $this->set_in_value("seller_groupid_ex",$seller_groupid_ex);

        return $this->seller_week_lesson_call();
    }

    public function seller_week_lesson_call(){
        list($start_time, $end_time) = $this->get_in_date_range_week(0);
        $group_adminid = $this->get_in_int_val("group_adminid",-1);
        $adminid_list  = null;

        $adminid           = $this->get_account_id();
        $seller_groupid_ex = $this->get_in_str_val('seller_groupid_ex', "");
        $adminid_list = $this->t_admin_main_group_name->get_adminid_list_new($seller_groupid_ex);

        if($group_adminid >0) {
            $groupid       = $this->t_admin_group_name->get_groupid_by_master_adminid($group_adminid);
            $mark_user_map = $this->t_admin_group_user->get_user_map($groupid);
            foreach($mark_user_map as $key=>$v){
                $adminid_list[] =$v;
            }
        }

        $week_start_time = [$start_time,$start_time + 3600*24,$start_time+3600*24*2,$start_time+3600*24*3,$start_time+3600*24*4,$start_time+3600*24*5,$start_time+3600*24*6];
        $week_info       = array_map(array($this,'change_week_value'),$week_start_time);

        $week_item_list = [];
        foreach( $week_info as $week_date ){
            $week_item_list[$week_date]  =["lesson_count"=>0,"lesson_call_before_count"=>0,"lesson_call_end_count"=>0 ] ;
        }
        $ret_map = array();
        $lesson_info = $this->t_lesson_info_b2->get_seller_week_lesson($start_time,$end_time,$adminid_list);
        foreach($lesson_info as $l_item ){
            $adminid     = $l_item['adminid'];
            $lesson_date = date("Y-m-d", $l_item["lesson_start" ]);
            if(!isset($ret_map[$adminid])){
                $ret_map[$adminid] = $week_item_list;
            }
            $ret_map[$adminid][$lesson_date]["lesson_count"] ++;
            if($l_item['call_before_time']){
                $ret_map[$adminid][$lesson_date]["lesson_call_before_count"] ++;
            }
            if($l_item['call_end_time']){
                $ret_map[$adminid][$lesson_date]["lesson_call_end_count"] ++;
            }
        }

        $ret_list=[];
        foreach( $ret_map  as $adminid=> $r_item)  {
            $item=["adminid" =>  $adminid  ,
                   "v_week_lesson_count" => 0 ,
                   "v_week_lesson_call_before_count"=>0,
                   "v_week_lesson_call_end_count"=>0,
            ] ;
            $i=0;
            $all_info=[];
            foreach( $r_item as  $v_item ) {
                $item["v_$i"."_lesson_count" ]         = $v_item["lesson_count"];
                $item["v_$i"."_lesson_call_before_count" ] = $v_item["lesson_call_before_count"];
                $item["v_$i"."_lesson_call_end_count" ]     = $v_item["lesson_call_end_count"];

                $item["v_week_lesson_count"]         += $item["v_$i"."_lesson_count" ];
                $item["v_week_lesson_call_before_count"] += $item["v_$i"."_lesson_call_before_count" ];
                $item["v_week_lesson_call_end_count"]     += $item["v_$i"."_lesson_call_end_count" ];

                $i++;
            }
            $ret_list[]= $item;
        }
        $ret_list=\App\Helper\Common::gen_admin_member_data($ret_list ,[],0, strtotime( date("Y-m-01",$start_time )));
        foreach( $ret_list as &$ad_item){
            E\Emain_type::set_item_value_str($ad_item);
        }
        return $this->pageView(__METHOD__, \App\Helper\Utils::list_to_page_info($ret_list)  , [
            "week_info" =>  $week_info,
            "adminid"           => $adminid,
        ]);
    }

    public function lesson_call_list(){
        list($start_time, $end_time) = $this->get_in_date_range_week(0);
        $adminid           = $this->get_account_id();
        $adminid_right     = $this->get_seller_adminid_and_right();
        $seller_groupid_ex = $this->get_in_str_val('seller_groupid_ex', "");
        $adminid_list = $this->t_admin_main_group_name->get_adminid_list_new($seller_groupid_ex);
        $stu_phone = $this->get_in_str_val('stu_phone');

        $ret_list = array();
        $ret_list = $this->t_lesson_info_b2->get_seller_week_lesson($start_time,$end_time,$adminid_list);
        foreach($ret_list as $key=>$item){
            \App\Helper\Utils::unixtime2date_for_item($item, "call_before_time", "", "Y-m-d H:i");
            \App\Helper\Utils::unixtime2date_for_item($item, "call_end_time", "", "Y-m-d H:i");
            \App\Helper\Utils::unixtime2date_for_item($item, "lesson_start", "", "Y-m-d H:i");
            $ret_list[$key]['call_before_time'] = $item['call_before_time'];
            $ret_list[$key]['call_end_time'] = $item['call_end_time'];
            $ret_list[$key]['lesson_start'] = $item['lesson_start'];
        }
        return $this->pageView(__METHOD__, \App\Helper\Utils::list_to_page_info($ret_list),[
            "adminid_right"     => $adminid_right,
            "adminid"           => $adminid
        ]);
    }

    public function lesson_after_call_list(){
        // $this->
        $this->t_test_lesson_subject_sub_list->get_no_after_call();
    }

    public function first_call_info() {
        list($start_time,$end_time)= $this->get_in_date_range_week(0);
        $origin_ex    = $this->get_in_str_val('origin_ex', "");
        $list=$this->t_seller_student_new->get_first_call_time_list(  $start_time, $end_time,$origin_ex );
        $ret_map=[];
        $diff_list=[0, 1, 5,15, 60, 60*12, 60*24 , 60*48,  0xFFFFFFFF ];
        $diff_title_list=["未回访", "1分钟", "5分钟", "15分钟",  "1小时", "半天", "一天", "两天", "两天以上"];
        $base_row_item=["v_all_count"=>0 ];
        $sum_field_list=["v_all_count"];

        foreach( $diff_list as $check_diff_value ) {
            $base_row_item["v_$check_diff_value"]=0;
            $sum_field_list[]="v_$check_diff_value";
        }

        for($i=0;$i<24;$i++) {
            $row_item= $base_row_item;
            $row_item["title"]=sprintf("%02d:00-%02d:59" ,$i,$i) ;
            $ret_map[$i]= $row_item;
        }


        foreach ($list as $item )  {
            $add_time= $item["add_time"];
            $hour=intval( date("H", $add_time));
            $diff=0;
            if ( $item["first_call_time"] >0 &&$item["first_call_time"]< $add_time ) {
                continue;
            }

            if ($item["first_call_time"]> $add_time ) {
                $diff= ($item["first_call_time"]- $add_time)/60;
            }

            $ret_map[$hour]["v_all_count"]++;
            foreach( $diff_list as $check_diff_value ) {
                if ( $diff <= $check_diff_value ) {
                    $ret_map[$hour]["v_".$check_diff_value]++;
                    break;
                }
            }
        }

        $all_item = ["title" => "全部"];

        \App\Helper\Utils::list_add_sum_item( $ret_map,$all_item,$sum_field_list );

        return $this->pageView(__METHOD__,\App\Helper\Utils::list_to_page_info($ret_map),[
            "diff_list"  => $diff_list,
            "diff_title_list"  => $diff_title_list,
        ]);

    }

    public function ass_month_kpi_tongji_master(){
        $adminid = $this->get_account_id();
         if($adminid==349){
           $adminid=297;
           }
        $this->set_in_value("adminid",$adminid);
         return $this->ass_month_kpi_tongji();

    }

    public function ass_month_kpi_tongji(){
        $this->switch_tongji_database();
        list($start_time,$end_time) = $this->get_in_date_range(0,0,0,[],3);
        $account_id    = $this->get_in_int_val('adminid',-1);

        // $ass_list = $this->t_manager_info->get_adminid_list_by_account_role(1); //uid,account,a.nick,m.name
        $cur_start = strtotime(date("Y-m-01",time()));
        if($start_time < $cur_start){
            $history_flag=1;
        }else{
            $history_flag=0;
        }
        $ass_list = $this->t_manager_info->get_adminid_list_by_account_role_new(1,$start_time,$history_flag);
        $month_middle = $start_time+15*86400;

        /* $lesson_list_first = $this->t_lesson_info_b2->get_all_ass_stu_lesson_info($start_time,$month_middle);
        $userid_list_first=[];
        $userid_list_first_all=[];
        foreach($lesson_list_first as $item){
            $userid_list_first[$item["uid"]][]=$item["userid"];
            $userid_list_first_all[] = $item["userid"];
        }
        $xq_revisit_first = $this->t_revisit_info->get_ass_xq_revisit_info_new($start_time,$month_middle,$userid_list_first_all,false);

        $lesson_list_second = $this->t_lesson_info_b2->get_all_ass_stu_lesson_info($month_middle,$end_time);
        $userid_list_second=[];
        $userid_list_second_all=[];
        foreach($lesson_list_second as $item){
            $userid_list_second[$item["uid"]][]=$item["userid"];
            $userid_list_second_all[] = $item["userid"];
        }

        $xq_revisit_second = $this->t_revisit_info->get_ass_xq_revisit_info_new($month_middle,$end_time,$userid_list_second_all,false);*/

        $warning_info    = $this->t_month_ass_student_info->get_ass_month_info($start_time);


        $last_month  = strtotime(date('Y-m-01',$start_time-100));
        $ass_last_month    = $this->t_month_ass_student_info->get_ass_month_info($last_month,-1,1);
        /* $assistant_renew_list = $this->t_manager_info->get_all_assistant_renew_list_new($start_time,$end_time);

        $new_info = $this->t_student_info->get_ass_new_stu_first_revisit_info($start_time,$end_time);
        $new_revisit=[];
        foreach($new_info as $v){
            @$new_revisit[$v["uid"]]["new_num"]++;
            if($v["revisit_time"]>0){
                @$new_revisit[$v["uid"]]["first_num"]++;
            }else{
                @$new_revisit[$v["uid"]]["un_first_num"]++;
            }
        }



        $student_finish = $this->t_student_info->get_ass_first_revisit_info_finish($start_time,$end_time);//结课学生数
        $student_finish_detail = [];
        foreach ($student_finish as $key => $value) {
            $student_finish_detail[$value['uid']] = $value['num'];
            }*/
        /*
        $student_all = $this->t_student_info->get_ass_first_revisit_info();//在册学生数
        $student_all_detail = [];
        foreach ($student_all as $key => $value) {
            $student_all_detail[$value['uid']] = $value['num'];
        }
        */
        //dd($new_revisit);
        /*  $refund_score = $this->get_ass_refund_score($start_time,$end_time);

        $lesson_money_all = $this->t_manager_info->get_assistant_lesson_money_info_all($start_time,$end_time);
        $lesson_count_all = $this->t_manager_info->get_assistant_lesson_count_info_all($start_time,$end_time);
        $lesson_price_avg = !empty($lesson_count_all)?$lesson_money_all/$lesson_count_all:0;
        $lesson_count_list = $this->t_manager_info->get_assistant_lesson_count_info($start_time,$end_time);*/
        //$kk_require_info = $this->t_test_lesson_subject_sub_list->get_kk_require_info($start_time,$end_time,"c.add_time");
        // $kk_require_info = $this->t_course_order->get_kk_succ_info($start_time,$end_time);

        $cur_start = strtotime(date('Y-m-01',$start_time));
        $ass_month= $this->t_month_ass_student_info->get_ass_month_info($cur_start);

        //课时目标系数
        $lesson_target     = $this->t_ass_group_target->get_rate_target($cur_start);
        if(empty($lesson_target)){
            $lesson_target= 14.0;
        }
        $target_info = $this->t_ass_group_target->field_get_list($start_time,"rate_target,renew_target,group_renew_target,all_renew_target");
        foreach($ass_list as $k=>&$val){
            /*$val["userid_list_first"] = isset($userid_list_first[$k])?$userid_list_first[$k]:[];
            $val["userid_list_first_target"] = count($val["userid_list_first"]);
            $val["userid_list_first_count"] = @$xq_revisit_first[$k]["num"];
            $val["userid_list_second"] = isset($userid_list_second[$k])?$userid_list_second[$k]:[];
            $val["userid_list_second_target"] = count($val["userid_list_second"]);
            $val["userid_list_second_count"] = @$xq_revisit_second[$k]["num"];
            $val["revisit_target"] = $val["userid_list_first_target"]+$val["userid_list_second_target"];
            $val["revisit_real"] = $val["userid_list_first_count"]+$val["userid_list_second_count"];*/
            $val["revisit_target"] = isset($ass_month[$k])?$ass_month[$k]["revisit_target"]:0;
            $val["revisit_real"] = isset($ass_month[$k])?$ass_month[$k]["revisit_real"]:0;
            $val["revisit_per"] = !empty( $val["revisit_target"])?round($val["revisit_real"]/$val["revisit_target"]*100,2):0;
            $val["renw_target"]  = @$ass_last_month[$k]["warning_student"]*0.8*7000;
            if($start_time >= strtotime("2017-11-01")){
                $val["renw_target"] = @$target_info["renew_target"]/100;
            }
            $val["renw_target_old"]  = @$ass_last_month[$k]["warning_student"]*0.8*7000;

            // $val["renw_price"] = isset($assistant_renew_list[$k])?$assistant_renew_list[$k]["renw_price"]/100:0;
            $val["renw_price"] = isset($ass_month[$k])?$ass_month[$k]["renw_price"]/100:0;
            $val["renw_per"] = !empty( $val["renw_target"])?round($val["renw_price"]/$val["renw_target"]*100,2):0;
            // $val["tran_price"] = isset($assistant_renew_list[$k])?$assistant_renew_list[$k]["tran_price"]/100:0;
            $val["tran_price"] = isset($ass_month[$k])?$ass_month[$k]["tran_price"]/100:0;
            // $val["all_price"] = isset($assistant_renew_list[$k])?$assistant_renew_list[$k]["all_price"]/100:0;
            $val["all_price"] = $val["renw_price"]+$val["tran_price"];
            // $val["tran_num"] = isset($assistant_renew_list[$k])?$assistant_renew_list[$k]["tran_num"]:0;
            $val["tran_num"] = isset($ass_month[$k])?$ass_month[$k]["tran_num"]:0;
            $val["cc_tran_num"] = isset($ass_month[$k])?$ass_month[$k]["cc_tran_num"]:0;
            // $val["new_num"] = isset($new_revisit[$k])?$new_revisit[$k]["new_num"]:0;
            //$val["first_revisit_num"] = isset($new_revisit[$k]["first_num"])?$new_revisit[$k]["first_num"]:0;
            $val["first_revisit_num"] = isset($ass_month[$k])?$ass_month[$k]["first_revisit_num"]:0;
            $val["un_first_revisit_num"] = isset($ass_month[$k])?$ass_month[$k]["un_first_revisit_num"]:0;
            $val["new_num"] = $val["first_revisit_num"]+$val["un_first_revisit_num"];
            //$val["un_first_revisit_num"] = isset($new_revisit[$k]["un_first_num"])?$new_revisit[$k]["un_first_num"]:0;
            // $val["refund_score"] = round((10-@$refund_score[$k])>=0?10-@$refund_score[$k]:0,2);
            // $val["refund_score"] = round(@$refund_score[$k],2);
            $val["refund_score"] = isset($ass_month[$k])?$ass_month[$k]["refund_score"]/100:0;
            // $val["lesson_money"] = round(@$lesson_count_list[$k]["lesson_count"]*$lesson_price_avg/100,2);
            $val["lesson_money"] = isset($ass_month[$k])?$ass_month[$k]["lesson_price_avg"]/100:0;
            $val["lesson_total"] = isset($ass_month[$k])?$ass_month[$k]["lesson_total"]/100:0;
            $val["kk_succ"] = (isset($ass_month[$k])?$ass_month[$k]["kk_num"]:0)+(isset($ass_month[$k])?$ass_month[$k]["hand_kk_num"]:0);
            $val["cc_tran_money"] = isset($ass_month[$k])?$ass_month[$k]["cc_tran_money"]/100:0;//CC转介绍金额
            $val["hand_tran_num"] = isset($ass_month[$k])?$ass_month[$k]["hand_tran_num"]:0;//手动确认转介绍人数
            // $val["tran_num"] = $val["hand_tran_num"]+$val["tran_num"]+$val["cc_tran_num"];//目前以两者相加为准(9月之后准确)
            $val["tran_num"] = $val["hand_tran_num"]+$val["tran_num"]+$val["cc_tran_num"];//目前以两者相加为准(9月之后准确)


            //$val["student_all"] = isset($student_all_detail[$k])?$student_all_detail[$k]:0;
            // $val["student_finish"] = isset($student_finish_detail[$k])?$student_finish_detail[$k]:0;
            $val["student_finish"] = isset($ass_month[$k])?$ass_month[$k]["student_finsh"]:0;
            //$val["student_online"] = isset($student_online_detail[$k])?$student_online_detail[$k]:0;
            //  $val["student_online"] = isset($lesson_count_list[$k])?$lesson_count_list[$k]["user_count"]:0;
            $val["student_online"] = isset($ass_month[$k])?$ass_month[$k]["read_student_new"]:0;
            $val["lesson_do_per"] = !empty( $val["student_online"])?round($val["lesson_total"]/$val["student_online"]/$lesson_target*100,2):0;
            //$val["student_all"] += $val["student_finish"];
            $val["student_all"] =  isset($ass_month[$k]["all_student_new"])?$ass_month[$k]["all_student_new"]:0;
            if($val['student_all'] > 0){
                $val["student_finish_per"] = round($val["student_finish"]/$val["student_all"]*100,2);
                $val["student_online_per"] = round($val["student_online"]/$val["student_all"]*100,2);
            }else{
                $val["student_finish_per"] = 0;
                $val["student_online_per"] = 0;
            }
            if($val["student_online"]){
                $val["people_per"] = round(($val["lesson_money"]+$val["all_price"])/$val["student_online"],2);
            }else{
                $val["people_per"] = 0;
            }
            $val["kpi"] = 0;
            $val["kpi"] = ((($val["revisit_per"]/100*50)>=50)?50:($val["revisit_per"]/100*50))+((($val["renw_per"]/100*10)>=10)?10:($val["renw_per"]/100*10));
            if((10-$val["un_first_revisit_num"]*5)>0 && $val["kpi"] > 0){
                $val["kpi"] += (10-$val["un_first_revisit_num"]*5);
            }
            if((10-$val["refund_score"]*5)>0 && $val["kpi"] > 0){
                $val["kpi"] += (10-$val["refund_score"]*10);
            }
            $val["kpi"] = round($val["kpi"],2);


            $ass_master_adminid = $val["master_adminid"];
            $val["group_name"] = $val["group_name"];//组别
            if($account_id==-1){

            }else{
                if($ass_master_adminid != $account_id){
                    unset($ass_list[$k]);
                }

            }


        }

        $ass_group=[];
        foreach($ass_list as $key=>$va){
            // echo $key;
            //  $master_adminid_ass_list = $this->t_admin_group_user->get_master_adminid_group_info($key);
            // $master_adminid_ass = $master_adminid_ass_list["master_adminid"];
            $master_adminid_ass = $va["master_adminid"];
            @$ass_group[$master_adminid_ass]["student_all"]  += $va["student_all"];
            @$ass_group[$master_adminid_ass]["student_finish"]     += $va["student_finish"];
            @$ass_group[$master_adminid_ass]["student_online"]     += $va["student_online"];
            @$ass_group[$master_adminid_ass]["lesson_total"]      += $va["lesson_total"];
            @$ass_group[$master_adminid_ass]["lesson_money"]     += $va["lesson_money"];
            @$ass_group[$master_adminid_ass]["renw_target"]           += $va["renw_target"];
            @$ass_group[$master_adminid_ass]["renw_target_old"]           += $va["renw_target_old"];
            @$ass_group[$master_adminid_ass]["renw_price"]       += $va["renw_price"];
            @$ass_group[$master_adminid_ass]["kk_succ"]       += $va["kk_succ"];
            @$ass_group[$master_adminid_ass]["tran_price"]       += $va["tran_price"];
            @$ass_group[$master_adminid_ass]["tran_num"]       += $va["tran_num"];
            @$ass_group[$master_adminid_ass]["cc_tran_money"]       += $va["cc_tran_money"];
            @$ass_group[$master_adminid_ass]["group_name"]       = $va["group_name"];


        }

        foreach($ass_group as $ke=>&$tt){
            if($tt['student_all'] > 0){
                $tt["student_finish_per"] = round($tt["student_finish"]/$tt["student_all"]*100,2);
                $tt["student_online_per"] = round($tt["student_online"]/$tt["student_all"]*100,2);
            }else{
                $tt["student_finish_per"] = 0;
                $tt["student_online_per"] = 0;
            }
            $tt["lesson_do_per"] = !empty( $tt["student_online"])?round($tt["lesson_total"]/$tt["student_online"]/$lesson_target*100,2):0;
            if($start_time>=strtotime("2018-01-01")){
                $tt["renw_target"] = @$target_info["group_renew_target"]/100;
            }
            $tt["renw_per"] = !empty( $tt["renw_target"])?round($tt["renw_price"]/$tt["renw_target"]*100,2):0;
            if($tt["student_online"]){
                $tt["people_per"] = round(($tt["lesson_money"]+$tt["renw_price"]+$tt["tran_price"])/$tt["student_online"],2);
            }else{
                $tt["people_per"] = 0;
            }
            if(empty($tt["group_name"])){
                unset($ass_group[$ke]);
            }



        }

        if(!empty($ass_list)){
            foreach($ass_list as $v){
                $flag[] = $v['people_per'];
            }


            array_multisort($flag, SORT_DESC, $ass_list);
        }
        return $this->pageView(__METHOD__,null,[
            "ass_list"=>$ass_list,
            "ass_group"=>$ass_group
        ]);
    }

    public function seller_origin_info() {
        list($start_time,$end_time)= $this->get_in_date_range_month(0);
        $origin_ex           = $this->get_in_str_val("origin_ex");
        $origin_level  = $this->get_in_el_origin_level();
        $tmk_student_status= $this->get_in_el_tmk_student_status();

        $old_ret_info=$this->t_test_lesson_subject->get_seller_new_user_count( $end_time-90*86400,$end_time , -1, $origin_ex ,$origin_level,$tmk_student_status );

        $old_new_user_count=0;
        foreach ($old_ret_info["list"] as $ol_item) {
            $old_new_user_count+=$ol_item["new_user_count"];
        }

        $old_order_info = $this->t_order_info->get_1v1_order_seller_list($end_time-90*86400,$end_time ,-1,"" , $origin_ex ,$origin_level, $tmk_student_status );

        $old_order_money = 0;
        foreach ($old_order_info["list"] as $order_item) {
            $old_order_money+=$order_item["all_price"];
        }
        $old_per_price = \App\Helper\Common::div_safe($old_order_money ,$old_new_user_count);
        $ret_info = $this->t_test_lesson_subject->get_seller_new_user_count( $start_time, $end_time, -1, $origin_ex  ,$origin_level,$tmk_student_status );

        $test_info=$this->t_test_lesson_subject->get_seller_test_lesson_count( $start_time, $end_time, -1, $origin_ex  ,$origin_level,$tmk_student_status );
        $test_tmp = $test_info['list'];
        foreach ($ret_info['list'] as $k=> &$v) {
            foreach ($test_tmp as $val) {
                if ($val['adminid'] === $v['admin_revisiterid']) {
                    $v['test_lesson_count'] = $val['test_count'];
                }
            }
        }

        $order_info=$this->t_order_info->get_1v1_order_seller_list($start_time,$end_time ,-1,"" , $origin_ex ,$origin_level,$tmk_student_status );
        $obj_list=&$ret_info["list"] ;
        foreach ($order_info["list"] as $order_item) {
            $k=$order_item["adminid"];
            \App\Helper\Utils::array_item_init_if_nofind($obj_list,$k, ["admin_revisiterid"=>$k] );
            $obj_list[$k]["order_count"]=$order_item["all_count"];
            $obj_list[$k]["order_money"]= intval($order_item["all_price"]);
        }

        $admin_map= $this->t_manager_info->get_create_time_list();
        foreach( $obj_list  as &$item) {
            $adminid=$item["admin_revisiterid"];
            $item["adminid"] = $adminid ;
            $item["per_price"] = intval( \App\Helper\Common::div_safe( @$item["order_money"], @$item ["new_user_count"]  ));
            $item["old_money"] = intval(@$item["new_user_count"] * $old_per_price)  ;
            $item["create_time"]= \App\Helper\Utils::unixtime2date(@$admin_map[$adminid]["create_time"], 'Y-m-d');
        }

        $ret_info=\App\Helper\Common::gen_admin_member_data($obj_list,[ "create_time", "per_price" ],0, strtotime( date("Y-m-01",$start_time )   ));
        foreach( $ret_info as &$item ) {
            E\Emain_type::set_item_value_str($item);
        }

        return $this->pageView(__METHOD__,\App\Helper\Utils::list_to_page_info($ret_info),[
            "old_per_price" => $old_per_price ,
        ]);
    }
    public function kaoqin_admin_list() {
        list($start_time,$end_time)= $this->get_in_date_range_month( -20 );
        $ret_info=$this->t_admin_card_log->get_list( null, $start_time,$end_time,-1,0);

    }

    public function tongji_lesson_teacher_identity(){
        list($start_time,$end_time) = $this->get_in_date_range(0,0,0,[],3);
        $lesson_type= $this->get_in_int_val("lesson_type",-1);
        $ret_info = $this->t_teacher_info->get_lesson_teacher_identity_info($start_time,$end_time,$lesson_type);
        foreach($ret_info["list"] as &$item){
            E\Eidentity::set_item_value_str($item);
        }
        return $this->pageView(__METHOD__,$ret_info);
    }

    public function tongji_teacher_stu_three_month(){
        $list = $this->t_lesson_info_b3->get_teacher_stu_three_month_info();
        return $this->pageView(__METHOD__,$list);
        // dd($list);
    }

    public function tongji_test(){
        $arr = [];
        return $this->pageView(__METHOD__, null, ['arr' => $arr]);
    }

    public function tongji_cr(){
        $this->switch_tongji_database();
        list($start_time,$end_time) = $this->get_in_date_range( 0 ,0,0,[],2 );
        $is_history_data = $this->get_in_int_val('history',1);
        $opt_date_type = $this->get_in_int_val("opt_date_type",2);
        if($is_history_data === 1){//历史记录
            if($opt_date_type == 3){ //月报
                $type = 1;
                $create_time = $end_time;
            }elseif($opt_date_type == 2){ //周报
                //                $start_month =
                $start_month = date("Y-m",$start_time);
                $end_month   = date("Y-m",$end_time);
                if($start_month == $end_month){ //周报
                    $type = 2;
                    $create_time = $end_time + 86400;
                }else{//跨月报
                    $type = 3;
                    $create_time = $end_time + 86400;
                }
            }else{
                $arr = [];
                $arr['create_time_range'] = "不在统计时间段内";
                $arr['type'] = 0;
                return $this->pageView(__METHOD__,null,["arr"=>$arr]);
            }

            $ret_info = $this->t_cr_week_month_info->get_data_by_type($create_time,$type);
            if($ret_info){
                //处理
                $ret_info['target']        = $ret_info['target']>0?$ret_info['target']/100:0;
                $ret_info['total_price']   = $ret_info['total_price']>0?$ret_info['total_price']/100:0;
                $ret_info['kpi_per']       = $ret_info['kpi_per'] >0?$ret_info['kpi_per']/100:0;
                $ret_info['gap_money']     = $ret_info['gap_money']>0?$ret_info['gap_money']/100:0;
                $ret_info['total_income']  = $ret_info['total_income']>0?$ret_info['total_income']/100:0;
                $ret_info['total_price_thirty']  = $ret_info['total_price_thirty']>0?$ret_info['total_price_thirty']/100:0;
                $ret_info['person_num_thirty_per'] = $ret_info['person_num_thirty_per']>0?$ret_info['person_num_thirty_per']/100:0;
                $ret_info['contract_per']  = $ret_info['contract_per']>0?$ret_info['contract_per']/100:0;
                $ret_info['month_kpi_per'] = $ret_info['month_kpi_per']>0?$ret_info['month_kpi_per']/100:0;
                $ret_info['lesson_consume']= $ret_info['lesson_consume']>0?$ret_info['lesson_consume']/100:0;
                $ret_info['teacher_leave'] = $ret_info['teacher_leave'] >0?$ret_info['teacher_leave']/100:0;
                $ret_info['student_leave'] = $ret_info['student_leave'] >0?$ret_info['student_leave']/100:0;
                $ret_info['other_leave']   = $ret_info['other_leave']>0?$ret_info['other_leave']/100:0;
                $ret_info['student_arrive_per'] = $ret_info['student_arrive_per']>0?$ret_info['student_arrive_per']/100:0;
                $ret_info['lesson_income'] = $ret_info['lesson_income']>0?$ret_info['lesson_income']/100:0;
                $ret_info['total_renew']   = $ret_info['total_renew']>0?$ret_info['total_renew']/100:0;
                $ret_info['renew_num_per'] = $ret_info['renew_num_per']>0?$ret_info['renew_num_per']/100:0;
                $ret_info['renew_per']     = $ret_info['renew_per']>0?$ret_info['renew_per']/100:0;
                $ret_info['finish_renew_per'] = $ret_info['finish_renew_per']>0?$ret_info['finish_renew_per']/100:0;
                $ret_info['tranfer_total_price'] = $ret_info['tranfer_total_price']>0?$ret_info['tranfer_total_price']/100:0;
                $ret_info['tranfer_success_per'] = $ret_info['tranfer_success_per']>0?$ret_info['tranfer_success_per']/100:0;
                $ret_info['total_tranfer'] = $ret_info['total_tranfer']>0?$ret_info['total_tranfer']/100:0;
                $ret_info['tranfer_num_per']=$ret_info['tranfer_num_per']>0?$ret_info['tranfer_num_per']/100:0;
                $ret_info['kk_success_per'] = $ret_info['kk_success_per']>0?$ret_info['kk_success_per']/100:0;

                //新增项
                $ret_info['average_person_effect']  = $ret_info['average_person_effect']>0?$ret_info['average_person_effect']/100:0;
                $ret_info['cumulative_refund_rate']  = $ret_info['cumulative_refund_rate']/100;
                $ret_info['student_end_per']  = $ret_info['student_end_per']/100;
                if($opt_date_type==3){
                    $ret_info['lesson_complete_per']  = $ret_info['lesson_consume_target']>0?round($ret_info['lesson_consume']/$ret_info['lesson_consume_target']*10000,2):0 ;


                    $ret_info['lesson_consume_target']  = $ret_info['lesson_consume_target']>0?$ret_info['lesson_consume_target']/100:"暂无数据";
                    $ret_info['lesson_target']  = $ret_info['lesson_target']>0?$ret_info['lesson_target']/100:"暂无数据";
                }elseif($opt_date_type==2){
                    $month_start = strtotime(date("Y-m-01",$end_time));
                    $month_time = strtotime("+1 months",$month_start);

                    $month_info = $this->t_cr_week_month_info->get_data_by_type($month_time,1);
                    $ret_info['lesson_complete_per']  = $month_info['lesson_consume_target']>0?round($month_info['lesson_consume']/$month_info['lesson_consume_target']*100,2):0 ;


                    $ret_info['lesson_consume_target']  = $month_info['lesson_consume_target']>0?$month_info['lesson_consume_target']/100:"暂无数据";
                    $ret_info['lesson_target']  = $month_info['lesson_target']>0?$month_info['lesson_target']/100:"暂无数据";




                }




            }
            return $this->pageView(__METHOD__,null,["arr"=>$ret_info]);
        }elseif($is_history_data === 2){
            $cur_start   = strtotime(date('Y-m-01',$start_time));
            $last_month  = strtotime(date('Y-m-01',$cur_start-100));
            $start_month = date("Y-m",$start_time);
            $end_month   = date("Y-m",$end_time);
            if($opt_date_type == 2){ //周报
                if($start_month == $end_month){ //周报
                    $type = 2;
                    $start_time = $start_time + 86400;
                    $end_time = $end_time + 86400;
                    $create_time = $end_time ;
                }else{//跨月报
                    $type = 3;
                    $start_time = $start_time + 86400;
                    $end_time = $end_time + 86400;
                    $create_time = $end_time ;
                }
            }elseif($opt_date_type == 3){
                $type = 1;
                $create_time = $end_time ;
            }else{
                $arr = [];
                $arr['create_time_range'] = "不在统计时间段内";
                $arr['type'] = 0;
                return $this->pageView(__METHOD__,null,["arr"=>$arr]);
            }
            //存档data
            $arr = [];
            $ret_info = $this->t_cr_week_month_info->get_data_by_type($create_time,$type);
            $arr['type']               = isset($ret_info['type'])?$ret_info['type']:0;
            $arr['finish_num']         = $ret_info['finish_num'];//结课学员数
            $arr['lesson_target']      = $ret_info['lesson_target'];//课时系数目标量
            $arr['read_num']           = $ret_info['read_num']; //在读学生数量
            $arr['total_student']      = $ret_info['total_student'];//上课学生数量
            $arr['student_arrive_per'] = $ret_info['student_arrive_per']/100;//学生到课率
            $arr['student_arrive']     = $ret_info['student_arrive'];//学生到课数量
            $arr['lesson_plan']        = $ret_info['lesson_plan'];//排课数量
            $arr['lesson_income']      = $ret_info['lesson_income']/100;//课时收入
            $arr['expect_finish_num']  = $ret_info['expect_finish_num'];//预计结课学生数量
            $arr['plan_renew_num']     = $ret_info['plan_renew_num'];//计划内续费学生数量
            $arr['other_renew_num']    = $ret_info['other_renew_num'];//计划外续费学生数量
            $arr['real_renew_num']     = $ret_info['real_renew_num'];//实际续费学生数量
            $arr['renew_per']          = $ret_info['renew_per'];//月续费率
            $arr['finish_renew_per']   = $ret_info['finish_renew_per'];//月预警续费率
            $arr['tranfer_success_per']= $ret_info['tranfer_success_per'];//月转介绍至CC签单率
            //$arr['kk_success_per']     = $ret_info['kk_success_per'];//月扩课成功率
            $arr['kk_success_per']     = $ret_info['kk_success_per']>0?$ret_info['kk_success_per']/100:0;
            $arr['create_time_range']  = date("Y-m-d H:i:s",$start_time)."--".date("Y-m-d H:i:s",$end_time);
            //漏斗
            $ret_info1 = $this->t_cr_week_month_info->get_data_by_type($create_time,4);//
            //$arr['plan_renew_num']     = $ret_info1['plan_renew_num'];//计划内续费学生数量
            //$arr['real_renew_num']     = $ret_info1['real_renew_num'];//实际续费学生数量
            $arr['renew_per']          = $ret_info1['renew_per']>0?$ret_info1['renew_per']/100:0;//月续费率
            $arr['finish_renew_per']   = $ret_info1['finish_renew_per']>0?$ret_info1['finish_renew_per']/100:0;//月预警续费率
            $arr['tranfer_success_per']= $ret_info1['tranfer_success_per']>0?$ret_info1['tranfer_success_per']/100:0;//月转介绍至CC签单率
            //$arr['kk_success_per']     = $ret_info1['kk_success_per']>0?$ret_info1['kk_success_per']/100:0;
            if($opt_date_type == 3){
                $arr['type'] = 1;
            }
            //节点
            //概况
            $ret_total   = $this->t_order_info->get_total_price($start_time,$end_time);
            if($type == 3){
                $month_ret_total   = $this->t_order_info->get_total_price(strtotime($end_month),$end_time);
                $month_total_money = $this->t_order_info->get_total_price_new(strtotime($end_month),$end_time);
                $ret_cr            = $this->t_manager_info->get_cr_num(strtotime($end_month),$end_time);
                $target = $this->t_manager_info->get_cr_target(strtotime($start_month));//月度目标
            }elseif($type == 1){
                $month_ret_total   = $this->t_order_info->get_total_price(strtotime($start_month),$end_time);
                $month_total_money = $this->t_order_info->get_total_price_new(strtotime($start_month),$end_time);
                $ret_cr            = $this->t_manager_info->get_cr_num(strtotime($start_month),$end_time);
                $target = $this->t_manager_info->get_cr_target($last_month);//月度目标
            }elseif($type == 2){
                $month_ret_total   = $this->t_order_info->get_total_price(strtotime($start_month),$end_time);
                $month_total_money = $this->t_order_info->get_total_price_new(strtotime($start_month),$end_time);
                $ret_cr            = $this->t_manager_info->get_cr_num_new(strtotime($start_month),$start_time,$end_time);
                $target = $this->t_manager_info->get_cr_target($last_month);//月度目标
            }
            $ret_total_thirty = $this->t_order_info->get_total_price_thirty($start_time,$end_time);

            $ret_refund = $this->t_order_refund->get_assistant_num($start_time,$end_time);  //退费总人数

            //$arr['total_price']        = $ret_total['total_price'] / 100; //现金总收入
            $arr['total_price']        = $month_total_money/100;                    //2-现金总收入
            $arr['total_income']       = $ret_total['total_price']/100 ;             //A1-现金总收入

            $arr['person_num']         = $ret_total['person_num']; //下单总人数
            $arr['contract_num']       = $ret_total['order_num']; //合同数
            $arr['total_price_thirty'] = round($ret_total_thirty['total_price'] / 100,2); //入职完整月人员签单额
            $arr['person_num_thirty']  = $ret_total_thirty['person_num'];  //入职完整月人员人数

            $arr['cr_num']             = $ret_cr;//在职人数
            $arr['refund_num']         = $ret_refund;//退费总人数
            $arr['target']             = $target;   //续费目标
            if(($arr['target']-$arr['total_price']) > 0){
                $arr['gap_money'] = $arr['target'] - $arr['total_price'];
            }else{
                $arr['gap_money'] = 0;  //缺口金额
            }
            if($arr['total_price']){
                $arr['contract_per']   = round($arr['total_income']/$arr['contract_num'],2);
            }else{
                $arr['contract_per']   = 0;
            }
            if($arr['person_num_thirty']){
                $arr['person_num_thirty_per'] = round($arr['total_price_thirty'] / $arr['person_num_thirty'],2);
            }else{
                $arr['person_num_thirty_per'] = 0;
            }
            if($arr['target']){
                $arr['kpi_per'] = round(100*$arr['total_price']/$arr['target'],2);
                $arr['month_kpi_per'] = round($month_ret_total['total_price']/$arr['target'],2);
            }else{
                $arr['kpi_per'] = 0;
                $arr['month_kpi_per'] = 0;
            }
            //课时消耗
            $lesson_consume    = $this->t_lesson_info->get_total_consume($start_time,$end_time); //课时消耗实际数量,上课学生数
            $leave_num         = $this->t_lesson_info->get_leave_num($start_time,$end_time); //老师,学生请假课时
            $arr['lesson_consume'] = round($lesson_consume['total_consume']/100,2);
            $arr['teacher_leave'] = 0;
            $arr['student_leave'] = 0;
            $arr['other_leave'] = 0;
            $arr['total_student'] = $lesson_consume["total_student"];
            foreach($leave_num as $key => $value){
                if($value['lesson_cancel_reason_type'] == 11){ //学生请假11
                    $arr['student_leave'] = round($value['num']/100,2);
                }
                if($value['lesson_cancel_reason_type'] == 12){ //老师请假
                    $arr['teacher_leave'] = round($value['num']/100,2);
                }
                if($value['lesson_cancel_reason_type'] == 3 || $value['lesson_cancel_reason_type'] == 4){ //网络设备
                    $arr['other_leave'] += round($value['num']/100,2);
                }
            }
            //续费
            $arr['total_renew'] = round($ret_total['total_renew']/100,2); //续费金额
            $arr['renew_num']   = $ret_total['renew_num'];       //总笔数
            if($arr['renew_num']){
                $arr['renew_num_per'] = round($arr['total_renew']/$arr['renew_num'],2); //平均单笔
            }else{
                $arr['renew_num_per'] = 0;
            }

            //转介绍
            $tranfer = $this->t_seller_student_new->get_tranfer_phone_num_new($start_time,$end_time);
            $tranfer_data = $this->t_order_info->get_cr_to_cc_order_num($start_time,$end_time);
            $arr['tranfer_num']   = $ret_total['tranfer_num']/1;  //转介绍成单数量
            $arr['total_tranfer'] = $ret_total['total_tranfer']/100; //转介绍总金额
            $arr['tranfer_phone_num'] = $tranfer; //转介绍至CC例子量

            $arr['tranfer_total_price'] = round($tranfer_data['total_price'] /100,2);
            $arr['tranfer_total_num']   = $tranfer_data['total_num'];
            if($arr['tranfer_num'] > 0){
                $arr['tranfer_num_per'] = round($arr['total_tranfer']/$arr['tranfer_num'],2);
            }else{
                $arr['tranfer_num_per'] = 0;
            }

            //扩科
            $kk          = $this->t_test_lesson_subject_sub_list->tongji_kk_data($start_time,$end_time) ;
            $success_num = $this->t_test_lesson_subject_sub_list->tongji_success_order($start_time,$end_time);
            $arr['total_test_lesson_num'] = $kk['total_test_lesson_num'];
            $arr['success_num'] = $success_num;
            $arr['fail_num'] = $kk['fail_num'];
            $arr['wait_num'] = $kk['wait_num'];


            //新增数据
            $cr_order_info = $this->t_order_info->get_all_cr_order_info($start_time,$end_time);
            $arr["average_person_effect"] = !empty(@$cr_order_info["ass_num"])?round($cr_order_info["all_money"]/$cr_order_info["ass_num"])/100:0; //平均人效(非入职完整月)

            $all_pay = $this->t_student_info->get_student_list_for_finance_count();//所有有效合同数
            $refund_info = $this->t_order_refund->get_refund_userid_by_month(-1,$end_time);//所有退费信息
            $arr["cumulative_refund_rate"] = round(@$refund_info["orderid_count"]/$all_pay["orderid_count"]*100,2);//合同累计退费率

            // 获取停课,休学,假期数
            $ret_info_stu = $this->t_student_info->get_student_count_archive();

            foreach($ret_info_stu as $item) {
                if ($item['type'] == 2) {
                    @$arr['stop_student']++;
                } else if ($item['type'] == 3) {
                    @$arr['drop_student']++;
                } else if ($item['type'] == 4) {
                    @$arr['summer_winter_stop_student']++;
                }
            }

            //新签合同未排量(已分配/未分配)/新签学生数
            $user_order_list = $this->t_order_info->get_order_user_list_by_month($end_time);
            $new_user = [];//上月新签

            foreach ( $user_order_list as $item ) {
                if ($item['order_time'] >= $start_time ){
                    $new_user[] = $item['userid'];
                    if (!$item['start_time'] && $item['assistantid'] > 0) {//新签订单,未排课,已分配助教
                        @$arr['new_order_assign_num']++;
                    } else if (!$item['start_time'] && !$item['assistantid']) {//新签订单,未排课,未分配助教
                        @$arr['new_order_unassign_num']++;
                    }
                }

            }

            $new_user = array_unique($new_user);
            $arr['new_student_num'] = count($new_user);//新签学生数

            //结课率
            $arr["all_registered_student"] = $arr['finish_num']+$arr["read_num"]+$arr["stop_student"]+$arr["drop_student"]+$arr["summer_winter_stop_student"];
            $arr["student_end_per"] = round($arr["finish_num"]/$arr["all_registered_student"]*100,2);

            if($opt_date_type==3){
                $month_start = $start_time;
                $month_end = $end_time;
            }elseif($opt_date_type==2){
                $month_start = strtotime(date("Y-m-01",$end_time));
                $month_end = strtotime("+1 months",$month_start);
            }
            //各年级在读学生统计
            $grade_list = $this->t_student_info->get_read_num_by_grade();
            $arrr=[];
            foreach($grade_list as $k=>$val){
                $arrr[$k]=$val["num"];
            }
            $grade_str = json_encode($arrr);


            //课时消耗目标数量
            $last_year_start = strtotime("-1 years",$month_start);
            $last_year_end = strtotime("+1 months",$last_year_start);

            $month_start_grade_info = $this->t_cr_week_month_info->get_data_by_type($month_start,$type);
            $month_start_grade_str = @$month_start_grade_info["grade_stu_list"];
            $grade_arr = json_decode($month_start_grade_str,true); //月初各年级在读人数

            $lesson_consume    = $this->t_lesson_info->get_total_consume_by_grade( $last_year_start,$last_year_end);
            $lesson_consume_target = 0;
            foreach($lesson_consume as $kk=>$vv){
                if($vv["total_student"]>0){
                    $lesson_consume_target += @$grade_arr[$kk]*$vv["total_consume"]/$vv["total_student"];
                }
            }
            $new_student_num_last = $this->t_cr_week_month_info->get_new_student_num($month_start,$type);
            $read_num_last = $this->t_cr_week_month_info->get_read_num($month_start,$type);
            $lesson_consume_target += $new_student_num_last*600;
            $lesson_consume_target = round($lesson_consume_target/100,2);
            $lesson_target  = ($read_num_last+ $new_student_num_last)>0?round($lesson_consume_target/($read_num_last+ $new_student_num_last),2):0;
            $arr["lesson_consume_target"] = $lesson_consume_target;
            $arr["lesson_target"] = $lesson_target;


            return $this->pageView(__METHOD__,null,["arr"=>$arr]);
        }
    }

    public function get_new_train_through_teacher_info(){
       list($start_time,$end_time) = $this->get_in_date_range( 0 ,0,0,[],3 );
       //只取2017年入职的老师
       $through_time = strtotime("2017-01-01");
       $ret_info = $this->t_teacher_info->get_new_train_through_teacher_info($through_time);
       foreach($ret_info["list"] as &$item){
           \App\Helper\Utils::unixtime2date_for_item($item, "train_through_new_time","_str");
           E\Esubject::set_item_value_str($item);
       }
       return $this->pageView(__METHOD__,$ret_info);

    }

    /**
     * @author sam
     * @function ID：1000409
     */
    public function one_three_grade_student(){
        list($start_time,$end_time) = $this->get_in_date_range( 0,0,0,[],3);
        $page_info = $this->get_in_page_info();

        $ret_info = $this->t_cr_week_month_info->get_apply_info_new($page_info,$start_time,$end_time);
        $ret = $this->t_cr_week_month_info->get_total_apply_info($start_time,$end_time);
        foreach ($ret_info['list'] as $key => &$value) {
            $value['grade_str'] = E\Egrade::get_desc($value['grade']);
            $value['subject_str'] = E\Esubject::get_desc($value['subject']);
            if($value['phone_location'] == "鹏博士" || $value['phone_location'] == '' || $value['phone_location'] == '免商店充值卡' || $value['phone_location'] == '中麦通信' ||$value['phone_location'] == '重庆U友' || $value['phone_location'] == '江苏U友' || $value['phone_location'] == '江苏U友' || $value['phone_location'] == '江苏U友' || $value['phone_location'] == '小米移动' || $value['phone_location'] == '北京U友' || $value['phone_location'] == "全国其它 " || $value['phone_location'] == '话机通信' || $value['phone_location'] == '阿里通信' || $value['phone_location'] == '辽宁U友'){
                $value['phone_location'] = "其它";
            }else{
                $pro = substr($value['phone_location'],0,strlen($value['phone_location'])-6);
                $value['phone_location'] = $pro;
            }
            if($value['lesson_user_online_status'] == 0 ){
                $value['lesson_user_online_status_str'] = "无效";
            }elseif($value['lesson_user_online_status'] == 1){
                $value['lesson_user_online_status_str'] = "有效";
            }else{
                $value['lesson_user_online_status_str'] = "无效";
            }
            if($value['price'] > 0 and $value['contract_status'] != 0){
                $value['status_str'] = "有效";
            }else{
                $value['status_str'] = "无效";
            }
        }
        foreach ($ret as $key => &$value) {
            $value['grade_str'] = E\Egrade::get_desc($value['grade']);
        }
        return $this->pageView(__METHOD__, $ret_info,[
            // "table_data_list" => $ret_info['list'],
            "ret" => $ret,
        ]);
    }


    //教学事业部核心数据
    public function get_teaching_core_data(){
        $list = $this->t_teaching_core_data->get_all_info(1);
        foreach($list["list"] as &$val){
            $val["month"] = date("Y年m月",$val["time"]);
        }
        return $this->pageView(__METHOD__, $list);
    }

    /**
     * @author sam
     * @function ID：1000424
     */
    public function home(){
        list($start_time,$end_time) = $this->get_in_date_range( 0,0,0,[],3);
        $phone_location_list = $this->t_cr_week_month_info->get_test_lesson_subject($start_time,$end_time);
        $new_list = [];
        foreach (E\Esubject::$desc_map as $key => $value) {
             $new_list['其它'][$key] = '';
        }
        foreach($phone_location_list as $key => $value){
            if($value['phone_location'] == "鹏博士" || $value['phone_location'] == '' || $value['phone_location'] == '免商店充值卡' || $value['phone_location'] == '中麦通信' ||$value['phone_location'] == '重庆U友' || $value['phone_location'] == '江苏U友' || $value['phone_location'] == '江苏U友' || $value['phone_location'] == '江苏U友' || $value['phone_location'] == '小米移动' || $value['phone_location'] == '北京U友' || $value['phone_location'] == "全国其它 " || $value['phone_location'] == '话机通信' || $value['phone_location'] == '阿里通信' || $value['phone_location'] == '辽宁U友'){

                $new_list['其它'][$value['subject']] += $value['total'];
                //$province_lesson_student['总计'] += $value['total'];
            }else{
                $pro = substr($value['phone_location'],0,strlen($value['phone_location'])-6);

                if(!isset($new_list[$pro])){
                    foreach (E\Esubject::$desc_map as $kaey => $vaalue) {
                        if(!isset($new_list[$pro][$kaey])){
                            $new_list[$pro][$kaey] = '';
                        }
                    }
                    $new_list[$pro][$value['subject']] = 0;
                    $new_list[$pro][$value['subject']] += $value['total'];
                }else{
                    $new_list[$pro][$value['subject']] += $value['total'];
                }
            }
        }

        $subject_list = $this->t_cr_week_month_info->get_test_lesson($start_time,$end_time);
        $list = [];
        foreach (E\Esubject::$desc_map as $key => $value) {
            foreach (E\Egrade::$desc_map as $kkey => $kvalue) {
                $grade_str = E\Egrade::get_desc($kkey);
                $list[$grade_str][$key] = '';
            }
        }
        foreach ($subject_list as $key => $value) {
            $grade_str_1 = E\Egrade::get_desc($value['grade']);
            //dd($grade_str_1);
            if(isset($list[$grade_str_1][$value['subject']])){
                $list[$grade_str_1][$value['subject']] = $value['total'];
            }else{
                //var_dump($value);
            }
        }
        return $this->pageView(__METHOD__, null,[
            // "table_data_list" => $ret_info['list'],
            "list" => $list,
            "new_list" => $new_list,
        ]);
    }

    public function subject_transfer(){
        //list($start_time,$end_time)=$this->get_in_date_range( date("Y-m-d",time(NULL)-90*86500),date("Y-m-d",time(NULL)));
        /*
        $date_list = \App\Helper\Common::get_date_time_list($start_time, $end_time-1);
        $log_type  = E\Edate_id_log_type::V_VALID_USER_COUNT;
        $from_list = $this->t_id_opt_log->get_date_list($log_type,$start_time,$end_time);

        \App\Helper\Utils::date_list_set_value($date_list,$from_list,"opt_date","user_count","count");
        $from_list=$this->t_lesson_info->get_user_count_list($start_time,$end_time);
        dd($from_list);
        \App\Helper\Utils::date_list_set_value($date_list,$from_list,"opt_date","lesson_user_count","count");
        usort($date_list,function($a,$b){
            //var_dump($a['title'],$b['title']);echo "<br/>";
            return \App\Helper\Common::sort_value_desc_func($a["title"],$b["title"]);
        });
        //dd(2);
        return $this->pageView(__METHOD__,\App\Helper\Utils::list_to_page_info($date_list));

        $start_time = $this->get_in_start_time_from_str(date("Y-m-01",1488297600));
        $end_time   = $this->get_in_end_time_from_str_next_day(
            date("Y-m-d",(strtotime(date("Y-m-01",time(NULL)))-86400)));
            */
        $this->get_in_int_val( "chinese",1);
        $this->get_in_int_val( "math",1);
        $this->get_in_int_val( "english",1);
        //$start_time = $this->get_in_start_time_from_str(date("Y-m-01",time(NULL)-15*86400));
        $start_time = $this->get_in_start_time_from_str(date("Y-m-01",1488297600));
        $end_time   = $this->get_in_end_time_from_str(
            date("Y-m-d",time(NULL)));

        $first_time  = strtotime(date('Y-m-01',$start_time));
        $second_time = strtotime(date('Y-m-01',$end_time));
        $i = $first_time;
        $montharr = [];
        while($i  <= $second_time){
            $montharr[] = date('Y-m-01',$i);
            $i = strtotime('+1 month', $i);
        }
        $i = 0;
        $subject_chinese = [];
        $subject_math = [];
        $subject_english = [];
        $date_list = [];
        foreach ($montharr as $key => $value) {
            $time1 = strtotime($value);
            $month = date('Y-m',$time1);
            $time2 = strtotime('+1 month',$time1);
            $success_num = $this->t_lesson_info->get_subject_transfer($time1,$time2);
            $lesson_num  = $this->t_lesson_info->get_subject_success($time1,$time2);
            $subject_chinese[$i]['month'] = $month;
            $subject_chinese[$i]['count'] = isset($success_num[1]['have_order'])&& isset($lesson_num[1]['success_lesson'])?round(100*$success_num[1]['have_order'] /$lesson_num[1]['success_lesson'],2):0;
            $subject_math[$i]['month'] = $month;
            $subject_math[$i]['count'] = isset($success_num[2]['have_order'])&& isset($lesson_num[2]['success_lesson'])?round(100*$success_num[2]['have_order'] /$lesson_num[2]['success_lesson'],2):0;
            $subject_english[$i]['month'] = $month;
            $subject_english[$i]['count'] = isset($success_num[3]['have_order'])&& isset($lesson_num[3]['success_lesson'])?round(100*$success_num[3]['have_order'] /$lesson_num[3]['success_lesson'],2):0;
            $date_list[$month]['title'] = $month;
            ++$i;
        }
        \App\Helper\Utils::date_list_set_value($date_list,$subject_chinese,"month","subject_chinese","count");
        \App\Helper\Utils::date_list_set_value($date_list,$subject_math,"month","subject_math","count");
        \App\Helper\Utils::date_list_set_value($date_list,$subject_english,"month","subject_english","count");
        return $this->pageView(__METHOD__,\App\Helper\Utils::list_to_page_info($date_list));
    }

    public function fulltime_teacher_kpi_chart(){
        $start_time = $this->get_in_start_time_from_str(date("Y-m-01",1488297600));
        $end_time   = $this->get_in_end_time_from_str(
            date("Y-m-d",time(NULL)));

        $first_time  = strtotime(date('Y-m-01',$start_time));
        $second_time = strtotime(date('Y-m-01',$end_time));
        $i = $first_time;
        $montharr = [];
        while($i  <= $second_time){
            $montharr[] = date('Y-m-01',$i);
            $i = strtotime('+1 month', $i);
        }
        $i = 0;
        $date_list = [];
        foreach ($montharr as $key => $value) {
            $time1 = strtotime($value);
            $month = date('Y-m',$time1);
            $time2 = strtotime('+1 month',$time1);
            $ret_info = $this->t_fulltime_teacher_data->get_info_by_time($time1);
            $cc_transfer_all[$i]['month'] = $month;
            $cc_transfer_all[$i]['count'] = round($ret_info[0]['cc_transfer_per']/100,2);
            $cc_transfer_sh[$i]['month'] = $month;
            $cc_transfer_sh[$i]['count'] = round($ret_info[1]['cc_transfer_per']/100,2);
            $cc_transfer_wh[$i]['month'] = $month;
            $cc_transfer_wh[$i]['count'] = round($ret_info[2]['cc_transfer_per']/100,2);

            $student_num_all[$i]['month'] = $month;
            $student_num_all[$i]['count'] = $ret_info[0]['student_num'];
            $student_num_sh[$i]['month'] = $month;
            $student_num_sh[$i]['count'] = $ret_info[1]['student_num'];
            $student_num_wh[$i]['month'] = $month;
            $student_num_wh[$i]['count'] = $ret_info[2]['student_num'];

            $lesson_count_all[$i]['month'] = $month;
            $lesson_count_all[$i]['count'] = round($ret_info[0]['lesson_count']/100,2);
            $lesson_count_sh[$i]['month'] = $month;
            $lesson_count_sh[$i]['count'] = round($ret_info[1]['lesson_count']/100,2);
            $lesson_count_wh[$i]['month'] = $month;
            $lesson_count_wh[$i]['count'] = round($ret_info[2]['lesson_count']/100,2);

            $date_list[$month]['title'] = $month;
            ++$i;
        }

        \App\Helper\Utils::date_list_set_value($date_list,$cc_transfer_all,"month","cc_transfer_all","count");
        \App\Helper\Utils::date_list_set_value($date_list,$cc_transfer_sh,"month","cc_transfer_sh","count");
        \App\Helper\Utils::date_list_set_value($date_list,$cc_transfer_wh,"month","cc_transfer_wh","count");

        \App\Helper\Utils::date_list_set_value($date_list,$student_num_all,"month","student_num_all","count");
        \App\Helper\Utils::date_list_set_value($date_list,$student_num_sh,"month","student_num_sh","count");
        \App\Helper\Utils::date_list_set_value($date_list,$student_num_wh,"month","student_num_wh","count");

        \App\Helper\Utils::date_list_set_value($date_list,$lesson_count_all,"month","lesson_count_all","count");
        \App\Helper\Utils::date_list_set_value($date_list,$lesson_count_sh,"month","lesson_count_sh","count");
        \App\Helper\Utils::date_list_set_value($date_list,$lesson_count_wh,"month","lesson_count_wh","count");
        //dd($date_list);
        return $this->pageView(__METHOD__,\App\Helper\Utils::list_to_page_info($date_list));
    }

    public function total_money()
    {
        //list($start_time,$end_time)=$this->get_in_date_range( date("Y-m-d",time(NULL)-30*86500),date("Y-m-d"));
        /*
        list($start_time,$end_time) = $this->get_in_date_range( 0,0,0,[],1)
        $date_list = \App\Helper\Common::get_date_time_list($start_time, $end_time-1);
        $log_type  = E\Edate_id_log_type::V_VALID_USER_COUNT;
        $from_list = $this->t_id_opt_log->get_date_list($log_type,$start_time,$end_time);

        \App\Helper\Utils::date_list_set_value($date_list,$from_list,"opt_date","user_count","count");
        $from_list=$this->t_lesson_info->get_user_count_list($start_time,$end_time);
        \App\Helper\Utils::date_list_set_value($date_list,$from_list,"opt_date","lesson_user_count","count");
        usort($date_list,function($a,$b){
            return \App\Helper\Common::sort_value_desc_func($a["title"],$b["title"]);
        });
        */
        //dd(2);
        list($start_time,$end_time) = $this->get_in_date_range( 0,0,0,[],1);
        $date_new_list = [
            0 => [
                "title" => "00:00",
                "lesson_user_count" => 331162,
            ],
            1 => [
                "title" => "01:00",
                "lesson_user_count" => 80627,
            ],
            2 => [
                "title" => "02:00",
                "lesson_user_count" => 0,
            ],
            3 => [
                "title" => "03:00",
                "lesson_user_count" => 0,
            ],
            4 => [
                "title" => "04:00",
                "lesson_user_count" => 0,
            ],
            5 => [
                "title" => "05:00",
                "lesson_user_count" => 0,
            ],
            6 => [
                "title" => "06:00",
                "lesson_user_count" => 0,
            ],
            7 => [
                "title" => "07:00",
                "lesson_user_count" => 0,
            ],
            8 => [
                "title" => "08:00",
                "lesson_user_count" => 0,
            ],
            9 => [
                "title" => "09:00",
                "lesson_user_count" => 129987,
            ],
            10 => [
                "title" => "10:00",
                "lesson_user_count" => 446330,
            ],
            11 => [
                "title" => "11:00",
                "lesson_user_count" => 670013,
            ],
            12 => [
                "title" => "12:00",
                "lesson_user_count" => 1188662,
            ],
            13 => [
                "title" => "13:00",
                "lesson_user_count" => 580762,
            ],
            14 => [
                "title" => "14:00",
                "lesson_user_count" => 622892,
            ],
            15 => [
                "title" => "15:00",
                "lesson_user_count" => 893221,
            ],
            16 => [
                "title" => "16:00",
                "lesson_user_count" => 1003267,
            ],
            17 => [
                "title" => "17:00",
                "lesson_user_count" => 1425980,
            ],
            18 => [
                "title" => "18:00",
                "lesson_user_count" => 1359781,
            ],
            19 => [
                "title" => "19:00",
                "lesson_user_count" => 1103296,
            ],
            20 => [
                "title" => "20:00",
                "lesson_user_count" => 1276031,
            ],
            21 => [
                "title" => "21:00",
                "lesson_user_count" => 923152,
            ],
            22 => [
                "title" => "22:00",
                "lesson_user_count" => 890137,
            ],
            23 => [
                "title" => "23:00",
                "lesson_user_count" => 533712,
            ],
            24 => [
                "title" => "24:00",
                "lesson_user_count" => 162121,
            ],
        ];
        return $this->pageView(__METHOD__,\App\Helper\Utils::list_to_page_info($date_new_list));
    }


    public function student_data_list(){
        list($start_time, $end_time) = $this->get_in_date_range(0,0,0,[],3 );
        $page_num               = $this->get_in_page_num();
        $grade  = $this->get_in_int_val('grade',-1);
        $subject = $this->get_in_int_val('subject',-1);
        $pad     = $this->get_in_int_val('pad',-1);
        //dd($subject);
        $ret_info = $this->t_student_call_data->get_all_data($page_num,$start_time,$end_time,$grade,$subject,$pad);
        foreach($ret_info['list'] as &$item){
            \App\Helper\Utils::unixtime2date_for_item($item,"add_time");
            \App\Helper\Utils::unixtime2date_for_item($item,"lesson_time");
            E\Egrade::set_item_value_str($item);
            E\Esubject::set_item_value_str($item);
            E\Epad_type::set_item_value_simple_str($item,"pad");
        }
        return $this->pageView(__METHOD__,$ret_info);
    }

    public function market_extension(){
        $type = $this->get_in_int_val('type',-1);
        $page_num = $this->get_in_page_num();
        list($start_time, $end_time) = $this->get_in_date_range(0,0,0,[],3 );
        $ret_info = $this->t_activity_usually->getActivityList($type,$start_time,$end_time,$page_num);

        foreach($ret_info['list'] as &$item){
            $item['add_time_str'] = \App\Helper\Utils::unixtime2date($item['add_time']);
            $item['gift_type_str'] = E\Emarket_gift_type::get_desc($item['gift_type']);
            if($item['activity_status'] == 0){
                $item['activity_status_str'] = "<font color='blue'>进行中</font>";
            }elseif($item['activity_status'] == 1){
                $item['activity_status_str'] = "<font color='green'>进行中</font>";
            }elseif($item['activity_status'] == 2){
                $item['activity_status_str'] = "<font color='red'>已失效</font>";
            }
        }

        return $this->pageView(__METHOD__,$ret_info);
    }

    # 市场部个性海报转发
    public function marketposterdata(){
        $page_num  = $this->get_in_page_num();
        $uid = $this->get_in_int_val("adminid",0);
        $ret_info = $this->t_personality_poster->getData($page_num,$uid);
        return $this->pageView(__METHOD__,$ret_info);
    }

    # 课次取消-技术支持
    public function lessoncancelrate(){
        list($start_time, $end_time) = $this->get_in_date_range(0,0,0,[],3 );
        $dayNum = ($end_time-$start_time)/86400;

        $lessonCancelNum = $this->t_lesson_info_b3->getLessonCancelRate($start_time,$end_time);
        $actualLessonNum = $this->t_lesson_info_b3->getTotalNum($start_time,$end_time);
        $dateArr = [];
        $rateArr = [];
        $tmp = [];
        for($i=0; $i<$dayNum; $i++){
            $timeStart = $start_time+$i*86400;
            $timeEnd   = $timeStart+86400;
            $dateArr[] = date('Y-m-d',$timeStart);
            $cancel_num = 0;
            $actual_num = 0;

            foreach($lessonCancelNum as $item_cancel){
                if($item_cancel['lesson_start']>=$timeStart && $item_cancel['lesson_start']<=$timeEnd){
                    $cancel_num+=1;
                }
            }

            foreach($actualLessonNum as $item_actual){
                if($item_actual['lesson_start']>=$timeStart && $item_actual['lesson_start']<=$timeEnd){
                    $actual_num+=1;
                }
            }
            if(($actual_num+$cancel_num)>0){
                $rateArr[] = ($cancel_num/($actual_num+$cancel_num))*100;
            }else{
                $rateArr[] = 0;
            }
        }

        $ret_info = [];

        return $this->pageView(__METHOD__,$ret_info,[
            "dateArr" => $dateArr,
            "rateArr" => $rateArr
        ]);
    }
    public function tongji_sys_assign_admin_info() {

    }

    public function tongji_sys_assign_call_info() {
        $page_info= $this->get_in_page_info();
        list( $order_in_db_flag, $order_by_str, $order_field_name,$order_type )
            = $this->get_in_order_by_str([],"logtime asc",[
                //"grade" => "s.grade",
            ]);

        list($start_time, $end_time ) = $this->get_in_date_range_day(0);


        $adminid=$this->get_in_adminid(-1);
        $userid=$this->get_in_userid(-1);
        $called_flag= $this->get_in_el_boolean(-1, "called_flag");
        $check_hold_flag= $this->get_in_el_boolean(-1, "check_hold_flag");
        $seller_student_assign_from_type=$this->get_in_el_seller_student_assign_from_type();

        $same_admin_flag = $this->get_in_el_boolean(-1, "same_admin_flag");
        $same_admin_flag =  $same_admin_flag[0];

        $ret_info=$this->t_seller_student_system_assign_log->get_list($page_info, $order_by_str ,$start_time, $end_time, $adminid,$userid, $called_flag ,$seller_student_assign_from_type ,$check_hold_flag ,$same_admin_flag);

        foreach ($ret_info["list"] as &$item) {
            \App\Helper\Utils::unixtime2date_for_item($item, "logtime");
            \App\Helper\Utils::unixtime2date_for_item($item, "add_time");
            $this->cache_set_item_account_nick($item);
            $this->cache_set_item_account_nick($item,"admin_revisiterid", "admin_revisiter_nick");
            $this->cache_set_item_student_nick($item);
            $item["call_time"]= \App\Helper\Common::get_time_format( $item["call_time"] );
            E\Eboolean::set_item_value_color_str($item, "called_flag");
            E\Eseller_student_assign_from_type::set_item_value_str($item);
            E\Eboolean::set_item_value_color_str($item, "check_hold_flag");
            E\Eorigin_level:: set_item_value_str($item );
        }

        return $this->pageView(__METHOD__, $ret_info);

    }
    //@desn:展示系统释放日志
    public function tongji_sys_free_log(){
        $page_info= $this->get_in_page_info();
        list($start_time, $end_time ) = $this->get_in_date_range_day(0);
        $adminid=$this->get_in_adminid(-1);
        $userid=$this->get_in_userid(-1);
        $ret_info=$this->t_seller_student_system_release_log->get_list($page_info,$start_time,$end_time,$adminid,$userid);
        foreach($ret_info['list'] as &$item){
            \App\Helper\Utils::unixtime2date_for_item($item, "release_time");
            \App\Helper\Utils::unixtime2date_for_item($item, "admin_assign_time");
            E\Erelease_reason_flag::set_item_value_str($item);
        }
        return $this->pageView(__METHOD__, $ret_info);
    }
    //对业绩值进行增改查
    public function cc_day_top_list(){
        $page_info= $this->get_in_page_info();
        list($start_time, $end_time ) = $this->get_in_date_range_day(0);
        $adminid=$this->get_in_adminid(-1);
        $ret_info = $this->t_cc_day_top->get_list($page_info,$start_time, $end_time,$adminid);
        foreach($ret_info['list'] as &$item){
            $item['score'] /= 100;
        }

        return $this->pageView(__METHOD__,$ret_info);
    }
    //@desn:添加销售排名
    public function cc_day_top_add(){
        if(!in_array($this->get_account(), ['abner','jim'])){
            return $this->output_err('无权限');
        }
        $score = $this->get_in_int_val('score');
        $rank = $this->get_in_int_val('rank');
        $uid = $this->get_in_int_val('uid');
        if($score>0 && $rank>0 && $uid >0){
            $this->t_cc_day_top->row_insert([
                'uid' => $uid,
                'score' => $score*100,
                'rank' => $rank,
                'add_time' => strtotime(date('Y-m-d'))
            ]);
            return $this->output_succ();
        }

        return $this->output_err('业绩、排名、销售不能为空!');
    }
    //@desn:修改销售排名信息
    public function cc_day_top_update(){
        if(!in_array($this->get_account(), ['abner','jim'])){
            return $this->output_err('无权限');
        }
        $id = $this->get_in_id();
        $score = $this->get_in_int_val('score');
        $rank = $this->get_in_int_val('rank');
        $uid = $this->get_in_int_val('uid');
        if($score>0 && $rank>0 && $uid >0){
            $this->t_cc_day_top->field_update_list($id,[
                'uid' => $uid,
                'score' => $score*100,
                'rank' => $rank,
            ]);
            return $this->output_succ();
        }

        return $this->output_err('业绩、排名、销售不能为空!');
    }
    //@desn:删除该条排名信息
    public function cc_day_top_del(){
        if(!in_array($this->get_account(), ['abner','jim'])){
            return $this->output_err('无权限');
        }
        $id = $this->get_in_id();
        if($id){
            $this->t_cc_day_top->row_delete($id);
            return $this->output_succ();
        }else
            return $this->output_err('为传入id');
    }
    //@desn:展示试听未回访记录表
    public function no_return_call_list(){
        $page_info = $this->get_in_page_info();
        $adminid = $this->get_in_int_val('adminid',-1);
        $ret_info = $this->t_cc_no_return_call->get_no_return_call_list($adminid,$page_info);
        foreach($ret_info['list'] as &$item){
            \App\Helper\Utils::unixtime2date_for_item($item, "add_time");
        }

        return $this->pageView(__METHOD__,$ret_info);
    }


}
