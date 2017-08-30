<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;


use App\Helper\Utils;

use Illuminate\Support\Facades\Input ;
class tongji extends Controller
{
    use  CacheNick;
    var $switch_tongji_database_flag = true;

    public function contract()
    {
        $opt_date_type= $this->get_in_int_val("opt_date_type",0);
        $start_time = $this->get_in_start_time_from_str(date("Y-m-d",time(NULL)-86400*14));
        $end_time   = $this->get_in_end_time_from_str(date("Y-m-d",time(NULL)));
        $end_time += 86400;
        $date_list=\App\Helper\Common::get_date_time_list($start_time, $end_time-1);

        $stu_from_type = $this->get_in_int_val("stu_from_type",-1);
        $contract_type = $this->get_in_enum_val(E\Econtract_type::class, 0);

        $list      = $this->t_order_info->get_1v1_order_list($start_time,$end_time,"", $stu_from_type, [],[] ,$contract_type );
        $all_item=[
            "title"=>"全部",
            "order_count"=> 0,
            "money"=> "",
        ];

        foreach ($list as $item) {
            $opt_date=date("Y-m-d",$item["order_time"]);
            $date_item= &$date_list[$opt_date];
            $date_item["order_count"]=@$date_item["order_count"]+1;
            $date_item["money"]=@$date_item["money"]+ $item["price"]/100;
            $all_item["money"]+= $item["price"]/100;
            $all_item["order_count"]+=1;

        }

        $all_list=$date_list;

        array_unshift($all_list, $all_item);
        $page_info=\App\Helper\Utils::list_to_page_info($all_list);
        return $this->pageView(__METHOD__, $page_info,["data_ex_list"=>$date_list]);
    }

    public function user_count()
    {
        $sum_field_list=[
            "add_time_count",
            "call_count",
            "call_old_count",
            "first_revisite_time_count",
            "after_24_first_revisite_time_count",
            "require_test_lesson_count",
            "test_lesson_count",
            "test_lesson_count_succ",
            "seller_test_lesson_count",
            "seller_test_lesson_count_succ",
            "order_count",
            "order_count_new",
            "order_count_from_stu",
            "order_count_next",
            "test_lesson_count_fail_need_money",
            "seller_test_lesson_count_fail_need_money",
            "seller_test_lesson_count_fail_not_need_money",
            "test_lesson_count_change_time",
            "seller_test_lesson_count_change_time",
        ];
        $order_field_arr=  array_merge(["title" ] ,$sum_field_list );

        list( $order_in_db_flag, $order_by_str, $order_field_name,$order_type )
            =$this->get_in_order_by_str($order_field_arr ,"title desc");

        $this->get_in_int_val( "check_add_time_count",1);
        $this->get_in_int_val( "check_call_old_count",1);
        $this->get_in_int_val( "check_first_revisite_time_count",1);
        $this->get_in_int_val( "check_test_lesson_count",1);
        $this->get_in_int_val( "check_order_count",1);

        $admin_revisiterid=$this->get_in_int_val("admin_revisiterid",-1);
        list($start_time,$end_time)=$this->get_in_date_range( date("Y-m-d",time(NULL)-14*86500),date("Y-m-d",time(NULL)));
        $date_list=\App\Helper\Common::get_date_time_list($start_time, $end_time-1);

        $list = $this->t_seller_student_info->get_tongji_add_time($start_time,$end_time,$admin_revisiterid);
        foreach ($list as $item) {
            $opt_date=date("Y-m-d",$item["opt_time"]);
            $date_item= &$date_list[$opt_date];
            $date_item["add_time_count"]=@$date_item["add_time_count"]+1;
        }

        $list = $this->t_seller_student_info->get_tongji_first_revisite_time($start_time,$end_time,$admin_revisiterid);
        foreach ($list as $item) {
            $opt_date=date("Y-m-d",$item["opt_time"]);
            $date_item= &$date_list[$opt_date];
            $date_item["first_revisite_time_count"]=@$date_item["first_revisite_time_count"]+1;

            $new_24_count=0;
            if ($item["add_time"] +86400 > $item["opt_time"] ) {
                $new_24_count=1;
            }
            $date_item["after_24_first_revisite_time_count"]=@$date_item["after_24_first_revisite_time_count"]+$new_24_count;
        }

        //申请
        $list = $this->t_lesson_info->test_lesson_require_tongji($start_time,$end_time,$admin_revisiterid);
        foreach ($list as $item) {
            $opt_date=date("Y-m-d",$item["opt_time"]);
            $date_item= &$date_list[$opt_date];
            $date_item["require_test_lesson_count"]=@$date_item["require_test_lesson_count"]+1;
        }

        //试听课
        $list = $this->t_lesson_info->test_lesson_get_tongji($start_time,$end_time,$admin_revisiterid);
        foreach ($list as $item) {
            $opt_date=date("Y-m-d",$item["opt_time"]);
            $date_item= &$date_list[$opt_date];
            $date_item["test_lesson_count"]=@$date_item["test_lesson_count"]+1;
            if ($item["confirm_flag"] ==3) {
                $date_item["test_lesson_count_fail_need_money"]=@$date_item["test_lesson_count_fail_need_money"]+1;
            }

            if ($item["cancel_flag"]==0 && $item["confirm_flag"] <2  ) {
                $date_item["test_lesson_count_succ"]=@$date_item["test_lesson_count_succ"]+1;
            }
            if ($item["cancel_flag"]==2  ) {
                $date_item["test_lesson_count_change_time"]=@$date_item["test_lesson_count_change_time"]+1;
            }

            if ($item["admin_revisiterid"]<>1) { //seller
                $date_item["seller_test_lesson_count"]=@$date_item["seller_test_lesson_count"]+1;
                if ($item["confirm_flag"] ==3) {
                    $date_item["seller_test_lesson_count_fail_need_money"]=@$date_item["seller_test_lesson_count_fail_need_money"]+1;
                }

                if ($item["cancel_flag"]==0 && $item["confirm_flag"] <2 ) {
                    $date_item["seller_test_lesson_count_succ"]=@$date_item["seller_test_lesson_count_succ"]+1;
                }

                if ($item["cancel_flag"]==2  ) {
                    $date_item["seller_test_lesson_count_change_time"]=@$date_item["seller_test_lesson_count_change_time"]+1;
                }
            }
        }

        //得到回访的总数
        $list = $this->t_tongji_date->get_list(1,$start_time,$end_time,$admin_revisiterid);
        foreach ($list as $item) {
            $opt_date=date("Y-m-d",$item["log_time"]);
            $date_item= &$date_list[$opt_date];
            $date_item["call_count"]= @$date_item["call_count"]+$item["count"];
        }

        $sys_operator="";
        if ($admin_revisiterid !=-1) {
            $sys_operator=$this->cache_get_account_nick($admin_revisiterid);
        }

        //合同数
        $list = $this->t_order_info->get_1v1_order_list($start_time,$end_time,$sys_operator,-1);
        foreach ($list as $item) {
            $opt_date=date("Y-m-d",$item["order_time"]);
            $date_item= &$date_list[$opt_date];
            $date_item["order_count"]=@$date_item["order_count"]+1;

            $stu_from_type=$item["stu_from_type"];
            if ( $stu_from_type==0 ) {
                $date_item["order_count_new"]=@$date_item["order_count_new"]+1;
            }else if ( $stu_from_type ==1 ) {
                $date_item["order_count_form_stu"]=@$date_item["order_count_from_stu"]+1;
            }else if ( $stu_from_type ==10 ||  $stu_from_type ==11  ) {
                $date_item["order_count_next"]=@$date_item["order_count_from_next"]+1;
            }
        }

        foreach ($date_list as &$d_item ) {
            $d_item["call_old_count"]= @$d_item["call_count"]-@$d_item["first_revisite_time_count"];
            if($d_item["call_old_count"] <0 ) {
                $d_item["call_old_count"] =0;
            }

            $d_item["seller_test_lesson_count_fail_not_need_money"]
                 = @$d_item["seller_test_lesson_count"]
                 - @$d_item["seller_test_lesson_count_succ"]
                 - @$d_item["seller_test_lesson_count_fail_need_money"]
                 - @$d_item["seller_test_lesson_count_change_time"]
                 ;
        }

        if (!$order_in_db_flag) {
            \App\Helper\Utils::order_list( $date_list, $order_field_name, $order_type );
        }
        $all_item=["title" => "全部"];
        \App\Helper\Utils::list_add_sum_item( $date_list, $all_item,$sum_field_list );

        return $this->pageView(__METHOD__,\App\Helper\Utils::list_to_page_info($date_list) ,["data_ex_list"=>$date_list]);
    }

    public function user_count_info()
    {
        $opt_time = $this->get_in_str_val('opt_time');
        return json_decode($opt_time);
    }

    public function seller_time() {
        //$admin_revisiterid=$this->get_in_int_val("admin_revisiterid",-1);
        $groupid = $this->get_in_int_val("groupid",-1);
        if($groupid == -1){
            $adminid_list = "";
        }else{
            $adminid_arr = $this->t_admin_group_user->get_userid_arr($groupid);
            $adminid_list = "";
            foreach ($adminid_arr as $val){
                $adminid_list .= $val.",";
            }
            $adminid_list = "(".rtrim($adminid_list,",").")";
        }
        $group_list= $this->t_admin_group_name->get_group_list(2);
        list($start_time,$end_time)=$this->get_in_date_range( date("Y-m-d",time(NULL)-14*86500),date("Y-m-d",time(NULL)));

        $ret_info=$this->t_seller_student_info-> tongji_first_revisite_time($start_time,$end_time,-1 ,$adminid_list);
        $count = count($ret_info['list']);
        $count_all = $avg_call_interval = 0;
        foreach($ret_info["list"] as &$item) {
            $this->cache_set_item_account_nick($item,"admin_revisiterid");
            $item["avg_call_interval"] =sprintf("%.2f", $item["avg_call_interval"]  );
            $count_all += $item['user_count'];
            $avg_call_interval += $item['avg_call_interval'];
        }
        if($count != 0){
            $count_ave =ceil($count_all/$count);
            $avg_call_interval_ave = sprintf("%.2f",$avg_call_interval/$count);
        }else{
            $count_ave= $avg_call_interval_ave=0;
        }
        return $this->pageView(__METHOD__, $ret_info,['group_list'=>$group_list,'count_ave'=>$count_ave,'avg_call_interval_ave'=>$avg_call_interval_ave]);
    }
    public function change_week_value($value){
        return date('Y-m-d',$value);
    }

    public function sms() {
        list($start_time,$end_time)=$this->get_in_date_range( -14,0);
        $is_succ                    = $this->get_in_int_val('is_succ',-1);
        $type                       = $this->get_in_int_val("type",-1);

        $date_list=\App\Helper\Common::get_date_time_list($start_time, $end_time-1);

        $ret_list= $this->t_sms_msg->tongji_get_list($start_time,$end_time,$is_succ,$type);
        foreach ($ret_list as $item)  {
            $opt_date=$item["log_date"];
            $date_item= &$date_list[$opt_date];
            $date_item["count"]=@$date_item["count"]+$item["count"];
        }
        return $this->pageView(__METHOD__, \App\Helper\Utils::list_to_page_info($date_list) );
    }
    public function sms_type() {
        list($start_time,$end_time)=$this->get_in_date_range( -14,0);


        $ret_list= $this->t_sms_msg->tongji_type_get_list ($start_time,$end_time);
        foreach ($ret_list as &$item)  {
            E\Esms_type::set_item_value_str($item, "type");
        }
        return $this->pageView(__METHOD__, \App\Helper\Utils::list_to_page_info( $ret_list ) );
    }

    public function  test_lesson_tongi() {
        list( $order_in_db_flag, $order_by_str, $order_field_name,$order_type )
            =$this->get_in_order_by_str( [ "succ_count" ,"after_4_bak_count"  ]);

        list($start_time,$end_time )= $this->get_in_date_range(
            -7, 0);


        $ret_info=$this->t_seller_student_info->test_lesson_tongi( $order_by_str,$start_time,$end_time);
        foreach($ret_info["list"] as &$item) {
            $item["succ_count"] = $item  ["all_count"]- $item["bad_count"] ;
            $item["after_4_bak_count"] = $item  ["bad_count"]- $item["before_4_bad_count"] ;

        }

        if (!$order_in_db_flag) {
            \App\Helper\Utils::order_list( $ret_info["list"], $order_field_name, $order_type );
        }


        return $this->pageView(__METHOD__, $ret_info );
    }

    public function first_revisite_time_list() {

        list($start_time,$end_time )= $this->get_in_date_range(
            0, 0,0,[],1);
        $ret_info=$this->t_seller_student_info->first_revisite_time_tongji($start_time,$start_time+86400 );

        return $this->pageView(__METHOD__, $ret_info );

    }

    public function test_lesson_detail_list()  {
        list($start_time,$end_time )= $this->get_in_date_range(
            0, 0,0,[],1);
        $page_num=$this->get_in_page_num();
        $lesson_flag=$this->get_in_int_val("lesson_flag",-1);

        $ret_info=$this->t_seller_student_info->test_lesson_list($page_num,$start_time,$end_time,$lesson_flag);

        foreach($ret_info["list"] as &$item) {
            $lesson_start=$item["real_lesson_start"];
            $lesson_end=$item["lesson_end"];
            if(!$lesson_end) {
                $lesson_end= $lesson_start+2400;
            }
            $item["lesson_time"]= \App\Helper\Utils::fmt_lesson_time($lesson_start,$lesson_end);
            $this->cache_set_item_student_nick($item);
            if ($item["teacherid"]) {
                $this->cache_set_item_teacher_nick($item );
            }else{
                $this->cache_set_item_teacher_nick($item, "cancel_teacherid" );
            }
            E\Egrade::set_item_value_str($item);
            E\Esubject::set_item_value_str($item);

            E\Ebook_status:: set_item_value_str($item,"status");
            E\Econfirm_flag:: set_item_value_str($item);
            $item["notify_lesson_day1"]=  $item["notify_lesson_day1"]?1:0;
            $item["notify_lesson_day2"]=  $item["notify_lesson_day2"]?1:0;
            E\Eboolean::set_item_value_str($item,"notify_lesson_day1");
            E\Eboolean::set_item_value_str($item,"notify_lesson_day2");

        }

        return $this->pageView(__METHOD__, $ret_info );

    }
    public function admin_card_admin_log_list ( ) {
        //admin_card_admin_log_list
        list($start_time,$end_time )=$this->get_in_date_range_day(0);
        $end_time=$start_time+86400;

        $admin_list=$this->t_admin_card_log->get_admin_list($start_time,$end_time);

        $map=[];
        foreach( $admin_list as  &$d_item ) {
            $d_item["work_time"]=  $d_item["end_logtime"] -  $d_item["start_logtime"] ;
            $d_item["work_time_str"] =\App\Helper\Common::get_time_format( $d_item["work_time"]  );
            \App\Helper\Utils::unixtime2date_for_item($d_item,"start_logtime", "","H:i:s");
            \App\Helper\Utils::unixtime2date_for_item($d_item,"end_logtime" ,"", "H:i:s");

            $d_item["error_flag"]= ($d_item["work_time"] < 9*3600);
            if ($d_item["error_flag"]) {
                $d_item["error_flag_str"] ="是";
            }

            $map[$d_item["adminid"]]=true ;
        }
        $def_admin_list = $this->t_manager_info->get_admin_member_list();
        foreach ($def_admin_list["list"]  as $a_item ) {
            if (!@$map[$a_item["adminid"]]) {
                $admin_list[]=["adminid" =>  $a_item["adminid"] ] ;
            }

        }

        $ret_list=\App\Helper\Common::gen_admin_member_data($admin_list,["error_flag_str", "error_flag","start_logtime", "end_logtime", "work_time_str", "work_time" ] );
        foreach( $ret_list as &$item ) {
            E\Emain_type::set_item_value_str($item);
        }

        return $this->pageView(__METHOD__,\App\Helper\Utils::list_to_page_info($ret_list) );

    }

    public function admin_card_date_log_list_self ( ) {
        $this->set_in_value("adminid", $this->get_account_id() );
        return  $this->admin_card_date_log_list();
    }

    public function admin_card_date_log_list ( ) {
        list($start_time,$end_time )=$this->get_in_date_range( date("Y-m-01", time(NULL) ) ,0,0, [],3  );
        $adminid= $this->get_in_int_val("adminid",0 );
        $date_list=\App\Helper\Common::get_date_time_list($start_time, $end_time-1);
        $ret_info=$this->t_admin_card_log->get_list( 1, $start_time,$end_time,$adminid,100000 );


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

        foreach( $date_list as  &$d_item ) {
            if (isset ( $d_item["start_logtime"]) ){
                $d_item["work_time"]=  $d_item["end_logtime"] -  $d_item["start_logtime"] ;
                $d_item["work_time_str"] =\App\Helper\Common::get_time_format( $d_item["work_time"]  );
                \App\Helper\Utils::unixtime2date_for_item($d_item,"start_logtime", "","H:i:s");
                \App\Helper\Utils::unixtime2date_for_item($d_item,"end_logtime" ,"", "H:i:s");

                $d_item["error_flag"]= ($d_item["work_time"] < 9*3600);
                if ($d_item["error_flag"]) {
                    $d_item["error_flag_str"] ="是";
                }
            }
        }

        return $this->pageView(__METHOD__, \App\Helper\Utils::list_to_page_info($date_list) );

    }
    public function admin_card_log_list ( ) {
        list($start_time,$end_time )=$this->get_in_date_range_day(0);
        $page_num=$this->get_in_page_num();
        $adminid= $this->get_in_int_val("adminid", -1);
        $ret_info=$this->t_admin_card_log->get_list( $page_num, $start_time,$end_time,$adminid );
        foreach( $ret_info["list"] as &$item) {
            \App\Helper\Utils::unixtime2date_for_item($item,"logtime");
        }

        return $this->pageView(__METHOD__,$ret_info);
    }
    public function upload_from_xls_card_log()
    {


        $file = Input::file('file');

        if ($file->isValid()) {
            //处理列
            $tmpName = $file ->getFileName();
            $realPath = $file ->getRealPath();
            $original_name=$file->getClientOriginalName();
            $original_name=preg_replace("/\\.xls$/", "", $original_name);

            $objReader = \PHPExcel_IOFactory::createReader('Excel5');

            $objPHPExcel = $objReader->load($realPath);
            $objPHPExcel->setActiveSheetIndex(0);
            $arr=$objPHPExcel->getActiveSheet()->toArray();
            $row_arr=[];
            foreach ($arr as $index => $item) {
                if ($index== 0) { //标题
                    //验证字段名
                    if (trim($item[0]) != "部门"
                        ||trim($item[1]) != "姓名"
                    ) {
                        return $this->output_err("文件字段不对");
                    }
                } else {
                    //导入数据
                    $cardid=$item["2"]*1;
                    $logtime=strtotime( $item["3"])*1;
                    $row_arr[]= "($logtime, $cardid)";
                }
            }
            $this->t_admin_card_log-> insert_arr(join(",",$row_arr ) );



            return $this->output_succ();
        } else {
            return $this->output_err("处理xls失败");
        }

    }

    public function test_lesson_ass_self() {
        $assistantid= $this->t_assistant_info->get_assistantid( $this->get_account() );
        $this->set_in_value("assistantid",  $assistantid );
        return $this->test_lesson_ass();
    }

    public function test_lesson_ass_jy() {
        return $this->test_lesson_ass();
    }

    public function test_lesson_ass(){
        $this->switch_tongji_database();
        $sum_field_list=[
            "stu_num",
            "valid_count",
            "family_change_count",
            "teacher_change_count",
            "fix_change_count",
            "internet_change_count",
            "student_leave_count",
            "teacher_leave_count",
        ];
        $order_field_arr=  array_merge(["ass_nick" ] ,$sum_field_list );

        list( $order_in_db_flag, $order_by_str, $order_field_name,$order_type )
            =$this->get_in_order_by_str($order_field_arr ,"ass_nick desc");
        $assistantid= $this->get_in_int_val("assistantid",-1);

        $seller_groupid_ex    = $this->get_in_str_val('seller_groupid_ex', "");
        $require_adminid_list = $this->t_admin_main_group_name->get_adminid_list_new($seller_groupid_ex);

        list($start_time,$end_time)=$this->get_in_date_range(0,0,0,[],2);
        $ret_info = $this->t_lesson_info->get_lesson_info_ass_tongji($start_time,$end_time, $assistantid ,$require_adminid_list  );

        foreach($ret_info as &$item_ret){
            $item_ret['lesson_rate'] = $item_ret['valid_count']/($item_ret['stu_num']*100);
            $item_ret['lesson_rate'] = number_format($item_ret['lesson_rate'],2);
            $item_ret['lesson_lose_rate'] = ($item_ret['fix_change_count']+$item_ret['internet_change_count']+$item_ret['student_leave_count']+$item_ret['teacher_leave_count'])/ ($item_ret['valid_count']+$item_ret['fix_change_count']+$item_ret['internet_change_count']+$item_ret['student_leave_count']+$item_ret['teacher_leave_count']);

            $item_ret['lesson_lose_rate'] = number_format($item_ret['lesson_lose_rate'],2);
        }



        $all_item=["ass_nick" => "全部" ];
        foreach ($ret_info as &$item) {
            foreach ($item as $key => $value) {
                if ((!is_int($key)) && ($key != "assistantid" )) {
                    $all_item[$key]=(@$all_item[$key])+$value;
                }
            }
            $item["ass_nick"]=$this->t_assistant_info->get_nick($item['assistantid']);
        }

        if (!$order_in_db_flag) {
            \App\Helper\Utils::order_list( $ret_info, $order_field_name, $order_type );
        }

        array_unshift($ret_info, $all_item);
        // dd($ret_info);
        return $this->pageView(__METHOD__,\App\Helper\Utils::list_to_page_info($ret_info) ,["data_ex_list"=>$ret_info]);
    }


    public function revisit_info_tongji_ass(){
        list($start_time,$end_time)=$this->get_in_date_range(date('Y-m-01',time()),0);

        $seller_groupid_ex    = $this->get_in_str_val('seller_groupid_ex', "");
        $require_adminid_list = $this->t_admin_main_group_name->get_adminid_list_new($seller_groupid_ex);
        $adminid_right              = $this->get_seller_adminid_and_right();

        $ret_info = $this->t_revisit_info->get_revisit_tongji_ass($start_time,$end_time,$require_adminid_list);
        $arr = ["sys_operator"=>"全部"];
        foreach($ret_info["list"] as $item){
            @$arr["xq_count"] +=$item["xq_count"];
            @$arr["sc_count"] +=$item["sc_count"];
        }
        array_unshift($ret_info["list"],$arr);
        return $this->pageView(__METHOD__,$ret_info,["adminid_right"=>$adminid_right]);

    }
    public function seller_user_count (){
        $sum_field_list=[
            "add_time_count",
            "call_count",
            "call_old_count",
            "first_revisite_time_count",
            "after_24_first_revisite_time_count",
            "require_test_lesson_count",
            "seller_test_lesson_count",
            "test_lesson_count",
            "test_lesson_count_succ",
            "order_count",
            "order_count_new",
            "order_count_from_stu",
            "order_count_next",
            "test_lesson_count_fail_need_money",
            "test_lesson_count_fail_not_need_money",
            "test_lesson_count_change_time",
        ];
        $order_field_arr=  array_merge(["nick" ] ,$sum_field_list );

        list( $order_in_db_flag, $order_by_str, $order_field_name,$order_type )
            =$this->get_in_order_by_str($order_field_arr ,"require_test_lesson_count desc");
        $groupid= $this->get_in_int_val("groupid",-1);
        $adminid=$this->get_account_id();
        $self_groupid = $this->t_admin_group_user->get_groupid_by_adminid(2 , $adminid );
        $admin_revisiterid=$this->get_in_int_val("admin_revisiterid",-1);
        list($start_time,$end_time)=$this->get_in_date_range(
            date("Y-m-d",time(NULL)-86400), 0,
            0,[],1
        );
        $ret_list=[];
        $set_ret_item=function(&$item, $key,$count_str=""  ) use(&$ret_list) {
            if (!$count_str) {
                $count_str=$key;
            }
            $admin_revisiterid =$item["admin_revisiterid"];
            if($admin_revisiterid>1)  {

                $count=$item[$count_str];
                if (!isset($ret_list[$admin_revisiterid] ) ) {
                    $ret_list[$admin_revisiterid] =[ "admin_revisiterid" => $admin_revisiterid ];
                }
                $ret_list[$admin_revisiterid][$key] =$count;
            }
        };


        $list = $this->t_seller_student_info->admin_revisiter_get_tongji_add_time($start_time,$end_time,$admin_revisiterid);
        foreach ($list as $item) {
            $set_ret_item( $item, "add_time_count", "count"  );
        }

        $list = $this->t_seller_student_info->admin_revisiter_get_tongji_first_revisite_time($start_time,$end_time,$admin_revisiterid);
        foreach ($list as $item) {
            $set_ret_item( $item, "first_revisite_time_count", "count"  );
            $set_ret_item( $item, "after_24_first_revisite_time_count", "after_24_count"  );
        }
        //申请
        $list = $this->t_lesson_info->admin_revisiter_test_lesson_require_tongji($start_time,$end_time,$admin_revisiterid);
        foreach ($list as $item) {
            $set_ret_item( $item, "require_test_lesson_count", "count"  );
        }


        //试听课
        $list = $this->t_lesson_info-> admin_revisiter_test_lesson_get_tongji($start_time,$end_time,$admin_revisiterid);
        foreach ($list as $item) {
            $set_ret_item( $item, "test_lesson_count_change_time"   );
            $set_ret_item( $item, "test_lesson_count"   );
            $set_ret_item( $item, "test_lesson_count_fail_need_money"   );
            $set_ret_item( $item, "test_lesson_count_succ"   );
        }


        //得到回访的总数
        $list = $this->t_tongji_date->admin_revisiter_get_list(1,$start_time,$end_time,$admin_revisiterid);

        foreach ($list as $item) {
            $set_ret_item( $item, "call_count" ,'count'  );
        }

        foreach ($ret_list as  &$r_item) {
            if ($r_item["admin_revisiterid"]==1) {
                $r_item["nick"]="助教";
            }else{
                $this->cache_set_item_account_nick($r_item,"admin_revisiterid", "nick");
            }

            $r_item["test_lesson_count_fail_not_need_money"]
                 = @$r_item["test_lesson_count"]
                 - @$r_item["test_lesson_count_succ"]
                 - @$r_item["test_lesson_count_fail_need_money"]
                 - @$r_item["test_lesson_count_change_time"]
                 ;
            $r_item["seller_test_lesson_count"]
                 = @$r_item["test_lesson_count"]
                 - @$r_item["test_lesson_count_change_time"]
                 ;


        }


        if ($groupid != -1 ) {
            $admin_list= $this->t_admin_group_user->get_userid_arr($groupid);
            $ret_list=array_filter($ret_list,function($item)use($admin_list)
            {
                $ret= in_array ($item["admin_revisiterid"], $admin_list);
                return $ret;
            });

        }


        $admin_info = $this->t_manager_info->get_admin_member_list();
        $admin_list= & $admin_info['list'] ;
        foreach ( $admin_list as $vk=> &$val){
            if( !isset($ret_list[$val['adminid']] ) ) {
                unset( $admin_list[$vk] );
            }else{


                $val['add_time_count'] = @$ret_list[$val['adminid']]['add_time_count'];
                $val['first_revisite_time_count'] = @$ret_list[$val['adminid']]['first_revisite_time_count'];
                $val['after_24_first_revisite_time_count'] = @$ret_list[$val['adminid']]['after_24_first_revisite_time_count'];
                $val['call_old_count'] = @$ret_list[$val['adminid']]['call_old_count'];
                $val['require_test_lesson_count'] = @$ret_list[$val['adminid']]['require_test_lesson_count'];
                $val['seller_test_lesson_count'] = @$ret_list[$val['adminid']]['seller_test_lesson_count'];
                $val['test_lesson_count'] = @$ret_list[$val['adminid']]['test_lesson_count'];
                $val['test_lesson_count_succ'] = @$ret_list[$val['adminid']]['test_lesson_count_succ'];
                $val['test_lesson_count_fail_need_money'] = @$ret_list[$val['adminid']]['test_lesson_count_fail_need_money'];
                $val['test_lesson_count_fail_not_need_money'] = @$ret_list[$val['adminid']]['test_lesson_count_fail_not_need_money'];
                $val['test_lesson_count_change_time']= @$ret_list[$val['adminid']]['test_lesson_count_change_time'];
            }
        }

        $ret_list=\App\Helper\Common::gen_admin_member_data($admin_info['list']);
        /*$ret_list= $this->gen_admin_member_data($admin_info['list']);*/
        foreach( $ret_list as &$item ) {
            E\Emain_type::set_item_value_str($item);
        }


        /* if (!$order_in_db_flag) {
            \App\Helper\Utils::order_list( $ret_list, $order_field_name, $order_type );
        }

        $all_item=[ "admin_revisiterid" => -1 ,"nick" => "全部"];
        \App\Helper\Utils::list_add_sum_item( $ret_list, $all_item,$sum_field_list );*/

        return $this->pageView(__METHOD__,\App\Helper\Utils::list_to_page_info($ret_list), [
            "group_field_list" =>  $this->t_admin_group_name->get_group_list( E\Eaccount_role::V_2 ),
            "self_groupid"=>$self_groupid
        ] );
    }

    public function all_info() {

        $all_money=$this->t_order_info->get_all_money();
        $row=$this->t_student_info->get_all_lesson_info();
        $row["all_money"] = intval($all_money);
        $row["left_money"] =intval( $all_money*$row["lesson_count_left"]/$row["lesson_count_all"]);
        $row["confirm_money"] =$row["all_money"]-$row["left_money"];
        $row["lesson_count_confirm"] =$row["lesson_count_all"]-$row["lesson_count_left"];

        return $this->pageView(__METHOD__,null, $row);
    }

    public function get_month_money_info(){
        $ret_list = $this->t_order_info->get_month_money_info();
        foreach($ret_list['list'] as &$val){
            $val['all_money']/=100;
        }

        return $this->pageView(__METHOD__, $ret_list);
    }

    public function online_user_count_list() {
        list($start_time ,$end_time)=$this->get_in_date_range_day(0);
        $week_flag=$this->get_in_int_val("week_flag", 1, E\Eboolean::class );
        $time_list=$this->t_online_count_log->get_list($start_time,$end_time);
        if (count($time_list) != 1440 ) {
            $t=$start_time;
            $tmp_list=[];
            foreach ( $time_list as $item ) {
                $c_time=$item["logtime"];
                while ( $t < $c_time -60  ) {
                    $tmp_list[]= ["online_count"=>null];
                    $t+=60;
                }
                $tmp_list[]= $item;
                $t+=60;
            }
            for ( ; $t<$end_time; $t+=60  ) {
                $tmp_list[]= ["online_count"=>null];
            }
            $time_list=$tmp_list;
        }


        $def_time_list=[];
        for($i=0; $i<288;$i++ ) {
            $def_time_list[$i]=0;
        }

        $date_count=1;
        for($i=0;$i<$date_count;$i++) {
            $opt_time=$start_time-$i*86400;
            $date_time_list=$this->t_lesson_info->get_lesson_time_list($start_time ,$end_time);
            $def_time_list=\App\Helper\Utils::get_online_line($def_time_list, $date_time_list );
        }


        return $this->pageView(__METHOD__,null,[
            "data_ex_list" =>[
                "time_list" => [$time_list, $def_time_list],
            ]
        ] );

    }

    public function online_def_user_count_list() {

        list($start_time ,$end_time)=$this->get_in_date_range_day(0);
        //5分钟 12*24
        $def_time_list=[];
        for($i=0; $i<288;$i++ ) {
            $def_time_list[$i]=0;
        }
        $time_list=[];

        $week_flag=$this->get_in_int_val("week_flag", 1, E\Eboolean::class );
        $date_count=1;
        if ($week_flag) {
            $date_count=8;

        }

        for($i=0;$i<$date_count;$i++) {
            $opt_time=$start_time-$i*86400;
            $date_time_list=$this->t_lesson_info->get_lesson_time_list($opt_time,$opt_time+86399 );
            $time_list[]=\App\Helper\Utils::get_online_line($def_time_list, $date_time_list );
        }


        return $this->pageView(__METHOD__,null,[
            "data_ex_list" =>[
                "time_list" => $time_list,
            ]
        ] );
    }

    public function assistant_test_lesson_count()
    {
        list($start_time,$end_time)=$this->get_in_date_range_month(0);
        $ass_test_lesson_type= $this->get_in_int_val("ass_test_lesson_type",-1,E\Eass_test_lesson_type::class);
        $list=$this->t_test_lesson_subject_require->tong_ass_test_lesson_info($start_time,$end_time, $ass_test_lesson_type ); // ,
        foreach( $list as &$item) {
            $this->cache_set_item_account_nick($item,"require_adminid","title");
        }
        $all_item=["title" => "全部"];
        \App\Helper\Utils::list_add_sum_item( $list, $all_item,["count", "course_count"]);

        $ret_info=\App\Helper\Utils::list_to_page_info($list);
        return $this->pageView(__METHOD__,$ret_info);

    }

    public function seller_call_rate(){
        $sum_field_list=[
            "all_count",
            "all_count_0",
            "all_count_1",
            "no_call",
            "no_call_0",
            "no_call_1",
            "call_duration",
            "calltotal",
            "called_num",
            "call_count",
            "lesson_num",
            "invalid_count",
            "no_connect",
            "valid_count",
            "require_test_count",
            "test_lesson_count",
            "fail_need_pay_count",
            "order_count",
        ];
        $order_field_arr=  array_merge(["account" ] ,$sum_field_list );

        $grade_list       = $this->get_in_enum_list(E\Egrade::class);
        $group_adminid=$this->get_in_int_val("group_adminid",-1);

        $hour     = $this->get_in_int_val("hour", -1,E\Ehour::class);
        $hour_str = E\Ehour::get_desc($hour);
        $hour_arr = explode('-',$hour_str);



        list( $order_in_db_flag, $order_by_str, $order_field_name,$order_type )
            =$this->get_in_order_by_str($order_field_arr ,"account desc");


        $group_list=$this->t_admin_group_name->get_group_list(2);
        // dd($group_list);
        $groupid=$this->get_in_int_val("groupid", -1);
        list($start_time,$end_time)= $this->get_in_date_range_day( 0 );

        if($hour != -1){
            $start_change = date('Y-m-d',$start_time);
            $start_time   = strtotime($start_change.' '.$hour_arr['0']);
            $end_time     = $start_time+3600;
        }

        // dd($group_list);



        $origin_ex           = $this->get_in_str_val("origin_ex");

        $ret_info=$this->t_test_lesson_subject->get_seller_count( $start_time, $end_time, $grade_list );
        // dd($ret_info);
        $tl_info=$this->t_test_lesson_subject_require->tongji_test_lesson_group_by_admin_revisiterid($start_time,$end_time ,$grade_list);
        // dd($tl_info);
        $tr_info=$this->t_test_lesson_subject_require->tongji_require_test_lesson_group_by_admin_revisiterid($start_time,$end_time,$grade_list);
        //order info
        $order_info=$this->t_order_info->get_1v1_order_seller_list($start_time,$end_time ,$grade_list,"");

        // dd($call_rate_info);
        // dd($order_info);
        $obj_list=&$ret_info["list"] ;
        foreach ($tl_info["list"] as $tl_item) {
            $k=$tl_item["admin_revisiterid"];

            \App\Helper\Utils::array_item_init_if_nofind($obj_list,$k, ["admin_revisiterid"=>$k] );
            $obj_list[$k]["test_lesson_count"]=$tl_item["test_lesson_count"];
            $obj_list[$k]["fail_need_pay_count"]=$tl_item["fail_need_pay_count"];
            $obj_list[$k]["fail_all_count"]=$tl_item["fail_all_count"];
            $obj_list[$k]["succ_all_count"]=$tl_item["succ_all_count"];

        }


        foreach ($tr_info["list"] as $tr_item) {
            $k=$tr_item["admin_revisiterid"];
            \App\Helper\Utils::array_item_init_if_nofind($obj_list,$k, ["admin_revisiterid"=>$k] );
            $obj_list[$k]["require_test_count"]=$tr_item["require_test_count"];

        }

        // dd($order_info);
        foreach ($order_info["list"] as $order_item) {
            $k=$order_item["adminid"];
            \App\Helper\Utils::array_item_init_if_nofind($obj_list,$k, ["admin_revisiterid"=>$k] );
            $obj_list[$k]["order_count"]=$order_item["all_count"];
            $obj_list[$k]["order_money"]=$order_item["all_price"];
        }

        $date_list = $this->t_id_opt_log-> get_seller_tongji($start_time,$end_time,$grade_list);
        // 新分配
        foreach ($date_list as $date_item) {
            $k=$date_item["opt_id"];
            \App\Helper\Utils::array_item_init_if_nofind($obj_list,$k, ["admin_revisiterid"=>$k] );
            $obj_list[$k]["assigned_count"]=$date_item["assigned_count"];
            $obj_list[$k]["get_new_count"]=$date_item["get_new_count"];
            $obj_list[$k]["get_histroy_count"]=$date_item["get_histroy_count"];
        }


        //处理通话时长
        $call_duration_info = $this->t_manager_info->get_call_duration_time($start_time,$end_time,$grade_list);
        foreach( $call_duration_info as $item_call){
            $k=$item_call["adminid"];
            \App\Helper\Utils::array_item_init_if_nofind($obj_list,$k, ["admin_revisiterid"=>$k] );
            $obj_list[$k]["call_duration"]=$item_call["duration"];
        }

        //处理接通次数
        foreach( $call_duration_info as $item_call){
            $k=$item_call["adminid"];
            \App\Helper\Utils::array_item_init_if_nofind($obj_list,$k, ["admin_revisiterid"=>$k] );
            $obj_list[$k]["called_num"] = $item_call["callnum"];
            $obj_list[$k]["calltotal"] = $item_call["calltotal"];
        }


        //处理接通率
        foreach( $call_duration_info as $item_call){
            $k=$item_call["adminid"];
            \App\Helper\Utils::array_item_init_if_nofind($obj_list,$k, ["admin_revisiterid"=>$k] );
        }

        //处理1小时前试听课未联系数
        $contact_lesson_num_info = $this->t_lesson_info->get_not_contact_lesson_num( $start_time, $end_time );
        // dd($contact_lesson_num_info);
        foreach( $contact_lesson_num_info as $item_lesson){
            $k=$item_lesson["adminid"];
            \App\Helper\Utils::array_item_init_if_nofind($obj_list,$k, ["admin_revisiterid"=>$k] );
            $obj_list[$k]["lesson_num"]=$item_lesson["lesson_num"];
        }


        // dd($call_duration_info);


        $all_item=["account" => "全部","admin_revisiterid" =>-1, ];
        foreach ($ret_info["list"] as &$item) {
            $item["valid_count"]=@$item["call_count"]-  @$item["invalid_count"]-@$item["no_connect"];
            foreach ($item as $key => $value) {
                if ((!is_int($key)) && ($key != "admin_revisiterid" )) {
                    $all_item[$key]=(@$all_item[$key])+$value;
                }
            }
            $this->cache_set_item_account_nick($item,"admin_revisiterid","account");
        }


        // dd($ret_info['list']);

        $ret_info = $ret_info['list'];
        $admin_info = $this->t_manager_info->get_admin_member_list();
        // dd($admin_info);
        $admin_list= & $admin_info['list'] ;
        if ($group_adminid >0) {
            $groupid=$this->t_admin_group_name->get_groupid_by_master_adminid($group_adminid);
            $mark_user_map= $this->t_admin_group_user->get_user_map($groupid);
        }
        // dd($ret_info);
        // dd($admin_list);

        foreach ($admin_list as $vk=>&$val){
            $adminid=$val['adminid'];
            if (!isset($ret_info[$adminid ] )
                || ( $group_adminid >0 &&  !isset($mark_user_map[ $adminid ] ) )  )  {
                unset( $admin_list[$vk] );
            }else{

                $val['admin_revisiterid'] = $adminid ;
                $ret_item=@$ret_info[$adminid];
                $val['all_count'] = @$ret_item['all_count'];
                $val['all_count_0'] = @$ret_item['all_count_0'];
                $val['all_count_1'] = @$ret_item['all_count_1'];
                $val['no_call'] = @$ret_item['no_call'];
                $val['no_call_0'] = @$ret_item['no_call_0'];
                $val['no_call_1'] = @$ret_item['no_call_1'];
                $val['call_count'] = @$ret_item['account'];
                $val['all_account'] = @$ret_item['call_count'];
                $val['invalid_count'] = @$ret_item['invalid_count'];
                $val['no_connect'] = @$ret_item['no_connect'];
                $val['valid_count'] = @$ret_item['valid_count'];
                $val['test_lesson_count'] = @$ret_item['test_lesson_count'];
                $val['fail_need_pay_count'] = @$ret_item['fail_need_pay_count'];
                $val['require_test_count'] = @$ret_item['require_test_count'];
                $val['succ_all_count'] = @$ret_item['succ_all_count'];
                $val['fail_all_count'] = @$ret_item['fail_all_count'];
                $val['order_count'] = @$ret_item['order_count'];
                $val['order_money'] = @$ret_item['order_money'];
                $val['global_tq_no_call'] = @$ret_item['global_tq_no_call'];

                $val['assigned_count'] = @$ret_item['assigned_count'];
                $val['call_duration'] = @$ret_item['call_duration'];
                $val['lesson_num'] = @$ret_item['lesson_num'];
                $val['called_num'] = @$ret_item['called_num'];
                $val['calltotal'] = @$ret_item['calltotal'];
                $val['get_new_count'] = @$ret_item['get_new_count'];
                $val['get_histroy_count'] = @$ret_item['get_histroy_count'];


            }
        }

        // dd($admin_info);
        $ret_info=\App\Helper\Common::gen_admin_member_data($admin_info['list']);
        // dd($ret_info);
        foreach( $ret_info as &$item ) {
            E\Emain_type::set_item_value_str($item);
            $item["call_duration_str"] =  \App\Helper\Common::get_time_format( @$item["call_duration"]  );

            $item["called_rate"] = number_format( \App\Helper\Common::div_safe(@$item["called_num"],@$item['calltotal'])*100,2);
        }


        return $this->pageView(__METHOD__,\App\Helper\Utils::list_to_page_info($ret_info),["group_list"=>$group_list,"data_ex_list"=>$ret_info]);

    }

    public function seller_time_income_list(){
        \App\Helper\Utils::logger("START");
        $start = strtotime(date('Y-m-01',time()));
        $day = intval(ceil((time()-$start)/86400)-1);
        $day = $day-2*$day;
        list($start_time,$end_time)=$this->get_in_date_range(0,0,0,[],3);
        if($end_time >= time()){
            $end_time = time();
        }

        $start_first = date('Y-m-01',$start_time);
        $res = [];
        $this->t_seller_month_money_target->switch_tongji_database();
        $ret_info = $this->t_seller_month_money_target->get_seller_month_time_info($start_first);
        $start_day = date('d',$start_time);
        $end_day = date('d',($end_time-10));
        foreach($ret_info as $k=>&$item){
            $month_time = json_decode($item['month_time'],true);
            $i = $j = $l=0;
            $now = time();
            $day = ceil(($end_time- $start_time)/86400);
            if(!empty($month_time)){
                foreach($month_time as $val){
                    if(substr($val[0],11,1) ==1){
                        $i++;
                    }
                    if(substr($val[0],11,1) ==1 && substr($val[0],8,2) <= $end_day && substr($val[0],8,2) >= $start_day ){
                        $j++;
                    }
                }
                $leave_and_overtime = json_decode($item['leave_and_overtime'],true);
                if(!empty($leave_and_overtime)){
                    foreach($leave_and_overtime as $v){
                        if(substr($v[0],11,1) ==2 && substr($v[0],8,2) <= $end_day && substr($v[0],8,2) >= $start_day ){
                            $l--;
                        }
                        if(substr($v[0],11,1) ==3 && substr($v[0],8,2) <= $end_day && substr($v[0],8,2) >= $start_day ){
                            $l++;
                        }

                    }
                }

            }

            $res[$k]['month_work_day'] = $i;
            $res[$k]['month_work_day_now'] = $j;
            $res[$k]['month_work_day_now_real'] = $j+$l;
            $res[$k]['target_personal_money'] = $item['personal_money'];
        }
        $this->t_admin_group_user->switch_tongji_database();
        $group_money_info = $this->t_admin_group_user->get_seller_month_money_info($start_first);
        $num_info = $this->t_admin_group_user->get_group_num($start_time);
        foreach($group_money_info as &$item){
            $groupid = $item['groupid'];
            if($groupid >0 && isset($num_info[$groupid])){
                $res[$item['adminid']]['target_money'] =  $item['month_money']/$num_info[$groupid]['num'];
            }
        }
        $this->t_tq_call_info->switch_tongji_database();


        /*
        $list=$this->t_tq_call_info->tongji_tq_info_new($start_time,$end_time);
        foreach ($list as $k=>&$item )  {
            $res[$k]['is_called_phone_count_for_month'] = $item['all_count'];
            if(isset($res[$k]['month_work_day_now_real']) && $res[$k]['month_work_day_now_real'] != 0){
                $res[$k]['is_called_phone_count_for_day'] = round($item['all_count']/$res[$k]['month_work_day_now_real']);
                $res[$k]['duration_count_for_day'] = round($item['duration_count']/$res[$k]['month_work_day_now_real']);
            }
        }
        */


        $this->t_test_lesson_subject_require->switch_tongji_database();

        $tr_info=$this->t_test_lesson_subject_require->tongji_require_test_lesson_group_by_admin_revisiterid($start_time,$end_time);
        foreach($tr_info['list'] as $item){
            $adminid = $item['admin_revisiterid'];
            $res[$adminid]['require_test_count_for_month']=$item['require_test_count'];
            if(isset($res[$adminid]['month_work_day_now_real']) && $res[$adminid]['month_work_day_now_real'] != 0){
                $res[$adminid]['require_test_count_for_day'] = round($item['require_test_count']/$res[$adminid]['month_work_day_now_real']);
            }

        }
        $test_leeson_list=$this->t_test_lesson_subject_require->tongji_test_lesson_group_by_admin_revisiterid($start_time,$end_time );
        foreach($test_leeson_list['list'] as $item){
            $adminid = $item['admin_revisiterid'];
            $res[$adminid]['succ_all_count_for_month']=$item['succ_all_count'];
            $res[$adminid]['test_lesson_count_for_month'] = $item['test_lesson_count'];
            $res[$adminid]['fail_all_count_for_month'] = $item['fail_all_count'];
            if($item['test_lesson_count'] != 0){
                $res[$adminid]['lesson_per'] = round($item['fail_all_count']/$item['test_lesson_count'],2);
            }

        }
        $this->t_order_info->switch_tongji_database();

        $order_new = $this->t_order_info->get_1v1_order_list_by_adminid($start_time,$end_time,-1);
        foreach($order_new as $k=>$v){
            $res[$k]['all_new_contract_for_month'] = $v['all_new_contract'];
            if(isset($res[$k]['succ_all_count_for_month']) && $res[$k]['succ_all_count_for_month'] != 0){
                $res[$k]['order_per'] =round($v['all_new_contract']/$res[$k]['succ_all_count_for_month'],2);
            }
            $res[$k]['all_price_for_month'] = $v['all_price']/100;
            /* $res[$k]['max_price_for_month'] = $v['max_price']/100;*/
            if(isset($res[$k]['target_money']) && $res[$k]['target_money'] != 0){
                $res[$k]['finish_per'] =  round($v['all_price']/100/$res[$k]['target_money'],2);
                $res[$k]['los_money'] = $res[$k]['target_money']-$v['all_price']/100;
            }
            if(isset($res[$k]['target_personal_money']) && $res[$k]['target_personal_money'] != 0){
                $res[$k]['finish_personal_per'] =  round($v['all_price']/100/$res[$k]['target_personal_money'],2);
                $res[$k]['los_personal_money'] = $res[$k]['target_personal_money']-$v['all_price']/100;
            }
            $res[$k]['become_member_time'] = $v['create_time'];
            $res[$k]['leave_member_time'] = $v['leave_member_time'];
        }
        foreach ($res as $ret_k=> &$res_item) {
            $res_item["adminid"] = $ret_k ;
        }
        //$ret_info=\App\Helper\Common::gen_admin_member_data($res);
        $ret_info=\App\Helper\Common::gen_admin_member_data($res,[],0, strtotime( date("Y-m-01",$start_time )   ));
        $ret_info_new = [];
        foreach( $ret_info as &$item ) {
            E\Emain_type::set_item_value_str($item);

            $item['lesson_per'] = @$item['test_lesson_count_for_month']!=0?(round(@$item['fail_all_count_for_month']/$item['test_lesson_count_for_month'],2)*100)."%":0;
            $item['order_per'] = @$item['succ_all_count_for_month']!=0?(round(@$item['all_new_contract_for_month']/$item['succ_all_count_for_month'],2)*100)."%":0;
            $item['finish_per'] =@$item['target_money']!=0?(round(@$item['all_price_for_month']/$item['target_money'],2)*100)."%":0;
            $item['finish_personal_per'] =@$item['target_personal_money']!=0?(round(@$item['all_price_for_month']/$item['target_personal_money'],2)*100)."%":0;


            $item['duration_count_for_day'] = \App\Helper\Common::get_time_format(@$item['duration_count_for_day']);
            $item['ave_price_for_month'] =@$item['all_new_contract_for_month']!=0?round(@$item['all_price_for_month']/@$item['all_new_contract_for_month']):0;
            $item['los_money'] = @$item['target_money']-@$item['all_price_for_month'];
            $item['los_personal_money'] = @$item['target_personal_money']-@$item['all_price_for_month'];

            if($item['level'] == "l-4" ){
                $item['target_money']="";
                $item['finish_per'] = "";
                $item['los_money'] = "";
                if(!isset($item['leave_member_time']) or !isset($item['create_time'])){
                    $manager_item = $this->t_manager_info->field_get_list($item['adminid'],'create_time,leave_member_time');
                    $item['become_member_time'] = $manager_item['create_time'];
                    $item['leave_member_time'] = $manager_item['leave_member_time'];
                }
                $time = $item['leave_member_time']?$item['leave_member_time']:time();
                $item['become_member_long_time'] = (int)(($time-$item['become_member_time'])/86400);
                $ret_info_new[] = $item;
            }

        }
        dd($ret_info,$ret_info_new);
        \App\Helper\Utils::logger("OUTPUT");
        return $this->pageView(__METHOD__,\App\Helper\Utils::list_to_page_info($ret_info));
    }

}
