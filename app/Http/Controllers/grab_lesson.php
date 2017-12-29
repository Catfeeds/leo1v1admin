<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use Illuminate\Support\Facades\Mail ;

class grab_lesson extends Controller
{

    use CacheNick;
    use TeaPower;
    var $check_login_flag=true;

    function __construct( )  {
        parent::__construct();
    }

    public function get_all_grab_info(){
        list($start_time,$end_time) = $this->get_in_date_range(-7, 0 );
        $grab_lesson_link           = $this->get_in_str_val('grab_lesson_link');
        $grabid    = $this->get_in_int_val('grabid');
        $live_time = ($this->get_in_int_val('live_time'))*60;
        $adminid   = $this->get_in_int_val('adminid');
        $page_info = $this->get_in_page_info();
        $ret_info  = $this->t_grab_lesson_link_info->get_all_info(
            $start_time, $end_time,$grabid, $grab_lesson_link, $live_time, $adminid, $page_info
            );

        foreach($ret_info['list'] as &$item) {
            $this->cache_set_item_account_nick($item,"adminid", "nick");
            \App\Helper\Utils::unixtime2date_for_item($item,"create_time");
            $item['live_time']    = $item['live_time'] / 60;
            $item['lesson_count'] = count( explode(',', $item['requireids']));
        }

        return $this->pageView( __METHOD__,$ret_info);
    }

    public function get_list_by_grabid_js(){
        $page_num = $this->get_in_page_num();
        $grabid   = $this->get_in_int_val('grabid', -1);
        $ret_list = $this->t_grab_lesson_link_visit_info->get_visit_detail_by_grabid($page_num, $grabid);

        foreach ($ret_list['list'] as &$item){
            \App\Helper\Utils::unixtime2date_for_item($item,"visit_time");
            \App\Helper\Utils::unixtime2date_for_item($item,"grab_time");
            $this->cache_set_item_teacher_nick($item,"teacherid", "tea_nick");
            \App\Helper\Utils::transform_1tg_0tr($item,"operation");
            \App\Helper\Utils::transform_1tg_0tr($item,"success_flag");
        }
        $ret_list["page_info"] = $this->get_page_info_for_js($ret_list["page_info"]);
        return $this->output_succ(["data"=> $ret_list]);
    }

    public function update_lesson_link(){
        $max_num          = pow(2,31) -1;
        $grab_lesson_link = $this->get_in_str_val('url');
        $live_time        = ( $this->get_in_int_val('live_time') ) * 60;
        $text             = $this->get_in_str_val('text');
        $adminid          = $this->get_account_id();

        if ( $live_time >= $max_num ) {
            $live_time = 2147483647;
        }

        $grabid = base64_decode($text);

        $ret = $this->t_grab_lesson_link_info->field_update_list( $grabid, [
            'grab_lesson_link' => $grab_lesson_link,
            'live_time'        => $live_time,
            'create_time'      => time(),
        ]);
        if ($ret) {
            return $this->output_succ();
        } else {
            return outputjson_error('生成抢课链接失败！');
        }
    }

    public function add_requireids(){
        $adminid    = $this->get_account_id();
        $requireids = $this->get_in_str_val('requireids');
        if($requireids) {
            //检查每个教务抛链接的量已做限制
            $check_flag = $this->check_jw_plan_limit($requireids);
            if($check_flag){
                return $check_flag;
            }
            $this->t_grab_lesson_link_info->row_insert([
                'grab_lesson_link' => 0,
                'live_time'        => 0,
                'create_time'      => time(),
                'adminid'          => $adminid,
                'requireids'       => $requireids,
            ]);

            $last_grabid   = $this->t_grab_lesson_link_info->get_last_insertid();
            $encode_grabid = base64_encode($last_grabid);
            return $this->output_succ(["data" => $encode_grabid]);
        }
    }

}
