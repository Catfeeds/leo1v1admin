<?php

namespace App\Http\Controllers;
use \App\Http\Controllers\Controller;
use \App\Enums as E;
use \App\Helper\Config;

class teacher_warn extends Controller
{
    use CacheNick;
    public function tea_warn_list() {
        list($start_time, $end_time) = $this->get_in_date_range_day(0);
        $page_info = $this->get_in_page_info();

        $ret_info = $this->t_teacher_warn->get_all_info($start_time, $end_time, $page_info);
        foreach($ret_info['list'] as &$item) {
            $item['nick'] = $this->cache_get_teacher_nick($item['teacherid']);
            $item['all'] = $item['fift_num'] + $item['leave_num'] + $item['absent_num'] + $item['adjust_num'] + $item['ask_leave_num'] + $item['big_order_num'];
        }
        
        return $this->pageView(__METHOD__, $ret_info);
    }

    public function get_teacher_detail() {
        $teacherid = $this->get_in_str_val("teacherid");
        $info = $this->t_teacher_info->field_get_list($teacherid, 'teacherid,nick');
        return $this->output_succ(['data' => $info]);
    }

    public function get_phone_for_teacherid() {
        $teacherid = $this->get_in_str_val("teacherid");
        $phone = $this->t_teacher_info->get_phone($teacherid);
        return $this->output_succ(['data' => $phone]);
    }

    public function get_return_back_info() {
        $teacherid = $this->get_in_str_val('teacherid');
        $info = $this->t_teacher_record_list->get_acc_for_teacherid($teacherid);
        return $this->output_succ(['data' => $info]);
    }

    public function add_record_data() {
        $teacherid = $this->get_in_str_val('teacherid');
        $record_info = $this->get_in_str_val('record_info');
        return $this->output_err($teacherid);
    }
}