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

        //获取2017年11月数据
        $data = $this->t_order_info->get_order_money_user_info(strtotime("2017-11-01"),time());
        $data["month"] = strtotime("2017-11-01");
        $data["month_str"] = "2017年11月";
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







   
}

