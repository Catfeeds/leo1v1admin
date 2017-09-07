<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;
use App\Helper\Utils;
use Illuminate\Support\Facades\Cookie;

class teacher_money_www extends Controller
{
    use CacheNick;
    var $teacher_money;
    var $late_num   = 0;
    var $change_num = 0;

    public function __construct(){
        $this->teacher_money = \App\Helper\Config::get_config("teacher_money");
    }

    public function get_teacher_money_total_list(){
        $teacherid = $this->get_login_teacher();

        $now_date   = date("Y-m-01",time());
        $begin_time = strtotime("-1 year ",$now_date);
        $end_time   = strtotime("+1 month",strtotime($now_date));
        $first_lesson_time = $this->t_lesson_info_b3->get_first_lesson_time($teacherid);
        if($begin_time<$first_lesson_time){
            $begin_time = $first_lesson_time;
        }

        $reward_list = $this->t_teacher_money_list->get_teacher_honor_money_list($teacherid,$begin_time,$end_time);
        $lesson_list = $this->t_lesson_info->get_lesson_list_for_wages($teacherid,$begin_time,$end_time);
        $date_list   = [];
        foreach($lesson_list as $l_val){
            $month_key = date("Y-m",$l_val['lesson_start']);
            \App\Helper\Utils::check_isset_data($date_list[],[],$type);

        }

    }




}