<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use App\Helper\Utils;
use Illuminate\Support\Facades\Cookie ;

class stu_manage extends Controller
{
    use CacheNick;
    use TeaPower;

    static $page_self_view_data=[];
    var $sid;

    public function index(){
        $sid    = $this->get_in_int_val("sid");
        $ret_db = $this->t_student_info->get_stu_all_info($sid);

        if ($ret_db === false) {
            return $this->output_err("出错") ;
        }
        if ($ret_db["parentid"]==0) {
            $ret_db["parent_phone"] ="";
        }
        if($ret_db['face'] == ""){
            $face = "/images/header_img.jpg";
        }else{
            $face = $ret_db['face'];
        }

        $aid = $ret_db["assistantid"];
        $ass_admind = $this->t_manager_info->get_ass_adminid($aid);
        $ass_group_info = $this->t_admin_group_name->get_group_id_by_aid($ass_admind);

        if ($ass_group_info) {
            $master_adminid_name = $this->t_manager_info->get_ass_master_nick($ass_group_info['master_adminid']);
            $ret_db['group_name'] = $ass_group_info['group_name'];
            $ret_db['master_adminid_name'] = $master_adminid_name;
       }

        $ret_channel=$this->t_user_info->get_reg_channel($ret_db['userid']);
        $student_info = array(
            'master_adminid_name'   => @$ret_db['master_adminid_name'],
            'group_name'        => @$ret_db['group_name'],
            'userid'            => $ret_db['userid'],
            'origin'            => E\Estu_origin::get_desc($ret_db['originid']),
            'nick'              => trim($ret_db['nick']),
            'face'              => trim($face),
            'reg_time'          => \App\Helper\Utils::unixtime2date($ret_db["reg_time"]) ,
            'praise'            => $ret_db['praise'],
            'birth'             => $ret_db['birth'],
            'phone'             => trim($ret_db['phone']),
            'gender'            => $ret_db['gender'],
            'gender_str'        => E\Egender::get_desc( $ret_db['gender']),
            'grade'             => $ret_db['grade'],
            'realname'          => $ret_db['realname'],
            'stu_email'         => $ret_db['stu_email'],
            'grade_str'         => E\Egrade::get_desc( $ret_db['grade'] ),
            'type'              => $ret_db['type'],
            'parent_name'       => $ret_db['parent_name'],
            'parentid'       => $ret_db['parentid'],
            'parent_type'       => $ret_db['parent_type'],
            'parent_wx_openid'       => $ret_db['parent_wx_openid']?"已绑定":"未绑定",
            'parent_type_str'   => E\Erelation_ship::get_desc( $ret_db['parent_type']),
            'address'           => $ret_db['address'],
            'school'            => $ret_db['school'],
            'textbook'          => E\Eregion_version::get_desc($ret_db['editionid']),
            'editionid'         => $ret_db['editionid'],
            'region'            => $ret_db['region'],
            'parent_phone'      => trim($ret_db['parent_phone']),
            'stu_phone'         => trim($ret_db['stu_phone']),
            'reg_channel'       => $ret_channel,
            'user_agent'        => $ret_db['user_agent'],
            'guest_code'        => $ret_db['guest_code'],
            'host_code'         => $ret_db['host_code'],
            'init_info_pdf_url' => $ret_db['init_info_pdf_url'],
            'assistant_nick'    => $this->cache_get_assistant_nick($ret_db["assistantid"]) ,
            'seller_admin_nick' => $this->cache_get_account_nick( $ret_db["seller_adminid"]),
            'seller_phone' => $this->t_manager_info->get_phone(  $ret_db["seller_adminid"] ),
            'is_test_user'      => $ret_db['is_test_user'],
            'province'          => $ret_db['province'],
            'city'              => $ret_db['city'],
            'area'              => $ret_db['area'],
        );

        $l_1v1_list = [];
        $data_list  = [] ;//$this->t_order_info->get_order_info($sid);
        foreach ( $data_list as $item ){
            $item["teacher_nick"]   = $this->cache_get_teacher_nick($item["teacherid"]);
            $item["assistant_nick"] = $this->cache_get_assistant_nick($item["assistantid"]);
            \App\Helper\Utils::unixtime2date_for_item($item,"pay_time","", "Y-m-d H:i" );
            \App\Helper\Utils::unixtime2date_for_item($item,"order_time","", "Y-m-d H:i" );
            E\Econtract_status::set_item_value_str($item);
            E\Econtract_type::set_item_value_str($item,"course_type");
            $item["lesson_count"] /= 100;
            $item["finish_lesson_count"] /= 100;
            $item["left_lesson_count"] = $item["lesson_count"] - $item ["finish_lesson_count"];
            $l_1v1_list[] = $item;
        }

        //得到小班课信息
        $small_lesson_list=$this->t_small_class_user->get_small_class_list_by_userid ($sid);
        foreach ($small_lesson_list as &$s_item) {
            $s_item["teacher_nick"]=$this->cache_get_teacher_nick($s_item["teacherid"]);
            $s_item["assistant_nick"]=$this->cache_get_assistant_nick($s_item["assistantid"]);
        }

        $open_lesson_list=$this->t_open_lesson_user->get_open_list_from_userid($sid);

        foreach ($open_lesson_list as &$o_item) {
            $o_item["teacher_nick"]   = $this->cache_get_teacher_nick($o_item["teacherid"]);
            $o_item["assistant_nick"] = $this->cache_get_assistant_nick($o_item["assistantid"]);
            $o_item["lesson_time"]    = Utils::fmt_lesson_time( $o_item["lesson_start"] ,$o_item["lesson_end"]);
        }

        //科目教材
        $subject_textbook_list = $this->t_student_subject_list->get_info_by_userid($sid);
        foreach($subject_textbook_list  as &$item_oo){
            $item_oo["editionid_str"] =  E\Eregion_version::get_desc ($item_oo["editionid"]);
            $item_oo["subject_str"] =  E\Esubject::get_desc ($item_oo["subject"]);
        }

        return $this->pageView(__METHOD__,null,[
            "stu_info"          => $student_info,
            "l_1v1_list"        => $l_1v1_list,
            "small_lesson_list" => $small_lesson_list,
            "open_lesson_list"  => $open_lesson_list,
            "subject_textbook_list"=>$subject_textbook_list
        ] );
    }

    public function __construct() {
        parent::__construct();
        $this->sid=$this->get_in_sid();
        static::$page_self_view_data["_sid"]= $this->sid;
        static::$page_self_view_data["_stu_nick"]= $this->cache_get_student_nick($this->sid);
    }

    public function lesson_plan_edit() {
        $account_role = $this->get_account_role();
        $all_flag     = $this->get_in_int_val("all_flag",0);
        $order_list   = $this->t_course_order->get_order_list($this->sid);
        $courseid     = -1;
        $course_list  = array();
        foreach ($order_list as &$o_item  ) {
            if ($courseid==-1) {
                $courseid=$o_item["courseid"];
            }
            $lesson_total = $o_item["lesson_total"] * $o_item["default_lesson_count"];

            $o_item["left_lesson_count"]=sprintf("%.1f",($lesson_total-$o_item["finish_lesson_count"])/100);
            $o_item["title"] = $this->cache_get_teacher_nick($o_item["teacherid"])."-未上课时:"
                             .$o_item["left_lesson_count"]."-".E\Econtract_type::get_desc($o_item["course_type"])."-"
                             .E\Egrade::get_desc( $o_item["grade"] )."-"
                             .E\Esubject::get_desc( $o_item["subject"] )."总课时:".$lesson_total/100;
            if($o_item["left_lesson_count"]!=0){
                $course_list[]=$o_item;
            }
        }

        $courseid=$this->get_in_courseid($courseid);
        $ret_info= $this->t_lesson_info->get_lessons_available($this->sid,$courseid,$all_flag, 1, 100000);
        foreach ($ret_info["list"] as &$item ) {
            // $item["teacher_nick"] = $this->cache_get_teacher_nick($item["teacherid"]);
            \App\Helper\Utils::unixtime2date_for_item($item,"lesson_start", "_str" );
            \App\Helper\Utils::unixtime2date_for_item($item,"lesson_end", "_str");
            E\Elesson_status::set_item_value_str($item);
            E\Econfirm_flag::set_item_value_str($item);
            E\Egrade::set_item_value_str($item);
            E\Esubject::set_item_value_str($item);
            $item["confirm_admin_nick"] = $this->cache_get_account_nick($item["confirm_adminid"]);
            \App\Helper\Utils::unixtime2date_for_item($item,"confirm_time", "_str");
            $item["lesson_count"] /=100;
            E\Elevel::set_item_value_str($item);
            E\Eteacher_money_type::set_item_value_str($item);
        }
        return $this->pageView(__METHOD__,$ret_info,[
            "course_list"  => $course_list,
        ]);
    }

    public function  set_assistantid() {
        $assistantid=$this->get_in_assistantid();
        $account_role = $this->get_account_role();
        $sys_operator = $this->get_in_str_val("sys_operator","");
        if (!$this->check_account_in_arr(["cora","fly","qichenchong","michael","longyu","kj","foster","jim","eros"])
            && !in_array($account_role,[12])
        ){
            return $this->output_err("没有权限");
        }

        if($assistantid==-1){
            return $this->output_err("助教不能不选");
        }

        $is_test_user = $this->t_student_info->get_is_test_user($this->sid);
        if(!$is_test_user){
            $noti_account = $this->t_assistant_info->get_account_by_id($assistantid);
            $nick = $this->cache_get_student_nick( $this->sid );
            if ($assistantid >0 &&  \App\Helper\Utils::check_env_is_release() ) {
                //通知wx
                //$noti_account=$this->t_assistant_info->get_account_by_id($assistantid);
                $header_msg="有新签学员给你啦!";
                $msg="学生:" . $nick;
                $url="/user_manage/ass_archive_ass";
                $ret=$this->t_manager_info->send_wx_todo_msg($noti_account, $this->get_account() ,$header_msg,$msg ,$url);
                if($ret) {
                }else{
                    return $this->output_err("发送WX通知失败,请确认[$noti_account]有绑定微信");
                }
            }
        }

        $old_ass_assign_time = $this->t_student_info->get_ass_assign_time($this->sid);
        $old_assistantid = $this->t_student_info->get_assistantid($this->sid);
        $old_ass_adminid = $this->t_assistant_info->get_adminid_by_assistand($old_assistantid);

        $this->t_student_info->field_update_list($this->sid,[
            "assistantid"     => $assistantid,
            "ass_assign_time" => time(),
            // "type"            => 0
        ]);

        $new_ass_adminid = $this->t_assistant_info->get_adminid_by_assistand($assistantid);
        $this->t_ass_stu_change_list->row_insert([
            "add_time"  =>time(),
            "userid"    =>$this->sid,
            "assistantid"=>$assistantid,
            "adminid"    =>$new_ass_adminid,
            "assign_ass_time"=>$old_ass_assign_time,
            "old_ass_adminid"=>$old_ass_adminid
        ]);

        // 添加操作日志
        $this->t_user_log->add_data("设置助教,助教id:".$assistantid, $this->sid);

        $this->t_lesson_info->set_user_assistantid( $this->sid,$assistantid  );
        $this->t_course_order->set_user_assistantid( $this->sid,$assistantid  );

        if(!$is_test_user){
            if($sys_operator){
                $seller_adminid = $this->t_manager_info->get_id_by_account($sys_operator);
            }else{
                $seller_adminid = $this->t_seller_student_new->get_admin_revisiterid($this->sid);
                if(!$seller_adminid){
                    $seller_adminid = $this->t_student_info->get_seller_adminid($this->sid);
                }
            }
            $ass_adminid    = $this->t_assistant_info->get_adminid_by_assistand($assistantid);
            $wx_id = $this->t_manager_info->get_wx_id($ass_adminid);
            if($seller_adminid>0){
                if($wx_id){
                   $this->t_manager_info->send_wx_todo_msg_by_adminid ($seller_adminid,"通知人:理优教育","学生分配助教通知","您好,学生".$nick."已经分配给助教".$noti_account."老师,助教微信号为:".$wx_id,"");
                }else{
                    $this->t_manager_info->send_wx_todo_msg_by_adminid ($seller_adminid,"通知人:理优教育","学生分配助教通知","您好,学生".$nick."已经分配给助教".$noti_account."老师,暂无该助教老师微信号","");
                }
            }else{
                $this->t_manager_info->send_wx_todo_msg_by_adminid (349,"通知人:理优教育","学生分配助教通知","您好,学生".$nick."已经分配给助教".$noti_account."老师,助教微信号为:".$wx_id.",未找到对应销售","");
                $phone = $this->t_student_info->get_phone($this->sid);
                $this->t_book_revisit->add_book_revisit(
                    $phone,
                    "学生".$nick."已经分配给助教".$noti_account."老师,助教微信号为:".$wx_id.",未找到对应销售",
                    "system"
                );

            }
        }

        return $this->output_succ();
    }

    public function  set_lesson_teacherid( ){
        $teacherid = $this->get_in_teacherid();
        $lessonid  = $this->get_in_lessonid();
        $db_teacherid=$this->t_lesson_info->get_teacherid($lessonid);
        $lesson_type=$this->t_lesson_info->get_lesson_type($lessonid);
        $status=$this->t_lesson_info->get_lesson_status($lessonid);
        if ($db_teacherid && $lesson_type==2) {
            return $this->output_err(" 试听课 不能修改老师，你可以删除这条记录");
        }

        $teacher_info=$this->t_teacher_info->field_get_list($teacherid,"*");

        if ($status <=1) { //2 end
            $this->t_lesson_info->field_update_list($lessonid,[
                "teacherid"          => $teacherid,
                "teacher_money_type" => $teacher_info["teacher_money_type"],
                "level"              => $teacher_info["level"],
                "lesson_start"       => 0,
                "lesson_end"         => 0,
            ]);
            $this->t_homework_info->field_update_list($lessonid,[
                "teacherid"  =>$teacherid,
            ]);
        }
        return outputjson_success();
    }

    public function lesson_account() {
        $sid               = $this->get_in_sid();
        $page_num          = $this->get_in_page_num();

        $lesson_account_list = $this->t_user_lesson_account->get_list_by_userid($sid);
        $lesson_account_id=-1;

        $lesson_1v1_price=0;

        foreach( $lesson_account_list as &$la_item){
            if ($lesson_account_id==-1 ) {
                $lesson_account_id=$la_item["lesson_account_id"];
            }

            $la_item["add_time"]= \App\Helper\Utils::unixtime2date($la_item["add_time"]);
            $la_item["left_lesson_count"]= sprintf("%.01f", $la_item["left_money"] /$la_item["lesson_1v1_price"]);
            $la_item["left_money"]= $la_item["left_money"] /100;
            if ($lesson_account_id== $la_item["lesson_account_id"] ) {
                $lesson_1v1_price=$la_item["lesson_1v1_price"];

            }

        }

        $lesson_account_id = $this->get_in_int_val('lesson_account_id',$lesson_account_id);

        $ret_info=$this->t_user_lesson_account_lesson-> get_list($lesson_account_id,$page_num);

        foreach( $ret_info["list"] as &$lal_item){
            $lal_item["lesson_count"]= sprintf("%.1f", $lal_item["price"]/ $lesson_1v1_price );
            $lal_item["real_lesson_count"]= sprintf("%.1f", $lal_item["real_price"]/ $lesson_1v1_price );

            $lal_item["price"]/=100;
            $lal_item["real_price"]/=100;
            $lal_item["lesson_time"]=Utils::fmt_lesson_time(
                $lal_item["lesson_start"],
                $lal_item["lesson_end"]);

            E\Elesson_status::set_item_value_str($lal_item);
            $lal_item["teacher_nick"]= $this->cache_get_teacher_nick($lal_item["teacherid"]);
            $lal_item["assistant_nick"]= $this->cache_get_assistant_nick($lal_item["assistantid"]);

        }
        return $this->pageView(__METHOD__,$ret_info,["lesson_account_list" => $lesson_account_list ]);
    }

    public function lesson_account_log() {
        $sid               = $this->get_in_int_val('sid',-1);
        $page_num          = $this->get_in_page_num();
        $lesson_account_list = $this->t_user_lesson_account->get_list_by_userid($sid);
        $lesson_account_id=-1;

        $lesson_1v1_price=0;
        foreach( $lesson_account_list as &$la_item){
            if ($lesson_account_id==-1 ) {
                $lesson_account_id=$la_item["lesson_account_id"];
            }

            $la_item["add_time"]= \App\Helper\Utils::unixtime2date($la_item["add_time"]);
            $la_item["left_lesson_count"]= sprintf("%.01f", $la_item["left_money"] /$la_item["lesson_1v1_price"]);
            $la_item["left_money"]= $la_item["left_money"] /100;
            if ($lesson_account_id== $la_item["lesson_account_id"] ) {
                $lesson_1v1_price=$la_item["lesson_1v1_price"];

            }

        }

        $lesson_account_id = $this->get_in_int_val('lesson_account_id',$lesson_account_id);

        if ($lesson_account_id >0) {
            $ret_info=$this->t_user_lesson_account_log->  get_list_by_lesson_account_id($lesson_account_id,$page_num);
        }
        $field_config=[
            "admin"=>"操作者",
            "old_real_price"=>["原先价格",function($v){

            }],
            "new_real_price"=>"现在价格",
            "old_price"=>"原先价格",
            "new_price"=>"现在价格",
            "reason"=>"原因",
            "info"=>"信息",
            "lesson_1v1_price" =>"1v1 价格",
        ];

        foreach( $ret_info["list"] as &$lal_item){
            $lal_item["add_time"]= Utils::unixtime2date($lal_item["add_time"]);
            $reason=$lal_item["reason"];

            $info=Utils::json_decode_as_array($lal_item["info"]);
            $arr=[];
            foreach ($info as $k=>$v) {
                $field_name=$k;
                if (isset($field_config[$k])) {
                    $field_name= $field_config[$k];
                }
                $arr[]="$field_name:".$v;
            }
            $lal_item["msg"]=join("<br/>",$arr );

            Euser_lesson_account_reason::set_item_value_str($lal_item,"reason");
            $lal_item["left_money"]= $lal_item["left_money"] /100;
            $lal_item["modify_money"]= $lal_item["modify_money"] /100;

        }

        $this->assign("lesson_account_list",$lesson_account_list);
        $this->setPageData($ret_info);
        $this->display(__METHOD__);
    }

    public function lesson_evaluation(){
        $start_time = $this->get_in_str_val('start_time',date('Y-m-d', time()-86400*7));
        $end_time   = $this->get_in_str_val('end_time',date('Y-m-d', time()+86400));

        $start_time_s = strtotime($start_time);
        $end_time_s   = strtotime($end_time);

        $ret_lesson = $this->t_lesson_info->get_lesson_list_info($this->sid,$start_time_s,$end_time_s);

        foreach($ret_lesson as &$value){
            $tea_nick             = $this->t_teacher_info->get_nick($value['teacherid']);
            $stu_nick             = $this->t_student_info->get_nick($value['userid']);
            $value['tea_nick']    = $tea_nick;
            $value['stu_nick']    = $stu_nick;
            $value['lesson_time'] = date('Y-m-d H:i',$value['lesson_start'])."-".date('H:i',$value['lesson_end']);
            $value['lesson_num']  = '第'.$value['lesson_num'].'次课';
            $value['stu_intro']   = json_decode($value['stu_performance'],true);
            $value['stu_point_performance']='';
            if(isset($value['stu_intro']['point_note_list']) && is_array($value['stu_intro']['point_note_list'])){
                foreach(@$value['stu_intro']['point_note_list'] as $val){
                    $value['stu_point_performance'].=$val['point_name'].":".$val['point_stu_desc']."。";
                }
            }
            $value["stu_comment"] = $this->get_test_lesson_comment_str($value["stu_comment"],1);
            if(isset($value['stu_intro']['stu_comment']) && $value['stu_intro']['stu_comment']!=''){
                if(is_array($value['stu_intro']['stu_comment'])){
                    $str = json_encode($value['stu_intro']['stu_comment']);
                    $str = $this->get_test_lesson_comment_str($str);
                }else{
                    $str = $value['stu_intro']['stu_comment'];
                }
                //   $str = $this->get_test_lesson_comment_str($str);
                $value['stu_point_performance'].=PHP_EOL."总体评价:".$str;
            }
        }
        //dd($ret_lesson);


        $args=array(
            "start_time" => $start_time,
            "end_time"   => $end_time,
        );
        $js_values_str=$this->get_js_g_args($args);

        return $this->view(__METHOD__,[
            "sel_item_id"     => 12,
            "table_data_list" => $ret_lesson,
            "js_values_str"   => $js_values_str,
        ] );
    }

    public function get_stu_parent(){
        $studentid = $this->get_in_int_val("studentid",0);

        $parentid = $this->t_student_info->get_parentid($studentid);
        $phone    = $this->t_parent_info->get_phone($parentid);
        $parent_name    = $this->t_student_info->get_parent_name($studentid);
        $parent_type   = $this->t_student_info->get_parent_type($studentid);

        return outputjson_success(array('phone'=>$phone,'parent_type'=>$parent_type,'parent_name'=>$parent_name));
    }

    public function set_stu_parent(){
        $studentid = $this->get_in_int_val("studentid",0);
        $phone     = trim($this->get_in_int_val("phone",0));
        $parent_name     = trim($this->get_in_str_val("parent_name",""));
        $parent_type     = $this->get_in_int_val("parent_type",0);

        $parent_info = $this->t_parent_info->get_parentid_by_phone($phone);
        $parentid    = $parent_info['parentid'];
        if(!$parent_name){
            $parent_name = $phone;
        }

        if($parentid==0){
            $fail_flag = 0;
            $this->t_user_info->start_transaction();
            $parentid = $this->t_user_info->user_reg(md5("123456"),"后台注册",0);
            if($parentid){
                $ret = $this->t_phone_to_user->row_insert([
                    "phone"  => $phone,
                    "role"   => E\Erole::V_PARENT,
                    "userid" => $parentid,
                ],true);

                if($ret){
                    $parent_ret = $this->t_parent_info->row_insert([
                        "parentid"           => $parentid,
                        "nick"               => $parent_name,
                        "phone"              => $phone,
                        "last_modified_time" => \App\Helper\Utils::unixtime2date(time()),
                    ]);
                    if(!$parent_ret){
                        $fail_flag=1;
                    }
                    $this->t_parent_info->commit();
                }else{
                    $fail_flag=1;
                }
            }else{
                $fail_flag=1;
            }

            if($fail_flag){
                $this->t_phone_to_user->roll_back();
                return $this->output_err("生成家长账号失败!请重试!");
            }
        }else{
            \App\Helper\Utils::logger("parentid".$parentid);
            $this->t_parent_info->field_update_list($parentid,[
               "nick"  =>$parent_name
            ]);
            $this->t_student_info->field_update_list($studentid,[
                "parentid"    => $parentid,
                "parent_name" => $parent_name,
                "parent_type" => $parent_type
            ]);
            $check_flag = $this->t_parent_child->check_has_parent($parentid,$studentid);
            if($check_flag){
                $this->t_parent_child->update_parent_type($parentid,$studentid,$parent_type);
                //$this->t_parent_info->send_wx_todo_msg($parentid,"确认课时","sadfaafa  ","ttt1");
                return outputjson_error("用户已绑定！");
            }
        }

        if($parentid>0){
            $bind_fail_flag=0;
            $this->t_parent_child->start_transaction();
            $ret_info = $this->t_parent_child->set_student_parent($parentid,$studentid,$parent_type);

            //查询该手机对应的帐号是否为家长登录帐号
            if (!$this->t_phone_to_user->get_info_by_userid($parentid)) {
                $this->t_phone_to_user->set_userid($phone,"parent",$parentid);
            }



            if($ret_info){
                $parent_name = $this->t_parent_info->get_nick($parentid);
                $ret_info    = $this->t_student_info->field_update_list($studentid,[
                    "parentid"    => $parentid,
                    "parent_name" => $parent_name,
                    "parent_type" => $parent_type
                ]);
            }else{
                \App\Helper\Utils::logger(" bind_fail_flag 22 ");
                $bind_fail_flag = 1;
            }

            if($bind_fail_flag){
                $this->t_parent_child->rollback();
                return  $this->output_err("绑定关系失败！");
            }else{
                $this->t_parent_child->commit();

                // 添加操作日志
                $this->t_user_log->add_data("添加家长,家长id".$parentid, $studentid);
                return $this->output_succ();
            }
        }
    }

    public function parent_list(){
        return $this->output_succ();
        $start_date = $this->get_in_str_val('start_date',date('Y-m-d', time(NULL)-30*86400 ));
        $end_date   = $this->get_in_str_val('end_date',date('Y-m-d', time(NULL)+86400 ));
        $origin = trim($this->get_in_str_val('origin',""));
        $origin_ex = trim($this->get_in_str_val('origin_ex',""));
        $start_time = strtotime($start_date);
        $end_time   = strtotime($end_date)+86400;
        $page_num   = $this->get_in_page_num();
        //得到渠道列表

        $ret_info=$this->t_seller_student_info->get_channel_statistics( $page_num,$start_time,$end_time,$origin ,$origin_ex );

        $ret_info["list"]= $this->gen_origin_data($ret_info["list"]);

        $check_power_flag=self::check_power(E\Epower::V_TONGJI_SHOW_MONEY);
        foreach( $ret_info["list"] as &$f_item) {
            if ($check_power_flag) {
                $f_item["money_all"]/=100;
                $f_item["first_money"]/=100;
            }else{
                $f_item["money_all"]="--";
                $f_item["first_money"]="--";
            }
        }
        return $this->pageView(__METHOD__,$ret_info);
    }

    public function get_stu_lesson_left(){
        $userid   = $this->get_in_int_val("userid");
        $courseid = $this->get_in_int_val("courseid");

        $lesson_left=0;
        if($courseid==0){
            $lesson_total = $this->t_order_info->get_user_lesson_total($userid);
            $assigned_lesson_count=$this->t_course_order->get_user_assigned_lesson_count($userid);
            $lesson_cost  = $this->t_course_order->get_user_lesson_cost($userid);
            $lesson_left  = $lesson_total-$lesson_cost+$assigned_lesson_count;
        }elseif($courseid>0){
            $lesson_total = $this->t_course_order->get_user_lesson_total($courseid);
            $assigned_lesson_count=$this->t_course_order->get_assigned_lesson_count($courseid);
            $lesson_cost  = $this->t_lesson_info->get_user_lesson_cost_by_courseid($courseid);
            $lesson_left  = $lesson_total-$assigned_lesson_count/100-$lesson_cost;
        }
        return $lesson_left;
    }

    public function add_course_order_for_stu(){
        $userid               = $this->get_in_int_val("userid");
        $teacherid            = $this->get_in_int_val("teacherid");
        $courseid             = $this->get_in_int_val("courseid");
        $subject              = $this->get_in_int_val("subject");
        $lesson_total         = $this->get_in_int_val("lesson_total");
        $default_lesson_count = $this->get_in_int_val("default_lesson_count");

        $ret=0;
        if($courseid>0){
            $assigned=$this->t_course_order->get_assigned_lesson_count($courseid);
            $assigned_lesson_count=$lesson_total*$default_lesson_count+$assigned;
            $ret=$this->t_course_order->field_update_list($courseid,[
                "assigned_lesson_count"=>$assigned_lesson_count
            ]);
            if(!$ret)
                return outputjson_error("添加错误，请重试");
        }
        $stu_info = $this->t_student_info->get_student_simple_info($userid);
        $ret      = $this->t_course_order->row_insert([
            "userid"               => $userid,
            "teacherid"            => $teacherid,
            "subject"              => $subject,
            "grade"                => $stu_info['grade'],
            "assistantid"          => $stu_info['assistantid'],
            "lesson_total"         => $lesson_total,
            "default_lesson_count" => $default_lesson_count,
        ]);

        if(!$ret)
            return outputjson_error("添加错误，请重试");
        return outputjson_success();
    }

    public function get_lesson_simple_info(){
        $lessonid = $this->get_in_int_val("lessonid");
        $grade    = $this->get_in_int_val("grade");
        $subject  = $this->get_in_int_val("subject");
        $type     = $this->get_in_str_val("type");

        if($type=='get'){
            $ret_info = $this->t_lesson_info->field_get_list($lessonid,"grade,subject");
            return outputjson_success(array("data"=>$ret_info));
        }else{
            $ret_info = $this->t_lesson_info->field_update_list($lessonid,[
                "grade"   => $grade,
                "subject" => $subject
            ]);
            return outputjson_success();
        }
    }

    public function course_lesson_list () {
        $courseid = $this->get_in_courseid();
        if ($courseid<=0) {
             return $this->stu_error_view([
                "没有课程信息 ",
                " 请从[课程列表] 点击\"排课\"进来 ",
            ]);
        }

        $all_flag = $this->get_in_int_val("all_flag",0);
        $ret_info = $this->t_lesson_info->get_lessons_available($this->sid,$courseid,$all_flag, 1, 100000);
        foreach ($ret_info["list"] as &$item){
            $item["confirm_admin_nick"] = $this->cache_get_account_nick($item["confirm_adminid"]);
            $item["lesson_count"] /=100;
            $item['lesson_diff'] = $item['lesson_end']-$item['lesson_start'];
            \App\Helper\Utils::unixtime2date_for_item($item,"lesson_start","_str" );
            \App\Helper\Utils::unixtime2date_for_item($item,"lesson_end","_str");
            \App\Helper\Utils::unixtime2date_for_item($item,"lesson_cancel_reason_next_lesson_time");
            \App\Helper\Utils::unixtime2date_for_item($item,"confirm_time", "_str");
            E\Elesson_status::set_item_value_str($item);
            E\Econfirm_flag::set_item_value_str($item);
            E\Egrade::set_item_value_str($item);
            E\Esubject::set_item_value_str($item);
            E\Elesson_cancel_reason_type::set_item_value_str($item);
            $item['level_str'] = \App\Helper\Utils::get_teacher_letter_level($item['teacher_money_type'],$item['level']);
            E\Elevel::set_item_value_str($item);
            E\Eteacher_money_type::set_item_value_str($item);
        }
        return $this->pageView(__METHOD__,$ret_info);
    }

    public function regular_course_stu(){
        $teacherid = $this->get_in_int_val('teacherid',-1);
        $userid    = $this->sid;

        $date = \App\Helper\Utils::get_week_range(time(NULL),1);
        $stat_time = $date["sdate"];

        $ret_info = \App\Helper\Utils::list_to_page_info([]);
        return $this->pageView(__METHOD__, $ret_info,["userid"=>$userid]);
    }

    public function course_list() {
        $userid           = $this->sid;
        $competition_flag = $this->get_in_int_val("competition_flag");

        $lesson_total            = $this->t_order_info->get_user_lesson_total($userid,$competition_flag);
        $lesson_refund           = $this->t_order_refund->get_user_lesson_refund($userid,$competition_flag);
        $g_assigned_lesson_count = $this->t_course_order->get_user_assigned_lesson_count($userid,$competition_flag);
        $lesson_split            = $this->t_order_info->get_user_split_total($userid,$competition_flag);
        $lesson_left             = $lesson_total-$lesson_refund-$lesson_split;
        if ($userid<>0) {
            $list = $this->t_course_order->get_list($userid,-1,$competition_flag);
        }else{
            $list = [];
        }

        foreach($list as &$item){
            $assigned_lesson_count          = $item["assigned_lesson_count"];
            $item["left_lesson_count"]      = ($assigned_lesson_count-$item["finish_lesson_count"])/100;
            $item["finish_lesson_count"]    = $item["finish_lesson_count"]/100;
            $item["assigned_lesson_count"]  = $item["assigned_lesson_count"]/100;
            $item["no_finish_lesson_count"] = $item["no_finish_lesson_count"]/100;
            $item["default_lesson_count"]   = $item["default_lesson_count"]/100;
            \App\Helper\Utils::unixtime2date_for_item($item,"add_time","_str");

            $this->cache_set_item_teacher_nick($item);
            $this->cache_set_item_assistant_nick($item);
            E\Ecourse_status::set_item_value_str($item,"course_status");
            E\Egrade::set_item_value_str($item);
            E\Esubject::set_item_value_str($item);
            E\Eboolean::set_item_value_str($item,"enable_video");
            E\Eboolean::set_item_value_str($item,"reset_lesson_count_flag");
            E\Econtract_type::set_item_value_str($item,"course_type");
            if($item['week_comment_num']>0){
                $item['week_comment_num_str']="只用评1节";
            }else{
                $item['week_comment_num_str']="每节课都评";
            }
        }

        $show_flag = 0;
        $is_test_user = $this->t_student_info->check_is_test_user($userid);
        if($is_test_user) {
            $show_flag++;
        }
        $ass_role = $this->get_account_role();
        $ass_id = $this->get_account_id();
        if ($ass_role == 10 || $ass_id == 356 || $ass_id == 1118 ) {
            $show_flag++;
        }

        return $this->pageView(__METHOD__, \App\Helper\Utils::list_to_page_info($list),[
            "lesson_left"            => sprintf("%.2f", $lesson_left),
            "assigned_lesson_count"   => sprintf("%.2f", $g_assigned_lesson_count),
            "unassigned_lesson_count" => sprintf("%.2f", $lesson_left-$g_assigned_lesson_count),
            "show_flag"     => $show_flag,
        ]);
    }

    public function order_lesson_list(){
        $sid              = $this->sid;
        $type             = $this->get_in_int_val("type",1);
        $competition_flag = $this->get_in_int_val("competition_flag");
        $page_num         = $this->get_in_page_num("page_num");

        $order_sum  = $this->t_order_info->get_user_order_list_sum($sid,$competition_flag);
        $lesson_sum = $this->t_lesson_info->get_user_lesson_list_sum($sid,$competition_flag);
        $order_left = $order_sum-$lesson_sum;

        //$lesson_info = $this->get_order_lesson_info_list($sid,$competition_flag,$type);
        $lesson_list = $this->t_order_lesson_list->order_lesson_list($sid,$competition_flag,$page_num);
        foreach($lesson_list['list'] as &$val){
            $val['lesson_time']  = date("Y-m-d H:i",$val['lesson_start'])."-".date("H:i",$val['lesson_end']);
            $val['tea_nick']     = $this->cache_get_teacher_nick($val['teacherid']);
            $val['price']        = $val['price']/100;
            $val['lesson_count'] = $val['lesson_count']/100;
            E\Egrade::set_item_value_str($val,"grade");
            E\Esubject::set_item_value_str($val,"subject");
            E\Econtract_type::set_item_value_str($val,"contract_type");
            E\Econfirm_flag::set_item_value_str($val,"confirm_flag");
        }

        return $this->pageView(__METHOD__, $lesson_list, [
            "lesson_sum" => $lesson_sum,
            "order_left" => $order_left,
        ]);
    }

    public function order_info_list(){
        $sid              = $this->sid;
        $type             = $this->get_in_int_val("type",2);
        // $competition_flag = $this->get_in_int_val("competition_flag");

        // 优先显示 有交接单的课堂类型
        $orderid_arr = $this->t_order_info->get_order_info_by_userid($sid);
        $routine_contract_num     = 0;
        $competition_contract_num = 0;
        foreach($orderid_arr as $item){
            if($item['competition_flag'] == 1){
                $competition_contract_num++;
            }else{
                $routine_contract_num++;
            }
        }

        if($routine_contract_num>=$competition_contract_num){
            $default_competition_flag =0 ;
        }else{
            $default_competition_flag =1 ;
        }

        $competition_flag = $this->get_in_int_val("competition_flag",$default_competition_flag);



        $order_list = $this->t_order_info->order_info_list($sid,$competition_flag);
        foreach($order_list as &$val){
            $val['lesson_total']   = $val['lesson_total']*$val['default_lesson_count']/100;
            $val['order_left']     = $val['lesson_left']/100;

            $val['hand_over_view'] = $this->t_student_cc_to_cr->get_post_time_by_orderid($val['orderid']);

            $val['price']          = $val['price']/100;
            if($val['lesson_total']!= 0){
                $val['per_price'] = round($val['price']/$val['lesson_total'],2);
            }else{
                $val['per_price']=0;
            }
            $val['discount_price'] = $val['discount_price']==0?'':$val['discount_price']/100;
            E\Econtract_type::set_item_value_str($val,'contract_type');
            E\Econtract_status::set_item_value_str($val,'contract_status');
            E\Egrade::set_item_value_str($val,'grade');
            E\Esubject::set_item_value_str($val,'subject');
        }

        return $this->pageView(__METHOD__,\App\Helper\Utils::list_to_page_info($order_list));
    }

    /**
     * @param sid 学生id
     * @param competition_flag 竞赛合同的标志 0 非竞赛 1 竞赛
     * @param type 1 返回课程消耗信息 2 返回合同消耗信息
     * @return 根据type来决定返回值
     * 数据更新后,此接口不再调用
     */
    private function get_order_lesson_info_list($sid,$competition_flag,$type){
        $order_list  = $this->t_order_info->get_user_order_list($sid,$competition_flag);
        $lesson_list = $this->t_lesson_info->get_user_lesson_list($sid,$competition_flag);

        $i                 = 0;
        $left_lesson_count = 0;
        $last_lessonid     = 0;
        $length            = count($lesson_list);
        $order_lesson_list = array();
        foreach($order_list as $key=>&$val){
            $lesson_total = $val['lesson_total']*$val['default_lesson_count']/100;
            $price        = $val['price']/100;
            $val['per_price']     = $lesson_total>0?$price/$lesson_total:0;
            $val['lesson_total']  = $lesson_total;
            $val['contract_type'] = E\Econtract_type::get_desc($val['contract_type']);

            if($lesson_total>0 && $i<$length-1){
                for($lesson_cost=0;$lesson_cost<$lesson_total && $i<=$length-1;$i++){
                    if($last_lessonid!=0){
                        $lesson_list[$i-1]['orderid'] .= ",".$val['orderid'];
                        $lesson_list[$i-1]['lesson_count'] .= " / ".$val['orderid']."扣了".$left_lesson_count."课时";

                        $lesson['orderid']      = $val['orderid'];
                        $lesson['lesson_count'] = $left_lesson_count;
                        $lesson['lessonid']     = $lesson_list[$i-1]['lessonid'];
                        $lesson['per_price']    = $val['per_price']*100;
                        $lesson['price']        = $val['per_price']*$left_lesson_count*100;
                        $order_lesson_list[]    = $lesson;

                        $lesson_cost       += $left_lesson_count;
                        $last_lessonid      = 0;
                        $left_lesson_count  = 0;
                    }

                    $lessonid      = $lesson_list[$i]['lessonid'];
                    $lesson_count  = $lesson_list[$i]['lesson_count']/100;
                    $lesson_time   = date("m-d H:i",$lesson_list[$i]['lesson_start'])."~"
                                   .date("H:i",$lesson_list[$i]['lesson_end']);
                    $lesson_cost  += $lesson_count;

                    $lesson_list[$i]['orderid']      = $val['orderid'];
                    $lesson_list[$i]['lesson_count'] = $lesson_count;
                    $lesson_list[$i]['tea_nick']     = $this->cache_get_teacher_nick($lesson_list[$i]['teacherid']);
                    $lesson_list[$i]['lesson_time']  = $lesson_time;
                    $lesson_list[$i]['price']        = $lesson_count*$val['per_price'];
                    $lesson_list[$i]['grade']        = E\Egrade::get_desc($lesson_list[$i]['grade']);

                    $lesson['orderid']      = $val['orderid'];
                    $lesson['lessonid']     = $lesson_list[$i]['lessonid'];
                    $lesson['lesson_count'] = $lesson_list[$i]['lesson_count'];
                    $lesson['per_price']    = $val['per_price']*100;
                    $lesson['price']        = $val['per_price']*$lesson_count*100;
                    $order_lesson_list[]    = $lesson;

                    if($lesson_cost<=$lesson_total){
                        $val['lesson_cost'] = $lesson_cost;
                    }else{
                        $last_lessonid       = $lessonid;
                        $left_lesson_count   = $lesson_cost-$lesson_total;
                        $cost_lesson_count   = $lesson_count-$left_lesson_count;
                        $val['lesson_cost']  = $lesson_total;
                        $lesson_list[$i]['lesson_count'] = $val['orderid']."扣了".$cost_lesson_count."课时";
                    }
                }
            }else{
                $val['lesson_cost']=0;
            }
            $val['order_left']=$val['lesson_total']-$val['lesson_cost'];
        }

        if($type==1){
            return $lesson_list;
        }elseif($type==2){
            return $order_list;
        }
    }

    public function init_info_tmp(){

        $userid=$this->sid;
        $row=$this->t_student_init_info->field_get_list($userid,"*");
        if (!$row) {
            $this->t_student_init_info->row_insert(["userid" => $userid]);
            $row=$this->t_student_init_info->field_get_list($userid,"*");
        }
        $stu_info        = $this->t_student_info->field_get_list($userid,"*");
        $seller_stu_info = $this->t_seller_student_info->get_user_init_info( $stu_info["phone"] );
        //处理
        $set_field = function( &$item, $item_filed_name, $from_item, $from_item_field_name="" ) {
            if (!$item[$item_filed_name]) {
                if (!$from_item_field_name) {
                    $from_item_field_name=$item_filed_name;
                }
                $item[$item_filed_name]=$from_item[$from_item_field_name];
            }
        };

        $set_field( $row,  "real_name", $stu_info  ,"nick" );
        $set_field( $row,  "grade", $stu_info  );
        $set_field( $row,  "gender", $stu_info  );
        $set_field( $row,  "birth", $stu_info  );
        $set_field( $row,  "school", $stu_info  );
        if ($seller_stu_info ) {
            $set_field( $row,  "xingetedian", $seller_stu_info, "stu_character_info");
            //$set_field( $row,  "subject_yuwen", $seller_stu_info, "stu_score_info");
            $set_field( $row,  "subject_info", $seller_stu_info,"user_desc" );
        }
        $set_field( $row,  "parent_real_name", $stu_info ,"parent_name"  );
        $set_field( $row,  "relation_ship", $stu_info ,"parent_type");
        $set_field( $row,  "phone", $stu_info );
        $set_field( $row,  "addr", $stu_info ,"address" );
        \App\Helper\Utils::unixtime2date_for_item($row,"call_time","", "Y-m-d H:i");

        foreach ( $row as &$item ){
            if(!json_encode($item)){
                $item="";
            }
        }
        return $this->pageView(__METHOD__,null,
                               [
                                   "init_data"=> $row,
                                   "show_post_flag"=> $this->check_power(E\Epower::V_POST_STU_INIT_INFO ),
                               ]
        );




    }

    public function init_info( ) {

        // return "";
        //此功能已停用
        // return $this->error_view(
        //     [
        //         "交接单已移动至新版,请在[合同消耗信息]-[新建交接单] 中查看!"
        //     ]
        // );


        $userid=$this->sid;
        $row=$this->t_student_init_info->field_get_list($userid,"*");
        if (!$row) {
            $this->t_student_init_info->row_insert(["userid" => $userid]);
            $row=$this->t_student_init_info->field_get_list($userid,"*");
        }
        $stu_info        = $this->t_student_info->field_get_list($userid,"*");
        $seller_stu_info = $this->t_seller_student_info->get_user_init_info( $stu_info["phone"] );
        //处理
        $set_field = function( &$item, $item_filed_name, $from_item, $from_item_field_name="" ) {
            if (!$item[$item_filed_name]) {
                if (!$from_item_field_name) {
                    $from_item_field_name=$item_filed_name;
                }
                $item[$item_filed_name]=$from_item[$from_item_field_name];
            }
        };

        $set_field( $row,  "real_name", $stu_info  ,"nick" );
        @$set_field( $row,  "grade", $stu_info  );
        $set_field( $row,  "gender", $stu_info  );
        $set_field( $row,  "birth", $stu_info  );
        $set_field( $row,  "school", $stu_info  );
        if ($seller_stu_info ) {
            $set_field( $row,  "xingetedian", $seller_stu_info, "stu_character_info");
            //$set_field( $row,  "subject_yuwen", $seller_stu_info, "stu_score_info");
            $set_field( $row,  "subject_info", $seller_stu_info,"user_desc" );
        }
        $set_field( $row,  "parent_real_name", $stu_info ,"parent_name"  );
        $set_field( $row,  "relation_ship", $stu_info ,"parent_type");
        $set_field( $row,  "phone", $stu_info );
        $set_field( $row,  "addr", $stu_info ,"address" );
        \App\Helper\Utils::unixtime2date_for_item($row,"call_time","", "Y-m-d H:i");

        foreach ( $row as &$item ){
            if(!json_encode($item)){
                $item="";
            }
        }
        return $this->pageView(__METHOD__,null,
                               [
                                   "init_data"=> $row,
                                   "show_post_flag"=> $this->check_power(E\Epower::V_POST_STU_INIT_INFO ),
                               ]
        );

    }

    public function init_info_by_contract_cc( ) {
        $orderid = $this->get_in_int_val('orderid');
        if(!$orderid){
            return $this->stu_error_view(
                [
                    "本页面只能从[合同消耗信息]查看!"
                ]
            );
        }

        $row     = $this->t_student_cc_to_cr->get_stu_info_by_orderid($orderid);
        $is_show_submit = $this->get_in_int_val('is_show_submit',0);
        $state_arr = $this->t_student_cc_to_cr->get_last_id_reject_flag_by_orderid($orderid);

        // 判断
        $is_submit_show = 0;

        if($state_arr){
            $is_submit_show = $state_arr['reject_flag'];
        }
        // if($state_arr && $state_arr['id']>0 && $state_arr['reject_flag'] == 0){
        //     $is_submit_show = 1;
        // }


        if($row){
            $row['is_submit_show'] = $is_submit_show;
            $userid  = $this->t_order_info->get_userid($orderid);
            $stu_info        = $this->t_student_info->field_get_list($userid,"*");
            $seller_stu_info = $this->t_seller_student_info->get_user_init_info( $stu_info["phone"] );
            //处理
            $set_field = function( &$item, $item_filed_name, $from_item, $from_item_field_name="" ) {
                if (!$item[$item_filed_name]) {
                    if (!$from_item_field_name) {
                        $from_item_field_name=$item_filed_name;
                    }
                    $item[$item_filed_name]=$from_item[$from_item_field_name];
                }
            };

            $set_field( $row,  "real_name", $stu_info  ,"nick" );
            // dd($row);
            $set_field( $row,  "grade", $stu_info  );
            $set_field( $row,  "gender", $stu_info  );
            $set_field( $row,  "birth", $stu_info  );
            $set_field( $row,  "school", $stu_info  );
            if ($seller_stu_info ) {
                $set_field( $row,  "xingetedian", $seller_stu_info, "stu_character_info");
                $set_field( $row,  "subject_info", $seller_stu_info,"user_desc" );
            }
            $set_field( $row,  "parent_real_name", $stu_info ,"parent_name"  );
            $set_field( $row,  "relation_ship", $stu_info ,"parent_type");
            $set_field( $row,  "phone", $stu_info );
            $set_field( $row,  "addr", $stu_info ,"address" );
            \App\Helper\Utils::unixtime2date_for_item($row,"call_time","", "Y-m-d H:i");
            \App\Helper\Utils::unixtime2date_for_item($row,"first_lesson_time","", "Y-m-d H:i");
            \App\Helper\Utils::unixtime2date_for_item($row,"reject_time","", "Y-m-d H:i");
            $row['ass_nick'] = $this->t_manager_info->get_account($row['ass_id']);
            foreach ( $row as &$item ){
                if(!json_encode($item)){
                    $item="";
                }
            }

        }

        $this->set_filed_for_js("gg_acc",$this->get_account());
        $show_post_flag = $this->check_power(E\Epower::V_POST_STU_INIT_INFO );
        if($this->get_account() == "long-张龙"){
            $show_post_flag=1;
        }

        # 因为系统权限问题 临时全部开放[James -2018-01-19]
        $show_post_flag=1;

        return $this->pageView(__METHOD__,null,
                               [
                                   "init_data"=> $row,
                                   "show_post_flag"=> $show_post_flag,
                               ]
        );
    }




    public function return_book_record(){
        $page_num   = $this->get_in_page_num();
        $userid   =  $this->get_in_sid();
        $ret_info = $this->t_book_revisit->get_book_revisit($page_num,$userid);
        foreach($ret_info['list'] as &$item){
            $item["revisit_time"] = date("Y-m-d H:i:s",$item["revisit_time"]);

        }
        return $this->pageView(__METHOD__,$ret_info );

    }
    public function return_record(){
        $page_num   = $this->get_in_page_num();
        $userid   =  $this->get_in_sid();
        $is_warning_flag         = $this->get_in_int_val("is_warning_flag",-1);
        $ret_info = $this->t_revisit_info->get_revisit_list($page_num,$userid,$is_warning_flag);
        $domain = config('admin')['qiniu']['public']['url'];
        $num = strlen($domain)+1;
        foreach($ret_info['list'] as &$item){
            \App\Helper\Utils::unixtime2date_for_item($item,"revisit_time", "_str");
            \App\Helper\Utils::unixtime2date_for_item($item,"recover_time", "_str");
            E\Erevisit_type::set_item_value_str($item);
            $item["duration"]= \App\Helper\Common::get_time_format($item["duration"]);
            E\Eset_boolean::set_item_value_str($item,"operation_satisfy_flag");
            E\Eset_boolean::set_item_value_str($item,"school_work_change_flag");
            E\Etea_content_satisfy_flag::set_item_value_str($item,"tea_content_satisfy_flag");
            E\Eschool_work_change_type::set_item_value_str($item,"school_work_change_type");
            E\Eschool_score_change_flag::set_item_value_str($item,"school_score_change_flag");
            E\Eoperation_satisfy_type::set_item_value_str($item,"operation_satisfy_type");
            E\Etea_content_satisfy_type::set_item_value_str($item,"tea_content_satisfy_type");
            E\Echild_class_performance_flag::set_item_value_str($item,"child_class_performance_flag");
            E\Echild_class_performance_type::set_item_value_str($item,"child_class_performance_type");
            E\Eis_warning_flag::set_item_value_str($item,"is_warning_flag");



            $now=time();
            if ($now-$item["revisit_time"] >1*86400 && (preg_match("/saas.yxjcloud.com/", $item["record_url"] )|| preg_match("/121.196.236.95/", $item["record_url"] ) ) ){
                $item["load_wav_self_flag"]=1;
            }else{
                $item["load_wav_self_flag"]=0;
            }
            $num_url = strlen($item["warning_deal_url"]);
            $item["url"] = substr($item["warning_deal_url"],$num,$num_url-1);
            $item["master_adminid"] = $this->t_admin_group_user->get_master_adminid_by_adminid($item["uid"]);

            $information_confirm = $item['information_confirm'];
            $information_confirm = json_decode($information_confirm);
            if(isset($information_confirm)){
                if($information_confirm != ''){
                    foreach ($information_confirm as $key => $value) {
                        $value_de = trim($value, '{}');
                        $value_arr = explode(':', $value_de);
                        $item[$value_arr[0]] = $value_arr[1];
                    }
                }
            }


        }
        $adminid = $this->get_account_id();
        return $this->pageView(__METHOD__,$ret_info,[
            "adminid"  =>$adminid
        ] );

    }

    public function get_course_subject(){
        $courseid = $this->get_in_int_val("courseid");

    }

    public function set_subject(){
        $type     = $this->get_in_int_val("type");
        $courseid = $this->get_in_int_val("courseid");
        $subject  = $this->get_in_int_val("subject");
        if($courseid==0)
            return $this->output_err("此课程不存在");

        if($subject==0 && $type==1)
            return $this->output_err("更改的科目错误");

        $flag=true;
        if($type==0){
            $subject = $this->t_course_order->get_subject($courseid);
            $ret     = array("subject"=>$subject);
        }else{
            $ret=$this->t_course_order->field_update_list($courseid,[
                "subject"=>$subject,
            ]);
            if(!$ret){
                $flag==false;
            }else{
                $ret=$this->t_lesson_info->set_subject_by_courseid($courseid,$subject);
                if(!$ret)
                    $flag=false;
            }
        }

        if(!$flag){
            return $this->output_err("更改出错,请重试");
        }else{
            return $this->output_succ($ret);
        }
    }

    public function test_lesson_list(){
        $page_num   = $this->get_in_page_num();
        $userid   =  $this->get_in_sid();
        $ret_info = $this->t_lesson_info->test_lesson_list($page_num,$userid, -1);
        foreach( $ret_info["list"] as &$item) {
            $item["lesson_time"]=Utils::fmt_lesson_time(
                $item["lesson_start"],
                $item["lesson_end"]);
            E\Elesson_status::set_item_value_str($item);
            E\Egrade::set_item_value_str($item);
            E\Esubject::set_item_value_str($item);
            E\Eboolean::set_item_value_str($item,"lesson_del_flag");
            $item["teacher_nick"]= $this->cache_get_teacher_nick($item["teacherid"]);

        }
        return $this->pageView(__METHOD__,$ret_info);

    }

    public function set_week_comment_num(){
        $courseid         = $this->get_in_int_val("courseid");
        $week_comment_num = $this->get_in_int_val("week_comment_num");

        $this->t_course_order->start_transaction();
        $ret = $this->t_course_order->field_update_list($courseid,[
            "week_comment_num" => $week_comment_num,
        ]);
        if(!$ret){
            $this->t_course_order->rollback();
            return $this->output_err("更新课程包出错！请重试！");
        }

        $lesson_num=$this->t_lesson_info->get_course_lesson_num($courseid);
        if($lesson_num>0){
            $ret = $this->t_lesson_info->set_lesson_week_comment_num($courseid,$week_comment_num);
            if(!$ret){
                $this->t_course_order->rollback();
                return $this->output_err("更新课程出错！请重试！");
            }
        }

        $this->t_course_order->commit();
        return $this->output_succ();
    }

    public function set_enable_video(){
        $courseid     = $this->get_in_int_val("courseid");
        $enable_video = $this->get_in_int_val("enable_video");

        $this->t_course_order->start_transaction();
        $ret = $this->t_course_order->field_update_list($courseid,[
            "enable_video" => $enable_video,
        ]);
        if(!$ret){
            $this->t_course_order->rollback();
            return $this->output_err("更新课程包出错！请重试！");
        }

        $lesson_num = $this->t_lesson_info->get_course_lesson_num($courseid);
        if($lesson_num>0){
            $ret = $this->t_lesson_info->set_lesson_enable_video($courseid,$enable_video);
            if(!$ret){
                $this->t_course_order->rollback();
                return $this->output_err("更新课程出错！请重试！");
            }
        }

        $this->t_course_order->commit();
        return $this->output_succ();
    }

    public function call_list () {
        $userid= $this->sid;
        $phone= $this->t_student_info->get_phone ($userid);
        header("Location: /tq/get_list_by_phone?phone=$phone");
    }

    public function init_info_by_contract_cr(){
        $orderid = $this->get_in_int_val('orderid');
        $sid     = $this->get_in_int_val('sid');
        if(!$orderid){
            return $this->stu_error_view(
                [
                    "本页面只能从[合同消耗信息]查看!"
                ]
            );
        }
        $adminid = $this->get_account_id();
        $is_ass  = 1; // 助教类型
        $is_master    = $this->t_admin_group_name->check_is_master($is_ass,$adminid);
        $assistantid  = $this->t_student_info->get_assistantid_by_userid($sid);

        // 测试
        $accountid = $this->get_account_id();
        if($accountid == 684){
            $is_master = 1;
        }


        $this->set_in_value('is_show_submit',1);
        $row  = $this->t_student_cc_to_cr->get_stu_info_by_orderid($orderid);
        $is_show_submit = $this->get_in_int_val('is_show_submit',0);

        if ($row) {
            $row['is_show_submit'] = $is_show_submit;
            $row['confirm_flag']   = $this->t_lesson_info_b3->check_is_consume($orderid);
            $row['is_master']      = $is_master;

            $userid   = $this->t_order_info->get_userid($orderid);
            $stu_info = $this->t_student_info->field_get_list($userid,"*");

            $seller_stu_info = $this->t_seller_student_info->get_user_init_info( $stu_info["phone"] );
            //处理
            $set_field = function( &$item, $item_filed_name, $from_item, $from_item_field_name="" ) {
                if (!$item[$item_filed_name]) {
                    if (!$from_item_field_name) {
                        $from_item_field_name=$item_filed_name;
                    }
                    $item[$item_filed_name]=$from_item[$from_item_field_name];
                }
            };

            $set_field( $row,  "real_name", $stu_info  ,"nick" );
            // dd($row);
            $set_field( $row,  "grade", $stu_info  );
            $set_field( $row,  "gender", $stu_info  );
            $set_field( $row,  "birth", $stu_info  );
            $set_field( $row,  "school", $stu_info  );
            if ($seller_stu_info ) {
                $set_field( $row,  "xingetedian", $seller_stu_info, "stu_character_info");
                $set_field( $row,  "subject_info", $seller_stu_info,"user_desc" );
            }
            $set_field( $row,  "parent_real_name", $stu_info ,"parent_name"  );
            $set_field( $row,  "relation_ship", $stu_info ,"parent_type");
            $set_field( $row,  "phone", $stu_info );
            $set_field( $row,  "addr", $stu_info ,"address" );
            \App\Helper\Utils::unixtime2date_for_item($row,"call_time","", "Y-m-d H:i");
            \App\Helper\Utils::unixtime2date_for_item($row,"first_lesson_time","", "Y-m-d H:i");

            foreach ( $row as &$item ){
                if(!json_encode($item)){
                    $item="";
                }
            }
        }

        return $this->pageView(__METHOD__,null,[
            "init_data"      => $row,
            "show_post_flag" => $this->check_power(E\Epower::V_POST_STU_INIT_INFO ),
        ]);
    }

    /**
     * @author    sam
     * @function  学生分数列表显示
     */
    public function  score_list () {
        //$time = strtotime(date("Y-m"));
        //$this->t_student_score_info->set_every_month_student_score($time);
        //dd(2);
        $userid = $this->sid;
        $page_info=$this->get_in_page_info();
        $ret_info=$this->t_student_score_info->get_list($page_info,$userid);
        foreach( $ret_info["list"] as $key => &$item ) {
            $ret_info['list'][$key]['num'] = $key + 1;
            $item['score'] = $item['score']/10;

            //$ret_info['list'][$key]['score'] = 100 * $ret_info['list'][$key]['score'] /  $ret_info['list'][$key]['total_score']
            \App\Helper\Utils::unixtime2date_for_item($item,"create_time");
            \App\Helper\Utils::unixtime2date_for_item($item,"stu_score_time","","Y-m-d");
            E\Esubject::set_item_value_str($item);
            E\Esemester::set_item_value_str($item);
            E\Egrade::set_item_value_str($item);
            E\Estu_score_type::set_item_value_str($item);
            $this->cache_set_item_account_nick($item,"create_adminid","create_admin_nick" );
        }
        // dd($ret_info);
        return $this->pageView(__METHOD__, $ret_info);
    }

    /**
     * @author    kevin
     * @function  学生登录反馈列表显示
     */
    public function  user_login_list() {
        $userid       = $this->sid;
        $dymanic_flag = $this->get_in_int_val("dymanic_flag",-1);
        $page_info    = $this->get_in_page_info();

        $ret_info = $this->t_user_login_log->login_list($page_info,$userid,$dymanic_flag);

        foreach($ret_info['list'] as &$item){
            \App\Helper\Utils::unixtime2date_for_item($item,"login_time");
            E\Erole::set_item_value_str($item);
            E\Eboolean::set_item_value_str($item,"dymanic_flag");
            $this->cache_set_item_student_nick($item);
        }
        return $this->pageView(__METHOD__,$ret_info);
    }


    public function student_lesson_learning_record(){
        $userid       = $this->sid;
        // $userid = $this->get_in_int_val("sid");
        #分页信息
        $page_info= $this->get_in_page_info();
        #排序信息
        list($order_in_db_flag, $order_by_str, $order_field_name,$order_type )
            =$this->get_in_order_by_str([],"adminid desc");

        #输入参数
        //  list($start_time,$end_time)=$this->get_in_date_range(-8,-1,1);
        $start_date = $this->get_in_str_val('start_date');
        $end_date   = $this->get_in_str_val('end_date');
        $start_time = $start_date?strtotime($start_date):0;
        $end_time   = $end_date?(strtotime($end_date)+86400):0;

        $subject = $this->get_in_int_val("subject",-1);
        $grade = $this->get_in_int_val("grade",-1);
        $semester = $this->get_in_int_val("semester",-1);
        $stu_score_type = $this->get_in_int_val("stu_score_type",-1);
        $current_id = $this->get_in_int_val("current_id",1);
        $current_table_id = $this->get_in_int_val("current_table_id",2);
        $cw_status = $this->get_in_int_val("cw_status",-1);
        $preview_status = $this->get_in_int_val("preview_status",-1);
        $subject_arr=[];
        $grade_arr=[];
        $domain = config('admin')['qiniu']['public']['url'];
        //获取该学生所有的课(未删除)
        $all_lesson_list = $this->t_lesson_info_b3->get_student_all_lesson_info($userid,0,0);
        foreach($all_lesson_list as $val){
            if(!isset($subject_arr[$val["subject"]])){
                $subject_arr[$val["subject"]]=$val["subject"];
            }
            if(!isset($grade_arr[$val["grade"]])){
                $grade_arr[$val["grade"]]=$val["grade"];
            }
 
        }
        $all_lesson=[];
        foreach($all_lesson_list as $k=>$val){
            $all_lesson[$val["lessonid"]] = $k+1;
        }
        if($current_id==1){
            $ret_info = $this->t_lesson_info_b3->get_pre_class_preview_info($page_info,$userid,$start_time,$end_time,$subject,$grade,$cw_status,$preview_status);
            $list = $this->t_lesson_info_b3->get_pre_class_preview_info($page_info,$userid,$start_time,$end_time,$subject,$grade,$cw_status,$preview_status,2);
            foreach($ret_info["list"] as &$item){
                E\Egrade::set_item_value_str($item);
                E\Esubject::set_item_value_str($item);
                \App\Helper\Utils::unixtime2date_range($item);
                $item["cw_url"] = \App\Helper\Utils::gen_download_url($item["tea_cw_url"]);
                if(empty($item["tea_cw_upload_time"]) || $item["tea_cw_upload_time"]>=$item["lesson_start"]){
                    $item["cw_status_str"]="未上传";
                    $item["cw_status_flag"]=0;
                    $item["preview_status_str"]="—";
                }else{
                    $item["cw_status_str"]="已上传";
                    $item["cw_status_flag"]=1;
                    E\Eboolean::set_item_value_str($item,"preview_status");
                }
                $item["lesson_num"] = @$all_lesson[$item["lessonid"]];

            }
            $cw_num=$pre_num=0;
            foreach($list as $val){
                // if(!isset($subject_arr[$val["subject"]])){
                //     $subject_arr[$val["subject"]]=$val["subject"];
                // }
                // if(!isset($grade_arr[$val["grade"]])){
                //     $grade_arr[$val["grade"]]=$val["grade"];
                // }
                if(empty($val["tea_cw_upload_time"]) || $val["tea_cw_upload_time"]>$val["lesson_start"]){
                }else{
                    $cw_num++;
                    if($val["preview_status"]>0){
                        $pre_num++;
                    }
                }


            }
            $pre_rate = $cw_num==0?0:round($pre_num/$cw_num*100,2);
            return $this->pageView(__METHOD__,$ret_info,[
                "pre_rate"=>$pre_rate,
                "subject_list"=>$subject_arr,
                "grade_list"=>$grade_arr,
            ]);
        }elseif($current_id==2){          
            $ret_info = $this->t_lesson_info_b3->get_classroom_situation_info($page_info,$userid,$start_time,$end_time,$subject,$grade);
            $list = $this->t_lesson_info_b3->get_classroom_situation_info($page_info,$userid,$start_time,$end_time,$subject,$grade,2);
            foreach($ret_info["list"] as &$item){
                E\Egrade::set_item_value_str($item);
                E\Esubject::set_item_value_str($item);
                \App\Helper\Utils::unixtime2date_range($item);
                $item["lesson_num"] = @$all_lesson[$item["lessonid"]];
                if($item["lesson_status"]<2){
                    $item["tea_login_num"] = "—";
                    $item["stu_login_num"] = "—";
                    $item["parent_login_num"] = "—";
                    $item["stu_praise"] = "—";
                    $item["tea_attend_str"] = "—";
                    $item["stu_attend_str"] = "—";
                }else{

                    if(in_array($item["lesson_cancel_reason_type"],[2,12,21]) && $item["confirm_flag"]>=2){
                        $item["tea_attend_str"] = E\Elesson_cancel_reason_type::get_desc($item["lesson_cancel_reason_type"]);
                        $item["stu_attend_str"] = "—";
                        $item["tea_login_num"] = "—";
                        $item["stu_login_num"] = "—";
                        $item["parent_login_num"] = "—";
                        $item["stu_praise"] = "—";

                    }elseif(in_array($item["lesson_cancel_reason_type"],[1,11,20]) && $item["confirm_flag"]>=2){
                        $item["stu_attend_str"] = E\Elesson_cancel_reason_type::get_desc($item["lesson_cancel_reason_type"]);
                        $item["tea_attend_str"] = "—";
                        $item["tea_login_num"] = "—";
                        $item["stu_login_num"] = "—";
                        $item["parent_login_num"] = "—";
                        $item["stu_praise"] = "—";


                    }else{
                        // $item["stu_attend_str"] = $item["tea_attend_str"] =E\Elesson_cancel_reason_type::get_desc($item["lesson_cancel_reason_type"]);
                        $stu_login_time = @$list[$item["lessonid"]]["stu_login_time"]; 
                        $stu_logout_time = @$list[$item["lessonid"]]["stu_logout_time"]; 
                        $tea_login_time = @$list[$item["lessonid"]]["tea_login_time"]; 
                        $tea_logout_time = @$list[$item["lessonid"]]["tea_logout_time"];
                        $lesson_start = ($item["lesson_start"]+59);
                        $lesson_end = $item["lesson_end"];
                        if($stu_login_time>$lesson_start && $stu_logout_time<$lesson_end){
                            $item["stu_attend_str"]="迟到且早退";
                        }elseif($stu_login_time>$lesson_start){
                            $item["stu_attend_str"]="迟到";
                        }elseif($stu_logout_time<$lesson_end){
                            $item["stu_attend_str"]="早退";
                        }else{
                            $item["stu_attend_str"]="正常";
                        }
                        if($tea_login_time>$lesson_start && $tea_logout_time<$lesson_end){
                            $item["tea_attend_str"]="迟到且早退";
                        }elseif($tea_login_time>$lesson_start){
                            $item["tea_attend_str"]="迟到";
                        }elseif($tea_logout_time<$lesson_end){
                            $item["tea_attend_str"]="早退";
                        }else{
                            $item["tea_attend_str"]="正常";
                        }

                    }
                }

            }

            $normal_num=$normal_all=0;
            foreach($list as $val){
                // if(!isset( $subject_arr[$val["subject"]])){
                //     $subject_arr[$val["subject"]]=$val["subject"];
                // }
                // if(!isset($grade_arr[$val["grade"]])){
                //     $grade_arr[$val["grade"]]=$val["grade"];
                // }
                if($val["lesson_status"]>=2){
                    if(!($val["confirm_flag"]>=2 && in_array($val["lesson_cancel_reason_type"],[2,12,21]))){
                        $stu_login_time = @$list[$val["lessonid"]]["stu_login_time"]; 
                        $stu_logout_time = @$list[$val["lessonid"]]["stu_logout_time"]; 
                        $tea_login_time = @$list[$val["lessonid"]]["tea_login_time"]; 
                        $tea_logout_time = @$list[$val["lessonid"]]["tea_logout_time"];
                        $lesson_start = ($val["lesson_start"]+59);
                        $lesson_end = $val["lesson_end"];
                        if($stu_login_time<=$lesson_start && $stu_logout_time>=$lesson_end){
                            $normal_num++;
                        }
                        $normal_all++;

                    }

                }


            }
            $attend_rate = $normal_num==0?0:round($normal_num/$normal_all*100,2);



            return $this->pageView(__METHOD__,$ret_info,[
                "attend_rate"=>@$attend_rate,
                "subject_list"=>$subject_arr,
                "grade_list"=>$grade_arr,
            ]);


        }elseif($current_id==3){
            $ret_info = $this->t_lesson_info_b3->get_lesson_performance_list_new($page_info,$userid,$start_time,$end_time,$subject,$grade);
            $list = $this->t_lesson_info_b3->get_lesson_performance_list_new($page_info,$userid,$start_time,$end_time,$subject,$grade,2);
            foreach($ret_info["list"] as &$item){
                E\Egrade::set_item_value_str($item);
                E\Esubject::set_item_value_str($item);
                \App\Helper\Utils::unixtime2date_range($item);             
                $item["lesson_num"] = @$all_lesson[$item["lessonid"]];

                         
                $item['stu_intro']   = json_decode($item['stu_performance'],true);
                $item['stu_point_performance']='';
                if(isset($item['stu_intro']['point_note_list']) && is_array($item['stu_intro']['point_note_list'])){
                    foreach(@$item['stu_intro']['point_note_list'] as $val){
                        $item['stu_point_performance'].=$val['point_name'].":".$val['point_stu_desc']."。";
                    }
                }
                $item["stu_comment"] = $this->get_test_lesson_comment_str($item["stu_comment"],1);
                if(isset($item['stu_intro']['stu_comment']) && $item['stu_intro']['stu_comment']!=''){
                    if(is_array($item['stu_intro']['stu_comment'])){
                        $str = json_encode($item['stu_intro']['stu_comment']);
                        $str = $this->get_test_lesson_comment_str($str);
                    }else{
                        $str = $item['stu_intro']['stu_comment'];
                    }
                    //   $str = $this->get_test_lesson_comment_str($str);
                    $item['stu_point_performance'].=PHP_EOL."总体评价:".$str;
                }
                $item['stu_intro']="";
                if(empty($item["teacher_comment"])){
                    $item["teacher_comment"]="—";
                }
                if(empty($item["stu_score"])){
                    $item["stu_score"]="—";
                }




            }
            $tea_comment=$all_num=0;
            foreach($list as $val){
                // if(!isset($subject_arr[$val["subject"]])){
                //     $subject_arr[$val["subject"]]=$val["subject"];
                // }
                // if(!isset($grade_arr[$val["grade"]])){
                //     $grade_arr[$val["grade"]]=$val["grade"];
                // }               
                if($val["confirm_flag"]<2){
                    $all_num++;
                    $stu_intro   = json_decode($val['stu_performance'],true);
                    $stu_point_performance='';
                    if(isset($stu_intro['point_note_list']) && is_array($stu_intro['point_note_list'])){
                        foreach(@$stu_intro['point_note_list'] as $val){
                            $stu_point_performance .=$val['point_name'].":".$val['point_stu_desc']."。";
                        }
                    }
                    if(isset($stu_intro['stu_comment']) && $stu_intro['stu_comment']!=''){
                        if(is_array($stu_intro['stu_comment'])){
                            $str = json_encode($stu_intro['stu_comment']);
                            $str = $this->get_test_lesson_comment_str($str);
                        }else{
                            $str = $stu_intro['stu_comment'];
                        }
                        //   $str = $this->get_test_lesson_comment_str($str);
                        $stu_point_performance .=PHP_EOL."总体评价:".$str;
                    }
                    $comment = trim($stu_point_performance,"\"");
                    if(!empty($comment)){
                        $tea_comment++;
                    }


                }


            }
            $record_rate = $all_num==0?0:round($tea_comment/$all_num*100,2);
            return $this->pageView(__METHOD__,$ret_info,[
                "record_rate"=>$record_rate,
                "subject_list"=>$subject_arr,
                "grade_list"=>$grade_arr,
            ]);




        }elseif($current_id==4){
            $ret_info = $this->t_lesson_info_b3->get_lesson_homework_list_new($page_info,$userid,$start_time,$end_time,$subject,$grade);
            $list = $this->t_lesson_info_b3->get_lesson_homework_list_new($page_info,$userid,$start_time,$end_time,$subject,$grade,2);
            foreach($ret_info["list"] as &$item){
                E\Egrade::set_item_value_str($item);
                E\Esubject::set_item_value_str($item);
                \App\Helper\Utils::unixtime2date_range($item);
                $item["issue_url_str"] = \App\Helper\Utils::gen_download_url($item["issue_url"]);
                $item["finish_url_str"] = \App\Helper\Utils::gen_download_url($item["finish_url"]);
                $item["check_url_str"] = \App\Helper\Utils::gen_download_url($item["check_url"]);
                if(empty($item["issue_url"])){
                    $item["issue_url_str"]="";
                    $item["finish_url_str"]="";
                    $item["check_url_str"]="";
                    $item["issue_flag"]="未上传";
                    $item["download_flag"]= $item["commit_flag"]= $item["check_flag"]="—";
                    $item["stu_check_flag"]="—";
                }else{
                    $item["issue_flag"]="已上传";
                    // $item["download_flag"]="—";                   
                    if($item["work_status"]>=2){
                        $item["commit_flag"]="已提交";
                    }else{
                        $item["commit_flag"]="未提交";
                    }
                    if($item["work_status"]>=3){
                        $item["check_flag"]="是";   
                    }else{
                        $item["check_flag"]="否";
                    }
                    if($item["stu_check_time"]>0){
                        $item["stu_check_flag"]="已查看"; 
                    }else{
                        $item["stu_check_flag"]="未查看"; 
                    }
                    if($item["download_time"]>0){
                        $item["download_flag"]="已下载"; 
                    }else{
                        $item["download_flag"]="未下载"; 
                    }




                }
                $item["lesson_num"] = @$all_lesson[$item["lessonid"]];
                if($item["lesson_start"]<strtotime("2018-01-26")){
                    $item["stu_check_flag"]=$item["download_flag"]="—";
                }


            }
            $commit_num=$upload_num=$check_num=$score_total=0;
            foreach($list as $val){
                // if(!isset($subject_arr[$val["subject"]])){
                //     $subject_arr[$val["subject"]]=$val["subject"];
                // }
                // if(!isset($grade_arr[$val["grade"]])){
                //     $grade_arr[$val["grade"]]=$val["grade"];
                // }
                if(!empty($val["issue_url"])){
                    $upload_num++;
                    if($val["work_status"]>=2){
                        $commit_num++;
                    }
                }
                if($val["work_status"]>=3){
                    $check_num++;
                    $score =$val["score"];
                    if($score=="A"){
                        $score_total +=90;
                    }elseif($score=="B"){
                        $score_total +=80;
                    }elseif($score=="C"){
                        $score_total +=70;
                    }else{
                        $score_total +=50;
                    }

                }
                

            }
            $score_avg = $check_num==0?"—":($score_total/$check_num);
            if($score_avg>=86){
                $score_final = "A";
            }elseif($score_avg>=75){
                $score_final = "B";
            }elseif($score_avg>=60){
                $score_final = "C";
            }else{
                $score_final = "D";
            }
            $complete_rate = $upload_num==0?0:round($commit_num/$upload_num*100,2);
            return $this->pageView(__METHOD__,$ret_info,[
                "complete_rate"=>$complete_rate,
                "score_final"  =>$score_final,
                "subject_list"=>$subject_arr,
                "grade_list"=>$grade_arr,
            ]);



        }elseif($current_id==5){
            $subject_arr=[];
            $grade_arr=[];
            $ret_info=$this->t_student_score_info->get_all_list($page_info,"",$grade,$semester,$stu_score_type,-1,$userid,$subject);
            $list = $this->t_student_score_info->get_all_list_no_page("",$grade,$semester,$stu_score_type,-1,$userid,$subject);
            foreach( $ret_info["list"] as $key => &$item ) {

                if(empty($item["paper_upload_time"])){
                    $item["paper_upload_time"] =$item["create_time"];
                }


                $ret_info['list'][$key]['num'] = $key + 1;
                \App\Helper\Utils::unixtime2date_for_item($item,"create_time","","Y/m/d");
                \App\Helper\Utils::unixtime2date_for_item($item,"stu_score_time","","Y/m/d");
                E\Esemester::set_item_value_str($item);
                E\Egrade::set_item_value_str($item);
                E\Esubject::set_item_value_str($item);
                E\Estu_score_type::set_item_value_str($item);
                if($ret_info['list'][$key]['total_score']){
                    $ret_info['list'][$key]['score'] = round(100*$ret_info['list'][$key]['score']/$ret_info['list'][$key]['total_score']);
                }


                if($item['admin_type'] == 1){
                    $item['create_admin_nick'] = "<font color=\blue\">家长/微信端</font>";
                }elseif($item['admin_type'] == 0){
                    $this->cache_set_item_account_nick($item,"create_adminid","create_admin_nick" );
                }
                $class_rank = explode("/",$item["rank"]);
                $item["rank"] = $class_rank[0];
                $item["rank_num"] = @$class_rank[1]?@$class_rank[1]:"--";
                $grade_rank = explode("/",$item["grade_rank"]);
                $item["grade_rank"] = $grade_rank[0];
                $item["grade_rank_num"] = @$grade_rank[1]?@$grade_rank[1]:"--";
                $item["file_url_str"] = \App\Helper\Utils::gen_download_url($item["file_url"]);
                if($item["file_url"]){
                    $item["file_upload_str"]="已上传";
                    \App\Helper\Utils::unixtime2date_for_item($item,"paper_upload_time","_str");
                }else{
                    $item["file_upload_str"]="未上传";
                    $item["paper_upload_time_str"]="无";
                }
                






            }

            $subject_1 = [];
            $subject_2 = [];
            $subject_3 = [];
            $subject_4 = [];
            $subject_5 = [];
            $subject_6 = [];
            $subject_7 = [];
            $subject_8 = [];
            $subject_9 = [];
            $subject_10 = [];
            $date_list = [];
            
            $min_month =100000000000;
            $max_month = 0;
            foreach ($list as $key => $value) {
                if($min_month>$value["create_time"]){                  
                    $min_month = $value["create_time"];
                }
                if($max_month<$value["create_time"]){                  
                    $max_month = $value["create_time"];
                }


                $subject = $value["subject"];
                $score = $value['total_score']>0?round(10*$value['score']/$value['total_score']):0;
                //  $month = date("Y-m-d H:i",$value["create_time"]);
                $month = $value["create_time"];
                $arr=[
                    "month"=>$month,
                    "count"=>$score
                ];
                
                if($subject==1){
                    $subject_1[]=$arr;
                }elseif($subject==2){
                    $subject_2[]=$arr;
                }elseif($subject==3){
                    $subject_3[]=$arr;
                   

                }elseif($subject==4){
                    $subject_4[]=$arr;

                }elseif($subject==5){
                    $subject_5[]=$arr;

                }elseif($subject==6){
                    $subject_6[]=$arr;

                }elseif($subject==7){
                    $subject_7[]=$arr;

                }elseif($subject==8){
                    $subject_8[]=$arr;

                }elseif($subject==9){
                    $subject_9[]=$arr;

                }elseif($subject==10){
                    $subject_10[]=$arr;

                }



                // if(!isset($subject_arr[$value["subject"]])){
                //     $subject_arr[$value["subject"]]=$value["subject"];
                // }
                // if(!isset($grade_arr[$value["grade"]])){
                //     $grade_arr[$value["grade"]]=$value["grade"];
                // }               





               
                $date_list[$month]['title'] = $month;

            }

            $all = $this->t_student_score_info->get_all_list_no_page("",-1,-1,-1,-1,$userid,-1);
            foreach($all as $value){
                if(!isset($subject_arr[$value["subject"]])){
                    $subject_arr[$value["subject"]]=$value["subject"];
                }
                if(!isset($grade_arr[$value["grade"]])){
                    $grade_arr[$value["grade"]]=$value["grade"];
                }               

            }

            $n = round(($max_month-$min_month)/86400/2);
            $middle_month = intval($min_month+$n*86400);
            
            \App\Helper\Utils::date_list_set_value($date_list,$subject_1,"month","subject_1","count");
            \App\Helper\Utils::date_list_set_value($date_list,$subject_2,"month","subject_2","count");
            \App\Helper\Utils::date_list_set_value($date_list,$subject_3,"month","subject_3","count");
            \App\Helper\Utils::date_list_set_value($date_list,$subject_4,"month","subject_4","count");
            \App\Helper\Utils::date_list_set_value($date_list,$subject_5,"month","subject_5","count");
            \App\Helper\Utils::date_list_set_value($date_list,$subject_6,"month","subject_6","count");
            \App\Helper\Utils::date_list_set_value($date_list,$subject_7,"month","subject_7","count");
            \App\Helper\Utils::date_list_set_value($date_list,$subject_8,"month","subject_8","count");
            \App\Helper\Utils::date_list_set_value($date_list,$subject_9,"month","subject_9","count");
            \App\Helper\Utils::date_list_set_value($date_list,$subject_10,"month","subject_10","count");
            //dd($date_list);
            $this->set_filed_for_js("max_month",$max_month);
            $this->set_filed_for_js("max_month_date",date("Y-m-d H:i",$max_month));
            $this->set_filed_for_js("min_month",$min_month);
            $this->set_filed_for_js("min_month_date",date("Y-m-d H:i",$min_month));
            $this->set_filed_for_js("middle_month_date",date("Y-m-d H:i",$middle_month));
            $this->set_filed_for_js("middle_month",$middle_month);
           

           

            return $this->pageView(__METHOD__,$ret_info,[
                "pic_data" =>$date_list,
                "subject_list"=>$subject_arr,
                "grade_list"=>$grade_arr
            ]);


            
        }

        return $this->pageView(__METHOD__);

    }


}
