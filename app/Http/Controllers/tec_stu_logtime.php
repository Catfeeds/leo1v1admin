<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;
use \App\Helper\Config;

class tec_stu_logtime extends Controller
{

    use CacheNick;

    public function __construct()
    {
        parent::__construct();
    }



    public function tec_stu_time()
    {
        dd(122);
        $date                = $this->get_in_str_val('date',date('Y-m-d', time(NULL)));
        $st_application_nick = $this->get_in_str_val('st_application_nick',"");
        $require_adminid     = $this->get_in_int_val('require_adminid', -1 );
        $userid              = $this->get_in_userid(-1);
        $teacherid           = $this->get_in_teacherid(-1);
        $run_flag            = $this->get_in_int_val("run_flag",1);
        $assistantid         = $this->get_in_assistantid(-1);

        $start_time  = strtotime($date);
        $end_time    = $start_time + 86400;
        $ret_info    = $this->t_lesson_info->get_lesson_condition_list(
            $start_time,$end_time,$st_application_nick,$userid,$teacherid,$run_flag,$assistantid,$require_adminid);
        $monitor_key = $this->session_gen_key($date,$st_application_nick,$userid,$teacherid,$run_flag) ;

        $adminid          = $this->get_account_id();
        $self_groupid     = $this->t_admin_group_user->get_groupid_by_adminid(2 , $adminid );
        $get_self_adminid = $this->t_admin_group_name->get_master_adminid($self_groupid);

        if($adminid == $get_self_adminid){
            $is_group_leader_flag = 1;
        }else{
            $is_group_leader_flag = 0;
        }

        session([$monitor_key=>$ret_info["list"]]);
        if(!empty($ret_info['list'])){
            $server_map = $this->gen_server_map($ret_info['list']);
        }

        $i=1;
        foreach($ret_info['list'] as $key=> &$item){
            $item['index']       = $i;
            $i++;
            $server_info         = @$server_map[$item['courseid']];
            $lesson_type         = $item['lesson_type'];
            $item['region']      = @$server_info['region'];
            $item['ip']          = @$server_info['ip'];
            $item['port']        = @$server_info['webrtc_port'];
            $item['lesson_type'] = $lesson_type;
            $item['room_id']     = \App\Helper\Utils::gen_roomid_name($lesson_type,$item['courseid'], $item['lesson_num'] );
            $item['lesson_time'] = date('H:i',$item['lesson_start'])."-".date('H:i',$item['lesson_end']);
            E\Econtract_type::set_item_value_str($item,"lesson_type");

            $this->cache_set_item_assistant_nick($item);
            $this->cache_set_item_teacher_nick($item);
            $this->cache_set_item_student_nick($item);
            if($item['server_type']==0){
                if ( $lesson_type <1000 ) {
                    $item[ 'server_type_str' ]="默认:理优";
                }else{
                    $item[ 'server_type_str' ]="默认:声网";
                }
            } else if($item[ 'server_type' ] ==1  ) {
                $item[ 'server_type_str' ]="理优";
            }else{
                $item[ 'server_type_str' ]="声网";
            }
        }

        return $this->pageView(__METHOD__,$ret_info,["self_groupid"=>$self_groupid,"is_group_leader_flag"=>$is_group_leader_flag]);
    }

}
