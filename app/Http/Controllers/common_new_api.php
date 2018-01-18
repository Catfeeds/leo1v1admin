<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;


class common_new_api extends Controller{

    var $check_login_flag = false;
    //日志事件
    public function event_log(){
        //http://self.admin.leo1v1.com/common_new_api/event_log?project=origin&sub_project=tttt&event_name=start
        $project= trim(trim( $this->get_in_str_val("project")), "=");  //
        $sub_project= trim( $this->get_in_str_val("sub_project"));
        $event_name = trim( $this->get_in_str_val("event_name"));
        $event_type_id=$this->t_log_event_type->get_event_type_id_with_check( $project, $sub_project, $event_name);

        $this->t_log_event_log->row_insert([
            "logtime"       => time(NULL),
            "ip"            => ip2long( $this->get_in_client_ip()),
            "event_type_id" => $event_type_id,
            "value"         => 1,
        ]);
        return $this->output_succ();

    }

}
