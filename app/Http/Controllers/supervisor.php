<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;
use \App\Helper\Config;

class supervisor extends Controller
{

    use CacheNick;

    public function __construct()
    {
        parent::__construct();
    }

    public function get_servers()
    {
        $courseid = $this->get_in_int_val('courseid',0);
        $server_arr = get_server_info($courseid);

        if(!$server_arr){
            outputJson(array('ret' => -1, 'info' => '无法获取服务器信息'));
        }
        outputJson(array(
            'ret'  => 0,
            'data' => array(
                "ip"          => $server_arr[0]['ip'],
                'xmpp_port'   => $server_arr[0]['xmpp_port'],
                'webrtc_port' => $server_arr[0]['websocket_port']
            )
        ));
    }

    public function get_lesson_contact()
    {
        $lessonid = $this->get_in_int_val('lessonid',0);
        $ret = $this->lesson_manage_model->get_contact_for_lesson($lessonid);
        outputJson(array('ret' => 0, 'data' => $ret));
    }

    public function lesson_get_log()
    {

        $lessonid     = $this->get_in_int_val('lessonid',-1);
        $userid       = $this->get_in_int_val('userid',-1);
        $server_type  = $this->get_in_int_val('server_type',-1);
        $teacherid    = $this->get_in_int_val('teacher_id',-1);
        $stu_id       = $this->get_in_int_val('stu_id',-1);
        $lesson_start = $this->get_in_int_val('lesson_start',0);
        $lesson_end   = $this->get_in_int_val('lesson_end',0);

        $ret_arr=$this->lesson_manage_model->get_lesson_log($lessonid,$userid,$server_type
                                                            ,$teacherid,$stu_id,$lesson_start,$lesson_end );

        $ret_list         = array();
        $server_type_conf = array("1" =>"webrtc" , "2" => "xmpp" );
        $log_type_conf    = array("1" =>"login" , "2" =>"logout", "3"=>"register", "4"=> "no_recv_data" );

        foreach( $ret_arr as $item ){
            $item["server_ip"]   = long2ip($item["server_ip"]);
            $item["opt_time"]    = unixtime2date( $item["opt_time"] );
            $item["opt_type"]    = $log_type_conf[$item["opt_type"]];
            $item["server_type"] = $server_type_conf[$item["server_type"]];
            $ret_list[]          = $item;
        }

        outputJson(array('ret' => 0, 'data' => $ret_list ));
    }


    public function session_gen_key($date,$st_application_nick,$userid,$teacherid,$run_flag) {
        return "current_monitor_list-$date-$st_application_nick-$userid-$teacherid-$run_flag";

    }

    public function monitor_seller()
    {
        $this->set_in_value("st_application_nick", $this->get_account() );
        $this->set_in_value("require_adminid", $this->get_account_id() );
        $this->set_in_value("test_seller_adminid", $this->get_account_id() );
        $this->set_in_value("run_flag",-1);
        return $this->monitor();
    }

    public function monitor_ass()
    {
        $this->set_in_value("assistantid", $this->t_assistant_info->get_assistantid($this->get_account()) );
        $this->set_in_value("run_flag",-1);
        return $this->monitor();
    }

    public function monitor()
    {
        $adminid             = $this->get_account_id();
        $date                = $this->get_in_str_val('date',date('Y-m-d', time(NULL)));
        $st_application_nick = $this->get_in_str_val('st_application_nick',"");
        $require_adminid     = $this->get_in_int_val('require_adminid', -1 );
        $test_seller_id      = $this->get_in_int_val("test_seller_id",-1 );
        $test_seller_adminid = $this->get_in_int_val('test_seller_adminid', -1 );
        $userid              = $this->get_in_userid(-1);
        $teacherid           = $this->get_in_teacherid(-1);
        $run_flag            = $this->get_in_int_val("run_flag",1);
        $assistantid         = $this->get_in_assistantid(-1);

        $start_time  = strtotime($date);
        $end_time    = $start_time + 86400;
        $group_type = 0;
        if($test_seller_adminid != -1){
            $son_adminid = $this->t_admin_main_group_name->get_son_adminid($adminid);
            $son_adminid_arr = [];
            foreach($son_adminid as $item){
                $son_adminid_arr[] = $item['adminid'];
            }
            array_unshift($son_adminid_arr,$adminid);
            $require_adminid_arr = array_unique($son_adminid_arr);
            $group_type = count($require_adminid_arr)>1?1:0;
            $ret_info    = $this->t_lesson_info->get_lesson_condition_list_new(
                $start_time,$end_time,$st_application_nick,$userid,$teacherid,$run_flag,$assistantid,$require_adminid_arr);
        }else{
            $ret_info    = $this->t_lesson_info->get_lesson_condition_list(
                $start_time,$end_time,$st_application_nick,$userid,$teacherid,$run_flag,$assistantid,$require_adminid);
        }
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

        $i = 1;
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
                    $item['server_type_str']="默认:理优";
                }else{
                    $item['server_type_str']="默认:声网";
                }
            }else if($item['server_type']==1) {
                $item['server_type_str']="理优";
            }else{
                $item['server_type_str']="声网";
            }
        }

        return $this->pageView(__METHOD__,$ret_info,[
            "group_type"           => $group_type,
            "self_groupid"         => $self_groupid,
            "is_group_leader_flag" => $is_group_leader_flag
        ]);
    }


    private function gen_server_map($list){
        $id_list = [];
        foreach($list as $item){
            $id_list[] = $item["courseid"];
        }

        $server_map = array();
        if(!\App\Helper\Utils::check_env_is_testing()){
            $server_arr = \App\Helper\Net::get_server_info($id_list);
            if(isset($server_arr["server_list"]) && !empty($server_arr) ){
                foreach($server_arr["server_list"] as $key => $value){
                    $server_map[$value['courseid']] = $value;
                }
            }
        }

        return $server_map;
    }

    public function get_current_lessons()
    {
        $ret = $this->lesson_manage_model->get_current_lessons();
        $server_map = $this->gen_server_map($ret);

        foreach($ret as &$item){
            if($item['current_server'] == ""){
                $item['current_server'] = $this->g_config['default_server']['id'];
            }

            $teacherid= $item['teacherid'];
            $lessonid= $item['lessonid'];
            $lesson_type= $item['lesson_type'];
            $real_begin_time= $item['real_begin_time'];

            $item[ 'room_id'] = ($lesson_type>=1000?"p_":"l_").$item['courseid']."y".$item['lesson_num']."y".$item['lesson_type'];

            list($item["last_server_ip"],$item["teacher_server_list"])=$this->lesson_manage_model->get_lesson_webrtc_log_by_teacherid($lessonid,$teacherid,$real_begin_time);
        }

        outputJson(array('ret' => 0, data => $ret,
                         "webrtc_xmpp_server_list" => $this->config->get_webrtc_xmpp_server_list() ));
    }


    public function get_lesson_conditions_js()
    {
        $date = $this->get_in_str_val('date', date('Y-m-d', time(NULL)));
        $start_time = strtotime($date);
        $end_time = $start_time + 86400;
        $st_application_nick = $this->get_in_str_val('st_application_nick',"");
        $require_adminid = $this->get_in_int_val('require_adminid',-1);
        $userid = $this->get_in_userid(-1);
        $teacherid= $this->get_in_teacherid(-1);
        $run_flag= $this->get_in_int_val("run_flag",-1);
        $assistantid= $this->get_in_assistantid(-1);


        $monitor_key=$this->session_gen_key($date,$st_application_nick,$userid,$teacherid,$run_flag);
        $current_monitor_list=session($monitor_key);

        $ret_info = $this->t_lesson_info->get_lesson_conditions($start_time,$end_time, $st_application_nick,$userid,$teacherid,$run_flag,$assistantid,$require_adminid );
        $reload_flag=false;
        if (count($current_monitor_list) !=  count($ret_info["list"]) ) {
            \App\Helper\Utils::logger("diff count:". count($current_monitor_list) ."<>".  count($ret_info["list"]) );
            $reload_flag=true;
        }
        foreach ($ret_info["list"] as &$item) {
            $lessonid=$item["lessonid"];
            $old_row=@$current_monitor_list[$lessonid];
            if($old_row) {
                foreach ($old_row as $o_k => $o_v) {
                    if(@$item[$o_k ] <>$o_v ) {
                        \App\Helper\Utils::logger("diff $o_k=> $o_v ");


                        $reload_flag=true ;
                    }
                    if ($o_k!="lessonid" && $o_k !="lesson_start"  )  {
                        unset  ($item[$o_k] );
                    }
                }
            }else{
                \App\Helper\Utils::logger("diff not find lessonid:$lessonid");
                $reload_flag=true;
            }


            if (!$item["lesson_condition"]) {
                $item['lesson_condition'] = $this->gen_empty_cond();
            }
            if($item['lesson_start'] < time(NULL) && $item['lesson_status'] != 2) {
                $item['lesson_status'] = 1;
            }
            foreach  ($item  as $ik=>$iv) {
                if (is_int($ik)) {
                    unset($item[$ik] );
                }
            }
        }
        $cond_list = $ret_info['list'];
        return $this->output_succ( [
            'condition_list' => $cond_list,
            "reload_flag"    => $reload_flag ,
            "lesson_count"   => count($cond_list),
        ] );

    }


    private function gen_empty_cond()
    {
        $condition_arr = array(
            'stu' => array(
                'xmpp'       => 0,
                'webrtc'     => 0,
                'xmpp_dis'   => 0,
                'webrtc_dis' => 0
            ),
            'tea' => array(
                'xmpp'       => 0,
                'webrtc'     => 0,
                'xmpp_dis'   => 0,
                'webrtc_dis' => 0
            ),
            'ad' => array(
                'xmpp'       => 0,
                'webrtc'     => 0,
                'xmpp_dis'   => 0,
                'webrtc_dis' => 0
            ),
            'par' => array(
                'xmpp'       => 0,
                'webrtc'     => 0,
                'xmpp_dis'   => 0,
                'webrtc_dis' => 0
            ),
            'suspend' => 0
        );
        return json_encode($condition_arr);
   }

    public function going_monitor()
    {
        $page_num = $this->get_in_int_val('page_num', -1);
        if($page_num < 1)
            $page_num = 1;

        $ret_info = $this->lesson_manage_model->get_going_lesson_condition_list($page_num);
        $condition_arr = array();

        $server_map = $this->gen_server_map($ret_info['list']);
        $num = ($page_num-1)*5+1;
        foreach($ret_info['list'] as $key => $value){
            $server_info  = $server_map[$value['courseid']];
            $condition_arr[] = array(
                'lessonid'    => $value['lessonid'],
                'region'      => $server_info['region'],
                'ip'          => $server_info['ip'],
                'port'        => $server_info['webrtc_port'],
                'num'         => $num++,
                'courseid'    => $value['courseid'],
                'lesson_num'  => $value['lesson_num'],
                'stu_nick'    => $value['stu_nick'],
                'tea_nick'    => $value['tea_nick'],
                'lesson_time' => date('Y-m-d H:i' ,$value['lesson_start']) . " - " . date('H:i', $value['lesson_end']),
            );
        }

        $s_url = get_url('supervisor', 'monitor', "?page_num={Page}");
        $this->setTplPageInfo($s_url, $ret_info['total_num'] ,  20, $page_num);

        $this->tpl->assign('page_num',$page_num);
        $this->tpl->assign('condition_arr', $condition_arr);
//        $this->tpl->display('going_monitor.html');
        $this->display(__METHOD__);
    }

    public function get_going_lesson_conditions()
    {
        $date = $this->get_in_str_val('date', date('Y-m-d', time(NULL)));

        $tea_nick = $this->get_in_str_val('tea_nick', "");
        $page_num = $this->get_in_int_val('page_num', -1);

        if($page_num < 1)
            $page_num = 1;
        $start_s = strtotime($date);
        $end_s = $start_s + 86400;

        $ret = $this->lesson_manage_model->get_going_lesson_conditions($page_num);
        $cond_list = $ret['list'];
        $len = count($cond_list);
        for($i = 0; $i < $len; $i++){
            if($cond_list[$i]['lesson_condition'] == "")
                $cond_list[$i]['lesson_condition'] = $this->gen_empty_cond();
            if($cond_list[$i]['lesson_start'] < time(NULL) && $cond_list[$i]['lesson_status'] != 2)
                $cond_list[$i]['lesson_status'] = 1;
        }

        outputJson(array('ret' => 0, 'condition_list' => $cond_list));
    }

    public function update_server_type(){
        $type         = $this->get_in_int_val('type',0);
        $lessonid_str = $this->get_in_str_val('update_lessonid','');

        $lessonid_list = array_filter(split(",",$lessonid_str));
        foreach ($lessonid_list as $lessonid) {
            $lessonid=intval($lessonid);
            if ($lessonid>0) {

            }

        }
        //$ret_info = $this->t_lesson_info->update_server_type($type,$lessonid);

        if(!$ret_info){
            outputjson_error();
        }
        outputjson_success();
    }

    public function get_tongji() {
        $date       = $this->get_in_str_val("date");
        $start_time = strtotime($date);
        $end_time   = $start_time+86400;
        $ret_list   = $this->lesson_manage_model->get_users_jion_lesson($start_time,$end_time);

        outputjson_success( array(
            "join_lesson_user_count" => count($ret_list),
        ) );
    }

    public function add_error_info(){
        $lessonid         = $this->get_in_int_val('lessonid',0);
        $error_info       = $this->get_in_str_val('error_info','');
        $error_info_other = $this->get_in_str_val('error_info_other','');

        $ret_info =$this->t_error_info->add_errror_info($lessonid);

        if(!$ret_info){
            outputjson_error();
        }
        outputjson_success();
    }

    public function lesson_all_info () {
        $date                = $this->get_in_str_val('date',date('Y-m-d', time(NULL)));
        $st_application_nick = $this->get_in_str_val('st_application_nick',"");
        $require_adminid     = $this->get_in_int_val('require_adminid', -1 );
        $userid              = $this->get_in_userid(-1);
        $teacherid           = $this->get_in_teacherid(-1);
        $run_flag            = $this->get_in_int_val("run_flag",1);
        $assistantid         = $this->get_in_assistantid(-1);

        $start_time  = strtotime($date);
        $end_time    = $start_time + 86400;
        $lessonid    = $this->get_in_int_val('lessonid');

        $ret_info = $this->t_lesson_info->get_lesson_info_by_lessonid($lessonid);

        $adminid          = $this->get_account_id();
        $self_groupid     = $this->t_admin_group_user->get_groupid_by_adminid(2 , $adminid );
        $get_self_adminid = $this->t_admin_group_name->get_master_adminid($self_groupid);

        if($adminid == $get_self_adminid){
            $is_group_leader_flag = 1;
        }else{
            $is_group_leader_flag = 0;
        }

        if(!empty($ret_info['list'])){
            $server_map = $this->gen_server_map($ret_info['list']);
        }

        $info=$this->t_lesson_info_b2-> get_info_for_monitor($lessonid);
        E\Egrade::set_item_value_str($info);
        E\Esubject::set_item_value_str($info);
        $this->cache_set_item_account_nick($info, "cur_require_adminid", "cur_require_admin_nick");

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

        // 处理登录日志

        $userid       = $ret_info['list'][$lessonid]['userid'];
        $server_type  = $ret_info['list'][$lessonid]['server_type'];
        $teacherid    = $ret_info['list'][$lessonid]['teacherid'];
        $stu_id       = $ret_info['list'][$lessonid]['userid'];
        $lesson_start = $ret_info['list'][$lessonid]['lesson_start'];
        $lesson_end   = $ret_info['list'][$lessonid]['lesson_end'];

        $ret_arr_log=$this->t_lesson_opt_log->get_lesson_log_by_pool($lessonid,$userid,$server_type
                                                                     ,$teacherid,$stu_id,$lesson_start,$lesson_end );
        $ret_list_log=array();
        $server_type_conf=array("1" =>"webrtc" , "2" => "xmpp" );
        $log_type_conf=array("1" =>"login" , "2" =>"logout", "3"=>"register", "4"=> "no_recv_data" );


        $ret_list_log_stu=[];
        $ret_list_log_tea=[];

        foreach( $ret_arr_log as $item_log ){
            $item_log["server_ip"]   = long2ip($item_log["server_ip"]);
            $item_log["opt_time"]    = unixtime2date( $item_log["opt_time"] );
            $item_log["opt_type"]    = $log_type_conf[$item_log["opt_type"]];
            $item_log["server_type"] = $server_type_conf[$item_log["server_type"]];

            if ( $item_log["opt_type"] == "login") {
                $item_log['cls'] = "success";
            }elseif ( $item_log["opt_type"] == "register") {
                $item_log['cls'] = "warning";
            }elseif ( $item_log["opt_type"] == "logout") {
                $item_log['cls'] = "danger";
            }

            if ($item_log['userid'] == $stu_id ) {
                $item_log['rule_str'] = '学生';
            } elseif ($item_log['userid'] == $teacherid) {
                $item_log['rule_str'] = '老师';
            }
            $ret_list_log[] = $item_log;


            // 处理学生是否在线             // 处理老师是否在线

            if ($item_log['userid'] == $stu_id) {
                $ret_list_log_stu[] = $item_log;
            } elseif ($item_log['userid'] == $teacherid) {
                $ret_list_log_tea[] = $item_log;
            }

        }

        // echo '开发中...';
        // dd($ret_list_log_stu);
        // dd($ret_list_log_tea);

        if ($ret_list_log_stu) {
            $ret_list_log_stu_last = array_pop($ret_list_log_stu);
            if ($ret_list_log_stu_last['opt_type'] == 'logout') {
                $info['stu_log_status'] = '不在线';
            } elseif ($ret_list_log_stu_last['opt_type'] == 'login'){
                $info['stu_log_status'] = '在线';
            }

        }

        if ($ret_list_log_tea) {
            $ret_list_log_tea_last = array_pop($ret_list_log_tea);
            if ($ret_list_log_tea_last['opt_type'] == 'logout') {
                $info['tea_log_status'] = '不在线';
            } elseif ($ret_list_log_tea_last['opt_type'] == 'login'){
                $info['tea_log_status'] = '在线';
            }

        }



        $log_num_str = $this->t_lesson_info->get_lesson_conditions_by_lessonid($lessonid);

        if ($log_num_str['0']['lesson_condition']) {
            $log_num_arr = json_decode($log_num_str['0']['lesson_condition'],true);
            $info['stu_xmpp']    = $log_num_arr['stu']['xmpp_dis'];
            $info['stu_webrtc']  = $log_num_arr['stu']['webrtc_dis'];
            $info['tea_xmpp']    = $log_num_arr['tea']['xmpp_dis'];
            $info['tea_webrtc']  = $log_num_arr['tea']['webrtc_dis'];
            $info['ass_xmpp']    = $log_num_arr['ad']['xmpp_dis'];
            $info['ass_webrtc']  = $log_num_arr['ad']['webrtc_dis'];
            $info['par_xmpp']    = $log_num_arr['par']['xmpp_dis'];
            $info['par_webrtc']  = $log_num_arr['par']['webrtc_dis'];
        } else {
            $info['stu_xmpp']    = 0;
            $info['stu_webrtc']  = 0;
            $info['tea_xmpp']    = 0;
            $info['tea_webrtc']  = 0;
            $info['ass_xmpp']    = 0;
            $info['ass_webrtc']  = 0;
            $info['par_xmpp']    = 0;
            $info['par_webrtc']  = 0;
        }


        return $this->pageView(__METHOD__,$ret_info,["self_groupid"=>$self_groupid,'stu_info'=>$info,"is_group_leader_flag"=>$is_group_leader_flag,'log_lists'=>$ret_list_log]);

    }




}
