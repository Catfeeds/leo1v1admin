<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;
use Illuminate\Support\Facades\Input ;

class seller_student extends Controller
{
    use CacheNick;
    public function student_sub_list()
    {
        $start_date = $this->get_in_str_val('start_date', date('Y-m-d', time(null)-30*86400));
        $end_date   = $this->get_in_str_val('end_date', date('Y-m-d', time(null)+86400));
        $origin     = trim($this->get_in_str_val('origin', ''));
        $grade      = $this->get_in_grade();
        $subject    = $this->get_in_subject();
        $phone      = trim($this->get_in_str_val('phone', ''));
        $page_num   = $this->get_in_page_num();

        $start_date_s = strtotime($start_date);

        $end_date_s = strtotime($end_date)+86400;
        $admin_revisiterid=$this->get_account_id();

        $ret_info=$this->t_seller_student_info_sub->get_list($admin_revisiterid, $phone, $origin, $start_date_s, $end_date_s, $grade, $subject, $page_num);
        foreach ($ret_info["list"] as &$item) {
            \App\Helper\Utils::unixtime2date_for_item($item, "add_time");
            E\Egrade::set_item_value_str($item);
            E\Esubject::set_item_value_str($item);
            E\Epad_type::set_item_value_str($item, "has_pad");
        }
        return $this->pageView(__METHOD__, $ret_info);

    }

    public function student_list_read()
    {
        return $this->student_list();
    }
    public function group_master_student_list() {
        dd("不在使用");
        exit;
        $this->set_in_value("group_master_flag",1) ;
        $adminid=$this->get_account_id() ;
        $this->set_in_value("sub_assign_adminid",$adminid );
        $self_groupid=$this->t_admin_group_name->get_groupid_by_master_adminid($adminid);
        $this->set_in_value("self_groupid", $self_groupid );
        return $this->student_list();
    }

    public function student_list()
    {
        list($start_time,$end_time,$opt_date_str)= $this->get_in_date_range(
            -3, 0, 0, [
            0 => array( "add_time", "资源进来时间"),
            1 => array("next_revisit_time","下次回访时间"),
            2 => array("last_revisit_time","最后一次回访"),
            3 => array("st_application_time","试听申请时间"),
            4 => array("sub_assign_time","分配给主管时间"),
            5 => array("admin_assign_time","分配给组员时间"),
            6 => array("lesson_start","上课时间"),
            7 => array("first_revisite_time","第一次回访时间"),
            ],0
        );
        $this->get_in_int_val("self_groupid", -1);

        $group_master_flag = $this->get_in_int_val("group_master_flag");
        $origin            = trim($this->get_in_str_val('origin', ''));
        $grade             = $this->get_in_grade();
        $subject           = $this->get_in_subject();
        $phone             = trim($this->get_in_str_val('phone', ''));
        $nick              = trim($this->get_in_str_val('nick', ''));
        $phone_location    = trim($this->get_in_str_val('phone_location', ''));
        $admin_revisiterid = $this->get_in_int_val('admin_revisiterid', -1);
        $status            = $this->get_in_int_val('status', -1);
        $origin_ex         = $this->get_in_str_val('origin_ex', "");
        $page_num          = $this->get_in_page_num();
        $page_count        = $this->get_in_page_count();
        $has_pad                 = $this->get_in_int_val("has_pad", -1);
        $ass_adminid_flag        = $this->get_in_int_val("ass_adminid_flag",-1,E\Eboolean::class);
        $tq_called_flag          = $this->get_in_int_val("tq_called_flag",-1,E\Etq_called_flag::class);
        $admin_assign_time_flag  = $this->get_in_int_val("admin_assign_time_flag",-1,E\Eboolean::class);
        $seller_resource_type    = $this->get_in_int_val("seller_resource_type",-1);
        $test_lesson_cancel_flag = $this->get_in_int_val("test_lesson_cancel_flag", -1, E\Etest_lesson_cancel_flag::class );
        $sub_assign_adminid      = $this->get_in_int_val("sub_assign_adminid", -1);

        if ($page_num==4294967296) {
            $number = 1;
        } else {
            $number = ($page_num["page_num"]-1)*$page_count+1;
        }

        $this->t_seller_student_info->switch_tongji_database();

        $ret_info = $this->t_seller_student_info->get_list(
            $page_num, $page_count, $admin_revisiterid, $status, $phone,
            $origin, $opt_date_str, $start_time, $end_time, $grade, $subject,
            $phone_location, $origin_ex, $nick, $has_pad, $ass_adminid_flag,
            $admin_assign_time_flag, $tq_called_flag,$seller_resource_type,
            $sub_assign_adminid, $test_lesson_cancel_flag);
        $check_power_flag = self::check_power(E\Epower::V_TONGJI_SHOW_MONEY);

        if ($group_master_flag) {
            $ret_unallot = $this->t_seller_student_info->group_master_get_unallot($start_time, $end_time,$sub_assign_adminid);
            $ret_unset_admin_revisiterid = $this->t_seller_student_info->group_master_get_unset_admin_revisiterid($start_time, $end_time,$sub_assign_adminid);

        }else{
            $ret_unallot = $this->t_seller_student_info->get_unallot($start_time, $end_time);
            $ret_unset_admin_revisiterid = $this->t_seller_student_info->get_unset_admin_revisiterid($start_time, $end_time);
        }
        foreach ($ret_info["list"] as &$item) {
            \App\Helper\Utils::unixtime2date_for_item($item, "add_time");
            E\Esubject::set_item_value_str($item);
            E\Epad_type::set_item_value_str($item, "has_pad");


            $item["lesson_time"]=\App\Helper\Utils::fmt_lesson_time($item["lesson_start"], $item["lesson_end"]);
            \App\Helper\Utils::unixtime2date_for_item($item, "admin_assign_time");
            \App\Helper\Utils::unixtime2date_for_item($item, "st_application_time");
            \App\Helper\Utils::unixtime2date_for_item($item, "last_revisit_time");
            \App\Helper\Utils::unixtime2date_for_item($item, "first_revisite_time");
            \App\Helper\Utils::unixtime2date_for_item($item, "lesson_start");
            \App\Helper\Utils::unixtime2date_for_item($item, "sub_assign_time");
            $this->cache_set_item_student_nick($item,"origin_userid","origin_user_nick");

            E\Ebook_status::set_item_value_str($item, "status");
            E\Ebook_grade::set_item_value_str($item, "grade");
            E\Etq_called_flag::set_item_value_str($item);
            if ($item["grade_str"]==200) {
                $item["grade_str"]="初中";
            }
            $item["opt_time"]= $item[$opt_date_str];
            $item['number'] = $number;

            $item["admin_revisiterid_nick"] = $this->cache_get_account_nick($item["admin_revisiterid"]);
            $this->cache_set_item_account_nick($item,"admin_revisiterid", "admin_revisiter_nick");
            $this->cache_set_item_account_nick($item,"sub_assign_adminid", "sub_assign_admin_nick");

            $this->cache_set_item_account_nick($item,"ass_adminid","ass_admin_nick");


            $item["teacher_nick"]=$this->cache_get_teacher_nick($item["teacherid"]);
            if ($check_power_flag) {
                $item["money_all"]=(@$item["money_all"])/100;
            } else {
                $item["money_all"]="--";
            }

            $number++;
        }
        return $this->pageView(__METHOD__, $ret_info,["unallot"=>$ret_unallot['unallot_all'],"unset_admin_revisiterid"=>$ret_unset_admin_revisiterid['unset_all']]);
    }


    public function student_no_call_count()
    {
        $adminid = $this->get_account_id();
        $count=$this->t_seller_student_info-> get_no_call_count($adminid);
        return $this->output_succ(["count" => $count]);
    }


    public function student_list2()
    {
        $callerid       = $this->get_in_str_val("callerid");
        $origin         = trim($this->get_in_str_val('origin', ''));
        $phone          = trim($this->get_in_str_val('phone', $callerid));
        $status         = $this->get_in_int_val('status', -1);
        $page_num       = $this->get_in_page_num();
        $phone_location = trim($this->get_in_str_val('phone_location', ''));
        $subject        = $this->get_in_subject();
        $origin_ex      = $this->get_in_str_val("origin_ex");
        $has_pad              = $this->get_in_int_val("has_pad", -1);
        $ass_adminid_flag     = $this->get_in_int_val("ass_adminid_flag",-1,E\Eboolean::class);
        $seller_resource_type = $this->get_in_int_val("seller_resource_type",-1);
        list($start_time,$end_time,$opt_time_str)= $this->get_in_date_range(
            -30, 0, 4, [
            0 => array( "add_time", "资源进来时间"),
            1 => array("next_revisit_time","下次回访时间"),
            2 => array("last_revisit_time","最后一次回访"),
            3 => array("st_application_time","试听申请时间"),
            4 => array("admin_assign_time","分配时间"),
            5 => array("lesson_start","上课时间"),
            ], 0
        );

        $adminid            = $this->get_account_id();
        $next_revisit_count = $this->t_seller_student_info->get_today_next_revisit_count($adminid);
        $require_count      = $this->t_seller_student_info->get_require_count($adminid);

        $return_back_count  = $this->t_seller_student_info->get_return_back_count($adminid);
        $notify_lesson_info = $this->t_seller_student_info->get_notify_lesson_info($this->get_account_id());

        $now=time(null);
        $notify_lesson_check_end_time=strtotime(date("Y-m-d", $now+86400*2));
        $next_day=$notify_lesson_check_end_time-86400;
        $notify_lesson_check_start_time=$now-3600;

        $nick="";
        if (!($phone>0)) {
            $nick=$phone;
            $phone="";
        }
        $this->t_seller_student_info->switch_tongji_database();

        $ret_info=$this->t_seller_student_info->get_list($page_num, 10, $adminid, $status, $phone, $origin, $opt_time_str, $start_time, $end_time, -1, $subject, $phone_location, $origin_ex, $nick, $has_pad,$ass_adminid_flag,-1,-1, $seller_resource_type);
        foreach ($ret_info["list"] as &$item) {
            $lesson_start= $item["lesson_start"];
            $status= $item["status"];
            $notify_lesson_flag_str="";
            $notify_lesson_flag=0;
            if (($status== E\Ebook_status::V_10 || $status== E\Ebook_status::V_12  ) && $lesson_start >= $notify_lesson_check_start_time
                && $lesson_start< $notify_lesson_check_end_time
            ) {
                $notify_lesson_day1=$item["notify_lesson_day1"];
                $notify_lesson_day2=$item["notify_lesson_day2"];
                if ($lesson_start<$next_day && $notify_lesson_day1 ==0) { // 今天的课
                    $notify_lesson_flag_str="<font color=red>未通知</font>";
                    $notify_lesson_flag=1;
                } elseif ($lesson_start>=$next_day && $notify_lesson_day2 ==0) { // 今天的课
                    $notify_lesson_flag_str="<font color=red>未通知</font>";
                    $notify_lesson_flag=1;
                } else {
                    $notify_lesson_flag=2;
                    $notify_lesson_flag_str="<font color=green>已通知</font>";
                }
            }

            $item["notify_lesson_flag_str"]=$notify_lesson_flag_str;
            $item["notify_lesson_flag"]=$notify_lesson_flag;
            \App\Helper\Utils::unixtime2date_for_item($item, "add_time");
            \App\Helper\Utils::unixtime2date_for_item($item, "last_revisit_time", "", "Y-m-d H:i");
            \App\Helper\Utils::unixtime2date_for_item($item, "next_revisit_time", "", "Y-m-d H:i");
            \App\Helper\Utils::unixtime2date_for_item($item, "st_application_time", "", "Y-m-d H:i");
            \App\Helper\Utils::unixtime2date_for_item($item, "admin_assign_time", "", "Y-m-d H:i");

            $item["opt_time"]=$item[$opt_time_str];

            $item["last_revisit_msg_sub"]=mb_substr($item["last_revisit_msg"], 0, 8, "utf-8");
            $item["user_desc_sub"]=mb_substr($item["user_desc"], 0, 6, "utf-8");
            E\Egrade::set_item_value_str($item);
            E\Esubject::set_item_value_str($item);
            E\Epad_type::set_item_value_str($item, "has_pad");
            E\Ebook_status::set_item_value_str($item, "status");
            E\Ebook_grade::set_item_value_str($item, "grade");
            E\Etq_called_flag::set_item_value_str($item );
            $item["lesson_time"]=\App\Helper\Utils::fmt_lesson_time($item["lesson_start"], $item["lesson_end"]);
            $item["teacher_nick"]=$this->cache_get_teacher_nick($item["teacherid"]);
            $this->cache_set_item_account_nick($item,"ass_adminid","ass_admin_nick");
            $this->cache_set_item_student_nick($item,"origin_userid","origin_user_nick");
            $item["st_test_paper_flag_str"]=\App\Helper\Common::get_test_pager_boolean_color_str($item["st_test_paper"],
                                                                                      $item['tea_download_paper_time']);
        }


        return $this->pageView(
            __METHOD__, $ret_info, [
            "next_revisit_count"=>$next_revisit_count,
            "require_count"=>$require_count,
            "return_back_count"=>$return_back_count,
            "notify_lesson_info"=>$notify_lesson_info,
            ]
        );
    }


    public function channel_manage()
    {
        $key0       = $this->get_in_str_val('key0', '');
        $key1       = $this->get_in_str_val('key1', '');
        $key2       = $this->get_in_str_val('key2', '');
        $key3       = $this->get_in_str_val('key3', '');
        $key4       = $this->get_in_str_val('key4', '');
        $value      = trim($this->get_in_str_val('value', ''));
        $origin_level = $this->get_in_int_val('origin_level',-1);
        $page_num   = $this->get_in_page_num();
        $this->get_in_int_val("key1_filed_hide");

        $ret_info=$this->t_origin_key->get_channel_manage($page_num, $key1, $key2, $key3, $key4, $value,$origin_level,$key0);
        $key0_list=$this->t_origin_key->get_key_list("", "", "", "key0");
        $key1_list=$this->t_origin_key->get_key_list("", "", "", "key1",$key0);
        $key2_list=$this->t_origin_key->get_key_list($key1, "", "", "key2",$key0);
        $key3_list=$this->t_origin_key->get_key_list($key1, $key2, "", "key3",$key0);
        $key4_list=$this->t_origin_key->get_key_list($key1, $key2, $key3, "key4",$key0);

        foreach($ret_info["list"] as &$item){
            E\Eorigin_level::set_item_value_str($item, "origin_level");
            $item["create_time_str"] = date("Y-m-d",$item["create_time"]);
        }

        return $this->pageView(
            __METHOD__, $ret_info, [
            "key0_list"=>$key0_list,
            "key1_list"=>$key1_list,
            "key2_list"=>$key2_list,
            "key3_list"=>$key3_list,
            "key4_list"=>$key4_list,
            ]
        );
    }

    public function  channel_manage_key1() {
        $this->set_filed_for_js("key1_filed_hide",1);
        return  $this->channel_manage();
    }

    public function channel_manage_bd(){
        $this->set_in_value("key1","BD");
        return  $this->channel_manage_key1();
    }

    public function channel_manage_yxb(){
        $this->set_in_value("key1","优学帮");
        return  $this->channel_manage_key1();
    }





    private function gen_origin_data($old_list)
    {

        $value_map=$this->t_origin_key->get_list();
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
        $add_data=function (&$item, $add_item) {
            $arr=&$item["data"];
            foreach ($add_item as $k => $v) {
                if (!is_int($k) && $k!="origin") {
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
            $add_data($key4_map, $item );

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
                            $data["key1_class"]=$item1["key_class"];
                            $data["key2_class"]=$item2["key_class"];
                            $data["key3_class"]=$item3["key_class"];
                            $data["key4_class"]=$item4["key_class"];
                            $k4_v=$item4["value"];
                            if ($k4_v != $key4) {
                                $data["key4"]=$key4."/". $k4_v ;
                            }
                            $data["level"]="l-4";
                            $list[]=$data;
                        }

                    }

                }


            }
        }

        return $list;
    }
    public function money_contract_list_for_origin()
    {
        $start_time      = $this->get_in_unixtime_from_str(
            "start_date",
            date("Y-m-1", (time(null)-86400*7))
        );
        $end_time      = $this->get_in_unixtime_from_str(
            "end_date",
            date("Y-m-d", (time(null)+86400))
        );
        $end_time += 86400;

        $contract_type   =  $this->get_in_int_val("contract_type", -1);
        $contract_status = -2;
        $config_courseid = -1;
        $is_test_user    = 0;
        $studentid       = $this->get_in_studentid(-1);
        $check_money_flag = $this->get_in_int_val("check_money_flag", -1);
        $page_num        = $this->get_in_page_num();
        $has_money = 1;

        $show_yueyue_flag = false;
        //试听
        $test_list=$this->t_lesson_info->get_test_listen_info($start_time, $end_time);


        //回访
        $rev_info=$this->t_seller_student_info->get_channel_statistics($start_time, $end_time, "", "");
        $rev_list=$rev_info["list"];


        $ret_list=$this->t_order_info-> get_order_list_for_origin(
            $page_num,
            $start_time,
            $end_time,
            $contract_type,
            $contract_status,
            $studentid,
            $config_courseid,
            $is_test_user,
            $show_yueyue_flag,
            $has_money,
            $check_money_flag
        );
        $list=[];
        foreach ($ret_list["list"] as $item) {
            $origin=$item["origin"];
            $item["test_count"]=0;
            $item["test_user_count"]=0;

            $item["al_count"]=0;
            $item["revisited_yi"]=0;
            $item["revisited_wei"]=0;
            $item["no_call"]=0;
            $item["revisited_wuxiao"]=0;

            if (isset($test_list[$origin])) {
                $item["test_count"]=$test_list[$origin]["test_count"];
                $item["test_user_count"]=$test_list[$origin]["test_user_count"];
                unset($test_list[$origin]);
            }
            if (isset($rev_list[$origin])) {
                $tmp= $rev_list[$origin];
                $item["al_count"]=$tmp["al_count"];
                $item["revisited_yi"]=$tmp["revisited_yi"];
                $item["revisited_wei"]=$tmp["revisited_wei"];
                $item["no_call"]=$tmp["no_call"];
                $item["revisited_wuxiao"]=$tmp["revisited_wuxiao"];
                unset($rev_list[$origin]);
            }


            $list[]=$item;
        }
        foreach ($test_list as $item) {
            $item["money_all"]=0;
            $item["order_count"]=0;
            $item["user_count"]=0;
            $item["new_price"]=0;

            $item["al_count"]=0;
            $item["revisited_yi"]=0;
            $item["revisited_wei"]=0;
            $item["no_call"]=0;
            $item["revisited_wuxiao"]=0;

            $list[]=$item;
        }

        foreach ($rev_list as $item) {
            $item["money_all"]=0;
            $item["order_count"]=0;
            $item["user_count"]=0;
            $item["new_price"]=0;

            $item["test_count"]=0;
            $item["test_user_count"]=0;
            $list[]=$item;
        }



        if (count($list)>0) {
            $ret_list["list"]= $this->gen_origin_data($list);
        }

        return $this->Pageview(__METHOD__, $ret_list);

    }


    public function channel_statistics()
    {
        dd ("关闭");
        list($start_time,$end_time )= $this->get_in_date_range(-30, 1);

        $origin = trim($this->get_in_str_val('origin', ""));
        $origin_ex = trim($this->get_in_str_val('origin_ex', ""));
        $admin_revisiterid = $this->get_in_int_val("admin_revisiterid", -1);
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
        //得到渠道列表

        $ret_info=$this->t_seller_student_info->get_channel_statistics($start_time, $end_time, $origin, $origin_ex, $admin_revisiterid,$adminid_list);


        $ret_info["list"]= $this->gen_origin_data($ret_info["list"]);


        /*
        $check_power_flag=self::check_power(E\Epower::V_TONGJI_SHOW_MONEY);
        foreach ($ret_info["list"] as &$f_item) {
            if ($check_power_flag) {
                @$f_item["money_all"]/=100;
                @$f_item["first_money"]/=100;
            } else {
                @$f_item["money_all"]="--";
                @$f_item["first_money"]="--";
            }
        }
        */

        $data_list=$this->t_seller_student_info->get_channel_statistics_2($start_time, $end_time, $origin, $origin_ex, $admin_revisiterid,$adminid_list);
        $subject_map=[];
        $grade_map=[];
        $has_pad_map=[];
        $area_map=[];
        $all_count=count($data_list);

        foreach ($data_list as $a_item) {
            $subject=$a_item["subject"];
            $grade=$a_item["grade"];
            $has_pad=$a_item["has_pad"];
            $area_name=substr($a_item["phone_location"], 0, -6);
            @$subject_map[$subject] ++;
            @$grade_map[$grade] ++;
            @$has_pad_map[$has_pad] ++;
            if (strlen($area_name)>5) {
                @$area_map[$area_name] ++;
            } else {
                @$area_map[""] ++;
            }
        }
        $group_list= $this->t_admin_group_name->get_group_list(2);

        return $this->pageView(
            __METHOD__, $ret_info, [
            "grade_map"  => $grade_map,
            "subject_map"  => $subject_map,
            "has_pad_map"  => $has_pad_map,
            "area_map"  => $area_map,
            "group_list"=>$group_list
            ]
        );
    }



    public function channel_summary()
    {
        $start_date = $this->get_in_str_val('start_date', date('Y-m-d', time(null)-30*86400));
        $end_date   = $this->get_in_str_val('end_date', date('Y-m-d', time(null)+86400));
        $origin = trim($this->get_in_str_val('origin', ""));
        $origin_ex         = $this->get_in_str_val('origin_ex', "");
        $start_time = strtotime($start_date);
        $end_time   = strtotime($end_date)+86400;
        $page_num   = $this->get_in_page_num();
        $ret_info=$this->t_seller_student_info->get_channel_summary($page_num, $start_time, $end_time, $origin, $origin_ex);

        $all_item=[
            "origin"=>"全部",
            "all_count"=>0,
            "effective"=>0,
            "listened_yi"=>0,
            "listened"=>0,
        ];

        foreach ($ret_info["list"] as &$item) {

            $item["effective"]    = $item["all_count"]-$item["no_call"]-$item["invalid_count"]-$item["not_connect"];
            $item["listened"]     = $item["listened_dai"]+$item["listened_wei"]+$item["listened_yi"];

            $all_item["all_count"]+=$item["all_count"];
            $all_item["effective"]+=$item["effective"];
            $all_item["listened_yi"]+=$item["listened_yi"];
            $all_item["listened"]+=$item["listened"];


        }

        array_unshift($ret_info["list"], $all_item);

        return $this->pageView(__METHOD__, $ret_info);
    }

    public function seller_count()
    {
        $sum_field_list=[
            "all_count",
            "all_count_0",
            "all_count_1",
            "no_call",
            "no_call_0",
            "no_call_1",
            "call_count",
            "invalid_count",
            "no_connect",
            "valid_count",
            "require_test_count",
            "test_lesson_count",
            "order_count",
        ];
        $order_field_arr=  array_merge(["account" ] ,$sum_field_list );

        list( $order_in_db_flag, $order_by_str, $order_field_name,$order_type )
            =$this->get_in_order_by_str($order_field_arr ,"account desc");

        $group_list=$this->t_admin_group_name->get_group_list(2);
        $groupid=$this->get_in_int_val("groupid", -1);
        list($start_time,$end_time,$opt_date_str)= $this->get_in_date_range(
            date("Y-m-01") , 0, 4, [
            0 => array( "add_time", "资源进来时间"),
            2 => array("last_revisit_time","最后一次回访"),
            3 => array("st_application_time","试听申请时间"),
            4 => array("admin_assign_time","分配时间"),
            6 => array("first_revisite_time","第一次回访时间"),
            ], 0
        );
        $origin_ex           = $this->get_in_str_val("origin_ex");

        $ret_info=$this->t_seller_student_info->get_seller_count($opt_date_str, $start_time, $end_time,$origin_ex,$groupid);
        $all_item=["account" => "全部" ];
        foreach ($ret_info["list"] as &$item) {
            $item["valid_count"]=$item["call_count"]-  $item["invalid_count"]-$item["no_connect"];
            foreach ($item as $key => $value) {
                if ((!is_int($key)) && ($key != "admin_revisiterid" )) {
                    $all_item[$key]=(@$all_item[$key])+$value;
                }
            }
            $item["account"]=$this->cache_get_account_nick($item["admin_revisiterid"]);
        }
        $ret_info = $ret_info['list'];
        if (!$order_in_db_flag) {
            \App\Helper\Utils::order_list( $ret_info, $order_field_name, $order_type );
        }
        //新例子获得数



        array_unshift($ret_info, $all_item);

        return $this->pageView(__METHOD__,\App\Helper\Utils::list_to_page_info($ret_info),["group_list"=>$group_list,"data_ex_list"=>$ret_info]);

    }


    public function upload_from_csv_cp()
    {
        $acc  = $this->get_account();
        $file = Input::file('file');
        $f    = fopen($file,'r');
        while ($data = fgetcsv($f)) { //每次读取CSV里面的一行内容
            $goods_list[] = $data;
        }
        unset($goods_list[0]);
        $arr_id = [];
        foreach($goods_list as $item){
            $phone = $item[5];
            $answer_begin_time = strtotime($item[1]);
            $ret_id = $this->t_teacher_lecture_appointment_info->check_is_exist(0,$phone);
            if(!$ret_id){
                $answer_end_time = strtotime($item[2]);
                $id = $this->t_teacher_lecture_appointment_info->add_all_info(
                    $answer_begin_time,$answer_end_time,$item[3],$item[4],$item[5],
                    $item[6],$item[7],$item[8],$item[9],$item[10],
                    $item[11],$item[12],$item[13],$acc
                );
                $arr_id[]=$id;
            }else{
                $arr_id[]=$ret_id;
            }
        }
        return $this->output_succ(["data"=> $arr_id]);
    }

    public function upload_from_xls_jingxun()
    {
                    $grade_map=[
                        '200'=>201,
                        '初二'=>202,
                        '初三'=>203,
                        '初一'=>201,
                        '二年级'=>102,
                        '高二'=>302,
                        '高三'=>303,
                        '高一'=>301,
                        '八年级'=>202,
                        '九年级'=>203,
                        '六年级'=>201,
                        '七年级'=>202,
                        '预备八年级'=>202,
                        '预备九年级'=>203,
                        '预备六年级'=>201,
                        '预备七年级'=>202,

                        '三年级'=>103,
                        '四年级'=>104,
                        '未填写'=>100,
                        '五年级'=>105,

                        '小二'=>102,
                        '小六'=>106,
                        '小三'=>103,
                        '小四'=>104,
                        '小五'=>106,
                        '小学'=>100,
                        '小一'=>101,
                        '学龄前'=>101,
                        '一年级'=>101,
                    ];

                    $subject_map= array(
                        "语文"=> 1,
                        "数学"=> 2,
                        "英语"=> 3,
                        "化学"=> 4,
                        "物理"=> 5,
                        "生物"=> 6,
                        "政治"=> 7,
                        "历史"=> 8,
                        "地理"=> 9,
                    );

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
                        foreach ($arr as $index => $item) {
                            if ($index== 0) { //标题
                                //验证字段名
                                if (trim($item[0]) != "渠道"
                                    ||trim($item[1]) != "孩子姓名"
                                ) {
                                    return "字段不对." ;
                                }
                            } else {
                                //导入数据

                                /*

                                  渠道	孩子姓名	手机	性别	年级	科目	家长姓名	平板	备注	工号

                                */

                                $phone_location = "" ;
                                $phone= $item[2] ;
                                if (! $phone  ) {
                                    continue ;
                                }


                                $origin         = $original_name ;
                                $nick=$item[1] ;
                                $user_desc      =
                                                "性别:".$item[3] . "\n".
                                                "家长:".$item[6] . "\n".
                                                 $item[8]  ;
                                $grade          = $item[4];
                                $subject        = $item[5] ;
                                $add_time  = time(NULL) ;
                                $has_pad  =  $item[7];

                                if (strpos($has_pad, "iPad")!== false) {
                                    $has_pad=1;
                                } elseif (strpos($has_pad, "安卓") !== false) {
                                    $has_pad=2;
                                } elseif (strpos($has_pad, "有") !== false) {
                                    $has_pad=1;
                                }

                                if (isset($grade_map[$grade])) {
                                    $grade = $grade_map[$grade] ;
                                }

                                $subject_str=$subject;
                                if (isset($subject_map[$subject])) {
                                    $subject = $subject_map[$subject] ;
                                }

                                //echo "$phone : $grade:$subject_str:$subject :$user_desc<br/>" ;

                                //$admin_revisiterid=0 ,$st_application_time=0, $st_application_nick="", $st_demand="",$status=0, $ass_adminid=0
                                $this->t_seller_student_info->add_or_add_to_sub(
                                    $nick,
                                    $phone,
                                    $grade,
                                    $origin,
                                    $subject,
                                    $has_pad,
                                    "",
                                    "",
                                    $user_desc,
                                    $add_time,
                                    true,

                                    0 ,0, "", "",0, 0);
                            }
                        }


                        return outputjson_success();
                    } else {
                        return outputjson_ret(false);
                    }

    }

    public function upload_from_xls_youzan()
    {

                    $grade_map=[
                        '200'=>201,
                        '初二'=>202,
                        '初三'=>203,
                        '初一'=>201,
                        '二年级'=>102,
                        '高二'=>302,
                        '高三'=>303,
                        '高一'=>301,
                        '八年级'=>202,
                        '九年级'=>203,
                        '六年级'=>201,
                        '七年级'=>202,
                        '预备八年级'=>202,
                        '预备九年级'=>203,
                        '预备六年级'=>201,
                        '预备七年级'=>202,

                        '三年级'=>103,
                        '四年级'=>104,
                        '未填写'=>100,
                        '五年级'=>105,

                        '小二'=>102,
                        '小六'=>106,
                        '小三'=>103,
                        '小四'=>104,
                        '小五'=>106,
                        '小学'=>100,
                        '小一'=>101,
                        '学龄前'=>101,
                        '一年级'=>101,
                    ];

                    $subject_map= array(
                        "语文"=> 1,
                        "数学"=> 2,
                        "英语"=> 3,
                        "化学"=> 4,
                        "物理"=> 5,
                        "生物"=> 6,
                        "政治"=> 7,
                        "历史"=> 8,
                        "地理"=> 9,
                    );

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
                        foreach ($arr as $index => $item) {
                            if ($index== 0) { //标题
                                //验证字段名
                                if (trim($item[0]) != "订单状态"
                                    ||trim($item[1]) != "买家会员名"
                                ) {
                                    return "xxx" ;
                                }
                            } else {
                                //导入数据

                                /*

                                  0.订单状态
                                  1买家会员名
                                  2买家省份
                                  3买家城市
                                  4买家实际支付金额
                                  5SKU
                                  6订单创建时间
                                  7商品留言
                                  8订单留言

                                */
                                $info_item=explode(";", $item[7]);
                                if (count($info_item)<2) {
                                    continue;

                                }
                                foreach ($info_item as $i_item) {
                                    $tmp_arr=explode(":", $i_item);
                                    $tmp_key=trim($tmp_arr[0]);
                                    if ($tmp_key) {
                                        $tmp_v=trim($tmp_arr[1]);
                                        if ($tmp_key=="手机号") {
                                            $phone= $tmp_v;
                                        } elseif ($tmp_key=="学生姓名") {
                                            $nick     = $tmp_v;
                                        } elseif ($tmp_key=="有无pad") {
                                            $has_pad = $tmp_v;
                                        }
                                    }


                                }


                                $phone_location = "" ;
                                $origin         = $original_name ;
                                $user_desc      = $item[4]."元" ;
                                $grade          = $item[5];
                                $subject        = "" ;
                                $add_time  = strtotime($item[6]);
                                $user_desc_1      =$item[0];
                                $user_desc_2      = isset($item[8]) ?$item[8]:"";
                                $user_desc_arr=[];
                                if ($user_desc) {
                                    $user_desc_arr[]=$user_desc;
                                }
                                if ($user_desc_1) {
                                    $user_desc_arr[]=$user_desc_1;
                                }
                                if ($user_desc_2) {
                                    $user_desc_arr[]=$user_desc_2;
                                }

                                $user_desc=join(",", $user_desc_arr);

                                if (strpos($has_pad, "iPad")!== false) {
                                    $has_pad=1;
                                } elseif (strpos($has_pad, "安卓") !== false) {
                                    $has_pad=2;
                                } elseif (strpos($has_pad, "有") !== false) {
                                    $has_pad=1;
                                }

                                if (isset($grade_map[$grade])) {
                                    $grade = $grade_map[$grade] ;
                                }

                                $subject_str=$subject;
                                if (isset($subject_map[$subject])) {
                                    $subject = $subject_map[$subject] ;
                                }

                                //echo "$phone : $grade:$subject_str:$subject :$user_desc<br/>" ;

                                $this->t_seller_student_info->add_or_add_to_sub(
                                    $nick,
                                    $phone,
                                    $grade,
                                    $origin,
                                    $subject,
                                    $has_pad,
                                    "",
                                    "",
                                    $user_desc,
                                    $add_time,
                                    true
                                );
                            }
                        }


                        return outputjson_success();
                    } else {
                        return outputjson_ret(false);
                    }

    }



    public function upload_from_xls()
    {

                    $grade_map=[
                        '200'=>201,
                        '八年级'=>202,
                        '初二'=>202,
                        '初三'=>203,
                        '初一'=>201,
                        '二年级'=>102,
                        '高二'=>302,
                        '高三'=>303,
                        '高一'=>301,
                        '九年级'=>203,
                        '六年级'=>201,
                        '七年级'=>202,
                        '三年级'=>103,
                        '四年级'=>104,
                        '未填写'=>100,
                        '五年级'=>105,
                        '小二'=>102,
                        '小六'=>106,
                        '小三'=>103,
                        '小四'=>104,
                        '小五'=>106,
                        '小学'=>100,
                        '小一'=>101,
                        '学龄前'=>101,
                        '一年级'=>101,
                    ];

                    $subject_map= array(
                        "语文"=> 1,
                        "数学"=> 2,
                        "英语"=> 3,
                        "化学"=> 4,
                        "物理"=> 5,
                        "生物"=> 6,
                        "政治"=> 7,
                        "历史"=> 8,
                        "地理"=> 9,
                    );

                    $file = Input::file('file');

                    if ($file->isValid()) {
                        //处理列
                        $tmpName = $file ->getFileName();
                        $realPath = $file -> getRealPath();

                        $objReader = \PHPExcel_IOFactory::createReader('Excel2007');
                        $obj_file="/tmp/001.xls";
                        move_uploaded_file($realPath, $obj_file);
                        $objPHPExcel = $objReader->load($obj_file);
                        $objPHPExcel->setActiveSheetIndex(0);
                        $arr=$objPHPExcel->getActiveSheet()->toArray();
                        foreach ($arr as $index => $item) {
                            if ($index== 0) { //标题
                                //验证字段名
                                if (trim($item[0]) != "手机号"
                                    ||trim($item[1]) != "归属地"
                                    ||trim($item[3]) != "来源"
                                ) {
                                    return "xxx" ;
                                }
                            } else {
                                //导入数据
                                /*
                                  0 => "手机号"
                                  1 => "归属地"
                                  2 => "时间"
                                  3 => "来源"
                                  4 => "姓名"
                                  5 => "用户备注"
                                  6 => "年级"
                                  7 => "科目"
                                  8 => "是否有pad"
                                */
                                $phone          = $item[0];
                                $phone_location = $item[1];
                                $origin         = $item[3];
                                $nick           = $item[4];
                                $user_desc      = $item[5];
                                $grade          = $item[6];
                                $subject        = $item[7];
                                $has_pad        = $item[8];
                                $user_desc_1      = isset($item[9]) ?$item[9]:"";
                                $user_desc_2      = isset($item[10]) ?$item[10]:"";
                                $user_desc_3      = isset($item[11]) ?$item[11]:"";
                                $user_desc_arr=[];
                                if ($user_desc) {
                                    $user_desc_arr[]=$user_desc;
                                }
                                if ($user_desc_1) {
                                    $user_desc_arr[]=$user_desc_1;
                                }
                                if ($user_desc_2) {
                                    $user_desc_arr[]=$user_desc_2;
                                }
                                if ($user_desc_3) {
                                    $user_desc_arr[]=$user_desc_3;
                                }
                                $user_desc=join(",", $user_desc_arr);



                                if (isset($grade_map[$grade])) {
                                    $grade = $grade_map[$grade] ;
                                }

                                $subject_str=$subject;
                                if (isset($subject_map[$subject])) {
                                    $subject = $subject_map[$subject] ;
                                }


                                //echo "$phone : $grade:$subject_str:$subject :$user_desc<br/>" ;

                                $this->t_seller_student_info->add_or_add_to_sub(
                                    $nick,
                                    $phone,
                                    $grade,
                                    $origin,
                                    $subject,
                                    $has_pad,
                                    "",
                                    "",
                                    $user_desc
                                );
                            }
                        }


                        return outputjson_success();
                    } else {
                        return outputjson_ret(false);
                    }

    }

    public function update_book_revisit()
    {
        $phone   = $this->get_in_str_val('phone', '');
        $op_note = $this->get_in_str_val('op_note', '');

        $sys_operator = $_SESSION['acc'];

        if ($op_note == '' || $phone == '') {
            return outputJson(array('ret' => -1, 'info' => '回访记录不能为空'));
        }
        $ret_add = $this->t_seller_student_info->update_book_revisit($phone, $op_note, $sys_operator);
        if ($ret_add === false) {
            return outputJson(array('ret' => -1, 'info' => '系统错误'));
        }

        return outputJson(array('ret' => 0, 'info' => '添加成功'));
    }

    public function set_status()
    {
        $status       = $this->get_in_int_val("status");
        $phone   = $this->get_in_str_val('phone', '');

        $old_status=$this->t_seller_student_info->get_status($phone);
        if ($old_status != $status) {
            $account=$this->get_account();
            $ret_update = $this->t_book_revisit->add_book_revisit(
                $this->get_in_phone_ex(),
                sprintf(
                    "操作者: $account 状态: %s=>%s",
                    E\Ebook_status::get_desc($old_status),
                    E\Ebook_status::get_desc($status)
                ),
                "system"
            );
        }

        $ret_add = $this->t_seller_student_info->update_status($phone, $status);
        if ($ret_add === false) {
            return outputJson(array('ret' => -1, 'info' => '系统错误'));
        }

        return outputJson(array('ret' => 0, 'info' => '添加成功'));

    }

    public function add_stu_info()
    {
        $id           = $this->get_in_int_val("id", "");
        $nick         = $this->get_in_str_val("nick", "");
        $subject      = $this->get_in_subject();
        $grade        = $this->get_in_grade();
        $user_desc    = $this->get_in_str_val("user_desc", "");
        $st_application_nick = $this->get_in_str_val("st_application_nick");
        $status=$this->get_in_int_val("status" ,0);
        $ass_adminid= $this->get_in_int_val("ass_adminid");

        $admin_revisiterid= $this->get_in_int_val("admin_revisiterid",0);
        $st_demand = $this->get_in_str_val("st_demand");
        $userid = $this->get_in_userid();
        $st_application_time=0;

        if ($st_application_nick){
            $st_application_time=time(NULL);
        }

        $set_self_flag=$this->get_in_int_val("set_self_flag",0);
        if ($set_self_flag ) {
            $admin_revisiterid= $this->get_account_id();
        }

        $origin        = $this->get_in_str_val("origin", "");

        $phone=$this->get_in_phone_ex();
        if ($origin == '') {
            $origin= "后台添加";
        }
        $trial_type=0;
        $has_pad=3;
        $qq="";

        $ret = $this->t_seller_student_info->add_or_add_to_sub($nick, $phone, $grade, $origin, $subject, $has_pad, $trial_type, $qq, "",0, true, $admin_revisiterid,$st_application_time, $st_application_nick, $st_demand,$status,$ass_adminid,0,$userid );
        return $this->output_bool_ret($ret);
    }

    public function del_student()
    {
        $phone = $this->get_in_phone();

        $ret = $this->t_seller_student_info->delete_student($phone);
        return $this->output_succ();

    }
    public function set_sub_assign_adminid()
    {
        $phone_list_str    = trim($this->get_in_str_val('phone_list', ''));
        $sub_assign_adminid= $this->get_in_int_val('sub_assign_adminid');
        $phone_list        = \App\Helper\Common::json_decode_as_array($phone_list_str);


        if ($sub_assign_adminid< 0) {
            $sub_assign_adminid=0;
        }
        $new_admin_nick= $this->cache_get_account_nick($sub_assign_adminid);

        $sub_assign_time = time();

        foreach ($phone_list as $phone) {
            $msg="";
            $old_admin_revisiterid=$this->t_seller_student_info->get_admin_revisiterid($phone);
            $book_phone=\App\Helper\Utils::get_phone($phone);
            $this->t_book_revisit->add_book_revisit($book_phone, "分配 小组长[$new_admin_nick]", "系统");
            $ret_seller = $this->t_seller_student_info->field_update_list($phone,[
                "sub_assign_time" => time(NULL),
                "sub_assign_adminid" => $sub_assign_adminid,
            ]);
            if ($ret_seller=== false) {
                return $this->output_err("");
            }

        }

        return outputjson_success();
    }


    public function set_admin_revisiterid()
    {
        $phone_list_str    = trim($this->get_in_str_val('phone_list', ''));
        $admin_revisiterid = $this->get_in_int_val('admin_revisiterid');
        $phone_list        = \App\Helper\Common::json_decode_as_array($phone_list_str);

        if ($admin_revisiterid < 0) {
            $admin_revisiterid =0;
        }
        $new_admin_nick= $this->cache_get_account_nick($admin_revisiterid);
        $st_application_nick = $new_admin_nick;
        $st_application_time = time();

        foreach ($phone_list as $phone) {
            $msg="";
            $old_admin_revisiterid=$this->t_seller_student_info->get_admin_revisiterid($phone);
            $book_phone=\App\Helper\Utils::get_phone($phone);
            if ($old_admin_revisiterid) {
                $old_admin_nick=$this->cache_get_account_nick($old_admin_revisiterid);
                $this->t_book_revisit->add_book_revisit($book_phone, "设置 负责人[$old_admin_nick]=>[$new_admin_nick]", "系统");
            } else {
                $this->t_book_revisit->add_book_revisit($book_phone, "设置 负责人[$new_admin_nick]", "系统");
            }
            $ret_seller = $this->t_seller_student_info->set_seller_info($phone, $admin_revisiterid,$st_application_nick);
            if ($ret_seller=== false) {
                return $this->output_err("");
            }

        }

        return outputjson_success();
    }

    public function update_user_info()
    {
        $phone   = $this->get_in_str_val('phone', '');
        $op_note = trim($this->get_in_str_val('op_note', ''));
        $status  = $this->get_in_int_val('status', 0);
        $note    = $this->get_in_str_val('note', 0);
        $adminid = $this->get_account_id();
        $account = $this->get_account();
        $cancel_flag= $this->get_in_int_val("cancel_flag");
        $cancel_reason= $this->get_in_str_val("cancel_reason");


        $old_status=$this->t_seller_student_info->get_status($phone);
        if ($old_status==E\Ebook_status::V_15) { //课程取消
            if ($status!=15) {
                return $this->output_err("课程取消后,不能修改状态");
            }
        }


        if ($op_note) {
            $ret_update = $this->t_book_revisit->add_book_revisit($this->get_in_phone_ex(), $op_note, $account);
            if ($ret_update === false) {
                return outputJson(array('ret' => -1, 'info' => '系统错误'));
            }
        }

        if ($status == E\Ebook_status::V_14) { //驳回
            if (\App\Helper\Utils::check_env_is_release()) {
                $st_application_nick=$this->t_seller_student_info->get_st_application_nick($phone);
                $this->t_manager_info->send_wx_todo_msg($st_application_nick,"来自:". $account
                                                        ,"驳回[$phone]","","");
            }
        }

        if ($status == E\Ebook_status::V_15 ) { //课程取消
            $lessonid=$this->t_seller_student_info->get_st_arrange_lessonid($phone);
            $lesson_start= $this->t_lesson_info->get_lesson_start($lessonid);
            $cancel_teacherid= $this->t_lesson_info->get_teacherid($lessonid);
            if ($lesson_start) { //有取消数据
                $userid= $this->t_lesson_info->get_userid($lessonid);
                $this->t_test_lesson_log_list->update_test_lesson_status(
                    $userid,
                    $lessonid,
                    $lesson_start,
                    E\Etest_lesson_status::V_3
                );

                $this->t_seller_student_info->field_update_list($phone,[
                    "cancel_lesson_start" => $lesson_start,
                    "cancel_teacherid" => $cancel_teacherid,
                ]);

                $this->t_lesson_info-> delete_test_lesson($lessonid);
                $this->t_lesson_info->field_update_list($lessonid,[
                    "confirm_flag"   => E\Econfirm_flag::V_3,
                    "confirm_adminid"   =>  $this->get_account_id(),
                    "confirm_time"   =>  time(NULL),
                    "confirm_reason"   =>  $cancel_reason,
                    "lesson_status"   =>   E\Elesson_status::V_END,
                ]);

            }
            //cancel_flag
            $this->t_seller_student_info->field_update_list($phone,[
                "cancel_flag" => $cancel_flag,
                "cancel_adminid" => $this->get_account_id(),
                "cancel_time" => time(NULL),
                "cancel_reason" => $cancel_reason,
            ]);

        }

        if ($old_status != $status) {
            $ret_update = $this->t_book_revisit->add_book_revisit(
                $this->get_in_phone_ex(),
                sprintf(
                    "操作者: $account 状态: %s=>%s",
                    E\Ebook_status::get_desc($old_status),
                    E\Ebook_status::get_desc($status)
                ),
                "system"
            );
        }

        $this->t_seller_student_info->set_first_revisite_time($phone);
        $this->t_seller_student_info->set_revisit_info($phone, $status, $note, $op_note);

        if ($status == E\Ebook_status::V_12) { //试听课确认
            $lessonid=$this->t_seller_student_info->get_st_arrange_lessonid($phone);
            if ($lessonid>0) {
                $lesson_start = $this->t_lesson_info->get_lesson_start($lessonid);
                $lesson_end   = $this->t_lesson_info->get_lesson_start($lessonid);
                /*
                 * 试听排课完成
                 * SMS_11490189
                 * 家长您好，已经帮您孩子成功预约${date_str}的试听课，请与孩子一起准时参加试听,试听当天请登录理优1对1学生端，
                 * 在课表中点击红色区域进入课堂（具体操作请参考：http://dwz.cn/3HyaA2） ，祝您试听愉快，
                 * 有任何问题请拨打您的服务专线：${public_telphone}
                 **/
                $date_str = date("m月d日 H:i", $lesson_start)."-".date("H:i", $lesson_end);
                $arr      = [
                    "date_str"        => $date_str,
                    "public_telphone" => "400-169-3070",
                ];
                \App\Helper\Net::send_sms_taobao($phone, 0, 11490189, $arr);
            }
        }

        return outputjson_success();
    }

    public function update_next_revisit_time()
    {
        $phone          = $this->get_in_str_val('phone', '');
        $next_revisit_time = $this->get_in_str_val('next_revisit_time', 0);

        $next_revisit_time_s= strtotime($next_revisit_time);
        $ret_info = $this->t_seller_student_info->field_update_list(
            $phone, [
            "next_revisit_time" => $next_revisit_time_s
            ]
        );

        return outputjson_success();
    }


    public function update_news()
    {
        $old_phone   = $this->get_in_str_val('old_phone', '');
        $grade       = $this->get_in_int_val('grade', 0);
        $subject     = $this->get_in_int_val('subject', 0);
        $pad         = $this->get_in_int_val('pad', 0);
        $nick        = $this->get_in_str_val('nick', "");
        $origin = $this->get_in_str_val('origin', "");
        $user_desc =trim($this->get_in_str_val('user_desc', ""));
        $from_type= $this->get_in_int_val('from_type');
        $ret_info = $this->t_seller_student_info->update_student_news($grade, $subject, $pad, $nick, $old_phone, $from_type,$user_desc);
        $this->t_seller_student_info->reset_origin_and_log($old_phone, $origin, $this->get_account());

        return outputjson_success();
    }


    public function get_show_student_info()
    {
        $phone          = $this->get_in_str_val('phone', '');


        //\App\Helper\Utils::logger("phone :$phone");
        $ret_info = $this->t_seller_student_info->get_show_student_info($phone);

        $ret_info['grade']   = E\Egrade::get_desc($ret_info['grade']);
        $ret_info['subject'] = E\Esubject::get_desc($ret_info['subject']);
        $ret_info['has_pad'] = E\Epad_type::get_desc($ret_info['has_pad']);

        //dd($ret_info);
        return outputJson(array('ret'=>0,'ret_info'=>$ret_info));
    }

    public function set_test_lesson_info()
    {
        $phone          = $this->get_in_phone();
        $st_class_time  = $this->get_in_str_val("st_class_time");
        $st_from_school = $this->get_in_str_val("st_from_school");
        $st_demand      = $this->get_in_str_val("st_demand");

        $status=$this->t_seller_student_info->get_status($phone);
        if ($status == 6
            || $status == 7
            || $status == 8
            || $status == 10
        ) {
            return $this->output_err("已不能设置了");
        }

        if (\App\Helper\Utils::check_env_is_release()) {
            /*
            $email= "812179469@qq.com";
            //$email= "329732001@qq.com";
            dispatch(
                new \App\Jobs\SendEmail(
                    $email,
                    "有预约：$phone",
                    "电话：$phone <br/>".
                                               "时间：$st_class_time <br/> ".
                    "需求：$st_demand<br/>"
                )
            );
            */
        }

        $status= E\Ebook_status::V_TEST_LESSON_REPORT;
        $old_status=$this->t_seller_student_info->get_status($phone);
        if ($old_status != $status) {
            $account=$this->get_account();
            $ret_update = $this->t_book_revisit->add_book_revisit(
                $this->get_in_phone_ex(),
                sprintf(
                    "操作者: $account 状态: %s=>%s",
                    E\Ebook_status::get_desc($old_status),
                    E\Ebook_status::get_desc($status)
                ),
                "system"
            );
        }

        $st_class_time_s=strtotime($st_class_time);
        $this->t_seller_student_info->update_test_lesson_info($phone, $this->get_account(), $st_class_time_s, $st_from_school, $st_demand);

        return outputjson_success();
    }

    public function get_user_info()
    {
        $phone = $this->get_in_phone();
        $row=$this->t_seller_student_info->field_get_list($phone, "*");
        return outputjson_success(["data"=> $row  ]);
    }

    public function save_user_info()
    {
        $userid        = $this->get_in_userid();
        $phone         = $this->get_in_phone();
        $grade         = $this->get_in_grade();
        $subject       = $this->get_in_subject();
        $address       = $this->get_in_str_val("address");
        $has_pad       = $this->get_in_int_val("has_pad");
        $gender        = $this->get_in_int_val("gender");
        $user_desc     = $this->get_in_str_val("user_desc");
        $status        = $this->get_in_int_val("status");
        $stu_nick      = $this->get_in_str_val("stu_nick");
        $par_nick      = $this->get_in_str_val("par_nick");
        $editionid     = $this->get_in_int_val("editionid");
        $school        = $this->get_in_str_val("school");
        $revisite_info = trim($this->get_in_str_val("revisite_info"));

        $next_revisit_time     = $this->get_in_str_val("next_revisit_time");
        $st_class_time         = $this->get_in_str_val("st_class_time");
        $st_demand             = $this->get_in_str_val("st_demand");
        $stu_score_info        = $this->get_in_str_val("stu_score_info");
        $stu_test_lesson_level = $this->get_in_str_val("stu_test_lesson_level");
        $stu_test_ipad_flag    = $this->get_in_str_val("stu_test_ipad_flag");
        $stu_character_info    = $this->get_in_str_val("stu_character_info");
        $stu_request_test_lesson_time_info = $this->get_in_str_val("stu_request_test_lesson_time_info");
        $stu_request_lesson_time_info      = $this->get_in_str_val("stu_request_lesson_time_info");

        $has_pad = $this->get_in_str_val("has_pad");

        if ($next_revisit_time) {
            $next_revisit_time =strtotime($next_revisit_time);
        } else {
            $next_revisit_time =0;
        }
        if ($st_class_time) {
            $st_class_time =strtotime($st_class_time);
        } else {
            $st_class_time =0;
        }

        $old_status=$this->t_seller_student_info->get_status($phone);
        if ($old_status != $status) {
            $account=$this->get_account();
            $ret_update = $this->t_book_revisit->add_book_revisit(
                $this->get_in_phone_ex(),
                sprintf(
                    "操作者: $account 状态: %s=>%s",
                    E\Ebook_status::get_desc($old_status),
                    E\Ebook_status::get_desc($status)
                ),
                "system"
            );
        }

        $this->cache_del_student_nick($userid);

        $set_arr=[
            "address"     => $address,
            "gender"      => $gender,
            "nick"        => $stu_nick,
            "school"      => $school,
            "editionid"   => $editionid,
            "parent_name" => $par_nick,
        ];
        if ( ! $this->t_student_info->get_assistantid($userid) ) {
            $set_arr["grade"] = $grade;
        }

        $this->t_student_info->field_update_list($userid, $set_arr);
        $this->t_seller_student_info->field_update_list(
            $phone, [
            "nick"                  => $stu_nick,
            "has_pad"               => $has_pad,
            "user_desc"             => $user_desc,
            "status"                => $status,
            "subject"               => $subject,
            "st_from_school"        => $school,
            "next_revisit_time"     => $next_revisit_time,
            "last_revisit_time"     => time(null),
            "grade"                 => $grade,
            "st_class_time"         => $st_class_time,
            "st_demand"             => $st_demand,
            "stu_score_info"        => $stu_score_info,
            "stu_test_lesson_level" => $stu_test_lesson_level,
            "stu_test_ipad_flag"    => $stu_test_ipad_flag,
            "stu_character_info"    => $stu_character_info,
            "stu_request_lesson_time_info"      => $stu_request_lesson_time_info,
            "stu_request_test_lesson_time_info" => $stu_request_test_lesson_time_info,
            ]
        );

        if ($revisite_info) {
            $ret_update = $this->t_book_revisit->add_book_revisit($this->get_in_phone_ex(), $revisite_info, $this->get_account());
            if ($ret_update === false) {
                return $this->output_err("系统错误");
            }
        }

        if ($status == E\Ebook_status::V_14) { //驳回
            if (\App\Helper\Utils::check_env_is_release()) {
                $email= "812179469@qq.com";
                //$email= "329732001@qq.com";
                dispatch(
                    new \App\Jobs\SendEmail(
                        $email,
                        "有驳回：$phone",
                        "电话：$phone <br/>"
                    )
                );
            }
        }

        $this->t_seller_student_info->set_first_revisite_time($phone);

        $this->t_seller_student_info->set_revisit_info($phone, $status, $user_desc, $revisite_info);

        return $this->output_succ();
    }

    public function get_user_info_fix()
    {
        $phone   = $this->get_in_phone();
        $userid  = $this->get_in_userid();
        $seller  = $this->t_seller_student_info->field_get_list($phone, "*");
        $student = $this->t_student_info->field_get_list($userid, "*");
        if ($student["nick"]>0) {
            $stu_nick=$seller["nick"];
        } else {
            $stu_nick=$student["nick"];
        }
        $ret["stu_nick"]  = $stu_nick;
        $ret["par_nick"]  = $student["parent_name"];
        $ret["gender"]    = $student["gender"];
        $ret["subject"]   = $seller["subject"];
        $ret["has_pad"]   = $seller["has_pad"];
        $ret["status"]    = $seller["status"];
        $ret["grade"]     = $student["grade"];
        $ret["user_desc"] = $seller["user_desc"];
        $ret["origin"]    = $seller["origin"];
        $ret["editionid"] = $student["editionid"];
        $ret["school"]    = $student["school"];
        $ret["next_revisit_time"]=\App\Helper\Utils::unixtime2date(
            $seller["next_revisit_time"],
            'Y-m-d H:i'
        );
        if (!$ret["school"]) {
            $ret["school"]=$seller["st_from_school"];
        }

        if ($student["address"]) {
            $ret["address"]=$student["address"];
        } else {
            $ret["address"]=substr($seller["phone_location"], 0, -6);
        }

        $ret["stu_score_info"]    = $seller["stu_score_info"];
        $ret["stu_test_lesson_level"]    = $seller["stu_test_lesson_level"];
        $ret["stu_character_info"]    = $seller["stu_character_info"];
        $ret["stu_request_test_lesson_time_info"]    = $seller["stu_request_test_lesson_time_info"];
        $ret["stu_request_lesson_time_info"]    = $seller["stu_request_lesson_time_info"];
        $ret["stu_test_ipad_flag"]    = $seller["stu_test_ipad_flag"];
        $ret["st_class_time"]    = \App\Helper\Utils::unixtime2date($seller["st_class_time"], 'Y-m-d H:i');
        $ret["st_demand"]    = $seller["st_demand"];

        return outputjson_success(["data"=> $ret]);
    }

    public function test_lesson_list()
    {
        list($start_time,$end_time, $opt_date_str)=$this->get_in_date_range(
            -7, 0, 1, [
                1 => array("st_application_time","申请时间"),
                2 => array("st_class_time","期待试听时间"),
                3 => array("cancel_lesson_start","取消的上课时间"),
                4 => array("lesson_start","上课时间"),
            ]
        );
        $origin              = trim($this->get_in_str_val('origin', ''));
        $grade               = $this->get_in_grade();
        $subject             = $this->get_in_subject();
        $phone               = trim($this->get_in_str_val('phone', ''));
        $st_application_nick = $this->get_in_str_val('st_application_nick', "");
        $status              = $this->get_in_int_val('status', -3);
        $from_type           = $this->get_in_int_val('from_type', -1);
        $st_arrange_lessonid = $this->get_in_int_val('st_arrange_lessonid', -1);
        $page_num            = $this->get_in_page_num();
        $origin_ex           = $this->get_in_str_val("origin_ex");
        $userid              = $this->get_in_userid(-1);
        $teacherid           = $this->get_in_teacherid(-1);
        $confirm_flag        = $this->get_in_int_val("confirm_flag",-1);
        $require_user_type   = $this->get_in_int_val("require_user_type",-1);

        $test_lesson_cancel_flag = $this->get_in_int_val("test_lesson_cancel_flag", -1, E\Etest_lesson_cancel_flag::class );
        $ass_adminid_flag        = $this->get_in_int_val("ass_adminid_flag",-1,E\Eboolean::class);

        $id_start = ($page_num["page_num"]-1)*10;
        $time     = strtotime("2016-10-15");

        $ret_info = $this->t_seller_student_info->test_lesson_get_list(
            $opt_date_str, $st_application_nick, $status, $phone, $origin,
            $start_time, $end_time, $grade, $subject, $page_num,
            $from_type, $origin_ex, $st_arrange_lessonid, $userid, $teacherid,
            $confirm_flag,$require_user_type,$ass_adminid_flag, $test_lesson_cancel_flag);

        foreach($ret_info["list"] as $id => &$item){
            $stu_time=$item['st_application_time'];
            if ($item["userid"] && $item["userid"] != $item ["st_userid"]){
                $this->t_seller_student_info->field_update_list( $item["phone"], [
                    "userid" => $item["userid"]
                ]);
            }

            \App\Helper\Utils::unixtime2date_for_item($item, "st_class_time");
            \App\Helper\Utils::unixtime2date_for_item($item, "st_application_time");
            \App\Helper\Utils::unixtime2date_for_item($item, "cancel_time");
            \App\Helper\Utils::unixtime2date_for_item($item, "cancel_lesson_start");
            E\Egrade::set_item_value_str($item);
            E\Eregion_version::set_item_value_str($item, "editionid");
            E\Etest_lesson_cancel_flag::set_item_value_str($item, "cancel_flag");
            E\Esubject::set_item_value_str($item);
            E\Epad_type::set_item_value_str($item, "has_pad");
            E\Ebook_status::set_item_value_str($item, "status");
            E\Etest_listen_from_type::set_item_value_str($item, "from_type");
            E\Etest_lesson_level::set_item_value_str($item, "stu_test_lesson_level");
            E\Eboolean::set_item_value_str($item, "stu_test_ipad_flag");
            E\Econfirm_flag::set_item_value_str($item);

            $this->cache_set_item_account_nick($item,"cancel_adminid","cancel_admin_nick" );
            $this->cache_set_item_teacher_nick($item, "assigned_teacherid", "assigned_teacher_nick");
            $this->cache_set_item_teacher_nick($item, "cancel_teacherid", "cancel_teacher_nick");
            $item["lesson_time"]=\App\Helper\Utils::fmt_lesson_time($item["lesson_start"], $item["lesson_end"]);
            $item["teacher_nick"]=$this->cache_get_teacher_nick($item["teacherid"]);

            $stu_request_lesson_time_info=\App\Helper\Utils::json_decode_as_array($item["stu_request_lesson_time_info"], true);
            $str_arr=[];
            foreach ($stu_request_lesson_time_info as $p_item) {
                $str_arr[]=E\Eweek::get_desc($p_item["week"])." " . date('H:i', @$p_item["start_time"]) . date('~H:i', $p_item["end_time"]);
            }
            $item["stu_request_lesson_time_info_str"]= join("<br/>", $str_arr);

            $stu_request_test_lesson_time_info=\App\Helper\Utils::json_decode_as_array($item["stu_request_test_lesson_time_info"], true);
            $str_arr=[];
            foreach ($stu_request_test_lesson_time_info as $p_item) {
                $str_arr[]= \App\Helper\Utils::fmt_lesson_time(@$p_item["start_time"], $p_item["end_time"]);
            }
            $item["stu_request_test_lesson_time_info_str"]= join("<br/>", $str_arr);

            if ($item["st_test_paper"]=="") {
                $item["st_test_paper_str"]="无";
            } else {
                $download_time = $item['tea_download_paper_time'];
                if($download_time>0){
                    $item['st_test_paper_str'] = "老师已下载".date("Y-m-d H:i",$download_time);
                }else{
                    $item['st_test_paper_str'] =" 老师未下载";
                }
                if($stu_time<$time){
                    $item['st_test_paper_str'] = "数据无法追溯";
                }
            }

            $item["admin_revisiterid_nick"] = $this->cache_get_account_nick($item["admin_revisiterid"]);
            $item['parent_nick']  = "" ;
            $item['parent_phone'] = $item["phone"] ;
            $item['address']      = "address" ;
            $item['id']           = $id_start+$id+1;
        }
        return $this->pageView(__METHOD__, $ret_info);
    }

    public function test_lesson_list_ass()
    {
        $this->set_in_value("st_application_nick",$this->get_account() );
        return $this->test_lesson_list();
    }

    public function test_lesson_log_list()
    {
        $userid             = $this->get_in_userid(-1);
        $teacherid          = $this->get_in_teacherid(-1);
        $del_flag = $this->get_in_int_val("del_flag", 0);
        $phone = trim($this->get_in_phone());
        $subject            = $this->get_in_subject(-1);
        $st_application_id  = $this->get_in_int_val("st_application_id", -1);
        $test_lesson_status = $this->get_in_int_val("test_lesson_status", -1);


        list($start_time,$end_time, $date_type_str)= $this->get_in_date_range(
            -7, 0, 1, [
            1 => array( "log_time", "记录时间"),
            2 => array("lesson_start","课程时间"),
            ]
        );

        $page_num           = $this->get_in_page_num();
        $ret_list           = $this->t_test_lesson_log_list->get_log_list($page_num, $userid, $start_time, $end_time, $teacherid, $st_application_id, $subject, $phone, $test_lesson_status, $del_flag, $date_type_str);

        $start_index=\App\Helper\Utils::get_start_index($page_num,10);

        foreach ($ret_list["list"] as $id => &$item) {
            $item["index"]=$start_index+$id;
            \App\Helper\Utils::unixtime2date_for_item($item, "st_class_time");
            \App\Helper\Utils::unixtime2date_for_item($item, "log_time");
            E\Esubject::set_item_value_str($item);
            E\Etest_lesson_status::set_item_value_str($item);
            $this->cache_set_item_student_nick($item);
            $this->cache_set_item_teacher_nick($item, "teacherid");
            $this->cache_set_item_account_nick($item, "test_lesson_bind_adminid", "test_lesson_bind_admin_nick");
            $this->cache_set_item_account_nick($item, "st_application_id", "st_application_nick");
            $item["lesson_time"]=\App\Helper\Utils::fmt_lesson_time($item["lesson_start"], $item["lesson_end"]);
        }

        return $this->Pageview(__METHOD__, $ret_list);
    }

    public function set_test_lesson_st_arrange_lessonid()
    {
        $phone=$this->get_in_phone();
        $st_arrange_lessonid =$this->get_in_int_val("st_arrange_lessonid", 0);
        $test_lesson_bind_adminid=$this->get_account_id();

        $test_lesson_info=$this->t_lesson_info->field_get_list($st_arrange_lessonid, "*");

        if ($test_lesson_info["lesson_type"] != E\Econtract_type::V_2) {
            return $this->output_err("要试听课才行!". $test_lesson_info["lesson_type"] ."!");
        }

        $seller_student_info=$this->t_seller_student_info->field_get_list($phone, "*");
        if ($seller_student_info["subject"] != $test_lesson_info["subject"]) {
            return $this->output_err("科目不对!,请到排课中修改科目");
        }

        if ($seller_student_info["grade"] != $test_lesson_info["grade"]) {
            return $this->output_err("年级不对!,请到排课中修改年级");
        }
        if ( $seller_student_info["cancel_lesson_start"] && $seller_student_info["cancel_lesson_start"] <=time(NULL)) {
            return $this->output_err("已经排过了,而且已上课,不能重新绑定");
        }

        if ($seller_student_info["status"]==15) {
            return $this->output_err("课程取消,不能重新绑定");
        }

        if($this->t_test_lesson_log_list->check_test_lesson_existed(
            $test_lesson_info["userid"],
            $test_lesson_info["teacherid"],
            $test_lesson_info["subject"],
            $test_lesson_info["lesson_start"])) {
            return $this->output_err("请修改时间,之前已经有同一时间的课程了, 或者已经绑定了");
        }

        $row=$this->t_lesson_info->field_get_list($st_arrange_lessonid, "lesson_start,lesson_end,teacherid");

        $this->t_seller_student_info->reset_status_and_log($phone, E\Ebook_status::V_TEST_LESSON_SET_LESSON, $this->get_account());
        $this->t_seller_student_info->field_update_list(
            $phone, [
            \App\Models\t_seller_student_info::C_st_arrange_lessonid=> $st_arrange_lessonid,
            "test_lesson_bind_adminid" => $test_lesson_bind_adminid,
            "cancel_lesson_start"      => $row["lesson_start"],
            "cancel_teacherid"         => $row["teacherid"],
            ]);

        $origin=$this->t_seller_student_info->get_origin($phone);
        $this->t_lesson_info->field_update_list(
            $st_arrange_lessonid, [
            "origin" => $origin
            ]
        );
        //log
        $this->t_test_lesson_log_list->add_log($phone);

        {

            //发信息给申请人
            $st_application_nick=$this->t_seller_student_info->get_st_application_nick($phone);

            $nick=$this->t_seller_student_info->get_nick($phone);
            $admin_info=$this->t_manager_info-> get_info_by_account($st_application_nick);
        if ($admin_info) {
            if ($row) {
                $tea_nick=$this->cache_get_teacher_nick($row["teacherid"]);
                $lesson_time=\App\Helper\Utils::fmt_lesson_time(
                    $row["lesson_start"],
                    $row["lesson_end"]
                );
                $this->t_manager_info->send_wx_todo_msg($st_application_nick,"来自:". $this->get_account()
                                                        ,"排课完成[$phone][$nick]","老师：$tea_nick \n  时间：$lesson_time ","");


            }

        }

        }


        return $this->output_succ();
    }

    public function set_test_lesson_st_test_paper()
    {
        $phone=$this->get_in_phone();
        $st_test_paper =$this->get_in_str_val("st_test_paper", "");
        $this->t_seller_student_info->field_update_list(
            $phone, [
            \App\Models\t_seller_student_info::C_st_test_paper=> $st_test_paper
            ]
        );
        return outputjson_success();
    }


    public function add_test_lesson_user()
    {
        $phone=$this->get_in_phone();
        $tmp=$phone;
        $count=100;
        if ($this->t_seller_student_info->check_phone_existed($phone)) {
            $i=1;
            do {
                $phone=$tmp. "-".$i;
                $i++;
                if ($i>$count) {
                    return $this->output_err("ERROR");
                }
            } while ($this->t_seller_student_info->check_phone_existed($phone));

        }

        $this->t_seller_student_info->add_test_user($this->get_account(), $phone);
        return outputjson_success();
    }


    public function add_origin_key()
    {
        $key0 = trim($this->get_in_str_val('key0', ''));
        $key1 = trim($this->get_in_str_val('key1', ''));
        $key2 = trim($this->get_in_str_val('key2', ''));
        $key3 = trim($this->get_in_str_val('key3', ''));
        $key4 = trim($this->get_in_str_val('key4', ''));
        $value = trim($this->get_in_str_val('value', ''));
        $origin_level = $this->get_in_int_val('origin_level');
        $create_time = time();

        $this->t_origin_key->add_origin_key($key1, $key2, $key3, $key4, $value,$origin_level,$create_time,$key0);

        return outputjson_success();
    }

    public function edit_origin_key()
    {
        $key0 = trim($this->get_in_str_val('key0', ''));
        $key1 = trim($this->get_in_str_val('key1', ''));
        $key2 = trim($this->get_in_str_val('key2', ''));
        $key3 = trim($this->get_in_str_val('key3', ''));
        $key4 = trim($this->get_in_str_val('key4', ''));
        $value = trim($this->get_in_str_val('value', ''));
        $old_value = trim($this->get_in_str_val('old_value', ''));
        $origin_level = $this->get_in_int_val('origin_level');

        $db_value= $this->t_origin_key-> get_origin_key_value ($key1, $key2,$key3,$key4,$key0 );
        if ($db_value !=  $old_value ) {
            return $this->output_err("$key0,$key1, $key2,$key3,$key4 => 已经绑定到 $db_value, 换个key4试试");
        }


        $ret_info=$this->t_origin_key->edit_origin_key($old_value, $key1, $key2, $key3, $key4, $value,$origin_level,$key0);

        return $this->output_succ();

    }

    public function edit_origin_level_by_batch()
    {
        $key1 = trim($this->get_in_str_val('key1', ''));
        $key2 = trim($this->get_in_str_val('key2', ''));
        $key3 = trim($this->get_in_str_val('key3', ''));
        $key4 = trim($this->get_in_str_val('key4', ''));
        $value  = trim( $this->get_in_str_val('value') );
        $origin_level = $this->get_in_int_val('origin_level');


        $ret_info=$this->t_origin_key->edit_origin_level_batch( $key1, $key2, $key3, $key4, $value, $origin_level);

        return $this->output_succ();

    }




    public function get_origin_key()
    {
        $value = $this->get_in_str_val('value', '');

        $row=$this->t_origin_key->field_get_list($value, "*");

        return outputjson_success(["data"=> $row  ]);
    }

    public function delete_origin_key()
    {
        $value = $this->get_in_str_val('value', '');

        $this->t_origin_key->delete_origin_key($value);

        return outputjson_success();
    }
    public function get_key1()
    {

        $page_num   = $this->get_in_page_num();
        $ret_list   = array();

        $ret_info=$this->t_origin_key->get_key1_info($page_num);

        return outputjson_success(array('data' => $ret_list ));


    }


    public function get_seller()
    {
        $telphone = $this->get_in_str_val('telphone');

        $ret_info= $this->t_seller_student_info->get_seller_info($telphone);

        return outputjson_success(array('data' => $ret_info));
    }


    public function register_appstore()
    {
        $telphone = $this->get_in_str_val('telphone');
        $origin   = $this->get_in_str_val('origin');
        $seller   = $this->get_in_int_val('seller');

        $userid = $this->t_student_info->get_userid_by_appstore($telphone);

        $this->t_student_info->update_originid_app($userid, $origin, $seller);

        return outputjson_success(array('data' => $userid));
    }

    public function test_lesson_assign_teacher()
    {
        $seller_student_id=$this->get_in_str_val("seller_student_id");
        $page_num=$this->get_in_page_num();
        $seller_student_info=$this->t_seller_student_info->get_test_lesson_info($seller_student_id);



        if (!$seller_student_info) {
            return $this->error_view(
                [
                " 没有试听信息 ",
                " 请从[试听管理] 点击\"派单\"进来 ",
                ]
            );
        } else {

            $this->cache_set_item_teacher_nick($seller_student_info, "assigned_teacherid", "assigned_teacher_nick");
            E\Egrade::set_item_value_str($seller_student_info);
            E\Esubject::set_item_value_str($seller_student_info);
            \App\Helper\Utils::unixtime2date_for_item($seller_student_info, "st_class_time", "", "Y-m-d H:i");
            //$ret_info=$this->t_test_lesson_assign_teacher->get_list_by_seller_student_id($page_num,$seller_student_id);
            $this->t_teacher_closest-> gen_top_to_test_lesson(
                $seller_student_info["grade"],
                $seller_student_info["subject"],
                $seller_student_id
            );

            //if (count($ret_info["list"]) ==0 ) {
                //得到
            $ret_info=$this->t_test_lesson_assign_teacher->get_list_by_seller_student_id($page_num, $seller_student_id);
            //}

            foreach ($ret_info["list"] as &$item) {
                $this->cache_set_item_teacher_nick($item);
                $this->cache_set_item_account_nick($item, "assign_adminid", "assign_admin_nick");
                \App\Helper\Utils::unixtime2date_for_item($item, "assign_time");
                \App\Helper\Utils::unixtime2date_for_item($item, "teacher_confirm_time");
                E\Eset_boolean::set_item_value_simple_str($item, "teacher_confirm_flag");
                E\Edegree::set_item_value_str($item);
                E\Eboolean::set_item_value_simple_str($item, "has_openid");
            }

            return $this->pageView(__METHOD__, $ret_info, ["seller_student_info"=>$seller_student_info]);
        }


    }
    public function wx_assign_teacher()
    {
        $id                = $this->get_in_id();
        $seller_student_id = $this->get_in_int_val("seller_student_id");
        $openid           = $this->get_in_str_val("openid");

        //\App\Helper\Wxjssdk::

        //$openid="o97Q8vxpdbvMOslCsDA5jiTptSRo";
        $template_id="GIbPl4eva3JeMTUVcgRyYE0AuujsZFJDWm4yGpIR8t0";

        $host=@$_SERVER["HTTP_HOST" ];
        $url="http://{$host}/wx_teacher_info/confirm_test_lesson?seller_student_id=$seller_student_id" ;
        $data=[
            "SSS"  => [
                "value" => "人民币1333.00元",
                "color" => "#173177",
            ]  ,
            "a"  => [
                "value" => "dfa",
                "color" => "#173177",
            ]  ,

        ];
        $str=\App\Helper\Wxjssdk::gen_temp_data($openid, $template_id, $url, $data);
        \App\Helper\Utils::logger("DATA:$str");
        \App\Helper\Utils::logger("URL:$url");
        $token=\App\Helper\Utils::wx_get_token();

        $url="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=$token";
        $ret=\App\Helper\Utils::json_decode_as_array(\App\Helper\Common::http_post_json_str($url, $str));
        if ($ret["errcode"]==0) {
            //设置
            $this->t_test_lesson_assign_teacher->field_update_list(
                $id, [
                "assign_time"   => time(null),
                "assign_adminid"   => $this->get_account_id(),
                ]
            );
            return $this->output_succ();

        } else {
            return $this->output_err("发送失败:".json_encode($ret));
        }

    }
    public function test_lesson_log_set_status()
    {
        $id=$this->get_in_id();
        $test_lesson_status=$this->get_in_int_val("test_lesson_status");
        $reason=$this->get_in_str_val("reason");
        $del_flag=$this->get_in_int_val("del_flag");
        $this->t_test_lesson_log_list->field_update_list(
            $id, [
            "test_lesson_status" => $test_lesson_status,
            "reason" => $reason,
            "del_flag" => $del_flag,
            ]
        );
        return $this->output_succ();
    }
    public function set_phone_userid()
    {
        $userid=$this->get_in_userid(0);
        $phone=$this->get_in_phone();
        $this->t_seller_student_info->field_update_list(
            $phone, [
            "userid" => $userid
            ]
        );
        return $this->output_succ();
    }
    public function get_stu_performance_for_seller(){
        $lessonid=$this->get_in_int_val("lessonid");

        $require_id = $this->t_test_lesson_subject_sub_list->get_require_id($lessonid);
        $stu_info   = $this->t_test_lesson_subject_require->get_stu_performance_for_seller($require_id);
        if(empty($stu_info)){
            $stu_info=$this->t_seller_student_info->get_stu_performance_for_seller($lessonid);
        }

        return outputjson_success(array("data"=>$stu_info));
    }



    public function no_called_list()
    {
        dd("不再使用");
        //list($start_time,$end_time)= $this->get_in_date_range(-7,0 );
        $page_num   = $this->get_in_page_num();

        $grade=$this->get_in_grade(-1);
        $has_pad=$this->get_in_has_pad(-1);
        $subject=$this->get_in_subject(-1);
        $origin=trim($this->get_in_str_val("origin",""));
        $ret_info= $this->t_seller_student_info->get_no_called_list($page_num, $this->get_account_id(), $grade, $has_pad, $subject,$origin );
        foreach ($ret_info["list"] as &$item) {
            \App\Helper\Utils::unixtime2date_for_item($item, "add_time");
            E\Epad_type::set_item_value_str($item, "has_pad");
            E\Esubject::set_item_value_str($item);
            E\Egrade::set_item_value_str($item);
            \App\Helper\Utils::hide_item_phone($item);
        }
        return $this->pageView(__METHOD__, $ret_info);

    }

    public function set_test_lesson_user_to_self(){
        $week_info=\App\Helper\Utils::get_week_range(time(NULL),1);
        $week_start=$week_info["sdate"];
        $adminid= $this->get_account_id();
        $json_ret=\App\Helper\Common::redis_get_json("SELLER_TEST_LESSON_USER_$adminid");
        $seller_level=  $this->t_manager_info->get_seller_level( $adminid);
        $seller_level_config=\App\Helper\Config::get_seller_test_lesson_user_week_limit();
        $level_limit_count= @$seller_level_config[$seller_level];
        if($seller_level == 0){
            return $this->output_err(-1,["info"=>"请先设置咨询师等级"]);
        }
        if($json_ret['opt_count'] >= $level_limit_count){
            return $this->output_err(-1,["info"=>"对不起,您本周的名额已达上限"]);
        }
        $userid=$this->get_in_userid();
        $info=$this->t_student_info->field_get_list($userid,"*");
        $nick=$info["nick"];
        $phone= $info["phone"];
        $grade=$info["grade"];
        $origin="试听未签";
        $subject=0;
        $has_pad=0;
        $trial_type=0;
        $qq="";
        $user_desc="";
        $add_time=time(NULL);
        $add_to_main_flag=true;
        $admin_revisiterid= $this->get_account_id();
        $st_application_time="";
        $st_application_nick="";
        $st_demand="";
        $status=0;
        $ass_adminid=0;
        $seller_resource_type=2;

        $this->t_seller_student_info->add_or_add_to_sub($nick,$phone,$grade,$origin,$subject,$has_pad,$trial_type,$qq,$user_desc,$add_time,$add_to_main_flag,$admin_revisiterid,$st_application_time,$st_application_nick,$st_demand,$status,$ass_adminid,$seller_resource_type);
        $this->t_student_info->field_update_list($userid,[
            "last_revisit_adminid"    => $this->get_account_id(),
            "last_revisit_admin_time" => time(NULL),
        ]);
        $json_ret["opt_count"] += 1;
        \App\Helper\Common::redis_set_json("SELLER_TEST_LESSON_USER_$adminid", $json_ret);
        return $this->output_succ();
    }


    public function set_no_called_to_self()  {
        $phone =$this->get_in_phone();

        $no_call_count=$this->t_seller_student_info->get_no_call_count($this->get_account_id());

        if($no_call_count>=50) {
            return $this->output_err("你已经有50个未回访的用户了,先去回访吧:<");
        }

        $admin_assign_time= $this->t_seller_student_info->get_admin_assign_time($phone);
        $status= $this->t_seller_student_info->get_status($phone);
        $now=time(NULL);
        if ($admin_assign_time > $now-4*86400
        ) { //
            return $this->output_err("已经被抢了");
        }

        $this->t_seller_student_info->field_update_list($phone,[
            "admin_revisiterid" => $this->get_account_id() ,
            "admin_assign_time" => $now,
            "seller_resource_type" => E\Eseller_resource_type::V_1,
            "status" =>  E\Ebook_status::V_0,
        ]);

        $account= $this->get_account();
        $ret_update = $this->t_book_revisit->add_book_revisit(
            $this->get_in_phone_ex(),
            sprintf(
                "操作者: $account 抢单: 电话[$phone] "
            ),
            "system"
        );

        return $this->output_succ();

    }

    public function test_lesson_no_order_list() {

        $end_time              = time(NULL)-60*86400;
        $start_time            = $end_time-240*86400;
        $page_num              = $this->get_in_page_num();
        $grade                 = $this->get_in_grade(-1);
        $can_reset_seller_flag = 1;

        $adminid= $this->get_account_id();

        $week_info=\App\Helper\Utils::get_week_range(time(NULL),1);
        $week_start=$week_info["sdate"];

        $json_ret=\App\Helper\Common::redis_get_json("SELLER_TEST_LESSON_USER_$adminid");

        if (!$json_ret || $json_ret["opt_date"] != $week_start ) {
            $json_ret=[
                "opt_date" =>  $week_start,
                "opt_count" => 0,
            ];
            \App\Helper\Common::redis_set_json("SELLER_TEST_LESSON_USER_$adminid", $json_ret);
        }



        $seller_level=  $this->t_manager_info->get_seller_level( $adminid);
        $seller_level_str=E\Eseller_level::get_desc($seller_level);
        $seller_level_config=\App\Helper\Config::get_seller_test_lesson_user_week_limit();
        $level_limit_count= @$seller_level_config[$seller_level];
        $last_count = $level_limit_count - $json_ret['opt_count'];

        $ret_info= $this->t_student_info->get_test_lesson_lost_user_list( $page_num,$grade,$start_time,$end_time, $can_reset_seller_flag,false);

        foreach($ret_info['list'] as &$item) {
            \App\Helper\Utils::unixtime2date_for_item($item,"lesson_start");
            E\Egrade::set_item_value_str($item);
            E\Egender::set_item_value_str($item);
            \App\Helper\Utils::hide_item_phone($item);
        }

        return $this->pageView(__METHOD__, $ret_info, [
            "seller_level_str"  => $seller_level_str,
            "opt_count"  => $json_ret['opt_count'],
            "last_count"  => $last_count
        ]);

    }

    public function test_lesson_no_binding_list() { //
        list($start_time,$end_time)= $this->get_in_date_range(
            0, 0, 0, [], 1);
        $page_num= $this->get_in_page_num();
        $ret_info=$this->t_lesson_info -> get_no_binding_test_lesson_list ( $page_num, $start_time,$end_time);
        foreach ($ret_info["list"] as &$item )  {
            $this->cache_set_item_student_nick($item);
            $this->cache_set_item_teacher_nick($item);
            $item["lesson_time"] =\App\Helper\Utils::fmt_lesson_time($item["lesson_start"],
                                                                     $item["lesson_end"]);
        }

        return $this->pageView(__METHOD__, $ret_info);

    }


    //转介绍
    public function ass_add_student_list ()
    {
        list($start_time,$end_time) = $this->get_in_date_range(-90,0);

        $ass_adminid = $this->get_in_int_val("ass_adminid", -1);
        $page_num    = $this->get_in_page_num();

        $ret_info=$this->t_seller_student_info->ass_get_list( $page_num ,$start_time,$end_time ,$ass_adminid );

        foreach ($ret_info["list"] as &$item) {
            \App\Helper\Utils::unixtime2date_for_item($item, "add_time");
            E\Egrade::set_item_value_str($item);
            E\Esubject::set_item_value_str($item);
            E\Ebook_status::set_item_value_str($item, "status");
            $this->cache_set_item_account_nick($item,"ass_adminid","ass_admin_nick");
            $this->cache_set_item_student_nick($item,"origin_userid","origin_user_nick");
            $this->cache_set_item_account_nick($item, "admin_revisiterid", "admin_revisiterid_nick" );
        }
        return $this->pageView(__METHOD__, $ret_info);
    }

    public function ass_add_student_list_ass () {
        $this->set_in_value("ass_adminid", $this->get_account_id() );
        return $this->ass_add_student_list();
    }

    public function ass_add_student_list_seller () {
        $this->set_in_value("ass_adminid", $this->get_account_id() );
        return $this->ass_add_student_list();
    }

    public function get_teacher_for_seller_student(){
        $phone = $this->get_in_str_val("phone");

        $ret_info = $this->t_seller_student_info->get_seller_student_info($phone);
        $stu_time = $ret_info['st_class_time'];
        if(!$stu_time){
            return $this->output_err(-1,["info"=>"该学生没有期待试听时间!"]);
        }

        $teacher_list = $this->t_teacher_closest->get_test_lesson_teacher_list($ret_info['subject'],$ret_info['grade']);
        if(!is_array($teacher_list)){
            return $this->output_err(-1,["info"=>"没有该年级,学科的老师!"]);
        }

        $day        = date("Y-m-d",$stu_time);
        $start_hour = date("H",$stu_time);
        $end_hour   = date("H",$stu_time+2400);

        $ret_info['stu_time']       = $day;
        $ret_info['stu_class_time'] = $stu_time;
        $ret_info['start_time']     = date("H:i",$stu_time);
        $ret_info['end_time']       = date("H:i",$stu_time+2400);

        $check_teacher_list = $this->check_teacher_list($teacher_list,$day,$start_hour,$end_hour);
        return $this->output_succ(["data"=>$check_teacher_list,"stu_info"=>$ret_info]);
    }

    /**
     * @param teacher_list array 符合年级和科目的想上试听课的老师列表
     * @param day 学生期待试听的日期
     * @param start_hour 学生期待试听的课堂开始时间
     * @param end_hour 学生期待试听的课堂结束时间(试听课40分钟)
     * @return array
     */
    private function check_teacher_list($teacher_list,$day,$start_hour,$end_hour){
        $free_time_list     = array();
        $check_teacher_list = array();
        $flag_list          = array();

        //check_flag 0 不匹配 1部分匹配 2完全匹配
        foreach($teacher_list as $val){
            $check_flag     = 0;
            $free_time_list = json_decode($val['free_time_new']);
            if(is_array($free_time_list)){
                foreach($free_time_list as $v){
                    $date_time = explode(" ",$v[0]);
                    if($day==$date_time[0]){
                        $tea_hour = explode(":",$date_time[1]);
                        if($end_hour<$tea_hour[0]){
                            break;
                        }
                        if($check_flag==0){
                            if($start_hour==$tea_hour[0]){
                                $check_flag=1;
                            }
                            if($end_hour==$tea_hour[0]){
                                $check_flag=2;
                                break;
                            }
                        }elseif($check_flag==1){
                            if($end_hour==$tea_hour[0]){
                                $check_flag=2;
                                break;
                            }
                        }
                    }
                }
            }

            if($check_flag>0){
                $teacher_info['teacherid']  = $val['teacherid'];
                $teacher_info['tea_nick']  = $this->cache_get_teacher_nick($val['teacherid']);
                $teacher_info['check_time'] = $check_flag==1?"时间部分匹配":"时间匹配";
                $teacher_info['phone']      = $val['phone'];

                $check_teacher_list[] = $teacher_info;
                $flag_list[]          = $check_flag;
            }
        }
        if(is_array($flag_list)){
            array_multisort($flag_list,SORT_DESC,$check_teacher_list);
        }

        return $check_teacher_list;
    }

    public function add_test_lesson(){
        $phone        = $this->get_in_str_val("phone");
        $grade        = $this->get_in_int_val("grade");
        $subject      = $this->get_in_int_val("subject");
        $stu_time     = $this->get_in_str_val("stu_time");
        $lesson_start = $this->get_in_str_val("lesson_start");
        $lesson_end   = $this->get_in_str_val("lesson_end");
        $teacherid    = $this->get_in_int_val("teacherid");

        if($lesson_start=='' || $lesson_end ==''){
            return $this->output_err("课程时间不能为空!");
        }
        $start_time = strtotime($stu_time.$lesson_start);
        $end_time   = strtotime($stu_time.$lesson_end);

        $ret_row=$this->t_lesson_info->check_teacher_time_free($teacherid,0,$start_time,$end_time);
        if($ret_row){
            $error_lessonid=$ret_row["lessonid"];
            return $this->output_err("<div>有现存的老师课程与该课程时间冲突！"
                                     ."<a href='/teacher_info/get_lesson_list?"
                                     ."teacherid=$teacherid&lessonid=$error_lessonid' target='_blank'>"
                                     ."查看[lessonid=$error_lessonid]<a/><div> "
            );
        }

        if($start_time<time()){
            return $this->output_err("课程时间出错,请检查学生预约时间");
        }

        $real_phone = explode("-",$phone);
        $userid     = $this->t_student_info->get_userid_by_phone($real_phone[0]);

        if(!$userid){
            return $this->output_err("用户未注册!");
        }

        $teacher_info = $this->t_teacher_info->field_get_list($teacherid,"teacher_money_type,level");
        $lessonid     = $this->t_lesson_info->add_lesson(0,0,$userid,0,2,$teacherid,0,
                                                         $start_time,$end_time,$grade,$subject,100,
                                                         $teacher_info['teacher_money_type'],$teacher_info['level']
        );

        if($lessonid){
            $adminid=$this->get_account_id();
            $this->t_seller_student_info->field_update_list($phone,[
                "st_arrange_lessonid"      => $lessonid,
                "test_lesson_bind_adminid" => $adminid,
                "cancel_lesson_start"      => $start_time,
                "cancel_teacherid"          => $teacherid,
            ]);
            return $this->output_succ();
        }else{
            return $this->output_err("添加失败,请重试");
        }
    }
}