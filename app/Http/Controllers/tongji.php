<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;


use App\Helper\Utils;

use Illuminate\Support\Facades\Input ;
class tongji extends Controller
{
    use  CacheNick;
    use  TeaPower;
    var $switch_tongji_database_flag = true;

    public function contract()
    {


        list($start_time,$end_time,$opt_date_str)= $this->get_in_date_range(
            -14, 1, 0, [
                0 => array( "order_time", "合同生成时间"),
                1 => array("check_money_time","财务确认时间"),
            ], 0,0
        );


        $date_list=\App\Helper\Common::get_date_time_list($start_time, $end_time-1);

        $stu_from_type = $this->get_in_int_val("stu_from_type",-1);
        $contract_type = $this->get_in_enum_val(E\Econtract_type::class, 0);

        $list      = $this->t_order_info->get_1v1_order_list($start_time,$end_time,"", $stu_from_type, [],[] ,$contract_type,[-1],-1,$opt_date_str);
        $all_item=[
            "title"=>"全部",
            "order_count"=> 0,
            "money"=> "",
        ];

        foreach ($list as $item) {
            $opt_date=date("Y-m-d",$item["opt_date"]);
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

    public function contract_down()
    {
        list($start_time,$end_time) = $this->get_in_date_range( 0,0,0,[],1);

        $opt_date_str=0;
        $date_list=\App\Helper\Common::get_date_time_list($start_time, $end_time-1);

        $stu_from_type = $this->get_in_int_val("stu_from_type",-1);
        $contract_type = $this->get_in_enum_val(E\Econtract_type::class, 0);

        $list      = $this->t_order_info->get_1v1_order_list($start_time,$end_time,"", $stu_from_type, [],[] ,$contract_type,[-1],-1,$opt_date_str);
        $all_item=[
            "title"=>"全部",
            "order_count"=> 0,
            "money"=> "",
        ];

        foreach ($list as $item) {
            $opt_date=date("Y-m-d",$item["opt_date"]);
            $date_item= &$date_list[$opt_date];
            $date_item["order_count"]=@$date_item["order_count"]+1;
            $date_item["money"]=@$date_item["money"]+ $item["price"]/100;
            $all_item["money"]+= $item["price"]/100;
            $all_item["order_count"]+=1;

        }

        $all_list=$date_list;

        array_unshift($all_list, $all_item);
        $page_info=\App\Helper\Utils::list_to_page_info($all_list);
        $data = [
            "00:00" => ['title'=>'00:00','money'=> 408765],
            "01:00" => ['title'=>'01:00','money'=> 331260],
            "02:00" => ['title'=>'02:00','money'=> 368721],
            "03:00" => ['title'=>'03:00','money'=> 103022],
            "04:00" => ['title'=>'04:00','money'=> 74319],
            "05:00" => ['title'=>'05:00','money'=> 23102],
            "06:00" => ['title'=>'06:00','money'=> 12300],
            "07:00" => ['title'=>'07:00','money'=> 31000],
            "08:00" => ['title'=>'08:00','money'=> 29800],
            "09:00" => ['title'=>'09:00','money'=> 129987],
            "10:00" => ['title'=>'10:00','money'=> 346330],
            "11:00" => ['title'=>'11:00','money'=> 446330],
            "12:00" => ['title'=>'12:00','money'=> 500317],
            "13:00" => ['title'=>'13:00','money'=> 680742],
            "14:00" => ['title'=>'14:00','money'=> 622892],
            "15:00" => ['title'=>'15:00','money'=> 699885],
            "16:00" => ['title'=>'16:00','money'=> 607439],
            "17:00" => ['title'=>'17:00','money'=> 753218],
            "18:00" => ['title'=>'18:00','money'=> 811374],
            "19:00" => ['title'=>'19:00','money'=> 973211],
            "20:00" => ['title'=>'20:00','money'=> 1225980],
            "21:00" => ['title'=>'21:00','money'=> 1085703],
            "22:00" => ['title'=>'22:00','money'=> 1123152],
            "23:00" => ['title'=>'23:00','money'=> 1000417],
            "24:00" => ['title'=>'24:00','money'=> 1175487],
        ];
        // $s = 0;
        // foreach ($data as $v) {
        //     $s = $s+$v['money'];
        // }
        // dd($s);
        return $this->pageView(__METHOD__, $page_info,["data_ex_list"=>$data]);
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
            $opt_date=date("Y-m-d",$item["opt_date"]);
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
        list($start_time,$end_time) = $this->get_in_date_range(0,0,0,null,3);
        $is_succ = $this->get_in_int_val('is_succ',-1);
        $type    = $this->get_in_int_val("type",-1);

        $date_list = \App\Helper\Common::get_date_time_list($start_time, $end_time-1);
        $ret_list  = $this->t_sms_msg->tongji_get_list($start_time,$end_time,$is_succ,$type);
        foreach ($ret_list as $item)  {
            $opt_date           = $item["log_date"];
            $date_item          = &$date_list[$opt_date];
            $date_item["count"] = @$date_item["count"]+$item["count"];
        }
        $ret_info = \App\Helper\Utils::list_to_page_info($date_list);
        return $this->pageView(__METHOD__,  $ret_info);
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
        list($start_time,$end_time)=$this->get_in_date_range_day(0);
        $end_time=$start_time+86400;

        $admin_list=$this->t_admin_card_log->get_admin_list($start_time,$end_time);

        $map=[];
        foreach( $admin_list as  &$d_item ) {
            //判断是否产品研发事业部
            $dev_flag = $this->check_is_dev_department($d_item["adminid"]);

            $d_item["work_time"]=  $d_item["end_logtime"] -  $d_item["start_logtime"] ;
            $d_item["work_time_str"] =\App\Helper\Common::get_time_format( $d_item["work_time"]  );
            $day_start = strtotime(date("Y-m-d",$d_item["start_logtime"])." 10:01:00");
            $day_end = strtotime(date("Y-m-d",$d_item["start_logtime"])." 18:30:00");
            if($dev_flag==1){
                if($d_item["end_logtime"]<$day_end || $d_item["start_logtime"]>=$day_start || $d_item["work_time"] < 9*3600){
                    $d_item["error_flag"]=true;
                    $d_item["error_flag_str"] ="是";
                }
            }else{
                $d_item["error_flag"]= ($d_item["work_time"] < 9*3600);
                if ($d_item["error_flag"]) {
                    $d_item["error_flag_str"] ="是";
                } 
            }
            \App\Helper\Utils::unixtime2date_for_item($d_item,"start_logtime", "","H:i:s");
            \App\Helper\Utils::unixtime2date_for_item($d_item,"end_logtime" ,"", "H:i:s");


            $map[$d_item["adminid"]]=true ;
        }
        $def_admin_list = $this->t_manager_info->get_admin_member_list();
        foreach ($def_admin_list["list"]  as $a_item ) {
            if (!@$map[$a_item["adminid"]]) {
                $admin_list[]=["adminid" =>  $a_item["adminid"] ] ;
            }
        }

        $ret_list = \App\Helper\Common::gen_admin_member_data($admin_list,["error_flag_str", "error_flag","start_logtime", "end_logtime", "work_time_str", "work_time" ] );
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
        $phone=$this->t_manager_info->field_get_value($adminid, 'phone');
        $userid=$this->t_company_wx_users->get_userid_for_adminid($phone);

        //判断是否产品研发事业部
        $dev_flag = $this->check_is_dev_department($adminid);
        \App\Helper\Utils::logger("dev_flag:".$dev_flag);


        $info=$this->t_company_wx_approval->get_info_for_userid($userid, $start_time, $end_time);
        $len = count($info);
        foreach($info as $key => $item) {
            $num = ($item['end_time'] - $item['start_time']) / 86400;
            if ($num >= 1) {
                for($i = 0; $i <= $num; $i ++) {
                    $info[$len]['start_time'] = $item['start_time'] + 86400 * $i;
                    $info[$len]['start_leavetime'] = $item['start_time'];
                    $info[$len]['end_time'] = $item['end_time'];
                    $len ++;
                }

            }
        }


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
            $current = strtotime(date('Y', time()).'-'.$d_item['title']);
            foreach($info as $key => $val) {
                if ($val['start_time'] >= $current && ($val['start_time'] - $current < 19*3600)) {
                    if (!isset($val['start_leavetime'])) $val['start_leavetime'] = $val['start_time'];
                    if ($val['start_time'] - $current < 10*3600) {
                        $d_item["error_str"] ="请假 |".date('Y-m-d H:i:s', $val['start_leavetime']).'-'.date('Y-m-d H:i:s', $val['end_time']);
                    } else {
                        $d_item["error_str"] = date('H', $val['start_time'])."点请假 |".date('Y-m-d H:i:s', $val['start_leavetime']).'-'.date('Y-m-d H:i:s', $val['end_time']);
                    }
                    unset($info[$key]);
                }
            }
            if (isset ( $d_item["start_logtime"]) ){
                $d_item["work_time"]=  $d_item["end_logtime"] -  $d_item["start_logtime"] ;
                $d_item["work_time_str"] =\App\Helper\Common::get_time_format( $d_item["work_time"]  );
                $day_start = strtotime(date("Y-m-d",$d_item["start_logtime"])." 10:01:00");
                $day_end = strtotime(date("Y-m-d",$d_item["start_logtime"])." 18:30:00");
                if($dev_flag==1){
                    if($d_item["end_logtime"]<$day_end || $d_item["start_logtime"]>=$day_start || $d_item["work_time"] < 9*3600){
                        $d_item["error_flag"]=1;
                        $d_item["error_flag_str"] ="是";
                    }
                }else{
                    $d_item["error_flag"]= ($d_item["work_time"] < 9*3600);
                    if ($d_item["error_flag"]) {
                        $d_item["error_flag_str"] ="是";
                    } 
                }

                \App\Helper\Utils::unixtime2date_for_item($d_item,"start_logtime", "","H:i:s");
                \App\Helper\Utils::unixtime2date_for_item($d_item,"end_logtime" ,"", "H:i:s");
            }
            if (!isset($d_item["work_time"]) )  {
                $d_item["work_time"]="";
                $d_item["work_time_str"]="";
                $d_item["start_logtime"]="";
                $d_item["error_flag_str"]="";
                $d_item["error_flag"]="";
                $d_item["error_str"]="";
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

            $mol = $item_ret['fix_change_count']+$item_ret['internet_change_count']+$item_ret['student_leave_count']+$item_ret['teacher_leave_count'];
            $den = $item_ret['valid_count']+$item_ret['fix_change_count']+$item_ret['internet_change_count']+$item_ret['student_leave_count']+$item_ret['teacher_leave_count'];

            $item_ret['lesson_lose_rate'] = $den>0?$mol/$den:0;

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
        $this->check_and_switch_tongji_domain();
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
        /*
        $year=$this->get_in_el_year(date('Y', time()));
        $start_time = $year[0].'-01-01 00:00:00';
        $end_time = date('Y-m-d 23:59:59', strtotime("$start_time +1 year -1day"));
        $end_time = strtotime($end_time);
        */
        list( $start_time, $end_time)= $this->get_in_date_range_month(0);
        $ret_list = $this->t_order_info->get_month_money_info($start_time, $end_time);

        $lesson_list = $this->t_month_student_count->get_month_money_info($start_time, $end_time);
        foreach($ret_list['list'] as $month=> &$item){
            $item['all_money']/=100;
            $now = date('Y-m', time());
            if ( $month == $now ) {
                $start = strtotime($now);
                $end = strtotime('+1 month', $start);
                $lesson_money = $this->t_lesson_info_b3->get_lesson_count_money_info_by_month($start, $end);
                $item['lesson_count']       = $lesson_money['lesson_count']/100;
                $item['lesson_stu_num']     = $lesson_money['lesson_stu_num'];
                $item['lesson_count_money'] = $lesson_money['lesson_count_money']/100;
            } else {
                $tmp_arr = @$lesson_list[$month];
                $item['lesson_count'] = @$tmp_arr['lesson_count']/100;
                $item['lesson_stu_num'] = @$tmp_arr['lesson_stu_num'];
                $item['lesson_count_money'] = @$tmp_arr['lesson_count_money']/100;
            }
        }

        return $this->pageView(__METHOD__, $ret_list);
    }

    public function online_user_count_list() {
        list($start_time ,$end_time)=$this->get_in_date_range_day(0);
        $week_flag=$this->get_in_int_val("week_flag", 1, E\Eboolean::class );
        $time_list=$this->t_online_count_log->get_list($start_time,$end_time);
        //dd($time_list);
        $time_list=\App\Helper\Common::gen_echarts_time_data($time_list,'logtime', "online_count" );

        $need_deal_count_list=$this->t_tongji_log->get_list(
            E\Etongji_log_type::V_SYS_NEED_GEN_LESSON_VIDEO_COUNT, $start_time,$end_time);
        $need_deal_count_list=\App\Helper\Common::gen_echarts_time_data($need_deal_count_list);


        $opt_time=$start_time;
        $date_time_list=$this->t_lesson_info->get_lesson_time_list($start_time ,$end_time);
        $def_time_list=\App\Helper\Utils::gen_echarts_online_line($date_time_list, $start_time );

        return $this->pageView(__METHOD__,null,[
            "data_ex_list" =>[
                "time_list" => [$time_list, $def_time_list, $need_deal_count_list],
            ]
        ] );

    }

    //每个xmpp在线用户统计显示
    public function online_user_count_xmpp_list() {
        list($start_time ,$end_time)=$this->get_in_date_range_day(0);
        $week_flag=$this->get_in_int_val("week_flag", 1, E\Eboolean::class );
        $xmpp_value=$this->get_in_str_val("xmpp_value",'');  //xmpp传值筛选
        $xmpp_id=$this->t_xmpp_server_config->get_xmpp_id($xmpp_value); //获取xmpp_id
        $def_time_list=$this->t_online_count_xmpp_log->get_list($xmpp_id,$start_time,$end_time);
        //dd($def_time_list);
        //查询xmpp列表
        $page_info= $this->get_in_page_info();
        $ret_info=$this->t_xmpp_server_config->get_list($page_info);

        return $this->pageView(__METHOD__,null,[
            "data_ex_list" =>[
                "time_list" => [[], $def_time_list, []],
            ],
            "xmpp_list"=>$ret_info["list"]
        ] );

    }
    public function reset_xmpp_online_count() {
        list($start_time ,$end_time)=$this->get_in_date_range_day(0);
        $xmpp_value=$this->get_in_str_val("xmpp_value",'');  //xmpp传值筛选
        $xmpp_id=$this->t_xmpp_server_config->get_xmpp_id($xmpp_value); //获取xmpp_id
        $this->switch_tongji_database(false);
        $end_time= $start_time+86400;

        $def_time_list=[];
        for($tmp=$start_time; $tmp<$end_time;$tmp+=300 ) {
            $def_time_list[$tmp]=0;
        }
        \App\Helper\Utils::logger("start_time :$start_time, end_time: $end_time");


        $date_time_list=$this->t_lesson_info_b3-> get_lesson_time_xmpp_list($xmpp_value,$start_time ,$end_time);
        $time_list=\App\Helper\Utils::get_online_line_timestramp($def_time_list, $date_time_list );
        foreach($time_list as $logtime=> $val){
            $this->t_online_count_xmpp_log->row_insert([
                "xmpp_id" => $xmpp_id,
                "logtime" => $logtime,
                "online_count" => $val,
            ],true);
        }
        return $this->output_succ() ;


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

        // dd($date_time_list);

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
        $ret_info_new = [['long_time'=>'','count'=>0,'money'=>0],['long_time'=>'','count'=>0,'money'=>0],['long_time'=>'','count'=>0,'money'=>0],['long_time'=>'','count'=>0,'money'=>0],['long_time'=>'','count'=>0,'money'=>0],['long_time'=>'','count'=>0,'money'=>0],['long_time'=>'','count'=>0,'money'=>0],['long_time'=>'','count'=>0,'money'=>0]];
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
            $item['los_personal_money'] = abs($item['los_personal_money']);

            if($item['level'] == "l-4" ){
                $item['target_money']="";
                $item['finish_per'] = "";
                $item['los_money'] = "";
                if(!isset($item['leave_member_time']) && !isset($item['create_time'])){
                    $manager_item = $this->t_manager_info->field_get_list($item['adminid'],'create_time,leave_member_time');
                    $item['become_member_time'] = $manager_item['create_time'];
                    $item['leave_member_time'] = $manager_item['leave_member_time'];
                    $item['los_personal_money'] = 0;
                }
                $time = $item['leave_member_time']?$item['leave_member_time']:time();
                $item['become_member_long_time'] = $time-$item['become_member_time'];
                if($item['become_member_long_time']<=2592000){
                    $ret_info_new[0]['long_time'] = '1个月';
                    $ret_info_new[0]['count']++;
                    $ret_info_new[0]['money'] += $item['los_personal_money'];
                }elseif(2592000<$item['become_member_long_time'] && $item['become_member_long_time']<=2592000*2){
                    $ret_info_new[1]['long_time'] = '1个月~2个月';
                    $ret_info_new[1]['count']++;
                    $ret_info_new[1]['money'] += $item['los_personal_money'];
                }elseif(2592000*2<$item['become_member_long_time'] && $item['become_member_long_time']<=2592000*3){
                    $ret_info_new[2]['long_time'] = '2个月~3个月';
                    $ret_info_new[2]['count']++;
                    $ret_info_new[2]['money'] += $item['los_personal_money'];
                }elseif(2592000*3<$item['become_member_long_time'] && $item['become_member_long_time']<=2592000*4){
                    $ret_info_new[3]['long_time'] = '3个月~4个月';
                    $ret_info_new[3]['count']++;
                    $ret_info_new[3]['money'] += $item['los_personal_money'];
                }elseif(2592000*4<$item['become_member_long_time'] && $item['become_member_long_time']<=2592000*5){
                    $ret_info_new[4]['long_time'] = '4个月~5个月';
                    $ret_info_new[4]['count']++;
                    $ret_info_new[4]['money'] += $item['los_personal_money'];
                }elseif(2592000*5<$item['become_member_long_time'] && $item['become_member_long_time']<=2592000*6){
                    $ret_info_new[5]['long_time'] = '5个月~6个月';
                    $ret_info_new[5]['count']++;
                    $ret_info_new[5]['money'] += $item['los_personal_money'];
                }else{
                    $ret_info_new[6]['long_time'] = '6个月以上';
                    $ret_info_new[6]['count']++;
                    $ret_info_new[6]['money'] += $item['los_personal_money'];
                }
                $ret_info_new[7]['long_time'] = '总计';
                $ret_info_new[7]['count']++;
                $ret_info_new[7]['money'] += $item['los_personal_money'];
            }
        }

        \App\Helper\Utils::logger("OUTPUT");
        return $this->pageView(__METHOD__,\App\Helper\Utils::list_to_page_info($ret_info_new));
    }

    public function gen_origin_data($old_list,$no_sum_list=[] ,$origin_ex="")
    {

        $value_map=$this->t_origin_key->get_list( $origin_ex);
        $cur_key_index=1;
        $check_init_map_item=function (&$item, $key, $key_class, $value = "") {
            global $cur_key_index;
            if (!isset($item [$key])) {
                $item[$key] = [
                    "value" => $value,
                    "key_class" => $key_class."-".$cur_key_index,
                    "sub_list"=>[] ,
                    "data" => array(),
                ];
                $cur_key_index++;
            }
        };
        $add_data=function (&$item, $add_item ,$self_flag=false) use ( $no_sum_list) {
            $arr=&$item["data"];
            foreach ($add_item as $k => $v) {
                if (!is_int($k) && $k!="origin" &&
                    ($self_flag|| !in_array(  $k ,$no_sum_list ) )
                ) {
                    if (!isset($arr[$k])) {
                        $arr[$k]=0;
                    }
                    $arr[$k]+=$v;
                }
            }

        };
        /*
          [
          "baidu pc " => ["data" => $item
          sub_list=> [
          "xx" ＝ ["data" => $item
          sub_list= [...]
          ]

          ]
          ]
          ]

        */

        $all_item=["origin"=>"全部"];

        $check_init_map_item($data_map,"","");
        foreach ($old_list as &$item) {
            $value=trim($item["origin"]);
            if (!isset($value_map[$value])) {
                $value_map[$value]=[
                    "key1"=>"未定义",
                    "key2"=>"未定义",
                    "key3"=>"未定义",
                    "key4"=>$value,
                    "value"=>$value,
                ];
            }

            $conf=$value_map[$value];

            $key1=$conf["key1"];
            $key2=$conf["key2"];
            $key3=$conf["key3"];
            $key4=$conf["key4"];
            $key0_map=&$data_map[""];
            $add_data($key0_map, $item );

            $check_init_map_item($key0_map["sub_list"] , $key1,"key1" );
            $key1_map=&$key0_map["sub_list"][$key1];
            $add_data($key1_map, $item );

            $check_init_map_item($key1_map["sub_list"] , $key2 ,"key2");
            $key2_map=&$key1_map["sub_list"][$key2];
            $add_data($key2_map, $item );

            $check_init_map_item($key2_map["sub_list"] , $key3 ,"key3");
            $key3_map=&$key2_map["sub_list"][$key3];
            $add_data($key3_map, $item );

            $check_init_map_item($key3_map["sub_list"] , $key4,"key4",$value);
            $key4_map=&$key3_map["sub_list"][$key4];
            $add_data($key4_map, $item, true);

        }
        $list=[];
        //array_unshift($ret_info["list"],$all_item);
        foreach ($data_map as $key0 => $item0) {
            $data=$item0["data"];
            $data["key1"]="全部";
            $data["key2"]="";
            $data["key3"]="";
            $data["key4"]="";
            $data["key1_class"]="";
            $data["key2_class"]="";
            $data["key3_class"]="";
            $data["key4_class"]="";
            $data["level"]="l-0";

            $list[]=$data;
            foreach ($item0["sub_list"] as $key1 => $item1) {
                $data=$item1["data"];
                $data["key1"]=$key1;
                $data["key2"]="";
                $data["key3"]="";
                $data["key4"]="";
                $data["key1_class"]=$item1["key_class"];
                $data["key2_class"]="";
                $data["key3_class"]="";
                $data["key4_class"]="";
                $data["level"]="l-1";

                $list[]=$data;

                foreach ($item1["sub_list"] as $key2 => $item2) {
                    $data=$item2["data"];
                    $data["key1"]=$key1;
                    $data["key2"]=$key2;
                    $data["key3"]="";
                    $data["key4"]="";
                    $data["key1_class"]=$item1["key_class"];
                    $data["key2_class"]=$item2["key_class"];
                    $data["key3_class"]="";
                    $data["key4_class"]="";
                    $data["level"]="l-2";

                    $list[]=$data;
                    foreach ($item2["sub_list"] as $key3 => $item3) {
                        $data=$item3["data"];
                        $data["key1"]=$key1;
                        $data["key2"]=$key2;
                        $data["key3"]=$key3;
                        $data["key4"]="";
                        $data["key1_class"]=$item1["key_class"];
                        $data["key2_class"]=$item2["key_class"];
                        $data["key3_class"]=$item3["key_class"];
                        $data["key4_class"]="";
                        $data["level"]="l-3";

                        $list[]=$data;
                        foreach ($item3["sub_list"] as $key4 => $item4) {
                            $data=$item4["data"];
                            $data["key1"]=$key1;
                            $data["key2"]=$key2;
                            $data["key3"]=$key3;
                            $data["key4"]=$key4;
                            $data["value"] = $item4["value"];
                            $data["key1_class"]=$item1["key_class"];
                            $data["key2_class"]=$item2["key_class"];
                            $data["key3_class"]=$item3["key_class"];
                            $data["key4_class"]=$item4["key_class"];
                            $k4_v=$item4["value"];
                            if ($k4_v != $key4) {
                                $data["key4"]=$key4."/". $k4_v ;
                            }
                            $data["old_key4"]=$key4;
                            $data["level"]="l-4";
                            $list[]=$data;
                        }

                    }

                }


            }
        }

        foreach($list as &$item){
            if($item["level"]=="l-4" && $item["key1"]!="未定义"){
                $item["create_time"] = $value_map[$item['value']]["create_time"];
                if(!empty($item["create_time"])){
                    $item["create_time"] = date('Y-m-d',$item["create_time"]);
                }else{
                    $item["create_time"] = "";
                }
            }else{
                $item["create_time"] = "";
            }
        }
        return $list;
    }

    public function origin_count_yxb_simple () {
        $this->set_in_value("origin_ex","自有渠道,优学帮,,,");
        return $this->origin_count_simple_has_intention();
    }

    public function origin_count_simple_has_intention(){
        $origin            = trim($this->get_in_str_val("origin",""));
        $origin_ex         = $this->get_in_str_val('origin_ex', "");
        $seller_groupid_ex = $this->get_in_str_val('seller_groupid_ex', "");
        $adminid_list      = $this->t_admin_main_group_name->get_adminid_list_new($seller_groupid_ex);
        $admin_revisiterid = $this->get_in_int_val("admin_revisiterid", -1);
        $groupid           = $this->get_in_int_val("groupid",-1);
        $tmk_adminid       = $this->get_in_int_val("tmk_adminid", -1);

        $check_field_id    = $this->get_in_int_val("check_field_id",1);
        $check_field_config=[
            1=> ["渠道","origin", "" ],
            2=> ["年级","grade", E\Egrade::class ],
            3=> ["科目","subject", E\Esubject::class ],
            4=> ["tmk人员","tmk_adminid", "" ],
            5=> ["销售人员","admin_revisiterid", "" ],
            6=> ["渠道等级","origin_level",   E\Eorigin_level::class  ],
        ];

        $data_map=[];
        $check_item=$check_field_config[$check_field_id];
        $field_name       = $check_item[1];
        $field_class_name = $check_item[2];

        list($start_time,$end_time ,$opt_date_str)=$this->get_in_date_range_month( date("Y-m-01"), 0, [
            0 => array( "add_time", "资源进来时间"),
            1 => array("tmk_assign_time","微信运营时间"),
        ]);
        //date_type_config=undefined&date_type=0&opt_date_type=3&start_time=2017-09-01&end_time=2017-09-30
        $this->t_seller_student_origin->switch_tongji_database();

        $ret_info = $this->t_seller_student_origin->get_origin_tongji_info($field_name,$opt_date_str ,$start_time,$end_time,$origin,$origin_ex,"",$adminid_list, $tmk_adminid);

        //订单占比
        $order_area_map    = [];
        $order_subject_map = [];
        $order_grade_map   = [];
        $order_data = $this->t_order_info->tongji_seller_order_info($origin, $field_name,$start_time,$end_time,$adminid_list,$tmk_adminid,$origin_ex,$opt_date_str);

        foreach ($order_data as $a_item) {
            $subject   = $a_item["subject"];
            $grade     = $a_item["grade"];
            $area_name = substr($a_item["phone_location"], 0, -6);
            @$order_subject_map[$subject] ++;
            @$order_grade_map[$grade] ++;

            if (strlen($area_name)>5) {
                @$order_area_map[$area_name] ++;
            } else {
                @$order_area_map[""] ++;
            }

        }
        //试听占比
        $test_area_map    = [];
        $test_subject_map = [];
        $test_grade_map   = [];
        $test_data=$this->t_test_lesson_subject_require->tongji_test_lesson_origin_info( $origin, $field_name,$start_time,$end_time,$adminid_list,$tmk_adminid, $origin_ex,'','','',$opt_date_str);
        foreach ($test_data as $a_item) {
            $subject   = $a_item["subject"];
            $grade     = $a_item["grade"];
            $area_name = substr($a_item["phone_location"], 0, -6);
            @$test_subject_map[$subject] ++;
            @$test_grade_map[$grade] ++;

            if (strlen($area_name)>5) {
                @$test_area_map[$area_name] ++;
            } else {
                @$test_area_map[""] ++;
            }

        }


        ///  测试区
        $data_map=&$ret_info["list"];
        //试听信息
        $this->t_test_lesson_subject_require->switch_tongji_database();
        $test_lesson_list=$this->t_test_lesson_subject_require->tongji_test_lesson_origin( $origin, $field_name,$start_time,$end_time,$adminid_list,$tmk_adminid, $origin_ex );
        foreach ($test_lesson_list as  $test_item ) {
            $check_value=$test_item["check_value"];
            \App\Helper\Utils:: array_item_init_if_nofind( $data_map, $check_value,["check_value" => $check_value] );
            $data_map[$check_value]["test_lesson_count"] = $test_item["test_lesson_count"];
            $data_map[$check_value]["distinct_test_count"] = $test_item["distinct_test_count"];
            $data_map[$check_value]["succ_test_lesson_count"] = $test_item["succ_test_lesson_count"];
        }
        //去掉重复userid
        $distinct_test_lesson_list=$this->t_test_lesson_subject_require->tongji_test_lesson_origin( $origin, $field_name,$start_time,$end_time,$adminid_list,$tmk_adminid, $origin_ex , 1);

        foreach ($distinct_test_lesson_list as  $test_item ) {
            $check_value=$test_item["check_value"];
            $data_map[$check_value]["distinct_succ_count"] = $test_item["distinct_succ_count"];
        }


        $require_list=$this->t_test_lesson_subject_require->tongji_require_count_origin( $field_name,$start_time,$end_time,$adminid_list,$tmk_adminid,$origin_ex, $origin);
        foreach ($require_list as  $item ) {
            $check_value=$item["check_value"];
            \App\Helper\Utils:: array_item_init_if_nofind( $data_map, $check_value,["check_value" => $check_value] );
            $data_map[$check_value]["require_count"] = $item["require_count"];
        }


        $this->t_order_info->switch_tongji_database();
        //合同
        // dd($field_name,$origin_ex);
        $order_list= $this->t_order_info->tongji_seller_order_count_origin( $field_name,$start_time,$end_time,$adminid_list,$tmk_adminid,$origin_ex,$opt_date_str, $origin);
        foreach ($order_list as  $order_item ) {
            $check_value=$order_item["check_value"];
            \App\Helper\Utils:: array_item_init_if_nofind( $data_map, $check_value,["check_value" => $check_value ] );

            $data_map[$check_value]["order_count"] = $order_item["order_count"];
            $data_map[$check_value]["user_count"] = $order_item["user_count"];
            $data_map[$check_value]["order_all_money"] = $order_item["order_all_money"];
        }

        foreach ($data_map as &$item ) {
            if($field_class_name ) {
                $item["title"]= $field_class_name::get_desc($item["check_value"]);
            }else{
                if ($field_name=="tmk_adminid" || $field_name=="admin_revisiterid"  ) {
                    $item["title"]= $this->cache_get_account_nick( $item["check_value"] );
                }else{
                    $item["title"]= $item["check_value"];
                }
            }

            if ($field_name=="origin") {
                $item["origin"]= $item["title"];
            }
        }

        if ($field_name=="origin") {
            $tongji_ss = new \App\Http\Controllers\tongji_ss();
            $ret_info["list"]= $tongji_ss->gen_origin_data_level5($ret_info["list"],["avg_first_time"], $origin_ex);
        }


        $data_list = $this->t_seller_student_origin->get_origin_detail_info($opt_date_str,$start_time,$end_time,$origin,$origin_ex,"",$adminid_list,$tmk_adminid);
        $subject_map      = [];
        $grade_map        = [];
        $has_pad_map      = [];
        $area_map         = [];
        $origin_level_map = [];
        $all_count        = count($data_list);

        $data_list = $this->t_seller_student_origin->get_origin_detail_info($opt_date_str,$start_time,$end_time,$origin,$origin_ex,"",$adminid_list,$tmk_adminid);

        foreach ($data_list as $a_item) {
            $subject      = $a_item["subject"];
            $grade        = $a_item["grade"];
            $has_pad      = $a_item["has_pad"];
            $origin_level = $a_item["origin_level"];
            $area_name    = substr($a_item["phone_location"], 0, -6);
            @$subject_map[$subject] ++;
            @$grade_map[$grade] ++;
            @$has_pad_map[$has_pad] ++;
            @$origin_level_map[$origin_level] ++;
            if (strlen($area_name)>5) {
                @$area_map[$area_name] ++;
            } else {
                @$area_map[""] ++;
            }

        }

        $group_list = $this->t_admin_group_name->get_group_list(2);

        $origin_type = 0;
        if($origin_ex == '优学帮,,,'){
            $origin_type = 1;

            list($all_count,$assigned_count,$tmk_assigned_count,$tq_no_call_count,$tq_called_count,$tq_call_fail_count,
                 $tq_call_succ_valid_count,$tq_call_succ_invalid_count,$tq_call_fail_invalid_count,$have_intention_a_count,
                 $have_intention_b_count,$have_intention_c_count,$require_count,$test_lesson_count,$succ_test_lesson_count,
                 $order_count,$user_count,$order_all_money) = [[],[],[],[],[],[],[],[],[],[],[],[],[],[],[],[],[],0];
            // $ret  = $this->t_agent->get_agent_info_new($start_time,$end_time);
            $ret  = $this->t_agent->get_agent_info_new(null);
            $userid_arr = [];

            $ret_new = [];
            $ret_info_new = [];
            $id_arr = array_unique(array_column($ret,'id'));
            foreach($ret as &$item){
                if($item['type'] == 1){
                    $userid_arr[] = $item['userid'];
                }
                $item['agent_type'] = $item['type'];
                $item['a_create_time'] = $item['create_time'];
                $item['a_lesson_start'] = $item['lesson_start'];
                $item['create_time'] = date('Y-m-d H:i:s',$item['create_time']);
                if($item['lesson_start']){
                    $item['lesson_start'] = date('Y-m-d H:i:s',$item['lesson_start']);
                }else{
                    $item['lesson_start'] = '';
                }

                $id = $item['id'];
                $id_arr_new = array_unique(array_column($ret_new,'id'));
                if(in_array($id,$id_arr_new)){
                }else{
                    if($item['lesson_start']){
                        if($item['lesson_start']>$item['create_time']){
                            $ret_new[] = $item;
                        }
                    }else{
                        $ret_new[] = $item;
                    }
                }
                //例子总数
                $id_arr_new_two = array_unique(array_column($ret_info_new,'id'));
                if(in_array($id,$id_arr_new_two)){
                }else{
                    if($item['a_create_time']>=$start_time && $item['a_create_time']<$end_time){
                        $ret_info_new[] = $item;
                    }
                }
            }
            if(count($userid_arr)>0){
                foreach($ret_new as &$item){
                    if($item['a_create_time']>=$start_time && $item['a_create_time']<$end_time){
                        //已分配销售
                        if($item['admin_revisiterid']>0){
                            $assigned_count[] = $item;
                        }
                        //TMK有效
                        if($item['tmk_student_status'] == 3){
                            $tmk_assigned_count[] = $item;
                        }
                        //未拨打
                        if($item['global_tq_called_flag'] == 0){
                            $tq_no_call_count[] = $item;
                        }
                        //已拨打
                        if($item['global_tq_called_flag'] != 0){
                            $tq_called_count[] = $item;
                        }
                        //未接通
                        if($item['global_tq_called_flag'] == 1){
                            $tq_call_fail_count[] = $item;
                        }
                        //已拨通-有效
                        if($item['global_tq_called_flag'] == 2 && $item['sys_invaild_flag'] == 0){
                            $tq_call_succ_valid_count[] = $item;
                        }
                        //已拨通-无效
                        if($item['global_tq_called_flag'] == 2 && $item['sys_invaild_flag'] == 1){
                            $tq_call_succ_invalid_count[] = $item;
                        }
                        //未拨通-无效
                        if($item['global_tq_called_flag'] == 1 && $item['sys_invaild_flag'] == 1){
                            $tq_call_fail_invalid_count[] = $item;
                        }
                        //有效意向(A)
                        if($item['global_tq_called_flag'] == 2 && $item['seller_student_status'] == 100){
                            $have_intention_a_count[] = $item;
                        }
                        //有效意向(B)
                        if($item['global_tq_called_flag'] == 2 && $item['seller_student_status'] == 101){
                            $have_intention_b_count[] = $item;
                        }
                        //有效意向(C)
                        if($item['global_tq_called_flag'] == 2 && $item['seller_student_status'] == 102){
                            $have_intention_c_count[] = $item;
                        }
                    }
                    if($item['a_lesson_start']>=$start_time && $item['a_lesson_start']<$end_time){
                        //预约数&&上课数
                        // if($item['accept_flag'] == 1 && $item['is_test_user'] == 0 && $item['require_admin_type'] == 2 ){
                        if($item['test_lessonid']){
                            $require_count[] = $item;
                            $test_lesson_count[] = $item;
                        }
                        //试听成功数
                        if($item['lesson_user_online_status'] == 1 ){
                            $succ_test_lesson_count[] = $item;
                        }
                    }
                }
            }
            $order_info = $this->t_agent_order->get_all_list($start_time,$end_time);
            foreach($order_info as $item){
                $orderid = $item['orderid'];
                $order_count[] = $item;
                $user_count[] = $item;
                $order_all_money += $item['price'];
            }
            foreach([0,1,2,3,4] as $item){
                $ret_info['list'][$item]['all_count'] = count($ret_info_new);
                $ret_info['list'][$item]['assigned_count'] = count($assigned_count);
                $ret_info['list'][$item]['tmk_assigned_count'] = count($tmk_assigned_count);
                $ret_info['list'][$item]['tq_no_call_count'] = count($tq_no_call_count);
                $ret_info['list'][$item]['tq_called_count'] = count($tq_called_count);
                $ret_info['list'][$item]['tq_call_fail_count'] = count($tq_call_fail_count);
                $ret_info['list'][$item]['tq_call_succ_valid_count'] = count($tq_call_succ_valid_count);
                $ret_info['list'][$item]['tq_call_succ_invalid_count'] = count($tq_call_succ_invalid_count);
                $ret_info['list'][$item]['tq_call_fail_invalid_count'] = count($tq_call_fail_invalid_count);
                $ret_info['list'][$item]['have_intention_a_count'] = count($have_intention_a_count);
                $ret_info['list'][$item]['have_intention_b_count'] = count($have_intention_b_count);
                $ret_info['list'][$item]['have_intention_c_count'] = count($have_intention_c_count);
                $ret_info['list'][$item]['require_count'] = count($require_count);
                $ret_info['list'][$item]['test_lesson_count'] = count($test_lesson_count);
                $ret_info['list'][$item]['succ_test_lesson_count'] = count($succ_test_lesson_count);
                $ret_info['list'][$item]['order_count'] = count($order_count);
                $ret_info['list'][$item]['user_count'] = count($user_count);
                $ret_info['list'][$item]['order_all_money'] = $order_all_money/100;
            }
        }
        return $this->pageView(__METHOD__,$ret_info,[
            "subject_map"       => $subject_map,
            "grade_map"         => $grade_map,
            "has_pad_map"       => $has_pad_map,
            "origin_level_map"  => $origin_level_map,
            "area_map"          => $area_map,
            "group_list"        => $group_list,
            "field_name"        => $field_name,
            "origin_type"       => $origin_type,
            "order_area_map"    => $order_area_map,
            "order_subject_map" => $order_subject_map,
            "order_grade_map"   => $order_grade_map,
            "test_area_map"     => $test_area_map,
            "test_subject_map"  => $test_subject_map,
            "test_grade_map"    => $test_grade_map,
            "start_time"        => $start_time,
            "end_time"          => $end_time,
        ]);
    }

    public function seller_personal_money(){
        list($start_time,$end_time)=$this->get_in_date_range(0,0,0,[],3);
        $common_new = new \App\Http\Controllers\common_ex;
        $month = $common_new->get_seller_month($start_time,$end_time)[0];
        $group_adminid_list = $common_new->get_month_group_adminid_list($month,$this->get_account_id());

        list($date_list,$ret,$ret_info,$adminid,$money,$money1,$money2,$money3,$money4,$money5,$money6,$num,$account) = [[['month'=>0],['month'=>0],['month'=>0],['month'=>0],['month'=>0],['month'=>0]],[],[],0,0,0,0,0,0,0,0,1,''];
        $account_role = E\Eaccount_role::V_2;
        $user_info = trim($this->get_in_str_val('user_info',''));
        $order_user_list = $this->t_order_info->get_admin_list_new(strtotime("-5 months", $start_time),$end_time,$account_role,$user_info,$group_adminid_list);
        $adminid_list = array_unique(array_column($order_user_list,'uid'));
        foreach($adminid_list as $item){
            foreach($order_user_list as $info){
                if($info['uid'] == $item){
                    $ret[$item][] = $info;
                }
            }
        }
        $ret_new = [];
        foreach($ret as $key=>$item){
            foreach($item as $info){
                $adminid    = $info['uid'];
                $account    = $info['sys_operator'];
                $money      = $info['price'];
                $order_time = $info['order_time'];
                if($order_time>=strtotime("-5 months", $start_time) && $order_time<strtotime("-4 months", $start_time)){
                    $money1 += $money;
                }elseif($order_time>=strtotime("-4 months", $start_time) && $order_time<strtotime("-3 months", $start_time)){
                    $money2 += $money;
                }elseif($order_time>=strtotime("-3 months", $start_time) && $order_time<strtotime("-2 months", $start_time)){
                    $money3 += $money;
                }elseif($order_time>=strtotime("-2 months", $start_time) && $order_time<strtotime("-1 months", $start_time)){
                    $money4 += $money;
                }elseif($order_time>=strtotime("-1 months", $start_time) && $order_time<$start_time){
                    $money5 += $money;
                }elseif($order_time>=$start_time && $order_time<strtotime("1 months", $start_time)){
                    $money6 += $money;
                }
            }
            $ret_info[$key]['adminid'] = $adminid;
            $ret_info[$key]['account'] = $account;
            $ret_info[$key]['money1']  = $money1/100;
            $ret_info[$key]['money2']  = $money2/100;
            $ret_info[$key]['money3']  = $money3/100;
            $ret_info[$key]['money4']  = $money4/100;
            $ret_info[$key]['money5']  = $money5/100;
            $ret_info[$key]['money6']  = $money6/100;
            list($money1,$money2,$money3,$money4,$money5,$money6) = [0,0,0,0,0,0];
        }
        foreach($date_list as $key=>&$item){
            $item['month'] = date("m", strtotime("-".(5-$key)." months", $start_time));
        }
        $money6_arr = array_column($ret_info,'money6');
        array_multisort($money6_arr,SORT_DESC,$ret_info);
        foreach($ret_info as $key=>$item){
            $ret_info[$key]['id'] = $num++;
        }

        $res = [];
        foreach($ret_info as $item){
            $adminid = $item['adminid'];
            $res[$adminid]['adminid'] = $adminid;
            $res[$adminid]['id']      = $item['id'];
            $res[$adminid]['account'] = $item['account'];
            $res[$adminid]['money1']  = $item['money1'];
            $res[$adminid]['money2']  = $item['money2'];
            $res[$adminid]['money3']  = $item['money3'];
            $res[$adminid]['money4']  = $item['money4'];
            $res[$adminid]['money5']  = $item['money5'];
            $res[$adminid]['money6']  = $item['money6'];
        }
        list($member_new,$member_num_new,$member,$member_num,$become_member_num_l1,$leave_member_num_l1,$become_member_num_l2,$leave_member_num_l2,$become_member_num_l3,$leave_member_num_l3) = [[],[],[],[],0,0,0,0,0,0];
        // $ret_info = \App\Helper\Common::gen_admin_member_data($res,[],0,strtotime(date("Y-m-01",$start_time )));
        $ret_info=\App\Helper\Common::gen_admin_member_data_new($res,[],0,strtotime(date("Y-m-01",$start_time)),$group_adminid_list);
        foreach($ret_info as $key=>&$item){
            $item["become_member_time"] = isset($item["become_member_time"])?(isset($item["create_time"])?($item["become_member_time"]>$item["create_time"]?$item["become_member_time"]:$item["create_time"]):0):0;
            $item["leave_member_time"] = isset($item["leave_member_time"])?$item["leave_member_time"]:0;
            $item["del_flag"] = isset($item["del_flag"])?$item["del_flag"]:0;
            E\Emain_type::set_item_value_str($item);
            E\Eseller_level::set_item_value_str($item);
            if($item['level'] == "l-5" ){
                \App\Helper\Utils::unixtime2date_for_item($item,"become_member_time",'','Y-m-d');
                \App\Helper\Utils::unixtime2date_for_item($item,"leave_member_time",'','Y-m-d');
                $item["del_flag_str"] = \App\Helper\Common::get_boolean_color_str($item["del_flag"]);
                $item["del_flag"]?$leave_member_num_l3++:$become_member_num_l3++;
                $item["del_flag"]?$leave_member_num_l2++:$become_member_num_l2++;
                $item['become_member_num'] = $become_member_num_l3;
                $item['leave_member_num'] = $leave_member_num_l3;
            }else{
                $item["become_member_time"] = '';
                $item["leave_member_time"] = '';
                $item["del_flag_str"] = '';
                $item['become_member_num'] = '';
                $item['leave_member_num'] = '';
            }

            if($item['level'] == 'l-4'){
                $member[] = [
                    "up_group_name"     => $item['up_group_name'],
                    "group_name"        => $item['group_name'],
                ];
                $member_num[] = [
                    'become_member_num' => $become_member_num_l3,
                    'leave_member_num'  => $leave_member_num_l3,
                ];

                $become_member_num_l3 = 0;
                $leave_member_num_l3 = 0;
            }

            if($item['level'] == 'l-3'){
                $member_new[] = [
                    "up_group_name" => $item['up_group_name'],
                    "group_name"    => $item['group_name'],
                ];
                $member_num_new[] = [
                    'become_member_num' => $become_member_num_l2,
                    'leave_member_num'  => $leave_member_num_l2,
                ];

                $become_member_num_l2 = 0;
                $leave_member_num_l2 = 0;
            }
            if($item['main_type_str'] == '助教'){
                unset($ret_info[$key]);
            }
            if($item['main_type_str'] == '未定义'){
                unset($ret_info[$key]);
            }
        }
        foreach($member as $key=>&$item){
            foreach($member_num as $k=>$info){
                if(($key+1) == $k){
                    $item['become_member_num'] = $info['become_member_num'];
                    $item['leave_member_num'] = $info['leave_member_num'];
                }
            }
            $item['become_member_num'] = isset($item['become_member_num'])?$item['become_member_num']:'';
            $item['leave_member_num'] = isset($item['leave_member_num'])?$item['leave_member_num']:'';
        }
        foreach($member_new as $key=>&$item){
            foreach($member_num_new as $k=>$info){
                if(($key+1) == $k){
                    $item['become_member_num'] = $info['become_member_num'];
                    $item['leave_member_num'] = $info['leave_member_num'];
                }
            }
            $item['become_member_num'] = isset($item['become_member_num'])?$item['become_member_num']:'';
            $item['leave_member_num'] = isset($item['leave_member_num'])?$item['leave_member_num']:'';
        }
        foreach($ret_info as &$item){
            if(($item['main_type_str'] == '未定义') or ($item['main_type_str'] == '助教')){
                unset($item);
            }else{
                if($item['level'] == 'l-3'){
                    foreach($member_new as $info){
                        if($item['up_group_name'] == $info['up_group_name']){
                            $item['become_member_num'] = $info['become_member_num'];
                            $item['leave_member_num'] = $info['leave_member_num'];
                        }
                    }
                }else{
                    if($item['level'] == 'l-4'){
                        foreach($member as $info){
                            if($item['group_name'] == $info['group_name']){
                                $item['become_member_num'] = $info['become_member_num'];
                                $item['leave_member_num'] = $info['leave_member_num'];
                            }
                        }
                    }else{
                        $item['become_member_num'] = '';
                        $item['leave_member_num'] = '';
                    }
                }
            }
        }

        return $this->pageView(__METHOD__,\App\Helper\Utils::list_to_page_info($ret_info),['date_list'=>$date_list]);
    }

    public function seller_personal_rank() {

        list($start_time,$end_time)= $this->get_in_date_range_month(date("Y-m-01"));

        $ret_info= $this->t_order_info->get_1v1_order_seller_list($start_time,$end_time,-1, '');

        foreach ($ret_info["list"] as $key=> &$item) {
            $item["index"]=$key+1;
            $item["all_price"] =sprintf("%.2f", $item["all_price"]  );

        }
        return $this->pageView(__METHOD__, $ret_info);
    }

    /**
     * 试听课学生和老师教材版本的匹配度
     */
    public function match_lesson_textbook(){
        list($start_time,$end_time) = $this->get_in_date_range(0,0,0,null,3);

        $region_version = array_flip(E\Eregion_version::$desc_map);

        // $list  = $this->t_lesson_info_b3->get_textbook_match_lesson_list($start_time,$end_time);
        $list      = $this->t_lesson_info_b3->get_textbook_match_lesson_and_order_list($start_time,$end_time);
        $all_num   = 0;
        $match_num = 0;
        $stu_arr   = [];
        $no_match_arr  = [];
        $match_arr = [];
        $no_match_arr_all = [];
        $match_arr_all = [];
        foreach($list as $val){
            $all_num++;
            if($val['textbook']!="" && isset($region_version[$val['textbook']]) ){
                $stu_textbook = $region_version[$val['textbook']];
            }else{
                $stu_textbook = $val['editionid'];
            }

            $tea_textbook = explode(",",$val['teacher_textbook']);
            if(in_array($stu_textbook,$tea_textbook)){
                $match_num++;
                if(!in_array($val['succ_userid'],$match_arr)){
                    array_push($match_arr,$val['succ_userid']);
                }
                if(!in_array($val['stu_userid'],$match_arr_all)){
                    array_push($match_arr_all,$val['succ_userid']);
                }

            } else {
                if(!in_array($val['succ_userid'],$no_match_arr)){
                    array_push($no_match_arr,$val['succ_userid']);
                }
                if(!in_array($val['stu_userid'],$no_match_arr_all)){
                    array_push($no_match_arr_all,$val['succ_userid']);
                }

            }

            if(!in_array($val['stu_userid'],$stu_arr)){
                array_push($stu_arr,$val['stu_userid']);
            }

        }

        $match_rate = $all_num>0?($match_num/$all_num):0;
        //除重数据
        $all_stu = count($stu_arr);
        $no_match_stu = count($no_match_arr)-1;
        $match_stu = count($match_arr)-1;

        $no_match_all_stu = count($no_match_arr_all);
        $match_all_stu = count($match_arr_all);


        $no_match_succ_rate  = $all_stu>0 ? $no_match_stu/$no_match_all_stu : 0;
        $match_succ_rate  = $all_stu>0 ? $match_stu/$match_all_stu : 0;
        // echo "总数:".$all_num." 匹配正确数: ".$match_num." 匹配率:".(round($match_per*100,2))."%";

        return $this->pageView(__METHOD__,[],[
            "all_num"    => $all_num,
            "match_num"  => $match_num,
            "match_rate" => round($match_rate*100,2)."%",
            "no_match_succ_rate" => round($no_match_succ_rate*100,2)."%",
            "match_succ_rate" => round($match_succ_rate*100,2)."%",
            "all_stu"    => $all_stu,
            "no_match_stu"    => $no_match_stu,
            "match_stu"    => $match_stu,
        ]);
    }


}
