<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;
use \App\Helper\Config;

class user_manage_new extends Controller
{
    use CacheNick;
    use TeaPower;
    var $change_num = 0;
    var $late_num   = 0;

    function __construct( $check_login_flag=true)  {
        $this->check_login_flag =$check_login_flag;
        parent::__construct();
        $this->teacher_money = \App\Helper\Config::get_config("teacher_money");
    }


    public function notify_phone()  {
        return $this->Pageview(__METHOD__);
    }

    public function ass_lesson_count_list() {
        list($start_time,$end_time) = $this->get_in_date_range( date("Y-m-01",time(NULL)) ,0 );
        $ret_list   = $this->t_lesson_info->get_confirm_lesson_list($start_time,$end_time);
        $ret_total  = $this->t_lesson_info->get_confirm_lesson_total($start_time,$end_time);
        $date_week  = \App\Helper\Utils::get_week_range($start_time,1);
        $week_total = $this->t_lesson_info->get_confirm_lesson_total($date_week["sdate"],$date_week["edate"]);

        $ret_total["assistantid"] =-1;
        $ret_total["assistant_nick"] ="全部";
        $ret_total["week_per"] = $week_total["lesson_count"]>0?round($ret_total["lesson_count"]/$week_total["lesson_count"],4)*100:0;

        foreach($ret_list['list'] as &$item ){
            $item["assistant_nick"] =$this->cache_get_assistant_nick($item["assistantid"]);
            $item["week_per"]=0;
        }
        array_unshift($ret_list["list"],$ret_total);
        foreach($ret_list['list'] as &$item ){
            $item["xs"] =$item["user_count"]>0?round($item["lesson_count"]/$item["user_count"]/100,1):" ";
        }

        return $this->Pageview(__METHOD__,$ret_list );
    }

    public function stu_lesson_count_list_ass() {
        return $this->stu_lesson_count_list();
    }

    public function stu_lesson_count_list() {
        $this->switch_tongji_database();
        $this->check_and_switch_tongji_domain();
        list($start_time,$end_time) = $this->get_in_date_range( 0 ,0,0,[],2 );
        $page_number = $this->get_in_int_val("page_number",30);
        $assistantid=$this->get_in_assistantid(-1);

        $acc = $this->get_account();
        if (\App\Helper\Utils::check_env_is_local()) {
            if ($acc=="jim") {
                $acc="lulul";
            }
        }else{
            if ($acc=="jim") {
                $acc="fly";
            }
        }

        if($assistantid == -1){
            $assistantid_before = $this->t_assistant_info->get_assistantid($acc);

            if ($assistantid_before) {
                $assistantid = $assistantid_before;
            }

        }


        $page_num=$this->get_in_page_num();

        $ret_list=$this->t_lesson_info->get_confirm_lesson_list_user($page_num,$start_time,$end_time,$assistantid, $page_number);
        $lesson_money_list = $this->t_lesson_info_b2->get_stu_lesson_money_info($start_time,$end_time);
        foreach($ret_list['list'] as &$item ){
            $this->cache_set_item_student_nick($item);
            $this->cache_set_item_assistant_nick($item);
            $item["grade"]          = E\Ebook_grade::get_desc($item["grade"]);
            $item["lesson_price"] = @$lesson_money_list[$item["userid"]]["price"];
        }
        return $this->Pageview(__METHOD__,$ret_list );
    }

    public function lesson_count_user_list() {
        $start_time         = $this->get_in_start_time_from_str(date("Y-m-01",time(NULL)) );
        $end_time           = $this->get_in_end_time_from_str(date("Y-m-d",(time(NULL)+86400)) );
        $lesson_count_start = $this->get_in_int_val("lesson_count_start",0)*100;
        $lesson_count_end   = $this->get_in_int_val("lesson_count_end",0)*100;
        $assistantid        = $this->get_in_int_val("assistantid",-1);
        $type               = $this->get_in_int_val("type",-1);
        $grade              = $this->get_in_grade();
        $page_num           = $this->get_in_page_num();

        $ret_list = $this->t_student_info->get_user_list_by_lesson_count(
            $page_num,$lesson_count_start,$lesson_count_end,$start_time,$end_time,$assistantid,$grade,$type
        );
        foreach($ret_list['list'] as &$item ){
            $item["nick"]           = $this->cache_get_student_nick($item["userid"]);
            $item["grade"]          = E\Ebook_grade::get_desc($item["grade"]);
            $item["assistant_nick"] = $this->cache_get_assistant_nick($item["assistantid"]);
            \App\Helper\Utils::unixtime2date_for_item($item,"last_lesson_time");
        }

        return $this->Pageview(__METHOD__,$ret_list );
    }

    public function tea_lesson_count_list(){
        $start_time = $this->get_in_start_time_from_str(date("Y-m-01",time(NULL)) );
        $end_time   = $this->get_in_end_time_from_str_next_day(date("Y-m-d",(time(NULL)+86400)) );
        $teacher_money_type = $this->get_in_int_val("teacher_money_type",-1);

        $ret_list = $this->t_lesson_info->get_tea_confirm_lesson_list($start_time,$end_time,$teacher_money_type);

        foreach($ret_list['list'] as &$item ){
            E\Eteacher_money_type::set_item_value_str($item);
            E\Esubject::set_item_value_str($item);
            $item['lesson_count'] /= 100;
            $item['trial_lesson_count'] /= 100;
            $item['normal_lesson_count'] /= 100;
        }

        return $this->Pageview(__METHOD__,$ret_list );
    }



    public function tea_lesson_count_detail_list() {
        $teacherid  = $this->get_in_teacherid(0);
        $studentid  = $this->get_in_studentid(-1);
        $start_time = $this->get_in_start_time_from_str(date("Y-m-01",time(NULL)-15*86400));
        $end_time   = $this->get_in_end_time_from_str_next_day(
            date("Y-m-d",(strtotime(date("Y-m-01",time(NULL)))-86400)));

        $old_list = $this->t_lesson_info->get_1v1_lesson_list_by_teacher($teacherid,$studentid,$start_time,$end_time);

        global $cur_key_index;
        $check_init_map_item = function(&$item,$key,$key_class,$value="") {
            global $cur_key_index;
            if (!isset($item[$key])) {
                $item[$key] = [
                    "value"     => $value,
                    "key_class" => $key_class."-".$cur_key_index,
                    "sub_list"  => [],
                    "data"      => array(),
                ];
                $cur_key_index++;
            }
        };

        $add_data=function(&$item,$add_item){
            $arr=&$item["data"];
            foreach  ($add_item as $k => $v ) {
                if ( !is_int($k) &&  ($k=="price" || $k=="lesson_count") ) {
                    if (!isset($arr[$k]))  {
                        $arr[$k]=0;
                    }
                    $arr[$k]+=$v;
                }
            }
        };

        $data_map=[];
        $check_init_map_item($data_map,"","");
        $price_class="";
        foreach ($old_list as $row_id=> &$item) {
            E\Eteacher_money_type::set_item_value_str($item);
            $already_lesson_count=$item["already_lesson_count"];
            //teacher level
            $level = $item["level"];
            $teacher_money_type = $item["teacher_money_type"];

            $price_class = \App\Config\teacher_price_base::get_price_class( $teacher_money_type,$level )  ;

            $diff = ($item["lesson_end"]-$item["lesson_start"])/60;
            if ($diff<=40) {
                $def_lesson_count=100;
            } else if ( $diff <= 60) {
                $def_lesson_count=150;
            } else if ( $diff <=90 ) {
                $def_lesson_count=200;
            }else{
                $def_lesson_count= ceil($diff/40)*100 ;
            }
            if ($item["lesson_count"]!=$def_lesson_count ) {
                $item["lesson_count_err"]="background-color:red;";
            }

            $lesson_count_level=$price_class::get_lesson_count_level($already_lesson_count);
            $studentid=$item["userid"];
            $item["tea_level"] = E\Elevel::get_desc( $level);
            $grade=$item["grade"];
            if($item["confirm_flag"]==2){
                $item["lesson_count"]=0;
            }

            $pre_price    = $price_class::get_price($level,$grade,$lesson_count_level) ;
            $lesson_count = $item["lesson_count"];
            if($item["lesson_type"] !=2){
                $item["price"] =$pre_price  * $lesson_count /100;
                $item["pre_price"] =$pre_price ;
            }else{ //试听  50　
                if($lesson_count>0) {
                    $item["price"] =50;
                    $item["pre_price"] =50;
                    $item["lesson_count"]=100;
                }else{
                    $item["price"] =0;
                    $item["pre_price"] =0;
                    $item["lesson_count"]=0;
                }
            }
            $lesson_count=$item["lesson_count"];
            $item['lesson_price']/=100;

            E\Egrade::set_item_value_str($item);
            E\Econfirm_flag::set_item_value_str($item);
            E\Econtract_type::set_item_value_str($item,"lesson_type");

            $item["lesson_time"]=\App\Helper\Utils::fmt_lesson_time($item["lesson_start"], $item["lesson_end"]);

            $key0_map=&$data_map[""];
            $check_init_map_item($key0_map["sub_list"] , $studentid,"key1" );
            $add_data($key0_map, $item );

            $key1_map=&$key0_map["sub_list"][$studentid];
            $check_init_map_item($key1_map["sub_list"] , $lesson_count_level,"key2" );
            $add_data($key1_map, $item );

            $key2_map=&$key1_map["sub_list"][$lesson_count_level];
            $check_init_map_item($key2_map["sub_list"] ,$row_id,"key3" );
            $add_data($key2_map, $item );

            $key3_map=&$key2_map["sub_list"][$row_id];
            $key3_map["data"]=$item;
        }

        if( $price_class) {
            $level_desc_map = $price_class::gen_level_name_config();
        }

        $list=[];
        if (count($old_list)>0) {
            foreach ($data_map as  $studentid=> $item0 ) {
                $data=$item0["data"];
                $data["key1"]="全部";
                $data["key2"]="";
                $data["key3"]="";
                $data["key1_class"]="";
                $data["key2_class"]="";
                $data["key3_class"]="";
                $data["level"]="l-0";
                $list[]=$data;

                foreach ( $item0["sub_list"] as $key1 => $item1 ) { // student
                    $data=$item1["data"];
                    $data["key1"]=$key1;
                    $data["key2"]="";
                    $data["key3"]="";
                    $data["key1_class"]=$item1["key_class"];
                    $data["key2_class"]="";
                    $data["key3_class"]="";
                    $data["level"]="l-1";
                    $data["stu_nick"]=$this->cache_get_student_nick($key1);

                    $list[]=$data;
                    foreach( $item1["sub_list"] as $key2=> $item2 ) { //lesson_count_level
                        $data=$item2["data"];
                        $data["key1"]=$key1;
                        $data["key2"]=$key2;
                        $data["lesson_count_level_str"]= $level_desc_map[$key2];
                        $data["key3"]="";
                        $data["key1_class"]=$item1["key_class"];
                        $data["key2_class"]=$item2["key_class"];
                        $data["key3_class"]="";
                        $data["level"]="l-2";

                        $list[]=$data;
                        foreach ( $item2["sub_list"] as $key3=> $item3  ) {
                            $data=$item3["data"];
                            $data["key1"]=$key1;
                            $data["key2"]=$key2;
                            $data["key3"]=$key3;
                            $data["key1_class"]=$item1["key_class"];
                            $data["key2_class"]=$item2["key_class"];
                            $data["key3_class"]=$item3["key_class"];
                            $data["level"]="l-3";
                            $data["lesson_count_level_str"]="-";

                            $list[]=$data;
                        }
                    }
                }
            }
        }
        $ret_list=\App\Helper\Utils::list_to_page_info($list);

        return $this->Pageview(__METHOD__,$ret_list );
    }

    public function get_tea_lesson_money_list(){
        $teacherid  = $this->get_in_teacherid(0);
        $studentid  = $this->get_in_studentid(-1);
        $start_time = $this->get_in_start_time_from_str(date("Y-m-01",time(NULL) -15*86400) );
        $end_time   = $this->get_in_end_time_from_str_next_day(
            date("Y-m-d",(strtotime(date("Y-m-01",time(NULL)))-86400)));
        $lesson_list=$this->t_lesson_info->get_1v1_lesson_list_by_teacher($teacherid,$studentid,$start_time,$end_time);
    }

    /**
     * 老师工资计算,如果变动某些固定的工资或扣款,在 config/admin.php 文件中更改 teacher_money 的数值
     */
    public function tea_wages_info(){
        list($start_time, $end_time) = $this->get_in_date_range(date("Y-m-01",strtotime("-1 month",time())),0, 0,[],3 );
        $teacherid = $this->get_in_teacherid(0);
        $studentid = $this->get_in_int_val("studentid",-1);
        $show_type = $this->get_in_str_val("show_type","current");

        if($teacherid==0){
            $ret_list=\App\Helper\Utils::list_to_page_info([]);
            return $this->Pageview(__METHOD__,$ret_list);
        }

        $teacher_type = $this->t_teacher_info->get_teacher_type($teacherid);
        $old_list     = $this->t_lesson_info->get_lesson_list_for_wages(
            $teacherid,$start_time,$end_time,$studentid,$show_type
        );

        //拉取上个月的课时信息
        $last_month_info          = $this->get_last_lesson_count_info($start_time,$end_time,$teacherid);
        $last_all_lesson_count    = $last_month_info['all_lesson_count'];
        $last_normal_lesson_count = $last_month_info['all_normal_count'];

        global $cur_key_index;
        $check_init_map_item = function(&$item,$key,$key_class,$value="") {
            global $cur_key_index;
            if (!isset($item[$key])) {
                $item[$key] = [
                    "value"     => $value,
                    "key_class" => $key_class."-".$cur_key_index,
                    "sub_list"  => [],
                    "data"      => [],
                ];
                $cur_key_index++;
            }
        };

        $add_data = function(&$item,$add_item){
            $arr  = &$item["data"];
            foreach($add_item as $k => $v){
                if (!isset($arr[$k])) {
                    $arr[$k]="";
                }
                if ($k=="price" || $k=="lesson_count" || $k=="lesson_reward" || $k=="lesson_full_reward") {
                    $arr[$k] += $v;
                }
                if($k=="lesson_cost"){
                    $arr[$k] -= $v;
                }
            }
        };

        $data_map     = [];
        $lesson_total_arr = [
            "trial_total"  => 0,
            "normal_total" => 0,
        ];
        $all_price = 0;
        $check_num = [];
        $check_init_map_item($data_map,"","");
        foreach ($old_list as $row_id => &$item) {
            $studentid    = $item["userid"];
            $grade        = $item["grade"];
            $pre_price    = $this->get_teacher_base_money($teacherid,$item);
            $lesson_count = $item["lesson_count"];

            //判断课程的老师类型来设置累计课时的数值
            $check_type = \App\Helper\Utils::check_teacher_money_type($item['teacher_money_type'],$teacher_type);
            switch($check_type){
            case 1: case 3:
                $already_lesson_count = $item['already_lesson_count'];
                break;
            case 2:
                $already_lesson_count = $last_all_lesson_count;
                break;
            case 4:
                $already_lesson_count = $last_normal_lesson_count;
                break;
            default:
                $already_lesson_count = 0;
                break;
            }

            if($item['type']!=0){
                $rule_type = \App\Config\teacher_rule::get_teacher_rule($item['type']);
            }else{
                $rule_type = [];
            }

            if(!empty($rule_type)){
                $i=0;
                $lesson_count_level = count($rule_type);
                foreach($rule_type as $key=>$val){
                    $i++;
                    if($already_lesson_count<$key){
                        $lesson_count_level=$key==0?$i:($i-1);
                        break;
                    }
                }
            }else{
                $lesson_count_level = 1;
            }

            $def_lesson_count = \App\Helper\Utils::get_lesson_count($item['lesson_start'],$item['lesson_end']);
            if ($lesson_count != $def_lesson_count ) {
                $item["lesson_count_err"] = "background-color:red;";
            }

            $item['lesson_full_reward'] = \App\Helper\Utils::get_lesson_full_reward($item['lesson_full_num']);
            $this->get_lesson_cost_info($item,$check_num,"admin");

            if($item['confirm_flag']==2){
                $item['lesson_price'] = 0;
                $item['pre_reward']   = 0;
                $item['price']        = 0;
            }else{
                $item['lesson_price']  /= 100;
                if($item["lesson_type"]!=2){
                    \App\Helper\Utils::check_isset_data($lesson_total_arr['normal_total'],$item['lesson_count']);
                    $item['pre_reward'] = \App\Helper\Utils::get_teacher_lesson_money($item['type'],$already_lesson_count);
                    $item["price"]      = ($pre_price+$item['pre_reward'])*$lesson_count/100
                                        +$item['lesson_full_reward']
                                        -$item['lesson_cost'];
                    $item["pre_price"] = $pre_price;
                }else{
                    \App\Helper\Utils::check_isset_data($lesson_total_arr['trial_total'],$item['lesson_count']);
                    $item['pre_reward'] = 0;
                    if($lesson_count>0) {
                        $trial_base = \App\Helper\Utils::get_trial_base_price(
                            $item['teacher_money_type'],$item['teacher_type'],$item['lesson_start']
                        );

                        $item["price"]        = $trial_base+$item['lesson_full_reward']-$item['lesson_cost'];
                        $item["pre_price"]    = $trial_base;
                        $item["lesson_count"] = 100;
                    }else{
                        $item["price"]        = 0;
                        $item["pre_price"]    = 0;
                        $item["lesson_count"] = 0;
                    }
                }
            }
            $all_price += $item['price'];

            $item['lesson_reward'] = $item['pre_reward']*$lesson_count/100;
            $item['tea_level_num'] = $item['level'];
            $item['tea_level'] = \App\Helper\Utils::get_teacher_letter_level($item['teacher_money_type'],$item['level']);
            E\Egrade::set_item_value_str($item);
            E\Esubject::set_item_value_str($item);
            E\Econfirm_flag::set_item_value_str($item);
            E\Econtract_type::set_item_value_str($item,"lesson_type");
            if ($item['lesson_type'] != 2) {
                $item['lesson_type_str'] = '常规';
            }
            E\Eteacher_money_type::set_item_value_str($item);

            $item["lesson_time"] = \App\Helper\Utils::fmt_lesson_time($item["lesson_start"], $item["lesson_end"]);

            $key0_map = &$data_map[""];
            $check_init_map_item($key0_map["sub_list"] , $studentid,"key1");
            $add_data($key0_map,$item);

            $key1_map=&$key0_map["sub_list"][$studentid];
            $check_init_map_item($key1_map["sub_list"] ,$lesson_count_level,"key2",$item['type'] );
            $add_data($key1_map, $item );

            $key2_map=&$key1_map["sub_list"][$lesson_count_level];
            $check_init_map_item($key2_map["sub_list"] ,$row_id,"key3");
            $add_data($key2_map, $item );

            $key3_map=&$key2_map["sub_list"][$row_id];
            $key3_map["data"]=$item;
        }

        $list=[];
        if (count($old_list)>0) {
            foreach ($data_map as  $studentid=> $item0 ) {
                $data=$item0["data"];
                $data["key1"]="全部";
                $data["key2"]="";
                $data["key3"]="";
                $data["key1_class"]="";
                $data["key2_class"]="";
                $data["key3_class"]="";
                $data["level"]="l-0";
                $list[]=$data;

                foreach ( $item0["sub_list"] as $key1=> $item1  ) { // student
                    $data=$item1["data"];
                    $data["stu_nick"]=$this->cache_get_student_nick($key1);
                    $data["key1"]=$key1;
                    $data["key2"]="";
                    $data["key3"]="";
                    $data["key1_class"]=$item1["key_class"];
                    $data["key2_class"]="";
                    $data["key3_class"]="";
                    $data["level"]="l-1";
                    $list[]=$data;

                    foreach ( $item1["sub_list"] as $key2 => $item2 ) { //lesson_count_level
                        $data=$item2["data"];
                        $data["key1"]=$key1;
                        $data["key2"]=$key2;

                        if($item2['value']>0){
                            $lesson_count_range = \App\Config\teacher_rule::get_teacher_lesson_count_range($item2['value']);
                            $data["lesson_count_level_str"] = $lesson_count_range[$key2];
                        }else{
                            $data["lesson_count_level_str"] = "未确认";
                        }

                        $data["key3"]       = "";
                        $data["key1_class"] = $item1["key_class"];
                        $data["key2_class"] = $item2["key_class"];
                        $data["key3_class"] = "";
                        $data["level"]      = "l-2";

                        $list[]=$data;
                        foreach ( $item2["sub_list"] as $key3=> $item3  ) {
                            $data=$item3["data"];
                            $data["key1"]=$key1;
                            $data["key2"]=$key2;
                            $data["key3"]=$key3;
                            $data["key1_class"]=$item1["key_class"];
                            $data["key2_class"]=$item2["key_class"];
                            $data["key3_class"]=$item3["key_class"];
                            $data["level"]="l-3";
                            $data["lesson_count_level_str"]="-";

                            $list[] = $data;
                        }
                    }
                }
            }
        }

        $teacher_reward = ($this->t_teacher_money_list->get_teacher_honor_money($teacherid,$start_time,$end_time,0))/100;
        $all_price += $teacher_reward;
        $ret_list = \App\Helper\Utils::list_to_page_info($list);
        return $this->Pageview(__METHOD__,$ret_list,[
            "teacherid"      => $teacherid,
            "lesson_count"   => $lesson_total_arr,
            "teacher_reward" => $teacher_reward,
            "all_price"      => $all_price,
        ]);
    }

    public function ass_contract_list () {
        list($start_time, $end_time, $opt_date_str )= $this->get_in_date_range_month(0,0, [
            0 => array( "order_time", "合同创建时间"),
            1 => array("ass_assign_time","分配助教时间"),
            2 => array("master_assign_time","分配助教助长时间"),
        ]);

        $contract_status  = -2;
        $config_courseid  = -1;
        $is_test_user     = 0;
        $studentid        = $this->get_in_studentid(-1);
        $check_money_flag = $this->get_in_int_val("check_money_flag", -1);
        $have_init        = $this->get_in_int_val("have_init", -1);
        $have_master      = $this->get_in_int_val("have_master", -1);
        $assistantid      = $this->get_in_int_val("assistantid", -1);
        $page_num         = $this->get_in_page_num();
        $has_money        = -1;
        $contract_type    = $this->get_in_int_val(  'contract_type', -2 );
        $account          = $this->get_account();
        $show_yueyue_flag = false;
        $sys_operator_uid = $this->get_in_int_val("sys_operator_uid", -1);

        $account_id = $this->get_account_id();
        $main_type = 1;
        $is_master = $this->t_admin_main_group_name->check_is_master($main_type,$account_id);
        if($is_master>0 || $account_id==349 || $account_id==60){
            $up_master_adminid = -1;
        }else{
            $up_master_adminid = 0;
        }

        $ret_list = $this->t_order_info->get_order_list($page_num,$start_time,$end_time,$contract_type,$contract_status,$studentid,$config_courseid,$is_test_user, $show_yueyue_flag, $has_money,$check_money_flag ,$assistantid,"",-1,"",-1,-1,-1,-1,-1,-1,$up_master_adminid,$account_id, [],  -1, $opt_date_str," t2.assistantid asc , order_time desc",$have_init,$have_master,$sys_operator_uid);

        $money_all=0;

        foreach($ret_list['list'] as &$item ){
            $item['is_new_stu'            ] = E\Eboolean::get_desc( $item['is_new_stu']) ;
            $item['grade'                 ] = E\Egrade::get_desc($item["grade"] );
            $item['contract_status'       ] = E\Econtract_status::get_desc($item["contract_status"]);
            E\Econtract_type::set_item_value_str($item);
            $item['contract_starttime'    ] = $item['contract_starttime'] ?  date("Y-m-d",$item['contract_starttime']):"无";
            $item['contract_endtime'      ] = date("Y-m-d",$item['contract_endtime']);
            E\Efrom_type::set_item_value_str($item);
            $item['price'] = $item['price']/100;
            E\Echeck_money_flag::set_item_value_str($item);
            //$item["check_money_admin_nick"] = $this->cache_get_account_nick( $item["check_money_adminid"] );
            \App\Helper\Utils::unixtime2date_for_item($item,"check_money_time");

            $item['assistant_nick']  = $this->cache_get_assistant_nick($item["assistantid"]);
            $item["ass_assign_time"]       = $item["ass_assign_time"]==0?'未分配':date('Y-m-d H:i:s',$item["ass_assign_time"]);
            $item["master_assign_time_str"]       = $item["master_assign_time"]==0?'未分配':date('Y-m-d H:i:s',$item["master_assign_time"]);
            if (!$item["stu_nick"]) {
                $item["stu_nick"] = $item["stu_self_nick"] ;
            }

            if(!empty($item["origin_userid"])){
                $item["origin_user_nick"] = $this->t_student_info->get_nick($item["origin_userid"]);
                $item["origin_assid"] = $this->t_student_info->get_assistantid($item["origin_userid"]);
                $item["origin_ass_nick"] = $this->t_assistant_info->get_nick($item["origin_assid"]);
            }
            $money_all += $item["price"];

            if ($item["init_info_pdf_url"]=="") {
                $item["init_info_pdf_url_str"]="";
            }else{
                $item["init_info_pdf_url_str"]="有";
            }

            // 处理交接单状态

            $hand_over_num = $this->t_student_cc_to_cr->get_hand_over_stat_by_orderid($item['orderid']);

            $last_reject_flag = $this->t_student_cc_to_cr->get_last_id_reject_flag_by_orderid($item['orderid']);

            if($last_reject_flag['reject_flag']==0 && $last_reject_flag['id']>0  ){
                $item['is_submit_str'] = '<font color="green">CC已处理(新版)</font>';
            }elseif($last_reject_flag['reject_flag']==1 ){
                $item['is_submit_str'] = '<font color="red">CC未处理</font>';
            }elseif(!$last_reject_flag && $item["init_info_pdf_url"]){
                $item['is_submit_str'] = '<font color="blue">CC已处理(旧版)</font>';
            }

            if($hand_over_num){
                if($last_reject_flag['reject_flag'] == 0){
                    $item['reject_num'] = $hand_over_num-1;
                }elseif($last_reject_flag['reject_flag'] == 1){
                    $item['reject_num'] = $hand_over_num;
                }
                $item['modify_num'] = $hand_over_num-1;
            }else{
                $item['reject_num'] = 0;
                $item['modify_num'] = 0;
            }

        }

        return $this->Pageview(__METHOD__,$ret_list, [
            "money_all"=>$money_all,
            "account_id" =>$account_id
        ]);


    }

    public function money_contract_list_stu(){
        //$this->set_filed_for_js("userid_flag",999);
        return $this->money_contract_list();
    }

    public function money_contract_list () {
        $start_time      = $this->get_in_start_time_from_str(date("Y-m-d",(time(NULL)-86400*7)) );
        $end_time        = $this->get_in_end_time_from_str(date("Y-m-d",(time(NULL)+86400)) );
        $userid_flag     = $this->get_in_int_val("userid_flag",-1);
        $contract_type   = $this->get_in_int_val("contract_type",-2);
        $contract_status = $this->get_in_el_contract_status();

        $config_courseid = -1;
        $is_test_user    = $this->get_in_int_val("is_test_user", 0 , E\Eboolean::class  );
        $can_period_flag = $this->get_in_int_val("can_period_flag",-1);
        $studentid       = $this->get_in_studentid(-1);

        $check_money_flag = $this->get_in_int_val("check_money_flag", -1);
        $origin           = $this->get_in_str_val("origin");
        $page_num         = $this->get_in_page_num();
        $from_type        = $this->get_in_int_val("from_type",-1);
        $account_role     = $this->get_in_int_val("account_role",-1);
        $has_money        = -1;
        $sys_operator     = $this->get_in_str_val("sys_operator","");
        $need_receipt     = $this->get_in_int_val("need_receipt", -1, E\Eboolean::class);

        $account=$this->get_account();
        $show_yueyue_flag = false;
        if ($account =="yueyue" || $account=="jim" || $account=="echo" ) {
            $show_yueyue_flag= true;
        }
        //$show_yueyue_flag= true;

        $this->set_in_value("userid_stu",$studentid);
        $userid_stu   = $this->get_in_int_val("userid_stu");

        $ret_list=$this->t_order_info->get_order_list(
            $page_num,$start_time,$end_time,$contract_type,$contract_status,
            $studentid,$config_courseid,$is_test_user, $show_yueyue_flag, $has_money,
            $check_money_flag,-1,$origin,$from_type,$sys_operator,
            $account_role, -1,-1,-1, $need_receipt, -1, -1, 74 , [], -1, "order_time",
            "order_time desc",-1,-1,-1,$can_period_flag);
        $money_all   = 0;
        $order_count = 0;
        $userid_map  = [];
        foreach($ret_list['list'] as &$item ){
            $item["can_period_flag_str"] = \App\Helper\Common::get_boolean_color_str( $item["can_period_flag"]);
            if(empty($item["lesson_start"]) && $item["order_time"] < strtotime(date("2016-11-01")) && $item["contract_type"]==0){
                $userid= $item["userid"];
                $item["lesson_start"] = $this->t_lesson_info->get_user_test_lesson_start($userid,$item["order_time"]);
            }
            $lesson_start= $item["lesson_start"];
            $check_time=strtotime( date("Y-m-d",$lesson_start)) +86400*2;
            $item["order_time_1_day_flag"]= ($item["order_time"] <$check_time);
            $item["check_money_time_1_day_flag"]= ($item["check_money_time"] <$check_time);

            E\Eboolean::set_item_value_str($item,"order_time_1_day_flag");
            E\Eboolean::set_item_value_str($item,"check_money_time_1_day_flag");
            E\Efrom_parent_order_type::set_item_value_str($item);

            $userid_map[$item["userid"]]=true;
            $item['price']= $item['price']/100;
            E\Eboolean::set_item_value_str($item,"order_stamp_flag");
            E\Eboolean::set_item_value_str($item,"is_invoice");
            $item['contract_status'] = E\Econtract_status::get_desc($item["contract_status"]);
            $item['contract_starttime'] = $item['contract_starttime'] ?  date("Y-m-d",$item['contract_starttime']):"无";
            $item['contract_endtime'] = date("Y-m-d",$item['contract_endtime']);

            E\Egrade::set_item_value_str($item);
            E\Econtract_type::set_item_value_str($item);
            E\Econtract_from_type::set_item_value_str($item,"stu_from_type");
            E\Efrom_type::set_item_value_str($item);
            E\Echeck_money_flag::set_item_value_str($item);
            E\Ecompetition_flag::set_item_value_str($item);
            $item["check_money_admin_nick"]= $this->cache_get_account_nick( $item["check_money_adminid"] );
            E\Eorder_promotion_type::set_item_value_str($item);
            if (!$item["stu_nick"]) {
                $item["stu_nick"]=$item["stu_self_nick"];
            }

            \App\Helper\Utils::unixtime2date_for_item($item,"check_money_time");
            \App\Helper\Utils::unixtime2date_for_item($item,"order_time");
            \App\Helper\Utils::unixtime2date_for_item($item,"lesson_start");
            \App\Helper\Common::set_item_enum_flow_status($item);
            $money_all+=$item["price"];
            $order_count++;
            if($item["pre_price"]==0){
                $item["pre_status"]="无定金";
            }else{
                if($item["pre_pay_time"]>0){
                    $item["pre_status"]="定金已支付";
                }else{
                    $item["pre_status"]="定金未支付";
                }
            }
            // $item["is_staged_flag_str"] = \App\Helper\Common::get_boolean_color_str($item["is_staged_flag"]);
        }

        return $this->Pageview(__METHOD__,$ret_list, [
            "money_all"   => $money_all,
            "order_count" => $order_count,
            "user_count"  => count($userid_map),
            "userid_flag" => $userid_flag
        ]);

    }

    //财务管理-支付信息
    public function get_order_channel_from_orderno_info(){
        $page_info = $this->get_in_page_info();
        list($start_time,$end_time,$opt_date_type)=$this->get_in_date_range(0,0,2,[
            1 => array("order_time","下单日期"),
            2 => array("c.pay_time","支付日期")
        ],3);

        $contract_type     = $this->get_in_int_val('contract_type',-1);
        $channel_origin    = $this->get_in_int_val('channel_origin',-1);
        $channel           = $this->get_in_int_val('channel',-1);
        $name_str           = $this->get_in_str_val('name_str',"");
        $ret_info = $this->t_child_order_info->get_all_order_channel_info($page_info,$start_time,$end_time,$opt_date_type,$contract_type, $channel_origin,$channel,$name_str);
        foreach($ret_info["list"] as &$item){
            E\Egrade::set_item_value_str($item);
            E\Esubject::set_item_value_str($item);
            E\Econtract_type::set_item_value_str($item);
            \App\Helper\Utils::unixtime2date_for_item($item,"order_time","_str");
            \App\Helper\Utils::unixtime2date_for_item($item,"pay_time","_str");
                   
            list($item["channel"],$item["channel_origin"])=$this->get_pay_channel_origin($item["channel"]);
            $item["lesson_total"]= $item["lesson_total"]*$item["default_lesson_count"]/100;
           
 
        }
        return $this->Pageview(__METHOD__,$ret_info);

 
        
    }
    private function gen_class($level) {
        global $g_l_id;
        if(!$g_l_id) {
            $g_l_id=0;
        }
        $g_l_id++;
        return "n_{$level}_$g_l_id";

    }

    private function get_menu_power_list ($power_map, $menu ,$start) {
        $list=[];
        $gen_n=function($item,$pid,$k_class,$level,$class_level_fix )use( $power_map) {
            $n             = ["k1"=>"----","k2"=> $level>=2?"----":"","k3"=> $level>=3?"----":"" ];
            $n["k$level" ] = $item["name"] ;
            $n["folder" ]  = isset($item["list"] ) ;
            if ( $n["folder" ]) {
                $n["pid" ]= 0;
            }else{
                $n["pid" ]= $pid;
            }
            $n["k_class" ]       = $k_class;
            $n["class" ]         = "l_$level $k_class $class_level_fix " ;
            $n["level" ]         = $level ;
            $n["has_power_flag"] = isset($power_map[$pid])?"checked":"";
            $n["url"]            = @$item["url"] ;
            return $n;
        };

        foreach ($menu as $k1_item )  {
            $k1_pid=0;
            if ( isset($k1_item["power_id"])) {
                $k1_pid=$start+$k1_item["power_id"] *10000;
            }
            $k1_class= $this->gen_class(1);
            $n=$gen_n($k1_item,$k1_pid,$k1_class,1,"" );

            $list[]=$n;

            if (isset($k1_item["list"] )) {
                foreach ( $k1_item["list"]  as $k2_item) {
                    $k2_pid=0;
                    if ( isset($k2_item["power_id"])) {
                        $k2_pid=$k1_pid +$k2_item["power_id"] *100;
                    }
                    $k2_class= $this->gen_class(2);
                    $n=$gen_n($k2_item,$k2_pid,$k2_class,2 , "$k1_class" );
                    $list[]=$n;

                    if (isset($k2_item["list"] )) {
                        foreach ( $k2_item["list"]  as $k3_item) {
                            $k3_pid=0;
                            if ( isset($k3_item["power_id"])) {
                                $k3_pid=$k2_pid +$k3_item["power_id"] ;
                            }
                            $k3_class= $this->gen_class(3);
                            $n=$gen_n($k3_item,$k3_pid,$k3_class,3, "$k1_class $k2_class"  );
                            $list[]=$n;
                        }
                    }
                }
            }
        }
        return $list;
    }

    public function get_menu_list($power_map)  {
        $start    = 1000000;
        $menu     = \App\Helper\Config::get_menu();
        $stu_menu = \App\Helper\Config::get_stu_menu();
        $tea_menu = \App\Helper\Config::get_tea_menu();


        $list=$this->get_menu_power_list($power_map,$menu,$start );
        $sub_menu=[
            ["power_id"=>1, "name"=>"子栏-学生信息", "list"=> $stu_menu],
            ["power_id"=>2, "name"=>"子栏-老师信息", "list"=> $tea_menu],
        ];
        $sub_list=$this->get_menu_power_list ($power_map,$sub_menu,$start*2 );

        $class_list=$this->get_menu_power_list($power_map,\App\ClassMenu\menu::get_config()  ,$start*3 );

        return array_merge($list, $class_list ,$sub_list);
    }

    public function get_menu_list_new($power_map) {
        $start = 1000000;
        $menu =\App\Helper\Config::get_menu();
        foreach($menu as &$item) {
            $item['start'] = $start;
        }
        $stu_menu = \App\Helper\Config::get_stu_menu();
        $tea_menu = \App\Helper\Config::get_tea_menu();

        $sub_menu=[
            ["power_id"=>1, "name"=>"子栏-学生信息", "list"=> $stu_menu],
            ["power_id"=>2, "name"=>"子栏-老师信息", "list"=> $tea_menu],
        ]; // * 2
        foreach($sub_menu as &$item) {
            $item['start'] = $start * 2;
        }

        $class_list=\App\ClassMenu\menu::get_config(); // * 3
        foreach($class_list as &$item) {
            $item['start'] = $start * 3;
        }


        $menu = array_merge($menu, $sub_menu, $class_list);

        $len = count($menu);
        $i = 1;
        $info = [];
        foreach($menu as &$item) { // 生成树对应的数据
            $k1 = $i;
            $item['id'] = $k1;
            $item['pId'] = 0;
            $i ++;
            $page_id = $item['start'] + $item['power_id'] * 10000;
            $item['page_id'] = $page_id;
            if (isset($item['list'])) {
                $item['page_id'] = 0;
            }
            $item['name'] .= ' - '.$item['page_id'];
            $check1 = false;
            if (isset($power_map[$page_id]) && $power_map[$page_id]) { $check1 = true;}
            $item['checked'] = $check1;
            //unset($item['url']);
            //$info[] = $item;
            if (isset($item['list'])) {
                $len1 = count($item['list']);
                $j = 0;
                foreach($item['list'] as &$item2) {
                    $k2 = $i;
                    $item2['id'] = $k2;
                    $item2['pId'] = $k1;
                    $page_id2 = $page_id + $item2['power_id'] * 100;
                    $item2['page_id'] = $page_id2;
                    if (isset($item2['list'])) {
                        $item2['page_id'] = 0;
                    }
                    $item2['name'] .= ' - '.$item2['page_id'];
                    $check2 = false;
                    if (isset($power_map[$page_id2]) && $power_map[$page_id2]) {
                        $check2 = true;
                        $j ++;
                    }
                    $item2['checked'] = $check2;
                    //unset($item2['url']);
                    //$info[] = $item2;
                    $i ++;
                    if (isset($item2['list'])) {
                        $len2 = count($item2['list']);
                        $k = 0;
                        foreach($item2['list'] as &$item3) {
                            $k3 = $i;
                            $item3['page_id'] = $page_id2 + $item3['power_id'];
                            $item3['name'] .= ' - '.$item3['page_id'];
                            $item3['id'] = $k3;
                            $item3['pId'] = $k2;
                            $check3 = false;
                            //echo $item['']
                            if (isset($power_map[$item3['page_id']]) && $power_map[$item3['page_id']]) {
                                $check3 = true;
                                $k ++;
                            }
                            $item3['checked'] = $check3;
                            //unset($item3['url']);
                            $info[] = $item3;
                            $i ++;
                            if ($len2 == $k) {
                                $item2['checked'] = true;
                                $j ++;
                            }
                        }
                    }
                    if ($len1 == $j) {
                        $item['checked'] = true;
                    }
                    $info[] = $item2;
                }

            }
            $info[] = $item;
        }
        //dd($info);
        $key = $i;
        $info[] = ['id' => $key, 'pId' => 0, 'name' => '其它 - 0'];
        $i ++;
        foreach(E\Epower::$desc_map as $k => $v) {
            $check = false;
            if (isset($power_map[$k]) && $power_map[$k]) { $check = true;}
            $info[] = ['id' => $i, 'pId' => $key, 'name' => $v.' - '.$k.'    配置权限', 'page_id' => $k, 'checked' => $check];
            $i ++;
        }
        return $info;
    }

    public function get_tea_admin_menu_list($power_map)  {
        $start          = 1000000;
        $tea_admin_menu = \App\Helper\Config::get_tea_admin_menu();
        $list=$this->get_menu_power_list($power_map,$tea_admin_menu,$start );
        return $list;
    }

    public function admin_group_edit() {
        $main_type=$this->get_in_int_val("main_type",2);
        $group_list=$this->t_admin_group_name->get_group_list($main_type);
        $default_groupid=0;
        if (count( $group_list)>0) {
            $default_groupid=$group_list[0]["groupid"];
        }
        $groupid=$this->get_in_int_val("groupid", -1);
        if ($groupid>0 ) {
            $db_main_type= $this->t_admin_group_name->get_main_type($groupid);
            if ($db_main_type != $main_type) {
                $groupid= -1;
            }
        }

        if ($groupid==-1)  {
            $this->set_in_value("groupid", $default_groupid );
            $groupid=$this->get_in_int_val("groupid", -1);
        }

        $ret_info=$this->t_admin_group_user->get_user_list($groupid);
        foreach( $ret_info["list"] as &$item ) {
            $this->cache_set_item_account_nick($item);
            if(!empty($item['assign_percent'])){
                $item["assign_percent"] =  $item["assign_percent"]."%";
            }
        }
        $group_master_nick="";
        $group_master_adminid=0;
        if ($groupid>0) {//
            $group_master_adminid= $this->t_admin_group_name->get_master_adminid($groupid);
            $group_master_nick= $this->cache_get_account_nick($group_master_adminid);
        }

        return $this->pageView(__METHOD__, $ret_info,
                               ["group_list"=> $group_list,
                                "group_master_nick" => $group_master_nick,
                                "group_master_adminid" => $group_master_adminid
        ]);
    }

    public function admin_main_group_edit() {
        $main_type=$this->get_in_int_val("main_type",2);
        $group_list=$this->t_admin_main_group_name->get_group_list($main_type);
        $default_groupid=0;
        if (count( $group_list)>0) {
            $default_groupid=$group_list[0]["groupid"];
        }
        $groupid=$this->get_in_int_val("groupid", -1);
        if ($groupid>0 ) {
            $db_main_type= $this->t_admin_main_group_name->get_main_type($groupid);
            if ($db_main_type != $main_type) {
                $groupid= -1;
            }
        }

        if ($groupid==-1)  {
            $this->set_in_value("groupid", $default_groupid );
            $groupid=$this->get_in_int_val("groupid", -1);
        }

        $ret_info=$this->t_admin_main_group_name->get_user_list($groupid,$main_type);
        foreach( $ret_info["list"] as &$item ) {
            $item['master_nick']= $this->cache_get_account_nick($item['master_adminid']);
            if(!empty($item['group_assign_percent'])){
               $item['group_assign_percent'] = $item['group_assign_percent']."%";
            }
        }

        $group_master_nick="";
        $group_master_adminid=0;
        if ($groupid>0 ) {//
            $group_master_adminid= $this->t_admin_main_group_name->get_master_adminid($groupid);
            $group_master_nick= $this->cache_get_account_nick($group_master_adminid);
        }
        return $this->pageView(__METHOD__, $ret_info,
                               ["group_list"=> $group_list,
                                "group_master_nick" => $group_master_nick,
                                "group_master_adminid" => $group_master_adminid,
                               ]);
    }

    public function admin_main_assign_percent_edit(){
        $ret_info=$this->t_admin_main_group_name->get_group_list(2);
        foreach( $ret_info as &$item ) {
            $item['master_nick']= $this->cache_get_account_nick($item['master_adminid']);
            if(!empty($item['main_assign_percent'])){
                $item['main_assign_percent'] = $item['main_assign_percent']."%";
            }
        }

        return $this->pageView(__METHOD__,\App\Helper\Utils::list_to_page_info($ret_info));

    }
    public function seller_attendance_info(){
        list($start_time,$end_time)=$this->get_in_date_range(0,0,0,[],1);
        $account     = trim($this->get_in_str_val('account',''));
        $seller_work_status = $this->get_in_int_val('seller_work_status',-1);
        $plan_seller_work_status = $this->get_in_int_val('plan_seller_work_status',-1);
        $day = date('Y-m-d',$start_time);
        $month = date('Y-m-01',$start_time);
        $ret_info = $this->t_admin_group_name->get_seller_admin_info(2,$account,$month);
        $plan_work_count = $real_work_count =$leave_count =$overtime_count =  0;
        foreach($ret_info["list"] as $k=>&$item){
            $month_time = json_decode($item['month_time'],true);
            if(!empty($month_time)){
                foreach($month_time as $val){
                    if(substr($val[0],0,10)==$day ){
                        $item["plan_do"] = substr($val[0],11,1);
                    }
                }
                $leave_and_overtime = json_decode($item['leave_and_overtime'],true);
                if(!empty($leave_and_overtime)){
                    foreach($leave_and_overtime as $v){
                        if(substr($v[0],0,10) ==$day ){
                            $item["real_do"] = substr($v[0],11,1);
                        }
                    }
                }

                if(!isset($item["real_do"])){
                    $item["real_do"] = $item["plan_do"];
                }

            }
            $item["plan_do"] = isset($item["plan_do"])?$item["plan_do"]:0;
            $item["real_do"] = isset($item["real_do"])?$item["real_do"]:0;
            if($item["plan_do"] ==1){
                $plan_work_count++;
            }
            if($item["real_do"] ==1 || $item["real_do"] ==3){
                $real_work_count++;
            }
            if($item["real_do"] ==2){
                $leave_count++;
            }
            if($item["real_do"] ==3){
                $overtime_count++;
            }


            if($seller_work_status==0){
                if($item["real_do"] !=0){
                    unset($ret_info['list'][$k]);
                }
            }else if($seller_work_status==1){
                if($item["real_do"] !=1){
                    unset($ret_info['list'][$k]);
                }
            }else if($seller_work_status==2){
                if($item["real_do"] !=2){
                    unset($ret_info['list'][$k]);
                }
            }else if($seller_work_status==3){
                if($item["real_do"] !=3){
                    unset($ret_info['list'][$k]);
                }
            }else if($seller_work_status==-2){
                if($item["real_do"] !=1 && $item["real_do"] != 3){
                    unset($ret_info['list'][$k]);
                }
            }

            if($plan_seller_work_status==0){
                if($item["plan_do"] !=0){
                    unset($ret_info['list'][$k]);
                }
            }else if($plan_seller_work_status==1){
                if($item["plan_do"] !=1){
                    unset($ret_info['list'][$k]);
                }
            }

        }

        $ret_info=\App\Helper\Common::gen_admin_member_data($ret_info['list']);
        foreach( $ret_info as &$item ) {
            E\Emain_type::set_item_value_str($item);
            E\Eseller_work_status::set_item_value_str($item,"plan_do");
            E\Eseller_work_status::set_item_value_str($item,"real_do");
            if($item['level'] != "l-4"){
                $item['plan_do_str'] = $item['real_do_str'] = "";
            }

        }

        return $this->pageView(__METHOD__,\App\Helper\Utils::list_to_page_info($ret_info),["plan_work_count"=>$plan_work_count,"real_work_count"=>$real_work_count,"leave_count"=>$leave_count,"overtime_count"=>$overtime_count]);
    }

    public function update_seller_work_status(){
        $month = $this->get_in_str_val('month');
        $day = $this->get_in_str_val('start_time');
        $adminid = $this->get_in_int_val('adminid');
        $seller_work_status = $this->get_in_int_val('seller_work_status');
        $leave_and_overtime = $this->t_seller_month_money_target->field_get_value_2($adminid,$month,"leave_and_overtime");
        if($seller_work_status ==2 || $seller_work_status ==3){
            if(!empty($leave_and_overtime)){
                $lo = json_decode($leave_and_overtime,true);
                $i = 0;
                foreach($lo as &$v){
                    if(substr($v[0],0,10) ==$day){
                        $v = $day.":".$seller_work_status;
                        $i ++;
                    }
                }
                if($i ==0){
                    $lo[] = [0=>$day.":".$seller_work_status];
                }
                $leave_and_overtime =json_encode($lo);
            }else{
                $lo = [];
                $lo[]=[0=>$day.":".$seller_work_status];
                $leave_and_overtime =json_encode($lo);
            }

        }else{
            if(!empty($leave_and_overtime)){
                $lo = json_decode($leave_and_overtime,true);
                $i = 0;
                foreach($lo as $k=>$v){
                    if(substr($v[0],0,10) ==$day){
                        unset($lo[$k]);
                    }
                }
                $leave_and_overtime =json_encode($lo);
            }

        }

        $this->t_seller_month_money_target->field_update_list_2($adminid,$month,["leave_and_overtime"=>$leave_and_overtime]);


        return $this->output_succ();
    }
    public function admin_member_list(){
        list($start_time,$end_time)=$this->get_in_date_range(date("Y-m-01"),0,0,[],3);
        $start_time = date('Y-m-d',$start_time);

        $ret_info = $this->t_manager_info->get_seller_month_money_info($start_time);
        $num_info = $this->t_admin_group_user->get_group_num($start_time);
        foreach($ret_info['list'] as &$item){
            $groupid = $item['groupid'];
            if($groupid >0 && isset($num_info[$groupid])){
                $item['month_money'] =  $item['month_money']/$num_info[$groupid]['num'];
            }
        }
        $ret_info['list']=\App\Helper\Common::gen_admin_member_data($ret_info['list']);
        foreach( $ret_info["list"] as &$item ) {
            E\Emain_type::set_item_value_str($item);
            if($item['level'] == "l-4"){
                $item['month_money']="";
            }
        }
        $week_data =\App\Helper\Utils::get_week_range(strtotime($start_time),1);
        $month_week_start = $week_data['sdate'];

        return $this->pageView(__METHOD__, $ret_info,['month'=>$start_time,"month_week_start"=>$month_week_start]);
    }

    public function admin_group_manage(){
        list($start_time,$end_time)=$this->get_in_date_range(0,0,0,[],3);
        $monthtime_flag = $this->get_in_int_val("monthtime_flag",1);
        // $admin_info = $this->t_manager_info->get_admin_member_list();
        // $list=\App\Helper\Common::gen_admin_member_data_new($monthtime_flag,$start_time); // 原始数据
        $list=\App\Helper\Common_new::gen_admin_member_data_new([],[],$monthtime_flag,$start_time); // 开发中
        list($member_new,$member_num_new,$member,$member_num,$become_member_num_l1,$leave_member_num_l1,$become_member_num_l2,$leave_member_num_l2,$become_member_num_l3,$leave_member_num_l3) = [[],[],[],[],0,0,0,0,0,0];
        foreach($list as $key=>&$val){
            $val["become_member_time"] = isset($val["create_time"])?$val["create_time"]:0;
            $val["leave_member_time"] = isset($val["leave_member_time"])?$val["leave_member_time"]:0;
            $val["del_flag"] = isset($val["del_flag"])?$val["del_flag"]:0;

            if($val['level'] == 'l-5' && $val['main_type'] != "未定义"){
                $log_info_arr = $this->t_user_group_change_log->get_user_change_log($val['adminid']);

                $add_time_formate = $log_info_arr['add_time']?date('Y-m-d H:i:s',$log_info_arr['add_time']):"";

                $do_adminid_nick  = $log_info_arr['do_adminid']?$this->cache_get_account_nick($log_info_arr['do_adminid']):"";

                $old_group        = $log_info_arr['old_group']?$log_info_arr['old_group']:"";

                if($add_time_formate !="" || $do_adminid_nick!="" || $old_group!=""){
                    $val['log_info'] = "分配时间:$add_time_formate 操作人:$do_adminid_nick 原来组别:$old_group";
                }else{
                    $val['log_info'] = "";
                }
            }else{
                $val['log_info'] = "";
            }

            if($val['level'] == "l-5" ){
                \App\Helper\Utils::unixtime2date_for_item($val,"become_member_time",'','Y-m-d');
                \App\Helper\Utils::unixtime2date_for_item($val,"leave_member_time",'','Y-m-d');
                $val["del_flag_str"] = \App\Helper\Common::get_boolean_color_str($val["del_flag"]);
                $val["del_flag"]?$leave_member_num_l3++:$become_member_num_l3++;
                $val["del_flag"]?$leave_member_num_l2++:$become_member_num_l2++;
                $val['become_member_num'] = $become_member_num_l3;
                $val['leave_member_num'] = $leave_member_num_l3;
            }else{
                $val["become_member_time"] = '';
                $val["leave_member_time"] = '';
                $val["del_flag_str"] = '';
                $val['become_member_num'] = '';
                $val['leave_member_num'] = '';
            }

            if($val['level'] == 'l-4'){
                $member[] = [
                    "up_group_name"     => $val['up_group_name'],
                    "group_name"        => $val['group_name'],
                ];
                $member_num[] = [
                    'become_member_num' => $become_member_num_l3,
                    'leave_member_num'  => $leave_member_num_l3,
                ];

                $become_member_num_l3 = 0;
                $leave_member_num_l3 = 0;
            }

            if($val['level'] == 'l-3'){
                $member_new[] = [
                    "up_group_name" => $val['up_group_name'],
                    "group_name"    => $val['group_name'],
                ];
                $member_num_new[] = [
                    'become_member_num' => $become_member_num_l2,
                    'leave_member_num'  => $leave_member_num_l2,
                ];

                $become_member_num_l2 = 0;
                $leave_member_num_l2 = 0;
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


        //各部门标识
        $main_type_flag = $this->get_in_int_val("main_type_flag");
        foreach($list as $kk=>&$item){
            if($main_type_flag>0){
                if($item['main_type'] != $main_type_flag ){
                    unset($list[$kk]);
                }
            }

            if(($item['main_type'] == '未定义') or ($item['main_type'] == '助教')){
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
        foreach( $list as &$item ) {
            E\Emain_type::set_item_value_str($item);
        }
        $group_list = json_encode($this->get_group_list($list));
        $this->set_filed_for_js("main_type_flag",$main_type_flag);
        $this->set_filed_for_js("monthtime_flag",$monthtime_flag);
        $this->set_filed_for_js("group_list",$group_list);
        return $this->pageView(__METHOD__, \App\Helper\Utils::list_to_page_info($list),[
            "monthtime_flag"=>$monthtime_flag,
        ]);
    }

    public function admin_group_manage_fulltime(){
        $this->set_in_value("main_type_flag",5);
        return $this->admin_group_manage();

    }

    public function get_group_list($list){
        $group_arr = [];
        foreach($list as $key=>$item){
            if($item['level'] == 'l-4' && $item['main_type']==2){
                $group_arr[$item['groupid']] = $item['group_name'];
            }
        }
        return $group_arr;
    }

    public function edit_seller_time(){

        $month = $this->get_in_str_val('month');

        if (!$month) {
            return $this->error_view(
                [
                    " 没有信息 ",
                    " 请从[销售额以及时间] 点击\"编辑当月上班时间\"进来 ",
                ]
            );
        } else {
            list($start_time,$end_time)=$this->get_in_date_range(date("Y-m-01"),0,0,[],3);
            if($month != date('Y-m-d',$start_time )){
                $month = date('Y-m-d',$start_time);
            }
            $adminid = $this->get_in_int_val('adminid');
            $groupid = $this->get_in_int_val('groupid');
            $week_data =\App\Helper\Utils::get_week_range(strtotime($month),1);
            $start_time = $week_data['sdate'];
            $month_time_str = substr($month,0,7);
            return $this->pageView(__METHOD__, null ,['month_time_str'=>$month_time_str,'start_time'=>$start_time,'adminid'=>$adminid,'groupid'=>$groupid,"month"=>$month]);

        }

    }

    public function get_seller_month_time_js()
    {
        $month = $this->get_in_str_val('month');
        $adminid = $this->get_in_int_val('adminid');
        $groupid = $this->get_in_int_val('groupid');
        if(empty($adminid)){
            $month_time = $this->t_admin_group_month_time->field_get_value_2($groupid,$month,"month_time");
        }else{
            $month_time = $this->t_seller_month_money_target->field_get_value_2($adminid,$month,"month_time");
        }
        if (trim($month_time)=="") {
            $month_time= "[]";
        }
        $month_time =  \App\Helper\Utils::json_decode_as_array($month_time);

        return  $this->output_succ( [ "data" =>$month_time] );
    }
    public function get_seller_leave_and_overtime_js()
    {
        $month = $this->get_in_str_val('month');
        $adminid = $this->get_in_int_val('adminid');
        $leave_and_overtime = $this->t_seller_month_money_target->field_get_value_2($adminid,$month,"leave_and_overtime");
        if (trim($leave_and_overtime)=="") {
            $leave_and_overtime= "[]";
        }
        $leave_and_overtime =  \App\Helper\Utils::json_decode_as_array($leave_and_overtime);

        return  $this->output_succ( [ "data" =>$leave_and_overtime] );
    }


    public function assistant_admin_member_list(){
        list($start_time,$end_time)=$this->get_in_date_range(date("Y-m-01"),0,0,[],3);
        // $start_time = date('Y-m-d',$start_time);
        $account_id = $this->get_account_id();
        $main_type = 1;
        $is_master = $this->t_admin_main_group_name->check_is_master($main_type,$account_id);
        // if($is_master>0 || $account_id==349 || $account_id==188 || $account_id){
        //     $up_master_adminid=-1;
        // }else{
        //     $up_master_adminid=0;
        // }
        $up_master_adminid=-1;
        $target_info = $this->t_ass_group_target->field_get_list($start_time,"rate_target,renew_target,group_renew_target,all_renew_target");
        $ret_info = $this->t_manager_info->get_assistant_month_target_info($start_time,$up_master_adminid,$account_id);
        $ret_info['list']=\App\Helper\Common::gen_admin_member_data($ret_info['list']);
        foreach( $ret_info["list"] as &$item ) {
            E\Emain_type::set_item_value_str($item);
            if($item["level"] == "l-4"){
                $item["lesson_target"]=@$target_info["rate_target"];
                $item["renew_target"]=@$target_info["renew_target"]/100;
            }elseif($item["level"] == "l-3"){
                $item["lesson_target"]=@$target_info["rate_target"];
                $item["renew_target"]=@$target_info["group_renew_target"]/100;

            }elseif($item["level"] == "l-1"){
                $item["lesson_target"]=@$target_info["rate_target"];
                $item["renew_target"]=@$target_info["all_renew_target"]/100;
            }else{
                $item["lesson_target"]="";
                $item["renew_target"]="";

            }

        }

        $this->set_filed_for_js("rate_target",@$target_info["rate_target"]);
        $this->set_filed_for_js("renew_target",@$target_info["renew_target"]/100);
        $this->set_filed_for_js("group_renew_target",@$target_info["group_renew_target"]/100);
        $this->set_filed_for_js("all_renew_target",@$target_info["all_renew_target"]/100);


        return $this->pageView(__METHOD__, $ret_info);
    }

    public function seller_tongji_report_info(){
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
        //试听申请数
        $this->t_test_lesson_subject_require->switch_tongji_database();
        $tr_info=$this->t_test_lesson_subject_require->tongji_require_test_lesson_group_by_admin_revisiterid_new($start_time,$end_time);
        foreach($tr_info['list'] as $item){
            $adminid = $item['admin_revisiterid'];
            $res[$adminid]['require_test_count_for_month']=$item['require_test_count'];
            if(isset($res[$adminid]['month_work_day_now_real']) && $res[$adminid]['month_work_day_now_real'] != 0){
                $res[$adminid]['require_test_count_for_day'] = round($item['require_test_count']/$res[$adminid]['month_work_day_now_real']);
            }
        }
        //教务排课数
        $test_leeson_list=$this->t_test_lesson_subject_require->tongji_test_lesson_group_by_admin_revisiterid_new_two($start_time,$end_time );
        foreach($test_leeson_list['list'] as $item){
            $adminid = $item['admin_revisiterid'];
            $res[$adminid]['test_lesson_count_for_month'] = $item['test_lesson_count'];
        }
        //学生上课数,试听成功数,取消数
        // $test_leeson_list=$this->t_test_lesson_subject_require->tongji_test_lesson_group_by_admin_revisiterid_new($start_time,$end_time );
        // foreach($test_leeson_list['list'] as $item){
        //     $adminid = $item['admin_revisiterid'];
        //     $res[$adminid]['test_lesson_count'] = $item['test_lesson_count'];
        //     $res[$adminid]['succ_all_count_for_month']=$item['succ_all_count'];
        //     $res[$adminid]['fail_all_count_for_month'] = $item['fail_all_count'];
        // }
        // list($start_time_new,$end_time_new)= $this->get_in_date_range_month(date("Y-m-01"));
        // if($end_time_new >= time()){
        //     $end_time_new = time();
        // }
        // $ret_new = $this->t_month_def_type->get_month_week_time($start_time_new);
        // $test_leeson_list_new=$this->t_test_lesson_subject_require->tongji_test_lesson_group_by_admin_revisiterid_new_three($start_time_new,$end_time_new);
        // foreach($test_leeson_list_new['list'] as $item){
        //     $adminid = $item['admin_revisiterid'];
        //     $lesson_start = $item['lesson_start'];
        //     foreach($ret_new as $info){
        //         $start = $info['start_time'];
        //         $end = $info['end_time'];
        //         $week_order = $info['week_order'];
        //         if($lesson_start>=$start && $lesson_start<$end && $week_order==E\Eweek_order::V_1){
        //             $res[$adminid][$week_order][] = $item;
        //         }elseif($lesson_start>=$start && $lesson_start<$end && $week_order==E\Eweek_order::V_2){
        //             $res[$adminid][$week_order][] = $item;
        //         }elseif($lesson_start>=$start && $lesson_start<$end && $week_order==E\Eweek_order::V_3){
        //             $res[$adminid][$week_order][] = $item;
        //         }elseif($lesson_start>=$start && $lesson_start<$end && $week_order==E\Eweek_order::V_4){
        //             $res[$adminid][$week_order][] = $item;
        //         }
        //     }
        // }
        // foreach($res as $key=>$item){
        //     $res[$key]['suc_lesson_count_one'] = isset($item[E\Eweek_order::V_1])?count($item[E\Eweek_order::V_1]):0;
        //     $res[$key]['suc_lesson_count_two'] = isset($item[E\Eweek_order::V_2])?count($item[E\Eweek_order::V_2]):0;
        //     $res[$key]['suc_lesson_count_three'] = isset($item[E\Eweek_order::V_3])?count($item[E\Eweek_order::V_3]):0;
        //     $res[$key]['suc_lesson_count_four'] = isset($item[E\Eweek_order::V_4])?count($item[E\Eweek_order::V_4]):0;
        //     $res[$key]['suc_lesson_count_one_rate'] = $res[$key]['suc_lesson_count_one']<12?0:15;
        //     $res[$key]['suc_lesson_count_two_rate'] = $res[$key]['suc_lesson_count_two']<12?0:15;
        //     $res[$key]['suc_lesson_count_three_rate'] = $res[$key]['suc_lesson_count_three']<12?0:15;
        //     $res[$key]['suc_lesson_count_four_rate'] = $res[$key]['suc_lesson_count_four']<12?0:15;
        //     $res[$key]['suc_lesson_count_rate_all'] = $res[$key]['suc_lesson_count_one_rate']+$res[$key]['suc_lesson_count_two_rate']+$res[$key]['suc_lesson_count_three_rate']+$res[$key]['suc_lesson_count_four_rate'];
        //     $res[$key]['suc_lesson_count_rate'] = $res[$key]['suc_lesson_count_rate_all'].'%';
        // }

        $this->t_order_info->switch_tongji_database();
        $order_new = $this->t_order_info->get_1v1_order_list_by_adminid($start_time,$end_time,-1);
        foreach($order_new as $k=>$v){
            $res[$k]['all_new_contract_for_month'] = $v['all_new_contract'];
            if(isset($res[$k]['succ_all_count_for_month']) && $res[$k]['succ_all_count_for_month'] != 0){
                $res[$k]['order_per'] =round($v['all_new_contract']/$res[$k]['succ_all_count_for_month'],2);
            }
            $res[$k]['all_price_for_month'] = $v['all_price']/100;
            if(isset($res[$k]['target_money']) && $res[$k]['target_money'] != 0){
                $res[$k]['finish_per'] =  round($v['all_price']/100/$res[$k]['target_money'],2);
                $res[$k]['los_money'] = $res[$k]['target_money']-$v['all_price']/100;
            }
            if(isset($res[$k]['target_personal_money']) && $res[$k]['target_personal_money'] != 0){
                $res[$k]['finish_personal_per'] =  round($v['all_price']/100/$res[$k]['target_personal_money'],2);
                $res[$k]['los_personal_money'] = $res[$k]['target_personal_money']-$v['all_price']/100;
            }
        }
        foreach ($res as $ret_k=> &$res_item) {
            $res_item["adminid"] = $ret_k ;
        }
        list($member_new,$member_num_new,$member,$member_num,$become_member_num_l1,$leave_member_num_l1,$become_member_num_l2,$leave_member_num_l2,$become_member_num_l3,$leave_member_num_l3) = [[],[],[],[],0,0,0,0,0,0];
        $ret_info=\App\Helper\Common::gen_admin_member_data_new($res,[],0,strtotime(date("Y-m-01",$start_time )));
        foreach( $ret_info as $key=>&$item ){
            $item["become_member_time"] = isset($item["create_time"])?$item["create_time"]:0;
            $item["leave_member_time"] = isset($item["leave_member_time"])?$item["leave_member_time"]:0;
            $item["del_flag"] = isset($item["del_flag"])?$item["del_flag"]:0;
            $item['suc_lesson_count_rate_all'] = isset($item["suc_lesson_count_rate_all"])?$item["suc_lesson_count_rate_all"]:0;
            E\Emain_type::set_item_value_str($item);
            E\Eseller_level::set_item_value_str($item);

            // $lesson_per = @$item['test_lesson_count']!=0?(round(@$item['fail_all_count_for_month']/$item['test_lesson_count'],2)*100):0;
            // $item['lesson_per'] = @$item['test_lesson_count']!=0?$lesson_per."%":0;
            // $lesson_kpi = $lesson_per<18?40:0;
            // $kpi = $lesson_kpi+$item['suc_lesson_count_rate_all'];
            // $item['kpi'] = ($kpi && @$item['test_lesson_count']>0)>0?$kpi."%":0;
            // if($item["become_member_time"]>0 && ($end_time-$item["become_member_time"])<3600*24*60 && $item["del_flag"]==0){
            //     $item['kpi'] = "100%";
            // }
            // $item['order_per'] = @$item['succ_all_count_for_month']!=0?(round(@$item['all_new_contract_for_month']/$item['succ_all_count_for_month'],2)*100)."%":0;

            $item['finish_per'] =@$item['target_money']!=0?(round(@$item['all_price_for_month']/$item['target_money'],2)*100)."%":0;
            $item['finish_personal_per'] =@$item['target_personal_money']!=0?(round(@$item['all_price_for_month']/$item['target_personal_money'],2)*100)."%":0;
            $item['duration_count_for_day'] = \App\Helper\Common::get_time_format(@$item['duration_count_for_day']);
            $item['ave_price_for_month'] =@$item['all_new_contract_for_month']!=0?round(@$item['all_price_for_month']/@$item['all_new_contract_for_month']):0;
            $item['los_money'] = @$item['target_money']-@$item['all_price_for_month'];
            $item['los_personal_money'] = @$item['target_personal_money']-@$item['all_price_for_month'];

            if($item['level'] == "l-5" ){
                $item['target_money']="";
                $item['finish_per'] = "";
                $item['los_money'] = "";
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
                $item['suc_lesson_count_rate'] = '';
                $item['kpi'] = '';
            }

            if($item['level'] == 'l-4'){
                $member[] = [
                    "first_group_name"  => $item['first_group_name'],
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
                    "first_group_name" => $item['first_group_name'],
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
            if(($item['main_type_str'] == '助教') || $item['main_type_str'] == '未定义'){
                unset($ret_info[$key]);
            }
            if(isset($item['target_money'])){
                $item['target_money'] = round($item['target_money']);
            }
            if(isset($item['los_money'])){
                $item['los_money'] = round($item['los_money']);
            }
            if(isset($item['all_price_for_month'])){
                $item['all_price_for_month'] = round($item['all_price_for_month']);
            }
            if(isset($item['ave_price_for_month'])){
                $item['ave_price_for_month'] = round($item['ave_price_for_month']);
            }
            if(isset($item['los_personal_money'])){
                $item['los_personal_money'] = round($item['los_personal_money']);
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
        \App\Helper\Utils::logger("OUTPUT");
        return $this->pageView(__METHOD__,\App\Helper\Utils::list_to_page_info($ret_info));
    }
    public function seller_require_tq_time_list(){
        list($start_time,$end_time)=$this->get_in_date_range(0,0,0,[],1);
        $this->t_test_lesson_subject_require->switch_tongji_database();
        $this->t_seller_month_money_target->switch_tongji_database();
        $this->t_tq_call_info->switch_tongji_database();
        $admin_list=$this->t_seller_month_money_target->get_seller_list_day($start_time);

        $tr_desc_info=$this->t_test_lesson_subject_require->tongji_require_test_lesson_list($start_time,$end_time,$admin_list, "desc" );

        $tr_asc_info=$this->t_test_lesson_subject_require->tongji_require_test_lesson_list($start_time,$end_time,$admin_list,"asc");
        $list_tq_desc=$this->t_tq_call_info->tongji_tq_info_ex($start_time,$end_time,$admin_list, "desc");
        foreach($list_tq_desc as &$item){
            $item['duration_count_str'] = \App\Helper\Common::get_time_format($item['duration_count']);
        }
        $list_tq_asc=$this->t_tq_call_info->tongji_tq_info_ex($start_time,$end_time,$admin_list, "asc");
        foreach($list_tq_asc as &$item){
            $item['duration_count_str'] = \App\Helper\Common::get_time_format($item['duration_count']);
        }

        return $this->pageView(__METHOD__, null, ["tr_asc_info"=>$tr_asc_info, "tr_desc_info" =>$tr_desc_info ,"list_tq_asc" => $list_tq_asc, "list_tq_desc" => $list_tq_desc]);
    }

    public function power_group_edit_new() {
        //角色id
        $role_groupid  = $this->get_in_int_val("role_groupid",1);

        //通用权限
        $group_common = $this->t_authority_group->get_groupid_by_role("1003");

        //所有权限组
        $group_list_all = $this->t_authority_group->get_auth_groups_all();
        $group_all = [];
        if($group_list_all){
            $role_id = 0;
            foreach($group_list_all as $group){
                if($group['role_groupid'] != $role_id){
                    $role_id = $group['role_groupid'];
                }
                $group_all[$role_id][] = $group;
            }
            foreach($group_all as $role=>$var){
                if($role != 0 && $role != 1003){
                    $group_all[$role] = array_merge($group_common,$var);
                }
            }
        }

        $default_groupid = @$group_common[0]['groupid'];
        if($group_all && array_key_exists($role_groupid, $group_all)){
            $default_groupid = $group_all[$role_groupid][0]['groupid'];
        }

        //选择权限组id
        $groupid  = $this->get_in_int_val("groupid",$default_groupid);

        $list=[];
        $user_list=[];
        $ret_info=\App\Helper\Utils::list_to_page_info([]);

        if( $groupid > 0 ){
            $user_list = $this->t_manager_info->get_power_group_user_list($groupid);
            $user_list = $this->get_user_permission($user_list);

            //$user_list = $this->get_user_powers($groupid);

            $power_map = $this->t_authority_group->get_auth_group_map($groupid);
            $list=$this->get_menu_list_new($power_map );

            $ret_info=\App\Helper\Utils::list_to_page_info($list);

        }
        return $this->Pageview(__METHOD__,$ret_info,[
            "_publish_version" => 201801127150,
            "group_all"        => $group_all,
            "user_list"        => $user_list,
            "list"             => $list,
            "groupid"          => $groupid
        ]);

    }

    private function get_user_permission($user_list){
        if($user_list){
            $permission = [];
            foreach($user_list as &$user){
                $user['permit_arr'] = explode(',',$user['permission']);
                $user['permit_name'] = "";
                $permission = array_merge($permission,$user['permit_arr']);
            }
            $permission = array_unique($permission);
            $per_name = [];
            if($permission){
                $per_str = "";
                foreach($permission as $per){
                    if($per != ''){
                        $per_str .= $per.',';
                    }
                }
                if( $per_str != ''){
                    $per_str = "(".substr($per_str,0,-1).')';
                    $permission_names = $this->t_authority_group->get_groups_by_idstr($per_str);
                    $per_name = array_column($permission_names, 'group_name', 'groupid');
                    foreach($user_list as &$user){
                        $permit_name = '';
                        if($user['permit_arr']){
                            foreach( $user['permit_arr'] as $gid){
                                $permit_str = array_key_exists($gid, $per_name) ? trim(@$per_name[$gid])."," : "";
                                $permit_name .= $permit_str;
                            }
                            $permit_name = substr($permit_name,0,-1);
                        }
                        $user['permit_name'] = $permit_name;
                    }

                }
            }
        }
        return $user_list;
    }

    public function power_group_edit() {
        // $err_mg = "旧的权限已经关闭，请前往新的页面";
        // return $this->view_with_header_info ( "common.resource_no_power", [],[
        //     "_ctr"          => "xx",
        //     "_act"          => "xx",
        //     "js_values_str" => "",
        //     'err_mg' => $err_mg
        // ] );
        return $this->error_view(["close"  ]);

        $this->t_user_log->row_insert([
            "add_time" => time(),
            "adminid"  => $this->get_account_id(),
            "msg"      => "旧的页面权限管理:登录",
            "user_log_type" => 5, //权限页面添加用户记录
        ]);

        $group_list = $this->t_authority_group->get_auth_groups();
        $default_groupid = 0;
        if (count($group_list)>0) {
            $default_groupid= $group_list[0]["groupid"];
        }
        $groupid  = $this->get_in_int_val("groupid",$default_groupid);
        $show_flag= $this->get_in_int_val("show_flag", -1);
        $user_list=[];
        $user_list=$this->t_manager_info->get_power_group_user_list($groupid);
        if ($show_flag!=2) { //只用户
            $power_map=$this->t_authority_group->get_auth_group_map($groupid);
            $list=$this->get_menu_list($power_map );

            $n=["k1"=>"","k2"=>"","k3"=>"" ];
            $n["k1" ]= "其它";
            $n["pid" ]= 0;
            $k1_class= $this->gen_class(1);
            $n["k_class" ]= $k1_class;
            $n["class" ]=  "l_1 $k1_class " ;
            $n["level" ]=  "1" ;
            $n["folder" ]=  true;
            $n["has_power_flag" ]= "" ;
            $list[]=$n;

            foreach (E\Epower::$desc_map as $k=> $v) {
                $n=["k1"=>"----","k2"=>"","k3"=>"" ];
                $k2_pid=$k;
                $n["k2" ]= $v ;
                $n["pid" ]= $k2_pid;
                $k2_class= $this->gen_class(2);
                $n["k_class" ]= $k2_class;
                $n["class" ]= "l_2 $k1_class $k2_class";
                $n["level" ]=  "2" ;
                $n["folder" ]=  false;
                $n["has_power_flag" ]= isset($power_map["$k2_pid"])?"checked":"" ;
                $list[]=$n;
            }
            $ret_info=\App\Helper\Utils::list_to_page_info($list);
        }else{
            $ret_info=\App\Helper\Utils::list_to_page_info([]);
        }

        // dd($ret_info);

        return $this->Pageview(__METHOD__,$ret_info,[
            "group_list"=>$group_list,
            "user_list"=>$user_list,
        ]);
    }


    public function test_james() {
        $group_list = $this->t_authority_group->get_auth_groups();
        $default_groupid = 0;
        if (count($group_list)>0) {
            $default_groupid= $group_list[0]["groupid"];
        }
        $groupid  = $this->get_in_int_val("groupid",$default_groupid);
        $show_flag= $this->get_in_int_val("show_flag", -1);
        $user_list=[];
        $user_list=$this->t_manager_info->get_power_group_user_list($groupid);
        if ($show_flag!=2) { //只用户
            $power_map=$this->t_authority_group->get_auth_group_map($groupid);
            $list=$this->get_menu_list($power_map );

            $n=["k1"=>"","k2"=>"","k3"=>"" ];
            $n["k1" ]= "其它";
            $n["pid" ]= 0;
            $k1_class= $this->gen_class(1);
            $n["k_class" ]= $k1_class;
            $n["class" ]=  "l_1 $k1_class " ;
            $n["level" ]=  "1" ;
            $n["folder" ]=  true;
            $n["has_power_flag" ]= "" ;
            $list[]=$n;

            foreach (E\Epower::$desc_map as $k=> $v) {
                $n=["k1"=>"----","k2"=>"","k3"=>"" ];
                $k2_pid=$k;
                $n["k2" ]= $v ;
                $n["pid" ]= $k2_pid;
                $k2_class= $this->gen_class(2);
                $n["k_class" ]= $k2_class;
                $n["class" ]= "l_2 $k1_class $k2_class";
                $n["level" ]=  "2" ;
                $n["folder" ]=  false;
                $n["has_power_flag" ]= isset($power_map["$k2_pid"])?"checked":"" ;
                $list[]=$n;
            }
            $ret_info=\App\Helper\Utils::list_to_page_info($list);
        }else{
            $ret_info=\App\Helper\Utils::list_to_page_info([]);
        }


        // dd($ret_info);
        $user_list = $this->t_manager_info->get_all();

        foreach($ret_info['list'] as &$item){
            if(!empty($item['url'])){
                $powerid_info = $this->get_powr_list($item['pid']);
                $group_list = [];
                foreach($powerid_info as $v){
                    $group_list[] = $v['groupid'];
                }
                $user_info = [];

                foreach($user_list as $vv){
                    $quan_arr = explode(',',$vv['permission']);

                    if(array_intersect($quan_arr,$group_list)){
                        // $item['']
                        $user_info[] = '姓名: '.$vv['account'].' adminid:'.$vv['uid'];
                    }
                }

                // dd($group_list);
                $item['user_info'] = implode(',',$user_info);
                // dd(json_encode($user_info));
                $item['url_name'] = '';
                if($item['k1'] != '----'){
                    $item['url_name'] = $item['k1'];
                }elseif($item['k2'] != '----'){
                    $item['url_name'] = $item['k2'];
                }elseif($item['k3'] != '----'){
                    $item['url_name'] = $item['k3'];
                }




                // $item['user_list'] = json_encode($this->t_manager_info->get_user_list($group_list));

            }


        }

        foreach($ret_info['list'] as $i=> &$v){
            if(empty(@$v['url'])){
                // dd($v);
                unset($ret_info['list'][$i]);
            }
        }

        // dd($group_list);

        // dd($ret_info);

        return $this->Pageview(__METHOD__,$ret_info);
    }





    public function get_powr_list($powerid) // james
    {
        // $powerid = 0;
        // $powerid = $this->get_in_int_val("powerid");
        $list    = $this->t_authority_group->get_all_list();
        // dd($list);
        $ret = [];
        foreach ($list as &$item) {
            $p_list=preg_split("/,/", $item["group_authority"] );
            unset( $item["group_authority"]);
            unset( $item["2"]);
            $item["has_power"] = in_array($powerid,$p_list)?1:0;

            if(in_array($powerid, $p_list)){
                $ret[] = $item;
            }
        }

        return $ret;
        // return $this->output_succ(["data"=> $ret]);
    }

    public function opt_accont_group() {
        $uid      = $this->get_in_int_val("uid") ;
        $groupid  = $this->get_in_int_val("groupid") ;
        $opt_type = $this->get_in_str_val("opt_type","add") ;
        $this->t_manager_info->opt_group($uid,$opt_type,$groupid);

        /**
         * @ 产品部加 数据更改日志
         */
        $this->t_user_log->row_insert([
            "add_time" => time(),
            "adminid"  => $this->get_account_id(),
            "msg"      => "权限管理页面,添加用户修改记录: [用户id:$uid,组别:$groupid]",
            "user_log_type" => E\Euser_log_type::V_4, //权限页面添加用户记录
        ]);


        return $this->output_succ();
    }

    public function set_group_power () {
        $groupid        = $this->get_in_str_val("groupid") ;
        $power_list_str = $this->get_in_str_val("power_list_str") ;

        $this->t_authority_group->field_update_list($groupid,[
            "group_authority" => $power_list_str,
        ]);

        /**
         * @ 产品部加 数据更改日志
         */
        $this->t_user_log->row_insert([
            "add_time" => time(),
            "adminid"  => $this->get_account_id(),
            "msg"      => "权限管理页面,权限修改记录:$power_list_str  权限组groupid:$groupid",
            "user_log_type" => E\Euser_log_type::V_2, //权限页面修改记录
        ]);

        return $this->output_succ();
    }

    public function power_group_add() {
        $this->t_authority_group->row_insert([
            "group_name"  => "99 角色",
            "create_time"  => time(NULL)
        ]);

        return $this->output_succ();
    }
    public function power_group_set_name() {
        $groupid    = $this->get_in_int_val("groupid");
        $group_name = $this->get_in_str_val("group_name");
        $this->t_authority_group->field_update_list($groupid,[
            "group_name"  => $group_name
        ]);
        return $this->output_succ();
    }

    public function account_set_phone_userid() {
        $phone  = $this->get_in_phone();
        $role   = $this->get_in_int_val("role");
        $userid = $this->get_in_userid();

        if ($this->t_phone_to_user->get_info_by_userid($userid)) {
            return $this->output_err("userid已存在:$userid");
        }

        $this->t_phone_to_user->set_userid($phone,$role,$userid);
        return $this->output_succ();
    }
    public function parent_child_set_parentid() {
        $userid = $this->get_in_userid();
        $parentid  = $this->get_in_parentid();
        $parent_type= $this->get_in_int_val("parent_type");

        $this->t_parent_child->set_parentid($userid,$parent_type,$parentid);

        return $this->output_succ();
    }


    public function parent_child_del() {
        $userid = $this->get_in_userid();
        $parentid  = $this->get_in_parentid();
        $parent_type= $this->get_in_int_val("parent_type");
        $this->t_parent_child->del($userid,$parent_type,$parentid);

        return $this->output_succ();
    }


    public function account_list () {
        $page_num=$this->get_in_page_num();
        $phone= $this->get_in_phone();
        $userid= $this->get_in_userid(-1);
        $ret_info=$this->t_phone_to_user->get_list($page_num,$phone,$userid);

        foreach ($ret_info["list"] as &$item) {
            $role=$item["role"];
            $id=$item["userid"];
            switch ( $role  ) {
            case  E\Erole::V_STUDENT :
                $nick=$this->cache_get_student_nick($id);
                break;
            case  E\Erole::V_TEACHER:
                $nick=$this->cache_get_teacher_nick($id);
                break;
            case  E\Erole::V_ASSISTENT:
                $nick=$this->cache_get_assistant_nick($id);
                break;
            case  E\Erole::V_PARENT:
                $nick=$this->cache_get_parent_nick($id);
                break;

            default:
                $nick="";
                break;
            }
            $item["nick"]=$nick;
            E\Erole::set_item_value_str($item);

        }
        return $this->Pageview(__METHOD__,$ret_info);
    }

    /**
     * @param type 0 后台权限角色 1 老师后台权限角色
     * @param groupid 角色组id
     */
    public function power_group_del() {
        $type    = $this->get_in_int_val("type",0);
        $groupid = $this->get_in_int_val("groupid");

        if($type==0){
            $ret = $this->t_authority_group->row_delete($groupid);
        }elseif($type==1){
            $ret=$this->t_user_authority_group->row_delete($groupid);
        }

        return $this->output_succ();
    }

    public function small_class_del_student() {
        $courseid = $this->get_in_courseid();
        $userid   = $this->get_in_userid();

        $lessonid_list = $this->t_lesson_info->get_lessonid_list($courseid,0);

        $this->t_small_class_user->start_transaction();
        $ret = $this->t_small_class_user->row_delete_2($courseid,$userid);
        if($ret){
            foreach($lessonid_list as $val){
                $ret = $this->t_small_lesson_info->row_delete_2($val['lessonid'],$userid);
                if(!$ret){
                    $this->t_small_lesson_info->rollback();
                    return $this->output_err("删除失败，请重试！");
                }
            }
            $this->t_small_lesson_info->commit();
            return $this->output_succ();
        }else{
            $this->t_small_lesson_info->rollback();
            return $this->output_err("删除失败，请重试！");
        }
    }

    public function edit_invoice() {
        $orderid          = $this->get_in_int_val("orderid");
        $is_invoice       = $this->get_in_int_val("is_invoice" );
        $can_period_flag  = $this->get_in_int_val("can_period_flag" );
        $invoice          = $this->get_in_str_val("invoice" );
        $check_money_desc = $this->get_in_str_val("check_money_desc" );
        $order_stamp_flag = $this->get_in_str_val("order_stamp_flag" );

        $this->t_order_info->field_update_list($orderid,[
            "is_invoice"       => $is_invoice,
            "invoice"          => $invoice,
            "check_money_desc" => $check_money_desc,
            "order_stamp_flag" => $order_stamp_flag,
            'can_period_flag'  => $can_period_flag,
        ]);
        return $this->output_succ();
    }

    public function lesson_count_type_list()
    {
        $page_num = $this->get_in_page_num();

        $ret_list = $this->t_assistant_info->get_type_count_by_ass($page_num);

        foreach ($ret_list['list'] as &$item){
            $item['yi_total_revisit'] = $this->t_student_info->get_revisit_count_all_by_assistantid($item['assistantid']);
            $item['total_revisit']    = ($this->t_student_info->get_lesson_count_all_by_assistantid($item['assistantid']))+1;
            $item['first_revisit']    = $this->t_student_info->get_first_revisit_by_assistantid($item['assistantid']);
            $item['yi_first_revisit'] = $this->t_student_info->get_yi_first_revisit_by_assistantid($item['assistantid']);
            $item['xq_revisit']       = $this->t_student_info->get_xq_revisit_by_assistantid($item['assistantid']);
            $item['yd_revisit']       = $this->t_student_info->get_yd_revisit_by_assistantid($item['assistantid']);
            $item['assistant_nick']   = $this->cache_get_assistant_nick($item['assistantid']);
            $item['yyd_revisit']      = ($item['total_revisit']-1)/4;
            $item['yxq_revisit']      = $item['yyd_revisit']*3+1;
        }

        return $this->Pageview(__METHOD__,$ret_list );
    }


    public function get_seller_student_orgin() {
        $phone    = $this->get_in_phone();

        $list     = $this->t_seller_student_info->get_origin_info($phone);
        $ret_list = \App\Helper\Utils::list_to_page_info($list);
        foreach( $ret_list["list"] as &$item) {
            E\Ebook_status::set_item_value_str(  $item,"status");
        }
        $ret_list["page_info"] = $this->get_page_info_for_js( $ret_list["page_info"]   );

        return outputjson_success(array('data' => $ret_list ));
    }

    public function get_lesson_info_for_monitor()
    {
        $lessonid=$this->get_in_lessonid();
        $info=$this->t_lesson_info_b2-> get_info_for_monitor($lessonid);
        E\Egrade::set_item_value_str($info);
        E\Esubject::set_item_value_str($info);
        //E\Eboolean::set_item_value_str($info,"stu_test_ipad_flag");
        return $this->output_succ(["data"=>$info]);
    }

    public function month_user_info() {
        $this->check_and_switch_tongji_domain();
        $year=$this->get_in_int_val("year","2016");
        $month=$this->get_in_int_val("month","5");
        $start_time=strtotime( "$year-$month-01");
        $next_month=$month+1;
        if ($next_month>=13) {
            $next_month=$next_month-12;
            $year++;
        }
        $end_time=strtotime( "$year-$next_month-01");

        $gen_userid_map=function($list){
            $map=[];
            foreach ($list as $item) {
                $map[$item["userid"]] =true;
            }
            return $map;
        };
        $gen_diff_list=function($l1,$l2 ) {
            //return  $1 not in $l2
            $map=[];
            foreach ($l1 as $k=>$v) {
                if (!isset($l2[$k])) {
                    $map[$k] = $v;
                }
            }
            return $map;
        };

        $old_order_list=$this->t_order_info->get_order_list_for_tongji(-1,$start_time);
        $new_order_list=$this->t_order_info->get_order_list_for_tongji($start_time,$end_time);
        //统计
        $old_user_map=$gen_userid_map($old_order_list);
        $new_user_map=$gen_userid_map($new_order_list);
        $new_user_map_ex= $gen_diff_list($new_user_map,$old_user_map);

        $ret=array();
        $ret["new_user_count"]= count($new_user_map_ex);
        $ret["old_pay_user_count"]= count($new_user_map)- $ret["new_user_count"];

        $test_lesson_count=0;
        $new_lesson_count=0;
        $old_lesson_count=0;
        //得到上课课时
        $lesson_list=$this->t_lesson_info-> get_lesson_list_for_tongji($start_time,$end_time);
        $new_lesson_user_map=[];
        $old_lesson_user_map=[];
        foreach($lesson_list as $item) {
            $userid=$item["userid"];
            $lesson_type=$item["lesson_type"];
            if ($lesson_type==2){ //试听
                $test_lesson_count++;
            }else{// 1v1
                if (isset($new_user_map_ex[$userid])) {
                    $new_lesson_count++;
                    $new_lesson_user_map[$userid]=true;
                }else{
                    $old_lesson_count++;
                    $old_lesson_user_map[$userid]=true;
                }
            }

        }

        $ret["test_lesson_count"]= $test_lesson_count;
        $ret["new_lesson_count"]= $new_lesson_count;
        $ret["old_lesson_count"]= $old_lesson_count;

        $ret["new_lesson_user_count"] = count($new_lesson_user_map);
        $ret["old_lesson_user_count"] = count($old_lesson_user_map);
        $ret["lesson_user_count"]     = $ret["new_lesson_user_count"] + $ret["old_lesson_user_count"];

        //到月末退费人数 -----开发中
        $refund_info = $this->t_order_refund->get_refund_userid_by_month(-1,$end_time);
        $refund_num = $refund_info['orderid_count'];
        //2017-10-25以后的新数据
        $now = strtotime( date('Y-m-01', time()) );
        if( $start_time == $now ){
            //实时付费学员数
            $list = $this->get_cur_month_stu_info($start_time);
            $all_order = $list['all_order'];
            $res = $this->t_month_student_count->get_student_month_info($start_time);
            $list['pay_stu_num'] = $res['pay_stu_num'];
        } else {
            $list = $this->t_month_student_count->get_student_month_info($start_time);
            $all_order = $this->t_month_student_count->get_all_pay_order_num($end_time);
        }

        if( $list != false ) {
            if ($all_order != 0){
                //退费率
                $list['refund_rate'] = round( $refund_num*100/$all_order ,2) .'%';
            } else {
                $list['refund_rate'] = 0;
            }
            //续费率
            $renow_num = $list['warning_renow_stu_num'] + $list['no_warning_renow_stu_num'];
            if ($list['warning_stu_num'] != 0) {
                $list['renow_rate'] = round( $renow_num*100/$list['warning_stu_num'] ,2) .'%';
                $list['warning_renow_rate'] = round( $list['warning_renow_stu_num']*100/$list['warning_stu_num'] ,2) .'%';
            }else {
                $list['renow_rate'] = 0;
                $list['warning_renow_rate'] = 0;
            }


        }

        return $this->pageView(__METHOD__,null, [
            "info"  => $ret,
            "new"  => $list,
        ]);
    }

    public function get_cur_month_stu_info($start_time){

        $end_time   = strtotime('+1 month', $start_time );
        $ret = [];
        //实时付费学员数
        $all_pay = $this->t_student_info->get_student_list_for_finance_count();
        $ret['all_pay'] = $all_pay['userid_count'];
        $ret['all_order'] = $all_pay['orderid_count'];

        $user_order_list = $this->t_order_info->get_order_user_list_by_month($end_time);
        $new_user = [];//月新签

        foreach ( $user_order_list as $item ) {
            if ($item['order_time'] >= $start_time){
                $new_user[] = $item['userid'];
                if (!$item['start_time'] && $item['assistantid'] > 0) {//月新签订单,未排课,已分配助教
                    @$ret['has_ass_num']++;
                } else if (!$item['start_time'] && !$item['assistantid']) {//月新签订单,未排课,未分配助教
                    @$ret['no_ass_num']++;
                }
            }

        }
        $new_user = array_unique($new_user);
        $ret['new_pay_stu_num'] = count($new_user);

        //退费名单
        $refund_num = $this->t_order_refund->get_refund_userid_by_month($start_time,$end_time);
        $ret['refund_stu_num'] = $refund_num['userid_count'];
        $ret['refund_order_num'] = $refund_num['orderid_count'];
        //正常结课学生
        $ret_num = $this->t_student_info->get_user_list_by_lesson_count_new($start_time,$end_time);
        $ret['normal_over_num'] = $ret_num;

        // 在读,停课,休学,假期数
        $ret_info = $this->t_student_info->get_student_count_archive();

        foreach($ret_info as $item) {
            if($item['type'] == 0) {
                @$ret['study_num']++;
            } else if ($item['type'] == 2) {
                @$ret['stop_num']++;
            } else if ($item['type'] == 3) {
                @$ret['drop_out_num']++;
            } else if ($item['type'] == 4) {
                @$ret['vacation_num']++;
            }
        }

        //月续费学员
        $renow_list = $this->t_order_info->get_renow_user_by_month($start_time, $end_time);
        $renow_user = [];
        foreach ($renow_list as $item) {
            $renow_user[] = $item['userid'];
        }
        //月预警学员
        $warning_list = $this->t_ass_weekly_info->get_warning_user_by_month($start_time);
        $warning_renow_num = 0;
        $warning_stu_num = 0;

        foreach ($warning_list as $item){
            $new = json_decode($item['warning_student_list'], true);
            if(is_array($new)){
                foreach($new as $v) {
                    if( strlen($v)>0){
                        $warning_stu_num++;
                        if( in_array($v ,$renow_user) ){
                            $warning_renow_num++;
                        }
                    }
                }
            }
        }

        $ret['warning_stu_num']          = $warning_stu_num;
        $ret['warning_renow_stu_num']    = $warning_renow_num;
        $ret['no_warning_renow_stu_num'] = count($renow_user) - $warning_renow_num;

        return $ret;
    }

    function stu_set_init_info_pdf_url() {
        $userid=$this->get_in_userid();
        $init_info_pdf_url=$this->get_in_str_val("init_info_pdf_url");
        $this->t_student_info->field_update_list($userid,[
            "init_info_pdf_url"   => $init_info_pdf_url,
        ]);
        $nick=$this->cache_get_student_nick($userid);

        $account=$this->get_account();
        $wx=new \App\Helper\Wx();
        // $template_id="1600puebtp9CfcIg41Oz9VHu6iRXHAJ8VpHKPYvZXT0";//old
        $template_id="9MXYC2KhG9bsIVl16cJgXFVsI35hIqffpSlSJFYckRU";
        //9MXYC2KhG9bsIVl16cJgXFVsI35hIqffpSlSJFYckRU
        $ret= $this->t_manager_info->send_template_msg("fly",$template_id,[
            "first"    => "PDF 交接单 更新",
            "keyword1" => "学生-$nick",
            "keyword2" => "助教-$account",
            "keyword3" => date("Y-m-d H:i:s"),
            "remark"   => "请点击[详情],进入管理系统操作",
        ],"/user_manage_new/ass_contract_list?studentid=$userid");

        return $this->output_succ();
    }

    function record_audio_server_list() {
        $page_num=$this->get_in_page_num();
        $ret_info=$this->t_audio_record_server->get_server_list($page_num);
        $check_time=time(NULL)-60;

        foreach($ret_info["list"] as &$item) {
            $item["status_class"]= ($item["last_active_time"] <$check_time)?"danger":"";

            \App\Helper\Utils::unixtime2date_for_item($item,"last_active_time");
        }
        return $this->pageView(__METHOD__,$ret_info);
    }

    function record_audio_server_del() {
        $ip=$this->get_in_str_val("ip");
        $this->t_audio_record_server->row_delete($ip);
        return $this->output_succ();
    }

    function record_audio_server_set() {
        $ip=$this->get_in_str_val("ip");
        $priority=$this->get_in_int_val("priority");
        $max_record_count=$this->get_in_int_val("max_record_count");
        $config_userid=$this->get_in_int_val("config_userid");
        $desc=$this->get_in_str_val("desc");
        $this->t_audio_record_server->field_update_list($ip,[
            "priority" => $priority,
            "config_userid" => $config_userid,
            "max_record_count" => $max_record_count,
            "`desc`" => $desc,
        ]);
        return $this->output_succ();
    }

    function get_teacher_money_list() {
        $teacher_money_type = $this->get_in_int_val("teacher_money_type");
        $level = $this->get_in_int_val("level");

        if(($teacher_money_type ==0 && $level<=3)
           ||  ($teacher_money_type ==1 && $level<=2 )
           ||  ($teacher_money_type ==2 && $level<=0 )
           ||  ($teacher_money_type ==3 && $level<=0 )
        ){
            $price_class=\App\Config\teacher_price_base::get_price_class( $teacher_money_type,$level )  ;
            for( $i=1;$i<300; $i++  ) {
                $item=["lesson_count"=>$i];
                foreach (  E\Egrade::$desc_map as  $grade => $grade_desc) {
                    $lesson_count_level=$price_class::get_lesson_count_level($i*100);
                    $pre_price= $price_class::get_price($level,$grade,$lesson_count_level);
                    $item["f_$grade" ] = $pre_price ;
                }
                $list[]=$item;
            }
        }else{
            $list=[];
        }
        $page_info=\App\Helper\Utils::list_to_page_info($list);
        return $this->pageView(__METHOD__, $page_info);
    }

    public function teacher_money_type_list_simulate(){
        return $this->teacher_money_type_list();
    }

    public function teacher_money_type_list(){
        $teacher_money_type = $this->get_in_int_val("teacher_money_type",6);

        /**
         * teacher_money_type 2 外聘 3 固定工资 这两类不分等级
         */
        if($teacher_money_type==2 || $teacher_money_type==3){
            $level = 0;
        }else{
            $level = $this->get_in_int_val("level");
        }

        $type      = $this->t_teacher_money_type->get_teacher_type($teacher_money_type,$level);
        $rule_type = \App\Config\teacher_rule::get_teacher_rule($type);

        $i = 0;
        $total_type = [];
        if(isset($rule_type) && is_array($rule_type)){
            $money_type=$rule_type;
            if($teacher_money_type==E\Eteacher_money_type::V_7){
                foreach($money_type as $key=>$val){
                    $i++;
                    if($key!=0){
                        $total_type[]="<=".$key/100;
                    }
                    if($i==count($money_type)){
                        $total_type[]=">".$key/100;
                    }
                }
            }else{
                foreach($money_type as $key=>$val){
                    $i++;
                    if($key!=0){
                        $total_type[]="<".$key/100;
                    }
                    if($i==count($money_type)){
                        $total_type[]=">=".$key/100;
                    }
                }
            }
        }else{
            $money_type=[];
        }

        $ret = $this->t_teacher_money_type->get_teacher_money_type_list($teacher_money_type,$level);
        foreach($ret as &$val){
            E\Egrade::set_item_value_str($val);
            if(!empty($money_type) && is_array($money_type)){
                $num = 0;
                foreach($money_type as $k=>$v){
                    $val['money_'.$num]=$val['money']+$v;
                    $num++;
                }
            }else{
                $val['money_0']=$val['money'];
            }
        }

        $page_info = \App\Helper\Utils::list_to_page_info($ret);
        return $this->pageView(__METHOD__,$page_info,["total_type" => $total_type]);
    }

    public function get_group_list_by_powerid()
    {
        $powerid = $this->get_in_int_val("powerid");
        $list    = $this->t_authority_group->get_all_list_order_by_role();
        foreach ($list as &$item) {
            $p_list=preg_split("/,/", $item["group_authority"] );
            unset( $item["group_authority"]);
            unset( $item["2"]);
            E\Eaccount_role::set_item_value_str($item, "role_groupid");
            $item["has_power"] = in_array($powerid,$p_list)?1:0;

        }

        return $this->output_succ(["data"=> $list]);
    }

    public function get_group_list_by_powerid_page()
    {
        $powerid = $this->get_in_int_val("powerid");
        $ret  = \App\Helper\Utils::list_to_page_info([]);
        $list    = $this->t_authority_group->get_all_list();
        foreach ($list as &$item) {
            $p_list=preg_split("/,/", $item["group_authority"] );
            unset( $item["group_authority"]);
            unset( $item["2"]);
            $item["has_power"] = in_array($powerid,$p_list)?1:0;
        }
        $ret['list'] = $list;
        return $this->output_ajax_table($ret);
    }

    public function set_power_with_groupid_list() {
        $powerid      = $this->get_in_int_val("powerid");
        $groupid_str  = $this->get_in_str_val("groupid_list");
        $groupid_list = \App\Helper\Utils::json_decode_as_int_array( $groupid_str );
        $list         = $this->t_authority_group->get_all_list();

        $this->t_user_log->row_insert([
            "add_time" => time(),
            "adminid"  => $this->get_account_id(),
            "msg"      => "旧的页面权限管理配置: [权限id:$powerid,权限列表:$groupid_str]",
            "user_log_type" => E\Euser_log_type::V_2, //权限页面添加用户记录
        ]);

        return $this->output_succ();
    }

    public function set_power_with_groupid_list_new() {
        $powerid      = $this->get_in_int_val("powerid");      //权限号
        $groupid_str  = $this->get_in_str_val("groupid_list"); //角色列表
        $groupid_list = \App\Helper\Utils::json_decode_as_int_array( $groupid_str );
        $list         = $this->t_authority_group->get_all_list();

        foreach ($list as &$item) {
            $p_list       = explode(",", $item["group_authority"] );   //权限号列表
            $find_indx = array_search($powerid,$p_list);
            $old_has_flag=true;
            if ($find_indx===false){
                $old_has_flag=false;
            }
            $groupid      = $item["groupid"];
            $new_has_flag = in_array($groupid,$groupid_list );
            if ($old_has_flag !=$new_has_flag) {
                if ($new_has_flag ) {
                    $p_list[]=$powerid ;
                }else{
                    unset($p_list[$find_indx]);
                }
                $group_authority=join(",",$p_list);
                $this->t_authority_group->field_update_list($groupid,[
                    "group_authority" => $group_authority
                ]);
            }

        }

        $this->t_user_log->row_insert([
            "add_time" => time(),
            "adminid"  => $this->get_account_id(),
            "msg"      => "新的页面权限管理配置: [权限id:$powerid,权限列表:$groupid_str]",
            "user_log_type" => E\Euser_log_type::V_2, //权限页面添加用户记录
        ]);

        return $this->output_succ();
    }


    public function tea_lesson_count_total_list_tea() {
        $this->set_in_value("check_adminid", $this->get_account_id() );
        $this->set_in_value("show_add_money_flag", 0 );
        return $this->tea_lesson_count_total_list();
    }

    public function tea_lesson_count_total_list () {
        $this->switch_tongji_database();
        list($start_time, $end_time)=$this->get_in_date_range(date("Y-m-01", time(NULL)-28*86400 ),0, 0,[],3 );
        $confirm_flag           = $this->get_in_int_val("confirm_flag",-1, E\Eboolean::class);
        $pay_flag               = $this->get_in_int_val("pay_flag",-1, E\Eboolean::class);
        $show_add_money_flag    = $this->get_in_int_val("show_add_money_flag",1);
        $check_adminid          = $this->get_in_int_val("check_adminid",-1);
        $has_check_adminid_flag = $this->get_in_int_val("has_check_adminid_flag",-1,E\Eboolean::class );

        $ret_info = $this->t_lesson_info->get_lesson_info_for_teacher($start_time,$end_time,$has_check_adminid_flag,$check_adminid);
        $real_money_list = $this->t_teacher_month_money->get_list($start_time,$confirm_flag,$pay_flag);

        $i = 1;
        $sum_all_lesson_money = 0;
        foreach ($ret_info["list"] as &$item) {
            $item["index"] = $i;
            $item["subject_str"] = "";
            $i++;
            E\Eteacher_money_type::set_item_value_str($item);
            E\Elevel::set_item_value_str($item);

            $teacherid = $item["teacherid"];
            $ritem     = @$real_money_list[$teacherid];
            if ($ritem) {
                $item["real_all_count"]        = $ritem["all_count"]/100;
                $item["real_l1v1_count"]       = $ritem["l1v1_count"]/100;
                $item["real_test_count"]       = $ritem["test_count"]/100;
                $item["real_money_all_count"]  = $ritem["money_all_count"]/100;
                $item["real_money_l1v1_count"] = $ritem["money_l1v1_count"]/100;
                $item["real_money_test_count"] = $ritem["money_test_count"]/100;
                $item["confirm_flag"]          = $ritem["confirm_flag"];
                $item["confirm_time"]          = $ritem["confirm_time"];
                $item["confirm_adminid"]       = $ritem["confirm_adminid"];
                $item["pay_flag"]              = $ritem["pay_flag"];
                $item["pay_time"]              = $ritem["pay_time"];
                $item["pay_adminid"]           = $ritem["pay_adminid"];
            }else{
                $item["real_all_count"]        = 0;
                $item["real_l1v1_count"]       = 0;
                $item["real_test_count"]       = 0;
                $item["real_money_all_count"]  = 0;
                $item["real_money_l1v1_count"] = 0;
                $item["real_money_test_count"] = 0;
                $item["confirm_flag"]          = 0;
                $item["confirm_time"]          = 0;
                $item["confirm_adminid"]       = 0;
                $item["pay_flag"]              = 0;
                $item["pay_time"]              = 0;
                $item["pay_adminid"]           = 0;
            }
            \App\Helper\Utils::unixtime2date_for_item($item,"confirm_time");
            \App\Helper\Utils::unixtime2date_for_item($item,"pay_time");
            E\Eboolean::set_item_value_str($item,"confirm_flag");
            E\Eboolean::set_item_value_str($item,"pay_flag");
            $this->cache_set_item_account_nick($item,"confirm_adminid", "confirm_admin_nick");
            $this->cache_set_item_account_nick($item,"pay_adminid", "pay_admin_nick");
            $this->cache_set_item_account_nick($item,"check_adminid", "check_admin_nick");

            $subject = $this->t_lesson_info->get_teacher_subject($teacherid,$start_time,$end_time);
            foreach($subject as $val){
                if($val['subject']!=""){
                    $item['subject_str'] .= E\Esubject::get_desc($val['subject'])."|";
                }
            }
            $item['subject_str']=trim($item['subject_str'],"|");
            $item["l1v1_lesson_count"]/=100;
            $item["all_lesson_money"]/=100;
            if (!$show_add_money_flag) {
                $item["all_lesson_money"]="";
            }else{
                $sum_all_lesson_money += $item["all_lesson_money"];
            }

            $item["all_count"]= $item["l1v1_lesson_count"]+$item["test_lesson_count"];
        }

        if ($confirm_flag != -1 ||  $pay_flag  != -1 ) {
            $t_list=[];
            foreach ( $ret_info["list"] as &$t_item )  {
                $get_flag=true;
                if ($confirm_flag  != -1  ){
                    if ($t_item["confirm_flag"] != $confirm_flag ) {
                        $get_flag=false;
                    }
                }
                if ($get_flag) {
                    if ($pay_flag !=-1 ){
                        if ($t_item["pay_flag"] != $pay_flag ) {
                            $get_flag=false;
                        }
                    }
                }
                if ($get_flag) {
                    $t_list[]=$t_item;
                }
            }
            $ret_info["list"]=$t_list;
        }

        return $this->pageView(__METHOD__,$ret_info,[
            "sum_all_lesson_money" => $sum_all_lesson_money,
        ]);
    }

    public function lesson_student_grade_list() {
        list($start_time,$end_time)=$this->get_in_date_range_month(date("Y-m-01"));

        $ret_info = $this->t_lesson_info->get_lesson_student_grade_list($start_time,$end_time);
        foreach($ret_info["list"] as &$item ) {
            $this->cache_set_item_student_nick($item);
        }

        return $this->pageView(__METHOD__,$ret_info);
    }

    public function get_teacher_all_money() {
        list($start_time,$end_time)=$this->get_in_date_range(0,0);
        $teacherid = $this->get_in_teacherid();
        $studentid = -1;

        $old_list     = $this->t_lesson_info->get_1v1_lesson_list_by_teacher($teacherid,$studentid,$start_time,$end_time);
        $test_money   = 0;
        $l1v1_money   = 0;
        $all_lesson_price = 0;

        foreach ($old_list as $row_id=> &$item) {
            E\Eteacher_money_type::set_item_value_str($item);
            $already_lesson_count = $item["already_lesson_count"];
            //teacher level
            $level               = $item["level"];
            $teacher_money_type  = $item["teacher_money_type"];
            $grade               = $item["grade"];
            $lesson_type         = $item["lesson_type"];
            $lesson_count        = $item["lesson_count"];
            $all_lesson_price   += $item['lesson_price'];

            if ( $lesson_type==2 ) {
                $test_money += 5000;
            }else{
                $l1v1_money += \App\Config\teacher_price_base::get_money($teacher_money_type,$level,$lesson_count,$grade,$already_lesson_count);
            }
        }

        return $this->output_succ([
            "l1v1_money" => $l1v1_money/100,
            "test_money" => $test_money/100,
            "all_money"  => $test_money/100+$l1v1_money/100,
            "all_lesson_price" => $all_lesson_price/100,
        ]);
    }

    public function  get_error_record_lesson_list() {
        list($start_time,$end_time) =        $this->get_in_date_range(-30,0);
        $page_num=$this->get_in_page_num();
        $lesson_type=$this->get_in_int_val("lesson_type",-1, E\Econtract_type::class);
        $this->t_lesson_info->switch_tongji_database();
        $ret_info=$this->t_lesson_info->get_error_record_lesson_list($page_num,$lesson_type,$start_time,$end_time );
        //unixtime
        foreach ($ret_info ["list"] as &$item) {
            $item ["lesson_time"]= \App\Helper\Utils::fmt_lesson_time($item["lesson_start"],$item["lesson_end"]);
            $this->cache_set_item_student_nick($item);
            $this->cache_set_item_teacher_nick($item);
            E\Econtract_type::set_item_value_str($item,"lesson_type");
            $item ["lesson_upload_time"]= \App\Helper\Utils::unixtime2date($item["lesson_upload_time"]);
            #$item ["lesson_upload_time"]= date('Y-m-d H:i:s',$item["lesson_upload_time"]);

            //
        }
        return $this->pageView(__METHOD__, $ret_info );

    }

    public function commodity_exchange_management()
    {
        list($start_time,$end_time) = $this->get_in_date_range(-30,0);
        $page_num    = $this->get_in_page_num();
        $gift_type   = $this->get_in_int_val("gift_type" ,-1, E\Econtract_type::class);
        $status      = $this->get_in_int_val("status" ,-1, E\Econtract_type::class);
        $assistantid = $this->get_in_int_val("assistantid",-1);

        $ret_info = $this->t_gift_consign->get_commodity_consign_list(
            $page_num,$gift_type,$status,$assistantid,$start_time,$end_time
        );

        foreach ($ret_info ["list"] as &$item) {
            E\Egift_status::set_item_value_str($item,"status");
            $item ["exchange_time"]= \App\Helper\Utils::unixtime2date($item["exchange_time"]);
        }

        return $this->pageView(__METHOD__, $ret_info );
    }

    public function commodity_exchange_management_assistant()
    {
        $assistantid=$this->t_assistant_info->get_assistantid($this->get_account());
        if($assistantid <= 0){
            $assistantid = 1;
        }
        $this->set_in_value("assistantid", $assistantid);
        return $this->commodity_exchange_management();
    }

    public function present_manage_new()
    {
        $page_num = $this->get_in_page_num();
        $del_flag = $this->get_in_int_val('del_flag', -1);


        // list( $order_in_db_flag, $order_by_str, $order_field_name,$order_type)
        //     =$this->get_in_order_by_str([],"",["cost_prise" => "cost_price"]);


        $ret_info = $this->t_gift_info->get_all_gift($page_num, $del_flag);
        $cur_ratio = Config::get_current_ratio();
        foreach($ret_info['list'] as &$item){
            E\Egift_type::set_item_value_str($item,"gift_type");
            $pic_list = array();
            $arr = explode(",",$item['gift_desc']);
            foreach($arr as $pic_info) {
                if (!empty($pic_info)) {
                    $pic_list[] = trim($pic_info);
                }
            }
            $item['del_flag_str'] = '<span style="color:green">已上架</span>';
            if ($item['del_flag']) {
                $item['del_flag_str'] = '<span style="color:red"> 已下架</span>';
            }
            $item["gift_desc_str"]  = json_encode($pic_list);
            $item['cost_price_str'] = $item['cost_price']/100;
        }
        $pub_domain = Config::get_qiniu_public_url()."/";

        return $this->pageView(__METHOD__, $ret_info ,['pub_domain' => $pub_domain,'cur_ratio'=>$cur_ratio]);
    }

    public function stu_all_info(){
        $student_type = $this->get_in_int_val("student_type" ,-1);
        $teacherid = $this->get_in_int_val("teacherid" ,-1);
        $ret_info = $this->t_course_order->get_tea_stu($student_type,$teacherid);
        foreach($ret_info['list'] as &$item) {
            E\Estudent_type::set_item_value_str($item,"type");
            $item['lesson_count_all']  = $item['lesson_count_all']/100;
            $item['lesson_count_left'] = $item['lesson_count_left']/100;
            \App\Helper\Utils::unixtime2date_for_item($item,"last_lesson_time");
        }
        return $this->pageView(__METHOD__, $ret_info );

    }

    public function test_lesson_lost_user_list() {
        list($start_time,$end_time) = $this->get_in_date_range( date("Y-m-01",time(NULL)) ,0,0,[],3 );
        $grade    = $this->get_in_grade();
        $page_num = $this->get_in_page_num();
        $ret_info = $this->t_student_info ->get_test_lesson_lost_user_list( $page_num,$grade,$start_time,$end_time);

        foreach($ret_info['list'] as &$item) {
            $this->cache_set_item_account_nick( $item, "last_revisit_adminid", "last_revisit_admin_nick");
            \App\Helper\Utils::unixtime2date_for_item($item,"lesson_start");
            \App\Helper\Utils::unixtime2date_for_item($item,"last_revisit_admin_time");
            E\Egrade::set_item_value_str($item);
            E\Egender::set_item_value_str($item);
        }

        return $this->pageView(__METHOD__, $ret_info );
    }

    public function get_unassigned_lesson_count(){
        $userid           = $this->get_in_int_val("userid");
        $competition_flag = $this->get_in_int_val("competition_flag");
        $orderid          = $this->get_in_int_val("orderid");
        if($orderid>0){
            $userid           = $this->t_order_info->get_userid($orderid);
            $competition_flag = $this->t_order_info->get_competition_flag($orderid);
        }
        //$course_cost = $this->t_course_order->reset_assigned_lesson_count($userid,$competition_flag);

        $lesson_unassigned = $this->t_course_order->get_unassigned_lesson_total($userid,$competition_flag,$orderid);

        return $this->output_succ(["lesson_unassigned" => $lesson_unassigned]);
    }

    public function update_teacher_identity(){
        $teacherid = $this->get_in_int_val("teacherid");
        $identity  = $this->get_in_int_val("identity");
        if($teacherid==0){
            return $this->output_err("老师id不能为0!");
        }

        $ret = $this->t_teacher_info->field_update_list($teacherid,[
            "identity" => $identity
        ]);
        if(!$ret){
            return $this->output_err("老师身份未改变!");
        }
        return $this->output_succ();
    }

    public function tea_wages_list(){
        list($start_time, $end_time) = $this->get_in_date_range(date("Y-m-01",time()),0, 0,[],3 );
        $teacher_ref_type            = $this->get_in_int_val("teacher_ref_type",-1);
        $teacher_money_type          = $this->get_in_int_val("teacher_money_type",-1);
        $teacher_type                = $this->get_in_int_val("teacher_type",-1);
        $level                       = $this->get_in_int_val("level",-1);
        $show_data                   = $this->get_in_int_val("show_data");
        $show_type                   = $this->get_in_str_val("show_type","current");
        $acc                         = $this->get_account();

        $this->switch_tongji_database();
        $tea_list = $this->t_lesson_info->get_tea_month_list(
            $start_time,$end_time,$teacher_ref_type,$teacher_type,$teacher_money_type,$level,$show_type
        );

        if($teacher_type==-1){
            //公司全职老师列表 full_tea_list
            $full_start_time = strtotime("-1 month",$start_time);
            $full_tea_list = $this->t_lesson_info->get_tea_month_list(
                $full_start_time,$start_time,$teacher_ref_type,E\Eteacher_type::V_3,$teacher_money_type,$level,$show_type
            );
            $list = array_merge($tea_list,$full_tea_list);
        }else{
            $list = $tea_list;
        }

        $all_lesson_1v1   = 0;
        $all_lesson_trial = 0;
        $all_lesson_total = 0;
        $num              = 1;
        foreach($list as &$val){
            \App\Helper\Utils::check_isset_data($val['lesson_1v1'],0,0);
            \App\Helper\Utils::check_isset_data($val['lesson_trial'],0,0);
            \App\Helper\Utils::check_isset_data($val['lesson_total'],0,0);

            E\Eteacher_money_type::set_item_value_str($val);
            $val['level_str']=\App\Helper\Utils::get_teacher_letter_level($val['teacher_money_type'],$val['level']);
            E\Esubject::set_item_value_str($val);
            $val['lesson_1v1']   /= 100;
            $val['lesson_trial'] /= 100;
            $val['lesson_total'] /= 100;
            \App\Helper\Utils::unixtime2date_for_item($val,"create_time","_str");
            $all_lesson_1v1   += $val['lesson_1v1'];
            $all_lesson_trial += $val['lesson_trial'];
            $all_lesson_total += $val['lesson_total'];
            $val['id'] = $num;
            $num++;
        }

        if($show_data){
            $list = \App\Helper\Utils::list_to_page_info($list);
        }else{
            $list = \App\Helper\Utils::list_to_page_info([]);
        }

        return $this->pageView(__METHOD__,$list,[
            "all_lesson_total" => $all_lesson_total,
            "all_lesson_1v1"   => $all_lesson_1v1,
            "all_lesson_trial" => $all_lesson_trial,
            "show_data"        => $show_data,
            "acc"              => $acc,
        ]);
    }

    public function tea_wages_count_list(){
        list($start_time,$end_time) = $this->get_in_date_range(date("Y-m-01",strtotime("-1 month",time())),0, 0,[],3 );

        $this->t_lesson_info->switch_tongji_database();
        $list = $this->t_lesson_info->get_tea_wages_count_list($start_time,$end_time);

        $ret_list  = [];
        $all_value = &$ret_list['总计']['all'];
        foreach( $list as $key=>&$val){
            if(!isset($all_value['tea_num'])){
                $all_value['tea_num']=0;
            }
            if(!isset($all_value['lesson_num'])){
                $all_value['lesson_num']=0;
            }
            if(!isset($all_value['lesson_total'])){
                $all_value['lesson_total']=0;
            }
            $all_value['tea_num']      += $val['tea_num'];
            $all_value['lesson_num']   += $val['lesson_num'];
            $all_value['lesson_total'] += $val['lesson_total'];

            $ret_list[$val['teacher_money_type']][$val['level']]['tea_num']      = $val['tea_num'];
            $ret_list[$val['teacher_money_type']][$val['level']]['lesson_num']   = $val['lesson_num'];
            $ret_list[$val['teacher_money_type']][$val['level']]['lesson_total'] = $val['lesson_total'];
        }

        $ret = $this->get_teacher_1v1_money($start_time,$end_time);
        foreach( $ret as $key=>$val ){
            foreach($val as $k=>$v){
                if(isset($ret_list[$key][$k])){
                    if(!isset($all_value['cost'])){
                        $all_value['cost']=0;
                    }
                    if(!isset($all_value['price'])){
                        $all_value['price']=0;
                    }
                    if(!isset($all_value['lesson_price'])){
                        $all_value['lesson_price']=0;
                    }
                    if(!isset($all_value['last_price'])){
                        $all_value['last_price']=0;
                    }

                    $all_value['cost']         += $v['cost'];
                    $all_value['price']        += $v['price'];
                    $all_value['lesson_price'] += $v['lesson_price'];
                    $all_value['last_price']   += $v['last_price'];

                    $ret_list[$key][$k]['cost']         = $v['cost'];
                    $ret_list[$key][$k]['price']        = $v['price'];
                    $ret_list[$key][$k]['lesson_price'] = $v['lesson_price'];
                    $ret_list[$key][$k]['last_price']   = $v['last_price'];
                }
            }
        }

        $all_tea_num      = $all_value['tea_num'];
        $all_lesson_num   = $all_value['lesson_num'];
        $all_lesson_total = $all_value['lesson_total'];
        $all_cost         = $all_value['cost'];
        $all_price        = $all_value['price'];
        $all_lesson_price = $all_value['lesson_price'];
        $all_last_price   = $all_value['last_price'];

        $result   = [];
        $last_key = "";
        foreach($ret_list as $key=>$val){
            if($key==="总计"){
                $data['teacher_money_type_str'] = "总计";
            }else{
                $data['teacher_money_type_str'] = E\Eteacher_money_type::get_desc($key);
            }
            if($last_key == ""){
                $last_key = $key;
            }

            foreach($val as $k=>$v){
                if($k==="all"){
                    $data['level_str'] = "";
                }else{
                    $data['level_str'] = \App\Helper\Utils::get_teacher_letter_level($key,$k);
                }

                $data['tea_num']              = $v['tea_num'];
                $data['tea_num_percent']      = $this->get_price_percent($v['tea_num'],$all_tea_num);
                $data['lesson_num']           = $v['lesson_num'];
                $data['lesson_num_percent']   = $this->get_price_percent($v['lesson_num'], $all_lesson_num);
                $data['lesson_total']         = $v['lesson_total']/100;
                $data['lesson_total_percent'] = $this->get_price_percent($v['lesson_total'], $all_lesson_total);
                $data['cost']                 = isset($v['cost'])?$v['cost']:0;
                $data['cost_percent']         = $this->get_price_percent($data['cost'],$all_cost);
                $data['price']                = isset($v['price'])?$v['price']:0;
                $data['price_percent']        = $this->get_price_percent($data['price'],$all_price);
                $data['lesson_price']         = isset($v['lesson_price'])?$v['lesson_price']:0;
                $data['lesson_price_percent'] = $this->get_price_percent($data['lesson_price'],$all_lesson_price);
                $data['last_price']           = isset($v['last_price'])?$v['last_price']:0;
                $data['last_price_percent']   = $this->get_price_percent($data['last_price'],$all_last_price);
                $data['final_percent']        = $this->get_price_percent($data['last_price'],$data['lesson_price']);

                $result[] = $data;
            }

            if($last_key != $key){
                $last_key = $key;
            }
        }
        $result = \App\Helper\Utils::list_to_page_info($result);

        return $this->pageView(__METHOD__,$result);
    }

    /**
     * 获取时间范围内的课程收入
     * @param start_time 开始时间
     * @param end_time   结束时间
     * @return float     结束时间
     */
    public function get_lesson_price(){
        $start_date = $this->get_in_str_val("start_time");

        $start_time = strtotime($start_date);
        $end_time   = strtotime("+1 month",$start_time);

        $money_list = $this->t_order_lesson_list->get_all_lesson_money($start_time,$end_time);
        $lesson_price = 0;
        foreach($money_list as $val){
            $lesson_price += $val['lesson_money'];
        }
        $lesson_price = round($lesson_price,2);
        return $this->output_succ(['lesson_price'=>$lesson_price]);
    }

    private function get_price_percent($price,$all_price){
        if($all_price!=0){
            $price_percent = (round($price/$all_price,4)*100).'%';
        }else{
            $price_percent = "";
        }
        return $price_percent;
    }

    public function get_teacher_1v1_money($start,$end){
        $start_fourth = strtotime("-1 month",$start);
        $end_fourth   = strtotime("-1 month",$end);

        $fourth_list = $this->t_lesson_info->get_all_fourth_teacher_already_lesson_count($start_fourth,$end_fourth);
        $list = $this->t_lesson_info->get_teacher_1v1_money($start,$end);

        $teacher_money = \App\Helper\Config::get_config("teacher_money");
        $deduct_type   = E\Elesson_deduct::$s2v_map;
        $deduct_info   = E\Elesson_deduct::$desc_map;

        $late   = [];
        $change = [];
        $ret    = [];
        foreach($list as $key=>$val){
            $teacherid          = $val['teacherid'];
            $teacher_money_type = $val['teacher_money_type'];
            $level              = $val['level'];
            $lesson_count       = $val['lesson_count']/100;
            $all_cost           = 0;

            if(!in_array($teacher_money_type,[0,1,2,3])){
                $already_lesson_count = isset($fourth_list[$teacherid]['already_lesson_count'])?$fourth_list[$teacherid]['already_lesson_count']:0;
            }else{
                $already_lesson_count = $val['already_lesson_count'];
            }

            if(!isset($late[$teacherid]['late_num'])){
                $late[$teacherid]['late_num']=0;
            }
            if(!isset($change[$teacherid]['change_num'])){
                $change[$teacherid]['change_num']=0;
            }
            if(!isset($ret[$teacher_money_type][$level]['cost'])){
                $ret[$teacher_money_type][$level]['cost']=0;
            }
            if(!isset($ret[$teacher_money_type][$level]['price'])){
                $ret[$teacher_money_type][$level]['price']=0;
            }
            if(!isset($ret[$teacher_money_type][$level]['lesson_price'])){
                $ret[$teacher_money_type][$level]['lesson_price']=0;
            }

            if($val['confirm_flag']==2 && $val['deduct_change_class']>0){
                if($val['lesson_cancel_reason_type']==21){
                    $all_cost += $teacher_money['lesson_miss_cost']/100;
                }elseif(($val['lesson_cancel_reason_type']==2 || $val['lesson_cancel_reason_type']==12)
                        && $val['lesson_cancel_time_type']==1){
                    if($change[$teacherid]['change_num']>=3){
                        $all_cost += $teacher_money['lesson_cost']/100;
                    }else{
                        $change[$teacherid]['change_num']++;
                    }
                }
            }else{
                foreach($deduct_type as $key=>$item){
                    if($val['deduct_change_class']==0){
                        if($val[$key]>0){
                            if($key=="deduct_come_late" && $late[$teacherid]['late_num']<3){
                                $late[$teacherid]['late_num']++;
                            }else{
                                $all_cost += $teacher_money['lesson_cost']/100;
                            }
                        }
                    }
                }
            }

            $reward = \App\Helper\Utils::get_teacher_lesson_money($val['type'],$already_lesson_count);
            $full   = \App\Helper\Utils::get_lesson_full_reward($val['lesson_full_num']);
            $base   = $val['money']*$lesson_count;
            $all    = $reward+$full+$base;

            $ret[$teacher_money_type][$level]['price']        += $all;
            $ret[$teacher_money_type][$level]['cost']         += $all_cost;
            $ret[$teacher_money_type][$level]['lesson_price'] += $val['price'];
        }

        foreach($ret as &$val){
            foreach($val as &$item){
                if($start<strtotime("2016-12-1")){
                    $item['cost']       = 0;
                    $item['last_price'] = $item['price'];
                }else{
                    $item['last_price'] = $item['price']-$item['cost'];
                }
                $item['lesson_price'] /= 100;
            }
        }

        return $ret;
    }

    public function teacher_details_money(){
        list($start_time,$end_time) = $this->get_in_date_range(0,0,0,null,3);

        $begin_time = $start_time;
        $final_time = $end_time;
        $start_time = 0;
        $end_time   = 0;
        $count_list = [];
        $this->t_lesson_info->switch_tongji_database();
        for($i=0;$end_time<$final_time;$i++){
            $start_time = strtotime("+$i month",$begin_time);
            $end_time   = strtotime("+1 month",$start_time);

            $tea_list   = $this->t_lesson_info->get_tea_month_list(
                $start_time,$end_time,-1,0,-1,-1
            );
            $full_start_time = strtotime("-1 month",$start_time);
            $full_tea_list   = $this->t_lesson_info->get_tea_month_list(
                $full_start_time,$start_time,-1,3,-1,-1
            );
            $list = array_merge($tea_list,$full_tea_list);

            $date_str = date("m-d",$start_time);
            $stu_num  = $this->t_lesson_info->get_stu_total($start_time,$end_time);
            $count_list[$date_str]["all"]["all"] = [
                "stu_num"       => $stu_num,
                "teacher_1v1"   => 0,
                "teacher_trial" => 0,
                "teacher_num"   => 0,
                "lesson_total"  => 0,
                "lesson_1v1"    => 0,
                "lesson_trial"  => 0,
                "lesson_money"  => 0,
            ];
            if(is_array($list) && !empty($list)){
                foreach($list as &$val){
                    $teacher_money_type = (string)$val['teacher_money_type'];
                    $level              = (string)$val['level'];

                    if(!isset($count_list[$date_str][$teacher_money_type]["all"]["stu_num"])){
                        $stu_num = $this->t_lesson_info->get_stu_total($start_time,$end_time,$teacher_money_type);
                        $count_list[$date_str][$teacher_money_type]["all"]["stu_num"] = $stu_num;
                    }

                    if(!isset($count_list[$date_str][$teacher_money_type][$level]["stu_num"])){
                        $stu_num = $this->t_lesson_info->get_stu_total($start_time,$end_time,$teacher_money_type,$level);
                        $count_list[$date_str][$teacher_money_type][$level]["stu_num"] = $stu_num;
                    }

                    if(!isset($count_list[$date_str][$teacher_money_type][$level]["stu_num"])){
                        $stu_num = $this->t_lesson_info->get_stu_total($start_time,$end_time,$teacher_money_type,$level);
                        $count_list[$date_str][$teacher_money_type][$level]["stu_num"] = $stu_num;
                    }

                    \App\Helper\Utils::check_isset_data($count_list[$date_str]["all"]["all"]["teacher_1v1"],0);
                    \App\Helper\Utils::check_isset_data($count_list[$date_str][$teacher_money_type]["all"]["teacher_1v1"],0);
                    \App\Helper\Utils::check_isset_data($count_list[$date_str][$teacher_money_type][$level]["teacher_1v1"],0);
                    \App\Helper\Utils::check_isset_data($count_list[$date_str]["all"]["all"]["teacher_trial"],0);
                    \App\Helper\Utils::check_isset_data($count_list[$date_str][$teacher_money_type]["all"]["teacher_trial"],0);
                    \App\Helper\Utils::check_isset_data($count_list[$date_str][$teacher_money_type][$level]["teacher_trial"],0);
                    if($val['lesson_1v1']>0){
                        \App\Helper\Utils::check_isset_data($count_list[$date_str]["all"]["all"]["teacher_1v1"]);
                        \App\Helper\Utils::check_isset_data($count_list[$date_str][$teacher_money_type]["all"]["teacher_1v1"],1);
                        \App\Helper\Utils::check_isset_data($count_list[$date_str][$teacher_money_type][$level]["teacher_1v1"],1);
                    }else{
                        \App\Helper\Utils::check_isset_data($count_list[$date_str]["all"]["all"]["teacher_trial"]);
                        \App\Helper\Utils::check_isset_data($count_list[$date_str][$teacher_money_type]["all"]["teacher_trial"],1);
                        \App\Helper\Utils::check_isset_data($count_list[$date_str][$teacher_money_type][$level]["teacher_trial"],1);
                    }

                    $lesson_1v1   = $val['lesson_1v1']/100;
                    $lesson_trial = $val['lesson_trial']/100;
                    $lesson_total = $val['lesson_total']/100;
                    \App\Helper\Utils::check_isset_data($count_list[$date_str]["all"]["all"]["lesson_1v1"],$lesson_1v1);
                    \App\Helper\Utils::check_isset_data($count_list[$date_str][$teacher_money_type]["all"]["lesson_1v1"],$lesson_1v1);
                    \App\Helper\Utils::check_isset_data($count_list[$date_str][$teacher_money_type][$level]["lesson_1v1"],$lesson_1v1);
                    \App\Helper\Utils::check_isset_data($count_list[$date_str]["all"]["all"]["lesson_trial"],$lesson_trial);
                    \App\Helper\Utils::check_isset_data($count_list[$date_str][$teacher_money_type]["all"]["lesson_trial"],$lesson_trial);
                    \App\Helper\Utils::check_isset_data($count_list[$date_str][$teacher_money_type][$level]["lesson_trial"],$lesson_trial);
                    \App\Helper\Utils::check_isset_data($count_list[$date_str]["all"]["all"]["lesson_total"],$lesson_total);
                    \App\Helper\Utils::check_isset_data($count_list[$date_str][$teacher_money_type]["all"]["lesson_total"],$lesson_total);
                    \App\Helper\Utils::check_isset_data($count_list[$date_str][$teacher_money_type][$level]["lesson_total"],$lesson_total);
                    \App\Helper\Utils::check_isset_data($count_list[$date_str]["all"]["all"]["teacher_num"]);
                    \App\Helper\Utils::check_isset_data($count_list[$date_str][$teacher_money_type]["all"]["teacher_num"]);
                    \App\Helper\Utils::check_isset_data($count_list[$date_str][$teacher_money_type][$level]["teacher_num"]);
                }
            }
        }

        $ret_info           = [];
        $teacher_money_type = [];
        $level              = [];
        foreach($count_list as $date_key=>&$date_val){
            foreach($date_val as $money_key=>&$money_val){
                foreach($money_val as $level_key=>&$level_val){
                    $teacher_money_type[] = $money_key;
                    $level_val['date_str'] = $date_key;
                    if($money_key!="all" || $money_key=="0"){
                        $money_key = E\Eteacher_money_type::get_desc($money_key);
                    }
                    $level_val['teacher_money_type_str'] = $money_key;
                    if($level_key!="all" || $level_key=="0"){
                        $level_key= E\Elevel::get_desc($level_key);
                    }
                    $level_val['level_str'] = $level_key;
                    if($level_val['teacher_num']>0){
                        $level_val['tea_stu_ratio'] = round($level_val["stu_num"]/$level_val['teacher_num'],2);
                        $level_val['per_total']     = round($level_val['lesson_1v1']/$level_val["teacher_num"],2);
                    }else{
                        $level_val['tea_stu_ratio'] = 0;
                        $level_val['per_total']     = 0;
                    }

                    $ret_info[]=$level_val;
                }
            }
        }

        array_multisort($teacher_money_type,SORT_ASC,$ret_info);
        $ret_info = \App\Helper\Utils::list_to_page_info($ret_info);
        return $this->pageView(__METHOD__,$ret_info);
    }

    public function wx_monitor_new_yy(){
        return $this->wx_monitor_new();
    }
    public function wx_monitor_new(){
        $page_num=$this->get_in_page_num();
        $ret_info = $this->t_send_wx_template_record_list->get_send_wx_template_record_list($page_num);
        foreach($ret_info['list'] as &$item){
            $item["send_time_str"] = date("Y-m-d H:i:s",$item["send_time"]);
            E\Etemplate_type::set_item_value_str($item,"template_type");
        }
        return $this->pageView(__METHOD__, $ret_info );
    }

    public function teacher_send_video_list(){
        list($start_time,$end_time) = $this->get_in_date_range(date('Y-m-01',time()), 0 );
        $page_num=$this->get_in_page_num();
        $subject = $this->get_in_int_val("subject",-1);
        $ret_info = $this->t_good_video_send_list->get_all_video_senf_info($start_time,$end_time,$subject,$page_num);
        foreach($ret_info["list"] as &$item){
            $item["send_time_str"] = date("Y-m-d H:i:s",$item["send_time"]);
            E\Egrade::set_item_value_str($item,"grade");
            E\Esubject::set_item_value_str($item,"subject");
        }

        return $this->pageView(__METHOD__, $ret_info );
    }

    public function set_gift_status(){
        $exchangeid = $this->get_in_int_val("exchangeid");
        $status     = $this->get_in_int_val("status");

        $ret=$this->t_gift_consign->field_update_list($exchangeid,[
            "status"=>$status
        ]);

        if($ret){
            return $this->output_succ();
        }else{
            return $this->output_err("设置失败！");
        }
    }

    public function user_power_group_edit() {
        $group_list = $this->t_user_authority_group->get_auth_groups();
        $role    = $this->get_in_int_val("role",2);
        $groupid = $this->get_in_int_val("groupid",1);

        $power_map = $this->t_authority_group->get_auth_group_map($groupid);
        $list      = $this->get_tea_admin_menu_list($power_map );

        $ret_info=\App\Helper\Utils::list_to_page_info($list);
        return $this->Pageview(__METHOD__,$ret_info,[
            "group_list" => $group_list,
        ]);
    }

    public function cancel_refund(){
        $orderid    = $this->get_in_int_val("orderid");
        $apply_time = $this->get_in_int_val("apply_time");

        $refund_info = $this->t_order_refund->field_get_list_2($orderid,$apply_time,"*");
        if($refund_info['refund_status']==1){
            return $this->output_err("此退费已打款，无法取消!");
        }

        $order_info = $this->t_order_info->get_order_info_by_orderid($orderid);
        $order_info['lesson_left'] += $refund_info['should_refund'];
        $this->t_order_info->start_transaction();
        $ret = $this->t_order_info->field_update_list($orderid,[
            "lesson_left"     => $order_info['lesson_left'],
            "contract_status" => 1,
        ]);

        if($ret>0){
            $ret = $this->t_order_refund->row_delete_2($orderid,$apply_time);
            if($ret>0){
                $this->t_order_info->commit();
                return $this->output_succ();
            }else{
                $this->t_order_info->rollback();
                return $this->output_err("退费记录删除失败!");
            }
        }else{
            $this->t_order_info->rollback();
            return $this->output_err("合同课时变更失败!");
        }
    }

    public function ass_warning_stu_info(){
        $account_id = $this->get_account_id();
        //  $account_id = 297;
        $main_type = 1;
        $is_master = $this->t_admin_main_group_name->check_is_master($main_type,$account_id);
        if($is_master>0 || in_array($account_id,[349,188,74,944]) ){
            $up_master_adminid=-1;
        }else{
            $up_master_adminid=0;
        }

        list($start_time, $end_time) = $this->get_in_date_range(0,0, 0,[],2 );
        $page_num    = $this->get_in_page_num();
        $leader_flag = $this->get_in_int_val("leader_flag",0);
        $assistantid = $this->get_in_int_val("assistantid",-1);
        $ass_renw_flag = $this->get_in_int_val("ass_renw_flag",-1);
        $master_renw_flag = $this->get_in_int_val("master_renw_flag",-1);
        $renw_week = $this->get_in_int_val("renw_week",-1);
        $end_week = $this->get_in_int_val("end_week",-1);
        $ret_info    = $this->t_month_ass_warning_student_info->get_all_info_by_month(
            $start_time,$page_num,$up_master_adminid,$account_id,$leader_flag,$assistantid,$ass_renw_flag,$master_renw_flag,$renw_week,$end_week,1);

        foreach($ret_info["list"] as &$item){
             E\Erenw_type::set_item_value_str($item,"ass_renw_flag");
             E\Erenw_type::set_item_value_str($item,"master_renw_flag");
        }
        return $this->Pageview(__METHOD__,$ret_info);
    }

    public function ass_warning_stu_info_leader()
    {
        $leader_flag = 1;

        $this->set_in_value("leader_flag",$leader_flag);

        return $this->ass_warning_stu_info();
    }

    public function ass_warning_stu_info_leader_new()
    {
        $leader_flag = 1;

        $this->set_in_value("leader_flag",$leader_flag);

        return $this->ass_warning_stu_info_new();
    }


    public function ass_warning_stu_info_new(){
        $account_id = $this->get_account_id();
        $adminid    = $this->get_ass_leader_account_id($account_id);
        $main_type = 1;
        $is_master = $this->t_admin_main_group_name->check_is_master($main_type,$account_id);
        if($is_master>0 || in_array($account_id,[349,188,74]) ){
            $up_master_adminid=-1;
        }else{
            $up_master_adminid=0;
        }

        $page_num    = $this->get_in_page_num();
        $leader_flag = $this->get_in_int_val("leader_flag",0);
        $assistantid = $this->get_in_int_val("assistantid",-1);
        $ass_renw_flag = $this->get_in_int_val("ass_renw_flag",-1);
        $master_renw_flag = $this->get_in_int_val("master_renw_flag",-1);
        $renw_week = $this->get_in_int_val("renw_week",-1);
        $end_week = $this->get_in_int_val("end_week",-1);
        $done_flag = $this->get_in_int_val("done_flag",0);
        $id = $this->get_in_int_val("id",-1);
        $ret_info    = $this->t_month_ass_warning_student_info->get_all_info_by_month_new(
            $page_num,$up_master_adminid,$account_id,$leader_flag,$assistantid,$ass_renw_flag,$master_renw_flag,$renw_week,$end_week,2,$adminid,$done_flag,$id);

        foreach($ret_info["list"] as &$item){
            E\Erenw_type::set_item_value_str($item,"ass_renw_flag");
            E\Erenw_type::set_item_value_str($item,"master_renw_flag");
            $change_info = $this->t_ass_warning_renw_flag_modefiy_list->get_new_renw_list($item["id"]);
            if(!empty($change_info["renw_week"])){
                $item["renw_end_day"] = date("Y-m-d", $change_info["add_time"]+ $change_info["renw_week"]*7*86400);
            }else{
                $item["renw_end_day"]="";
            }
            $item["month_str"] = date("Y-m-d H:i:s",$item["month"]);
            $first_time = $this->t_ass_warning_renw_flag_modefiy_list->get_first_renw_time($item["id"]);
            if(empty($first_time)){
                $item["first_time"]="无";
            }else{
                $item["first_time"] = date("Y-m-d H:i:s",$first_time);
            }

        }
        return $this->Pageview(__METHOD__,$ret_info);
    }


    public function get_order_info(){
        $orderid = $this->get_in_int_val("orderid");

        $order_info             = $this->t_order_info->get_order_info_by_orderid($orderid);
        $order_info['realname'] = $this->t_student_info->get_realname($order_info['userid']);
        $order_info['phone']    = $this->t_student_info->get_phone($order_info['userid']);
        if($order_info['discount_price']==0){
            $order_info['discount_price'] = $order_info['price'];
        }
        $order_info['price']          /= 100;
        $order_info['lesson_left']    /= 100;
        $order_info['discount_price'] /= 100;
        $order_info['lesson_total']    = $order_info['lesson_total']*$order_info['default_lesson_count']/100;
        $order_info['per_price']       = $order_info['discount_price']/$order_info['lesson_total'];

        return $this->output_succ(["data"=>$order_info]);
    }

    public function teacher_trial_reward_list(){
        list($start_time,$end_time) = $this->get_in_date_range(date("Y-m-d",time()),0,0,null,3);
        $teacherid  = $this->get_in_int_val("teacherid",-1);
        $type       = $this->get_in_int_val("type",-1);
        $lessonid   = $this->get_in_int_val("lessonid",-1);
        $has_lesson = $this->get_in_int_val("has_lesson",-1);
        $page_num = $this->get_in_page_num();

        $list = $this->t_teacher_money_list->get_teacher_trial_reward_list(
            $page_num,$start_time,$end_time,$teacherid,$type,$lessonid,$has_lesson
        );

        foreach($list['list'] as &$val){
            $val['tea_nick'] = $this->cache_get_teacher_nick($val['teacherid']);
            \App\Helper\Utils::unixtime2date_for_item($val,"add_time","_str");
            E\Ereward_type::set_item_value_str($val,"type");
            $val['money'] /= 100;

            if(in_array($val['type'],[E\Ereward_type::V_2,E\Ereward_type::V_3])){
                $val['money_info_extra'] = $this->cache_get_student_nick($val['userid']);
            }elseif($val['type']==E\Ereward_type::V_6){
                $identity = E\Eidentity::get_desc($val['identity']);
                $val['money_info_extra'] = $val['realname']."|".$identity;
            }else{
                $val['money_info_extra'] = "";
            }
        }

        return $this->Pageview(__METHOD__,$list, [
            'teacherid' => $teacherid
        ]);
    }

    public function get_ass_change_teacher_info(){
        $id = $this->get_in_int_val("id",-1);
        list($start_time,$end_time) = $this->get_in_date_range(0,0,0,null,3);
        $adminid = $this->get_account_id();
        if(in_array($adminid,[349,72,448,99,478])){
            $adminid=-1;
        }
        $page_num        = $this->get_in_page_num();
        $ass_adminid     = $this->get_in_int_val("ass_adminid",-1);
        $accept_flag     = $this->get_in_int_val("accept_flag",-1);
        $require_adminid = $this->get_in_int_val("require_adminid",-1);
        $accept_adminid = $this->get_in_int_val("accept_adminid",$adminid);
        $ret_info        = $this->t_change_teacher_list->get_ass_change_teacher_info($start_time,$end_time,$ass_adminid,$page_num,$id,$require_adminid,$accept_adminid,$accept_flag,1);
        $start_index = \App\Helper\Utils::get_start_index_from_ret_info($ret_info) ;
        $domain      = config('admin')['qiniu']['public']['url'];
        $num         = strlen($domain)+1;
        foreach($ret_info["list"] as $k=>&$val){
            $val["id_index"] = $start_index+$k;
            \App\Helper\Utils::unixtime2date_for_item($val,"add_time","_str");
            \App\Helper\Utils::unixtime2date_for_item($val,"accept_time","_str");
            \App\Helper\Utils::unixtime2date_for_item($val,"done_time","_str");
            E\Esubject::set_item_value_str($val);
            E\Egrade::set_item_value_str($val);
            E\Echange_teacher_reason_type::set_item_value_str($val);
            $num_url    = strlen($val["change_reason_url"]);
            $val["url"] = substr($val["change_reason_url"],$num,$num_url-1);
            if($val["accept_time"]>0){
                $val["deal_time"] = round(($val["accept_time"] - $val["add_time"])/3600,2);
            }
            if($val["is_done_flag"]==1){
                $val["is_done_flag_str"]="已解决";
            }elseif($val["is_done_flag"]==2){
                if($val["is_resubmit_flag"]==0){
                    $val["is_done_flag_str"]="未解决";
                }else{
                    $val["is_done_flag_str"]="未解决,已重新提交换老师申请";
                }
            }


        }

        return $this->Pageview(__METHOD__,$ret_info);
    }

    public function get_ass_change_teacher_info_ass(){
        $adminid = $this->get_account_id();
        $this->set_in_value("ass_adminid",$adminid);
        $this->set_in_value("accept_adminid",-1);
        return $this->get_ass_change_teacher_info();
    }

    public function update_teacher_money_list_info(){
        $id           = $this->get_in_int_val("id");
        $type         = $this->get_in_int_val("type");
        $money_info   = $this->get_in_str_val("money_info");
        $money        = $this->get_in_str_val("money");
        $add_time     = $this->get_in_str_val("add_time");
        $teacherid    = $this->get_in_int_val("teacherid");
        $account      = $this->get_account();

        // $add_time_old = strtotime($this->get_in_str_val("add_time_old"));
        $old_reward_info = $this->t_teacher_money_list->get_reward_info_by_id($id);
        $add_time_old    = $old_reward_info['add_time'];
        $update_arr = [
            "type"       => $type,
            "money_info" => $money_info,
            "money"      => $money,
            "acc"        => $account,
            "teacherid"  => $teacherid,
        ];

        if($add_time!=""){
            $add_time = strtotime($add_time);
            if($add_time!=$add_time_old && !in_array($account,['adrian','sunny','jim'])){
                return $this->output_err("你没有权限更改时间！");
            }
            $update_arr["add_time"] = $add_time;
        }
        $check_old_time_flag = \App\Helper\Utils::check_teacher_salary_time($add_time_old);
        if(!$check_old_time_flag){
            return $this->output_err("无法修改，本条额外奖金已经结算！");
        }
        $check_time_flag = \App\Helper\Utils::check_teacher_salary_time($add_time);
        if(!$check_time_flag){
            return $this->output_err("无法更改，不能设置到已经结算工资的月份中！");
        }

        $ret = $this->t_teacher_money_list->field_update_list($id,$update_arr);
        if($ret){
            $log_arr = [
                "old_data" => $old_reward_info,
                "new_data" => $update_arr,
            ];
            $msg = json_encode($log_arr);
            $this->t_user_log->add_user_log($teacherid,$msg,E\Euser_log_type::V_200);
        }

        return $this->output_ret($ret);
    }

    //删除老师额外奖金记录
    public function delete_teacher_reward(){
        $id = $this->get_in_int_val("id");

        $add_time   = $this->t_teacher_money_list->get_add_time($id);
        $check_flag = \App\Helper\Utils::check_teacher_salary_time($add_time);
        if(!$check_flag){
            return $this->output_err("超出时间，无法删除! \n 只能删除本月数据！");
        }
        $old_reward_info = $this->t_teacher_money_list->get_reward_info_by_id($id);

        $ret = $this->t_teacher_money_list->row_delete($id);
        if($ret){
            $log_arr = [
                "delete_info" => $old_reward_info
            ];
            $msg = json_encode($log_arr);
            $this->t_user_log->add_user_log($old_reward_info['teacherid'],$msg,E\Euser_log_type::V_200);
        }

        return $this->output_ret($ret,"删除失败！请重试！");
    }

    public function ass_revisit_warning_info_old(){
        list($start_time,$end_time) = $this->get_in_date_range(0,0,0,null,3);
        $page_num             = $this->get_in_page_num();
        $is_warning_flag      = $this->get_in_int_val("is_warning_flag",1);
        $ass_adminid          = $this->get_in_int_val("ass_adminid",-1);
        $seller_groupid_ex    = $this->get_in_str_val('seller_groupid_ex', "");
        $require_adminid_list = $this->t_admin_main_group_name->get_adminid_list_new($seller_groupid_ex);
        $adminid_right        = $this->get_seller_adminid_and_right();
        $revisit_warning_type = $this->get_in_str_val('revisit_warning_type',-1);

        $this->t_revisit_info->switch_tongji_database();
        // $ret_info      = $this->t_revisit_info->get_ass_revisit_warning_info($start_time,$end_time,$page_num,$is_warning_flag,$ass_adminid,$require_adminid_list);
        $ret_info = $this->t_revisit_info->get_ass_revisit_warning_info_new($start_time,$end_time,$page_num,$is_warning_flag,$ass_adminid,$require_adminid_list,$revisit_warning_type);

        $now = time();
        $three = $now - 86400*7;
        $warning_count = $this->t_revisit_info->get_ass_revisit_warning_count($ass_adminid, $three);

        $warning_type_num = [
            'warning_type_one'   => 0,
            'warning_type_two'   => 0,
            'warning_type_three' => 0,
        ];
        foreach($warning_count as $item){
            \App\Helper\Utils::revisit_warning_type_count($item, $warning_type_num);
        }

        $opt_date_type = $this->get_in_int_val("opt_date_type",3);
        // dd($opt_date_type);
        if($opt_date_type==3){
            $cur_start = $start_time;
            $cur_end = $end_time;

        }else{
            $cur_start = strtotime(date('Y-m-01',$end_time));
            $cur_end = strtotime(date('Y-m-01',$cur_start+40*86400));
        }
        $three_count = $this->t_revisit_warning_overtime_info->get_ass_warning_overtime_count($ass_adminid,-1,$cur_start,$cur_end);
        $warning_type_num['warning_type_three'] = $three_count;

        foreach($ret_info['list'] as &$item){
            \App\Helper\Utils::unixtime2date_for_item($item,"revisit_time", "_str");
            E\Erevisit_type::set_item_value_str($item);
            E\Eset_boolean::set_item_value_str($item,"operation_satisfy_flag");
            E\Eset_boolean::set_item_value_str($item,"school_work_change_flag");
            E\Etea_content_satisfy_flag::set_item_value_str($item,"tea_content_satisfy_flag");
            E\Eschool_work_change_type::set_item_value_str($item,"school_work_change_type");
            E\Eschool_score_change_flag::set_item_value_str($item,"school_score_change_flag");
            E\Eoperation_satisfy_type::set_item_value_str($item,"operation_satisfy_type");
            E\Etea_content_satisfy_type::set_item_value_str($item,"tea_content_satisfy_type");
            E\Echild_class_performance_flag::set_item_value_str($item,"child_class_performance_flag");
            E\Echild_class_performance_type::set_item_value_str($item,"child_class_performance_type");
            E\Eis_warning_flag::set_item_value_str($item,"is_warning_flag");
        }

        return $this->pageView(__METHOD__,$ret_info,[
            "adminid_right" => $adminid_right,
            "warning"       => $warning_type_num
        ] );
    }

    public function ass_revisit_warning_info(){
        list($start_time,$end_time) = $this->get_in_date_range(0,0,0,null,3);
        $page_num             = $this->get_in_page_num();
        $is_warning_flag      = $this->get_in_int_val("is_warning_flag",1);
        $ass_adminid          = $this->get_in_int_val("ass_adminid",-1);
        $seller_groupid_ex    = $this->get_in_str_val('seller_groupid_ex', "");
        $require_adminid_list = $this->t_admin_main_group_name->get_adminid_list_new($seller_groupid_ex);
        $adminid_right        = $this->get_seller_adminid_and_right();
        $revisit_warning_type = $this->get_in_str_val('revisit_warning_type',-1);

        //获取组长的所有组员
        if($ass_adminid == -1 ) {
            $adminid = $this->get_account_id();
            $uid_str = $this->t_manager_info->get_uid_str_by_adminid($adminid);
        } else {
            $uid_str = -1;
        }


        $this->t_revisit_info->switch_tongji_database();
        // $ret_info = $this->t_revisit_info->get_ass_revisit_warning_info($start_time,$end_time,$page_num,$is_warning_flag,$ass_adminid,$require_adminid_list);
        $ret_info = $this->t_revisit_info->get_ass_revisit_warning_info_new($start_time,$end_time,$page_num,$is_warning_flag,$ass_adminid,$require_adminid_list,$revisit_warning_type,$uid_str);

        $now = time();
        $three = $now - 86400*7;
        $warning_count = $this->t_revisit_info->get_ass_revisit_warning_count($ass_adminid, $three,$uid_str);

        $warning_type_num = [
            'warning_type_one' =>0,
            'warning_type_two' =>0,
            'warning_type_three' =>0,
        ];
        foreach($warning_count as $item){
            \App\Helper\Utils::revisit_warning_type_count($item, $warning_type_num);
        }

        $opt_date_type = $this->get_in_int_val("opt_date_type",3);
        // dd($opt_date_type);
        if($opt_date_type==3){
            $cur_start = $start_time;
            $cur_end = $end_time;

        }else{
            $cur_start = strtotime(date('Y-m-01',$end_time));
            $cur_end = strtotime(date('Y-m-01',$cur_start+40*86400));
        }
        $three_count = $this->t_revisit_warning_overtime_info->get_ass_warning_overtime_count($ass_adminid, $uid_str,$cur_start,$cur_end);
        $warning_type_num['warning_type_three'] = $three_count;

        foreach($ret_info['list'] as &$item){
            \App\Helper\Utils::unixtime2date_for_item($item,"revisit_time", "_str");
            E\Erevisit_type::set_item_value_str($item);
            E\Eset_boolean::set_item_value_str($item,"operation_satisfy_flag");
            E\Eset_boolean::set_item_value_str($item,"school_work_change_flag");
            E\Etea_content_satisfy_flag::set_item_value_str($item,"tea_content_satisfy_flag");
            E\Eschool_work_change_type::set_item_value_str($item,"school_work_change_type");
            E\Eschool_score_change_flag::set_item_value_str($item,"school_score_change_flag");
            E\Eoperation_satisfy_type::set_item_value_str($item,"operation_satisfy_type");
            E\Etea_content_satisfy_type::set_item_value_str($item,"tea_content_satisfy_type");
            E\Echild_class_performance_flag::set_item_value_str($item,"child_class_performance_flag");
            E\Echild_class_performance_type::set_item_value_str($item,"child_class_performance_type");
            E\Eis_warning_flag::set_item_value_str($item,"is_warning_flag");
        }

        return $this->pageView(__METHOD__,$ret_info,[
            "adminid_right" => $adminid_right,
            "warning"       => $warning_type_num
        ] );
    }

    public function ass_revisit_warning_info_sub(){
        $this->set_in_value("ass_adminid",$this->get_account_id());
        return $this->ass_revisit_warning_info();
    }


    public function user_regular_course_check_info(){
        $adminid = $this->get_account_id();
        if(in_array($adminid,["74","349","60"])){
            $adminid=-1;
        }
        // list($start_time,$end_time)=$this->get_in_date_range(0,0,0,[],2);
        $assistantid = $this->get_in_int_val('assistantid',-1);
        $userid      = $this->get_in_int_val('userid',-1);
        $teacherid      = $this->get_in_int_val('teacherid',-1);
        $adminid      = $this->get_in_int_val('adminid',$adminid);

        $ret = $this->t_week_regular_course->get_all_week_regular_course_info_all($assistantid,$userid,$teacherid,$adminid);
        $ret2 = $ret3 = $ret;
        $date_week = \App\Helper\Utils::get_week_range(time(),1);
        $lstart    = $date_week["sdate"];
        $lend      = $date_week["edate"];
        $list1 = $this->t_week_regular_course->get_all_week_regular_course_info($lstart,$assistantid,$userid,$teacherid,$adminid);
        foreach($ret as $k=>&$item){
            foreach($list1 as $val){
                if($val["userid"]==$item["userid"] && $val["teacherid"]==$item["teacherid"] && $val["start_time"]==$item["start_time"]){
                    unset($ret[$k]);
                }
            }
            $item["lesson_start"] = $item["start_time_s"]+$lstart;
            $item["lesson_start_str"] = date("Y-m-d H:i:s",$item["lesson_start"]);
        }
        $list2 = $this->t_week_regular_course->get_all_week_regular_course_info($lstart+7*86400,$assistantid,$userid,$teacherid,$adminid);
        foreach($ret2 as $k=>&$tt){
            foreach($list2 as $val){
                if($val["userid"]==$tt["userid"] && $val["teacherid"]==$tt["teacherid"] && $val["start_time"]==$tt["start_time"]){
                    unset($ret2[$k]);
                }
            }
            $tt["lesson_start"] = $tt["start_time_s"]+$lstart+7*86400;
            $tt["lesson_start_str"] = date("Y-m-d H:i:s",$tt["lesson_start"]);
        }
        $list3 = $this->t_week_regular_course->get_all_week_regular_course_info($lstart+14*86400,$assistantid,$userid,$teacherid,$adminid);
        foreach($ret3 as $k=>&$ttt){
            foreach($list3 as $val){
                if($val["userid"]==$ttt["userid"] && $val["teacherid"]==$ttt["teacherid"] && $val["start_time"]==$ttt["start_time"]){
                    unset($ret3[$k]);
                }
            }
            $ttt["lesson_start"] = $ttt["start_time_s"]+$lstart+14*86400;
            $ttt["lesson_start_str"] = date("Y-m-d H:i:s",$ttt["lesson_start"]);
        }

        $list = array_merge($ret,$ret2,$ret3);

        \App\Helper\Utils::order_list( $list,"userid", 0);
        $list = \App\Helper\Utils::list_to_page_info($list);
        return $this->Pageview(__METHOD__,$list);
    }


    public function parent_report(){
        $page_num = $this->get_in_page_num();
        $ret_info = $this->t_user_report->get_report_info($page_num);
        foreach($ret_info['list'] as &$item){
            \App\Helper\Utils::unixtime2date_for_item($item,'log_time');
            E\Euser_report_from_type::set_item_value_str($item,'from_type');
            $report_uid = $item["report_uid"];
            $opt_nick="";
            if ($item['from_type']==1 ){
                $opt_nick= $this->cache_get_parent_nick($report_uid);
            }
            $item["opt_nick"]=$opt_nick;
        }
        return $this->Pageview(__METHOD__,$ret_info);
    }

    public function get_two_weeks_old_stu(){

        $grade          = $this->get_in_grade();
        $all_flag       = $this->get_in_int_val('all_flag',0);
        $test_user      = $this->get_in_int_val('test_user',-1);
        $originid       = $this->get_in_int_val('originid',-1);
        $user_name      = trim($this->get_in_str_val('user_name',''));
        $phone          = trim($this->get_in_str_val('phone',''));
        $assistantid    = $this->get_in_int_val("assistantid",-1);
        $seller_adminid = $this->get_in_int_val("seller_adminid",-1);
        $order_type     = $this->get_in_int_val("order_type",-1);
        $page_num       = $this->get_in_page_num();
        $status         = -1;
        $userid         = $this->get_in_userid(-1);
        $ass_groupid_ex    = $this->get_in_str_val('seller_groupid_ex', "");
        $ass_adminid_list = $this->t_admin_main_group_name->get_adminid_list_new($ass_groupid_ex);
        $adminid_right              = $this->get_seller_adminid_and_right();


        $teacherid = -1;
        if (is_numeric($user_name) && $user_name< 10000000 ) {
            $userid    = $user_name;
            $user_name = "";
        }
        // if ($assistantid >0 && $order_type == -1) {
        $order_type = 3;
        // }

        list($start_time,$end_time)= $this->get_in_date_range(-100 ,0, 0 );

        $ret_info = $this->t_student_info->get_student_list_search_two_weeks( $start_time,$end_time,
                                                                              $page_num,$all_flag,
                                                                    $userid, $grade, $status,
                                                                    $user_name, $phone, $teacherid,
                                                                    $assistantid, $test_user, $originid,
                                                                              $seller_adminid,$order_type,$ass_adminid_list);

        foreach($ret_info['list'] as &$item) {
            $item['originid']          = E\Estu_origin::get_desc($item['originid']);
            $item['is_test_user']      = E\Etest_user::get_desc($item['is_test_user']);
            $item['user_agent_simple'] = get_machine_info_from_user_agent($item["user_agent"] );
            $item['last_login_ip']     = long2ip( $item['last_login_ip'] );
            \App\Helper\Utils::unixtime2date_for_item($item,"last_lesson_time");
            \App\Helper\Utils::unixtime2date_for_item($item,"last_login_time");
            $item['lesson_count_all']  = $item['lesson_count_all']/100;
            $item['lesson_count_left'] = $item['lesson_count_left']/100;
            $item["seller_admin_nick"] = $this->cache_get_account_nick($item["seller_adminid"] );
            $item["assistant_nick"]    = $this->cache_get_assistant_nick ($item["assistantid"] );
            $item["ss_assign_time"]    = $item["ass_assign_time"]==0?'未分配':date('Y-m-d H:i:s',$item["ass_assign_time"]);
            $item["cache_nick"]        = $this->cache_get_student_nick($item["userid"]) ;
            \App\Helper\Utils::unixtime2date_for_item($item,"reg_time");
        }

        return $this->Pageview(__METHOD__,$ret_info,[
            "adminid_right" =>$adminid_right
        ]);

    }

    public function get_two_weeks_old_stu_seller(){

        //$this->switch_tongji_database();
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
        $ass_groupid_ex    = $this->get_in_str_val('seller_groupid_ex', "");
        $ass_adminid_list = $this->t_admin_main_group_name->get_adminid_list_new($ass_groupid_ex);
        $adminid_right              = $this->get_seller_adminid_and_right();


        $teacherid = -1;
        if (is_numeric($user_name) && $user_name< 10000000 ) {
            $userid    = $user_name;
            $user_name = "";
        }
        // if ($assistantid >0 && $order_type == -1) {
        $order_type = 3;
        // }

        list($start_time,$end_time)= $this->get_in_date_range(-100 ,0, 0 );
        $stu_list =[];

        $list = $this->t_student_info->get_student_search_two_weeks_list( $start_time,$end_time,$all_flag,
                                                                              $userid, $grade, $status,
                                                                              $user_name, $phone, $teacherid,
                                                                              $assistantid, $test_user, $originid,
                                                                          $seller_adminid,$ass_adminid_list,$student_type);
        foreach($list as $val){
            if(!isset($stu_list[$val["userid"]])){
                $stu_list[$val["userid"]] = $val["userid"];
            }
        }
        $warning_list = $this->t_month_ass_warning_student_info->get_done_stu_info_seller( $start_time,$end_time,$all_flag,
                                                                              $userid, $grade, $status,
                                                                              $user_name, $phone, $teacherid,
                                                                              $assistantid, $test_user, $originid,
                                                                              $seller_adminid,$ass_adminid_list,$student_type);
        foreach($warning_list as $val){
            if(!isset($stu_list[$val["userid"]])){
                $stu_list[$val["userid"]] = $val["userid"];
            }
        }

        $ret_info = $this->t_student_info->get_end_stu_for_seller($page_num,$stu_list,$order_type);


        foreach($ret_info['list'] as &$item) {
            $item['originid']          = E\Estu_origin::get_desc($item['originid']);
            $item['is_test_user']      = E\Etest_user::get_desc($item['is_test_user']);
            $item['user_agent_simple'] = get_machine_info_from_user_agent($item["user_agent"] );
            $item['last_login_ip']     = long2ip( $item['last_login_ip'] );
            \App\Helper\Utils::unixtime2date_for_item($item,"last_lesson_time");
            \App\Helper\Utils::unixtime2date_for_item($item,"last_login_time");
            $item['lesson_count_all']  = $item['lesson_count_all']/100;
            $item['lesson_count_left'] = $item['lesson_count_left']/100;
            $item["seller_admin_nick"] = $this->cache_get_account_nick($item["seller_adminid"] );
            $item["assistant_nick"]    = $this->cache_get_assistant_nick ($item["assistantid"] );
            $item["ss_assign_time"]    = $item["ass_assign_time"]==0?'未分配':date('Y-m-d H:i:s',$item["ass_assign_time"]);
            $item["cache_nick"]        = $this->cache_get_student_nick($item["userid"]) ;
            \App\Helper\Utils::unixtime2date_for_item($item,"reg_time");
        }

        return $this->Pageview(__METHOD__,$ret_info,[
            "adminid_right" =>$adminid_right
        ]);

    }


    public function get_fulltime_teacher_attendance_info(){
        $page_num       = $this->get_in_page_num();
        list($start_time,$end_time)= $this->get_in_date_range(0,0,0,[],1 );
        $attendance_type  = $this->get_in_int_val("attendance_type",-1);
        $teacherid = $this->get_in_int_val("teacherid",-1);
        $adminid = $this->get_in_int_val("adminid",-1);
        $account_role = $this->get_in_int_val("account_role",-1);
        $fulltime_teacher_type = $this->get_in_int_val("fulltime_teacher_type", -1);
        $ret_info = $this->t_fulltime_teacher_attendance_list->get_fulltime_teacher_attendance_list($start_time,$end_time,$attendance_type,$teacherid,$page_num,$adminid,$account_role,$fulltime_teacher_type);
        $month_start = strtotime(date("Y-m-01",$start_time));
        $month_end = strtotime("+1 months",$start_time);
        $extra_list = $this->t_fulltime_teacher_attendance_list->get_fulltime_teacher_attendance_list_new($month_start,$month_end,4,-1,-1);
        //获取当月加班列表
        $extra_arr=[];
        foreach($extra_list as $v){
            $attendance_time_str= date("Y-m-d",$v["attendance_time"]);
            @$extra_arr[$v["adminid"]] .=$attendance_time_str."<br>" ;
        }

        foreach($ret_info["list"] as &$item){

            //本月加班时间
            $item["extra_time_info"] = @$extra_arr[$item["adminid"]];
            \App\Helper\Utils::unixtime2date_for_item($item,"add_time","_str");
            \App\Helper\Utils::unixtime2date_for_item($item,"card_start_time","_str");
            \App\Helper\Utils::unixtime2date_for_item($item,"card_end_time","_str");
            $item["attendance_time_str"] = date("Y-m-d",$item["attendance_time"]);
            $w = date("w",$item["attendance_time"]);
            if($w>0 && $w <3){
                $item["kaoqin_type"]=1;
                $item["kaoqin_type_str"] = "正常休息";
                if($item["attendance_type"]==4){
                    E\Eattendance_type::set_item_value_str($item);
                }else{
                    $item["attendance_type_str"]="";
                }
                if($item["attendance_type"]==4){
                    $item["result"] = "加班";
                }else{
                    $item["result"]="正常";
                }

            }else{
                $item["kaoqin_type"]=2;
                $item["kaoqin_type_str"] = "公司坐班";
                $item["off_time_str"] = date("H:i",$item["off_time"]);
                $item["delay_work_time_str"] = date("H:i",$item["delay_work_time"]);
                E\Eattendance_type::set_item_value_str($item);
                if($item["holiday_hugh_time"]){
                    $holiday_hugh_time_arr = json_decode($item["holiday_hugh_time"],true);
                    $item["holiday_hugh_time_str"] = date("Y.m.d",@$holiday_hugh_time_arr["start"])."-".date("Y.m.d",@$holiday_hugh_time_arr["end"]);
                    $item["holiday_start_time"] = @$holiday_hugh_time_arr["start"];
                    $item["holiday_lesson_count"] = @$holiday_hugh_time_arr["lesson_count"];
                }else{
                    $item["holiday_hugh_time_str"]="";
                    $item["holiday_start_time"] =0;
                    $item["holiday_lesson_count"]=0;
                }

                if($item["attendance_type"]==5){
                    $item["result"] = "请假";
                }elseif($item["attendance_type"]==3){
                    $item["result"] = "休假";
                }elseif($item["attendance_type"]==1){
                    $item["result"]="正常";
                }else{
                    if($item["card_start_time"]==0 && $item["card_end_time"]==0){
                        $item["result"]="旷工";
                    }else{
                        $off_time = $item["off_time"]==0?($item["attendance_time"]+18.5*3600):$item["off_time"];
                        $delay_time = $item["delay_work_time"]==0?($item["attendance_time"]+9.5*3600):$item["delay_work_time"];
                        if($item["card_start_time"]<=$delay_time && $item["card_end_time"]>=$off_time){
                            $item["result"]="正常";
                        }elseif($item["card_start_time"]>$delay_time && $item["card_end_time"]<$off_time){
                            $item["result"]="迟到且早退";
                        }elseif($item["card_start_time"]>$delay_time){
                            $item["result"]="迟到";
                        }elseif($item["card_end_time"]<$off_time){
                            $item["result"]="早退";
                        }




                    }
                }


            }


        }
        return $this->Pageview(__METHOD__,$ret_info,[
            "acc"   =>session("acc")
        ]);
    }

    public function get_fulltime_teacher_attendance_info_full(){
        $this->set_in_value("account_role",5);
        return $this->get_fulltime_teacher_attendance_info();

    }


    public function update_order_time(){
        $orderid         = $this->get_in_int_val("orderid");
        $order_time_date = $this->get_in_str_val("order_time_date",date("Y-m-d H:i",time()));
        $acc             = $this->get_account();

        $order_time = strtotime($order_time_date);
        $ret = $this->t_order_info->field_update_list($orderid,[
            "order_time" => $order_time
        ]);

        if(!$ret){
            return $this->output_err("更新失败！请重试！");
        }
        \App\Helper\Utils::logger("user".$acc."修改了订单".$orderid."的下单时间！");
        return $this->output_succ();
    }

    public function production_department_memeber_list(){
        $page_info = $this->get_in_page_info();
        $post = $this->get_in_int_val("post",-1);
        $department = $this->get_in_int_val("department",-1);
        $department_group = $this->get_in_int_val("department_group",-1);
        $user_info         = trim($this->get_in_str_val('user_info',''));
        $adminid           = $this->get_in_adminid(-1);
        $main_department = $this->get_in_int_val("main_department",-1);
        $ret_info = $this->t_manager_info->get_product_department_memeber_list( $page_info,$user_info,$adminid,$post,$department,$department_group,$main_department);
        $start_index = \App\Helper\Utils::get_start_index_from_ret_info($ret_info) ;
        $domain = config('admin')['qiniu']['public']['url'];
        $num = strlen($domain)+1;

        foreach($ret_info["list"] as $id=>&$item){
            $item['id'] = $start_index+$id;
            E\Egender::set_item_value_str($item);
            E\Eeducation::set_item_value_str($item);
            E\Ecompany::set_item_value_str($item);
            E\Eemployee_level::set_item_value_str($item);
            E\Epost::set_item_value_str($item);
            E\Edepartment_group::set_item_value_str($item);
            E\Edepartment::set_item_value_str($item);
            \App\Helper\Utils::unixtime2date_for_item($item,"create_time","_str","Y-m-d");
            \App\Helper\Utils::unixtime2date_for_item($item,"become_full_member_time","_str","Y-m-d");
            \App\Helper\Utils::unixtime2date_for_item($item,"order_end_time","_str","Y-m-d");
            if($item["resume_url"]){
                $num_surl = strlen($item["resume_url"]);
                $item["rurl"] = substr($item["resume_url"],$num,$num_surl-1);
            }else{
                $item["rurl"]="";
            }

        }
        return $this->Pageview(__METHOD__,$ret_info);

    }

    public function department_memeber_list_production(){
        $this->set_in_value("main_department",2);
        return $this->production_department_memeber_list();

    }
    public function department_memeber_list_seller(){
        $this->set_in_value("main_department",1);
        return $this->production_department_memeber_list();

    }
    public function department_memeber_list_sc(){
        $this->set_in_value("main_department",3);
        return $this->production_department_memeber_list();

    }
    public function department_memeber_list_market(){
        $this->set_in_value("main_department",4);
        return $this->production_department_memeber_list();

    }
    public function department_memeber_list_development(){
        $this->set_in_value("main_department",5);
        return $this->production_department_memeber_list();

    }
    public function department_memeber_list_finance(){
        $this->set_in_value("main_department",6);
        return $this->production_department_memeber_list();

    }
    public function department_memeber_list_human(){
        $this->set_in_value("main_department",7);
        return $this->production_department_memeber_list();

    }


    public function get_department_and_group_info(){
        $main_department = $this->get_in_int_val("main_department");
        $department_list = $department_group_list=[];
        switch($main_department){
        case 1:
            $department_group_list=[0];
            $department_list=[0,8,9,10,11];
            break;
        case 2:
            $department_group_list=[0,1,2,3,4,5,6,7,8,9];
            $department_list=[0,1,2,3,4,5,6,7];
            break;
        case 3:
            $department_group_list=[0,10,11];
            $department_list=[0,12,13,14];
            break;
        case 4:
            $department_group_list=[0,12,13,14];
            $department_list=[0,15];
            break;
        case 5:
            $department_group_list=[0,15,16,17,18];
            $department_list=[0,16];
            break;
        case 6:
            $department_group_list=[0,19,20];
            $department_list=[0,17];
            break;
        case 7:
            $department_group_list=[0,21,22,23,24];
            $department_list=[0,18];
            break;
        default:
            $department_group_list=[0];
            $department_list=[0];
        }

        $list=[];
        $list["department_list"] = $department_list;
        $list["department_group_list"] = $department_group_list;
        return  $this->output_succ( [ "data" =>$list] );
    }

    /**
     * @param type 需要重置的内容 1 课程包年级 2 学生年级
     * 暑期(7.1)重置学生课程包的年级 升级课程包年级
     * 开学(9.1)重置学生年级
     */
    public function reset_course_order_grade(){
        $type = $this->get_in_int_val("type",1);
        $acc  = $this->get_account();

        $type_str  = $type==1?"课程包年级":"学生年级";
        $title     = "重置学生年级";
        $send_info = $acc."用户在".date("Y-m-d H:i",time())."时候，尝试重置学生的相关年级,类型为".$type_str;

        if($type==2 && !in_array($acc,["adrian","jim"])){
            return $this->output_err("你没有此权限!");
        }

        $job = new \App\Jobs\reset_student_course_grade($type);
        dispatch($job);
    }

    public function notice_stu_price_up(){
        $type = $this->get_in_int_val("type");

        $stu_list = $this->t_student_info->get_stu_list($type);
        $stu_list = [
            "0" => [
                "phone" => 18790256265
            ]
        ];
        echo "<pre>";
        var_dump($stu_list);
        echo "</pre>";
        exit;

        /**
         * 模板ID:
         * SMS_72835024
         * 模板内容:
         * 家长您好，自7月起，部分课程价格将有小幅上涨，特此通知。
         */
        // $job = new \App\Jobs\SendStuMessage($stu_list,"72765026",[]);
        // dispatch($job);
    }

    public function get_relation_order_list() {
        $orderid = $this->get_in_int_val("orderid");
        $contract_type= $this->get_in_int_val("contract_type");
        $old_orderid=$orderid;
        if ($contract_type ==1 ) {
            $orderid=$this->t_order_info->get_parent_order_id($orderid);
            if(!$orderid) {
                return $this->output_err("没有数据");
            }
        }

        $ret_list=$this->t_order_info->get_relation_order_list($orderid);
        foreach ($ret_list["list"] as  &$item) {
            E\Econtract_type::set_item_value_str($item);
            $item["self_flag_str"]= ($item["orderid"]==$old_orderid?"当前":"");
            \App\Helper\Utils::unixtime2date_for_item($item,"order_time");
            $this->cache_set_item_student_nick($item );
            $item["price"]/=100;
            if ($item["contract_type"]==1) {
                E\Efrom_parent_order_type::set_item_value_str($item);
            }
            $item["lesson_count"]=($item["lesson_total"]*$item["default_lesson_count"])/100;
        }
        return $this->pageView(__METHOD__,$ret_list);
    }

    /**
     * @param userid 转赠入的userid
     * @param orderid 转赠出的orderid
     * @param parent_lesson_count 转赠出的课时
     * @param lesson_count 转赠入的课时
     * @param grade 转赠入的年级
     * @param competition_flag 转赠入的竞赛标志
     */
    public function split_order(){
        $userid              = $this->get_in_int_val("userid");
        $orderid             = $this->get_in_int_val("orderid");
        $parent_lesson_count = ($this->get_in_str_val("parent_lesson_count"))*100;
        $lesson_count        = ($this->get_in_str_val("lesson_count"))*100;
        $grade               = $this->get_in_int_val("grade");
        $competition_flag    = $this->get_in_int_val("competition_flag");

        $order_info = $this->t_order_info->get_order_info_by_orderid($orderid);
        if($order_info['contract_status']!=1){
            return $this->output_err("此合同不是执行中的合同，无法拆分！");
        }
        if(!in_array($order_info['contract_type'],[0,3])){
            return $this->output_err("此合同不是常规或续费合同，无法拆分！");
        }

        $this->t_order_info->start_transaction();
        if($order_info['lesson_left']<$lesson_count){
            return $this->output_err("课时不足！无法拆分！");
        }

        $ret = $this->t_order_info->row_insert([
            "userid"                         => $userid,
            "lesson_total"                   => ($lesson_count/100),
            "default_lesson_count"           => 100,
            "grade"                          => $grade,
            "subject"                        => $order_info['subject'],
            "competition_flag"               => $competition_flag,
            "contract_type"                  => 1,
            "contract_status"                => 1,
            "from_parent_order_type"         => 5,
            "from_parent_order_lesson_count" => $parent_lesson_count,
            "parent_order_id"                => $orderid,
        ]);

        if(!$ret){
            $this->t_order_info->rollback();
            return $this->output_err("分割失败！请重试！");
        }

        if($left_order_total==0){
            $contract_status = 2;
        }else{
            $contract_status = 1;
        }
        $ret = $this->t_order_info->field_update_list($orderid,[
            "lesson_left"     => $left_order_total,
            "contract_status" => $contract_status
        ]);
        if(!$ret){
            $this->t_order_info->rollback();
            return $this->output_err("分割失败！请重试！");
        }
        $this->t_order_info->commit();
        return $this->output_succ();
    }

    public function cancel_split_order(){
        $orderid = $this->get_in_int_val("orderid");

        $order_info = $this->t_order_info->get_order_info_by_orderid($orderid);
        if(empty($order_info)){
            return $this->output_err("合同不存在！");
        }
        $parent_order_info = $this->t_order_info->get_order_info_by_orderid($order_info['parent_order_id']);
        if(empty($parent_order_info)){
            return $this->output_err("父合同不存在！");
        }

        $this->t_order_info->start_transaction();
        $lesson_left = $parent_order_info['lesson_left']+$order_info['from_parent_order_lesson_count'];
        $ret = $this->t_order_info->field_update_list($order_info['parent_order_id'],[
            "contract_status" => 1,
            "lesson_left"     => $lesson_left,
        ]);
        if(!$ret){
            $this->t_order_info->rollback();
            return $this->output_err("父合同还原失败！请重试！");
        }
        $ret = $this->t_order_info->row_delete($orderid);
        if(!$ret){
            $this->t_order_info->rollback();
            return $this->output_err("赠送合同取消失败！请重试！");
        }

        $this->t_order_info->commit();
        return $this->output_succ();
    }

    public function update_from_data(){
        $orderid  = $this->get_in_int_val("orderid");
        $from_key = $this->get_in_str_val("from_key");
        $from_url = $this->get_in_str_val("from_url");

        $ret = $this->t_order_info->field_update_list($orderid,[
            "from_key" => $from_key,
            "from_url" => $from_url
        ]);
        if($ret){
            return $this->output_succ();
        }else{
            return $this->output_err("修改失败!");
        }
    }

    public function reset_lecture_grade(){
        $acc = $this->get_account();
        if($acc=="adrian"){
            $this->t_teacher_lecture_info->reset_lecture_grade();
        }
    }

    public function get_last_change_type_info(){
        $userid = $this->get_in_int_val("userid",0);
        $data = $this->t_student_type_change_list->get_info_by_userid_last($userid);
        if($data){
            if($data["recover_time"]>0){
                $data["recover_time"] = date("Y-m-d", $data["recover_time"]);
            }else{
                $data["recover_time"]="";
            }
            if($data["wx_remind_time"]>0){
                $data["wx_remind_time"] = date("Y-m-d", $data["wx_remind_time"]);
            }else{
                $data["wx_remind_time"]="";
            }

        }

        return $this->output_succ(["data"=>$data]);
    }

    //心里辅导课排课
    public function get_ass_psychological_lesson(){
        list($start_time,$end_time) = $this->get_in_date_range(0,0,0,null,2);
        $ret_info = \App\Helper\Utils::list_to_page_info([]);
        return $this->pageView(__METHOD__,$ret_info);

    }

    //获取心理课排课信息
    public function get_ass_psychological_lesson_detail(){
        $start_time = strtotime($this->get_in_str_val("start_time"));
        $end_time = strtotime($this->get_in_str_val("end_time")." 23:59:59");
        list($start_time,$end_time) = $this->get_in_date_range(0,0,0,null,2);
        $date      = \App\Helper\Utils::get_week_range(time(NULL),1);
        $week_start = $date["sdate"];

        // $start_time = $week_start+7*86400;
        // $end_time = $week_start+14*86400;
        $list= $this->t_psychological_teacher_time_list->get_info_by_time($start_time,$end_time);
        //$ret = $this->t_psychological_teacher_time_list->get_all_info();
        // dd(date("Y-m-d",1500652800));
        foreach($list as &$val){
            $teacher_phone_list =  explode(",",$val['teacher_phone_list']);
            $lesson_start = strtotime(date("Y-m-d",$val["day"])." ".$val["start"]);
            $tea_arr=[];
            $val["realname"]="";
            foreach($teacher_phone_list as $item){
                $phone = trim($item);
                $teacherid = $this->t_teacher_info->get_teacherid_by_phone($phone);
                $realname = $this->t_teacher_info->get_realname($teacherid);
                $check_lesson = $this->t_lesson_info_b2->check_psychological_lesson($teacherid,$lesson_start);
                if($check_lesson ==1){
                    // @$val["realname"] .= $realname."(排),";
                }else{
                    @$val["realname"] .= $realname.",";
                    $tea_arr[] = $teacherid;
                }
            }
            $val["realname"] = trim($val["realname"],",");
            $val["tea_list"] = json_encode($tea_arr);
            $val["lesson_start"] = strtotime(date("Y-m-d",$val["day"])." ".$val["start"]);
            $val["lesson_end"]   = strtotime(date("Y-m-d",$val["day"])." ".$val["end"]);

            $w = date("w",$val["lesson_start"]);
            if($w==0){
                $w=7;
            }
            $week_day = $week_start+($w-1)*86400;
            $val["start_time_ex"] = strtotime(date("Y-m-d",$week_day)." ".$val["start"])*1000;
            $val["end_time_ex"]   = strtotime(date("Y-m-d",$week_day)." ".$val["end"])*1000;

            $val["start_time"] = $w."-".$val["start"];

        }
        return  outputjson_success( [ "common_lesson_config" => $list] );

        //dd($list);




    }


    //心里辅导课排课
    public function set_psychological_lesson(){
        $lesson_start  = $this->get_in_int_val("lesson_start");
        $lesson_end = $this->get_in_int_val("lesson_end");
        $userid = $this->get_in_int_val("userid");
        $lesson_name = $this->get_in_int_val("lesson_name");
        $tea_list = $this->get_in_str_val("tea_list");
        $tea_list = json_decode($tea_list,true);
        $orderid      = 1;
        if(empty($userid) || empty($tea_list)){
             return $this->output_err("未选择学生或老师已经用完");
        }
        $teacherid = $tea_list[0];
        $ret_row1 = $this->t_lesson_info->check_student_time_free($userid,0,$lesson_start,$lesson_end);
        //检查时间是否冲突
        if ($ret_row1) {
            $error_lessonid=$ret_row1["lessonid"];
            return $this->output_err(
                "<div>有现存的学生课程与该课程时间冲突！<a href='/tea_manage/lesson_list?lessonid=$error_lessonid/' target='_blank'>查看[lessonid=$error_lessonid]<a/><div> "
            );
        }

        $ret_row2 = $this->t_lesson_info->check_teacher_time_free($teacherid,0,$lesson_start,$lesson_end);
        if($ret_row2){
            $error_lessonid = $ret_row2["lessonid"];
            return $this->output_err(
                "<div>有现存的老师课程与该课程时间冲突！<a href='/tea_manage/lesson_list?lessonid=$error_lessonid/' target='_blank'>查看[lessonid=$error_lessonid]<a/><div> "
            );
        }

        $lesson_name          = E\Epsychological_lesson_name_list::get_desc($lesson_name);
        $teacher_info = $this->t_teacher_info->field_get_list($teacherid,"teacher_money_type,level");
        $grade = $this->t_student_info->get_grade($userid);
        $subject=11;
        $courseid     = $this->t_course_order->add_course_info_new($orderid,$userid,$grade,$subject,150,2,0,1,1,0,$teacherid);
        $lessonid     = $this->t_lesson_info->add_lesson(
            $courseid,0,$userid,0,2,
            $teacherid,0,$lesson_start,$lesson_end,$grade,
            $subject,150,$teacher_info["teacher_money_type"],$teacher_info["level"]
        );
        $this->t_lesson_info->field_update_list($lessonid,[
           "lesson_name"   =>$lesson_name
        ]);
        $this->t_homework_info->add(
            $courseid,0,$userid,$lessonid,$grade,$subject,$teacherid
        );

        $this->t_lesson_info->reset_lesson_list($courseid);

        return $this->output_succ();
    }

   public function merge_order(){
        $orderid      = $this->get_in_int_val("orderid");
        $orderid_goal = $this->get_in_int_val("orderid_goal");
        $acc          = $this->get_account();
        $account_role = $this->get_account_role();

        $order_info = $this->t_order_info->get_order_info_by_orderid($orderid);
        $order_goal_info = $this->t_order_info->get_order_info_by_orderid($orderid_goal);

        if(!in_array($acc,['adrian',"jim"]) && !in_array($account_role,[13])){
            return $this->output_err("没有权限合并合同！");
        }
        if($order_info['userid'] != $order_goal_info['userid']){
            return $this->output_err("两个合同不是同一个学生！");
        }
        if($order_info['contract_status']>1 || !in_array($order_info['contract_type'],[0,3])){
            return $this->output_err("此合同类型或状态出错！");
        }
        if($order_goal_info['contract_status']>1 || !in_array($order_goal_info['contract_type'],[0,3])){
            return $this->output_err("目标合同类型或状态出错！");
        }


        $order_goal_info['price'] += $order_info['price'];
        $order_goal_info['discount_price'] += $order_info['discount_price'];
        $order_goal_info['promotion_discount_price'] += $order_info['promotion_discount_price'];
        $order_goal_info['promotion_spec_discount'] += $order_info['promotion_spec_discount'];
        $order_goal_info['lesson_left']+=$order_info['lesson_left'];
        $lesson_total      = $order_info['lesson_total']*$order_info['default_lesson_count'];
        $lesson_goal_total = $order_goal_info['lesson_total']*$order_goal_info['default_lesson_count'];
        $lesson_goal_total += $lesson_total;

        $this->t_order_info->start_transaction();
        $ret = $this->t_order_info->field_update_list($orderid_goal,[
            'price'                    => $order_goal_info['price'],
            'discount_price'           => $order_goal_info['discount_price'],
            'promotion_spec_discount'  => $order_goal_info['promotion_spec_discount'],
            'promotion_discount_price' => $order_goal_info['promotion_discount_price'],
            'lesson_left'              => $order_goal_info['lesson_left'],
            'lesson_total'             => $lesson_goal_total,
            'default_lesson_count'     => 1,
        ]);

        //子合同处理
        $child_order_info = $this->t_child_order_info->get_all_child_order_info($orderid);
        $child_order_info_goal = $this->t_child_order_info->get_all_child_order_info($orderid_goal,0);
        $goal_info= $child_order_info_goal[0];
        foreach($child_order_info as $val){
            if($val["child_order_type"]==0){
                if($val["pay_status"]==$goal_info["pay_status"]){
                    $new_price = $val["price"]+$goal_info["price"];
                    $this->t_child_order_info->field_update_list($goal_info["child_orderid"],[
                        "price"  =>$new_price
                    ]);
                    $this->t_child_order_info->row_delete($val["child_orderid"]);

                }elseif($val["pay_status"]==0 && $goal_info["pay_status"]==1){
                    $this->t_child_order_info->field_update_list($goal_info["child_orderid"],[
                        "child_order_type"  =>3
                    ]);
                    $this->t_child_order_info->field_update_list($val["child_orderid"],[
                        "parent_orderid"  =>$orderid_goal
                    ]);
                }elseif($val["pay_status"]==1 && $goal_info["pay_status"]==0){
                    $this->t_child_order_info->field_update_list($val["child_orderid"],[
                        "parent_orderid"  =>$orderid_goal,
                        "child_order_type"=>3
                    ]);

                }
            }else{
                $this->t_child_order_info->field_update_list($val["child_orderid"],[
                   "parent_orderid"  =>$orderid_goal
                ]);
            }
        }


        //设置目标合同是否分期
        $this->set_order_partition_flag($orderid_goal);

        if(!$ret){
            $this->t_order_info->rollback();
            return $this->output_err("合并失败！");
        }
        $ret = $this->t_order_info->row_delete($orderid);
        if(!$ret){
            $this->t_order_info->rollback();
            return $this->output_err("原合同删除失败！");
        }

        $this->t_order_info->commit();
        return $this->output_succ();
    }

    public function update_order_price(){
        $orderid = $this->get_in_int_val("orderid");
        $price   = $this->get_in_str_val("price");
        $discount_price   = $this->get_in_str_val("discount_price");
        $account = $this->get_account();

        if(!in_array($account,["zero","echo","jack","adrian","jim"])){
            return $this->output_err("你没有权限");
        }
        $old_price = $this->t_order_info->get_price($orderid);

        $child_order_info = $this->t_child_order_info->get_all_child_order_info($orderid,0);
        $child_order_info= $child_order_info[0];
        $new_price = $price*100-$old_price+$child_order_info["price"];
        if($child_order_info["pay_status"]){
             return $this->output_err("子合同已付款,请联系开发人员处理");
        }
        if($new_price <0){
            return $this->output_err("请先重新拆分合同!");
        }
        //更新子合同金额
        $this->t_child_order_info->field_update_list($child_order_info["child_orderid"],[
           "price"  => $new_price
        ]);

        //设置主合同是否分期
        $this->set_order_partition_flag($orderid);

        $ret = $this->t_order_info->field_update_list($orderid,[
            "price"          => $price*100,
            "discount_price" => $discount_price*100,
        ]);

        if(!$ret){
            return $this->output_err("更新失败！请重试!");
        }
        return $this->output_succ();
    }

    public function reset_teacher_trans_subject(){
        $id    = $this->get_in_int_val("id");
        $phone = $this->get_in_str_val("phone");

        $teacherid = $this->t_teacher_info->get_teacherid_by_phone($phone);
        $lecture_info = $this->t_teacher_lecture_appointment_info->get_simple_info($phone);
        $lessonid = $this->t_lesson_info_b2->get_train_lesson($teacherid,$lecture_info['trans_subject_ex']);
        if($lessonid>0){
            return $this->output_err("此老师已经预约了面试试讲！无法重置！");
        }
        $lecture_flag = $this->t_teacher_lecture_info->check_have_video($phone);
        if($lecture_flag>0){
            return $this->output_err("此老师已提交了录制试讲！无法重置！");
        }

        $this->t_teacher_lecture_appointment_info->field_update_list($id,[
            "trans_subject_ex" => "",
            "trans_grade_ex"   => "",
        ]);

        return $this->output_succ();
    }

    public function update_teacher_money_type(){
        $teacher_money_type = $this->get_in_int_val("teacher_money_type");
        $level     = $this->get_in_int_val("level");
        $money_101 = $this->get_in_int_val("money_101");
        $money_106 = $this->get_in_int_val("money_106");
        $money_203 = $this->get_in_int_val("money_203");
        $money_301 = $this->get_in_int_val("money_301");
        $money_303 = $this->get_in_int_val("money_303");

        if($teacher_money_type!=7){
            return $this->output_err("此类型工资不能个修改!");
        }

        if($teacher_money_type==-1 || $level==-1){
            return $this->output_err("工资类型和等级都不能为空!");
        }

        $check_flag = $this->t_teacher_money_type->check_is_exists($teacher_money_type,$level,-1);
        if($check_flag){
            $ret = $this->t_teacher_money_type->update_teacher_money_type(
                $teacher_money_type,$level,$money_101,$money_106,$money_203,$money_301,$money_303
            );
            if(!$ret){
                return $this->output_err("更新失败！");
            }
        }else{
            $grade_money_arr = [
                $money_101 => [101,102,103,104,105],
                $money_106 => [106,201,202],
                $money_203 => [203],
                $money_301 => [301,302],
                $money_303 => [303],
            ];
            $ret = $this->add_teacher_money_type($teacher_money_type,$level,$grade_money_arr,7);
        }
        return $this->output_succ();
    }

    /**
     * 添加老师工资类型
     */
    public function add_teacher_money_type($teacher_money_type,$level,$grade_money_arr,$type){
        $this->t_teacher_money_type->start_transaction();
        foreach($grade_money_arr as $money_key => $grade_val){
            foreach($grade_val as $g_val){
                $ret = $this->t_teacher_money_type->row_insert([
                    "teacher_money_type" => $teacher_money_type,
                    "level"              => $level,
                    "grade"              => $g_val,
                    "money"              => $money_key,
                    "type"               => $type,
                ]);
                if(!$ret){
                    $this->t_teacher_money_type->rollback();
                    return $ret;
                }
            }
        }
        $this->t_teacher_money_type->commit();
        return $ret;
    }

    public function teacher_reward_rule_list(){
        $reward_count_type = $this->get_in_int_val("reward_count_type",1);
        $rule_type   = $this->get_in_int_val("rule_type",6);

        $list = $this->t_teacher_reward_rule_list->get_teacher_reward_rule_list($reward_count_type,$rule_type);
        foreach($list as &$val){
            E\Ereward_count_type::set_item_value_str($val);
            if($val['reward_count_type']==1){
                E\Erule_type_1::set_item_value_str($val,"rule_type");
            }elseif($val['reward_count_type']==2){
                E\Erule_type_2::set_item_value_str($val,"rule_type");
            }
            $val['money']/=100;
            $val['num']/=100;
        }

        $list = \App\Helper\Utils::list_to_page_info($list);
        return $this->pageView(__METHOD__,$list);
    }

    public function add_reward_rule_type(){
        $reward_count_type = $this->get_in_int_val("reward_count_type");
        $rule_type         = $this->get_in_int_val("rule_type");
        $num               = $this->get_in_int_val("num")*100;
        $old_num           = $this->get_in_int_val("old_num")*100;
        $money             = $this->get_in_int_val("money")*100;
        $type              = $this->get_in_str_val("type","add");

        if($type=="add"){
            $ret = $this->t_teacher_reward_rule_list->row_insert([
                "reward_count_type" => $reward_count_type,
                "rule_type"         => $rule_type,
                "num"               => $num,
                "money"             => $money,
            ]);
        }elseif($type=="update"){
            $ret = $this->t_teacher_reward_rule_list->update_reward_rule($reward_count_type,$rule_type,$num,$old_num,$money);
        }elseif($type=="delete"){
            $ret = $this->t_teacher_reward_rule_list->delete_reward_rule($reward_count_type,$rule_type,$num);
        }
        if(!$ret){
            return $this->output_err("更新失败！");
        }

        $rule_list = $this->t_teacher_reward_rule_list->get_reward_rule_list();
        $teacher_rule = [];
        foreach($rule_list as $r_val){
            $teacher_rule[$r_val['reward_count_type']][$r_val['rule_type']][$r_val['num']]=$r_val['money'];
        }
        $key = \App\Helper\Config::get_config("rule_type_key","redis_keys");
        \App\Helper\Utils::redis(E\Eredis_type::V_SET,$key,$teacher_rule);

        return $this->output_succ();
    }

    public function contract_list_seller_payed_new(){
        $adminid = $this->get_account_id();
        $son_adminid = $this->t_admin_main_group_name->get_son_adminid($adminid);
        $son_adminid_arr = [];
        foreach($son_adminid as $item){
            $son_adminid_arr[] = $item['adminid'];
        }
        array_unshift($son_adminid_arr,$adminid);
        $son_adminid_arr = array_unique($son_adminid_arr);
        // $this->set_in_value("contract_status", -2);

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
        $ret_list = $this->t_order_info->get_order_list_require_adminid_new(
            $page_num,$start_time,$end_time,$contract_type,
            $contract_status,$studentid,$config_courseid,
            $is_test_user, $show_yueyue_flag, $has_money,
            -1, $assistantid,"",$stu_from_type,$son_adminid_arr,
            $account_role,$grade,$subject,$tmk_adminid,-1,
            $teacherid, -1 , 0, $require_adminid_list,$origin_userid,
            $referral_adminid,$opt_date_type
            , " t2.assistantid asc , order_time desc"
            , $spec_flag
        );
        $all_lesson_count = 0;
        $all_promotion_spec_diff_money=0;
        foreach($ret_list['list'] as &$item ){
            if($item["order_time"] >= strtotime("2017-10-27 16:00:00") && $item["can_period_flag"]==0){
                $item["can_period_flag"]=0;
            }else{
                $item["can_period_flag"]=1;
            }

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
            \App\Helper\Utils::unixtime2date_for_item($item, 'lesson_start');
            \App\Helper\Utils::unixtime2date_for_item($item, 'lesson_end');
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
            $item["promotion_spec_diff_money"] /= 100;
            $item["pre_money_info"] = $pre_money_info;
            $item["promotion_spec_is_not_spec_flag_str"] = "";
            if ($item["promotion_spec_is_not_spec_flag"]){
                $item["promotion_spec_is_not_spec_flag_str"]= "<font color=red>已转为非特殊申请</font>";
            }else{
                if ( $item["flowid"] ) {
                    $all_promotion_spec_diff_money+= $item["promotion_spec_diff_money"];
                }
            }
        }

        $acc = $this->get_account();
        return $this->Pageview(__METHOD__,$ret_list,[
            "account_role"                  => $this->get_account_role(),
            "all_lesson_count"              => $all_lesson_count,
            "all_promotion_spec_diff_money" => $all_promotion_spec_diff_money,
            "acc"                           => $acc
        ]);
    }

    /**
     * 将平台合作代理下的推荐老师全部转换出来
     * @param teacherid 助理/总代理老师id
     */
    public function transfer_agent_list(){
        $teacherid = $this->get_in_int_val("teacherid");

        $agent_teacher_info = $this->t_teacher_info->get_teacher_info($teacherid);
        $tea_list = $this->t_teacher_lecture_appointment_info->get_tea_list_by_reference($agent_teacher_info['phone']);

    }

    public function product_info(){
        $page_num  = $this->get_in_page_num();
        $deal_flag = $this->get_in_int_val('deal_flag',-1);
        $lesson_problem = $this->get_in_int_val('lesson_problem',-1);
        $feedback_nick = $this->get_in_str_val('feedback_nick',"");
        list($start_time,$end_time,$opt_date_type) = $this->get_in_date_range(date("Y-m-01"),0,1,[
            1 => array("pf.create_time","录入时间"),
        ],3);

        $ret_list  = $this->t_product_feedback_list->get_product_list($lesson_problem, $deal_flag, $feedback_nick, $start_time, $end_time, $page_num, $opt_date_type);

        foreach($ret_list['list'] as &$item){
            $item['stu_agent_simple'] = get_machine_info_from_user_agent($item["stu_agent"] );
            $item['tea_agent_simple'] = get_machine_info_from_user_agent($item["tea_agent"] );
            \App\Helper\Utils::unixtime2date_for_item($item,"create_time");
            $item['record_nick']   = $this->cache_get_account_nick($item['record_adminid']);
            $item["tea_phone"] = preg_replace('/(1[358]{1}[0-9])[0-9]{4}([0-9]{4})/i','$1****$2',$item["tea_phone"]);
            $item["stu_phone"] = preg_replace('/(1[358]{1}[0-9])[0-9]{4}([0-9]{4})/i','$1****$2',$item["stu_phone"]);
            if($item['deal_flag'] == -1){
                $item['deal_flag_str'] = '<font color="blue">未设置</font>';
            }else{
                $item['deal_flag_str'] = E\Eboolean::get_color_desc($item['deal_flag']);
            }
            $item['lesson_problem_str'] = E\Elesson_problem::get_desc($item['lesson_problem']);
        }
        return $this->Pageview(__METHOD__,$ret_list,[]);
    }

    /**
     * 用户管理/加载权限
     */
    public function flush_power() {
        $tea = \App\Helper\Config::get_menu();
        if(\App\Helper\Utils::check_env_is_release()){
            $filter = ["/user_manage_new/power_group_edit", "/user_manage_new/power_group_edit_new"];
        }else{
            $filter = [];
        }
        foreach($tea as $item) { // 过滤核心数据
            if ($item["name"] == "核心数据") {
                if (!isset($item["list"])) continue;
                foreach($item["list"] as $val) {
                    array_push($filter, $val["url"]);
                }
            }
            break;
        }

        $url = \App\Helper\Config::get_url_power_map();
        if(\App\Helper\Utils::check_env_is_local()){
            $groupid = 29; // 非金钱管理账户 $groupid = 29; // 非金钱管理账户
        }else{
            $groupid = 52; // 非金钱管理账户
        }

        $permission = $this->t_authority_group->get_group_authority($groupid);
        $old = $permission;

        foreach($url as $key => $item) {
            if (!in_array($key, $filter)) {
                $permission .= ",".$item;
            }
        }

        $auth = explode(",", $permission);
        $auth = array_unique($auth);
        $auth = implode(",", $auth);

        // 将页面权限分配给非金钱管理账户 group_authority 
        $this->t_authority_group->field_update_list($groupid, [
            "group_authority" => $auth
        ]);

        $adminid = $this->get_account_id();
        $type = 1;
        $new = $auth;
        $this->t_seller_edit_log->row_insert([
            "adminid"     => $adminid,
            "type"        => $type,
            "old"         => $old,
            "new"         => $new,
            "create_time" => time(NULL),
        ],false,false,true );

        return $this->output_succ();
    }

    // 备份当前权限
    public function power_back() {
        $this->t_power_back->back();
        return $this->output_succ();
    }

    // 备份权限列表
    public function power_back_list() {
        $page_info = $this->get_in_page_info();
        list($start_time, $end_time) = $this->get_in_date_range_day(0);
        $ret_info = $this->t_power_back->get_list($start_time, $end_time, $page_info);

        foreach($ret_info["list"] as &$item) {
            $item['log_date'] = date("Y-m-d H:i:s", $item["log_date"]);
        }
        return $this->pageView(__METHOD__, $ret_info);
    }

    // 从备份表中更新数据
    public function update_authority() {
        $groupid = $this->get_in_str_val("groupid");
        $group_name = $this->get_in_str_val("group_name");
        $group_authority = $this->get_in_str_val("group_authority");
        $role_groupid = $this->get_in_str_val("role_groupid");

        $permission = $this->t_authority_group->field_get_list($groupid, "group_authority");
        $old = $permission["group_authority"];

        $this->t_authority_group->field_update_list($groupid, [
            "group_name" => $group_name,
            "group_authority" => $group_authority,
            "role_groupid" => $role_groupid
        ]);

        $adminid = $this->get_account_id();
        $type = 1;
        $new = $group_authority;
        $this->t_seller_edit_log->row_insert([
            "adminid"     => $adminid,
            "type"        => $type,
            "old"         => $old,
            "new"         => $new,
            "create_time" => time(NULL),
        ],false,false,true );

        return $this->output_succ();
    }

    public function get_refund_warn_info() {
        $userid = $this->get_in_str_val("userid");
        $info = $this->t_student_info->get_refund_warn_info($userid);
        $data = [];
        if ($info) {
            $data["学员类型"] = E\Estudent_stu_type::get_desc($info["type"]);
            $warn = "无";
            if ($info["refund_warning_level"] == 3) {
                $warn = "三级";
            } elseif ($info["refund_warning_level"] == 2) {
                $warn = "二级";
            } elseif ($info["refund_warning_level"] == 1) {
                $warn = "一级";
            }
            $data["退费预警级别"] = $warn;
            $warn = json_decode($info["refund_warning_reason"], true);
            if ($warn) $data = array_merge($data, $warn);
        }
        
        return $this->output_succ(['data' => $data]);
    }

}
