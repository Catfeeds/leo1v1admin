<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use \App\Enums as E;
use Illuminate\Support\Facades\Mail ;

class test_lesson_opt extends Controller
{
    use CacheNick;

    public function test_opt_list(){
        $this->check_and_switch_tongji_domain();
        list($start_time,$end_time)=$this->get_in_date_range_month(0);
        $test_lesson_type = $this->get_in_int_val('test_lesson_type',-1);
        $action           = $this->get_in_int_val('action',-1);
        $test_opt_type    = $this->get_in_int_val('test_opt_type',-1);
        $adminid          = $this->get_in_int_val('adminid',-1);
        $user_name        = trim($this->get_in_str_val('user_name',''));
        $page_info        = $this->get_in_page_info();
        $ret_info = $this->t_test_lesson_opt_log->get_all_list($start_time,$end_time,$test_lesson_type,$action,$test_opt_type,$adminid,$user_name,$page_info);
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
