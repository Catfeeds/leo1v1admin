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

        if($is_history_data == 1){
            //历史数据
            if($opt_date_type == 2){
                //周报
                $example_arr = $this->t_test_lesson_subject->get_example_info($start_time,$end_time);
                foreach($example_arr as &$item){
                    $item['valid_rate'] = number_format($item['valid_example_num']/$item['called_num'], 2);
                    $item['invalid_rate'] = number_format($item['invalid_example_num']/$item['called_num'], 2);
                    $item['not_through_rate'] = number_format($item['not_through_num']/$item['called_num'], 2);
                }
            }else{
                //月报
            }
        }else{
            //即时数据
            if($opt_date_type == 2){
                //周报

            }else{
                //月报
            }
        }

        $header_arr['create_time_range'] ='不在统计时间段内';
        $header_arr['type'] = 0;

        return $this->pageView(__METHOD__,null,["arr"=>$header_arr]);
    }
}
