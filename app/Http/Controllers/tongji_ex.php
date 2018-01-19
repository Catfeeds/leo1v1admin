<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use App\Helper\Utils;

use Illuminate\Support\Facades\Input ;
class tongji_ex extends Controller
{
    use  CacheNick;
    public function __construct() {
		parent::__construct();
        $this->switch_tongji_database();
    }

    public function top_list() {

        list($start_time,$end_time)=$this->get_in_date_range_month(date("Y-m-01") );
        $tongji_type=$this->get_in_int_val("tongji_type",1, E\Etongji_type::class);
        $page_num=$this->get_in_page_num();

        $ret_info=$this->t_tongji_seller_top_info->get_list($page_num,$tongji_type,$start_time);
        foreach($ret_info["list"] as &$item) {
            $this->cache_set_item_account_nick($item);
        }
        return $this->pageView(__METHOD__,$ret_info);
    }
    public function call_count() {
        list($start_time,$end_time)=$this->get_in_date_range_month(date("Y-m-01") );
        $sys_invaild_flag= $this->get_in_e_boolean(-1,"sys_invaild_flag");
        $this->switch_tongji_database();
        $ret_info=$this->t_seller_student_new->get_call_info( $start_time, $end_time, $sys_invaild_flag  );
        return $this->pageView(__METHOD__,$ret_info);
    }
    public function test_lesson_order_detail_list() {
        list($start_time,$end_time)=$this->get_in_date_range_month(0 );
        $cur_require_adminid= $this->get_in_int_val("cur_require_adminid", -1 );
        $teacherid= $this->get_in_int_val("teacherid", -1);
        $origin_ex= $this->get_in_str_val("origin_ex", "");
        $page_info= $this->get_in_page_info();

        $ret_info=$this->t_test_lesson_subject_require->test_lesson_order_detail_list($page_info,$start_time,$end_time,$cur_require_adminid,$origin_ex,$teacherid);
        foreach ($ret_info["list"] as &$item ){
            E\Egrade::set_item_value_str($item);
            E\Esubject::set_item_value_str($item);
        }
        return $this->pageView(__METHOD__,$ret_info);
    }

    public function test_lesson_order_info() {
        list($start_time,$end_time)=$this->get_in_date_range_month(0 );
        $cur_require_adminid= $this->get_in_int_val("cur_require_adminid", -1 );
        $teacherid= $this->get_in_int_val("teacherid", -1);
        $origin_ex= $this->get_in_str_val("origin_ex", "");

        $group_by_field= $this->get_in_str_val("group_by_field", "cur_require_adminid");
        $ret_info=$this->t_test_lesson_subject_require->tongji_test_lesson_order($group_by_field,$start_time,$end_time,$cur_require_adminid,$origin_ex,$teacherid);

        $all_item=["title"=> "全部" ];

        foreach($ret_info["list"]  as &$item ) {
            $item["percent"] = intval( $item["order_count"]/ $item["test_lesson_count"] *10000)/100;
            $item["order_money"]/=100;

            $field_value=$item["field_name"];
            switch ( $group_by_field ) {
            case "teacherid" :
                $title=$this->cache_get_teacher_nick($field_value);
                break;
            case "cur_require_adminid" :
                $title=$this->cache_get_account_nick($field_value);
                break;
            default:
                $title=$field_value;
                break;
            }
            $item["title"]= $title;
        }


        return $this->pageView(__METHOD__,$ret_info);
    }

    public function user_login() {
        list($start_time,$end_time)=$this->get_in_date_range_month(0 );
        $ret_info=$this->t_user_login_log->get_login_tongji($start_time,$end_time);
        return $this->pageView(__METHOD__,$ret_info);
    }

    public function user_login_list() {
        $page_info= $this->get_in_page_info();
        list($start_time,$end_time) = $this->get_in_date_range_month(0 );
        $ip                         = trim( $this->get_in_str_val("ip"));
        $userid                     = $this->get_in_userid(-1);
        $ret_info                   = $this->t_user_login_log->get_login_list($page_info, $start_time,$end_time, $userid ,$ip );
        foreach($ret_info["list"]  as &$item ) {
            \App\Helper\Utils::unixtime2date_for_item($item,"login_time");
        }
        /*
        "userid" => "21312"
                 "login_time" => "12412412"
                 "nick" => "2131wo"
                 "ip" => "1231"
        */
        return $this->pageView(__METHOD__,$ret_info);
    }

    public function sys_error_list() {
        list($start_time, $end_time)= $this->get_in_date_range(0,0,0,[],0,0,true);
        $report_error_from_type=$this->get_in_el_report_error_from_type();
        $report_error_from_type=$this->get_in_el_report_error_type();

    }
    public function show_sys_error_info() {
        $id=$this->get_in_id();
        $item= $this->t_sys_error_info->field_get_list($id,"*");
        \App\Helper\Utils::unixtime2date_for_item($item,"add_time");
        E\Ereport_error_from_type::set_item_value_str($item);
        E\Ereport_error_type::set_item_value_str($item);
        return $this->pageView(__METHOD__, null, ["report_info"=> $item]);
    }

    public function get_lesson_user_ip_same_info(){
        list($start_time,$end_time) = $this->get_in_date_range_month(0 );
        $match_type = $this->get_in_int_val("match_type",0);
        $company_ip_list=[
             '123.57.153.80',
             '123.57.153.95',
             '116.226.191.120',
             '101.81.224.61',
             '121.43.230.95',
             '116.226.191.6',
             '222.64.63.129'
        ];
        $str ="";
        foreach($company_ip_list as $val){
            $str  .="'".$val."',";           
        }
        $ip_str = "(".trim($str,",").")";
        $ret_info = $this->t_user_login_log->get_pay_stu_ip_list($start_time,$end_time,$match_type,$ip_str);
        
        $list=[];
        foreach($ret_info as $val){
            $k = $val["userid"]."-".$val["ip"];
            @$list[$k]["userid"]=$val["userid"]; 
            @$list[$k]["nick"]=$val["phone"]; 
            @$list[$k]["ip"]=$val["ip"];
            @$list[$k]["grade"]=$val["grade"];
            @$list[$k]["same_name_list"] .=$val["s2_phone"].",";
        }
        foreach($list as &$item){
            $item["same_name_list"] = trim($item["same_name_list"],",");
            E\Egrade::set_item_value_str($item);
        }
        return $this->pageView(__METHOD__, \App\Helper\Utils::list_to_page_info($list)  );

    }

    public function seller_student_detail(){
        $this->check_and_switch_tongji_domain();
        list($start_time,$end_time)=$this->get_in_date_range_day(0);
        $page_info = $this->get_in_page_info();
        $ret_info = $this->t_seller_student_new->get_master_detail_list($start_time,$end_time,$page_info);
        foreach($ret_info['list'] as &$item){
            \App\Helper\Utils::unixtime2date_for_item($item,"add_time");
            E\Eseller_student_status::set_item_value_color_str($item);
            E\Etq_called_flag::set_item_value_str($item,"global_tq_called_flag");
            $cc_adminid = $item['admin_revisiterid']>0?$item['admin_revisiterid']:$item['competition_call_adminid'];
            $item['cc_nick'] = $this->cache_get_account_nick($cc_adminid);
            $item['first_called_cc'] = $this->cache_get_account_nick($item['first_called_cc']);
            $item['first_get_cc'] = $this->cache_get_account_nick($item['first_get_cc']);
            $item['key0'] = $this->cache_get_origin_key0($item['origin']);
            $item['test_lesson_flag'] = \App\Helper\Common::get_set_boolean_color_str($item['test_lesson_flag']>0?1:2);
            $item["suc_test_flag"] = \App\Helper\Common::get_set_boolean_color_str($item["test_lesson_count"]>0?1:2);
            $item['order_flag'] = \App\Helper\Common::get_set_boolean_color_str($item["orderid"]>0?1:2);
            $item['price'] = $item['price']/100;
        }
        return $this->pageView(__METHOD__,$ret_info);
    }

}
