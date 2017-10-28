<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use \App\Enums as E;
use Illuminate\Support\Facades\Mail ;

class test_lesson_opt extends Controller
{
    use CacheNick;

    public function test_opt_list(){
        list($start_time,$end_time)=$this->get_in_date_range_month(0);
        $page_info        = $this->get_in_page_info();
        $ret_info = $this->t_test_lesson_opt_log->get_all_list($start_time,$end_time,$page_info);
        foreach($ret_info['list'] as &$item){
            $this->cache_set_item_student_nick($item);
            if($item['role']==6){//销售
                $item['student_nick'] = $item['account'];
            }
            \App\Helper\Utils::unixtime2date_for_item($item,'opt_time');
            E\Erole::set_item_value_str($item);
            if($item['action'] == E\Eaction::V_1){
                $item['opt_type_str'] = E\Etest_opt_type::get_desc($item['opt_type']);
            }else{
                $item['opt_type_str'] = E\Etest_opt_type_new::get_desc($item['opt_type']);
            }
            $item['action_str'] = E\Eaction::get_desc($item['action']);
            $item['class_type'] = $item['lessonid']>0?'试听课':'测试课';
        }
        return $this->pageView(__METHOD__,$ret_info);
    }

}
