<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use \App\Enums as E;
use App\Helper\Utils;
use Illuminate\Support\Facades\Cookie;

class teacher_lesson extends Controller
{
    use CacheNick;

    public function lesson_count_list(){
        $start_time = $this->get_in_start_time_from_str(date("Y-m-01",time(NULL)) );
        $end_time   = $this->get_in_end_time_from_str_next_day(date("Y-m-d",(time(NULL)+86400)) );
        $teacher_money_type = $this->get_in_int_val("teacher_money_type",-1);

        $ret_list = $this->t_lesson_info->get_tea_confirm_lesson_list($start_time,$end_time,$teacher_money_type);

        foreach($ret_list['list'] as &$item ){
            $item["teacher_nick"] =$this->cache_get_teacher_nick($item["teacherid"]);
            E\Eteacher_money_type::set_item_value_str($item);
        }
        return $this->Pageview(__METHOD__,$ret_list );
    }

}