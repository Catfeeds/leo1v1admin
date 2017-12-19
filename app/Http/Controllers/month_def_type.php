<?php

namespace App\Http\Controllers;
use \App\Http\Controllers\Controller;
use \App\Enums as E;
use \App\Helper\Config;

class month_def_type extends Controller
{
    public function def_list()
    {
        $page_info = $this->get_in_page_info();
        // $month_def_type = $this->get_in_el_month_def_type();
        $month_def_type = $this->get_in_int_val('month_def_type',-1);
        //list($start_time, $end_time) = $this->get_in_date_range_day(0);
        $ret_info = $this->t_month_def_type->get_list($page_info, $month_def_type);

        foreach($ret_info["list"] as &$item) {
            $item['def_time'] = date('Y-m-d', $item["def_time"]);
            $item['start_time'] = date('Y-m-d', $item["start_time"]);
            $item['end_time'] = date('Y-m-d', $item['end_time']);
            //\App\Helper\Utils::unixtime2date_for_time($item, "def_time");
            //\App\Helper\Utils::unixtime2date_for_item($item, "def_time", '_str');
            E\Emonth_def_type::set_item_value_str($item);
        }
        return $this->pageView(__METHOD__, $ret_info);
    }

    public function add_data()
    {
        $month_def_type = $this->get_in_int_val("month_def_type");
        $week_order = $this->get_in_int_val("week_order");
        if ($month_def_type == -1) {$month_def_type = 1;}
        $def_time = strtotime($this->get_in_str_val("def_time"));
        $start_time = $this->get_in_str_val("start_time");
        $end_time = $this->get_in_str_val("end_time");
        $start_time = strtotime($start_time);
        $end_time = strtotime($end_time);

        $res = $this->t_month_def_type->get_count_by_def_time($def_time,$month_def_type);
        if($month_def_type != E\Emonth_def_type::V_3){
            $week_order = 0;
        }
        if (!$res) {
            $this->t_month_def_type->row_insert([
                "month_def_type" => $month_def_type,
                'def_time' => $def_time,
                "start_time" => $start_time,
                "end_time" => $end_time,
                "week_order" => $week_order,
            ]);

            return $this->output_succ();
        } else {
            if ($month_def_type == E\Emonth_def_type::V_3) {
                $this->t_month_def_type->row_insert([
                    "month_def_type" => $month_def_type,
                    'def_time' => $def_time,
                    "start_time" => $start_time,
                    "end_time" => $end_time,
                    "week_order" => $week_order,
                ]);

                return $this->output_succ();
            }else{
                return $this->output_err('本月已有数据，请不要重复添加');
            }
        }
    }

    public function update_data()
    {
        $id = $this->get_in_int_val("id");
        $month_def_type = $this->get_in_int_val("month_def_type");
        $def_time = strtotime($this->get_in_str_val("def_time"));
        $start_time = strtotime($this->get_in_str_val("start_time"));
        $end_time = strtotime($this->get_in_str_val("end_time"));

        $this->t_month_def_type->field_update_list($id, [
            "month_def_type" => $month_def_type,
            "def_time" => $def_time,
            "start_time" => $start_time,
            "end_time" => $end_time
        ]);

        return $this->output_succ();
    }

    public function del_data()
    {
        $id = $this->get_in_int_val("id");
        $res = $this->t_month_def_type->row_delete($id);
        if ($res) {
            return $this->output_succ();
        } else {
            return $this->output_err("删除失败");
        }
    }
}
