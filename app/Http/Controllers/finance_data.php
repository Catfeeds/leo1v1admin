<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Cookie;
use App\Http\Controllers\Controller;
use \App\Libs;
use \App\Config as C;
use \App\Enums as E;
use App\Helper\Utils;

class finance_data  extends Controller
{
    use CacheNick;
    use TeaPower;
   
    public function income_info(){
        $ret_info = $this->t_admin_corporate_income_list->get_all_info();
        foreach($ret_info["list"] as &$item){
            $item["month_str"] = date("Y年m月",$item["month"]);
        }

        //获取当前月数据
        $data = $this->t_order_info->get_order_money_user_info(strtotime(date("Y-m-01",time())),time());
        $data["month"] = strtotime(date("Y-m-01",time()));
        $data["month_str"] = date("Y年m月",$data["month"]);
        $data["new_order_money"]=2*$data["new_order_money"];
        $data["renew_order_money"]=2*$data["renew_order_money"];
        $data["new_order_stu"]=2*$data["new_order_stu"];
        $data["renew_order_stu"]=2*$data["renew_order_stu"];
        $data["new_signature_price"]=$data["new_order_stu"]>0?round( $data["new_order_money"]/$data["new_order_stu"]):0;
        $data["renew_signature_price"]=$data["renew_order_stu"]>0?round( $data["renew_order_money"]/$data["renew_order_stu"]):0;
        array_push($ret_info["list"],$data);
        return $this->pageView(__METHOD__, $ret_info);

    }

    public function refund_order_info(){
        $page_info = $this->get_in_page_info();
        $ret_info = $this->t_admin_refund_order_list->get_all_info($page_info);
        foreach($ret_info["list"] as &$item){
            \App\Helper\Utils::unixtime2date_for_item($item, "order_time","_str","Y-m-d");
            \App\Helper\Utils::unixtime2date_for_item($item, "apply_time","_str");
            \App\Helper\Utils::unixtime2date_for_item($item, "approve_time","_str");
            $item["order_custom_str"] = str_replace(",","<br>",$item["order_custom"]);
        }

        // dd($ret_info);
        return $this->pageView(__METHOD__, $ret_info);

    }

    public function student_data(){
        $ret_info = $this->t_admin_student_month_info->get_all_info();
        foreach($ret_info as &$item){
            $item["month_str"] = date("Y年m月",$item["month"]);
        }
        return $this->pageView(__METHOD__, null,[
            "data" =>$ret_info
        ]);
        //dd($ret_info);

    }

    public function student_type_data(){
        $ret_info = $this->t_admin_student_month_info->get_all_info();
        foreach($ret_info as &$item){
            $item["month_str"] = date("Y年m月",$item["month"]);
        }
        return $this->pageView(__METHOD__, null,[
            "data" =>$ret_info
        ]);
        //dd($ret_info);

    }

    public function test_lesson_origin_tongji(){
        $time  = strtotime("2016-12-01");
        $rr=["公众号"=>1,"信息流"=>2,"BD"=>3,"口碑"=>4,"转介绍"=>5];
        $arr=[];
        for($i=1;$i<13;$i++){
            $month = strtotime("+".$i." months",$time);
            $list =  $this->t_order_student_month_list->get_list_by_month($month);
            foreach($list as $val){
                if(isset($rr[$val["origin"]])){                                    
                    $arr[$month][$rr[$val["origin"]]]=$val;
                }
            }
            if($arr){
                ksort($arr[$month]);
            }
        }
        foreach($arr as $k=>&$item){
            $item["num"] = count($item);
            $item["month_str"] = intval(date("m",$k))."月";
        }
        // dd($arr);
        return $this->pageView(__METHOD__, null,[
            "data" =>$arr
        ]);
    }

    public function money_contract_list () {
        $start_time      = $this->get_in_start_time_from_str(date("Y-m-d",(time(NULL)-86400*7)) );
        $end_time        = $this->get_in_end_time_from_str(date("Y-m-d",(time(NULL)+86400)) );
        $userid_flag     = $this->get_in_int_val("userid_flag",-1);
        $contract_type   = $this->get_in_int_val("contract_type",-2);
        $contract_status = $this->get_in_el_contract_status();

        $config_courseid = -1;
        $is_test_user    =  $this->get_in_int_val("is_test_user", 0 , E\Eboolean::class  );
        $can_period_flag    =  $this->get_in_int_val("can_period_flag",-1);
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

        $ret_list=$this->t_order_info_finance->get_order_list(
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









   
}

