<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use \App\Enums as E;
use \App\Helper\Config;
use Illuminate\Support\Facades\Cookie ;

class teacher_simulate extends Controller
{
    use CacheNick;
    use TeaPower;

    public function new_teacher_money_list(){
        $this->switch_tongji_database();
        list($start_time,$end_time) = $this->get_in_date_range(0,0,0,null,3);
        // $start_time = strtotime("2017-1-1");
        // $end_time   = strtotime("2017-7-1");

        $teacherid          = $this->get_in_int_val("teacherid",-1);
        $teacher_money_type = $this->get_in_int_val("teacher_money_type",0);
        $level              = $this->get_in_int_val("level",-1);
        $is_test_user       = $this->get_in_int_val("is_test_user",0);
        $page_num           = $this->get_in_page_num();

        $tea_list = $this->t_teacher_info->get_teacher_total_list_new(
            $page_num,$start_time,$end_time,$teacherid,$teacher_money_type,$level,$is_test_user
        );

        foreach($tea_list['list'] as &$val){
            E\Eteacher_money_type::set_item_value_str($val);
            E\Elevel::set_item_value_str($val);
            E\Enew_level::set_item_value_str($val,"level_simulate");
            $val['create_time_str']=\App\Helper\Utils::unixtime2date($val['create_time']);
            $val['trial_lesson_count'] /= 100;
            $val['normal_lesson_count'] /= 100;
            $val['all_lesson_count']=$val['trial_lesson_count']+$val['normal_lesson_count'];
        }

        return $this->pageView(__METHOD__,$tea_list);
    }

}