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

        $teacherid          = $this->get_in_int_val("teacherid",-1);
        $teacher_money_type = $this->get_in_int_val("teacher_money_type",0);
        $level              = $this->get_in_int_val("level",-1);
        $ignore_level_up    = $this->get_in_int_val("ignore_level_up",0);
        $page_num           = $this->get_in_page_num();

        $tea_list = $this->t_teacher_info->get_teacher_simulate_list(
            $page_num,$start_time,$end_time,$teacherid,$teacher_money_type,$level,$ignore_level_up
        );
        dd($tea_list);
        foreach($tea_list['list'] as &$val){
            E\Eteacher_money_type::set_item_value_str($val);
            E\Elevel::set_item_value_str($val);
            E\Enew_level::set_item_value_str($val,"level_simulate");
        }

        return $this->pageView(__METHOD__,$tea_list);
    }

}