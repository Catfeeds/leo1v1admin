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
        $ret_info = $this->t_test_lesson_opt_log->get_all_list($page_info);
        foreach($ret_info['list'] as &$item){

        }
        return $this->pageView(__METHOD__,$ret_info);
    }
}
