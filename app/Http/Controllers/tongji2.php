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
        //$ret_info= $this->t_manager_info->get_admin_member_list(  E\Emain_type::V_2,$adminid );
        list($start_time,$end_time )= $this->get_in_date_range_month(0);
        $month= strtotime( date("Y-m-01", $start_time));
        $ret_info= $this->t_manager_info->get_admin_member_list_new($month ,E\Emain_type::V_2,$adminid );

        $admin_list=&$ret_info["list"];
        $account_role= E\Eaccount_role::V_2;
        $order_user_list=$this->t_order_info->get_admin_list ($start_time,$end_time,$account_role);
        $map=[];
        foreach($ret_info["list"] as &$item ) {
            $map[$item["adminid"] ]=true;
            //$sys_operator = $item["account"];
            //$sort_money = $this->t_order_info->get_sort_order_count_money($sys_operator,$start_time,$end_time);
            //$item["stage_money"] = @$sort_moeny["stage_money"];
            //$item["no_stage_money"] = @$sort_moeny["no_stage_money"];
        }
		//unset($item);
        foreach($order_user_list as $item ) {
            // if(!@$map[$item["adminid"] ] ) {
            if(@$map[$item["adminid"]] == true && isset($map[$item["adminid"]])) {
                dd($ret_info["list"],$item["adminid"]);
                // if ($adminid = -1  && $adminid==  $item["adminid"]   ) {
                if ($adminid==$item["adminid"]) {
                    $ret_info["list"][]=["adminid" => $item["adminid"] ];
                }
            }
        }
        dd($ret_info["list"]);
        $admin_list=\App\Helper\Common::gen_admin_member_data($admin_list, [],0, strtotime( date("Y-m-01",$start_time )));

        foreach( $admin_list as &$item ) {
            E\Emain_type::set_item_value_str($item);
        }

        return $this->pageView(__METHOD__,$ret_info);
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

        // dd($ret_info);

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
            $item['price_num']  = $item['price_num']/100;
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
            // $val["renw_price"] = isset($assistant_renew_list[$k])?$assistant_renew_list[$k]["renw_price"]/100:0;
            $val["renw_price"] = isset($ass_month[$k])?$ass_month[$k]["renw_price"]/100:0;
            $val["renw_per"] = !empty( $val["renw_target"])?round($val["renw_price"]/$val["renw_target"]*100,2):0;
            // $val["tran_price"] = isset($assistant_renew_list[$k])?$assistant_renew_list[$k]["tran_price"]/100:0;
            $val["tran_price"] = isset($ass_month[$k])?$ass_month[$k]["tran_price"]/100:0;
            // $val["all_price"] = isset($assistant_renew_list[$k])?$assistant_renew_list[$k]["all_price"]/100:0;
            $val["all_price"] = $val["renw_price"]+$val["tran_price"];
            // $val["tran_num"] = isset($assistant_renew_list[$k])?$assistant_renew_list[$k]["tran_num"]:0;
            $val["tran_num"] = isset($ass_month[$k])?$ass_month[$k]["tran_num"]/100:0;
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
            $val["tran_num"] = $val["hand_tran_num"]+$val["tran_num"];//目前以两者相加为准(9月之后准确)
            

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

            $ass_master_adminid = $this->t_admin_group_user->get_master_adminid_group_info($k);
            $val["group_name"] = $ass_master_adminid["group_name"];//组别
            if($account_id==-1){

            }else{
                if($ass_master_adminid["master_adminid"] != $account_id){
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
            $arr['plan_renew_num']     = $ret_info1['plan_renew_num'];//计划内续费学生数量
            $arr['real_renew_num']     = $ret_info1['real_renew_num'];//实际续费学生数量
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
            }elseif($type == 1){
                $month_ret_total   = $this->t_order_info->get_total_price(strtotime($start_month),$end_time);
                $month_total_money = $this->t_order_info->get_total_price_new(strtotime($start_month),$end_time);
                $ret_cr            = $this->t_manager_info->get_cr_num(strtotime($start_month),$end_time);
            }elseif($type == 2){
                $month_ret_total   = $this->t_order_info->get_total_price(strtotime($start_month),$end_time);
                $month_total_money = $this->t_order_info->get_total_price_new(strtotime($start_month),$end_time);
                $ret_cr            = $this->t_manager_info->get_cr_num_new(strtotime($start_month),$start_time,$end_time);
            }
            $ret_total_thirty = $this->t_order_info->get_total_price_thirty($start_time,$end_time);
            
            $ret_refund = $this->t_order_refund->get_assistant_num($start_time,$end_time);  //退费总人数
            $target = $this->t_manager_info->get_cr_target($last_month);//月度目标
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
            $tranfer = $this->t_seller_student_new->get_tranfer_phone_num($start_time,$end_time);
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
}
