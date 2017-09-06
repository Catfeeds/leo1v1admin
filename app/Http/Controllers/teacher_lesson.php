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
        list($start_time,$end_time) = $this->get_in_date_range(-1,0,0,null,1);
        $teacher_money_type = $this->get_in_int_val("teacher_money_type",-1);

        $ret_list = $this->t_lesson_info_b3->get_tea_lesson_count_list($start_time,$end_time,$teacher_money_type);
        dd($ret_list);

        // return $this->Pageview(__METHOD__,$ret_list );
    }

}