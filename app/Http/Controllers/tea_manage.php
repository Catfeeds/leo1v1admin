<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use \App\Enums as E;
use \App\Libs;
use \App\Config as C;
use App\Helper\Utils;

use Illuminate\Support\Facades\Cookie ;

class tea_manage extends Controller
{
    const TYPE_CREATE_MEETING = 1;
    const TYPE_CANCEL_MEETING = 0;
    use CacheNick;
    use TeaPower;

    public function __construct() {
        if ($this->get_action_str()=="get_lesson_reply" ) {
            $this->check_login_flag=false;
        }
        parent::__construct();
    }

    public function open_class2()
    {
        list($start_time,$end_time) = $this->get_in_date_range(0,0,0,null,1);
        $lesson_status = $this->get_in_int_val('lesson_status', -1);
        $lesson_type   = $this->get_in_int_val('lesson_type',-1);
        $teacherid     = $this->get_in_int_val('teacherid',-1);
        $lessonid      = $this->get_in_lessonid(-1);
        $page          = $this->get_in_int_val('page_num',1);

        $ret_info = $this->t_lesson_info->get_open_lessons($lesson_status,$teacherid,$lesson_type,
                                                           $start_time,$end_time,$lessonid,$page);
        foreach($ret_info['list'] as &$item){
            if($item['lesson_status']>=2){
                $is_over = 2;
            }elseif($item['lesson_status']==1){
                $is_over = 1;
            }else{
                $is_over = 0;
            }
            $item['lesson_time']     = date('Y-m-d H:i:s',$item['lesson_start'])."-".date("H:i:s",$item['lesson_end']);
            $item['lesson_status']   = E\Elesson_status::get_desc($item['lesson_status']);
            $item['can_set']         = E\Ecan_set::get_desc($item['can_set_as_from_lessonid']);
            $item['lesson_num']      = ($item['lesson_num'])."/".($item['lesson_total']);
            $item['lesson_type_str'] = E\Econtract_type::get_desc($item['lesson_type']);
            $item['cw_status']       = $item['tea_cw_status']?"已上传":"未上传";
            $item['nick']            = $this->cache_get_teacher_nick($item['teacherid']);
            $item['stu_total']       = $this->t_open_lesson_user->check_lesson_has($item['lessonid']);
            $item['stu_join']        = $this->t_lesson_opt_log->get_login_user($item['lessonid']);
            E\Egrade::set_item_value_str($item,"grade");
        }
        $qiniu_url=\App\Helper\Config::get_qiniu_public_url();

        return $this->Pageview(__METHOD__,$ret_info,["qiniu_domain"=>$qiniu_url]);
    }

    public function get_open_course_list_for_js() {
        $courseid    = $this->get_in_courseid(-1);
        $course_type = $this->get_in_int_val("course_type",-1);
        $search_str  = trim($this->get_in_str_val("search_str"));
        $page_num    = $this->get_in_page_num();

        $ret_info = $this->t_course_order->get_open_course_list($courseid,$course_type,$search_str,$page_num);

        foreach($ret_info['list'] as &$item){
            $item['course_type_str']      = E\Econtract_type::get_desc ($item['course_type' ]) ;
        }

        $ret_info["page_info"] = $this->get_page_info_for_js( $ret_info["page_info"]   );

        return outputjson_success(array('data' => $ret_info));
    }

    public function get_open_from_list_for_js(){
        $courseid    = $this->get_in_courseid(-1);
        $course_type = $this->get_in_int_val("course_type",-1);
        $search_str  = trim($this->get_in_str_val("search_str"));
        $page_num    = $this->get_in_page_num();

        $ret_info = $this->t_lesson_info->get_open_from_list($courseid,$course_type,$search_str,$page_num);
        foreach($ret_info['list'] as &$item){
            $item['course_type']      = E\Econtract_type::get_desc ($item['course_type' ]) ;
        }
        $ret_info["page_info"] = $this->get_page_info_for_js( $ret_info["page_info"]   );

        return outputjson_success(array('data' => $ret_info));
    }

    public function get_open_package_list_for_js() {
        $packageid    = $this->get_in_packageid(-1);
        $package_type = $this->get_in_int_val("package_type",-1);
        $search_str   = trim($this->get_in_str_val("search_str"));
        $page_num     = $this->get_in_page_num();

        $ret_info = $this->t_appointment_info->get_open_package_list($packageid,$package_type,$search_str,$page_num);
        foreach($ret_info['list'] as &$item){
            $item['subject']      = E\Esubject::get_desc ($item['subject' ]) ;
            $item['package_type'] = E\Epackage_type::get_desc($item['package_type']);
        }
        $ret_info["page_info"] = $this->get_page_info_for_js( $ret_info["page_info"]   );

        return outputjson_success(array('data' => $ret_info));
    }

    public function get_course_name(){
        $courseid=$this->get_in_int_val("courseid",0);
        $lessonid=$this->get_in_int_val("lessonid",0);
        $course_name=$this->t_course_order->get_course_name($courseid);
        $lesson_intro=$this->t_lesson_info->get_lesson_intro($lessonid);

        return outputjson_success(array(
            'course_name'  => $course_name,
            'lesson_intro' => $lesson_intro
        ));
    }

    //laravel
    public function lesson_account(){
        $lesson_type  = $this->get_in_int_val('lesson_type',-1);
        $teacherid    = $this->get_in_int_val('teacherid',-1);
        $start_date   = $this->get_in_str_val('start_date',date('Y-m-d', time(NULL)-3*86400 ));
        $end_date     = $this->get_in_str_val('end_date',date('Y-m-d', time(NULL)+86400 ));
        $page_num     = $this->get_in_page_num();
        $start_date_s = strtotime($start_date);
        $end_date_s   = strtotime($end_date)+86400;

        $ret_info = $this->t_lesson_info->lesson_account($lesson_type,$start_date_s,$end_date_s,$teacherid,$page_num);
        foreach($ret_info['list'] as &$item){
            $count                   = 0;
            $item['lesson_time']     = date('Y-m-d H:i',$item['lesson_start'])."-".date('H:i',$item['lesson_end']);
            $item['lesson_type_str'] = E\Econtract_type::get_desc($item["lesson_type"]) ;
            $ret_all                 = $this->t_small_class_user->get_small_class_user_list($item['courseid']);
            $item['course_name']     = $this->t_course_order->get_course_name($item['courseid']);
            foreach($ret_all as &$val) {
                $val["user_login_time"] = $this->t_small_class_user->get_small_class_user_login_time($item['lessonid'],$val["userid"]);
                if($val["user_login_time"] > 0){
                    $count++;
                }
            }

            $item['user_login'] = $count++;
            $item['user_all']   = count($ret_all);
            if (  $item['user_all'] ==0 ) {
                $item['user_rate'] = "0%" ;
            }else{
                $item['user_rate']  = round(($item['user_login']/$item['user_all'])*100).'%';
            }
        }
        return $this->pageView(__METHOD__,$ret_info);
    }

    public function lesson_list_seller() {
        $this->set_in_value("test_seller_adminid", $this->get_in_int_val('test_seller_id',-1));
        $adminid = $this->get_account_id();
        $this->set_in_value("test_seller_id", $adminid);
        $this->set_in_value("lesson_type",  2);
        $this->set_in_value("seller_flag",  1);
        return $this->lesson_list();
    }



    public function lesson_list_zj() {
        return $this->lesson_list_research();
    }

    public function lesson_list_research() {
        $this->set_in_value("fulltime_flag",0);
        return $this->lesson_list();
    }
    public function lesson_list_fulltime() {
        $this->set_in_value("fulltime_flag",1);
        return $this->lesson_list();
    }

    public function lesson_list()
    {
        $this->switch_tongji_database();
        list( $order_in_db_flag, $order_by_str, $order_field_name,$order_type )
            = $this->get_in_order_by_str([],"lesson_start asc",[
                "grade" => "s.grade",
            ]);
        $page_info= $this->get_in_page_info();

        list($start_time,$end_time) = $this->get_in_date_range(0,0,0,null,1);

        $acc         = $this->get_account();
        $adminid     = $this->get_account_id();
        $right_list  = $this->get_tea_subject_and_right_by_adminid($adminid);
        $tea_subject = $right_list["tea_subject"];
        $tea_right   = $right_list["tea_right"];
        $qz_flag     = $right_list["qz_flag"];
        if($adminid==486 || $adminid==478){
             $tea_subject= "";
        }elseif($adminid==329){
            $tea_subject="";
        }elseif($adminid==1143){
            $tea_subject="";//朱丽莎权限
        }

        $account_info = $this->t_manager_info->get_teacher_info_by_adminid($adminid);
        if($account_info["teacherid"]>0){
            $is_tea = 1;
        }else{
            $is_tea = 0;
        }

        $studentid       = $this->get_in_int_val('studentid', -1);
        $teacherid       = $this->get_in_int_val('teacherid', -1);
        $confirm_flag    = $this->get_in_enum_list(E\Econfirm_flag::class);
        $seller_adminid  = $this->get_in_int_val("seller_adminid",-1);
        $lesson_status   = $this->get_in_int_val("lesson_status",-1);
        $assistantid     = $this->get_in_assistantid(-1);
        $grade           = $this->get_in_enum_list(E\Egrade::class);
        $test_seller_id  = $this->get_in_int_val("test_seller_id",-1 );
        $test_seller_adminid = $this->get_in_int_val("test_seller_adminid",-1 );
        $has_performance = $this->get_in_int_val("has_performance",-1 );
        $fulltime_flag   = $this->get_in_int_val("fulltime_flag",-1 );
        $lesson_user_online_status = $this->get_in_e_set_boolean(-1,"lesson_user_online_status");

        $lesson_type_default = Cookie::get("lesson_type")==null?-1: Cookie::get("lesson_type") ;
        $subject_default     = Cookie::get("subject")==null?-1: Cookie::get("subject");

        $lesson_type               = $this->get_in_int_val('lesson_type', $lesson_type_default);
        $subject                   = $this->get_in_int_val('subject', $subject_default);
        $lesson_count              = $this->get_in_int_val('lesson_count', -1 );
        $lesson_cancel_reason_type = $this->get_in_int_val('lesson_cancel_reason_type', -1 );
        $lesson_del_flag           = $this->get_in_int_val('lesson_del_flag', -1 );
        $has_video_flag            = $this->get_in_e_boolean(-1,"has_video_flag");

        $is_with_test_user = $this->get_in_int_val('is_with_test_user', 0);
        $seller_flag = $this->get_in_int_val('seller_flag', 0);
        $lessonid          = $this->get_in_lessonid(-1);
        $origin            = $this->get_in_str_val("origin");
        $fulltime_teacher_type = $this->get_in_int_val("fulltime_teacher_type", -1);
        if ($lessonid ==0) {
            $lessonid= $this->t_lesson_info->get_lessonid_by_lesson_str( $this->get_in_str_val("lessonid"));
        }

        if($seller_flag==1){ //销售
            $son_adminid = $this->t_admin_main_group_name->get_son_adminid($adminid);
            $son_adminid_arr = [];
            foreach($son_adminid as $item){
                $son_adminid_arr[] = $item['adminid'];
            }
            array_unshift($son_adminid_arr,$adminid);
            $test_seller_id_arr = array_unique($son_adminid_arr);

            $ret_info = $this->t_lesson_info->get_lesson_condition_list_ex_new(
                $start_time,$end_time, $teacherid,$studentid, $lessonid ,
                $lesson_type ,$subject,$is_with_test_user,$seller_adminid,$page_info,
                $confirm_flag,$assistantid,$lesson_status,$test_seller_id_arr,$test_seller_adminid,$has_performance,
                $origin,$grade,$lesson_count,$lesson_cancel_reason_type,$tea_subject,
                $has_video_flag, $lesson_user_online_status,$fulltime_flag,
                $lesson_del_flag,$fulltime_teacher_type
            );
        }else{
            $ret_info = $this->t_lesson_info->get_lesson_condition_list_ex(
                $start_time,$end_time, $teacherid,$studentid, $lessonid ,
                $lesson_type ,$subject,$is_with_test_user,$seller_adminid,$page_info,
                $confirm_flag,$assistantid,$lesson_status,$test_seller_id,$has_performance,
                $origin,$grade,$lesson_count,$lesson_cancel_reason_type,$tea_subject,
                $has_video_flag, $lesson_user_online_status,$fulltime_flag,
                $lesson_del_flag,$fulltime_teacher_type
            );
        }

        $lesson_list       = array();
        $lesson_status_cfg = array( 0 => "未上", 1 => "进行",2 => "结束",3=>"终结");
        $lesson_cw_cfg     = array( 0 => "未传", 1=> "已传" );
        $lesson_hw_cfg     = array( 0 =>"未布置", 1=>"已布置",2=>"学生已做", 3=>"老师已批" ,4 => "教研已看",5=>"助教已看");
        $lesson_quiz_cfg   = array( 0 => "未传", 1=> "已传" );
        $lesson_comment    = array( 1=>"很差", 2=>"差", 3=>"一般", 4 => "好",5=>"很好");

        $lesson_count_all      = 0;
        $lesson_count_fail_all = 0;
        $lesson_deduct_key     = E\Elesson_deduct::$v2s_map;
        $lesson_deduct_info    = E\Elesson_deduct::$desc_map;
        $price_all             = 0;
        $start_index           = \App\Helper\Utils::get_start_index_from_ret_info($ret_info);
        foreach( $ret_info['list'] as $i=> &$item){
            $item["number"] = $start_index+$i;
            $stu_id         = $item["stu_id"];

            \App\Helper\Utils::unixtime2date_for_item($item,"lesson_cancel_reason_next_lesson_time");
            $new_test_listen=   "不是";
            if ($item["lesson_type"] == E\Econtract_type::V_2 ) {
                $has_order       = false;
                $new_test_listen = $has_order?"不是":"是";
            }

            if($item['tea_rate_time']==0){
                $item['performance_status'] = 0;
                $item['performance']        = '未评价';
            }else{
                $item['performance_status'] = 1;
                $item['performance']        = '已评价';
                $item['tea_has_update']     = $item['stu_performance']==''?1:0;
            }

            $item["new_test_listen"] = $new_test_listen;
            $item['lesson_time' ]    = \App\Helper\Utils::fmt_lesson_time($item["lesson_start"] ,$item["lesson_end"]);

            E\Elesson_cancel_reason_type::set_item_value_str($item);
            \App\Helper\Common::set_item_enum_flow_status($item ,"require_lesson_success_flow_status" );
            $item['assistant_nick']= $this->cache_get_assistant_nick($item['assistantid']);
            $item['lesson_end_str' ]        = date('Y-m-d H:i',$item['lesson_end']);
            $item['real_begin_time_str' ]   = date('Y-m-d H:i',$item['real_begin_time']);
            $item['lesson_status_str']      = $lesson_status_cfg[ $item['lesson_status' ] ];
            $item['lesson_vedio_flag']      = $item['audio'] && $item["draw"] ? 1:0   ;
            $item['lesson_vedio_flag_str']  = E\Eboolean::get_desc( $item['lesson_vedio_flag'] );
            $item['stu_cw_status_str']      = $lesson_cw_cfg[ $item['stu_cw_status' ] ];
            $item['tea_cw_status_str']      = $lesson_cw_cfg[ $item['tea_cw_status' ] ];
            $item['work_status_str']        = $lesson_hw_cfg[ $item['work_status' ]?:0 ];
            $item['lesson_quiz_status_str'] = $this->get_lesson_quiz_cfg($item['lesson_quiz_status'], $item['lesson_type']);
            $item['is_complained_str']      = E\Eboolean::get_desc ($item['is_complained' ]) ;
            $item['homework_url']           = $this->get_work_url($item);
            $item['lesson_type_str']        = E\Econtract_type::get_desc($item["lesson_type"]);
            $item['level_str'] = \App\Helper\Utils::get_teacher_letter_level($item['teacher_money_type'],$item['level']);
            E\Eteacher_money_type::set_item_value_str($item);
            $item['teacher_score']        = sprintf("%.2f",($item["teacher_effect"]+$item["teacher_quality"]+$item["teacher_interact"])/3 );
            $item["tea_nick"]             = $this->cache_get_teacher_nick($item["teacherid"]);
            $item["require_admin_nick"]   = $this->cache_get_account_nick($item["require_adminid"]);
            $item["teacher_comment"]      = trim($item["teacher_comment"]);
            $item['teacher_effect_str']   = @$lesson_comment[ $item['teacher_effect'] ];
            $item['teacher_quality_str']  = @$lesson_comment[ $item['teacher_quality'] ];
            $item['teacher_interact_str'] = @$lesson_comment[ $item['teacher_interact'] ];
            $item['stu_stability_str']    = @$lesson_comment[ $item['stu_stability'] ];
            $item['lesson_diff']          = $item['lesson_end'] - $item['lesson_start'];
            $item["lesson_user_online_status_str"] = \App\Helper\Common::get_set_boolean_color_str(
                $item["lesson_user_online_status"]
            );
            $item["lesson_del_flag_str"] = \App\Helper\Common::get_set_boolean_color_str($item["lesson_del_flag"]);
            $item["room_name"] = \App\Helper\Utils::gen_roomid_name($item["lesson_type"],$item["courseid"],$item["lesson_num"]);


            \App\Helper\Utils::unixtime2date_for_item($item,"confirm_time");
            $item["confirm_admin_nick"] = $this->cache_get_account_nick($item["confirm_adminid"]);
            E\Econfirm_flag::set_item_value_str($item);
            E\Egrade::set_item_value_str($item);
            E\Esubject::set_item_value_str($item);
            if(!empty($item["ass_test_lesson_type"])){
                E\Eass_test_lesson_type::set_item_value_str($item);
            }else{
                $item["ass_test_lesson_type_str"]="";
            }

            $item["lesson_user_online_status_str"] = \App\Helper\Common::get_set_boolean_color_str(
                $item["lesson_user_online_status"]
            );
            if ($item["lesson_status"] == 2) {
                if ($item["confirm_flag"] ==2 || $item["confirm_flag"] ==3 ) {
                    $lesson_count_fail_all+= $item["lesson_count"];
                }else {
                    $lesson_count_all+= $item["lesson_count"];
                }
            }

            $item['lesson_deduct']="";
            foreach($lesson_deduct_key as $deduct_key => $deduct_val){
                if($item[$deduct_val]>0){
                    $item['lesson_deduct'] .= $lesson_deduct_info[$deduct_key]."|";
                }
            }
            $item['lesson_deduct']=trim($item['lesson_deduct'],"|");
            E\Etest_lesson_fail_flag::set_item_value_str($item);
            E\Esuccess_flag::set_item_value_str($item);
            \App\Helper\Utils::unixtime2date_for_item($item, "test_confirm_time","_str");
            $this->cache_set_item_account_nick($item,"test_confirm_adminid","test_confirm_admin_nick");

        }
        $seller_list      = $this->t_admin_group->get_admin_list_by_gorupid(E\Eaccount_role::V_1 );
        $adminid          = $this->get_account_id();
        $self_groupid     = $this->t_admin_group_user->get_groupid_by_adminid(2 , $adminid );
        $get_self_adminid = $this->t_admin_group_name->get_master_adminid($self_groupid);
        if($adminid == $get_self_adminid){
            $is_group_leader_flag = 1;
        }else{
            $is_group_leader_flag = 0;
        }


        $ret_str  = $this->pageView(__METHOD__,$ret_info, [
            "lesson_count_all"      => $lesson_count_all,
            "lesson_count_fail_all" => $lesson_count_fail_all,
            "seller_list"           => $seller_list,
            "self_groupid"          => $self_groupid,
            "is_group_leader_flag"  => $is_group_leader_flag,
            "is_tea"                => $is_tea,
            "adminid"               => $adminid,
            "acc"                   => $acc,
            "account_role"          => $this->get_account_role()
        ]);
        $response = new \Illuminate\Http\Response($ret_str);

        return  $response->withCookie(cookie('lesson_type',$lesson_type , 45000))
          ->withCookie(cookie('subject', $subject, 45000));
    }


    /**
     * 老师课程列表 type_flag=1实时播放二维码/　type_flag=2录制视频回放
     */
    public function get_tea_pad_lesson_qr(){
        $lessonid = $this->get_in_lessonid();
        $type_flag = $this->get_in_int_val('type_flag', 1);
        $ret_arr=$this->t_lesson_info->field_get_list($lessonid,"*");
        $ret_arr["roomid"]= \App\Helper\Utils::gen_roomid_name( $ret_arr["lesson_type"],
                                $ret_arr["courseid"], $ret_arr["lesson_num"]);

        $lesson_type=$ret_arr["lesson_type"];
        $server_type=$ret_arr["server_type"];


        $server_info= $this->t_lesson_info_b3->get_real_xmpp_server($lessonid) ;

        $ret_arr["webrtc"] = $server_info["ip"].":".  ($server_info["webrtc_port"] -(20061 -5061 ) )  ;
        $ret_arr["xmpp"]   = $server_info["ip"].":".  $server_info["xmpp_port"]  ;

        if($lesson_type<1000) {
            $ret_arr["type"]=1;
        }else if ($lesson_type<3000 ){
            $ret_arr["type"]=2;
        }else{
            $ret_arr["type"]=3;
        }

        $server_type= \App\Helper\Utils::get_lesson_server_type ($lesson_type,$server_type);

        if ($server_type==1){
            $ret_arr["audioService"]="leoedu";
        }else{
            $ret_arr["audioService"]="agora";
        }
        // dd($ret_arr);
        //图片信息
        if($type_flag ==1){
            $qr_info = "title=lessonid:{$lessonid}&beginTime={$ret_arr['lesson_start']}&endTime={$ret_arr['lesson_end']}&roomId={$ret_arr['roomid']}&xmpp={$ret_arr['xmpp']}&webrtc={$ret_arr['webrtc']}&ownerId={$ret_arr['teacherid']}&type={$ret_arr['type']}&audioService={$ret_arr['audioService']}";
        }else{
            $qr_info = "title=lessonid : {$lessonid}&beginTime={$ret_arr['real_begin_time']}&endTime={$ret_arr['real_end_time']}&drawUrl={$ret_arr['draw']}&audioUrl={$ret_arr['audio']}";
        }

        $base64_qr = base64_encode($qr_info);

        $lessonid_qr_name = $lessonid."_t_".$type_flag."_qr_new.png";
        $qiniu     = \App\Helper\Config::get_config("qiniu");
        $qiniu_url = $qiniu['public']['url'];
        $is_exists = \App\Helper\Utils::qiniu_file_stat($qiniu_url,$lessonid_qr_name);

        if(!$is_exists ){
            //text待转化为二维码的内容
            if($type_flag ==1){
                $text = "leoedu://meeting.leoedu.com/meeting=".$base64_qr;
            } else {
                $text = "leoedu://video.leoedu.com/video=".$base64_qr;
            }
            $qr_url         = "/tmp/".$lessonid.".png";
            $teacher_qr_url = "/tmp/".$lessonid_qr_name;

            //背景图
            $bg_url = "http://7u2f5q.com2.z0.glb.qiniucdn.com/b20278468cb5d4bc2dd1eaff3d843edd1507975354381.png";
            \App\Helper\Utils::get_qr_code_png($text,$qr_url,5,4,3);

            list($qr_width, $qr_height)=getimagesize($qr_url);
            //缩放比例
            // $per = round(157/$qr_width,3);
            $per = round(365/$qr_width,3);
            $n_w = $qr_width*$per;
            $n_h = $qr_height*$per;
            $new = imagecreatetruecolor($n_w, $n_h);
            $img = imagecreatefrompng($qr_url);
            //copy部分图像并调整
            imagecopyresized($new,$img,0,0,0,0,$n_w,$n_h,$qr_width,$qr_height);
            //图像输出新图片、另存为
            imagepng($new, $qr_url);
            imagedestroy($new);
            imagedestroy($img);


            $tea_nick = $this->cache_get_teacher_nick($ret_arr['teacherid']);
            $stu_nick = $this->cache_get_student_nick($ret_arr['userid']);
            $grade_str = E\Egrade::get_desc($ret_arr['grade']);
            $subject_str = E\Esubject::get_desc($ret_arr['subject']);
            $lesson_time = \App\Helper\Utils::fmt_lesson_time_new($ret_arr['lesson_start'],$ret_arr['lesson_end']);
            $image_title = $grade_str.$subject_str;
            $image_tea = "老师：".$tea_nick;
            $image_stu = "学生：".$stu_nick;
            $image_time = "时间：".$lesson_time;
            $image_lessonid = "课程：".$lessonid;
            $font_file1 = "fonts/Medium_50868_S60SC_C.ttf";

            //创建文字
            $text_url1 = "/tmp/".$lessonid."-text1.png";
            $im1 = imagecreatetruecolor(320, 60);
            $bkcolor = imagecolorallocate($im1, 255, 255, 255);
            imagefill($im1, 0, 0, $bkcolor);

            $fontcolor = imagecolorallocate($im1, 62,187,254);
            imagefttext($im1, 40, 0, 55, 50, $fontcolor, $font_file1, $image_title);
            imagepng($im1, $text_url1);
            imagedestroy($im1);

            $font_file2 = "fonts/Light_50868_S60SC_C.ttf";
            $text_url2 = "/tmp/".$lessonid."-text2.png";
            $im2 = imagecreatetruecolor(400, 180);
            imagefill($im2, 0, 0, $bkcolor);
            $fontcolor = imagecolorallocate($im2, 102,102,102);
            imagefttext($im2, 20, 0, 0, 40, $fontcolor, $font_file2, $image_tea);
            imagefttext($im2, 20, 0, 0, 80, $fontcolor, $font_file2, $image_stu);
            imagefttext($im2, 20, 0, 0, 120, $fontcolor, $font_file2, $image_time);
            imagefttext($im2, 20, 0, 0, 160, $fontcolor, $font_file2, $image_lessonid);
            imagepng($im2, $text_url2);
            imagedestroy($im2);

            $image_bg  = imagecreatefrompng($bg_url);
            $image_qr  = imagecreatefrompng($qr_url);
            $image_text1 = imagecreatefrompng($text_url1);
            $image_text2 = imagecreatefrompng($text_url2);

            $image_ret  = imageCreatetruecolor(imagesx($image_bg),imagesy($image_bg));

            imagecopyresampled($image_ret,$image_bg,0,0,0,0,imagesx($image_bg),imagesy($image_bg),imagesx($image_bg),imagesy($image_bg));
            imagecopymerge($image_ret,$image_qr,193,450,0,0,imagesx($image_qr),imagesy($image_qr),100);

            imagecopymerge($image_ret,$image_text1,215,130,0,0,imagesx($image_text1),imagesy($image_text1),100);
            imagecopymerge($image_ret,$image_text2,139,250,0,0,imagesx($image_text2),imagesy($image_text2),100);


            imagepng($image_ret,$teacher_qr_url);

            $file_name = \App\Helper\Utils::qiniu_upload($teacher_qr_url);

            if($file_name!=''){
                $cmd_rm = "rm /tmp/".$lessonid."*.png";
                \App\Helper\Utils::exec_cmd($cmd_rm);
            }

            imagedestroy($image_bg);
            imagedestroy($image_qr);
            imagedestroy($image_text1);
            imagedestroy($image_text2);
            imagedestroy($image_ret);
        }else{
            $file_name=$lessonid_qr_name;
        }

        $file_url = $qiniu_url."/".$file_name;
        // return $file_url;
        return $this->output_succ(["data"=>$file_url]);
    }


    public function set_test_lesson_comment(){
        $lessonid =  $this->get_in_int_val('lessonid');
        $courseware_flag =  $this->get_in_str_val('courseware_flag');
        $courseware_flag_score =  $this->get_in_int_val('courseware_flag_score');
        $lesson_preparation_content =  $this->get_in_str_val('lesson_preparation_content');
        $lesson_preparation_content_score =  $this->get_in_int_val('lesson_preparation_content_score');
        $courseware_quality =  $this->get_in_str_val('courseware_quality');
        $courseware_quality_score =  $this->get_in_int_val('courseware_quality_score');
        $tea_process_design =  $this->get_in_str_val('tea_process_design');
        $tea_process_design_score =  $this->get_in_int_val('tea_process_design_score');
        $class_atm =  $this->get_in_str_val('class_atm');
        $class_atm_score =  $this->get_in_int_val('class_atm_score');
        $knw_point =  $this->get_in_str_val('knw_point');
        $knw_point_score =  $this->get_in_int_val('knw_point_score');
        $dif_point =  $this->get_in_str_val('dif_point');
        $dif_point_score =  $this->get_in_int_val('dif_point_score');
        $teacher_blackboard_writing =  $this->get_in_str_val('teacher_blackboard_writing');
        $teacher_blackboard_writing_score =  $this->get_in_int_val('teacher_blackboard_writing_score');
        $tea_rhythm =  $this->get_in_str_val('tea_rhythm');
        $tea_rhythm_score =  $this->get_in_int_val('tea_rhythm_score');
        $language_performance =  $this->get_in_str_val('language_performance');
        $language_performance_score =  $this->get_in_int_val('language_performance_score');
        $content_fam_degree =  $this->get_in_str_val('content_fam_degree');
        $content_fam_degree_score =  $this->get_in_int_val('content_fam_degree_score');
        $answer_question_cre =  $this->get_in_str_val('answer_question_cre');
        $answer_question_cre_score =  $this->get_in_int_val('answer_question_cre_score');
        $tea_attitude =  $this->get_in_str_val('tea_attitude');
        $tea_attitude_score =  $this->get_in_int_val('tea_attitude_score');
        $tea_method =  $this->get_in_str_val('tea_method');
        $tea_method_score =  $this->get_in_int_val('tea_method_score');
        $tea_concentration =  $this->get_in_str_val('tea_concentration');
        $tea_concentration_score =  $this->get_in_int_val('tea_concentration_score');
        $tea_accident =  $this->get_in_str_val('tea_accident');
        $tea_accident_score =  $this->get_in_int_val('tea_accident_score');
        $tea_operation =  $this->get_in_str_val('tea_operation');
        $tea_operation_score =  $this->get_in_int_val('tea_operation_score');
        $tea_environment =  $this->get_in_str_val('tea_environment');
        $tea_environment_score =  $this->get_in_int_val('tea_environment_score');
        $class_abnormality =  $this->get_in_str_val('class_abnormality');
        $class_abnormality_score =  $this->get_in_int_val('class_abnormality_score');
        $test_lesson_advice =  trim($this->get_in_str_val('test_lesson_advice'));
        $test_lesson_score =  $this->get_in_int_val('test_lesson_score');
        $this->t_test_lesson_subject_sub_list->field_update_list($lessonid,[
            "courseware_flag"                       =>$courseware_flag,
            "courseware_flag_score"                 =>$courseware_flag_score,
            "lesson_preparation_content"            =>$lesson_preparation_content,
            "lesson_preparation_content_score"      =>$lesson_preparation_content_score,
            "courseware_quality"                    =>$courseware_quality,
            "courseware_quality_score"              =>$courseware_quality_score,
            "tea_process_design"                    =>$tea_process_design,
            "tea_process_design_score"              =>$tea_process_design,
            "class_atm"                             =>$class_atm,
            "class_atm_score"                       =>$class_atm_score,
            "knw_point"                             =>$knw_point,
            "knw_point_score"                       =>$knw_point_score,
            "dif_point"                            =>$dif_point,
            "dif_point_score"                      =>$dif_point_score,
            "teacher_blackboard_writing"            =>$teacher_blackboard_writing,
            "teacher_blackboard_writing_score"      =>$teacher_blackboard_writing_score,
            "tea_rhythm"                            =>$tea_rhythm,
            "tea_rhythm_score"                      =>$tea_rhythm_score,
            "language_performance"                  =>$language_performance,
            "language_performance_score"            =>$language_performance_score,
            "content_fam_degree"                    =>$content_fam_degree,
            "content_fam_degree_score"              =>$content_fam_degree_score,
            "answer_question_cre"                   =>$answer_question_cre,
            "answer_question_cre_score"             =>$answer_question_cre_score,
            "tea_attitude"                          =>$tea_attitude,
            "tea_attitude_score"                    =>$tea_attitude_score,
            "tea_method"                            =>$tea_method,
            "tea_method_score"                      =>$tea_method_score,
            "tea_concentration"                     =>$tea_concentration,
            "tea_concentration_score"               =>$tea_concentration_score,
            "tea_accident"                          =>$tea_accident,
            "tea_accident_score"                    =>$tea_accident_score,
            "tea_operation"                         =>$tea_operation,
            "tea_operation_score"                   =>$tea_operation_score,
            "tea_environment"                       =>$tea_environment,
            "tea_environment_score"                 =>$tea_environment_score,
            "class_abnormality"                     =>$class_abnormality,
            "class_abnormality_score"               =>$class_abnormality_score,
            "test_lesson_score"                     =>$test_lesson_score,
            "test_lesson_advice"                    =>$test_lesson_advice
        ]);

        return $this->output_succ();

    }
    public function lesson_list_ass(){
        $this->set_in_value("assistantid",$this->t_assistant_info->get_assistantid($this->get_account()));
        $this->set_in_value("test_seller_id",$this->get_account_id());
        return $this->lesson_list();
    }

    public function tea_lesson_list() {
        $this->set_in_value("is_with_test_user",0);
        $this->set_in_value("lesson_type",-2);

        return $this->lesson_list();
    }

    private function get_lesson_quiz_cfg($lesson_quiz_status, $lesson_type)
    {
        if ($lesson_type < 3001) {
            return '无需上传';
        }

        if ($lesson_quiz_status == 0) {
            return '未上传';
        } else {
            return '已上传';
        }
    }

    private function get_work_url($work_value)
    {
        switch($work_value['work_status']) {
        case 1:
            return $work_value['issue_url'];
        case 2:
            return $work_value['finish_url'];
        case 3:
            return $work_value['check_url'];
        case 4:
            return $work_value['tea_research_url'];
        case 5:
            return $work_value['ass_research_url'];
        default:
            return '';
        }
    }

    public function quiz_info()
    {
        $is_part_time = $this->get_in_int_val('is_part_time', -1 );
        $tea_nick     = $this->get_in_str_val('tea_nick', "" );
        $page_num     = $this->get_in_int_val('page_num', -1 );

        if($page_num < 1){
            $page_num = 1;
        }

        $ret_info = $this->t_teacher_info->get_tea_list_for_tea_manage($tea_nick, $is_part_time, $page_num);
        foreach($ret_info['list'] as $key => &$value){
            $ret_quiz           = $this->t_quiz_info->get_unchecked_quiz_by_teacherid($value['teacherid']);
            $value[	'tea_nick'] = $value['nick'];
            $value[	'quiz_num'] = $ret_quiz['num'];
        }

        return $this->pageView(__METHOD__,$ret_info);
    }

    public function change_open_teacher()
    {
        $courseid  = $this->get_in_int_val('courseid',0);
        $teacherid = $this->get_in_int_val('teacherid',0);
        $ret = $this->t_course_order->change_teacher($courseid, $teacherid);
        if($ret)
             return outputJson(array('ret' => 0, 'info' => '修改成功'));

        return outputJson(array('ret' => -1, 'info' => '修改失败'));
    }

    public function delete_open_lesson()
    {
        $lessonid = $this->get_in_int_val('lessonid',0);
        $ret      = $this->t_lesson_info->get_lesson_after($lessonid);
        if(!empty($ret))
           return outputJson(array('ret' => -1, 'info' => '还有在此课程之后的已排课程, 请先删除这些课程再重新操作'));
        $ret                             =  $this->t_lesson_info->delete_open_lesson($lessonid);
        if(!$ret)
           return outputJson(array('ret' => -1, 'info' => '删除失败'));
        return outputJson(array('ret' => 0));
    }

    public function search_role()
    {
        $role  = $this->get_in_int_val('role',0);
        $phone = $this->get_in_str_val('phone',"");
        $info  = $this->t_phone_to_user->get_info_from_role_phone($role,$phone);
        if(!$info)
            return outputJson(array('ret' => -1, 'info' => '该账号不存在'));
        return outputJson(array('ret' => 0, 'info' => $info));
    }

    public function opt_open_lesson_users()
    {
        $lessonid = $this->get_in_int_val('lessonid',-1);
        $opt_type = $this->get_in_str_val('opt_type',"");
        $userid   = $this->get_in_int_val('userid',-1);

        if ($opt_type == "del"){
            $ret             = $this->t_open_lesson_user->delete_open_lesson_user($lessonid,$userid);
        }else if ($opt_type == "add"){
            if(-1 == $userid || -1 == $lessonid){
               return outputJson_error( '错误的用户和课程id!');
            }
            $enter_type = $this->t_lesson_info->get_open_class_enter_type($lessonid);
            if($enter_type != POINT_TO_LESSON )
               return outputJson_error( '无法为该课程增加学生' );

            $ret = $this->t_open_lesson_user->get_open_class_user($lessonid,$userid);
            if(!empty($ret))
               return outputJson_error(  '该用户已被加入此课程中' );
            $this->t_open_lesson_user->add_open_class_user($lessonid,$userid);
        }

        $uid_arr = $this->t_open_lesson_user->get_open_class_users($lessonid);
        $stu_arr = array();
        foreach($uid_arr as $item){
            $ret       = $this->t_phone_to_user->get_phone_role_by_userid($item['userid']);
            $nick      = $this->t_student_info->get_user_nick_by_id($item['userid'], $ret['role']);
            $stu_arr[] = array(
                'userid' => $item['userid'],
                'nick'   => $nick,
                'phone'  => $ret['phone'],
            );
        }
       return outputjson_success(array( 'stu_list' => $stu_arr));
    }


    public function upload_open_cw()
    {
        $id  = $this->get_in_str_val('id', "");
        $url = $this->get_in_str_val('urlkey', "");

        $lessonid = substr($id, 3);

        $this->tea_manage_model->upload_files($lessonid, 0, $url);
        return outputJson(array('ret' => 0, 'info' => '上传成功'));
    }


    public function add_from_lessonid()
    {
        $courseid      = $this->get_in_int_val('courseid', 0);
        $from_lessonid = $this->get_in_int_val('from_lessonid', 0);

        $ret_info = $this->t_lesson_info->add_from_lessonid($courseid,$from_lessonid);

        return outputjson_success();
    }

    public function can_set_from_lessonid()
    {
        $lessonid = $this->get_in_int_val('lessonid', 0);
        $can_set  = $this->get_in_int_val('can_set', 0);

        $ret_info = $this->t_lesson_info->can_set_from_lessonid($lessonid,$can_set);

        return outputjson_success();

    }
    public function set_course_name() {
        $courseid = $this->get_in_courseid();
        $lessonid = $this->get_in_int_val("lessonid",0);
        $course_name = $this->get_in_str_val("course_name","");
        $lesson_intro1= $this->get_in_str_val("lesson_intro1","");
        $lesson_intro2= $this->get_in_str_val("lesson_intro2","");

        if($lesson_intro2){
            $lesson_intro=$lesson_intro1."|".$lesson_intro2;
        }else{
            $lesson_intro=$lesson_intro1;
        }

        $this->t_course_order->field_update_list($courseid,["course_name" => $course_name]);
        $this->t_lesson_info->field_update_list($lessonid,["lesson_intro" => $lesson_intro]);
        return outputjson_success();
    }

    public function get_teacherid(){
        $lessonid=$this->get_in_int_val('lessonid',0);

        $ret_info=$this->t_lesson_info->get_teacherid($lessonid);

        return $ret_info;
    }

    public function get_stu_performance(){
        $lessonid=$this->get_in_int_val('lessonid',0);
        $ret=$this->t_lesson_info->get_stu_performance($lessonid);
        \App\Helper\Utils::logger("xxooox1:".$ret);

        if($ret!=''){
            $ret_info = json_decode($ret,true);
            if(isset($ret_info['point_note_list']) && is_array($ret_info['point_note_list'])){
                foreach($ret_info['point_note_list'] as $key => $val){
                    $ret_info['point_name'][$key]     = $val['point_name'];
                    $ret_info['point_stu_desc'][$key] = $val['point_stu_desc'];
                }
            }
        }else{
            $ret_info='';
        }

        return $ret_info;
    }

    public function new_get_stu_performance(){
        $lessonid = $this->get_in_int_val('lessonid',0);

        $homework_situation = array_flip(E\Ehomework_situation::$desc_map);
        $content_grasp = array_flip(E\Econtent_grasp::$desc_map);
        $lesson_interact = array_flip(E\Elesson_interact::$desc_map);

        $ret = $this->t_lesson_info_b2->get_lesson_stu_performance($lessonid);
        if($ret['stu_performance']!=''){
            $ret_info = json_decode($ret['stu_performance'],true);
            $ret_info['homework_situation'] = $homework_situation[$ret_info['homework_situation']];
            $ret_info['content_grasp']      = $content_grasp[$ret_info['content_grasp']];
            $ret_info['lesson_interact']    = $lesson_interact[$ret_info['lesson_interact']];

            if(isset($ret_info['point_note_list']) && is_array($ret_info['point_note_list'])){
                foreach($ret_info['point_note_list'] as $key => $val){
                    $ret_info['point_name'][$key]     = $val['point_name'];
                    $ret_info['point_stu_desc'][$key] = $val['point_stu_desc'];
                }
            }else{
                $ret_info['point_name']=explode("|",$ret['lesson_intro']);
            }
        }else{
            $ret_info=explode("|",$ret['lesson_intro']);
        }


        return $ret_info;
    }

    public function new_set_stu_performance(){
        $lessonid                    = $this->get_in_int_val('lessonid',0);
        $ret_info['total_judgement'] = $this->get_in_str_val('total_judgement','');
        $homework_situation          = $this->get_in_str_val('homework_situation',0);
        $content_grasp               = $this->get_in_str_val('content_grasp',0);
        $lesson_interact             = $this->get_in_int_val('lesson_interact',0);
        $ret_info['stu_comment']     = $this->get_in_str_val('stu_comment','');
        $point_name                  = $this->get_in_str_val('point_name','');
        $point_stu_desc              = $this->get_in_str_val('point_stu_desc','');
        $point_name2                 = $this->get_in_str_val('point_name2','');
        $point_stu_desc2             = $this->get_in_str_val('point_stu_desc2','');

        $ret_info['homework_situation'] = E\Ehomework_situation::get_desc($homework_situation);
        $ret_info['content_grasp']      = E\Econtent_grasp::get_desc($content_grasp);
        $ret_info['lesson_interact']    = E\Elesson_interact::get_desc($lesson_interact);

        if($point_name!=''){
            $ret_info['point_note_list'][0]['point_name']     = $point_name;
            $ret_info['point_note_list'][0]['point_stu_desc'] = $point_stu_desc;
            if($point_name2!=''){
                $ret_info['point_note_list'][1]['point_name']     = $point_name2;
                $ret_info['point_note_list'][1]['point_stu_desc'] = $point_stu_desc2;
            }
        }else{
            $ret_info['point_note_list']='';
        }

        $stu_performance = json_encode($ret_info);
        $ret_info = $this->t_lesson_info->field_update_list($lessonid,[
            'stu_performance'   => $stu_performance,
            'ass_comment_audit' => 3,
            'tea_rate_time'     => time(NULL),
        ]);

        if(!$ret_info && $ret_info!=0){
            return outputjson_error('设置失败!');
        }

        // if(\App\Helper\Utils::check_env_is_release()){
        //     $post_url = "http://admin.leo1v1.com/common_new/send_wx_to_par?lessonid=$lessonid";
        //     $this->send_curl_post($post_url);
        // }

        return outputjson_success();
    }

    public function send_curl_post($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_exec($ch);
        curl_close($ch);
    }


    public function get_stu_performance_new(){
        $lessonid=$this->get_in_int_val('lessonid',0);
        #$lessonid = 30267;
        $ret=$this->t_lesson_info->get_stu_performance($lessonid);

        if($ret!=''){
            $ret_info = json_decode($ret,true);
            if(isset($ret_info['point_note_list']) && is_array($ret_info['point_note_list'])){
                foreach($ret_info['point_note_list'] as $key => $val){
                    $ret_info['point_name'][$key]     = $val['point_name'];
                    $ret_info['point_stu_desc'][$key] = $val['point_stu_desc'];
                }
            }
        }else{
            $ret_info='';
        }

        dd( $ret_info);
    }


    public function set_stu_performance(){
        $lessonid                       = $this->get_in_int_val('lessonid',0);
        $ret_info['total_judgement']    = $this->get_in_str_val('total_judgement','');
        $ret_info['homework_situation'] = $this->get_in_str_val('homework_situation','');
        $ret_info['content_grasp']      = $this->get_in_str_val('content_grasp','');
        $ret_info['lesson_interact']    = $this->get_in_str_val('lesson_interact','');
        $ret_info['stu_comment']    = $this->get_in_str_val('stu_comment','');
        $point_name                     = $this->get_in_str_val('point_name','');
        $point_stu_desc                 = $this->get_in_str_val('point_stu_desc','');
        $point_name2                    = $this->get_in_str_val('point_name2','');
        $point_stu_desc2                = $this->get_in_str_val('point_stu_desc2','');

        if($point_name!=''){
            $ret_info['point_note_list'][0]['point_name']     = $point_name;
            $ret_info['point_note_list'][0]['point_stu_desc'] = $point_stu_desc;
            if($point_name2!=''){
                $ret_info['point_note_list'][1]['point_name']     = $point_name2;
                $ret_info['point_note_list'][1]['point_stu_desc'] = $point_stu_desc2;
            }
        }else{
            $ret_info['point_note_list']='';
        }

        $stu_performance = json_encode($ret_info);
        $ret_info = $this->t_lesson_info->field_update_list($lessonid,array('stu_performance'=>$stu_performance));

        if(!$ret_info && $ret_info !=0)
            return outputjson_error('设置失败!');
        return outputjson_success();

    }

    public function update_tea_money()
    {
        $lessonid   = $this->get_in_int_val('lessonid',0);
        $tea_money  = $this->get_in_int_val('tea_money',0);

        $ret_info=$this->t_lesson_info->field_update_list($lessonid,array('tea_price'=>$tea_money));

        return outputjson_success();
    }

    public function get_lesson_name_and_intro(){
        $lessonid = $this->get_in_int_val('lessonid',0);

        $ret_info = $this->t_lesson_info->get_lesson_name_and_intro($lessonid);

        return outputjson_success(["data"=>$ret_info]);
    }

    public function set_lesson_info(){
        $lessonid      = $this->get_in_int_val('lessonid',0);
        $lesson_name   = $this->get_in_str_val('lesson_name','');
        $lesson_intro  = $this->get_in_str_val('lesson_intro','');
        $lesson_intro2 = $this->get_in_str_val('lesson_intro2','');

        if($lesson_intro2!=''){
            $lesson_intro=$lesson_intro."|".$lesson_intro2;
        }

        $ret_info = $this->t_lesson_info->field_update_list($lessonid,[
            "lesson_name"  => $lesson_name,
            "lesson_intro" => $lesson_intro
        ]);

        return outputjson_success();
    }

    public function add_teacher()
    {
        $tea_nick              = $this->get_in_str_val('tea_nick',"");
        $gender                = $this->get_in_int_val('gender', -1);
        $birth                 = $this->get_in_str_val('birth',"");
        $phone                 = $this->get_in_str_val('phone',"");
        $phone_spare           = $this->get_in_int_val('phone_spare',"");
        $email                 = $this->get_in_str_val('email',"");
        $level                 = $this->get_in_int_val('level',-1);
        $work_year             = $this->get_in_int_val('work_year', 0);
        $subject               = $this->get_in_int_val('subject',-1);
        $address               = $this->get_in_str_val('address',"");
        $school                = $this->get_in_str_val('school',"");
        $identity              = $this->get_in_int_val('identity',-1);
        $teacher_type          = $this->get_in_int_val('teacher_type',-1);
        $grade_part_ex         = $this->get_in_int_val('grade_part_ex',-1);
        $interview_access      = $this->get_in_str_val('interview_access',"");
        $teacher_money_type    = $this->get_in_int_val('teacher_money_type',-1);
        $trial_lecture_is_pass = $this->get_in_int_val('trial_lecture_is_pass',0);
        $face                  = $this->get_in_str_val('face',"");
        $textbook              = $this->get_in_str_val('textbook',"");
        $resume_url            = $this->get_in_str_val('resume_url',"");
        $create_time           = $this->get_in_int_val("create_time",time());
        $dialect_note          = $this->get_in_str_val("dialect_note","");
        $textbook_type         = $this->get_in_int_val("textbook_type",0);
        $interview_score       = $this->get_in_int_val("interview_score",0);
        $sshd_good             = $this->get_in_str_val("sshd_good","");
        $sshd_bad              = $this->get_in_str_val("sshd_bad","");
        $ktfw_good             = $this->get_in_str_val("ktfw_good","");
        $ktfw_bad              = $this->get_in_str_val("ktfw_bad","");
        $skgf_good             = $this->get_in_str_val("skgf_good","");
        $skgf_bad              = $this->get_in_str_val("skgf_bad","");
        $jsfg_good             = $this->get_in_str_val("jsfg_good","");
        $jsfg_bad              = $this->get_in_str_val("jsfg_bad","");
        $record_info           = $this->get_in_str_val("record_info","");
        $grade_start           = $this->get_in_int_val("grade_start",0);
        $grade_end             = $this->get_in_int_val("grade_end",0);
        $not_grade             = $this->get_in_str_val("not_grade","");
        $zs_id                 = $this->get_in_int_val('zs_id',-1);
        $wx_use_flag           = $this->get_in_int_val("wx_use_flag",1);
        $bankcard              = $this->get_in_str_val("bankcard");
        $bank_address          = $this->get_in_str_val("bank_address");
        $bank_account          = $this->get_in_str_val("bank_account");
        $train_through_new     = $this->get_in_int_val("train_through_new");
        $teacher_ref_type      = $this->get_in_int_val('teacher_ref_type',0);
        $account_role          = $this->get_account_role();
        $acc                   = $this->get_account();

        return $this->output_err("接口暂停");

        /**
         * 0 审核添加 1 后台添加
         */
        $add_type = $this->get_in_int_val("add_type");
        if ($teacher_money_type==-1) {
            return $this->output_err("工资分类没选!") ;
        }
        if(in_array($teacher_money_type,[1,2,3])){
            \App\Helper\Utils::logger("error teacher type:".$phone." account:".$this->get_account());
            return $this->output_err("此分类无法新加老师！");
        }
        if ($level==-1) {
            return $this->output_err("等级没选!") ;
        }
        if ($identity==-1) {
            return $this->output_err("请选择老师身份!") ;
        }
        if (strlen($phone)!=11) {
            return $this->output_err("电话号码不对") ;
        }
        if(($grade_start!=0 || $grade_end !=0) && $grade_start>$grade_end){
            return $this->output_err("年级范围出错");
        }
        if($teacher_money_type==0){
            $trial_lecture_is_pass = 1;
        }

        $ret_num = $this->t_phone_to_user->is_phone_valid($phone, E\Erole::V_TEACHER);
        if($ret_num['num'] > 0) {
            return $this->output_err("此号码已经存在") ;
        }

        srand(microtime(true) * 1000);
        $passwd = (int)$phone+rand(9999999999,99999999999);
        $passwd = substr($passwd, 0, 6);
        $md5_passwd = md5($passwd);

        $this->t_user_info->start_transaction();
        $this->t_user_info->row_insert([
            "passwd" => $md5_passwd,
        ]);
        $teacherid = $this->t_user_info->get_last_insertid();
        if (!$teacherid) {
            $this->t_user_info->rollback();
            return $this->output_err("生成用户出错");
        }

        $ret = $this->t_phone_to_user->add($phone,E\Erole::V_TEACHER,$teacherid) ;
        if (!$ret)  {
            $this->t_user_info->rollback();
            return $this->output_err("生成用户出错:电话->userid") ;
        }

        $reference      = $this->t_teacher_lecture_appointment_info->get_reference_by_phone($phone);
        $reference_info = $this->t_teacher_info->get_teacher_info_by_phone($reference);
        if($reference_info['teacher_type']>20){
            $teacher_ref_type = $reference_info['teacher_ref_type'];
        }else{
            // $teacher_ref_type = 0;
        }

        /**
         * 向db_ejabberd.users中添加用户信息
         */
        $this->t_teacher_info->add_teacher_info_to_ejabberd($teacherid,$md5_passwd);
        $ret = $this->t_teacher_info->add_new_teacher($tea_nick,$gender,$birth,$work_year,$phone,
                                                      $email,$teacher_type,$teacherid, $level, $teacher_money_type,
                                                      $create_time,$address,$subject,$school,$interview_access,
                                                      $grade_part_ex,$identity,$trial_lecture_is_pass,$face,$textbook,
                                                      $resume_url,$textbook_type,$dialect_note,$interview_score,$wx_use_flag,
                                                      $teacher_ref_type,$grade_start,$grade_end,$not_grade,$bankcard,
                                                      $bank_address,$bank_account,$phone_spare,$train_through_new,$acc,$zs_id
        );
        if (!$ret)  {
            $this->t_user_info->rollback();
            return $this->output_err("生成老师出错");
        }else{
            $this->t_user_info->commit();
            $this->set_teacher_label($teacherid,0,"",$sshd_good,3);
        }

        /**
         * 模板名称 :  老师注册通知
         * 模板ID   : SMS_55565027
         * 模板内容 :  ${name}老师您好，您已经成功注册理优教育平台，您的帐号是您的手机号，密码是：${passwd}，
         请用此帐号绑定“理优1对1老师帮”公众号，参加培训通过后即可成为理优正式授课老师。
         */
        $sign_name = \App\Helper\Utils::get_sms_sign_name();
        $arr = [
            "name"   => $tea_nick,
            "passwd" => $passwd,
        ];
        \App\Helper\Utils::sms_common($phone,55565027,$arr,0,$sign_name);

        if($record_info != ""){
            $this->t_teacher_record_list->row_insert([
                "teacherid"   => $teacherid,
                "type"        => 6,
                "record_info" => $record_info,
                "add_time"    => time(),
                "acc"         => $this->get_account(),
            ]);
        }

        $ret = $this->t_teacher_freetime_for_week->row_insert([
            "teacherid"  => $teacherid,
        ]);
        if(!$ret){
            return $this->output_err("老师生成成功，但创建每周空闲时间失败！");
        }

        if($add_type==1 && \App\Helper\Utils::check_env_is_release()){
            $teacher_money_type_str = E\Eteacher_money_type::get_desc($teacher_money_type);
            $level_str              = E\Elevel::get_desc($level);
            $send_info = $acc."后台添加老师:".$phone
                       ."老师类型为:".$teacher_money_type_str
                       ."等级为:".$level_str
                       ."添加时间:".date("Y-m-d H:i:s",time());
            $title     = "后台有添加老师记录!";
            \App\Helper\Utils::logger($send_info);
            \App\Helper\Utils::send_error_email("wg392567893@163.com",$title,$send_info);
            \App\Helper\Utils::send_error_email("erick@leoedu.com",$title,$send_info);
        }
        return $this->output_succ(["teacherid" => $teacherid]);
    }

    public function get_teacher_info_by_phone(){
        $phone = $this->get_in_int_val('phone',"");

        $res = $this->t_teacher_info->get_teacher_info_by_phone($phone);

        return $this->output_succ(["res"=>$res]);
    }


    /**
     * 添加机器人课程
     * @param from_lessonid 源课程id
     * @param lesson_start 机器人开始时间
     * @param lesson_num 连排天数
     * @return int/string
     */
    public function add_robot_lesson(){
        $from_lessonid = $this->get_in_int_val("from_lessonid");
        $lesson_start  = $this->get_in_str_val('lesson_start', date('Y-m-d', time(NULL)-86400*7));
        $lesson_num    = $this->get_in_int_val("lesson_num",1);

        if($from_lessonid==0)
            return $this->output_err("源课程不存在!");

        $start_time  = strtotime($lesson_start);
        $from_lesson = $this->t_lesson_info->get_all_lesson_info($from_lessonid);

        $time_difference = $from_lesson['lesson_end']-$from_lesson['lesson_start'];
        $end_time        = $start_time+$time_difference;

        $ret=$this->t_course_order->row_insert([
            "course_type" => 4001,
            "stu_total"   => 1000,
            "course_name" => $from_lesson["course_name"],
            "subject"     => $from_lesson["subject"],
            "grade"       => $from_lesson["grade"],
            "packageid"   => $from_lesson["packageid"],
        ]);
        $courseid=$this->t_course_order->get_last_courseid();

        for($i=0;$i<$lesson_num;$i++){
            $start = $start_time+86400*$i;
            $end   = $end_time+86400*$i;
            $this->t_lesson_info->row_insert([
                "from_lessonid"      => $from_lessonid,
                "lesson_type"        => 4001,
                "lesson_start"       => $start,
                "lesson_end"         => $end,
                "courseid"           => $courseid,
                "teacherid"          => $from_lesson['teacherid'],
                "subject"            => $from_lesson['subject'],
                "grade"              => $from_lesson['grade'],
                "lesson_num"         => $from_lesson['lesson_num'],
                "rand_num"           => 0,
                "stu_cw_upload_time" => time(),
                "stu_cw_status"      => 1,
                "stu_cw_url"         => $from_lesson['stu_cw_url'],
                "enter_type"         => 1,
                "rand_num"           => rand(300,500),
            ]);
        }

        return $this->output_succ();
    }

    public function course_plan_psychological(){
        $this->set_in_value("assistantid",-1);
        $this->set_in_value("subject",11);
        $this->set_in_value("lesson_type",2);
        return $this->course_plan();

    }
    public function course_plan(){
        list($start_time,$end_time)=$this->get_in_date_range(0,3);
        $page_num    = $this->get_in_page_num();
        $assistantid = $this->t_assistant_info->get_assistantid($this->get_account());
        if($assistantid == 0){
            $assistantid = -1;
        }
        $assistantid = $this->get_in_int_val("assistantid",$assistantid);
        $subject = $this->get_in_int_val("subject",-1);
        $lesson_type = $this->get_in_int_val("lesson_type",-1);
        $userid   = $this->get_in_int_val("studentid",-1);

        $ret_info = $this->t_lesson_info->get_lesson_info_ass($page_num,$start_time,$end_time,$assistantid,$userid,$subject,$lesson_type);
        foreach ($ret_info['list'] as &$item){

            if($item["subject"]==11){
                $item['teacher_nick'] = $this->t_teacher_info->get_realname($item['teacherid']);
            }else{
            $item['teacher_nick'] = $this->t_teacher_info->get_nick($item['teacherid']);
            }
            $item['user_nick'] = $this->t_student_info->get_nick($item['userid']);
            $item['level'] = $this->t_teacher_info->get_level($item['teacherid']);
            $item['teacher_money_type'] = $this->t_teacher_info->get_teacher_money_type($item['teacherid']);
            E\Egrade::set_item_value_str($item);
            E\Esubject::set_item_value_str($item);
            E\Eteacher_money_type::set_item_value_str($item);
            E\Elevel::set_item_value_str($item,"level");
            E\Elesson_status::set_item_value_str($item);
            E\Econfirm_flag::set_item_value_str($item);
            E\Elesson_cancel_reason_type::set_item_value_str($item);
            $item['lesson_start_str' ] = date('Y-m-d H:i:s',$item['lesson_start']);
            $item['lesson_end_str' ] = date('Y-m-d H:i:s',$item['lesson_end']);
            if(!empty($item['lesson_cancel_reason_next_lesson_time'])){
                $item['lesson_cancel_reason_next_lesson_time_str' ] = date('Y-m-d H:i',$item['lesson_cancel_reason_next_lesson_time']);
            }
            $item['lesson_diff' ] = $item['lesson_end']-$item['lesson_start'];
        }

        return $this->pageView(__METHOD__,$ret_info);
    }

    public function course_plan_stu_winter(){
        $adminid =$this->get_account_id();
        $acc= $this->get_account();
        $assistantid = $this->t_assistant_info->get_assistantid($this->get_account());
        if($assistantid <= 0 ){
            $assistantid = 1;
        }
        $account_role = $this->get_account_role();
        if($adminid==349 || $acc=="jim" || $account_role==12){
            $assistantid=-1;
        }
        list($start_time,$end_time)=$this->get_in_date_range(0,0,0,[],2);
        $is_done      = $this->get_in_int_val('is_done',-1);
        $assistantid  = $this->get_in_int_val('assistantid',$assistantid);
        $userid       = $this->get_in_int_val('userid',-1);
        $student_type = $this->get_in_int_val("student_type",0);
        if($assistantid <= 0 && $userid <= 0){
            $assistantid = 1;
        }

        $list = $this->t_student_info->get_stu_ass_all($assistantid,$userid,$student_type);
        $ass_userid = "";
        foreach($list['list'] as $val){
            $ass_userid .= $val['userid'].",";
        }
        $ass_userid      = "(".rtrim($ass_userid,",").")";
        $ret_lesson_info = $this->t_lesson_info->get_lesson_info_ass_all_new($start_time,$end_time,$ass_userid);
        $lesson_userid   = "";
        foreach($ret_lesson_info as $val){
            $lesson_userid .= $val['userid'].",";
        }

        $lesson_userid    = "(".rtrim($lesson_userid,",").")";
        $lesson_time_info = $this->t_lesson_info->get_lesson_info_time_new($start_time,$end_time,$lesson_userid);
        $plan_lessonid    = "";
        foreach($lesson_time_info as $val){
            $plan_lessonid .= $val['lessonid'].",";
        }

        $plan_lessonid = "(".rtrim($plan_lessonid,",").")";
        $ret_check_stu = $this->t_winter_week_regular_course->check_is_clash_stu_new($lesson_userid,$plan_lessonid,$start_time);
        $arr_check = [];
        $arr_lesson_count = [];
        $arr_lesson_count_all = [];
        $arr_lesson_count_diff = [];
        foreach($ret_check_stu['all'] as $item){
            $arr_check[$item['userid']] = $item['userid'];
            if(!isset($arr_lesson_count[ $item['userid']])){
                $arr_lesson_count[ $item['userid']]=0;
            }
            $arr_lesson_count[ $item['userid']]+=$item['lesson_count'];
        }
        foreach($ret_lesson_info as $key=>&$val){
            if(!in_array($key,$arr_check)){
                $val['is_clash'] = 1;
            }
            $arr_lesson_start = [];
            foreach ($ret_check_stu['all'] as $item){
                if($item['userid'] == $key){
                    $arr_lesson_start[] = $item['lesson_start'];
                }
            }
            if(!empty($arr_lesson_start)){
                $val['lesson_start'] = json_encode($arr_lesson_start);
            }
            foreach ($ret_check_stu['clash'] as $item){
                if($item['userid'] == $key){
                    $val['is_col'] = 1;
                }
            }
            $ret_lesson_info[$key]['count_diff'] = $val['lesson_total']-@$arr_lesson_count[$key];
        }

        $res_regular_info = $this->t_winter_week_regular_course->get_stu_count_total_new($ass_userid);
        $regular_userid = "";
        foreach($res_regular_info as $v){
            $regular_userid .= $v['userid'].",";
        }
        $regular_userid = "(".rtrim($regular_userid,",").")";
        $regular_lesson_info =$this->t_winter_week_regular_course->get_lesson_info_new($regular_userid);
        $regular_count_all = $plan_count_all = 0;
        foreach ($list['list'] as &$item){
            E\Egrade::set_item_value_str($item);
            if(isset($ret_lesson_info[$item['userid']])){
                $item['lesson_total'] = $ret_lesson_info[$item['userid']]['lesson_total'];
                if(isset($ret_lesson_info[$item['userid']]['is_col'])){
                    $item['is_col'] = $ret_lesson_info[$item['userid']]['is_col'];
                }
                if(isset($ret_lesson_info[$item['userid']]['is_clsah'])){
                    $item['is_clash'] = $ret_lesson_info[$item['userid']]['is_clsah'];
                }
                if(isset($ret_lesson_info[$item['userid']]['lesson_start'])){
                    $item['lesson_start'] = $ret_lesson_info[$item['userid']]['lesson_start'];
                }
            }else{
                $item['lesson_total'] = 0;
            }
            if(isset($ret_lesson_info[$item['userid']])  && $ret_lesson_info[$item['userid']]['count_diff']>0){
                $item['is_clash'] = 1;
            }
            if(!isset($item['is_clash'])){
                $item['is_clash'] = 0;
            }
            if(!isset($item['is_col'])){
                $item['is_col'] = 0;
            }
            E\Eboolean::set_item_value_str($item,"is_clash");
            E\Eboolean::set_item_value_str($item,"is_col");
            if(isset($res_regular_info[$item['userid']])){
                $item['regular_total'] = $res_regular_info[$item['userid']]['regular_total'];
                if( $item['lesson_total'] == $item['regular_total'] && $item['is_clash'] == 0){
                    $item['is_done'] = 1;
                    $item['is_done_str'] = "已完成排课";
                    $item['is_con']=1;
                }else{
                    $item['is_con']=0;
                    if($item['regular_total'] == @$arr_lesson_count[$item['userid']]){
                        $item['is_done'] = 1;
                        $item['is_done_str'] = "已完成排课";
                    }else{
                        $item['is_done'] = 0;
                        $item['is_done_str'] = "未完成排课";
                    }
                }
            }else{
                $item['regular_total'] = 0;
                $item['is_done'] = 0;
                $item['is_done_str'] = "常规课表为空!";
                if($item['lesson_total'] == 0){
                    $item['is_con']=1;
                }else{
                    $item['is_con']=0;
                }

            }
            E\Eboolean::set_item_value_str($item,"is_con");
            $regular_count_all += $item['regular_total'];
            $plan_count_all += $item['lesson_total'];
        }

        if($is_done == 0){
            foreach($list['list'] as $key=>$value){
                if($value['is_done']==1){
                    unset($list['list'][$key]);
                }
            }
        }elseif($is_done ==1){
            foreach($list['list'] as $key=>$value){
                if($value['is_done']== 0){
                    unset($list['list'][$key]);
                }
            }
        }

        $this->set_filed_for_js("ass_account_role",$account_role);
        return $this->pageView(__METHOD__,$list,["regular_count_all"=>$regular_count_all,"plan_count_all"=>$plan_count_all]);


    }

    public function course_plan_stu_summer(){
        $adminid =$this->get_account_id();
        $acc= $this->get_account();
        $assistantid = $this->t_assistant_info->get_assistantid($this->get_account());
        if($assistantid <= 0 ){
            $assistantid = 1;
        }
        if($adminid==349 || $acc=="jim"){
            $assistantid=-1;
        }
        list($start_time,$end_time)=$this->get_in_date_range(0,0,0,[],2);
        $is_done      = $this->get_in_int_val('is_done',-1);
        $assistantid  = $this->get_in_int_val('assistantid',$assistantid);
        $userid       = $this->get_in_int_val('userid',-1);
        $student_type = $this->get_in_int_val("student_type",0);
        if($assistantid <= 0 && $userid <= 0){
            $assistantid = 1;
        }

        $list = $this->t_student_info->get_stu_ass_all($assistantid,$userid,$student_type);
        $ass_userid = "";
        foreach($list['list'] as $val){
            $ass_userid .= $val['userid'].",";
        }
        $ass_userid      = "(".rtrim($ass_userid,",").")";
        $ret_lesson_info = $this->t_lesson_info->get_lesson_info_ass_all_new($start_time,$end_time,$ass_userid);
        $lesson_userid   = "";
        foreach($ret_lesson_info as $val){
            $lesson_userid .= $val['userid'].",";
        }

        $lesson_userid    = "(".rtrim($lesson_userid,",").")";
        $lesson_time_info = $this->t_lesson_info->get_lesson_info_time_new($start_time,$end_time,$lesson_userid);
        $plan_lessonid    = "";
        foreach($lesson_time_info as $val){
            $plan_lessonid .= $val['lessonid'].",";
        }

        $plan_lessonid = "(".rtrim($plan_lessonid,",").")";
        $ret_check_stu = $this->t_summer_week_regular_course->check_is_clash_stu_new($lesson_userid,$plan_lessonid,$start_time);
        $arr_check = [];
        $arr_lesson_count = [];
        $arr_lesson_count_all = [];
        $arr_lesson_count_diff = [];
        foreach($ret_check_stu['all'] as $item){
            $arr_check[$item['userid']] = $item['userid'];
            if(!isset($arr_lesson_count[ $item['userid']])){
                $arr_lesson_count[ $item['userid']]=0;
            }
            $arr_lesson_count[ $item['userid']]+=$item['lesson_count'];
        }
        foreach($ret_lesson_info as $key=>&$val){
            if(!in_array($key,$arr_check)){
                $val['is_clash'] = 1;
            }
            $arr_lesson_start = [];
            foreach ($ret_check_stu['all'] as $item){
                if($item['userid'] == $key){
                    $arr_lesson_start[] = $item['lesson_start'];
                }
            }
            if(!empty($arr_lesson_start)){
                $val['lesson_start'] = json_encode($arr_lesson_start);
            }
            foreach ($ret_check_stu['clash'] as $item){
                if($item['userid'] == $key){
                    $val['is_col'] = 1;
                }
            }
            $ret_lesson_info[$key]['count_diff'] = $val['lesson_total']-@$arr_lesson_count[$key];
        }

        $res_regular_info = $this->t_summer_week_regular_course->get_stu_count_total_new($ass_userid);
        $regular_userid = "";
        foreach($res_regular_info as $v){
            $regular_userid .= $v['userid'].",";
        }
        $regular_userid = "(".rtrim($regular_userid,",").")";
        $regular_lesson_info =$this->t_summer_week_regular_course->get_lesson_info_new($regular_userid);
        $regular_count_all = $plan_count_all = 0;
        foreach ($list['list'] as &$item){
            E\Egrade::set_item_value_str($item);
            if(isset($ret_lesson_info[$item['userid']])){
                $item['lesson_total'] = $ret_lesson_info[$item['userid']]['lesson_total'];
                if(isset($ret_lesson_info[$item['userid']]['is_col'])){
                    $item['is_col'] = $ret_lesson_info[$item['userid']]['is_col'];
                }
                if(isset($ret_lesson_info[$item['userid']]['is_clsah'])){
                    $item['is_clash'] = $ret_lesson_info[$item['userid']]['is_clsah'];
                }
                if(isset($ret_lesson_info[$item['userid']]['lesson_start'])){
                    $item['lesson_start'] = $ret_lesson_info[$item['userid']]['lesson_start'];
                }
            }else{
                $item['lesson_total'] = 0;
            }
            if(isset($ret_lesson_info[$item['userid']])  && $ret_lesson_info[$item['userid']]['count_diff']>0){
                $item['is_clash'] = 1;
            }
            if(!isset($item['is_clash'])){
                $item['is_clash'] = 0;
            }
            if(!isset($item['is_col'])){
                $item['is_col'] = 0;
            }
            E\Eboolean::set_item_value_str($item,"is_clash");
            E\Eboolean::set_item_value_str($item,"is_col");
            if(isset($res_regular_info[$item['userid']])){
                $item['regular_total'] = $res_regular_info[$item['userid']]['regular_total'];
                if( $item['lesson_total'] == $item['regular_total'] && $item['is_clash'] == 0){
                    $item['is_done'] = 1;
                    $item['is_done_str'] = "已完成排课";
                    $item['is_con']=1;
                }else{
                    $item['is_con']=0;
                    if($item['regular_total'] == @$arr_lesson_count[$item['userid']]){
                        $item['is_done'] = 1;
                        $item['is_done_str'] = "已完成排课";
                    }else{
                        $item['is_done'] = 0;
                        $item['is_done_str'] = "未完成排课";
                    }
                }
            }else{
                $item['regular_total'] = 0;
                $item['is_done'] = 0;
                $item['is_done_str'] = "常规课表为空!";
                if($item['lesson_total'] == 0){
                    $item['is_con']=1;
                }else{
                    $item['is_con']=0;
                }

            }
            E\Eboolean::set_item_value_str($item,"is_con");
            $regular_count_all += $item['regular_total'];
            $plan_count_all += $item['lesson_total'];
        }

        if($is_done == 0){
            foreach($list['list'] as $key=>$value){
                if($value['is_done']==1){
                    unset($list['list'][$key]);
                }
            }
        }elseif($is_done ==1){
            foreach($list['list'] as $key=>$value){
                if($value['is_done']== 0){
                    unset($list['list'][$key]);
                }
            }
        }

        return $this->pageView(__METHOD__,$list,["regular_count_all"=>$regular_count_all,"plan_count_all"=>$plan_count_all]);


    }
    public function course_plan_stu(){
        list($start_time,$end_time)=$this->get_in_date_range(0,0,0,[],2);
        $is_done      = $this->get_in_int_val('is_done',-1);
        $assistantid  = $this->get_in_int_val('assistantid',-1);
        $userid       = $this->get_in_int_val('userid',-1);
        $student_type = $this->get_in_int_val("student_type",0);
        if($assistantid <= 0 && $userid <= 0){
            $assistantid = 1;
        }

        $list = $this->t_student_info->get_stu_ass_all($assistantid,$userid,$student_type);
        $ass_userid = "";
        foreach($list['list'] as $val){
            $ass_userid .= $val['userid'].",";
        }
        $ass_userid      = "(".rtrim($ass_userid,",").")";
        $ret_lesson_info = $this->t_lesson_info->get_lesson_info_ass_all_new($start_time,$end_time,$ass_userid);
        $lesson_userid   = "";
        foreach($ret_lesson_info as $val){
            $lesson_userid .= $val['userid'].",";
        }

        $lesson_userid    = "(".rtrim($lesson_userid,",").")";
        $lesson_time_info = $this->t_lesson_info->get_lesson_info_time_new($start_time,$end_time,$lesson_userid);
        $plan_lessonid    = "";
        foreach($lesson_time_info as $val){
            $plan_lessonid .= $val['lessonid'].",";
        }

        $plan_lessonid = "(".rtrim($plan_lessonid,",").")";
        $ret_check_stu = $this->t_week_regular_course->check_is_clash_stu_new($lesson_userid,$plan_lessonid,$start_time);
        $arr_check = [];
        $arr_lesson_count = [];
        $arr_lesson_count_all = [];
        $arr_lesson_count_diff = [];
        foreach($ret_check_stu['all'] as $item){
            $arr_check[$item['userid']] = $item['userid'];
            if(!isset($arr_lesson_count[ $item['userid']])){
                $arr_lesson_count[ $item['userid']]=0;
            }
            $arr_lesson_count[ $item['userid']]+=$item['lesson_count'];
        }
        foreach($ret_lesson_info as $key=>&$val){
            if(!in_array($key,$arr_check)){
                $val['is_clash'] = 1;
            }
            $arr_lesson_start = [];
            foreach ($ret_check_stu['all'] as $item){
                if($item['userid'] == $key){
                    $arr_lesson_start[] = $item['lesson_start'];
                }
            }
            if(!empty($arr_lesson_start)){
               $val['lesson_start'] = json_encode($arr_lesson_start);
            }
            foreach ($ret_check_stu['clash'] as $item){
                if($item['userid'] == $key){
                    $val['is_col'] = 1;
                }
            }
            $ret_lesson_info[$key]['count_diff'] = $val['lesson_total']-@$arr_lesson_count[$key];
        }

        $res_regular_info = $this->t_week_regular_course->get_stu_count_total_new($ass_userid);
        $regular_userid = "";
        foreach($res_regular_info as $v){
            $regular_userid .= $v['userid'].",";
        }
        $regular_userid = "(".rtrim($regular_userid,",").")";
        $regular_lesson_info =$this->t_week_regular_course->get_lesson_info_new($regular_userid);
        $regular_count_all = $plan_count_all = 0;
        foreach ($list['list'] as &$item){
            E\Egrade::set_item_value_str($item);
            if(isset($ret_lesson_info[$item['userid']])){
                $item['lesson_total'] = $ret_lesson_info[$item['userid']]['lesson_total'];
                if(isset($ret_lesson_info[$item['userid']]['is_col'])){
                    $item['is_col'] = $ret_lesson_info[$item['userid']]['is_col'];
                }
                if(isset($ret_lesson_info[$item['userid']]['is_clsah'])){
                   $item['is_clash'] = $ret_lesson_info[$item['userid']]['is_clsah'];
                }
                if(isset($ret_lesson_info[$item['userid']]['lesson_start'])){
                    $item['lesson_start'] = $ret_lesson_info[$item['userid']]['lesson_start'];
                }
            }else{
                $item['lesson_total'] = 0;
            }
            if(isset($ret_lesson_info[$item['userid']])  && $ret_lesson_info[$item['userid']]['count_diff']>0){
                $item['is_clash'] = 1;
            }
            if(!isset($item['is_clash'])){
                $item['is_clash'] = 0;
            }
            if(!isset($item['is_col'])){
                $item['is_col'] = 0;
            }
            E\Eboolean::set_item_value_str($item,"is_clash");
            E\Eboolean::set_item_value_str($item,"is_col");
            if(isset($res_regular_info[$item['userid']])){
                $item['regular_total'] = $res_regular_info[$item['userid']]['regular_total'];
                if( $item['lesson_total'] == $item['regular_total'] && $item['is_clash'] == 0){
                    $item['is_done'] = 1;
                    $item['is_done_str'] = "已完成排课";
                    $item['is_con']=1;
                }else{
                    $item['is_con']=0;
                    if($item['regular_total'] == @$arr_lesson_count[$item['userid']]){
                        $item['is_done'] = 1;
                        $item['is_done_str'] = "已完成排课";
                    }else{
                        $item['is_done'] = 0;
                        $item['is_done_str'] = "未完成排课";
                    }
                }
            }else{
                $item['regular_total'] = 0;
                $item['is_done'] = 0;
                $item['is_done_str'] = "常规课表为空!";
                if($item['lesson_total'] == 0){
                    $item['is_con']=1;
                }else{
                    $item['is_con']=0;
                }

            }
            E\Eboolean::set_item_value_str($item,"is_con");
            $regular_count_all += $item['regular_total'];
            $plan_count_all += $item['lesson_total'];
        }

        if($is_done == 0){
            foreach($list['list'] as $key=>$value){
                if($value['is_done']==1){
                    unset($list['list'][$key]);
                }
            }
        }elseif($is_done ==1){
            foreach($list['list'] as $key=>$value){
                if($value['is_done']== 0){
                    unset($list['list'][$key]);
                }
            }
        }

        return $this->pageView(__METHOD__,$list,["regular_count_all"=>$regular_count_all,"plan_count_all"=>$plan_count_all]);
    }

    public function course_plan_stu_ass(){
        $assistantid = $this->t_assistant_info->get_assistantid($this->get_account());
        if($assistantid <= 0){
            $assistantid = 1;
        }
        $this->set_in_value("assistantid",$assistantid );
        return $this->course_plan_stu();
    }

    public function course_set_new() {
        $teacherid    = $this->get_in_str_val('teacherid');
        $courseid     = $this->get_in_int_val('courseid');
        $userid       = $this->get_in_int_val('userid');
        $lesson_count = $this->get_in_int_val('lesson_count');
        $lesson_start = $this->get_in_str_val('lesson_start');
        $ymd          = @substr($lesson_start,0,10);
        $lesson_start = @strtotime($lesson_start);
        $lesson_end   = $this->get_in_str_val('lesson_end');
        $lesson_end   = @strtotime($ymd." ".$lesson_end);

        if (empty($teacherid) || empty($lesson_end) || empty($lesson_start) || empty($lesson_count)) {
            return $this->output_err("请填写完整!");
        }
        $item = $this->t_course_order->field_get_list($courseid,"*");
        if($item['lesson_grade_type']==0){
            $grade = $this->t_student_info->get_grade($userid);
        }elseif($item['lesson_grade_type']==1){
            $grade = $item['grade'];
        }else{
            return $this->output_err("学生课程年级出错！请在课程包列表中修改！");
        }

        $teacher_info = $this->t_teacher_info->field_get_list($item["teacherid"],"teacher_money_type,level");
        $default_lesson_count = 0;
        $lessonid = $this->t_lesson_info->add_lesson(
            $courseid,0,
            $userid,
            0,
            $item["course_type"],
            $teacherid,
            $item["assistantid"],
            0,0,
            $grade,
            $item["subject"],
            $default_lesson_count,
            $teacher_info["teacher_money_type"],
            $teacher_info["level"],
            $item["competition_flag"]
        );

        if ($lessonid) {
            $this->t_homework_info->add(
                $item["courseid"],
                0,
                $item["userid"],
                $lessonid,
                $item["grade"],
                $item["subject"]);
        }

        $this->t_lesson_info->reset_lesson_list($courseid);
        $db_teacherid = $this->t_lesson_info->get_teacherid($lessonid);
        $lesson_type  = $this->t_lesson_info->get_lesson_type($lessonid);
        $status       = $this->t_lesson_info->get_lesson_status($lessonid);
        $teacher_info = $this->t_teacher_info->field_get_list($teacherid,"*");

        if ($status <=1) { //2 end
            $this->t_lesson_info->field_update_list($lessonid,[
                "teacherid"          => $teacherid,
                "teacher_money_type" => $teacher_info["teacher_money_type"],
                "level"              => $teacher_info["level"],
                "lesson_start"       => $lesson_start,
                "lesson_end"         => $lesson_end,
                "lesson_count"         => $lesson_count
            ]);
            $this->t_homework_info->field_update_list($lessonid,[
                "teacherid"  =>$teacherid,
            ]);
        }


        return $this->output_succ("");
    }
    public function get_course_info (){
        $courseid   = $this->get_in_int_val("courseid");
        $course_info = $this->t_course_order->field_get_list($courseid,"teacherid,default_lesson_count");
        $course_info['default_lesson_count'] = $course_info['default_lesson_count']/100;
        return outputjson_success(array('course_info' => $course_info ));
    }
    public function get_course_list ()
    {
        $userid    = $this->get_in_userid();
        $page_num     = $this->get_in_page_num();
        $ret_info   = $this->t_course_order->get_all_list($page_num,$userid);
        foreach($ret_info['list'] as &$item){
            $item['subject']      = E\Esubject::get_desc ($item['subject' ]) ;
            $item['grade'] = E\Egrade::get_desc($item['grade']);
            $item['course_status'] = E\Ecourse_status::get_desc($item['course_status']);
            $item['course_type'] = E\Econtract_type::get_desc($item['course_type']);
            $item['teacher_nick'] = $this->t_teacher_info->get_nick($item['teacherid']);
            $item['default_lesson_count'] = $item['default_lesson_count']/100;
            $item['lesson_total'] = $item['lesson_total'];
            $item['lesson_left'] = $item['lesson_left'];
        }
        $ret_info["page_info"] = $this->get_page_info_for_js( $ret_info["page_info"]   );
        return outputjson_success(array('data' => $ret_info));
    }

    public function get_course_list_new ()
    {
        $userid    = $this->get_in_userid();
        //  $userid = 162854;
        $page_num     = $this->get_in_page_num();
        $ret_info   = $this->t_course_order->get_all_list_new($page_num,$userid);
        foreach($ret_info['list'] as $k=>&$item){
            $item['subject']      = E\Esubject::get_desc ($item['subject' ]) ;
            $item['grade'] = E\Egrade::get_desc($item['grade']);
            $item['course_status'] = E\Ecourse_status::get_desc($item['course_status']);
            $item['course_type'] = E\Econtract_type::get_desc($item['course_type']);
            $item['teacher_nick'] = $this->t_teacher_info->get_nick($item['teacherid']);
            $item['default_lesson_count'] = $item['default_lesson_count']/100;
            $item['lesson_total'] = $item['lesson_total'];
            $item['lesson_left'] = $item['lesson_left'];
            /* if($item['lesson_left']==0){
                unset($ret_info["list"][$k]);
                }*/
        }
        // $ret_info["page_info"] = $this->get_page_info_for_js( $ret_info["page_info"]   );
        //dd($ret_info);
        return outputjson_success(array('data' => $ret_info));
    }


    public function get_user_video_info(){
        $lessonid = $this->get_in_int_val("lessonid");
        $ret_info = $this->t_user_video_info->get_info_by_lessonid($lessonid);
        return outputjson_success(array('data' => $ret_info));
    }

    public function train_lesson_list_fulltime(){
        $this->t_lesson_info->switch_tongji_database();
        list($start_time,$end_time) = $this->get_in_date_range(0,0,0,null,3);
        $page_num        = $this->get_in_page_num();
        $teacherid       = $this->get_in_int_val("teacherid",-1);
        $lesson_status   = $this->get_in_int_val("lesson_status",-1);
        $train_type      = $this->get_in_int_val("train_type",7);
        $acc             = $this->get_account();
        $ret_info = $this->t_lesson_info->get_train_lesson(
            $page_num,$start_time,$end_time,$teacherid,$lesson_status,
            -1,-1,$train_type
        );
        $server_name_map=$this->t_xmpp_server_config->get_server_name_map();

        $n = 1;
        foreach($ret_info['list'] as &$val){
            \App\Helper\Utils::unixtime2date_range($val);
            E\Esubject::set_item_value_str($val);
            E\Egrade::set_item_value_str($val);
            E\Elesson_status::set_item_value_str($val);
            E\Econtract_type::set_item_value_str($val,"lesson_type");
            if($val['tea_cw_url']==""){
                $val['cw_status']="未上传";
            }else{
                $val['cw_status']="已上传";
            }

            $val['index']  = $n;
            $n++;
            $xmpp_server_name= $val["xmpp_server_name"];
            $current_server= $val["current_server"];
            $server_info   = $this->t_lesson_info_b3->eval_real_xmpp_server($xmpp_server_name,$current_server,$server_name_map) ;

            $val['region'] = @$server_info['region'];
            $val['ip']     = @$server_info['ip'];
            $val['port']   = @$server_info['webrtc_port'];
            $val['server_type_str'] = \App\Helper\Utils::get_server_type_str($val);
        }

        return $this->pageView(__METHOD__,$ret_info,[
            "acc" => $acc
        ]);

    }
    public function train_lesson_list_research(){
        return $this->train_lesson_list();
    }

    public function train_lesson_list(){
        list($start_time,$end_time) = $this->get_in_date_range(0,0,0,null,3);
        $teacherid       = $this->get_in_int_val("teacherid",-1);
        $lesson_status   = $this->get_in_int_val("lesson_status",-1);
        $lesson_type     = $this->get_in_int_val("lesson_type",1100);
        $lessonid        = $this->get_in_int_val("lessonid",-1);
        $lesson_sub_type = $this->get_in_int_val("lesson_sub_type",-1);
        $train_type      = $this->get_in_int_val("train_type",-1);
        $page_num        = $this->get_in_page_num();

        $this->t_lesson_info->switch_tongji_database();
        $ret_info = $this->t_lesson_info->get_train_lesson(
            $page_num,$start_time,$end_time,$teacherid,$lesson_status,
            $lessonid,$lesson_sub_type,$train_type
        );
        $server_name_map=$this->t_xmpp_server_config->get_server_name_map();

        $n = 1;
        foreach($ret_info['list'] as &$val){
            \App\Helper\Utils::unixtime2date_range($val);
            E\Esubject::set_item_value_str($val);
            E\Egrade::set_item_value_str($val);
            E\Elesson_status::set_item_value_str($val);
            E\Econtract_type::set_item_value_str($val,"lesson_type");
            if($val['tea_cw_url']==""){
                $val['cw_status']="未上传";
            }else{
                $val['cw_status']="已上传";
            }

            $val['index']  = $n;
            $n++;

            $xmpp_server_name= $val["xmpp_server_name"];
            $current_server= $val["current_server"];
            $server_info   = $this->t_lesson_info_b3->eval_real_xmpp_server($xmpp_server_name,$current_server,$server_name_map) ;
            $server_info   = $this->t_lesson_info_b3->eval_real_xmpp_server($xmpp_server_name,$current_server,$server_name_map) ;
            $val['region'] = @$server_info['region'];
            $val['ip']     = @$server_info['ip'];
            $val['port']   = @$server_info['webrtc_port'];
            $val['server_type_str'] = \App\Helper\Utils::get_server_type_str($val);
        }

        return $this->pageView(__METHOD__,$ret_info);
    }

    public function add_train_lesson(){
        $lessonid        = $this->get_in_int_val("lessonid");
        $lesson_name     = $this->get_in_str_val("lesson_name");
        $lesson_start    = $this->get_in_str_val("lesson_start");
        $lesson_end      = $this->get_in_str_val("lesson_end");
        $teacherid       = $this->get_in_int_val("teacherid");
        $subject         = $this->get_in_int_val("subject");
        $grade           = $this->get_in_int_val("grade");
        $type            = $this->get_in_str_val("type","add");
        $lesson_sub_type = $this->get_in_int_val("lesson_sub_type");
        $train_type      = $this->get_in_int_val("train_type");

        if($teacherid<=0){
            return $this->output_err("请选择上课老师!");
        }
        if (\App\Helper\Utils::check_env_is_release()){
            if($lesson_name=="" || $lesson_start=="" || $lesson_end==""){
                return $this->output_err("数据不完整，请完善课程数据!");
            }
        }

        $start_time = strtotime($lesson_start);
        $end_time   = strtotime((date("Y-m-d",$start_time).$lesson_end));

        if($type=="add"){
            $courseid = $this->t_course_order->add_open_course($teacherid,$lesson_name,$grade,$subject,1100);
            $ret = $this->t_lesson_info->row_insert([
                "courseid"        => $courseid,
                "lesson_name"     => $lesson_name,
                "lesson_start"    => $start_time,
                "lesson_end"      => $end_time,
                "subject"         => $subject,
                "grade"           => $grade,
                "teacherid"       => $teacherid,
                "lesson_type"     => 1100,
                "server_type"     => 2,
                "lesson_sub_type" => $lesson_sub_type,
                "train_type"      => $train_type,
            ]);
        }elseif($type=="update" && $lessonid!=0){
            $old = $this->t_lesson_info->get_lesson_info($lessonid);
            if($old['lesson_status']==2 && ($old['lesson_start']!=$start_time || $old['lesson_end']!=$end_time)){
                return $this->output_err("课程已结束，无法更改课程时间！");
            }

            $ret = $this->t_lesson_info->field_update_list($lessonid,[
                "lesson_name"     => $lesson_name,
                "lesson_start"    => $start_time,
                "lesson_end"      => $end_time,
                "subject"         => $subject,
                "grade"           => $grade,
                "teacherid"       => $teacherid,
                "lesson_sub_type" => $lesson_sub_type,
                "train_type"      => $train_type,
            ]);
        }

        if($ret){
            return $this->output_succ();
        }else{
            return $this->output_err("操作出错，请重试！");
        }
    }

    public function get_train_lesson(){
        $lessonid = $this->get_in_int_val("lessonid");

        $lesson_info = $this->t_lesson_info->get_lesson_info($lessonid);
        \App\Helper\Utils::unixtime2date_for_item($lesson_info,"lesson_start","_str");
        \App\Helper\Utils::unixtime2date_for_item($lesson_info,"lesson_end","_str","H:i");

        if(empty($lesson_info)){
            return $this->output_err();
        }else{
            return $this->output_succ(["data"=>$lesson_info]);
        }
    }

    public function train_not_through_list_px(){
        return $this->train_not_through_list();
    }

    public function train_not_through_list(){
        list($start_time,$end_time) = $this->get_in_date_range(0,0,0,null,3);
        $is_all     = $this->get_in_int_val("is_all",-1);
        $has_openid = $this->get_in_int_val("has_openid",-1);

        if($is_all==1){
            $start_time = 0;
            $end_time   = 0;
        }

        $ret_info = $this->t_train_lesson_user->get_not_through_user($start_time,$end_time,$has_openid);
        foreach($ret_info['list'] as &$val){
            if($val['wx_openid']!=""){
                $val['has_openid_str']="已绑定";
            }else{
                $val['has_openid_str']="未绑定";
            }
            \App\Helper\Utils::unixtime2date_for_item($val,"create_time","_str");
        }

        return $this->pageView(__METHOD__,$ret_info);
    }

    public function train_is_through_list() { // 新师培训名单
        list($start_time,$end_time) = $this->get_in_date_range(0,0,0,null,3);
        $is_all     = $this->get_in_int_val("is_all",-1);
        $has_openid = $this->get_in_int_val("has_openid",-1);
        $grade      = $this->get_in_int_val("grade",-1);
        $subject    = $this->get_in_int_val("subject",-1);
        $is_pass    = $this->get_in_int_val("is_pass",-1);

        if($is_all==1){
            $start_time = 0;
            $end_time   = 0;
        }

        $ret_info = $this->t_train_lesson_user->get_is_through_user($start_time,$end_time,$has_openid, $subject, $grade, $is_pass);
        foreach($ret_info['list'] as &$val){
            if($val['wx_openid']!=""){
                $val['has_openid_str']="已绑定";
            }else{
                $val['has_openid_str']="未绑定";
            }
            \App\Helper\Utils::unixtime2date_for_item($val,"create_time","_str");
            \App\Helper\Utils::unixtime2date_for_item($val,"train_through_new_time","_str");
        }

        return $this->pageView(__METHOD__,$ret_info);
    }


    /**
     * 添加培训课程的参与者
     * @param type     新入职培训课程
     * @param lessonid 需要添加的课程id
     * @param userid   需要添加的待培训用户id
     */
    public function add_train_lesson_user(){
        $type           = $this->get_in_int_val("type");
        $subject        = trim($this->get_in_str_val("subject"),",");
        $grade_part_ex  = trim($this->get_in_str_val("grade_part_ex"),",");
        $create_day     = $this->get_in_str_val("create_day");
        $min_per        = $this->get_in_str_val("min_per");
        $max_per        = $this->get_in_str_val("max_per");
        $lessonid       = $this->get_in_int_val("lessonid");
        $userid         = $this->get_in_int_val("userid");
        $is_test_user   = $this->get_in_int_val("is_test_user");
        $has_limit      = $this->get_in_int_val("has_limit",-1);
        $is_freeze      = $this->get_in_int_val("is_freeze",-1);
        $train_lessonid = $this->get_in_int_val("train_lessonid",-1);
        $through_start  = strtotime($this->get_in_str_val("through_start"));
        $through_end    = strtotime($this->get_in_str_val("through_end"));

        $lesson_teacher = $this->t_lesson_info->get_teacherid($lessonid);
        if($userid!=0 && $lesson_teacher==$userid){
            return $this->output_err("该用户是本堂课的老师！无法添加！");
        }
        if($lessonid==0){
            return $this->output_err("课程id出错！");
        }

        $teacherid_list=[];
        if($userid>0){
            $check_user = $this->t_train_lesson_user->check_user_exists($lessonid,$userid);
            if($check_user){
                return $this->output_err("用户在此培训课程中！");
            }
            $teacherid_list = $userid;
        }else{
            if($type==4){
                $teacherid_list = $this->t_lesson_info_b2->get_trial_train_no_pass_list_b2(0);
            }elseif($type==5){
                $teacherid_list = $this->t_teacher_info->get_fulltime_teacher_by_time($through_start,$through_end);
            }else if($type==3){
                $train_teacher_list = [];
                $per_teacher_list   = [];
                if($train_lessonid>0){
                    $train_teacher_list = $this->t_train_lesson_user->get_train_through_teacher_list($train_lessonid,$lessonid);
                }
                if($min_per!="" || $max_per!=""){
                    $per_teacher_list = $this->t_teacher_info->get_need_join_train_teacher_list(
                        2,$lessonid,"","",0,$min_per,$max_per,0,-1,-1
                    );
                }
                $teacherid_list = array_merge($train_teacher_list,$per_teacher_list);
            }else{
                if($create_day != "" || $create_day != "0"){
                    $create_time = strtotime("-".$create_day."day",time());
                }else{
                    $create_time = 0;
                }

                $teacherid_list = $this->t_teacher_info->get_need_join_train_teacher_list(
                    $type,$lessonid,$subject,$grade_part_ex,$create_time,$min_per,$max_per,$is_test_user,$has_limit,$is_freeze
                );
            }
        }

        if($type==0){
            $check_flag = $this->t_train_lesson_user->check_user_exists($lessonid,$userid);
            $train_type = $this->t_lesson_info->get_train_type($lessonid);
            if(!$check_flag){
                $this->t_train_lesson_user->row_insert([
                    "lessonid" => $lessonid,
                    "userid"   => $userid,
                    "add_time" => time(),
                    "train_type"=>$train_type
                ]);
            }
        }else{
            $job = new \App\Jobs\AddUserToTrainLesson($lessonid,$teacherid_list,$type);
            dispatch($job);
        }

        return $this->output_succ();
    }

    /**
     * 获取培训课程人员名单
     * @param lessonid 培训课程id
     * @param type 0 参加培训名单 1 培训通过名单
     */
    public function get_train_lesson_user(){
        $lessonid = $this->get_in_int_val("lessonid");
        $type     = $this->get_in_int_val("type");

        $user_list = $this->t_train_lesson_user->get_train_lesson_user($lessonid,$type);
        foreach($user_list as &$user_val){
            E\Esubject::set_item_value_str($user_val);
        }

        $user_list = \App\Helper\Utils::list_to_page_info($user_list);
        return $this->output_succ(["data"=>$user_list]);
    }

    public function del_train_user(){
        $lessonid = $this->get_in_int_val("lessonid");
        $userid   = $this->get_in_int_val("userid");

        $ret=$this->t_train_lesson_user->row_delete_2($lessonid,$userid);

        return $this->output_succ();
    }

    public function change_enable_video(){
        $lessonid     = $this->get_in_int_val("lessonid");
        $enable_video = $this->get_in_int_val("enable_video");

        $ret=$this->t_lesson_info->field_update_list($lessonid,[
            "enable_video"=>$enable_video,
        ]);
        return $this->output_succ();
    }

    /**
     * 发送课后讲义
     */
    public function send_email_with_lessonid(){
        $lessonid   = $this->get_in_int_val("lessonid");
        $send_email = $this->get_in_str_val("stu_email");
        if($lessonid==0){
            return $this->output_err("lessonid 为空!");
        }

        $html_header="<div style=\"text-indent:2em;margin-\">家长您好!</div><br>"
                    ."<div style=\"text-indent:2em\">以下是您孩子本次课的课程讲义和课后作业,请及时下载</div><br>"
                    ."<div style=\"text-indent:2em\">本邮件由\"上海升学帮\"发送,若想获得更加便捷和专业的服务</div><br>"
                    ."<div style=\"text-indent:2em\">请务必下载\"上海升学帮\"关注孩子点滴成长!</div><br>"
                    ."<div style=\"color:red;text-indent:2em\">高效的学习,从课后巩固和完成作业开始</div></br>";
        $html_footer="<div style=\"color:#545454;text-indent:2em;margin-top:-3px\">单击文字即可下载</div><br> "
                    ."<div style=\"color:#545454;text-indent:2em\">(若下载中遇到任何问题,"
                    ."可直接联系学员的私人助教获得更多帮助)</div><br>"
                    ."<div style=\"margin-top:2px;font-weight:bold;text-indent:2em\">理优家长端\"上海升学帮\"功能介绍</div><br>"
                    ."<div style=\"text-indent:2em\">通过下载并使用\"上海升学帮\"可以全过程透明化的进行教学反馈,"
                    ."随时随地查看学员的上课情况、作业情况以及学员针对性的反馈与评价。</div><br>"
                    ."<div style=\"text-indent:2em\">无论身处何地,都能及时准确地了解您孩子的辅导情况!</div><br>"
                    ."<div style=\"text-indent:2em\">家长端APP下载"
                    ."<span style=\"color:#545454;ext-indent:2em\" >(请用手机的二维码扫描工具扫描下载)</span></div><br>"
                    ."<img src=\" http://admin.leo1v1.com/images/shsxb.png\" alt=\" 对不起,图片失效了\">";

        $lesson_info = $this->t_lesson_info->get_lesson_info_for_send_email_by_lessonid($lessonid);
        if(!empty($lesson_info)){
            foreach($lesson_info as $item){
                $lesson_start = date('Y-m-d H:i',$item['lesson_start']);
                $lesson_end   = date('H:i',$item['lesson_end']);
                if($send_email!=""){
                    $stu_email = $send_email;
                }else{
                    $stu_email = $item['stu_email'];
                }
                $tea_cw_url   = $item['tea_cw_url'];
                $work_status  = $item['work_status'];

                $html_work = "";
                if($work_status >= 1){
                    $homework_url = $item['issue_url'];
                    $homework_url_ex = config("admin")['monitor_new_url']."/common/email_open_address?url=".$homework_url;
                    $html_work = "<a href=".$homework_url_ex." target=\"_blank\" "
                      ." style=\"color:blue;text-indent:2em;display:block;margin-top:-3px\" >作业下载</a><br>";
                }

                $html_cw = "";
                if($tea_cw_url){
                    $tea_cw_url_ex = config("admin")['monitor_new_url']."/common/email_open_address?url=".$tea_cw_url;
                    $html_cw= "<a href=".$tea_cw_url_ex." target=\"_blank\" "
                            ." style=\"color:blue;text-indent:2em;display:block\">讲义下载</a><br>";
                }
                $html_title = $lesson_start."-".$lesson_end."课堂讲义";

                $html_download = $html_cw.$html_work;
                $html          = $html_header.$html_download.$html_footer;

                // \App\Helper\Common::send_mail_leo_com($stu_email,$html_title,$html);
                dispatch( new \App\Jobs\SendEmail($stu_email,$html_title,$html));
                $this->t_lesson_info->field_update_list($item['lessonid'],["lesson_end_todo_flag"=>1]);
            }
        }else{
            return $this->output_err("课程信息出错!");
        }
        return $this->output_succ();
    }

    public function get_stu_request(){
        $lessonid = $this->get_in_int_val("lessonid");
        $data     = $this->t_test_lesson_subject->get_stu_request($lessonid);
        if($data['stu_test_paper']){
            $has_paper = "\n有试卷\n<div class='btn btn-primary download_paper' data-paper='"
                .$data['stu_test_paper']."'>下载试卷</div>";
            if($data['tea_download_paper_time']>0){
                $has_paper .= "\n老师已下载";
            }else{
                $has_paper .= "\n老师未下载";
            }
        }else{
            $has_paper = "\n无试卷";
        }

        $info = $data['stu_request_test_lesson_demand'].$has_paper;
        return $this->output_succ(["data"=>$info]);
    }




    public function get_lesson_list(){
        $type        = $this->get_in_str_val("type","normal_lesson");
        $lesson_name = $this->get_in_str_val("lesson_name","");
        $page_num    = $this->get_in_page_num();

        $ret_list = [];
        $this->switch_tongji_database();
        if($type=="train_lesson"){
            $ret_list = $this->t_lesson_info_b2->get_train_lesson_list_for_select($page_num,$lesson_name);
        }

        return $this->output_ajax_table($ret_list);
    }

    public function trial_train_lesson_list_zj(){
        return $this->trial_train_lesson_list();
    }
    public function trial_train_lesson_list_zs(){
        return $this->trial_train_lesson_list();
    }

    public function trial_train_lesson_list(){
        list($start_time,$end_time,$opt_date_str) = $this->get_in_date_range(0,0,1,[
            1 => array("l.lesson_start","上课时间"),
            2 => array("tr.add_time", "审核时间"),
            ],3);

        $status        = $this->get_in_int_val("status",-1);
        $lesson_status = $this->get_in_int_val("lesson_status",2);
        $grade         = $this->get_in_int_val("grade",-1);
        $subject       = $this->get_in_int_val("subject",-1);
        $teacherid     = $this->get_in_int_val("teacherid",-1);
        $is_test       = $this->get_in_int_val("is_test",0);
        $teacher_type  = $this->get_in_int_val("teacher_type",-1);
        $page_num      = $this->get_in_page_num();
        $acc           = $this->get_account();
        $tea_subject = $this->get_admin_subject($this->get_account_id(),1);

        $ret_info = $this->t_teacher_record_list->get_trial_train_lesson_list(
            $page_num,$start_time,$end_time,$status,$grade,$subject,$teacherid,
            $is_test,$lesson_status,$tea_subject,$opt_date_str,$teacher_type
        );

        $train_from_lessonid_list = \App\Helper\Config::get_config("trian_lesson_from_lessonid","train_lesson");
        foreach($ret_info['list'] as &$val){
            if($val['subject']==0){
                $val['subject']=1;
            }
            if($val['grade']==0){
                $val['grade']=100;
            }
            if( $val['subject']>=4 && $val['grade']==100){
                $val['grade']=200;
            }
            $from_lessonid    = $train_from_lessonid_list[$val['subject']][$val['grade']];
            $from_lesson_info = $this->t_test_lesson_subject->get_from_lesson_info($from_lessonid);
            $val['stu_request_test_lesson_demand'] = $from_lesson_info['stu_request_test_lesson_demand'];
            E\Esubject::set_item_value_str($val);
            E\Egrade::set_item_value_str($val);
            E\Elesson_status::set_item_value_str($val);
            $val['lesson_time'] = \App\Helper\Utils::fmt_lesson_time($val['lesson_start'],$val['lesson_end']);
            E\Echeck_status::set_item_value_str($val,"trial_train_status");
            if($val["trial_train_num"]==1){
                $val["lesson_num"]="第一次课";
            }else{
                $val["lesson_num"]="第二次课";
            }
            \App\Helper\Utils::unixtime2date_for_item($val, "add_time","_str");
            if($from_lesson_info["stu_test_paper"]){
                $val["paper_url"] = \App\Helper\Utils::gen_download_url($from_lesson_info["stu_test_paper"]);
            }else{
                $val["paper_url"] = "";
            }

        }

        return $this->pageView(__METHOD__,$ret_info,[
            "acc" => $acc
        ]);
    }

    public function trial_train_no_pass_list(){
        $this->switch_tongji_database();
        list($start_time,$end_time) = $this->get_in_date_range(0,0,0,[],2);
        $subject = $this->get_in_int_val("subject",-1);
        $page_info = $this->get_in_page_info();
        $absenteeism_flag = $this->get_in_int_val("absenteeism_flag",0);
        $is_test_user = $this->get_in_int_val("is_test_user",0);
        $ret_info = $this->t_lesson_info_b2->get_trial_train_no_pass_list($page_info,$start_time,$end_time,$subject,$is_test_user,$absenteeism_flag);
        foreach($ret_info["list"] as &$item){
            E\Esubject::set_item_value_str($item);
            E\Eboolean::set_item_value_str($item,"absenteeism_flag");
            $item["add_time_str"] = date("Y-m-d H:i:s",$item["add_time"]);
            $item["lesson_start_str"] = date("Y-m-d H:i:s",$item["lesson_start"]);
        }
        return $this->pageView(__METHOD__,$ret_info);

    }

    public function set_teacher_record_account(){
        $id  = $this->get_in_int_val("id");
        $acc = $this->get_account();

        $old_acc = $this->t_teacher_record_list->get_acc($id);
        if($old_acc==""){
            $ret = $this->t_teacher_record_list->field_update_list($id,[
                "acc" => $acc
            ]);
            if(!$ret){
                return $this->output_err("更新负责人失败！请重试！");
            }
        }elseif($old_acc!=$acc){
            return $this->output_err("本视频已被其他人观看！");
        }

        return $this->output_succ();
    }


    public function tea_imgs_show(){
        $lessonid = $this->get_in_int_val('lessonid');

        $tea_cw_pic_str = $this->t_lesson_info_b2->get_tea_imgs_show($lessonid);

        $ret_info = explode(',',$tea_cw_pic_str);
        dd($ret_info);

        // return $this->pageView(__METHOD__,$ret_info);
    }


    public function train_lecture_lesson_fulltime(){
        $this->set_in_value("fulltime_flag",1);
        $this->set_in_value("is_all",0);
        return $this->train_lecture_lesson_zs();
    }

    public function train_lecture_lesson_zs(){
        $this->set_in_value("is_all",1);
        return $this->train_lecture_lesson();
    }

    public function train_lecture_lesson_zj(){
        $this->set_in_value("is_all",0);
        return $this->train_lecture_lesson();
    }

    public function train_lecture_lesson(){
        list($start_time,$end_time,$opt_date_str) = $this->get_in_date_range(0,0,1,[
            1 => array("l.lesson_start","面试时间"),
            2 => array("tl.add_time", "邀约时间"),
        ],1);
        $lesson_status    = $this->get_in_int_val("lesson_status",-1);
        $subject          = $this->get_in_int_val("subject",-1);
        $identity         = $this->get_in_int_val('identity',-1);
        $grade            = $this->get_in_int_val("grade",-1);
        $check_status     = $this->get_in_int_val("check_status",-2);
        $train_teacherid  = $this->get_in_int_val("train_teacherid",-1);
        $page_num         = $this->get_in_page_num();
        $adminid          = $this->get_account_id();
        $acc              = $this->get_account();
        $acc_role         = $this->get_account_role();
        $lessonid         = $this->get_in_int_val("lessonid",-1);
        $res_teacherid    = $this->get_in_int_val("res_teacherid",-1);
        $have_wx          = $this->get_in_int_val("have_wx",-1);
        $lecture_status   = $this->get_in_int_val("lecture_status",-1);
        $train_email_flag = $this->get_in_int_val("train_email_flag",-1);
        $is_all           = $this->get_in_int_val("is_all",2);
        $full_time        = $this->get_in_int_val("full_time",-1);
        $fulltime_flag    = $this->get_in_int_val("fulltime_flag");

        $recommend_teacherid = $this->get_in_str_val('teacherid',-1);

        if($recommend_teacherid == -1){
            $recommend_teacherid_phone = -1;
        }else{
            $recommend_teacherid_phone = $this->t_teacher_info->get_phone($recommend_teacherid);
        }

        //判断招师主管
        $is_master_flag = $this->t_admin_group_name->check_is_master(8,$adminid);
        //判断是否是招师
        $is_zs_flag = (($this->t_admin_group_user->get_main_type($adminid))==8)?1:0;
        if($is_zs_flag==1 && $is_master_flag !=1){
            // $accept_adminid = $adminid;
            if($train_teacherid > 0)//通过面试老师可以检索别人面试老师
                $accept_adminid = -1;
            else
                $accept_adminid = $adminid;
            $id_train_through_new=0;
        }else{
            $accept_adminid = -1;
            $id_train_through_new=-1;
        }

        $id_train_through_new_time = $this->get_in_int_val("id_train_through_new_time",-1);
        $id_train_through_new      = $this->get_in_int_val("id_train_through_new",$id_train_through_new);

        if($fulltime_flag==1 || $is_all==2){
            $full_time=1;
        }

        $this->switch_tongji_database();
        $teacherid = -1;$subject_eg=$grade_eg="";
        if(!in_array($acc,["adrian","夏宏东","ted","jim","ivy","jack","abby","amyshen","孙瞿","艾欣","林文彬"]) && $is_all==0){
            $teacher_info = $this->t_manager_info->get_teacher_info_by_adminid($adminid);
            if($teacher_info['teacherid']>0 ){
                $teacherid = $teacher_info['teacherid'];
            }else{
                $teacherid=0;
            }
        }elseif(!in_array($acc,["adrian","夏宏东","ted","jim","ivy","jack","abby","amyshen","孙瞿","艾欣","林文彬"]) && $is_all==2){
            list($subject_eg,$grade_eg)  = $this->get_1v1_subject_grade_permit($this->get_account_id());
        }

        $ret_info = $this->t_lesson_info_b2->train_lecture_lesson(
            $page_num,$start_time,$end_time,$lesson_status,$teacherid,
            $subject,$grade,$check_status,$train_teacherid,$lessonid,
            $res_teacherid,$have_wx,$lecture_status,$opt_date_str,
            $train_email_flag,$full_time,$id_train_through_new_time,
            $id_train_through_new,$accept_adminid,$identity,$recommend_teacherid_phone,
            $subject_eg,$grade_eg
        );

        foreach($ret_info['list'] as &$val){
            \App\Helper\Utils::unixtime2date_range($val);
            E\Elesson_status::set_item_value_str($val);
            E\Egrade::set_item_value_str($val);
            E\Esubject::set_item_value_str($val);
            E\Eboolean::set_item_value_str($val,"train_email_flag");
            if($val['trial_train_status']==-1){
                $status_str="未审核";
            }elseif($val['trial_train_status']==0){
                $status_str="<font color='red'>未通过</font>";
            }elseif($val['trial_train_status']==1){
                $status_str="<font color='green'>已通过</font>";
            }elseif($val['trial_train_status']==2){
                $status_str="<font color='blue'>老师未到</font>";
            }
            if($val['train_through_new_time'] >0){
                $val['train_status_str'] = "已通过";
            }else{
                $val['train_status_str'] = "未通过";
            }
            if($val['train_through_new'] == 1){
                $val['train_through_str'] = "已通过";
            }else{
                $val['train_through_str'] = "未通过";
            }
            $val['trial_train_status_str']=$status_str;
            $val['tea_nick'] = $this->cache_get_teacher_nick($val['l_teacherid']);
            if($val["lecture_status_ex"]==-2){
                $val['lecture_status_str']="无试讲";
            }else{
                $val['lecture_status_str']  = E\Echeck_status::get_desc($val['lecture_status_ex']);
            }
            if(empty($val["acc"])){
                $val["acc"] = $val["account"];
            }
            $val["add_time_str"] = date("Y-m-d H:i:s",$val["add_time"]);
            if($val["wx_openid"]){
                $val["have_wx_flag"] = "是";
            }else{
                $val["have_wx_flag"] = "否";
            }
            E\Eidentity::set_item_value_str($val,"identity");
            $val["phone_ex"] = preg_replace('/(1[356789]{1}[0-9])[0-9]{4}([0-9]{4})/i','$1****$2',$val["phone_spare"]);
        }

        $all_num = $this->t_lesson_info_b2->train_lecture_lesson_count(
            $start_time,$end_time,$opt_date_str
        );
        $wx_num = $this->t_lesson_info_b2->train_lecture_lesson_count(
            $start_time,$end_time,$opt_date_str,1
        );
        $email_num = $this->t_lesson_info_b2->train_lecture_lesson_count(
            $start_time,$end_time,$opt_date_str,-1,1
        );

        return $this->pageView(__METHOD__,$ret_info,[
            "acc"            => $acc,
            "acc_role"       => $acc_role,
            "all_num"        => $all_num,
            "wx_num"         => $wx_num,
            "email_num"      => $email_num,
            "is_all"         => $is_all,
            "fulltime_flag"  => $fulltime_flag
        ]);
    }

    /**
     * @param phone  老师手机号
     * @param flag   通过情况 0 未通过 1 通过 2 老师未到
     * @param record_info  面试评价
     * @param grade    年级
     * @param subject  科目
     */
    public function set_train_lecture_status(){
        $teacherid   = $this->get_in_int_val("teacherid");
        $lessonid    = $this->get_in_int_val("lessonid");
        $phone       = $this->get_in_str_val("phone");
        $nick        = $this->get_in_str_val("nick");
        $account     = $this->get_in_str_val("account");
        $flag        = $this->get_in_int_val("flag");
        $record_info = $this->get_in_str_val("record_info");
        $grade       = $this->get_in_int_val("grade");
        $subject     = $this->get_in_int_val("subject");
        $identity    = $this->get_in_int_val("identity");
        $acc         = $this->get_account();

        if($identity<=0 && $flag <2){
            return $this->output_err("请选择老师身份！");
        }

        //更新试讲预约老师类型
        $appointment_id = $this->t_teacher_lecture_appointment_info->get_appointment_id_by_phone($phone);
        $this->t_teacher_lecture_appointment_info->field_update_list($appointment_id,["teacher_type"=>$identity]);

        $teacher_info = $this->t_teacher_info->get_teacher_info_by_phone($phone);
        $this->t_teacher_lecture_appointment_info->reset_teacher_identity_by_phone($phone,$identity);
        if($flag==1){
            if(empty($teacher_info)){
                $teacher_info['phone']    = $phone;
                $teacher_info['tea_nick'] = $nick;
                $teacher_info['grade']    = $grade;
                $teacher_info['subject']  = $subject;
                $teacher_info['level']    = 0;
                $teacher_info['acc']      = $acc;
                $teacher_info['identity'] = $identity;
                $teacher_info['use_easy_pass'] = 2;
                $teacherid = $this->add_teacher_common($teacher_info);
                if(!$teacherid){
                    return $this->output_err("老师添加失败！");
                }
            }else{
                $check_info['subject'] = $subject;
                $check_info['grade']   = $grade;
                $this->set_teacher_grade($teacher_info,$check_info);
                $this->check_teacher_lecture_is_pass($teacher_info);
            }
        }

        $appointment_info = $this->t_teacher_lecture_appointment_info->get_simple_info($teacher_info['phone']);
        $full_time = $appointment_info['full_time'];
        //微信通知老师
        $wx_openid = $this->t_teacher_info->get_wx_openid_by_phone($phone);
        if($wx_openid && ($flag==1 || $flag==0)){
            /**
             * 模板ID   : rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o
             * 标题课程 : 待办事项提醒
             * {{first.DATA}}
             * 待办主题：{{keyword1.DATA}}
             * 待办内容：{{keyword2.DATA}}
             * 日期：{{keyword3.DATA}}
             * {{remark.DATA}}
             */
            $data=[];
            $url = "";
            if($full_time==0){
                $template_id = "rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o";
                if($flag==1){
                    $data['first']    = "老师您好,恭喜您已经成功通过试讲";
                    $data['keyword1'] = "通过";
                    $data['keyword2'] = "\n账号:".$phone
                                      ."\n密码:leo+手机号后4位"
                                      ."\n新师培训群号：315540732"
                                      ."\n请在【我的培训】或【培训课程】中查看培训课程,每周我们都会组织新入职老师的培训,帮助各位老师熟悉使用软件,提高教学技能,请您准时参加,培训通过后我们会及时给您安排试听课";
                    $data['keyword3'] = date("Y-m-d H:i",time());
                    $data['remark']   = "理优期待与你一起共同进步,提供高质量教学品质";
                    $url="https://jq.qq.com/?_wv=1027&k=4Bik1eq";
                }else if($flag==0){
                    $data['first']    = "老师您好,通过评审老师的1对1面试,很抱歉您没有通过面试审核,希望您再接再厉";
                    $data['keyword1'] = "未通过";
                    $data['keyword2'] = "\n您的面试反馈情况是".$record_info
                                      ."\n如果对于面试结果有疑问，请添加试讲答疑2群，群号：26592743";
                    $data['keyword3'] = date("Y-m-d H:i",time());
                    $data['remark']   = "理优教育致力于打造高水平的教学服务团队,期待您能通过下次面试,加油!如对面试结果有疑问,请联系招聘老师";
                    $url="https://jq.qq.com/?_wv=1027&k=4BiqfPA";
                }
            }elseif($full_time==1){
                /**
                   9glANaJcn7XATXo0fr86ifu0MEjfegz9Vl_zkB2nCjQ
                   {{first.DATA}}
                   评估内容：{{keyword1.DATA}}
                   评估结果：{{keyword2.DATA}}
                   时间：{{keyword3.DATA}}
                   {{remark.DATA}}
                 */
                $template_id = "9glANaJcn7XATXo0fr86ifu0MEjfegz9Vl_zkB2nCjQ";
                if($flag==1){
                    $data['first']="老师您好，恭喜您已经成功通过初试。";
                    $data['keyword1']="初试结果";
                    $data['keyword2']="通过";
                    $data['keyword3']=date("Y年m月d日 H:i:s");
                    $data['remark']="后续将有HR和您联系，请保持电话畅通。";

                }else{
                    $data['first']="老师您好，很抱歉您没有通过面试审核。";
                    $data['keyword1']="初试结果";
                    $data['keyword2']="未通过";
                    $data['keyword3']=date("Y年m月d日 H:i:s");
                    $data['remark']="感谢您的投递，您的简历已进入我公司的简历库，如有需要我们会与您取得联系。";
                }
            }
            \App\Helper\Utils::send_teacher_msg_for_wx($wx_openid,$template_id,$data,$url);
        }

        if($full_time==1 && $flag==1){
            $this->t_manager_info->send_wx_todo_msg_by_adminid (986,"全职老师一面通过","全职老师一面通过",$nick."老师一面通过","");
            $this->t_manager_info->send_wx_todo_msg_by_adminid (1043,"全职老师一面通过","全职老师一面通过",$nick."老师一面通过","");
            $this->t_manager_info->send_wx_todo_msg_by_adminid (349,"全职老师一面通过","全职老师一面通过",$nick."老师一面通过","");
        }

        $record_id = $this->t_teacher_record_list->check_have_record($teacherid,10,$lessonid);
        if($record_id){
            $ret = $this->t_teacher_record_list->field_update_list($record_id,[
                "record_info"        => $record_info,
                "trial_train_status" => $flag,
            ]);
        }else{
            $ret = $this->t_teacher_record_list->row_insert([
                "teacherid"          => $teacherid,
                "trial_train_status" => $flag,
                "train_lessonid"     => $lessonid,
                "record_info"        => $record_info,
                "add_time"           => time(),
                "type"               => 10,
                "current_acc"        => $acc,
                "acc"                => $account,
                "phone_spare"        => $phone
            ]);
        }
        if(!$ret){
            return $this->output_err("添加反馈失败！");
        }
        return $this->output_succ();
    }

    public function get_stu_performance_for_seller(){
        $lessonid=$this->get_in_int_val("lessonid");

        $stu_info=$this->t_test_lesson_subject_require->get_stu_performance_for_seller_by_lessonid($lessonid);
        if(empty($stu_info)){
            $stu_info=$this->t_seller_student_info->get_stu_performance_for_seller($lessonid);
        }

        return outputjson_success(array("data"=>$stu_info));
    }

    public function set_stu_performance_for_seller(){
        $lessonid               = $this->get_in_int_val("lessonid");
        $stu_lesson_content     = $this->get_in_str_val("stu_lesson_content");
        $stu_lesson_status      = $this->get_in_str_val("stu_lesson_status");
        $stu_study_status       = $this->get_in_str_val("stu_study_status");
        $stu_advantages         = trim($this->get_in_str_val("stu_advantages"),",");
        $stu_disadvantages      = trim($this->get_in_str_val("stu_disadvantages"),",");
        $stu_lesson_plan        = $this->get_in_str_val("stu_lesson_plan");
        $stu_teaching_direction = $this->get_in_str_val("stu_teaching_direction");
        $stu_advice             = $this->get_in_str_val("stu_advice");

        $lesson_type= $this->t_lesson_info->get_lesson_type($lessonid);
        if($lesson_type==2){
            $require_id = $this->t_test_lesson_subject_sub_list->get_require_id($lessonid);
            if(!empty($require_id)){
                $ret_info = $this->t_test_lesson_subject_require->field_update_list(
                    [["require_id=%u",$require_id]],
                    [
                        "stu_lesson_content"     => $stu_lesson_content,
                        "stu_lesson_status"      => $stu_lesson_status,
                        "stu_study_status"       => $stu_study_status,
                        "stu_advantages"         => $stu_advantages,
                        "stu_disadvantages"      => $stu_disadvantages,
                        "stu_lesson_plan"        => $stu_lesson_plan,
                        "stu_teaching_direction" => $stu_teaching_direction,
                        "stu_advice"             => $stu_advice,
                    ]
                );
            }else{
                $ret_info = $this->t_seller_student_info->field_update_list(
                    [["st_arrange_lessonid=%u",$lessonid]],
                    [
                        "stu_lesson_content"     => $stu_lesson_content,
                        "stu_lesson_status"      => $stu_lesson_status,
                        "stu_study_status"       => $stu_study_status,
                        "stu_advantages"         => $stu_advantages,
                        "stu_disadvantages"      => $stu_disadvantages,
                        "stu_lesson_plan"        => $stu_lesson_plan,
                        "stu_teaching_direction" => $stu_teaching_direction,
                        "stu_advice"             => $stu_advice,
                    ]
                );
            }


            if(!$ret_info){
                return outputjson_error("评价失败,请稍后再试");
            }else{
                $this->t_lesson_info->field_update_list($lessonid,[
                    'ass_comment_audit' => 3,
                    'tea_rate_time'     => time(),
                ]);
                return outputjson_success();
            }
        }else{
            $arr =  [
                "stu_lesson_content"     => $stu_lesson_content,
                "stu_lesson_status"      => $stu_lesson_status,
                "stu_study_status"       => $stu_study_status,
                "stu_advantages"         => $stu_advantages,
                "stu_disadvantages"      => $stu_disadvantages,
                "stu_lesson_plan"        => $stu_lesson_plan,
                "stu_teaching_direction" => $stu_teaching_direction,
                "stu_advice"             => $stu_advice,
            ];
            $stu_comment = json_encode($arr);

            $this->t_lesson_info->field_update_list($lessonid,[
                'ass_comment_audit' => 3,
                'tea_rate_time'     => time(),
                'stu_comment'       => $stu_comment
            ]);
            return outputjson_success();
        }

        // $require_id = $this->t_test_lesson_subject_sub_list->get_require_id($lessonid);
        // if(!empty($require_id)){
        //     $ret_info = $this->t_test_lesson_subject_require->field_update_list(
        //         [["require_id=%u",$require_id]],
        //         [
        //             "stu_lesson_content"     => $stu_lesson_content,
        //             "stu_lesson_status"      => $stu_lesson_status,
        //             "stu_study_status"       => $stu_study_status,
        //             "stu_advantages"         => $stu_advantages,
        //             "stu_disadvantages"      => $stu_disadvantages,
        //             "stu_lesson_plan"        => $stu_lesson_plan,
        //             "stu_teaching_direction" => $stu_teaching_direction,
        //             "stu_advice"             => $stu_advice,
        //         ]
        //     );
        // }else{
        //     $ret_info = $this->t_seller_student_info->field_update_list(
        //         [["st_arrange_lessonid=%u",$lessonid]],
        //         [
        //             "stu_lesson_content"     => $stu_lesson_content,
        //             "stu_lesson_status"      => $stu_lesson_status,
        //             "stu_study_status"       => $stu_study_status,
        //             "stu_advantages"         => $stu_advantages,
        //             "stu_disadvantages"      => $stu_disadvantages,
        //             "stu_lesson_plan"        => $stu_lesson_plan,
        //             "stu_teaching_direction" => $stu_teaching_direction,
        //             "stu_advice"             => $stu_advice,
        //         ]
        //     );
        // }


        // if(!$ret_info){
        //     return outputjson_error("评价失败,请稍后再试");
        // }else{
        //     $this->t_lesson_info->field_update_list($lessonid,[
        //         'ass_comment_audit' => 3,
        //         'tea_rate_time'     => time(),
        //     ]);
        //     return outputjson_success();
        // }
    }

    public function set_train_lecture_status_b1(){
        $teacherid   = $this->get_in_int_val("teacherid");
        $lessonid    = $this->get_in_int_val("lessonid");
        $phone       = $this->get_in_str_val("phone");
        $nick        = $this->get_in_str_val("nick");
        $account     = $this->get_in_str_val("account");
        $flag        = $this->get_in_int_val("flag");
        $record_info = $this->get_in_str_val("record_info");
        $grade       = $this->get_in_int_val("grade");
        $subject     = $this->get_in_int_val("subject");
        $identity    = $this->get_in_int_val("identity");
        $acc         = $this->get_account();
        $lecture_out_list  = $this->get_in_str_val("lecture_out_list");
        $teacher_info = $this->t_teacher_info->get_teacher_info_by_phone($phone);
        if(!$account){
            $account = $acc;
        }
        $this->t_teacher_lecture_appointment_info->reset_teacher_identity_by_phone($phone,$identity);
        if($flag==1){
            if(empty($teacher_info)){
                $teacher_info['phone']    = $phone;
                $teacher_info['tea_nick'] = $nick;
                $teacher_info['grade']    = $grade;
                $teacher_info['subject']  = $subject;
                $teacher_info['level']    = 1;
                $teacher_info['acc']      = $acc;
                $teacher_info['identity'] = $identity;
                $teacher_info['use_easy_pass'] = 2;
                $teacherid = $this->add_teacher_common($teacher_info);
                if(!$teacherid){
                    return $this->output_err("老师添加失败！");
                }
            }else{
                $check_info['subject'] = $subject;
                $check_info['grade']   = $grade;
                $this->set_teacher_grade($teacher_info,$check_info);
                $this->check_teacher_lecture_is_pass($teacher_info);
            }
        }

        $appointment_info = $this->t_teacher_lecture_appointment_info->get_simple_info($teacher_info['phone']);
        $full_time = $appointment_info['full_time'];
        //微信通知老师
        $wx_openid = $this->t_teacher_info->get_wx_openid_by_phone($phone);
        if($wx_openid && ($flag==1 || $flag==0)){
            /**
             * 模板ID   : rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o
             * 标题课程 : 待办事项提醒
             * {{first.DATA}}
             * 待办主题：{{keyword1.DATA}}
             * 待办内容：{{keyword2.DATA}}
             * 日期：{{keyword3.DATA}}
             * {{remark.DATA}}
             */
            $data=[];
            $url = "";
            if($full_time==0){
                $template_id = "rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o";
                if($flag==1){
                    $data['first']    = "老师您好,恭喜您已经成功通过试讲";
                    $data['keyword1'] = "通过";
                    $data['keyword2'] = "\n账号:".$phone
                                      ."\n密码:leo+手机号后4位"
                                      ."\n新师培训群号：315540732"
                                      ."\n请在【我的培训】或【培训课程】中查看培训课程,每周我们都会组织新入职老师的培训,帮助各位老师熟悉使用软件,提高教学技能,请您准时参加,培训通过后我们会及时给您安排试听课";
                    $data['keyword3'] = date("Y-m-d H:i",time());
                    $data['remark']   = "理优期待与你一起共同进步,提供高质量教学品质";
                    $url="https://jq.qq.com/?_wv=1027&k=4Bik1eq";
                }else if($flag==0){
                    $data['first']    = "老师您好,通过评审老师的1对1面试,很抱歉您没有通过面试审核,希望您再接再厉";
                    $data['keyword1'] = "未通过";
                    $data['keyword2'] = "\n您的面试反馈情况是".$record_info
                                      ."\n如果对于面试结果有疑问，请添加试讲答疑2群，群号：26592743";
                    $data['keyword3'] = date("Y-m-d H:i",time());
                    $data['remark']   = "理优教育致力于打造高水平的教学服务团队,期待您能通过下次面试,加油!如对面试结果有疑问,请联系招聘老师";
                    $url="https://jq.qq.com/?_wv=1027&k=4BiqfPA";
                }
            }elseif($full_time==1){
                /**
                   9glANaJcn7XATXo0fr86ifu0MEjfegz9Vl_zkB2nCjQ
                   {{first.DATA}}
                   评估内容：{{keyword1.DATA}}
                   评估结果：{{keyword2.DATA}}
                   时间：{{keyword3.DATA}}
                   {{remark.DATA}}
                 */
                $template_id = "9glANaJcn7XATXo0fr86ifu0MEjfegz9Vl_zkB2nCjQ";
                if($flag==1){
                    $data['first']="老师您好，恭喜您已经成功通过初试。";
                    $data['keyword1']="初试结果";
                    $data['keyword2']="通过";
                    $data['keyword3']=date("Y年m月d日 H:i:s");
                    $data['remark']="后续将有HR和您联系，请保持电话畅通。";
                    $this->t_manager_info->send_wx_todo_msg_by_adminid (986,"全职老师一面通过","全职老师一面通过",$nick."老师一面通过","");
                    $this->t_manager_info->send_wx_todo_msg_by_adminid (349,"全职老师一面通过","全职老师一面通过",$nick."老师一面通过","");
                    $this->t_manager_info->send_wx_todo_msg_by_adminid (1043,"全职老师一面通过","全职老师一面通过",$nick."老师一面通过","");



                }else{
                    $data['first']="老师您好，很抱歉您没有通过面试审核。";
                    $data['keyword1']="初试结果";
                    $data['keyword2']="未通过";
                    $data['keyword3']=date("Y年m月d日 H:i:s");
                    $data['remark']="感谢您的投递，您的简历已进入我公司的简历库，如有需要我们会与您取得联系。";
                }
            }
            \App\Helper\Utils::send_teacher_msg_for_wx($wx_openid,$template_id,$data,$url);
        }

        $record_id = $this->t_teacher_record_list->check_have_record($teacherid,10,$lessonid);
        if($record_id){
            $ret = $this->t_teacher_record_list->field_update_list($record_id,[
                "record_info"        => $record_info,
                "trial_train_status" => $flag,
                "lecture_out_list"   => $lecture_out_list
            ]);
        }else{
            $ret = $this->t_teacher_record_list->row_insert([
                "teacherid"          => $teacherid,
                "trial_train_status" => $flag,
                "train_lessonid"     => $lessonid,
                "record_info"        => $record_info,
                "add_time"           => time(),
                "type"               => 10,
                "current_acc"        => $acc,
                "acc"                => $account,
                "phone_spare"        => $phone,
                "lecture_out_list"   => $lecture_out_list
            ]);
        }
        if(!$ret){
            return $this->output_err("添加反馈失败！");
        }
        return $this->output_succ();
    }

    public function set_train_lecture_status_b2(){
        $teacherid   = $this->get_in_int_val("teacherid");
        $lessonid    = $this->get_in_int_val("lessonid");
        $record_lesson_list = $this->get_in_str_val("record_lesson_list","");
        $phone       = $this->get_in_str_val("phone");
        $nick        = $this->get_in_str_val("nick");
        $account     = $this->get_in_str_val("account");
        $flag        = $this->get_in_int_val("flag");
        $record_info = $this->get_in_str_val("record_info");
        $grade       = $this->get_in_int_val("grade");
        $subject     = $this->get_in_int_val("subject");
        $identity    = $this->get_in_int_val("identity");
        $acc         = $this->get_account();
        $reason                             = $this->get_in_str_val("record_info");
        $lecture_content_design_score       = $this->get_in_int_val("lecture_content_design_score");
        $lecture_combined_score             = $this->get_in_int_val("lecture_combined_score");
        $course_review_score                = $this->get_in_int_val("course_review_score");
        $teacher_mental_aura_score          = $this->get_in_int_val("teacher_mental_aura_score");
        $teacher_point_explanation_score    = $this->get_in_int_val("teacher_point_explanation_score");
        $teacher_class_atm_score            = $this->get_in_int_val("teacher_class_atm_score");
        $teacher_dif_point_score            = $this->get_in_int_val("teacher_dif_point_score");
        $teacher_blackboard_writing_score   = $this->get_in_int_val("teacher_blackboard_writing_score");
        $teacher_explain_rhythm_score       = $this->get_in_int_val("teacher_explain_rhythm_score");
        $teacher_language_performance_score = $this->get_in_int_val("teacher_language_performance_score");
        $sshd_good                          = $this->get_in_str_val("sshd_good");
        $new_tag_flag                     = $this->get_in_int_val("new_tag_flag",0);
        $style_character                  = $this->get_in_str_val("style_character");
        $professional_ability             = $this->get_in_str_val("professional_ability");
        $classroom_atmosphere             = $this->get_in_str_val("classroom_atmosphere");
        $courseware_requirements          = $this->get_in_str_val("courseware_requirements");
        $diathesis_cultivation            = $this->get_in_str_val("diathesis_cultivation");
        if(!$account){
            $account = $acc;
        }


        if($identity<=0 && $flag <2){
            return $this->output_err("请选择老师身份！");
        }

        //更新试讲预约老师类型
        $appointment_id = $this->t_teacher_lecture_appointment_info->get_appointment_id_by_phone($phone);
        $this->t_teacher_lecture_appointment_info->field_update_list($appointment_id,["teacher_type"=>$identity]);

        $teacher_detail_score = array(
            'lecture_content_design_score'   =>   $lecture_content_design_score,
            'lecture_combined_score'         =>   $lecture_combined_score,
            'course_review_score'            =>   $course_review_score,
            'teacher_mental_aura_score'      =>   $teacher_mental_aura_score,
            'teacher_point_explanation_score'=>   $teacher_point_explanation_score,
            'teacher_class_atm_score'        =>   $teacher_class_atm_score,
            'teacher_dif_point_score'        =>   $teacher_dif_point_score,
            'teacher_blackboard_writing_score'=>   $teacher_blackboard_writing_score,
            'teacher_explain_rhythm_score'   =>   $teacher_explain_rhythm_score,
            'teacher_language_performance_score'   =>   $teacher_language_performance_score
        );
        $teacher_detail_score = json_encode($teacher_detail_score);
        $teacher_lecture_score              = $this->get_in_int_val("total_score");//2
        $identity                           = $this->get_in_int_val("identity");
        $work_year                          = $this->get_in_int_val("work_year");//3
        $sshd_good                          = $this->get_in_str_val("sshd_good");//4
        $not_grade                          = $this->get_in_str_val("not_grade");//5
        $teacher_info = $this->t_teacher_info->get_teacher_info_by_phone($phone);
        $this->t_teacher_lecture_appointment_info->reset_teacher_identity_by_phone($phone,$identity);
        if($flag==1){
            if(empty($teacher_info)){
                $teacher_info['phone']    = $phone;
                $teacher_info['tea_nick'] = $nick;
                $teacher_info['grade']    = $grade;
                $teacher_info['subject']  = $subject;
                $teacher_info['level']    = 1;
                $teacher_info['acc']      = $acc;
                $teacher_info['identity'] = $identity;
                $teacher_info['use_easy_pass'] = 2;
                $teacherid = $this->add_teacher_common($teacher_info);
                if(!$teacherid){
                    return $this->output_err("老师添加失败！");
                }
            }else{
                $check_info['subject'] = $subject;
                $check_info['grade']   = $grade;
                $this->set_teacher_grade($teacher_info,$check_info);
                $this->check_teacher_lecture_is_pass($teacher_info);
            }
        }

        $appointment_info = $this->t_teacher_lecture_appointment_info->get_simple_info($teacher_info['phone']);
        if($appointment_info['teacher_type']!=$identity){
            $this->t_teacher_lecture_appointment_info->field_update_list($appointment_info['id'],[
                "teacher_type" => $identity
            ]);
        }

        $full_time = $appointment_info['full_time'];
        //微信通知老师
        $wx_openid = $this->t_teacher_info->get_wx_openid_by_phone($phone);
        if($wx_openid && ($flag==1 || $flag==0)){
            /**
             * 模板ID   : rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o
             * 标题课程 : 待办事项提醒
             * {{first.DATA}}
             * 待办主题：{{keyword1.DATA}}
             * 待办内容：{{keyword2.DATA}}
             * 日期：{{keyword3.DATA}}
             * {{remark.DATA}}
             */
            $data=[];
            $url = "";
            if($full_time==0){
                $template_id = "rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o";
                if($flag==1){
                    $data['first']    = "老师您好,恭喜您已经成功通过试讲";
                    $data['keyword1'] = "通过";
                    $data['keyword2'] = "\n账号:".$phone
                                      ."\n密码:leo+手机号后4位"
                                      ."\n新师培训群号：315540732"
                                      ."\n请在【我的培训】或【培训课程】中查看培训课程,每周我们都会组织新入职老师的培训,帮助各位老师熟悉使用软件,提高教学技能,请您准时参加,培训通过后我们会及时给您安排试听课";
                    $data['keyword3'] = date("Y-m-d H:i",time());
                    $data['remark']   = "理优期待与你一起共同进步,提供高质量教学品质";
                    $url="https://jq.qq.com/?_wv=1027&k=4Bik1eq";
                }else if($flag==0){
                    $data['first']    = "老师您好,通过评审老师的1对1面试,很抱歉您没有通过面试审核,希望您再接再厉";
                    $data['keyword1'] = "未通过";
                    $data['keyword2'] = "\n您的面试反馈情况是".$record_info
                                      ."\n如果对于面试结果有疑问，请添加试讲答疑2群，群号：26592743";
                    $data['keyword3'] = date("Y-m-d H:i",time());
                    $data['remark']   = "理优教育致力于打造高水平的教学服务团队,期待您能通过下次面试,加油!如对面试结果有疑问,请联系招聘老师";
                    $url="https://jq.qq.com/?_wv=1027&k=4BiqfPA";
                }
            }elseif($full_time==1){
                /**
                   9glANaJcn7XATXo0fr86ifu0MEjfegz9Vl_zkB2nCjQ
                   {{first.DATA}}
                   评估内容：{{keyword1.DATA}}
                   评估结果：{{keyword2.DATA}}
                   时间：{{keyword3.DATA}}
                   {{remark.DATA}}
                 */
                $template_id = "9glANaJcn7XATXo0fr86ifu0MEjfegz9Vl_zkB2nCjQ";
                if($flag==1){
                    $data['first']="老师您好，恭喜您已经成功通过初试。";
                    $data['keyword1']="初试结果";
                    $data['keyword2']="通过";
                    $data['keyword3']=date("Y年m月d日 H:i:s");
                    $data['remark']="后续将有HR和您联系，请保持电话畅通。";

                }else{
                    $data['first']="老师您好，很抱歉您没有通过面试审核。";
                    $data['keyword1']="初试结果";
                    $data['keyword2']="未通过";
                    $data['keyword3']=date("Y年m月d日 H:i:s");
                    $data['remark']="感谢您的投递，您的简历已进入我公司的简历库，如有需要我们会与您取得联系。";
                }
            }
            \App\Helper\Utils::send_teacher_msg_for_wx($wx_openid,$template_id,$data,$url);
        }

        if($full_time==1 && $flag==1){
            $this->t_manager_info->send_wx_todo_msg_by_adminid (986,"全职老师一面通过","全职老师一面通过",$nick."老师一面通过","");
            $this->t_manager_info->send_wx_todo_msg_by_adminid (1043,"全职老师一面通过","全职老师一面通过",$nick."老师一面通过","");
            $this->t_manager_info->send_wx_todo_msg_by_adminid (349,"全职老师一面通过","全职老师一面通过",$nick."老师一面通过","");
        }
        $record_id = $this->t_teacher_record_list->check_have_record($teacherid,10,$lessonid);
        if($record_id){
            $ret = $this->t_teacher_record_list->field_update_list($record_id,[
                "record_info"        => $record_info,
                "trial_train_status" => $flag,
                "record_info"        => $record_info,
            ]);
        }else{
            $ret = $this->t_teacher_record_list->row_insert([
                "teacherid"          => $teacherid,
                "trial_train_status" => $flag,
                "train_lessonid"     => $lessonid,
                "record_info"        => $record_info,
                "add_time"           => time(),
                "type"               => 10,
                "current_acc"        => $acc,
                "acc"                => $account,
                "phone_spare"        => $phone,
                "teacher_detail_score" => $teacher_detail_score,
                "teacher_lecture_score" => $teacher_lecture_score,
                "work_year"          => $work_year,
                "not_grade "         => $not_grade,
            ]);


            //设置标签

            if($new_tag_flag==0){
                $this->set_teacher_label($teacherid,$lessonid,$record_lesson_list,$sshd_good,2);
            }elseif($new_tag_flag==1){
                $tea_tag_arr=[
                    "style_character"=>$style_character,
                    "professional_ability"=>$professional_ability,
                    "classroom_atmosphere"=>$classroom_atmosphere,
                    "courseware_requirements"=>$courseware_requirements,
                    "diathesis_cultivation"=>$diathesis_cultivation,
                ];
                $this->set_teacher_label_new($teacherid,$lessonid,$record_lesson_list,$tea_tag_arr,2);
            }

        }
        if(!$ret){
            return $this->output_err("添加反馈失败！");
        }
        return $this->output_succ();
    }

     /**
     * @author    sam
     * @function  培训进度列表
     */
    public function  teacher_train_list () {

        list($start_time,$end_time) = $this->get_in_date_range(date("Y-m-01",time()),0,0,[],3);
        $train_type = $this->get_in_int_val("train_type",-1);
        $subject    = $this->get_in_int_val("subject",-1);
        $status     = $this->get_in_int_val("status",-1);
        //$userid = 99;
        $page_info=$this->get_in_page_info();

        $ret_info=$this->t_teacher_train_info->get_list($page_info,$start_time,$end_time,$train_type,$subject,$status);
        foreach( $ret_info["list"] as $key => &$item ) {
            $ret_info['list'][$key]['num'] = $key + 1;
            \App\Helper\Utils::unixtime2date_for_item($item,"create_time");
            \App\Helper\Utils::unixtime2date_for_item($item,"through_time");
            $this->cache_set_item_account_nick($item,"create_adminid","create_admin_nick" );
            $this->cache_set_item_teacher_nick($item);
            E\Esubject::set_item_value_str($item);
            E\Etrain_type::set_item_value_str($item);
            $item['train_status_str']  =  E\Etrain_status::get_desc($item['status']);
        }
        return $this->pageView(__METHOD__, $ret_info);
    }
    /**
     * @author    sam
     * @function  待培训名单
     */
    public function  teacher_cc_count () {

        list($start_time,$end_time) = $this->get_in_date_range(date("Y-m-01",time()),0,0,[],3);
        $subject          = $this->get_in_int_val("subject",-1);
        $grade_part_ex    = $this->get_in_int_val("grade_part_ex",-1);
        $tranfer_per      = $this->get_in_int_val("tranfer_per",-1);
        $teacherid             = $this->get_in_int_val('teacherid',-1);
        $test_lesson_flag      = $this->get_in_int_val('test_lesson_flag',-1);
        $test_lesson_num      = $this->get_in_int_val('test_lesson_num',-1);
        //$userid = 99;
        $page_info=$this->get_in_page_info();

        $ret_info = $this->t_lesson_info_b3->get_seller_test_lesson_tran_tea_count($page_info,$start_time,$end_time,-1,1,$subject,$grade_part_ex,$teacherid,$tranfer_per,$test_lesson_flag,$test_lesson_num);
        //$ret_info=$this->t_teacher_train_info->get_list($page_info,$start_time,$end_time,$train_type,$subject,$status);
        foreach( $ret_info['list'] as $key => &$item ) {
            $ret_info['list'][$key]['num'] = $key + 1;
            \App\Helper\Utils::unixtime2date_for_item($item,"train_through_new_time");
            $item['subject_str']  =  E\Esubject::get_desc($item['subject']);
            $item['grade_part_ex_str'] = E\Egrade_part_ex::get_desc($item['grade_part_ex']);
        }
        return $this->pageView(__METHOD__, $ret_info);
    }
    public function get_lesson_xmpp_audio(){
        $lessonid=$this->get_in_lessonid();
        $ret_arr=$this->t_lesson_info->field_get_list($lessonid,"*");
        $ret_arr["roomid"]= \App\Helper\Utils::gen_roomid_name( $ret_arr["lesson_type"],
                                $ret_arr["courseid"], $ret_arr["lesson_num"]);

        $lesson_type=$ret_arr["lesson_type"];
        $server_type=$ret_arr["server_type"];


        $server_info= $this->t_lesson_info_b3->get_real_xmpp_server($lessonid) ;

        $ret_arr["webrtc"] = $server_info["ip"].":".  ($server_info["webrtc_port"] -(20061 -5061 ) )  ;
        $ret_arr["xmpp"]   = $server_info["ip"].":".  $server_info["xmpp_port"]  ;


        if($lesson_type<1000) {
            $ret_arr["type"]=1;
        }else if ($lesson_type<3000 ){
            $ret_arr["type"]=2;
        }else{
            $ret_arr["type"]=3;
        }

        $server_type= \App\Helper\Utils::get_lesson_server_type ($lesson_type,$server_type);

        if ($server_type==1){
            $ret_arr["audioService"]="leoedu";
        }else{
            $ret_arr["audioService"]="agora";
        }

        return $this->output_succ(["data"=>$ret_arr]);
    }

    public function auto_rank_lesson(){
        return 1;
    }
    public function get_lesson_reply()
    {
        $lessonid = $this->get_in_int_val('lessonid', -1);
        if($lessonid == -1) {
            return $this->output_err("错误的课程id！");
        }

        //TODO 权限管理
        $ret_video= $this->t_lesson_info->field_get_list($lessonid, "audio, draw,userid ,real_begin_time");
        $userid=  $ret_video["userid"];
        if(empty($ret_video) || $ret_video['audio'] == "" || $ret_video['draw'] == "") {
            return $this->output_err("视频参数不完整1");
        }

        $nick = $this->t_student_info->get_nick($userid);
        $draw_url=\App\Helper\Common:: get_url_ex($ret_video['draw'] );
        $audio_url=\App\Helper\Common:: get_url_ex($ret_video['audio'] );

        return $this->output_succ([
            'draw_url' => $draw_url,'audio_url' => $audio_url,'real_begin_time' =>
            $ret_video['real_begin_time'],'stu_nick'=>$nick
        ]);
    }

    public function get_pdf_download_url_new()
    {
        $file_url = trim($this->get_in_str_val('file_url'));
        $qiniu_type = $this->get_in_str_val('qiniu_type');

        if (strlen($file_url) == 0) {
            return outputJson(array('ret' => -1, 'info' => '文件名为空', 'file' => $file_url));
        }

        if($qiniu_type ==0){ // 老师自己上传
            if (preg_match("/http/", $file_url)) {
                return $this->output_succ(['file'=>$file_url]);
            } else {
                return $this->output_succ(['file'=>urlencode($this->gen_download_url($file_url)),'file_ex'=> $this->gen_download_url($file_url)]);
            }
        }else{ // 使用理优讲义
            return $this->output_succ(['file'=>urlencode($this->get_teacher_note($file_url)),'file_ex'=> $this->get_teacher_note($file_url)]);
        }
    }

    public function gen_download_url($file_url)
    {
        $auth = new \Qiniu\Auth(
            \App\Helper\Config::get_qiniu_access_key(),
            \App\Helper\Config::get_qiniu_secret_key()
        );

        $file_url = \App\Helper\Config::get_qiniu_private_url()."/" .$file_url;

        $base_url=$auth->privateDownloadUrl($file_url );
        return $base_url;
    }

    public function get_teacher_note($file_link){
        $store=new \App\FileStore\file_store_tea();
        $auth=$store->get_auth();
        $authUrl = $auth->privateDownloadUrl("http://teacher-doc.leo1v1.com/". $file_link );
        return $authUrl;
    }



}
