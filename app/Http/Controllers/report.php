<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class report extends Controller
{
    //@desn:市场渠道周月报
    public function week_of_monthly_report(){
        list($start_time,$end_time) = $this->get_in_date_range(
            $init_start_date=0,$init_end_date=0,$date_type=0,$date_type_config=[],$opt_date_type=2
        );
        $is_history_data = $this->get_in_int_val('history',1);
        $opt_date_type = $this->get_in_int_val("opt_date_type",2);
        $data_arr['create_time_range'] = '不在统计时段内';
        $data_arr['type'] = 1;
        $this->check_and_switch_tongji_domain();
        $this->switch_tongji_database();

        if($is_history_data == 2){
            $data_arr['create_time_range'] = date('Y-m-d',$start_time).'—'.date('Y-m-d',$end_time);

            if($opt_date_type == 2){
                //周报
                $data_arr['type'] = 2;
                $data_arr['create_time_range'] = date('Y-m-d H:i:s',$start_time).'—'.date('Y-m-d H:i:s',$end_time);
            }

            if($opt_date_type == 2 || $opt_date_type == 3)//获取实时周月报时间
                $data_arr = $this->get_week_of_monthly_report($start_time, $end_time,$data_arr);

        }else{
            //存档数据
            if($opt_date_type == 2){
                $start_time = strtotime(' + 1 day',$start_time);
                $end_time = strtotime(' + 1 day',$end_time);
                //周报
                $data_arr['type'] = 2;
                $data_arr['create_time_range'] = date('Y-m-d H:i:s',$start_time).'—'.date('Y-m-d H:i:s',$end_time);
                $example_info = $this->t_week_of_monthly_report->get_example_info($report_type=1,$start_time);

                $data_arr['all_example_info'] = $example_info;

            }else{
                $data_arr['create_time_range'] = date('Y-m-d',$start_time).'—'.date('Y-m-d',$end_time);
                //月报
                $example_info = $this->t_week_of_monthly_report->get_example_info($report_type=2,$start_time);
                // dd($example_info);
                $data_arr['all_example_info'] = $example_info;
            }
        }
        $this->set_filed_for_js('is_history_data', $is_history_data);

        return $this->pageView(__METHOD__,null,[
            "data_arr"=>$data_arr,
            'is_history_data' => $is_history_data
        ]);
    }
    //@desn:获取周月报统计数据
    //@param:$start_time 开始时间
    //@param:$end_time 结束时间
    //@param:$data_arr 初始data_arr 数组
    public function get_week_of_monthly_report($start_time,$end_time,$data_arr=[]){
        //历史数据
        $example_info = $this->t_test_lesson_subject->get_example_info($start_time,$end_time);
        if(@$example_info['called_num'] > 0){
            $example_info['valid_rate'] = number_format(@$example_info['valid_example_num']/@$example_info['called_num']*100, 2);
            $example_info['invalid_rate'] =number_format(@$example_info['invalid_example_num']/@$example_info['called_num']*100, 2);
            $example_info['not_through_rate'] =number_format(@$example_info['not_through_num']/@$example_info['called_num']*100, 2);
            $example_info['high_num_rate'] = number_format(@$example_info['high_num']/@$example_info['example_num']*100, 2);
            $example_info['middle_num_rate'] = number_format(@$example_info['middle_num']/@$example_info['example_num']*100, 2);
            $example_info['primary_num_rate'] = number_format(@$example_info['primary_num']/@$example_info['example_num']*100, 2);
        }
        //获取公开课次数
        $public_class_num = $this->t_lesson_info->get_public_class_num($start_time,$end_time);
        //微信运营信息
        $wx_example_num = $this->t_seller_student_new->get_wx_example_num($start_time,$end_time);
        $wx_order_info = $this->t_order_info->get_wx_order_info($start_time,$end_time);
        //获取所有公众号渠道
        $all_public_number_origin_list = $this->t_origin_key->get_all_public_number_origin();
        $all_public_number_origin_arr = array_column($all_public_number_origin_list, 'value');
        //公众号信息
        $pn_example_num = 0;
        $pn_order_num = 0;
        $pn_order_money = 0;
        $public_number_example_info = $this->t_seller_student_new->get_public_number_example_info($start_time,$end_time);
        $public_number_order_info = $this->t_order_info->get_public_number_order_info($start_time,$end_time);
        foreach($all_public_number_origin_arr as $item){
            $pn_example_num += @$public_number_example_info[$item]['public_number_num'];
            $pn_order_num += @$public_number_order_info[$item]['pn_order_count'];
            $pn_order_money += @$public_number_order_info[$item]['pn_order_all_money'];
        }
        //将统计数据放进数组里
        $data_arr['all_example_info'] = $example_info;
        $data_arr['public_class_num'] = $public_class_num;
        $data_arr['wx_example_num'] = $wx_example_num;
        $data_arr['wx_order_info'] = $wx_order_info;
        $data_arr['pn_example_num'] = $pn_example_num;
        $data_arr['pn_order_num'] = $pn_order_num;
        $data_arr['pn_order_money'] = $pn_order_money;
        return $data_arr;
    }
}
