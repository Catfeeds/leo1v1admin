<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;
use Illuminate\Support\Facades\Redis;

use Illuminate\Support\Facades\Session;
// 引入鉴权类
use Qiniu\Auth;

// 引入上传类
use Qiniu\Storage\UploadManager;
use Qiniu\Storage\BucketManager;


class self_manage extends Controller
{
    use TeaPower;
    use CacheNick;

    public  function index() {
        return $this->pageView(__METHOD__);
    }

    public function qingjia() {
        list($start_time,$end_time)= $this->get_in_date_range(date("Y-01-01"), 0 );
        $adminid  = $this->get_account_id();
        $page_num = $this->get_in_page_num();

        $ret_list = $this->t_qingjia->get_list($page_num,$adminid,$start_time,$end_time);
        foreach ($ret_list["list"] as &$item) {
            E\Eqingjia_type::set_item_value_str($item,"type");
            \App\Helper\Utils::unixtime2date_for_item($item,"add_time");
            \App\Helper\Utils::unixtime2date_for_item($item,"start_time","", "Y-m-d H");
            \App\Helper\Utils::unixtime2date_for_item($item,"end_time", "", "Y-m-d H");
            $hour_count=$item["hour_count"];
            $day_count=floor($hour_count/8);
            $hour_count_tmp=$item["hour_count"]%8;
            $item["hour_count_str"]=" $day_count 天 $hour_count_tmp 小时 ";
            \App\Helper\Common::set_item_enum_flow_status($item);
        }
        return $this->pageView(__METHOD__,$ret_list);
    }

    public function qingjia_add( ){
        $adminid    = $this->get_account_id();
        $msg        = $this->get_in_str_val("msg");
        $type       = $this->get_in_int_val("type");
        $start_time = $this->get_in_start_time_from_str();
        $end_time   = $this->get_in_end_time_from_str_next_day();
        $hour_count = $this->get_in_int_val("hour_count");

        $this->t_qingjia->start_transaction();
        $this->t_qingjia->row_insert([
            "adminid"    => $adminid,
            "add_time"   => time(NULL),
            "type"       => $type,
            "start_time" => $start_time,
            "end_time"   => $end_time,
            "hour_count" => $hour_count,
            "msg"        => $msg,
        ]);
        $id= $this->t_qingjia->get_last_insertid();
        //审批流程
        $ret=$this->t_flow->add_flow(E\Eflow_type::V_QINGJIA,$adminid,$msg, $id );
        if (!$ret) {
            $this->t_qingjia->rollback();
            return $this->output_err("你的上级不存在,不能提交休假!");
        }
        $this->t_qingjia->commit();
        return $this->output_succ();
    }

    public function qingjia_del() {
        $id      = $this->get_in_id();
        $adminid = $this->get_account_id();

        $del_flag=$this->t_flow->flow_del_by_from_key_int( $adminid, E\Eflow_type::V_QINGJIA, $id);
        if ($del_flag) {
            $this->t_qingjia->row_delete($id);
        }
        return $this->output_bool_ret($del_flag);
    }

    public function flow_list(){
        list($start_time,$end_time)=$this->get_in_date_range(-60,0);
        $adminid         = $this->get_account_id();

        if($adminid == 1118){ // 孙瞿
            $adminid = 684;
        }

        $post_adminid    = $this->get_in_int_val("post_adminid",-1);
        $flow_type       = $this->get_in_int_val("flow_type",-1, E\Eflow_type::class );
        $page_info    = $this->get_in_page_info();
        $page_type= $this->get_in_int_val("page_type", -1);

        $flow_check_flag = $this->get_in_el_flow_check_flag();
        $node_type= -1 ;
            /*
            0 => "未审核",
            1 => "通过",
            2 => "不通过"
            3 => "驳回",
            4 => "自动通过",
            5 => "转审",
            */


        $ret_info=$this->t_flow_node->get_list($page_info,$page_type,$start_time,$end_time,$adminid,$post_adminid,$flow_type,$flow_check_flag, $node_type);
        foreach( $ret_info["list"] as &$item) {
            E\Eflow_type::set_item_value_str($item);
            $flow_class=\App\Flow\flow::get_flow_class($item["flow_type"]);
            $item["node_name"]=$flow_class::get_node_name($item["node_type"]);
            $this->cache_set_item_account_nick($item, "post_adminid","post_admin_nick");
            $item["line_data"] =$flow_class::get_line_data($item["from_key_int"] ,$item["from_key_str"],$item["from_key2_int"] );

            E\Eflow_check_flag::set_item_value_str($item);
            \App\Helper\Common::set_item_enum_flow_status($item);
            \App\Helper\Utils::unixtime2date_for_item($item,"add_time");
            \App\Helper\Utils::unixtime2date_for_item($item,"check_time");
            \App\Helper\Utils::unixtime2date_for_item($item,"post_time");
        }
        return $this->pageView(__METHOD__,$ret_info);
    }

    public function flow_table_data()
    {
        $flowid    = $this->get_in_int_val("flowid");
        $flow_type = $this->get_in_int_val("flow_type");
        if (!$flowid) {
            return $this->output_err("没有数据");
        }
        if (!$flow_type) {
            $flow_type= $this->t_flow->get_flow_type($flowid);
        }

        $flow_class = \App\Flow\flow::get_flow_class($flow_type);
        $table_data = $flow_class::get_table_data($flowid);
        $ret_info   = $this->t_flow_node->get_node_list($flowid,"asc");

        foreach ($ret_info["list"] as &$item) {
            \App\Helper\Utils::unixtime2date_for_item($item,"check_time");
            $this->cache_set_item_account_nick($item);
            E\Eflow_check_flag::set_item_value_str($item);
            $item["node_name"]=$flow_class::get_node_name($item["node_type"]);
        }

        return $this->output_succ(["table_data"=>$table_data,"node_list"=>$ret_info["list"] ]);
    }

    public function flow_node_set_check_flag() {
        $nodeid          = $this->get_in_int_val("nodeid");
        $flow_check_flag = $this->get_in_int_val("flow_check_flag",1 );
        $check_msg       = $this->get_in_str_val("check_msg" );

        $node_info  = $this->t_flow_node->field_get_list($nodeid,"*");
        $flowid     = $node_info["flowid"] ;
        $flow_info  = $this->t_flow->field_get_list($flowid,"*");
        $flow_type  = $flow_info["flow_type"];
        if($this->get_account_id() != $node_info["adminid"] ) {
            return $this->output_err("你不是审核者!");
        }

        if ($flow_check_flag== E\Eflow_check_flag::V_PASS ) {
            $ret=\App\Flow\flow_base::do_flow_pass($nodeid,$flow_check_flag, $check_msg );
            \App\Helper\Utils::logger(" james_shengpi: $ret");

            if($ret) {
                \App\Helper\Utils::logger("flow_type ".$flow_info['flow_type']);
                if($flow_info['flow_type'] == E\Eflow_type::V_AGENT_MONEY_EX_EXAMINE){
                    $agent_money_ex = $this->t_agent_money_ex->field_get_list($flow_info['from_key_int'],'*');
                    //@desn:添加收入记录到优学优享收入记录表
                    $this->t_agent_income_log->row_insert([
                        'agent_income_type' => E\Eagent_income_type::V_ACTIVITY_INCOME,
                        'money' => $agent_money_ex['money'],
                        'agent_id' => $agent_money_ex['agent_id'],
                        'agent_money_ex_id' => $flow_info['from_key_int'],
                        'create_time' => time(NULL)
                    ]);
                }
                return $this->output_succ();
            }else{
                return $this->output_err("下个审批人不存在");
            }

        } else if ($flow_check_flag== E\Eflow_check_flag::V_NO_PASS ) {
            $this->t_flow_node->set_check_info($nodeid,$flow_check_flag,0);
            $this->t_flow->set_flow_status($flowid, E\Eflow_status::V_NO_PASS);
            return $this->output_succ();
        }else{
            return $this->output_err("未支持flag:$flow_check_flag");
        }


    }
    public function ssh_login() {
        $account = $this->get_account();
        session(["debug_flag" => true ]);
        if ( \App\Helper\Utils::check_env_is_release() ) {
            $ip="118.190.115.161";
            \App\Helper\Common::redis_set("SSH_LOGIN_TIME_$account",time(NULL));
            return $this->output_succ( [
                "ssh_cmd"  => "ssh -l$account $ip "
            ]);
        }else{
            return $this->output_err("内网不能用" ) ;
        }
    }
    public function todo_list() {
        list($start_time,$end_time) = $this->get_in_date_range_day(0);
        $todo_type= $this->get_in_el_todo_type();
        $todo_status= $this->get_in_el_todo_status();
        $adminid= $this->get_account_id();
        //$adminid=-1;
        $page_info= $this->get_in_page_info();

        $ret_info=$this->t_todo->get_list( $page_info, $adminid , $todo_type  ,$todo_status,  $start_time, $end_time );
        foreach( $ret_info["list"]  as &$item ) {
            $todo_type=$item["todo_type"];
            E\Etodo_type::set_item_value_str($item);
            E\Etodo_status::set_item_value_color_str($item);
            $msg_arr=\App\Helper\Utils::json_decode_as_array($item["msg"] );
            $item["line_info"]=@$msg_arr[0];
            //$item["jump_url"]=@$msg_arr[1];
            \App\Helper\Utils::unixtime2date_for_item($item,"start_time");
            \App\Helper\Utils::unixtime2date_for_item($item,"end_time");
            \App\Helper\Utils::unixtime2date_for_item($item,"create_time");
        }

        $assistantid = $this->t_assistant_info->get_assistantid( $this->get_account());

        $assign_lesson_count = $this->t_assistant_info->get_assign_lesson_count($assistantid);
        $this->set_filed_for_js("assign_lesson_count",$assign_lesson_count/100);
        $account_role = $this->t_manager_info->get_account_role($adminid);

        return $this->pageView(__METHOD__,$ret_info,[
            "account_role"        =>$account_role
        ]);

    }

    //添加回访
    public function todo_add(){
        $next_revisit_time = $this->get_in_start_time_from_str("","start_time");
        $userid            = $this->get_in_userid();

        $todo_type     = 1001;
        $adminid       = $this->get_account_id();
        $from_key_int  = $userid;
        $from_key2_int = $next_revisit_time;
        $ret=\App\Todo\todo_base::add($todo_type,$next_revisit_time,$next_revisit_time+7200,$adminid,
                                      $from_key_int,$from_key2_int);
        return $this->output_succ();

    }

    public function self_menu_list() {
        $adminid= $this->get_account_id();
        $list=$this->t_admin_self_menu->get_list($adminid);
        return $this->pageView(__METHOD__, \App\Helper\Utils::list_to_page_info($list) );
    }


    public function self_menu_add() {
        $url   = $this->get_in_str_val("url");
        $title = $this->get_in_str_val("title");
        $icon  = $this->get_in_str_val("icon");

        $adminid=$this->get_account_id();
        $row=$this->t_admin_self_menu->get_url_info($adminid,$url);
        if ($row) {
            return $this->output_err("已经存在：".$row["title"]);
        }
        $this->t_admin_self_menu->add($adminid,$title,$url,$icon);
        (new login() )->reset_power($this->get_account() );
        return $this->output_succ();
    }

    public function  self_menu_switch() {
        $next_flag = $this->get_in_int_val("next_flag");
        $order_index= $this->get_in_int_val("order_index");
        $adminid = $this->get_account_id();
        $order_index2=$this->t_admin_self_menu->get_next_order_index($adminid,$order_index,$next_flag);
        $id1= $this->t_admin_self_menu->get_id_by_admin_order_index($adminid,$order_index);
        $id2= $this->t_admin_self_menu->get_id_by_admin_order_index($adminid,$order_index2);

        if ($order_index2) {
            $this->t_admin_self_menu->switch_order_index($id1,$id2);
        }
        return $this->output_succ();
    }


    public function  self_menu_del() {
        $adminid = $this->get_account_id();
        $id      = $this->get_in_int_val("id");
        $this->t_admin_self_menu->del($adminid,$id);
        return $this->output_succ();

    }
    public function upload_face_pic(){
        //header("Content-type: image/jpeg");
        $adminid= $this->get_account_id();
        $ret_info = $this->t_manager_info->get_show_manage_info($adminid);
        return $this->pageView(__METHOD__,null,[
                "ret_info" => @$ret_info,
        ]);
    }

    public function set_manager_face(){
        $uid = $this->get_in_int_val("uid");
        $face = $this->get_in_str_val("face");
        $domain = config('admin')['qiniu']['public']['url'];
        $face = $domain.'/'.$face;

        //$origin_pic = "http://7u2f5q.com2.z0.glb.qiniucdn.com/fdc4c3830ce59d611028f24fced65f321504755368876.png";
        $origin_pic = $face;
        $filename = pathinfo($origin_pic);
        $extension = $filename['extension'];
        $filename = "/tmp/".$filename['filename']."test".".".$extension;
        if($extension == "jpg"){
            $imagecreatefrom = "imagecreatefromjpeg";
            $image  = "imagejpeg";
        }else{
            $imagecreatefrom = "imagecreatefrom".$extension;
            $image  = "image".$extension;
        }

        $width = 750;
        $height = 750;
        // 计算缩放比例
        $info = getimagesize($origin_pic);
        $calc = min($width / $info[0], $height / $info[1]);

        $dim = $imagecreatefrom($origin_pic);
        // 创建缩略画布
        $tim = imagecreatetruecolor($width, $height);
        // 创建白色填充缩略画布
        $white = imagecolorallocate($tim, 255, 255, 255);
        // 填充缩略画布
        imagefill($tim, 0, 0, $white);

        $dwidth = (int)$info[0] * $calc;
        $dheight = (int)$info[1] * $calc;
        $paddingx = (int)($width - $dwidth) / 2;
        $paddingy = (int)($height - $dheight) / 2;
        imagecopyresampled($tim,$dim,$paddingx,$paddingy,
                           0, 0,
                           $dwidth, $dheight,
                           $info[0], $info[1]);
        //imagepng($tim);
        //$dim = imagecreatefrompng($tim);
        $bg_pic     = "http://7u2f5q.com2.z0.glb.qiniucdn.com/0d26a106be32a52a51fd61d57133deff1504766326652.png";
        //dd($image_bg);
        $image_bg = imagecreatefrompng($bg_pic);
        imagecopymerge($tim,$image_bg, 0, 557, 0, 0, 750, 193, 100);

        $image($tim, $filename);
        $file_name = \App\Helper\Utils::qiniu_upload($filename);

        if($file_name!=''){
            $cmd_rm = "rm ".$filename;

            \App\Helper\Utils::exec_cmd($cmd_rm);
        }
        imagedestroy($image_bg);
        imagedestroy($tim);
        imagedestroy($dim);

        $face = "http://7u2f5q.com2.z0.glb.qiniucdn.com/".$file_name;
        $this->t_manager_info->field_update_list($uid,[
            "face_pic" => $face,
        ]);

        $phone     = $this->t_manager_info->get_phone($uid);
        $account_role = $this->get_account_role();
        //如果cc，cr上传头像，也需更新其老师账号的头像
        if(in_array($account_role,[E\Eaccount_role::V_1,E\Eaccount_role::V_2])){
            $teacherid = $this->t_teacher_info->get_teacherid_by_phone($phone);
            if($teacherid){
                $this->t_teacher_info->field_update_list($teacherid,[
                    "face" => $face,
                ]);
            }
        }

        $_SESSION['face_pic'] = "http://7u2f5q.com2.z0.glb.qiniucdn.com/".$file_name;
        return $this->output_succ();
    }

    public function set_group_img(){
        $adminid = $this->get_account_id();
        $face = $this->get_in_str_val("face");
        $domain = config('admin')['qiniu']['public']['url'];
        $face = $domain.'/'.$face;
        $origin_pic = $face;
        $filename = pathinfo($origin_pic);
        $extension = $filename['extension'];
        $filename = "/tmp/".$filename['filename']."test".".".$extension;
        if($extension == "jpg"){
            $imagecreatefrom = "imagecreatefromjpeg";
            $image  = "imagejpeg";
        }else{
            $imagecreatefrom = "imagecreatefrom".$extension;
            $image  = "image".$extension;
        }
        $width = 750;
        $height = 750;
        // 计算缩放比例
        $info = getimagesize($origin_pic);
        $calc = min($width / $info[0], $height / $info[1]);
        $dim = $imagecreatefrom($origin_pic);
        // 创建缩略画布
        $tim = imagecreatetruecolor($width, $height);
         // 创建白色填充缩略画布
        $white = imagecolorallocate($tim, 255, 255, 255);
          // 填充缩略画布
        imagefill($tim, 0, 0, $white);

        $dwidth = (int)$info[0] * $calc;
        $dheight = (int)$info[1] * $calc;
        $paddingx = (int)($width - $dwidth) / 2;
        $paddingy = (int)($height - $dheight) / 2;
        imagecopyresampled($tim,$dim,$paddingx,$paddingy,
                           0, 0,
                           $dwidth, $dheight,
                           $info[0], $info[1]);
        // $bg_pic     = "http://7u2f5q.com2.z0.glb.qiniucdn.com/0d26a106be32a52a51fd61d57133deff1504766326652.png";
        // $image_bg = imagecreatefrompng($bg_pic);
        // imagecopymerge($tim,$image_bg, 0, 557, 0, 0, 750, 193, 100);
        $image($tim, $filename);
        $file_name = \App\Helper\Utils::qiniu_upload($filename);
        if($file_name!=''){
            $cmd_rm = "rm ".$filename;
            \App\Helper\Utils::exec_cmd($cmd_rm);
        }
        // imagedestroy($image_bg);
        imagedestroy($tim);
        imagedestroy($dim);
        $group_img = "http://7u2f5q.com2.z0.glb.qiniucdn.com/".$file_name;
        $group_img = str_replace(' ','',$group_img);
        $ret = $this->t_admin_group_name->update_group_img_by_master_adminid($adminid,$group_img);
        return $this->output_succ();
    }

    public function get_self_order_list(){
        dd("暂停");
        $list = $this->t_order_info->get_self_order_list($this->get_account());
        return $this->pageView(__METHOD__,$list);
    }

    public function test() {
        $subject=$this->get_in_el_subject();
        $grade= $this->get_in_el_grade();
        $contract_type=$this->get_in_el_contract_type();
        $query_text=$this->get_in_query_text();
        $userid = $this->get_in_userid(-1);
        list($start_time,$end_time) = $this->get_in_date_range_day(0);

        return $this->pageView(__METHOD__);
    }
    public function get_login_info() {
        return $this->output_succ([
            "account" =>  session("acc"),
            "adminid" =>  session("adminid"),
            "power_list"=> session("power_list"),
            "account_role"=> session("account_role"),
            "menu_html"=> session("menu_html"),
            "face_pic" => session("face_pic"),
        ]);
    }
}
