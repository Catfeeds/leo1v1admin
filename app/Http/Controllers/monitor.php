<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

class monitor extends Controller
{
    public function smsmonitor()
    {
        list($start_time,$end_time) = $this->get_in_date_range(-10, 0);
        $is_succ  = $this->get_in_int_val('is_succ', -1);
        $phone    = $this->get_in_phone();
        $receive_content = $this->get_in_str_val("receive_content");
        $type     = $this->get_in_int_val('type', -1);
        $page_num = $this->get_in_page_num();


        $ret_info = $this->t_sms_msg->get_sms_list($page_num, $start_time, $end_time, $phone, $is_succ, $type,$receive_content);
        foreach ($ret_info['list'] as &$item) {
            $item['user_ip']    = long2ip($item['user_ip']);
            $item['type']       = E\Esms_type::get_desc($item['type']);
            $item['send_time']  = date('Y-m-d H:i:s', $item['send_time']);
            $item['is_success'] = $item['is_success'] == 0 ? '失败' : '成功';
        }

        return $this->pageView(__METHOD__,$ret_info);
    }

    public function wxmonitor(){
        list($start_time,$end_time) = $this->get_in_date_range(-10, 0);
        $is_succ     = $this->get_in_int_val('is_succ', -1);
        $teacherid   = $this->get_in_phone();
        $template_id = $this->get_in_int_val('type', -1);
        $page_num    = $this->get_in_page_num();

        $ret_info = $this->t_weixin_msg->get_weixin_list($page_num, $start_time, $end_time, $phone, $is_succ, $type);

        foreach ($ret_info['list'] as &$item) {
            $item['user_ip']    = long2ip($item['user_ip']);
            $item['type']       = E\Esms_type::get_desc($item['type']);
            $item['send_time']  = date('Y-m-d H:i:s', $item['send_time']);
            $item['is_success'] = $item['is_success'] == 0 ? '失败' : '成功';
        }
        return $this->Pageview(__METHOD__, $ret_info);
    }

}