<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use Illuminate\Support\Facades\Mail ;

use CacheNick;

class grab_lesson extends Controller
{

    public function make_lesson_link(){
        $grab_lesson_link = $this->get_in_str_val('url');
        $live_time        = $this->get_in_int_val('live_time');
        $create_time      = $this->get_in_int_val('create_time');
        $requireids       = $this->get_in_str_val('requireids');
        $adminid          = $this->get_account_id();

        $ret = $this->t_grab_lesson_link_info->row_insert([
            'grab_lesson_link' => $grab_lesson_link,
            'live_time'        => $live_time,
            'create_time'      => $create_time,
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
