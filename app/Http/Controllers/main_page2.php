<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

class main_page2 extends Controller
{

    var $switch_tongji_database_flag = true;
    use CacheNick;
    function __construct()  {
        parent::__construct();
    }
    public  function market() {
        list($start_time,$end_time)=$this->get_in_date_range_month(0);
        $role_2_diff_money= $this->t_order_info-> get_spec_diff_money_all( $start_time,$end_time,E\Eaccount_role::V_2 );
        $role_1_diff_money= $this->t_order_info-> get_spec_diff_money_all( $start_time,$end_time,E\Eaccount_role::V_1 );  
        $role_2_diff_money_def= $this->t_config_date->get_config_value(E\Econfig_date_type::V_MONTH_MARKET_SELLER_DIFF_MONEY ,strtotime( date("Y-m-01", $start_time) ));
 
        $role_1_diff_money_def= $this->t_config_date->get_config_value(E\Econfig_date_type::V_MONTH_MARKET_TEACH_ASSISTANT_DIFF_MONEY ,strtotime( date("Y-m-01", $start_time) ));
        //已使用预算
        $used_quota = 0;
        //获取活动明细
        $order_activity_detail = $this->t_activity_quota_detail->get_activity_detail_month($start_time,$end_time);
        foreach($order_activity_detail as &$item){
            $item['used_quota'] = $this->t_order_activity_info->get_used_quota($item['order_activity_desc'],$start_time,$end_time);
            $item['left_quota'] = $item['market_quota']-$item['used_quota'];
            $used_quota += $item['used_quota'];
            $item['market_quota'] /= 100;
            $item['used_quota'] /= 100;
            $item['left_quota'] /= 100;
        }
        //获取合同活动配额总额
        $sum_activity_quota = $this->t_sum_activity_quota->get_market_quota_month($start_time,$end_time);
        //获取已投放预算
        $put_quota = $this->t_activity_quota_detail->get_sum_quota_month($start_time,$end_time);
        $sum_activity_quota_arr = [
            'sum_activity_quota' => $sum_activity_quota/100,
            'put_quota' => $put_quota/100,
            'used_quota' => $used_quota/100,
            'left_quota' => ($sum_activity_quota-$used_quota)/100,
        ];

        $current_month_first = strtotime(date("Y-m-01"));
        $current_month_last = strtotime(date("Y-m-01",strtotime("+1 month")));

        $last_month_first = strtotime(date("Y-m-01",strtotime("-1 month")));       
        $last_month_last  = $current_month_first;

        $history_month_last = strtotime(date("Y-m-t",strtotime("-2 month")));
        //dd(date("Y-m-t",strtotime("-2 month")));

        $contract_status="(1,2,3)";  //合同状态
        $test_user=0;        //是否是测试用户
        $stu_from_type=0;    //签约类型
        $has_money=1;        //是否有钱 

        $where_arr=[
            ["stu_from_type=%u" , $stu_from_type],
            ["o.price>%u" , 0],
            ["contract_status != %u" , 0 ],
            ["is_test_user=%u" , $test_user],
            ["o.order_time>=%d" , $current_month_first ],
            ["o.order_time<%d" , $current_month_last ]
        ];
        //根据4个筛选条件 所有新签合同的数量
        $current_month_num_row = $this->t_order_info->get_cc_order_count($where_arr);
        !empty($current_month_num_row) ? $current_month_num = $current_month_num_row['count_order'] : $current_month_num = 0;        

        $current_month_cc = 0;
        $current_month_rate = '0%';
        $current_arr = $where_arr;
        $current_arr[] =["n.add_time>=%d", $current_month_first];
        $current_arr[] =["n.add_time<%d", $current_month_last];
        $current_month_cc_row = $this->t_order_info->get_cc_order_count($current_arr);
        if( $current_month_num > 0 && $current_month_cc_row ){
            $current_month_cc = $current_month_cc_row['count_order'];
            $current_month_rate = sprintf('%.4f', $current_month_cc/$current_month_all)*100;
            $current_month_rate = $current_month_rate.'%';
        }

        $last_month_cc = 0;
        $last_month_rate = '0%';
        $last_arr = $where_arr;
        $last_arr[] =["n.add_time>=%d", $last_month_first];
        $last_arr[] =["n.add_time<%d", $last_month_last];
        $last_month_cc_row = $this->t_order_info->get_cc_order_count($last_arr);
        if( $current_month_num > 0 && $last_month_cc_row ){
            $last_month_cc = $last_month_cc_row['count_order'];
            $last_month_rate = sprintf('%.4f', $last_month_cc/$current_month_all)*100;
            $last_month_rate = $last_month_rate.'%';
        }
        
     
        $his_month_cc = 0;
        $his_month_rate = '0%';
        $his_arr = $where_arr;
        $his_arr[] =["n.add_time<%d", $history_month_last];
        $his_month_cc_row = $this->t_order_info->get_cc_order_count($his_arr);
        if( $current_month_num > 0 && $his_month_cc_row ){
            $his_month_cc = $his_month_cc_row['count_order'];
            $his_month_rate = sprintf('%.4f', $his_month_cc/$current_month_all)*100;
            $his_month_rate = $his_month_rate.'%';
        }


        //dd(date("Y-m-01 00:00:00",strtotime("-1 month")));
        return $this->pageView(__METHOD__,null,[
            "role_1_diff_money" => $role_1_diff_money ,
            "role_2_diff_money" => $role_2_diff_money ,
            "role_2_diff_money_def" =>  $role_2_diff_money_def,
            "role_1_diff_money_def" =>  $role_1_diff_money_def,
            'sum_order_activity_quota' => $sum_activity_quota_arr,
            'order_activity_detail' => $order_activity_detail,
            'current_month_cc' => $current_month_cc,
            'current_month_rate' => $current_month_rate,
            'last_month_cc' => $last_month_cc,
            'last_month_rate' => $last_month_rate,
            'his_month_cc' => $his_month_cc,
            'his_month_rate' => $his_month_rate,
        ]);
    }
    //@desn:配置合同活动总配额
    public function config_order_sum_activity_quato(){
        $opt_time=$this->get_in_unixtime_from_str("opt_time");
        $market_quota         = $this->get_in_int_val("market_quota");
        $opt_time = strtotime(date("Y-m-01", $opt_time) );
        $market_quota *= 100;


        $this->t_sum_activity_quota->set_config_value($opt_time,$market_quota);

        return $this->output_succ();
    }

    //@desn:配置活动名
    public function config_order_activity_detail(){
        $id = $this->get_in_id();
        $order_activity_desc         = $this->get_in_str_val("order_activity_desc");
        $market_quota = $this->get_in_int_val('market_quota');
        $market_quota *=100;


        $this->t_activity_quota_detail->field_update_list($id,[
            'order_activity_desc' => $order_activity_desc,
            'market_quota' => $market_quota,
        ]);
        return $this->output_succ();
    }
    //@desn:添加活动
    public function add_order_activity(){
        $order_activity_desc         = $this->get_in_str_val("order_activity_desc");
        $market_quota = $this->get_in_int_val('market_quota');
        $market_quota *=100;
        $opt_time=$this->get_in_unixtime_from_str("opt_time");
        $opt_time = strtotime(date("Y-m-01", $opt_time) );
        $this->t_activity_quota_detail->set_config_value($opt_time,$order_activity_desc,$market_quota);
        return $this->output_succ();
    }

}

