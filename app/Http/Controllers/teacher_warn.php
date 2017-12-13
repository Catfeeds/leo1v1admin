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
        $teacherid = $this->get_in_int_val('teacher', 0);
        //$page_info = $this->get_in_page_info();

        $info = $this->t_teacher_warn->get_all_info($start_time, $end_time, $teacherid);
        $sort = [];
        foreach($info as &$item) {
            $item['nick'] = $this->cache_get_teacher_nick($item['teacherid']);
            $item['all'] = $item['five_num'] + $item['fift_num'] + $item['leave_num'] + $item['absent_num'] + $item['adjust_num'] + $item['ask_leave_num'] + $item['big_order_num'];
            $sort[] = $item['all'];
        }
        array_multisort($sort, SORT_DESC, $info);
        
        return $this->pageView(__METHOD__, '', [
            'info' => $info
        ]);
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
        $type = $this->get_in_str_val('type', 0);
        if ($type) {
            $info = $this->t_teacher_record_list->get_acc_for_teacherid($teacherid, 'all');
            if ($info) {
                foreach($info as &$item) {
                    $item['add_time'] = date('Y-m-d H:i:s', $item['add_time']);
                }
            }
        } else {
            $info = $this->t_teacher_record_list->get_acc_for_teacherid($teacherid);
            if ($info) {$info['add_time'] = date('Y-m-d H:i:s', $info['add_time']);}
        }
        return $this->output_succ(['data' => $info]);
    }

    public function add_record_data() {
        $teacherid = $this->get_in_str_val('teacherid');
        $record_info = $this->get_in_str_val('record_info');
        $acc = $this->get_account();
        $this->t_teacher_record_list->row_insert([
            'teacherid' => $teacherid,
            'record_info' => $record_info,
            'add_time' => time(),
            'type' => E\Erecord_type::V_16,
            'acc' => $acc
        ]);
        return $this->output_succ();
    }

}