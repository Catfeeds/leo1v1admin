<?php

namespace App\Http\Controllers;
use \App\Http\Controllers\Controller;
use \App\Enums as E;
use \App\Helper\Config;

class user_log extends Controller
{

    public function log_list()
    {
        $page_info = $this->get_in_page_info();
        $admin_id = session('adminid');
        if (!$admin_id) {
            exit('对不起, 你没有权限');
        }
        list($start_time, $end_time) = $this->get_in_date_range_day(0);
        $ret_info = $this->t_user_log->get_list($page_info, $start_time, $end_time);
        foreach($ret_info['list'] as &$item) {
            $item['add_time'] = date('Y-m-d H:i:s',$item['add_time']);
            $item['admin_name'] = $this->t_admin_users->get_account($item['adminid']);
            $item['stu_name'] = $this->t_student_info->get_realname($item['userid']);
        }

        return $this->pageView(__METHOD__, $ret_info);
    }

}