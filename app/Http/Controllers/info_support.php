<?php

namespace App\Http\Controllers;
use \App\Http\Controllers\Controller;
use \App\Enums as E;
use \App\Helper\Config;

class info_support extends Controller
{
    use CacheNick;
    var $check_login_flag=true;

    function __construct( ) {
        parent::__construct();
    }

    public function get_examination()
    {
        $use_type      = $this->get_in_int_val('use_type', 1);
        $resource_type = $this->get_in_int_val('resource_type', 1);
        $subject       = $this->get_in_int_val('subject', -1);

        $ret_info = $this->t_user_log->get_list();
        foreach($ret_info['list'] as &$item) {
            $item['add_time'] = date('Y-m-d H:i:s',$item['add_time']);
            $item['admin_name'] = $this->t_admin_users->get_account($item['adminid']);
            $item['stu_name'] = $this->t_student_info->get_realname($item['userid']);
        }

        return $this->pageView(__METHOD__, $ret_info);
    }

}