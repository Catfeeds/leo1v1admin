<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use Illuminate\Support\Facades\Mail ;


class grab_lesson extends Controller
{

    use CacheNick;
    var $check_login_flag=true;

    function __construct( )  {
        parent::__construct();
    }

    public function get_all_grab_info(){
        list($start_time,$end_time) = $this->get_in_date_range(-7, 0 );
        $grabid = $this->get_in_int_val('grabid');
        $grab_lesson_link = $this->get_in_str_val('grab_lesson_link');
        $live_time = ($this->get_in_int_val('live_time'))*60;
        // $start_time = $this->get_in_int_val('start_time', -1);
        // $end_time = $this->get_in_int_val('end_time', -1);
        $adminid = $this->get_in_int_val('adminid');
        $page_info= $this->get_in_page_info();
        $ret_info = $this->t_grab_lesson_link_info->get_all_info($start_time, $end_time,$grabid, $grab_lesson_link, $live_time,
                                                                 $adminid, $page_info);
        foreach($ret_info['list'] as &$item) {
            $this->cache_set_item_account_nick($item,"adminid", "nick");
            \App\Helper\Utils::unixtime2date_for_item($item,"create_time");

            // \App\Helper\Utils::unixtime2date_for_item($item, 'create_time');
            // $this->cache_get_account_nick($item['adminid']);
            $item['live_time'] = $item['live_time'] / 60;
        }

        // dd($ret_info);
        return $this->pageView( __METHOD__,$ret_info);
    }

    public function get_list_by_grabid_js(){
        $grabid = $this->get_in_int_val('grabid', -1);
        $ret_info = $this->t_grab_lesson_link_visit_info->get_visit_detail_by_grabid($grabid);
        foreach ($ret_info as &$item){
            \App\Helper\Utils::unixtime2date_for_item($item,"visit_time");
            \App\Helper\Utils::unixtime2date_for_item($item,"grab_time");
            $this->cache_set_item_teacher_nick($item,"teacherid", "tea_nick");
            \App\Helper\Utils::transform_1tg_0tr($item,"operation");
            \App\Helper\Utils::transform_1tg_0tr($item,"success_flag");
        }
        return $this->output_succ(["data"=> $ret_info]);
    }
    public function make_lesson_link(){
        $max_num = pow(2,31) -1;
        $grab_lesson_link = $this->get_in_str_val('url');
        $live_time        = ( $this->get_in_int_val('live_time') ) * 60;
        $requireids       = $this->get_in_str_val('requireids');
        $adminid          = $this->get_account_id();

        if ( $live_time >= $max_num ) {
            $live_time = 2147483647;
        }
        $ret = $this->t_grab_lesson_link_info->row_insert([
            'grab_lesson_link' => $grab_lesson_link,
            'live_time'        => $live_time,
            'create_time'      => time(),
            'adminid'          => $adminid,
            'requireids'       => $requireids,
            ]);
        // $id=$this->t_grab_lesson_link_info->get_last_insertid();
        if ($ret) {
            return $this->output_succ();
            // $id = $this->t_grab_lesson_link_info->get_last_insertid();
            // return outputjson_success(['test' => $id]);
        } else {
            return outputjson_error('生成链接失败！');
        }
    }


}
