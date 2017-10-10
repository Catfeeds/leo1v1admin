<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use Illuminate\Support\Facades\Mail ;


class seller_level_goal extends Controller
{
    public function seller_level_goal_list(){
        $page_num   = $this->get_in_page_num();
        $page_info  = $this->get_in_page_info();
        $ret_info  = $this->t_seller_level_goal->get_all_list($page_info);
        foreach($ret_info['list'] as &$item){
            E\Epp_level::set_item_value_str($item);
            \App\Helper\Utils::unixtime2date_for_item($item,'create_time');
        }
        return $this->pageView(__METHOD__,$ret_info);
    }

    public function add_seller_level_goal(){
        $seller_level = $this->get_in_int_val('seller_level');
        $level_goal = $this->get_in_int_val('level_goal');
        $level_face = $this->get_in_str_val('level_face');
        if($level_face){
            $domain = config('admin')['qiniu']['public']['url'];
            $level_face_url = $domain.'/'.$level_face;
        }else{
            $level_face_url = '';
        }
        $this->t_seller_level_goal->row_insert([
            "seller_level" => $seller_level,
            "level_goal"   => $level_goal ,
            "level_face"   => $level_face_url,
            "create_time"  => time(null),
        ]);

        return $this->output_succ();
    }
}
