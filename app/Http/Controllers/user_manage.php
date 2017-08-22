<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use \App\Enums as E;

class user_manage extends Controller
{
    use CacheNick;
    use TeaPower;
    public function all_users(){
        global $g_request;
        $g_request->offsetSet("all_flag",1);
        return $this->index();
    }

    public function ass_count()
    {
        $test_user       = $this->get_in_int_val('test_user',"-1");
        $originid        = $this->get_in_int_val('originid',"-1");
        $grade           = $this->get_in_grade();
        $user_name       = trim($this->get_in_str_val('user_name',''));
        $phone           = trim($this->get_in_str_val('phone',''));
        $teacherid       = $this->get_in_int_val("teacherid",-1);
        $assistantid     = $this->get_in_int_val("assistantid",-1);
        $page_num        = $this->get_in_page_num();
        $status          = -1;
        $userid          = $this->get_in_userid(-1);

        if (is_numeric($user_name)) {
            $userid=$user_name;
            $user_name="";
        }

        $ret_info = $this->t_student_info->get_student_list_count($userid, $grade, $status, $user_name, $phone, $teacherid,
                                                                  $assistantid, $test_user, $originid, $page_num);
        foreach($ret_info['list'] as &$item) {
            $item['originid']   = E\Estu_origin::get_desc($item['originid']);
            $userid             = $item["userid"];
            $arr                = $this->t_revisit_info->get_last_revisit( $userid);
            $arrf               = $this->t_revisit_info->get_first_revisit( $userid);
            $max_revisit_time   = $arr["revisit_time"];
            $first_revisit_time = $arrf["revisit_time"];
            $item['max_revisit_time']    = \App\Helper\Utils::unixtime2date($max_revisit_time );
            $item['first_revisit_time']  = \App\Helper\Utils::unixtime2date($first_revisit_time );
            $item["assistant_nick"]      = $this->cache_get_assistant_nick ($item["assistantid"] );
            $item['first_operator_note'] = $arrf["operator_note"];
            $item['operator_note']       = $arr["operator_note"];
            $item['sys_operator']        = $arr["sys_operator"];
        }
        return $this->Pageview(__METHOD__,$ret_info);
    }

    public function parent_archive()
    {
        $parentid    = $this->get_in_int_val('parentid',"-1");
        $gender      = $this->get_in_int_val('gender',-1);
        $nick        = trim($this->get_in_str_val('nick',''));
        $phone       = trim($this->get_in_str_val('phone',''));
        $time        = strtotime($this->get_in_str_val("last_modified_time"));
        $assistantid = $this->get_in_int_val("assistantid",-1);
        $page_num    = $this->get_in_page_num();

        $ret_info = $this->t_parent_info->get_parent_info($parentid,$phone,$nick,$page_num);
        foreach($ret_info['list'] as &$item){
            $item["time"]       = $item["last_modified_time"];
            $item["gender"]     = E\Egender::get_desc($item["gender"]);
            $item["has_login"]  = $item["has_login"]==0?"未登录":"曾登录";
        }

        return $this->Pageview(__METHOD__,$ret_info);
    }

    public function index()
    {
        $grade          = $this->get_in_grade();
        $all_flag       = $this->get_in_int_val('all_flag',0);
        $test_user      = $this->get_in_int_val('test_user',-1);
        $originid       = $this->get_in_int_val('originid',-1);
        $user_name      = trim($this->get_in_str_val('user_name',''));
        $phone          = trim($this->get_in_str_val('phone',''));
        $assistantid    = $this->get_in_int_val("assistantid",-1);
        $seller_adminid = $this->get_in_int_val("seller_adminid",-1);
        $order_type     = $this->get_in_int_val("order_type",-1);
        $student_type   = $this->get_in_int_val("student_type",-1);
        $page_num       = $this->get_in_page_num();
        $status         = -1;
        $userid         = $this->get_in_userid(-1);

        $teacherid = -1;
        if (is_numeric($user_name) && $user_name< 10000000 ) {
            $userid    = $user_name;
            $user_name = "";
        }
        if ($assistantid >0 && $order_type == -1) {
            $order_type = 3;
        }

        $ret_info = $this->t_student_info->get_student_list_search(
            $page_num,$all_flag, $userid, $grade, $status,
            $user_name, $phone, $teacherid,
            $assistantid, $test_user, $originid,
            $seller_adminid,$order_type,$student_type);
        foreach($ret_info['list'] as &$item) {
            $item['originid']          = E\Estu_origin::get_desc($item['originid']);
            $item['is_test_user_str']      = E\Etest_user::get_desc($item['is_test_user']);
            $item['user_agent_simple'] = get_machine_info_from_user_agent($item["user_agent"] );
            $item['last_login_ip']     = long2ip( $item['last_login_ip'] );
            \App\Helper\Utils::unixtime2date_for_item($item,"last_lesson_time");
            \App\Helper\Utils::unixtime2date_for_item($item,"last_login_time");
            $item['lesson_count_all']  = $item['lesson_count_all']/100;
            $item['lesson_count_left'] = $item['lesson_count_left']/100;
            $item["seller_admin_nick"] = $this->cache_get_account_nick($item["seller_adminid"] );
            $item["assistant_nick"]    = $this->cache_get_assistant_nick ($item["assistantid"] );
            $item["origin_ass_nick"]   = $this->cache_get_account_nick($item["origin_assistantid"] );
            $item["ss_assign_time"]    = $item["ass_assign_time"]==0?'未分配':date('Y-m-d H:i:s',$item["ass_assign_time"]);
            $item["cache_nick"]        = $this->cache_get_student_nick($item["userid"]) ;
            \App\Helper\Utils::unixtime2date_for_item($item,"reg_time");
        }
        return $this->Pageview(__METHOD__,$ret_info);
    }

    public function get_user_manage_list_for_js(){
        $page_num = $this->get_in_page_num();
        $ret_info = $this->t_student_info->get_student_list($page_num);
        $ret_info["page_info"] = $this->get_page_info_for_js( $ret_info["page_info"]   );
        return outputjson_success(array('data' => $ret_info));
    }

    public function ass_counts()
    {
        $start_date      = $this->get_in_str_val('start_date',date('Y-m-d', time(NULL)-3*86400 ));
        $end_date        = $this->get_in_str_val('end_date',date('Y-m-d', time(NULL)+86400 ));
        $test_user       = $this->get_in_int_val('test_user',"-1");
        $originid        = $this->get_in_int_val('originid',"-1");
        $grade           = $this->get_in_grade();
        $user_name       = trim($this->get_in_str_val('user_name',''));
        $phone           = trim($this->get_in_str_val('phone',''));
        $teacherid       = $this->get_in_int_val("teacherid",-1);
        $assistantid     = $this->get_in_int_val("assistantid",-1);
        $revisit_assistantid     = $this->get_in_int_val("revisit_assistantid",-1);
        $page_num        = $this->get_in_page_num();
        $status          = -1;
        $userid          = $this->get_in_userid(-1);
        $revisit_type    = $this->get_in_int_val('revisit_type',"-1");

        $start_date_s = strtotime($start_date);
        $end_date_s   = strtotime($end_date)+86400;

        if (is_numeric($user_name)) {
            $userid=$user_name;
            $user_name="";
        }

        $ret_info = $this->t_student_info->get_student_list_counts($userid, $grade, $status, $user_name, $phone, $teacherid,
                                                                   $assistantid, $test_user, $originid, $page_num,
                                                                   $start_date_s, $end_date_s, $revisit_type,$revisit_assistantid);
        foreach($ret_info['list'] as &$item) {
            E\Estu_origin::set_item_value_str($item,"originid");
            \App\Helper\Utils::unixtime2date_for_item($item,"revisit_time");
            $this->cache_set_item_assistant_nick($item);
            E\Erevisit_type::set_item_value_str($item);
            E\Egrade::set_item_value_str($item);

        }
        return $this->Pageview(__METHOD__,$ret_info);
    }

    public function ass_archive_ass() {
        $assistantid = $this->t_assistant_info->get_assistantid( $this->get_account());
        if ($assistantid<=0) {
            $assistantid=1; //no find
        }
        $adminid = $this->get_account_id();
        if($adminid==349){
            $assistantid = 240321;
        }
        $this->set_in_value("assistantid",$assistantid );
        return $this->ass_archive();
    }

    public function ass_archive()
    {
        $this->switch_tongji_database();
        $sum_field_list=[
            "userid",
            "nick",
            "location",
            "type",
            "parent_name",
            "assistant_nick",
            "parent_type",
            "phone",
            "grade",
            "lesson_count_all",
            "lesson_count_left",
            "lesson_count_done",
            "lesson_total",
            "praise"
        ];
        $order_field_arr=  array_merge(["ass_assign_time" ] ,$sum_field_list );

        list( $order_in_db_flag, $order_by_str, $order_field_name,$order_type )
            =$this->get_in_order_by_str($order_field_arr ,"ass_assign_time desc");

        $test_user    = $this->get_in_int_val('test_user',"-1");
        $originid     = $this->get_in_int_val('originid',"-1");
        $grade        = $this->get_in_el_grade();
        $user_name    = trim($this->get_in_str_val('user_name',''));
        $phone        = trim($this->get_in_str_val('phone',''));
        $teacherid    = $this->get_in_int_val("teacherid",-1);
        $student_type = $this->get_in_int_val("student_type",-1);
        $assistantid  = $this->get_in_int_val("assistantid",-1);
        $page_num     = $this->get_in_page_num();
        $status       = -1;
        $userid       = $this->get_in_userid(-1);
        $revisit_flag = $this->get_in_int_val('revisit_flag',-1);
        $warning_stu  = $this->get_in_int_val('warning_stu',-1);
        $now  = strtotime(date("Y-m-d",time()));
        $date = \App\Helper\Utils::get_week_range($now,1);
        $day  = $date["edate"];
        if (is_numeric($user_name)) {
            $userid=$user_name;
            $user_name="";
            if ($userid >1000000 ) {
                $phone=$userid;
                $userid=-1;
            }
        }

        $month_start = strtotime(date("Y-m-01",time()));
        $m = date("m",time());
        $y = date("Y",time());
        $d = date("d",time());
        $next_m = $m+1;
        if($next_m >12){
            $next_m="01";
            $y = $y+1;
        }else if($next_m<10){
            $next_m ="0".$next_m;
        }
        $month_end_str = $y."-".$next_m."-"."01";
        $month_end = strtotime(date($month_end_str));
        $cur_start = $month_start+15*86400;
        $cur_end =  $month_end;
        $last_start = $month_start;
        $last_end =  $month_start+15*86400;


        $cur_time_str  = date("m.d",$cur_start)."-".date("m.d",$cur_end-300);
        $last_time_str = date("m.d",$last_start)."-".date("m.d",$last_end-300);
        $ret = $this->t_student_info->get_student_sum_archive( $assistantid);
        if($d<=15){
            $sum_start = $last_start;
            $sum_end = $last_end;
        }else{
            $sum_start = $cur_start;
            $sum_end = $cur_end;
        }
        //  dd(date("Y-m-d",$sum_start));
        $sumweek = $this->t_student_info->get_student_sum_archive_new($assistantid,$sum_start);

        $ret_info = $this->t_student_info->get_student_list_archive( $userid, $grade, $status, $user_name, $phone, $teacherid,
                                                                     $assistantid, $test_user, $originid, $page_num, $student_type,
                                                                     $revisit_flag, $warning_stu,$sum_start);
        $userid_list=[];
        foreach($ret_info['list'] as $t_item) {
            $userid_list[]=$t_item["userid"];
        }

        $ret_revisit_info =[];
        if ( count( $userid_list)>0) {
            $ret_revisit_info_cur = $this->t_revisit_info->get_ass_revisit_info_new(-1,$cur_start,$cur_end,$userid_list);
            $ret_revisit_info_last = $this->t_revisit_info->get_ass_revisit_info_new(-1,$last_start,$last_end,$userid_list);
        }

        $now=time(NULL);
        foreach($ret_info['list'] as &$item) {
            $item['originid']          = E\Estu_origin::get_desc($item['originid']);
            $item['type_str']          = $item['type']  ;
            $item['type']              = E\Estudent_type::get_desc($item['type']);
            $item['is_test_user']      = E\Etest_user::get_desc($item['is_test_user']);
            $item['user_agent_simple'] = get_machine_info_from_user_agent($item["user_agent"] );
            $item['last_login_ip']     = long2ip( $item['last_login_ip'] );
            $item['last_login_time']   = unixtime2date( $item['last_login_time']);
            $item['ass_assign_time_str']   = unixtime2date( $item['ass_assign_time']);
            $item['lesson_count_all']  = $item['lesson_count_all']/100;
            $item['lesson_count_left'] = $item['lesson_count_left']/100;
            $item['lesson_count_done'] = $item['lesson_count_all']-$item['lesson_count_left'];
            $item['lesson_total'] = $item['lesson_total']/100;
            $item["assistant_nick"]    = $this->cache_get_assistant_nick ($item["assistantid"] );
            $ass_revisit_last_week_time = $item ["ass_revisit_last_week_time"];
            $ass_revisit_last_month_time = $item ["ass_revisit_last_month_time"];
            $item ["ass_revisit_week_flag"]= (($now - $ass_revisit_last_week_time) < 7*86400 )  ;
            $item ["ass_revisit_month_flag"]= (($now - $ass_revisit_last_month_time) < 28*86400 )  ;
            E\Eboolean::set_item_value_str($item, "ass_revisit_week_flag");
            E\Eboolean::set_item_value_str($item, "ass_revisit_month_flag");

            $item['cur'] = @$ret_revisit_info_cur[$item['userid']]['num'];
            if(isset($item['cur']) && $item['cur']>0){
                $item['cur'] = 1;
            }else{
                $item['cur'] = 0;

            }
            $item['last'] = @$ret_revisit_info_last[$item['userid']]['num'];
            if(isset($item['last']) && $item['last']>0){
                $item['last'] = 1;
            }else{
                $item['last'] = 0;

            }


            /* $item['week_second'] = @$ret_revisit_info[$item['userid']]['week_second'];
            if(isset($item['week_second']) && $item['week_second']>0){
                $item['week_second'] = 1;
            }else{
                $item['week_second'] = 0;

            }

            $item['week_third'] = @$ret_revisit_info[$item['userid']]['week_third'];
            if(isset($item['week_third']) && $item['week_third']>0){
                $item['week_third'] = 1;
            }else{
                $item['week_third'] = 0;
            }

            $item['week_fourth'] = @$ret_revisit_info[$item['userid']]['week_fourth'];
            if(isset($item['week_fourth']) && $item['week_fourth']>0){
                $item['week_fourth'] = 1;
            }else{
                $item['week_fourth'] = 0;
            }

            $item["week_now"] = $item["week_fourth"]+$item["week_third"];
            if($item["week_now"]>0) $item["week_now"]=1;
            $item["week_last"] = $item["week_second"]+$item["week_first"];
            if($item["week_last"]>0) $item["week_last"]=1;*/

            E\Eboolean::set_item_value_str($item, "cur");
            E\Eboolean::set_item_value_str($item, "last");
            if(empty($item["phone_location"])){
                $item["location"] = \App\Helper\Common::get_phone_location($item["phone"]);
            }else{
                $item["location"]= $item["phone_location"];
            }

            //$item["course_list_total"] = $this->t_course_order->get_list_total($item['userid'],-1,0);
            $ret_get_list_total = $this->t_course_order->get_list_total($item['userid'],-1,0);
            $arr = [];
            foreach ($ret_get_list_total as $key => $value) {
                $arr[] = $value['subject'];
            }
            $item["course_list_total"] = count(array_unique($arr));
        }
        if (!$order_in_db_flag) {
            \App\Helper\Utils::order_list( $ret_info["list"], $order_field_name, $order_type );
        }

        $account_id = $this->get_account_id();
        $main_type = 1;
        $is_master = $this->t_admin_group_name->check_is_master($main_type,$account_id);
        if($is_master>0 || $account_id==74 || $account_id=349){
            $master_adminid=1;
        }else{
            $master_adminid=0;
        }
        //dd($ret_info);
        return $this->Pageview(__METHOD__,$ret_info,['sumweek'=>$sumweek,'summonth'=>$ret['summonth'],"master_adminid"=>$master_adminid,"cur_time_str"=>$cur_time_str,"last_time_str"=>$last_time_str,"acc" => session("acc")]);
    }

    public function contract_list_seller () {
        $this->set_in_value("sys_operator", $this->get_account());
        return $this->contract_list();
    }

    public function contract_list_seller_add () {
        $this->set_in_value("contract_status", 0);
        $this->set_in_value("sys_operator", $this->get_account());
        return $this->contract_list();
    }

    public function contract_list_seller_payed () {
        $this->set_in_value("sys_operator", $this->get_account());
        $this->set_in_value("contract_status", -2);
        return $this->contract_list();
    }

    public function contract_list_ass () {
        $this->set_in_value("sys_operator", $this->get_account());
        return $this->contract_list();
    }

    public function contract_list () {
        list($start_time,$end_time,$opt_date_type)=$this->get_in_date_range(date("Y-m-01"),0,1,[
            1 => array("order_time","下单日期"),
            2 => array("pay_time", "生效日期"),
            3 => array("app_time", "申请日期"),
        ],3);

        $contract_type     = $this->get_in_int_val('contract_type',-1);
        $contract_status   = $this->get_in_int_val('contract_status',-1);
        $config_courseid   = $this->get_in_int_val('config_courseid',-1);
        $is_test_user      = $this->get_in_int_val('test_user',0);
        $studentid         = $this->get_in_studentid(-1);
        $page_num          = $this->get_in_page_num();
        $has_money         = $this->get_in_int_val("has_money",-1);
        $sys_operator      = $this->get_in_str_val("sys_operator","");
        $stu_from_type     = $this->get_in_int_val("stu_from_type",-1);
        $account_role      = $this->get_in_int_val("account_role",-1);
        $seller_groupid_ex = $this->get_in_str_val('seller_groupid_ex', "");
        $grade             = $this->get_in_int_val("grade",-1);
        $subject           = $this->get_in_int_val("subject",-1);
        $this->get_in_int_val("self_adminid", $this->get_account_id());
        $tmk_adminid       = $this->get_in_int_val("tmk_adminid", -1);
        $teacherid         = $this->get_in_teacherid(-1);
        $origin_userid     = $this->get_in_int_val("origin_userid", -1);
        $referral_adminid  = $this->get_in_int_val("referral_adminid",-1, "");
        $assistantid       = $this->get_in_assistantid(-1);
        $from_key          = $this->get_in_str_val('from_key');
        $from_url          = $this->get_in_str_val('from_url');
        $spec_flag= $this->get_in_e_boolean(-1,"spec_flag");

        $require_adminid_list = $this->t_admin_main_group_name->get_adminid_list_new($seller_groupid_ex);
        $account = $this->get_account();
        $show_yueyue_flag = false;
        if ($account == "yueyue" || $account == "jim") {
            $show_yueyue_flag = true;
        }

        $ret_auth = $this->t_manager_info->check_permission($account, E\Epower::V_SHOW_MONEY );
        $ret_list = $this->t_order_info->get_order_list_require_adminid(
            $page_num,$start_time,$end_time,$contract_type,
            $contract_status,$studentid,$config_courseid,
            $is_test_user, $show_yueyue_flag, $has_money,
            -1, $assistantid,"",$stu_from_type,$sys_operator,
            $account_role,$grade,$subject,$tmk_adminid,-1,
            $teacherid, -1 , 0, $require_adminid_list,$origin_userid,
            $referral_adminid,$opt_date_type
            , " t2.assistantid asc , order_time desc"
            , $spec_flag
        );

        $all_lesson_count = 0;
        $all_promotion_spec_diff_money=0;
        foreach($ret_list['list'] as &$item ){
            E\Eboolean::set_item_value_str($item,"is_new_stu");
            E\Egrade::set_item_value_str($item);
            E\Econtract_from_type::set_item_value_str($item,"stu_from_type");
            E\Efrom_parent_order_type::set_item_value_str($item);
            E\Econtract_status::set_item_value_str($item);
            E\Econtract_type::set_item_value_str($item);
            E\Esubject::set_item_value_str($item);
            \App\Helper\Utils::unixtime2date_for_item($item, 'contract_starttime');
            \App\Helper\Utils::unixtime2date_for_item($item, 'contract_endtime');
            \App\Helper\Utils::unixtime2date_for_item($item, 'order_time');
            \App\Helper\Utils::unixtime2date_for_item($item, 'get_packge_time');
            E\Efrom_type::set_item_value_str($item);
            $item["user_agent"]= \App\Helper\Utils::get_user_agent_info($item["user_agent"]);
            $this->cache_set_item_account_nick($item,"tmk_adminid", "tmk_admin_nick" );
            $this->cache_set_item_assistant_nick($item,"assistantid", "assistant_nick");
            $this->cache_set_item_account_nick($item,"origin_assistantid", "origin_assistant_nick");
            $this->cache_set_item_teacher_nick($item);

            $item['lesson_total']         = $item['lesson_total']*$item['default_lesson_count']/100;
            $item['order_left']           = $item['lesson_left']/100;
            $item['competition_flag_str'] = $item['competition_flag']==0?"否":"是";
            if (!$item["stu_nick"] ) {
                $item["stu_nick"]=$item["stu_self_nick"];
            }
            if($account == $item["sys_operator"] || $item['assistant_nick'] == $account || $ret_auth ) {
                $item['price'] = $item['price']/100;
            }else{
                $item['price'] = "---";
            }
            if($item['discount_price']==0){
                $item['discount_price']='';
            }else{
                $item['discount_price']=$item['discount_price']/100;
            }
            if($item['price']>0 && $item['lesson_total']>0){
                $item['per_price'] = round($item['price']/$item['lesson_total'],2);
            }else{
                $item['per_price'] = 0;
            }
            \App\Helper\Common::set_item_enum_flow_status($item);
            $all_lesson_count += $item['lesson_total'] ;
            $pre_money_info="";
            if ($item["pre_price"]) {
                if ($item["pre_pay_time"] ) {
                    $pre_money_info="已支付";
                }else{
                    $pre_money_info="未付";
                }
            }else{
                $pre_money_info="无";
            }
            $item["promotion_spec_diff_money"]/=100;
            $item["pre_money_info"]=$pre_money_info;
            $item["promotion_spec_is_not_spec_flag_str"]="";
            if ($item["promotion_spec_is_not_spec_flag"]) {
                $item["promotion_spec_is_not_spec_flag_str"]= "<font color=red>已转为非特殊申请</font>";
            }else{
                if ( $item["flowid"] ) {
                    $all_promotion_spec_diff_money+= $item["promotion_spec_diff_money"];
                }
            }
        }

        return $this->Pageview(__METHOD__,$ret_list,
                               ["all_lesson_count" => $all_lesson_count,
                                "all_promotion_spec_diff_money"=> $all_promotion_spec_diff_money ] );
    }

    //＝＝＝＝＝＝＝＝＝＝＝＝＝＝
    public function del_contract(){
        $orderid = $this->get_in_int_val("orderid");
        $userid  = $this->get_in_int_val("userid");


        //get from_type
        $from_type = $this->t_order_info->get_from_type($orderid);
        if($from_type==E\Efrom_type::V_1) {
            $lesson_account_id=$this->t_order_info->get_config_lesson_account_id($orderid);
        }
        $sys_operator=$this->t_order_info->get_sys_operator($orderid);
        $opt_adminid=$this->t_manager_info->get_id_by_account($sys_operator);

        $this->t_flow->flow_del_by_from_key_int($opt_adminid, E\Eflow_type::V_SELLER_ORDER_REQUIRE,$orderid);

        //$this->cache_del_teacher_nick($id);
        $ret = $this->t_order_info->del_contract($orderid,$userid);
        if ($ret){
            //处理
            if ($from_type == E\Efrom_type::V_1 ) {
                $total_money=$this->t_user_lesson_account->get_total_money($lesson_account_id);
                if ($total_money==0) {//删掉.
                    $this->t_user_lesson_account->row_delete($lesson_account_id);
                }
            }
            $this->t_student_info->field_update_list($userid,[
               "status"  => 0
            ]);
            $son_order_list = $this->t_order_info->get_son_order_list($orderid);
            foreach($son_order_list as $item){
                $this->t_order_info->del_contract($item["orderid"],$userid);
            }
        }

        return outputjson_ret($ret);
    }
    //============================================================================================
    public function get_contract_count_by_courseid(){
        $courseid = $this->get_in_int_val("courseid") ;

        $count= $this->t_course_order->count_course($courseid);

        return outputjson_success( ["count"=> $count]);
    }


    public function set_test_user()
    {
        $userid        = $this->get_in_int_val('userid',0);
        $type          = $this->get_in_int_val('type',0);

        $ret_note = $this->t_student_info->set_test_type($userid,$type);
        if($ret_note === false){
            return outputJson_err();
        }
        return outputjson_success();
    }

    public function set_stu_type()
    {
        $userid = $this->get_in_int_val('userid',0);
        $type   = $this->get_in_int_val('type',0);
        $is_auto_set_type_flag   = $this->get_in_int_val('is_auto_set_type_flag',0);
        $lesson_stop_reason   = trim($this->get_in_str_val('lesson_stop_reason'));
        $recover_time   = $this->get_in_str_val('recover_time');
        $wx_remind_time   = $this->get_in_str_val('wx_remind_time');
        $stop_duration   = trim($this->get_in_str_val('stop_duration'));
        if(empty($lesson_stop_reason)){
            return $this->output_err("请填写原因");
        }
        if($type>1){
            if(empty($recover_time) || empty($stop_duration)){
                return $this->output_err("请填写完整");
            }
            $recover_time = strtotime($recover_time);
            $wx_remind_time = strtotime($wx_remind_time);
            if($wx_remind_time>0 && ($wx_remind_time>$recover_time || $wx_remind_time<time())){
                return $this->output_err("微信提醒时间不能早于当前时间,不能大于复课时间");
            }
        }else{
            $recover_time =0;
            $wx_remind_time =0;
            $stop_duration="";
        }

        $old_type= $this->t_student_info->get_type($userid);
        if($old_type !=1 && $type==1){
            $lesson_time = time();
            $have_lesson = $this->t_lesson_info_b2->check_have_regular_lesson_new($userid,$lesson_time);
            if(!empty($have_lesson)){
                return $this->output_err("该学生有未上的常规课,不能设置为结课学员");
            }
            $this->delete_teacher_regular_lesson($userid);
        }

        $ret_note = $this->t_student_info->set_student_type($userid,$type,$is_auto_set_type_flag,$lesson_stop_reason);
        if($type != $old_type){
            $ret_note= $this->t_student_info->field_update_list($userid,[
                "type_change_time"  =>time()
            ]);

            $this->t_student_type_change_list->row_insert([
                "userid"    =>$userid,
                "add_time"  =>time(),
                "type_before" =>$old_type,
                "type_cur"    =>$type,
                "change_type" =>2,
                "adminid"     =>$this->get_account_id(),
                "reason"      =>$lesson_stop_reason,
                "recover_time"=>$recover_time,
                "wx_remind_time"=>$wx_remind_time,
                "stop_duration"=>$stop_duration
            ]);
        }

        //echo "11";
        //dd($ret_note);exit;
        if($ret_note === false){
            return outputJson_err();
        }
        return outputjson_success();
    }

    public function get_user_list()
    {
        $type        = $this->get_in_str_val("type","teacher");
        $gender      = $this->get_in_int_val('gender',-1);
        $id          = $this->get_in_int_val('id',-1);
        $nick_phone  = trim($this->get_in_str_val('nick_phone',""));
        $lru_flag    = $this->get_in_int_val("lru_flag");
        $lru_id      = $this->get_in_int_val("lru_id");
        $lru_id_name = $this->get_in_str_val("lru_id_name" );




        // dd($lru_id);
        $lru_key = "USER_LIST_".$type."_".$this->get_account_id();

        \App\Helper\Utils::logger("lru_id1: $lru_key, $lru_id");



        if ($lru_id) {
            $lru_arr=\App\Helper\Common::redis_get_json($lru_key);
            if (!$lru_arr) {
                $lru_arr=[];
            }
            $new_lru_arr   = [];
            $new_lru_arr[] = ["id"=> $lru_id, "name" => $lru_id_name ];

            foreach($lru_arr as $lru_item )  {
                if ($lru_item["id"]!= $lru_id) {
                    $new_lru_arr[]=$lru_item;
                }
            }

            if (count($new_lru_arr)>10) {
                unset($new_lru_arr[10]);
            }
            \App\Helper\Common::redis_set_json($lru_key, $new_lru_arr);
            return $this->output_succ();
        }

        $main_type = $this->get_in_int_val('main_type', -1);
        $groupid   = $this->get_in_int_val('groupid', -1);
        $adminid   = $this->get_in_int_val('adminid', -1);
        $page_num  = $this->get_in_page_num();
        $ret_list  = \App\Helper\Utils::list_to_page_info( array());
        if ($type=="teacher"){
            $ret_list= $this->t_teacher_info->get_tea_list_for_select($id,$gender, $nick_phone, $page_num);
        }else if ($type=="assistant" ){
            $ret_list= $this->t_assistant_info->get_ass_list_for_select($id,$gender, $nick_phone, $page_num);
        }else if ($type=="student" ){
            $this->t_student_info->switch_tongji_database();
            $ret_list= $this->t_student_info->get_list_for_select($id,$gender, $nick_phone, $page_num,$adminid);
        }else if ($type=="seller_student" ){ //销售的用户
            $ret_list= $this->t_student_info->get_seller_list_for_select($id,$gender, $nick_phone, $page_num,$adminid);
        }else if ($type=="admin" ||$type=="account"  ){
            $ret_list= $this->t_manager_info->get_list_for_select($id,$gender, $nick_phone, $page_num,$main_type);
        }else if ($type=="admin_group_master" ){
            $ret_list= $this->t_manager_info-> get_group_master_for_select($id,$gender,$nick_phone,$page_num,$main_type);
        }else if ($type=="admin_group_member" ){
            $ret_list= $this->t_manager_info-> get_group_user_list_for_select($id,$gender,$nick_phone,$page_num,$groupid);
        }else if ($type=="parent" ){
            $ret_list= $this->t_parent_info->get_list_for_select($id,$gender, $nick_phone, $page_num);
        }else if ($type=="none_freeze_teacher" ){//未冻结排课的老师
            $ret_list= $this->t_teacher_info->get_none_freeze_tea_list_for_select($id,$gender, $nick_phone, $page_num);
        }else if ($type=="interview_teacher" ){//有面试权限的老师
            $ret_list= $this->t_teacher_info->get_interview_tea_list_for_select($id,$gender, $nick_phone, $page_num);
        }else if($type=="jiaoyan_teacher"){//教研以及全职老师
            $ret_list= $this->t_teacher_info->get_jiaoyan_tea_list_for_select($id,$gender, $nick_phone, $page_num);
        }else if($type=="research_teacher"){//教研老师
            $ret_list= $this->t_teacher_info->get_research_tea_list_for_select($id,$gender, $nick_phone, $page_num);
        }



        $lru_list=null;
        if ($lru_flag) {
            $lru_list=\App\Helper\Common::redis_get_json($lru_key);
            if (!$lru_list) {
                $lru_list=[];
            }
        }
        if($type=="teacher" || $type=="none_freeze_teacher" || $type=="interview_teacher" || $type=="jiaoyan_teacher" || $type=="research_teacher"){
            foreach($ret_list["list"] as &$item){
                $item["phone"] = preg_replace('/(1[358]{1}[0-9])[0-9]{4}([0-9]{4})/i','$1****$2',$item["phone"]);
                $item["subject"] = E\Esubject::get_desc($item["subject"]);
                $item["grade"] = E\Egrade_part_ex::get_desc($item["grade_part_ex"]);
;
            }
        }
        return $this->output_ajax_table($ret_list, [ "lru_list" => $lru_list ]);
    }

    public function get_userid_by_phone() {
        $phone = $this->get_in_phone_ex();
        $userid=$this->t_phone_to_user->get_userid_by_phone($phone);
        return outputjson_success(["userid"=>$userid]);
    }
    public function get_courseid_by_userid() {
        $userid= $this->get_in_userid();
        $userid=$this->t_course_order->get_student_courseid_by_userid($userid);

        return $this->output_succ(["has_flag"=>$ret]);
    }

    public function get_parent_list(){
        $parentid = $this->get_in_int_val('parentid',-1);
        $ret_info = $this->t_parent_info->get_parent_sim_info($parentid);
        return outputJson(array('ret'=>0,'ret_info'=>$ret_info));
    }


    public function update_parent_info()
    {
        $parentid    = $this->get_in_int_val('parentid',-1);
        $nick        = $this->get_in_str_val('nick');
        $gender      = $this->get_in_int_val('gender');
        $phone       = $this->get_in_str_val('phone');
        $last_time   = $this->get_in_str_val('last_time');
        $has_login   = $this->get_in_int_val('has_login',-1);
        $wx_openid   = $this->get_in_str_val("wx_openid");

        return $this->t_parent_info->update_parent_info($parentid,$nick,$gender,$phone,$last_time,$has_login,$wx_openid);

    }


    public function set_spree()
    {
        $studentid = $this->get_in_int_val('studentid');
        $spree     = $this->get_in_str_val('spree');
        $this->t_student_info->set_spree_details($studentid,$spree);

        return outputjson_success();
    }


    public function get_self_contract()
    {
        $userid     = $this->get_in_int_val('userid',-1);
        $contractid = $this->get_in_str_val('contractid');

        $row = $this->t_order_info->get_contract_type_by_userid($userid,$contractid);

        return outputjson_success(["data"=> $row  ]);

    }

    public function update_contract_type()
    {
        $userid        = $this->get_in_int_val('userid',-1);
        $contractid    = $this->get_in_str_val('contractid');
        $contract_type = $this->get_in_int_val('contract_type',-1);

        $this->t_order_info->update_only_contract_type($userid,$contractid,$contract_type);

        return outputjson_success();
    }

    public function get_contract_starttime(){
        $contractid = $this->get_in_int_val('contractid',-1);
        $orderid    = $this->get_in_int_val('orderid',-1);

        $ret_info = $this->t_order_info->get_contract_starttime_info($contractid,$orderid);

        return outputJson(array('ret' => 0,'data'=>$ret_info));
    }

    public function set_contract_starttime()
    {
        $contractid         = $this->get_in_int_val('contractid',-1);
        $orderid            = $this->get_in_int_val('orderid',-1);
        $contract_starttime = strtotime($this->get_in_str_val('contract_starttime'));
        $contract_endtime   = $this->get_in_str_val('contract_starttime');
        $contract_endtime_s1   = explode('-',$contract_endtime);
        $contract_endtime_s1[0]+3;
        $contract_endtime_s2   = strtotime(implode('-',$contract_endtime_s1));

        $ret_info = $this->t_order_info->set_contract_sim_starttime($contractid,$orderid,$contract_starttime,$contract_endtime_s2);

        return outputJson(array('ret' => 0,'data'=>$ret_info));
    }


    public function pc_relationship()
    {
        $page_num  = $this->get_in_page_num();
        $studentid = $this->get_in_studentid(-1);
        $parentid  = $this->get_in_parentid(-1);

        $ret_list = $this->t_parent_child->get_relationship($page_num,$studentid,$parentid);

        foreach($ret_list['list'] as &$item ){
            $item['parent_nick'] = $this->cache_get_parent_nick($item['parentid']);
            $item['user_nick']   = $this->cache_get_student_nick($item['userid']);
            E\Erelation_ship::set_item_value_str($item, "parent_type");
            E\Erole::set_item_value_str($item);
        }
        return $this->Pageview(__METHOD__,$ret_list );
    }

    public function count_zan()
    {
        $praise_type = $this->get_in_int_val('praise_type',-1);
        $start_date  = $this->get_in_str_val('start_date',date('Y-m-d', time(NULL)-3*86400 ));
        $end_date    = $this->get_in_str_val('end_date',date('Y-m-d', time(NULL)+86400 ));


        $start_date_s = strtotime($start_date);
        $end_date_s   = strtotime($end_date)+86400;

        $ret_info = $this->t_mypraise->get_praise_list_by_page($praise_type,$start_date_s,$end_date_s);

        foreach($ret_info as &$item) {

            E\Epraise::set_item_value_str ($item,"type");
            if($item['type'] == 2001){
                $item['praise_num'] = '-'.$item['praise_num'];
            }
        }

        $args = array(
            "start_date"  => $start_date,
            "end_date"    => $end_date,
            "praise_type" => $praise_type,
        );
        $js_values_str   =  $this->get_js_g_args($args);

        return $this->view(__METHOD__,["ret_info"=>$ret_info,"js_values_str" => $js_values_str]);

    }

    public function get_mypraise_info()
    {
        $type     = $this->get_in_int_val('type',-1);
        $page_num = $this->get_in_page_num();

        $ret_db = $this->t_mypraise->get_praise_list($type,$page_num);

        return outputJson(array('ret' => 0, 'info' => "success", 'praise_list' => $ret_db));

    }


    public function zan_info()
    {
        $start_date = $this->get_in_str_val('start_date',date('Y-m-d', time(NULL)-30*86400 ));
        $end_date   = $this->get_in_str_val('end_date',date('Y-m-d', time(NULL)+86400 ));
        $type       = $this->get_in_int_val('praise_type',-1);
        $userid     = $this->get_in_int_val('userid',-1);
        $start_time = strtotime($start_date);
        $end_time   = strtotime($end_date)+86400;
        $page_num   = $this->get_in_page_num();
        $lessonid   = $this->get_in_lessonid(-1);

        $ret_info = $this->t_mypraise->get_all_info( $page_num,$start_time,$end_time,$type,$userid ,$lessonid);

        foreach($ret_info['list'] as &$item){
            $type = $item['type'];
            if($type==1099){
                $item['type'] = $item['reason'];
            }else{
                $item['type'] = E\Epraise::get_desc($item['type']);
            }

            $item['ts']   = date('Y-m-d H:i',$item['ts']);
            $item['name'] = $this->cache_get_student_nick($item['userid']);

            if($item['add_userid']!=0){
                $item['add_user_name'] = $this->t_manager_info->get_account($item['add_userid']);
            }else{
                $item['add_user_name'] = "系统添加";
            }
        }
        return $this->pageView(__METHOD__,$ret_info);
    }

    public function change_contract_user(){
        $userid   = $this->get_in_int_val('userid',0);
        $orderid  = $this->get_in_int_val('orderid',0);
        $courseid = $this->get_in_int_val('courseid',0);

    }

    public function add_praise(){
        $userid     = $this->get_in_int_val('userid',0);
        $type       = $this->get_in_int_val('type',1099);
        $praise_num = $this->get_in_int_val('praise_num',0);
        $reason     = $this->get_in_str_val('reason','');
        $add_userid = $_SESSION['adminid'];

        $ret_info = $this->t_mypraise->add_mypraise($userid,$praise_num,$reason,$add_userid,$type);

        if(!$ret_info){
            return outputjson_error("添加失败，请重试！");
        }

        return outputjson_success();
    }

    public function get_type_student_list(){
        for($i=0;$i<12;$i++){
            if($i>0){
                $str="-".$i."month";
            }else{
                $str="now";
            }

            $start     = strtotime(date("Y-m-01",time()).$str);
            $ret_count = $this->t_order_info->get_user_lesson_count($start);
            $ret_cost  = $this->t_lesson_info->get_user_lesson_cost($start);
            foreach($ret_count as $key=>$val){
                $order_count=$this->t_order_info->get_order_count($key);
                if($order_count>1){
                    $ret_cost[$key]['userid']=$val['userid'];
                    $ret_cost[$key]['lesson_count']=$val['lesson_count'];
                    if(isset($ret_cost[$key]['lesson_cost'])){
                        $ret_cost[$key]['lesson_left']=$val['lesson_count']-$ret_cost[$key]['lesson_cost'];
                    }else{
                        $ret_cost[$key]['lesson_left']=$val['lesson_count'];
                    }
                    if($ret_cost[$key]['lesson_left']<=15 && $ret_cost[$key]['lesson_left']>0){
                        $userid_list[$i][]=$key;
                    }
                }
            }
            $count[$i]=count($userid_list[$i]);
        }

        \App\Helper\Utils::debug_to_html( $count );
    }

    public function refund_list_seller(){
        $this->set_in_value( "refund_userid", $this->get_account_id() );
        return $this->refund_list();
    }

    public function refund_list_ass(){
        $this->set_in_value( "refund_userid", $this->get_account_id() );
        return $this->refund_list();
    }

    public function refund_list(){
        list($start_time,$end_time,$opt_date_str) = $this->get_in_date_range_month(0,0, [
            0 => array( "apply_time", "申请时间"),
            1 => array("flow_status_time","审批时间"),
        ]);

        $adminid       = $this->get_account_id();
        $refund_type   = $this->get_in_int_val('refund_type',-1);
        $userid        = $this->get_in_int_val('userid',-1);
        $is_test_user  = $this->get_in_int_val('is_test_user',0);
        $page_num      = $this->get_in_page_num();
        $refund_userid = $this->get_in_int_val("refund_userid", -1);

        $seller_groupid_ex    = $this->get_in_str_val('seller_groupid_ex', "");
        $require_adminid_list = $this->t_admin_main_group_name->get_adminid_list_new($seller_groupid_ex);
        $adminid_right        = $this->get_seller_adminid_and_right();
        $acc                  = $this->get_account();

        $ret_info = $this->t_order_refund->get_order_refund_list($page_num,$opt_date_str,$refund_type,$userid,$start_time,$end_time,
                                                                 $is_test_user,$refund_userid,$require_adminid_list);

        foreach($ret_info['list'] as &$item){
            $item['user_nick']         = $this->cache_get_student_nick($item['userid']);
            $item['refund_user']       = $this->cache_get_account_nick($item['refund_userid']);
            $item['lesson_total']      = $item['lesson_total']/100;
            $item['should_refund']     = $item['should_refund']/100;
            $item['price']             = $item['price']/100;
            $item['real_refund']       = $item['real_refund']/100;
            $item['discount_price']    = $item['discount_price']/100;
            $item['apply_time_str']    = date("Y-m-d H:i",$item['apply_time']);
            $item['refund_status_str'] = $item['refund_status']?'已打款':'未付款';
            \App\Helper\Common::set_item_enum_flow_status($item);
            E\Econtract_type::set_item_value_str($item,"contract_type");
            E\Eboolean::set_item_value_str($item,"need_receipt");
            E\Egrade::set_item_value_str($item);
            \App\Helper\Utils::unixtime2date_for_item($item,"flow_status_time");
            \App\Helper\Utils::unixtime2date_for_item($item,"order_time","","Y-m-d");

            $refund_qc_list = $this->t_order_refund->get_refund_analysis($item['apply_time'], $item['orderid']);
            foreach($refund_qc_list as $val){
                if(!empty($val['qc_other_reason']) || !empty($val['qc_analysia']) || !empty($val['qc_reply']) ){
                    $item['flow_status_str'] = '<font style="color:#a70192;">QC已审核</font>';
                }
            }
        }

        return $this->pageView(__METHOD__,$ret_info,[
            "adminid_right" => $adminid_right,
            "acc"           => $acc,
            "adminid"       => $adminid
        ]);
    }

    public function set_refund_order(){
        $userid        = $this->get_in_int_val("userid");
        $orderid       = $this->get_in_int_val("orderid");
        $contractid    = $this->get_in_int_val("contractid");
        $contract_type = $this->get_in_int_val("contract_type");
        $lesson_total  = $this->get_in_int_val("lesson_total");
        $order_left    = $this->get_in_str_val("order_left");
        $should_refund = $this->get_in_int_val("should_refund")/100;
        $real_refund   = $this->get_in_int_val("real_refund")/100;
        $price         = $this->get_in_str_val("price");
        $refund_info   = $this->get_in_str_val("refund_info");
        $pay_account   = $this->get_in_str_val("pay_account");
        $pay_account_admin   = $this->get_in_str_val("pay_account_admin");
        $save_info     = $this->get_in_str_val("save_info");
        $file_url      = $this->get_in_str_val("file_url");
        $lesson_unassigned = $this->get_in_str_val("lesson_unassigned");
        $competition_flag  = $this->get_in_int_val("competition_flag");
        $adminid           = $this->get_account_id();
        $account           = $this->get_account();

        if($should_refund>$order_left){
            return $this->output_err("所退课时不足！请检查学生未分配的课时！");
        }

        if($should_refund>$lesson_unassigned){
            $ret = $this->t_course_order->reset_assigned_lesson_count($userid,$competition_flag);
            if(!$ret){
                return $this->output_err("重置学生已分配课时失败！请重试！");
            }
        }

        if($real_refund>$price){
            return $this->output_err("退费金额错误！");
        }

        $apply_time = time();
        $this->t_order_refund->start_transaction();
        $ret = $this->t_order_refund->row_insert([
            "userid"        => $userid,
            "orderid"       => $orderid,
            "contractid"    => $contractid,
            "contract_type" => $contract_type,
            "lesson_total"  => $lesson_total*100,
            "should_refund" => $should_refund*100,
            "price"         => $price*100,
            "real_refund"   => $real_refund*100,
            "apply_time"    => $apply_time,
            "refund_userid" => $adminid,
            "refund_info"   => $refund_info,
            "save_info"     => $save_info,
            "file_url"      => $file_url,
            "pay_account"   => $pay_account,
            "pay_account_admin"   => $pay_account_admin,
        ]);

        if(!$ret){
            $this->t_order_refund->rollback();
            return $this->output_err("退费添加失败");
        }

        $lesson_left     = $order_left-$should_refund;
        $contract_status = $lesson_left==0?3:1;
        $ret = $this->t_order_info->field_update_list($orderid,[
            "lesson_left"     => $lesson_left*100,
            "contract_status" => $contract_status,
        ]);

        if(!$ret){
            $this->t_order_refund->rollback();
            return $this->output_err("合同更新失败！");
        }

        /**
         $header_msg = $account."提出了一个退费申请。";
         $msg = "登陆后台->服务管理->其他->退费管理 进行审核。";
         $url = "/user_manage/refund_list";
         $this->t_manager_info->send_wx_todo_msg("echo",$account,$header_msg,$msg,$url);
        */

        //插入
        $ret = $this->t_flow->add_flow(
            E\Eflow_type::V_ASS_ORDER_REFUND ,
            $this->get_account_id(),"退费申请", $orderid,NULL,$apply_time  );

        if($ret){
            $this->t_order_refund->commit();

            $ret = $this->cost_praise($userid,$lesson_total,$real_refund);
            if(!$ret){
                return $this->output_err("学生退赞失败！请联系技术人员！");
            }
            return $this->output_succ();
        }else{
            $this->t_order_refund->rollback();
            return $this->output_err("生成退费流程失败！");
        }
    }

    private function cost_praise($userid,$lesson_total,$price){
        /**
         * 加赞规则
         * total<30      price * 0.3% *20
         * 30<total<100  price * 0.4% *20
         * 100<total<200 price * 0.5% *20
         * 200<total     price * 0.6% *20
         */
        if($lesson_total<30){
            $rate = 0.003;
        }elseif($lesson_total<100){
            $rate = 0.004;
        }elseif($lesson_total<200){
            $rate = 0.005;
        }else{
            $rate = 0.006;
        }

        $praise_num = round($price*$rate*20/100);
        $ret = $this->t_mypraise->add_praise($userid,2003,$praise_num);

        return $ret;
    }

    public function get_user_normal_order(){
        $userid=$this->get_in_int_val("userid",0);

        $order_list=$this->t_order_info->get_user_normal_order($userid);
        $ret_list = \App\Helper\Utils::list_to_page_info($order_list);
        foreach($ret_list['list'] as &$val){
            $val['order_time_str'] = date("Y-m-d H:i",$val['order_time']);
            $val['lesson_total']=$val['lesson_total']*$val['default_lesson_count']/100;
        }
        $ret_list["page_info"] = $this->get_page_info_for_js($ret_list["page_info"]);

        return $this->output_succ(["data"=>$ret_list]);
    }

    public function set_contract_payed_new()
    {
        $orderid    = $this->get_in_int_val('orderid', -1);
        $channelid  = $this->get_in_int_val('channelid',-1);
        $userid     = $this->get_in_int_val('userid',-1);
        $pay_number = $this->get_in_str_val('pay_number', "");
        if($orderid == -1 || $channelid == -1 || $pay_number == "") {
            return $this->output_err("参数错误");
        }
        /* 判断是否有尚未付款的普通合同 下一步可能会增加可付款的合同种类 */
        $ret_status = $this->t_order_info->get_contract_status($orderid);
        $channel = $this->t_order_info->get_channel($orderid);
        if($ret_status == E\Econtract_status::V_1 && empty($channel)) { //
            return $this->output_err("已经付款！不能再手动更改！");
        }

        $stu_nick   = $this->t_student_info->get_realname($userid);
        $ret_type   = $this->t_order_info->get_contract_type($orderid);
        $ret_info   = $this->t_student_info->has_parent_logined($orderid);
        $order_info = $this->t_order_info ->field_get_list($orderid,"contract_type,lesson_total,default_lesson_count,price");

        $lesson_total = $order_info['lesson_total']*$order_info['default_lesson_count']/100;

        if($ret_info['has_login'] === false && $order_info['contract_type']!=1){
            srand(microtime(true) * 1000);
            $phone = $this->t_phone_to_user->get_phone_by_userid($ret_info['parentid']);
            $passwd = $phone+rand(9999999999,99999999999);
            $passwd = substr($passwd, 0, 6);
            $md5_passwd = md5($passwd);
            $this->t_user_info->field_update_list($ret_info['parentid'],[
                "passwd"  => $md5_passwd,
            ]);
            if ($ret_type == 3001) {
            } else {
                /*
                 * SMS_46880148
                 * 第一次支付成功2-14
                 * ${name}家长您好，您已经成功购买“${lesson_total}”课时，愿与您一起为孩子的学习进步而努力。
                 * 想同步收听孩子上课实况、了解孩子作业情况请下载上海升学帮，下载链接：http://dwz.cn/3Bi1a9，
                 * 您的帐号是：${phone}，密码是：${passwd}。
                 */
                $sms_id = 46880148;
                $arr    = [
                    "name"         => $stu_nick,
                    "lesson_total" => $lesson_total,
                    "phone"        => $phone,
                    "passwd"       => $passwd,
                ];
            }
        }else{
            $phone       = $this->t_phone_to_user->get_phone_by_userid($ret_info['parentid']);
            $order_price = $this->t_order_info->get_price($orderid)/100;

            if ($ret_type == 3001) {
            } else if(!in_array($ret_type,[1,2])){
                /*
                 * 第二次支付成功2-14
                 * SMS_46800132
                 * ${name}家长您好，您所购买的理优课程已支付成功，支付金额为${price}元。
                 * 想同步收听孩子上课实况、了解孩子作业情况请下载：上海升学帮。下载链接：http://dwz.cn/3Bi1a9。
                 */
                $sms_id = 46800132;
                $arr    = [
                    "name"  => $stu_nick,
                    "price" => $order_price,
                ];
            }
        }

        if(isset($phone) && isset($sms_id) && $phone!=0 && $phone!=""){
            $sign_name = \App\Helper\Utils::get_sms_sign_name(0);
            \App\Helper\Utils::sms_common($phone,$sms_id,$arr,0,$sign_name);
        }

        $this->t_order_info->set_order_payed($orderid, $channelid, $pay_number);

        if(in_array($ret_type,[0,3])){
            $this->add_praise_by_order($orderid,$userid,$ret_type,$lesson_total);
        }

        $job = new \App\Jobs\StdentResetLessonCount($userid);
        dispatch($job);

        if($ret_type == 0){
            $this->update_agent_order($orderid,$userid,$order_info['price']);
        }

        return $this->output_succ();
    }


    public function update_agent_order($orderid,$userid,$order_price){
        $agent_order = [];
        $ret_info = [];
        $agent_order = $this->t_agent_order->get_row_by_orderid($orderid);
        if(!isset($agent_order['orderid'])){
            $phone    = $this->t_student_info->get_phone($userid);
            $ret_info = $this->t_agent->get_p_pp_id_by_phone($phone);
            if(isset($ret_info['id'])){
                $level1 = 0;
                $level2 = 0;
                if($ret_info['p_phone']){
                    $level1 = $this->check_agent_level($ret_info['p_phone']);
                }
                if($ret_info['pp_phone']){
                    $level2 = $this->check_agent_level($ret_info['pp_phone']);
                }
                $price           = $order_price/100;
                $level1_price    = $price/20>500?500:$price/20;
                $level2_p_price  = $price/10>1000?1000:$price/10;
                $level2_pp_price = $price/20>500?500:$price/20;
                $pid = $ret_info['pid'];
                $ppid = $ret_info['ppid'];
                $p_price = 0;
                $pp_price = 0;
                if($level1 == 1){//黄金
                    $p_price = $level1_price*100;
                }elseif($level1 == 2){//水晶
                    $p_price = $level2_p_price*100;
                }
                if($level2 == 2){//水晶
                    $pp_price = $level2_pp_price*100;
                }
                $this->t_agent_order->row_insert([
                    'orderid'     => $orderid,
                    'aid'         => $ret_info['id'],
                    'pid'         => $pid,
                    'p_price'     => $p_price,
                    'ppid'        => $ppid,
                    'pp_price'    => $pp_price,
                    'create_time' => time(null),
                ]);
            }
        }
    }

    public function check_agent_level($phone){//黄金1,水晶2,无资格0
        $student_info = [];
        $student_info = $this->t_student_info->get_stu_row_by_phone($phone);
        if(isset($student_info['userid'])){
            return 2;
        }else{
            $agent_item = [];
            $agent_item = $this->t_agent->get_agent_info_row_by_phone($phone);
            if(count($agent_item)>0){
                $test_lesson = [];
                $test_lesson = $this->t_agent->get_agent_test_lesson_count_by_id($agent_item['id']);
                $count       = count(array_unique(array_column($test_lesson,'id')));
                if(2<=$count){
                    return 2;
                }else{
                    return 1;
                }
            }else{
                return 0;
            }
        }
    }

    private function add_praise_by_order($orderid,$userid,$contract_type,$lesson_total){
        $price        = $this->t_order_info->get_price($orderid);
        $order_status = $this->t_order_info->get_order_status($orderid);

        /**
         * 加赞规则
         * total<30      price * 0.3% *20
         * 30<total<100  price * 0.4% *20
         * 100<total<200 price * 0.5% *20
         * 200<total     price * 0.6% *20
         */
        if(( $contract_type==0 || $contract_type==3 ) && $order_status==1 ){
            if( $lesson_total<30 ){
                $rate = 0.003;
            }elseif($lesson_total<100){
                $rate = 0.004;
            }elseif($lesson_total<200){
                $rate = 0.005;
            }else{
                $rate = 0.006;
            }
            $praise_num = round($price*$rate*20/100);
            $this->t_mypraise->add_praise($userid,1029,$praise_num);
        }
    }

    public function user_info_by_month(){
        $this->switch_tongji_database();
        $ret_info = [];
        for($i=1;$i<=12;$i++){
            $ret_info[$i] = $this->t_lesson_info->get_user_info_by_month($i);
        }
        foreach($ret_info as $k=>&$item){
            $item["month"]=$k;
        }
        $ret_info = \App\Helper\Utils::list_to_page_info($ret_info);
        return $this->pageView(__METHOD__,$ret_info);
    }

    public function user_login_list(){
        list($start_time,$end_time)=$this->get_in_date_range(-7,0);
        $account = $this->get_in_str_val("account","");
        $page_num   = $this->get_in_page_num();
        $ret_info = $this->t_login_log->get_login_list_by_time_new($start_time,$end_time,$account, $page_num);
        foreach($ret_info["list"] as &$item){
            $item["login_time_str"] = date("Y-m-d H:i:s",$item["login_time"]);
            E\Eboolean::set_item_value_str($item, "flag");
        }
        return $this->pageView(__METHOD__,$ret_info);
    }

    public function tongji_login_ip_info(){
        list($start_time,$end_time)=$this->get_in_date_range(0,0,0,[],1);
        $account = $this->get_in_str_val("account","");
        $ret_info = $this->t_login_log->tongji_login_ip_info_new($start_time,$end_time,$account);

        foreach($ret_info["list"] as &$item){
            $item["local"] = \App\Helper\Common::get_ip_addr_str_new($item["ip"]);
        }
        return $this->pageView(__METHOD__,$ret_info);
    }

    public function get_refund_info () {
        $orderid     = $this->get_in_int_val("orderid");
        $apply_time  = $this->get_in_int_val("apply_time");
        $list        = $this->t_refund_analysis->get_list($orderid,$apply_time);

        foreach ($list as $key =>&$item) {
            if($item['configid']>0){
                $keys       = $this->t_order_refund_confirm_config->get_refundid_by_configid($item['configid']);
                $ret        = $this->t_order_refund_confirm_config->get_refund_str_by_keys($keys);
                $list[$key] = array_merge($item,$ret);
            }
        }
        list($refund_info ,$map) = $this->t_order_refund_confirm_config->get_refund_list_and_map( -1, -1, -1);
        return $this->output_succ(
            array(
                'confirm_config' => $map,
                'confirm_list' => $list,
            )
        );
    }

    public function set_refund_analysis_info () {
        $orderid          = $this->get_in_int_val("orderid");
        $apply_time       = $this->get_in_int_val("apply_time");
        $confirm_info     = $this->get_in_str_val("confirm_info");
        $confirm_info_arr = json_decode($confirm_info,true);
        // dd($confirm_info_arr);
        // dd(1);
        $add_time=time();
        $re = $this->t_refund_analysis->delete_by_order_apply_time($orderid, $apply_time);

        foreach ( $confirm_info_arr as $item ) {
            if ($item['key3'] == -1 && $item['key4'] == -1) {
                $refund_arr_key3 = $this->insert_refund_info($item['key1'], $item['key2'], $item['key3'], $item['key4'], $item['key3_str']);
                $item['key3'] = $refund_arr_key3['key3'];

                $refund_arr_key4 = $this->insert_refund_info($item['key1'], $item['key2'], $item['key3'], $item['key4'], $item['key3_str']);
                $item['key4'] = $refund_arr_key4['key4'];
            }
            $configid = $this->t_order_refund_confirm_config->get_id_by_keys($item['key1'], $item['key2'], $item['key3'], $item['key4'] );
            $ret =  $this->t_refund_analysis->row_insert([
                "orderid"    => $orderid,
                "apply_time" => $apply_time ,
                "configid"   => $configid,
                "score"      => $item['score'],
                "reason"     => $item['reason'],
                "add_time"   => $add_time
            ]);
        }

       if(isset($ret)){
           return $this->output_succ();
       } else {
           return $this->output_err("退费添加失败");
        }
    }

    public function get_responsibility_by_orderid_apply_time () {
        $orderid          = $this->get_in_int_val("orderid");
        $apply_time       = $this->get_in_int_val("apply_time");

        $list = $this->t_refund_analysis->get_list($orderid, $apply_time);
        $total_score = 0;

        // dd($list);
        $key1_value = $this->t_order_refund_confirm_config->get_all_key1_value();

        foreach ($list as &$item) {
            $total_score += $item['score'];
            $item['department'] = $this->t_order_refund_confirm_config->get_department_name_by_configid($item['configid']);
        }

        foreach ($list as &$item) {
            if($total_score != 0){
                $item['responsibility_percent'] = number_format(($item['score']/$total_score)*100,2);
            } else {
                $item['responsibility_percent'] = 0;
            }
        }
        // dd($list);

        $all_percent_tmp = [];
        foreach ($key1_value as &$item) {
            $item['responsibility_percent']  = 0;
            $all_percent_tmp[$item['value']] = 0;
            foreach ($list as $item_tmp) {
                if ($item['value'] == $item_tmp['department']) {
                    $all_percent_tmp[$item['value']] += $item_tmp['responsibility_percent'];
                }
            }
            $all_percent_tmp[$item['value']].="%";
        }

        $all_percent[0] = $all_percent_tmp;
        return $this->output_succ(
            array(
                'confirm_list' => $all_percent,
            )
        );
    }

    public function get_reply_by_orderid_apply_time () {
        $orderid          = $this->get_in_int_val("orderid");
        $apply_time       = $this->get_in_int_val("apply_time");

        $list = $this->t_refund_analysis->get_list($orderid, $apply_time);

        if (!empty($list) && ($list[0]['other_reason'] || $list[0]['qc_analysia'] || $list[0]['reply']) ) {
            $reply[0] = $list[0];
        } else {
            $reply = [];
        }

        return $this->output_succ(
            array(
                'confirm_list' => $reply,
            )
        );
    }

    public function set_reply () {
        $orderid          = $this->get_in_int_val("orderid");
        $apply_time       = $this->get_in_int_val("apply_time");
        $confirm_info     = $this->get_in_str_val("confirm_info");
        $confirm_info_arr = json_decode($confirm_info,true);

        $this->t_refund_analysis->clear_by_order_apply_time($orderid, $apply_time);

        $orderid_apply_arr = [
            "orderid"    => $orderid,
            "apply_time" => $apply_time
        ];

        foreach ( $confirm_info_arr as $item ) {
            $ret =  $this->t_refund_analysis->update_reply($orderid,$apply_time, $item["other_reason"],$item['qc_analysia'],$item['reply']);
        }
    }

    public function refund_analysis () {
        $adminid = $this->get_account_id();
        $orderid     = $this->get_in_int_val("orderid",-1);
        $apply_time  = $this->get_in_int_val("apply_time");
        $list        = $this->t_refund_analysis->get_list($orderid,$apply_time);

        if($orderid <=0){
            return $this->error_view(["请从[退费管理]-[QC退费分析总表]进入"]);
        }
        // dd($list);
        foreach ($list as $key =>&$item) {
            $keys       = $this->t_order_refund_confirm_config->get_refundid_by_configid($item['configid']);
            $ret        = $this->t_order_refund_confirm_config->get_refund_str_by_keys($keys);
            $list[$key] = array_merge($item,$ret);
        }

        // dd($list);
        list($refund_info ,$map) = $this->t_order_refund_confirm_config->get_refund_list_and_map( -1, -1, -1);

        //以上处理原因填写

        $total_score   = 0;
        $key1_value    = $this->t_order_refund_confirm_config->get_all_key1_value();
        $has_teaching  = true;

        foreach ($list as &$item) {
            $total_score += $item['score'];
            $item['department'] = $this->t_order_refund_confirm_config->get_department_name_by_configid($item['configid']);
            if ($item['department'] == "教学部") {
                $has_teaching = false;
            }
        }

        foreach($list as &$item){
            $is_hasScoreFlag = false;

            if(($item["department"] == "教学部" && $item['score'] == 0) || $has_teaching) {
                $is_hasScoreFlag = true;
            }

            if ($is_hasScoreFlag && ($item['department'] == "老师" || $item['department'] == "科目" )) {
                $total_score = $total_score - $item['score'];
            }
        }

        foreach ($list as &$item) {
            if($total_score != 0){
                $item['responsibility_percent'] = number_format(($item['score']/$total_score)*100,2);
            } else {
                $item['responsibility_percent'] =0;
            }
        }

        $all_percent = [];

        // dd($key1_value);

        foreach ($key1_value as &$item) {
            $item['responsibility_percent']  = 0;
            $all_percent[$item['value']] = 0;
            foreach ($list as $item_tmp) {
                if ($item['value'] == $item_tmp['department']) {
                    $all_percent[$item['value']] += $item_tmp['responsibility_percent'];
                }
            }
            $all_percent[$item['value']].="%";
        }

        if($all_percent["教学部"] == '0%'){
            if (isset($all_percent['老师'] )) {
                $all_percent['老师'] = "0%";
            }

            if ( isset($all_percent['科目'])) {
                $all_percent['科目'] = "0%";
            }
        }
        //以上处理责任比率

        $qc_anaysis = $this->t_order_refund->get_qc_anaysis_by_orderid_apply($orderid, $apply_time);

        return $this->pageView(__METHOD__,null,
                  ["refund_info" => $list,
                   "all_percent" => $all_percent,
                   "qc_anaysis"  => $qc_anaysis,
                   "adminid"     => $adminid
                  ]
        );
    }

    public function add_qc_analysis_by_order_apply(){
        $orderid           = $this->get_in_int_val("orderid");
        $apply_time        = $this->get_in_int_val("apply_time");
        $qc_other_reason   = $this->get_in_str_val("qc_other_reason");
        $qc_analysia       = $this->get_in_str_val("qc_analysia");
        $qc_reply          = $this->get_in_str_val("qc_reply");

        $this->t_order_refund->update_refund_list($orderid, $apply_time, $qc_other_reason, $qc_analysia, $qc_reply);
        return $this->output_succ();
    }

    public function insert_refund_info ($key1, $key2, $key3, $key4, $value) {
        $key4  = 0;

        if ($key1==-1) {
            $key1= $this->t_order_refund_confirm_config-> get_next_key_value($key1,$key2,$key3);
            $key2=0;
            $key3=0;
        }else if($key2==-1) {
            $key2= $this->t_order_refund_confirm_config-> get_next_key_value($key1,$key2,$key3);
            $key3=0;
        }else if($key3==-1) {
            $key3= $this->t_order_refund_confirm_config-> get_next_key_value($key1,$key2,$key3);
        }else  {
            $key4= $this->t_order_refund_confirm_config-> get_next_key_value($key1,$key2,$key3);
        }

        $refund_arr = [
            "key1"  => $key1,
            "key2"  => $key2,
            "key3"  => $key3,
            "key4"  => $key4,
            "value" => $value,
        ];

       $ret = $this->t_order_refund_confirm_config->row_insert($refund_arr);
        return $refund_arr;

    }


    public function graduating_lesson_time () {
        list($start_time,$end_time) = $this->get_in_date_range(date("Y-04-01"),0,0,[],3);

        $adminid = $this->get_account_id();
        // $adminid = 299;//助员
        // $adminid = 297;//助张
        $assistantid_flag = 0;

        $group_id = $this->t_admin_group_name->get_group_id($adminid);

        // 处理权限问题
        $permission_str = $this->t_manager_info->get_permission_by_adminid($adminid);

        $permission_arr = explode(',',$permission_str);

        $allow_adminid = [37,66,52,90];

        $ret_intersect = array_intersect($allow_adminid,$permission_arr);

        $assistantid_flag = $ret_intersect?1:0;
        // 处理权限问题

        $group_id_lists_arr = [];
        if($group_id == 0){
            $group_id_lists_str = $adminid;
        } else {
            $group_id_lists = $this->t_admin_group_user->get_group_id_lists($group_id);

            foreach($group_id_lists as $item_group){
                Array_push($group_id_lists_arr,$item_group['adminid']);
                $group_id_lists_str = implode(',',$group_id_lists_arr); ;
            }
        }

        $residual_flag  = $this->get_in_e_boolean(-1,"residual_flag");

        $page_num     = $this->get_in_page_num();
        $lists_all    = $this->t_student_info->get_graduating_student_info($page_num, $start_time, $group_id_lists_str, $assistantid_flag );
        // dd($lists_all);
        $date         = date('Y-04-01',$start_time);
        $year         = date('Y',$start_time);
        $weeks        = $this->datetoweek($date);

        $userid_list_arr   = [];
        $plan_lists        = [];
        $weeks_actual_time = [];
        foreach( $lists_all['list'] as $index=> &$item ) {
            $plan_lists[$index] = $this->t_graduating_student_lesson_time_count->get_plan_lesson_count($item['userid'], $start_time);
            E\Ebook_grade::set_item_value_str($item,"grade");
            $item["assistant_nick"] = $this->cache_get_assistant_nick ($item["assistantid"] );
            $userid_list_arr[]      = $item['userid'];
            $item_tmp_arr     = $this->t_lesson_info->get_actual_lesson_time($item['userid'], $start_time);

            //处理每周的实际课时
            $weeks_actual_time[] = $this->t_lesson_info->get_week_actual_time($weeks,$item['userid'], $year);
            $item_week_temp = 0;
            if(!empty($weeks_actual_time)){
                foreach($weeks_actual_time as $val_weeks_all){
                    foreach($val_weeks_all as $val_weeks){
                        $item_week_temp += $val_weeks;
                        // $item['actual_time_weeks'] = $item_week_temp/3600 ;
                        $item['actual_time_weeks'] = $item_week_temp/2400 ;
                    }
                }
            }

            $item['plan_lesson_time_all'] = 0;
            if(!empty($plan_lists[$index])){
                foreach($plan_lists[$index] as $items_plan_arr){
                    $item['plan_lesson_time_all'] += $items_plan_arr['plan_lesson_time'];
                }
            }
            // $item['lesson_count_left'] = $item['lesson_count_left']/100;
            $item['residual_value']    = ($item['lesson_count_left']- $item['plan_lesson_time_all'])/100;
        }

        foreach($lists_all['list'] as $index_residual_flag => $item_residual_flag){
            if($residual_flag == 0){
                if($item_residual_flag['residual_value'] >= 0){
                    unset($lists_all['list'][$index_residual_flag]);
                }
            } else if($residual_flag == 1){
                if($item_residual_flag['residual_value'] < 0){
                    unset($lists_all['list'][$index_residual_flag]);
                }
            }
        }

        $userid_list_str = implode(",",$userid_list_arr);

        $tmp = [];
        $total_plan_actual_time = [];
        foreach($weeks as $i=>$val){
            $tmp[$i-1]['plan_lesson_time']= 0;
        }

        foreach($plan_lists as $i=>&$val){
            if (empty($val)) {
                $val = $tmp;
            }
        }

        foreach($plan_lists as $index_plan_time=>$item_total_time){
            foreach($item_total_time as $index_time=>$item_time){
                $total_plan_actual_time[$index_plan_time][$index_time]['plan_lesson_time'] = $item_time['plan_lesson_time']/100;
            }
        }

        foreach ($weeks_actual_time as $index_actual_total_time=>$item_total_actual_time){
            foreach($item_total_actual_time as $index_actual_time=>$item_actual_time){
                if (number_format($item_actual_time/2400,2) == 2.25) {
                    $total_plan_actual_time[$index_actual_total_time][$index_actual_time-1]['actual_lesson_time'] = 2;
                } else {
                    $total_plan_actual_time[$index_actual_total_time][$index_actual_time-1]['actual_lesson_time'] = number_format($item_actual_time/2400,2);
                }
            }
        }


        return $this->pageView(__METHOD__,$lists_all,
                               ['graduating_list'        => $lists_all,
                                'weeks'                  => $weeks,
                                'total_plan_actual_time' => $total_plan_actual_time
                               ]
        );
    }



    public function set_graduating_lesson_time(){
        list($start_time,$end_time) = $this->get_in_date_range(date("Y-04-01"),0,0,[],3);
        $res_json   = $this->get_in_str_val('res');
        $res_arr    = json_decode($res_json);
        $userid     = $this->get_in_int_val('userid');

        $date   = date('Y-04-01',$start_time);
        $weeks  = $this->datetoweek($date);
        $year   = date('Y',$start_time);
        // dd($res_arr);
        $this->t_graduating_student_lesson_time_count->set_plan_lesson_time($res_arr, $userid, $weeks, $year);
        return $this->output_succ();
    }

    /**
    * 获取当前日期的周数
    */

    public function get_weeks() {
        list($start_time,$end_time)=$this->get_in_date_range(date("Y-04-01"),0,0,[],3);
        $userid   = $this->get_in_int_val('userid');
        $plan_list = $this->t_graduating_student_lesson_time_count->get_plan_lesson_count($userid, $start_time );
        $date = date('Y-04-01',$start_time);
        $ret  = $this->datetoweek($date);

        $ret_list=[];
        foreach($ret as  $i=>$week_str) {
            if (empty($plan_list)) {
                $plan_lesson_count = 0;
            } else {
                $plan_lesson_count = $plan_list[$i-1]['plan_lesson_time']?$plan_list[$i-1]['plan_lesson_time']:0;
                $plan_lesson_count = $plan_lesson_count/100;
            }
            $ret_list[]=[  "id" =>$i, "week_title" => $week_str,  "plan_lesson_count" => $plan_lesson_count ];
        }
        // dd($ret_list);
        return $this->output_succ(['list'=>$ret_list]);
    }


    /**
     *时间转换为星期
     */
    public function datetoweek($date){
        $ret=array();
        $stimestamp   = strtotime($date);
        $mdays        = date('t',$stimestamp);
        $msdate_time  = date('m.d',$stimestamp);
        $medate       = date('Y-m-'.$mdays,$stimestamp);
        $etimestamp   = strtotime($medate);

        // 获取终止的月份
        $lasttimestamp      = strtotime('+2 months',$stimestamp);
        $lastdays           = date('t',$lasttimestamp);
        $lastedate          = date('Y-m-'.$lastdays,$lasttimestamp);
        $lastedate_time     = date('m.'.$lastdays,$lasttimestamp);
        $lasttimestampend   = strtotime($lastedate);

        //獲取第一周
        if (date('w',$stimestamp) == 0) {
            $zcsy = 0;//第一周去掉第一天還有幾天
        } else {
            $zcsy = 7-date('w',$stimestamp);//第一周去掉第一天還有幾天
        }

        $zcs1=$msdate_time;
        $next_mon = date('Y-m-d',strtotime("+$zcsy day",$stimestamp));
        $zce1=date('m.d',strtotime("+$zcsy day",$stimestamp));
        $ret[1]=$zcs1.'-'.$zce1;
        //獲取中間周次
        $jzc=0;
        //獲得當前月份是6周次還是5周次
        $jzc0="";
        $jzc6="";
        for($i=$stimestamp; $i<=$lasttimestampend; $i+=86400){
            if(date('w', $i) == 0){$jzc0++;}
            if(date('w', $i) == 6){$jzc6++;}
        }

        if($jzc0!= $jzc6)
        {
            $jzc = $jzc0-1;
        } else {
            $jzc = $jzc0;
        }

        date_default_timezone_set('PRC');
        $t = strtotime('+1 monday '.$next_mon);
        $n = 1;
        for($n=1; $n<$jzc; $n++) {
            $b = strtotime("+$n week -1 week", $t);
            $dsdate=date("m.d", strtotime("0 day", $b));
            $dedate=date("m.d", strtotime("6 day", $b));
            $jzcz=$n+1;
            $ret[$jzcz]=$dsdate.'-'.$dedate;
        }
        //獲取最後一周
        $zcsy=date('w',$lasttimestamp);//最後一周是周幾日~六 0~6
        $zcs1=date('m.d',strtotime("-$zcsy day",$lasttimestampend));
        $zce1=$lastedate_time;
        $jzcz=$jzc+1;
        $ret[$jzcz]=$zcs1.'-'.$zce1;
        return $ret;
    }

    public function qc_complaint(){
        $page_num = $this->get_in_page_num();
        $account_type = $this->get_in_int_val('account_type',-1);
        $is_complaint_state = $this->get_in_int_val('is_complaint_state', -1);
        if(!$account_type){
            $account_type = -1;
        }

        list($start_time,$end_time,$opt_date_str) = $this->get_in_date_range_month(0,0, [
            0 => array( "add_time", "投诉时间"),
            1 => array( "current_admin_assign_time", "分配时间"),
        ]);

        $ret_info = $this->t_complaint_info->get_complaint_info_for_qc($time_type=-1,$page_num,$opt_date_str,$start_time,$end_time,$is_complaint_state, $account_type );
        foreach($ret_info['list'] as $index=>&$item){

            E\Ecomplaint_type::set_item_value_str($item);
            E\Eaccount_role::set_item_value_str($item,'complained_adminid_type');
            $item['complaint_state_str'] = \App\Helper\Common::get_set_state_color_str($item['complaint_state']);
            E\Ecomplaint_user_type::set_item_value_str($item,'account_type');
            $item['deal_date']              = \App\Helper\Utils::unixtime2date($item['deal_time']);
            $item['complaint_date']                 = \App\Helper\Utils::unixtime2date($item['add_time']);
            $item['current_admin_assign_time_date'] = \App\Helper\Utils::unixtime2date($item['current_admin_assign_time']);
            $item['deal_admin_nick'] = $this->t_manager_info->get_ass_master_nick($item['deal_adminid']);


            $this->get_nick_phone_by_account_type($item['account_type'],$item);

            $current_account_arr = $this->t_complaint_assign_info->get_last_accept_adminid($item['complaint_id']);
            $current_account_last = reset($current_account_arr);

            $item['current_account'] = $current_account_last['account'];



            if ($item['current_account']) {
                $item['follow_state_str'] = '<font color="green">已分配</font>';
            } else {
                $item['follow_state_str'] = '<font color="blue">未分配</font>';
            }
        }

        // dd($ret_info);
        return $this->pageView(__METHOD__,$ret_info);
    }

    public function complaint_department_deal_teacher(){
        $this->set_in_value('account_type',2);
        return $this->complaint_department_deal();
    }

    public function complaint_department_deal_parent(){
        $this->set_in_value('account_type',1);
        return $this->complaint_department_deal();
    }

    public function complaint_department_deal_qc(){
        $this->set_in_value('account_type',3);
        return $this->complaint_department_deal();
    }

    public function complaint_department_deal_product(){
        $this->set_in_value('account_type',2);
        $this->set_in_value('complained_feedback_type',2); // 显示软件反馈类型
        return $this->complaint_department_deal();
    }


    public function complaint_department_deal(){
        $page_info    = $this->get_in_page_info();
        $account_id   = $this->get_account_id();
        $account_role = $this->get_account_role();
        $account_type = $this->get_in_int_val('account_type');
        $complained_feedback_type = $this->get_in_int_val('complained_feedback_type',-1);

        // 权限分配
        $root_id_arr = ['60','72','188','303','323','68','186','349','448','540','684','831','478','818'];
        $root_flag = in_array($account_id,$root_id_arr);

        $up_groupid = $this->t_admin_group_name->get_up_groupid_by_master_adminid($account_id);

        if($up_groupid > 0){
            $account_id_arr = $this->t_admin_group_name->get_adminid_list_by_up_groupid($up_groupid);
            $account_id_arr_tmp = [];
            if($account_id_arr){
                foreach($account_id_arr as $item){
                    $account_id_arr_tmp[] = $item['adminid'];
                }
                $account_id_str = implode(',',$account_id_arr_tmp);
            }else{
                $account_id_str = $account_id;
            }

        }else{
            $account_id_str = $account_id;
        }



        list($start_time,$end_time,$opt_date_str) = $this->get_in_date_range_month(0,0, [
            0 => array( "add_time", "投诉时间"),
            1 => array( "current_admin_assign_time", "分配时间"),
        ]);
        $ret_info   = $this->t_complaint_info->get_complaint_info_by_ass($page_info,$opt_date_str,$start_time,$end_time,$account_id_str,$account_type,$root_flag, $complained_feedback_type );


        foreach($ret_info['list'] as $index=>&$item){

            E\Ecomplaint_type::set_item_value_str($item);
            E\Eaccount_role::set_item_value_str($item,'complained_adminid_type');
            $item['complaint_state_str'] = \App\Helper\Common::get_set_state_color_str($item['complaint_state']);
            E\Ecomplaint_user_type::set_item_value_str($item,'account_type');
            $item['complaint_date']         = \App\Helper\Utils::unixtime2date($item['add_time']);
            $item['deal_date']              = \App\Helper\Utils::unixtime2date($item['deal_time']);
            $item['current_admin_assign_time_date'] = \App\Helper\Utils::unixtime2date($item['current_admin_assign_time']);
            $item['deal_admin_nick'] = $this->t_manager_info->get_ass_master_nick($item['deal_adminid']);

            $this->get_nick_phone_by_account_type($item['account_type'],$item);

            $current_account_arr = $this->t_complaint_assign_info->get_last_accept_adminid($item['complaint_id']);
            $current_account_last = reset($current_account_arr);

            $item['current_account'] = $current_account_last['account'];

            if ($item['current_account']) {
                $item['follow_state_str'] = '<font color="green">已分配</font>';
            } else {
                $item['follow_state_str'] = '<font color="blue">未分配</font>';
            }

            $item['time_consuming'] = \App\Helper\Common::secsToStr($item['deal_time']-$item['current_admin_assign_time']);

        }
        return $this->pageView(__METHOD__,$ret_info);
    }


    public function get_nick_phone_by_account_type($account_type,&$item){
        if($account_type == 1){ //家长
            $item['phone']      = $this->t_parent_info->get_phone_by_userid($item['userid']);
            $item["user_nick"]  = $this->cache_get_parent_nick ($item["userid"] );
        } elseif($account_type == 2) { // 老师
            $item["user_nick"]  = $this->cache_get_teacher_nick ($item["userid"] );
            if($item['user_nick']){
                $item['phone']      = $this->t_teacher_info->get_phone_by_nick($item['user_nick']);
            }else{
                $item['phone']      = "";
            }
        } elseif($account_type == 3) { // QC
            $item["user_nick"]  = $this->cache_get_account_nick($item["userid"] );
            $item['phone']      = $this->t_manager_info->get_operation_phone($item['userid']);
        }
    }

    public function set_refund_money(){
        $orderid     = $this->get_in_int_val("orderid");
        $apply_time  = $this->get_in_int_val("apply_time");
        $real_refund = $this->get_in_str_val("real_refund");
        $acc         = $this->get_account();

        if(!in_array($acc,["echo","jim"])){
            return $this->output_err("你没有修改退费金额的权限!");
        }

        $ret = $this->t_order_refund->field_update_list_2($orderid,$apply_time,[
            "real_refund" => ($real_refund*100)
        ]);

        if(!$ret){
            return $this->output_err("修改失败！请重试！");
        }
        return $this->output_succ();
    }

    /**
     * @author    sam
     * @function  未录入成绩学生列表
     */
    public function no_type_student_score()
    {
        list($start_time,$end_time) = $this->get_in_date_range(date("Y-m-01",time()),0,0,[],3);
        $page_num  = $this->get_in_page_num();
        $assistantid = $this->t_assistant_info->get_assistantid($this->get_account());
        if($assistantid <= 0){
            $assistantid =- 1;
            //$assistantid = 60078;
        }

        $page_info=$this->get_in_page_info();
        $ret_info = $this->t_student_info->get_no_type_student_score($page_info,$assistantid,$page_num,$start_time,$end_time);
        foreach( $ret_info["list"] as $key => &$item ) {
            $ret_info['list'][$key]['num'] = $key + 1;
            E\Esubject::set_item_value_str($item);
            $item['create_time'] = \App\Helper\Utils::unixtime2date($item['create_time'],'Y-m');
            $this->cache_set_item_student_nick($item,"userid","student_nick" );

        }
        return $this->pageView(__METHOD__,$ret_info);
    }

    /**
     * @author    sam
     * @function  助教统计学生科目数量
     */
    public function tongji_student_subject()
    {
        $this->t_student_info->switch_tongji_database();
        $ret_info = $this->t_student_info->get_studentid(); //获取学生id
        $ret_student_subject = array(
                1 => 0,
                2 => 0,
                3 => 0,
                4 => 0,
                5 => 0,
                "5科以上" => 0,
                "合计" => 0,
                "平均科目数" => 0
            );
        $sum = 0;
        foreach ($ret_info as $key => $value) {
            $num = $value['num'];
            $sum += $num;
            if($num < 6){
                ++$ret_student_subject[$num];
            }else{
                ++$ret_student_subject["5科以上"];
            }
            ++$ret_student_subject["合计"];
        }
        $ret_student_subject['平均科目数'] = round($sum / $ret_student_subject["合计"],2);

        return $this->pageView(__METHOD__,null,[
                "ret_info" => @$ret_student_subject,
        ]);
    }
     /**
     * @author    sam
     * @function  学生分数列表显示
     */
    public function  student_school_score_stat() {
        $sum_field_list=[
            "rank",
            "grade_rank",
            "rank_up",
            "rank_down"
        ];
        $order_field_arr = array_merge(["create_time"],$sum_field_list);
        list( $order_in_db_flag, $order_by_str, $order_field_name,$order_type )
            = $this->get_in_order_by_str($order_field_arr,"create_time desc");

        $username = $this->get_in_str_val("username");
        $grade    = $this->get_in_int_val("grade",-1);
        $semester = $this->get_in_int_val("semester",-1);
        $stu_score_type = $this->get_in_int_val("stu_score_type",-1);
        $page_info=$this->get_in_page_info();
        $is_test_user = 0;//1测试用户
        $ret_info=$this->t_student_score_info->get_all_list($page_info,$username,$grade,$semester,$stu_score_type,$is_test_user);
        foreach( $ret_info["list"] as $key => &$item ) {
            $ret_info['list'][$key]['num'] = $key + 1;
            \App\Helper\Utils::unixtime2date_for_item($item,"create_time","","Y/m/d");
            \App\Helper\Utils::unixtime2date_for_item($item,"stu_score_time","","Y/m/d");
            E\Esemester::set_item_value_str($item);
            E\Egrade::set_item_value_str($item);
            E\Estu_score_type::set_item_value_str($item);
    	    if($ret_info['list'][$key]['total_score']){
	          $ret_info['list'][$key]['score'] = intval(100*$ret_info['list'][$key]['score']/$ret_info['list'][$key]['total_score']);
	     }
            $this->cache_set_item_account_nick($item,"create_adminid","create_admin_nick" );
        }
        if (!$order_in_db_flag) {
            \App\Helper\Utils::order_list( $ret_info["list"], $order_field_name, $order_type );
        }
        return $this->pageView(__METHOD__, $ret_info);
    }
}
