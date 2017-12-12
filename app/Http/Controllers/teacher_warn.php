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
        }
        
        return $this->pageView(__METHOD__, $ret_info);
    }

    public function get_teacher_detail() {
        $teacherid = $this->get_in_str_val("teacherid");
        $info = $this->t_teacher_info->field_get_list($teacherid, 'teacherid,nick');
        return $this->output_succ(['data' => $info]);
    }
}