<?php

namespace App\Console\Tasks;

use \App\Enums as E;
use \App\Models\Zgen as Z;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis ;
use \App\Helper;

class CommonTask extends TaskController
{
    function set_book_info_free() {
        return $this->t_book_info->free_on_no_deal();
    }

    function set_bind_phone_to_userid(){
        $key        = "set_bind_phone_to_userid";
        $redis      = Redis::connection();
        $start_time = $redis->get($key);
        echo "start_time:$start_time\n";

        if ( !$start_time ){
            $start_time = time(NULL)-3600;
        }
        $end_time = time(NULL);

        $ret_list = $this->t_student_info->get_closest_list($start_time,$end_time);
        foreach ($ret_list as $item ) {
            $userid=$this->t_book_info->get_userid_by_phone( $item["phone"]);
            if (!$userid )  { /* 未绑定 */
                $this->t_book_info->reset_userid($item["phone"], $item["userid"]);

                $sys_operator = $this->t_book_info->get_sys_operator_by_phone( $item["phone"]);
                if ($sys_operator) {
                    $row     = $this->t_manager_info->get_info_by_account( $sys_operator);
                    $message = $item["phone"];
                }else{ /* 新注册的用户 */
                    $this->t_book_info->row_insert([
                        Z\z_t_book_info::C_userid        => $item["userid"],
                        Z\z_t_book_info::C_grade         => $item["grade"],
                        Z\z_t_book_info::C_phone         => $item["phone"],
                        Z\z_t_book_info::C_book_time     => time(NULL),
                        Z\z_t_book_info::C_register_flag => 0,
                        Z\z_t_book_info::C_origin        => "注册",
                    ]);
                }
            }
            $has_pad = \App\Helper\Utils::get_pad_type($item["user_agent"]);
            $this->t_seller_student_info->add_or_add_to_sub("",$item["phone"],$item["grade"],"注册",0,$has_pad,0,"");
        }
        $redis->set($key,$end_time);
    }

    function notice_lesson_start_to_student(){
        $start  = time()+270;
        $end    = time()+330;

        $ret_id = $this->t_lesson_info->get_today_lesson_list($start,$end);
        if(is_array($ret_id)){
            $ret_list = $this->get_student_info($ret_id);
            foreach( $ret_list as $item ) {
                $userid       = $item["userid"];
                $lesson_start = $item["lesson_start"];
                $lesson_end   = $item["lesson_end"];
                $lessonid     = $item["lessonid"];
                $lesson_type  = $item["lesson_type"];
                if ($userid){
                    /* student msg */
                    $date_str = date('m月d日 H:i',$lesson_start)."-".date('H:i',$lesson_end);
                    $title    = $date_str."的课程即将开始，请立即进入课堂。";
                    $this->t_baidu_msg->baidu_push_msg($userid,$title,$lessonid,1003,1);
                    $message = array(
                        "push_num" => 1,
                        "lessonid" => (int)$lessonid,
                    );
                    \App\Helper\Net::baidu_push($userid,1003,$title,$message);

                    /* parent msg */
                    $stu_nick = $this->t_student_info->get_nick($item['userid']);
                    $parentid = $this->t_student_info->get_parentid($item['userid']);
                    if($lesson_type<1000){
                        $push_num=301;
                        $title_parent = $stu_nick.$date_str."的课程即将开始，请进入课堂陪读。";
                        $this->t_baidu_msg->baidu_push_msg($parentid,$title_parent,$lessonid,4001,$push_num);
                        $message_parent = array(
                            "push_num" => $push_num,
                            "lessonid" => (int)$lessonid,
                        );
                        \App\Helper\Net::baidu_push($parentid,4001,$title_parent,$message_parent);
                    }
                }
            }
        }
    }

    public function notice_lesson_start_to_teacher(){
        $start  = time()+870;
        $end    = time()+930;
        $ret_id = $this->t_lesson_info->get_today_tea_lesson_list($start,$end);

        if(is_array($ret_id)){
            foreach($ret_id as $val){
                $lessonid     = $val['lessonid'];
                $lesson_start = $val['lesson_start'];
                $lesson_end   = $val['lesson_end'];
                $userid       = $val['teacherid'];
                if($userid){
                    $date_str = date('m月d日 H:i',$lesson_start)."-".date('H:i',$lesson_end);
                    $title    = $date_str."的课程即将开始，请立即进入课堂。";
                    $this->t_baidu_msg->baidu_push_msg($userid,$title,$lessonid,2001,101);
                    $message=array(
                        "push_num" => 101,
                        "lessonid" => (int)$lessonid,
                    );
                    \App\Helper\Net::baidu_push($userid,'2004',$title,$message);
                }
            }
        }
    }

    function get_student_info($ret_id){
        $v1    = '';
        $open  = '';
        $small = '';
        foreach($ret_id as $val){
            if($val['lesson_type']<1000){
                $v1[]=$val['lessonid'];
            }else if($val['lesson_type']<2000 || $val['lesson_type']>4000){
                $open[]=$val['lessonid'];
            }else if($val['lesson_type']<4000 && $val['lesson_type']>3000){
                $small[]=$val['lessonid'];
            }
        }

        $v1_l    = is_array($v1)?implode(',',$v1):0;
        $open_l  = is_array($open)?implode(',',$open):0;
        $small_l = is_array($small)?implode(',',$small):0;

        $ret_1v1   = $this->t_lesson_info->get_1v1_user_list($v1_l);
        $ret_open  = $this->t_lesson_info->get_open_user_list($open_l);
        $ret_small = $this->t_lesson_info->get_small_user_list($small_l);
        $ret_list  = array_merge($ret_1v1,$ret_open,$ret_small);
        return $ret_list;
    }

    function notice_lesson_homework($type){
        if($type=1){
            $end   = strtotime(date("Y-m-d"))+86400;
            $start = $end-86400*2;
        }else{
            $start = time()-86400*2;
            $end   = $start+60;
        }
        $ret_id = $this->t_lesson_info->get_lesson_list_push_homework( $start, $end );
        $this->push_homework_by_lessonid($ret_id,$type);
    }

    function push_homework_by_lessonid($ret_id,$type){
        if(is_array($ret_id)){
            $ret_list = $this->get_homework_student_info($ret_id,$type);
            foreach( $ret_list as $item ){
                $userid       = $item["userid"];
                $lesson_start = $item["lesson_start"];
                $lesson_end   = $item["lesson_end"];
                $lesson_type  = $item['lesson_type'];
                $lessonid     = $item['lessonid'];
                if ($userid) {
                    $stu_nick = $this->t_student_info->get_nick($item['userid']);
                    $parentid = $this->t_student_info->get_parentid($item['userid']);
                    $date_str = date('m月d日 H:i', $lesson_start)."-".date('H:i', $lesson_end);
                    /* 2 pdf  3 题库 */
                    $push_num = $item['issue_time']==0?3:2;
                    if($type==1){
                        $title        = $date_str."的课程作业已布置，请及时完成。";
                        $title_parent = $stu_nick.$date_str."的课程作业已布置,请提醒孩子及时完成";
                        $message_type    = 1009;
                        $parent_msg_type = 4015;
                    }elseif($type==2){
                        $title        = $date_str."的课程作业未完成，请及时完成。";
                        $title_parent = $stu_nick.$date_str."的课程作业未完成,请提醒孩子抓紧完成";
                        $message_type    = 1012;
                        $parent_msg_type = 4008;
                    }

                    $this->change_homework_flag($lessonid,$userid,$lesson_type,$type);
                    $this->t_baidu_msg->baidu_push_msg($userid,$title,$lessonid,$message_type,$push_num);
                    if($push_num==2){
                        $message_parent=[
                            "push_num"=>302,
                            "lessonid"=>(int)$lessonid,
                        ];
                        $this->t_baidu_msg->baidu_push_msg($parentid,$title_parent,$lessonid,$parent_msg_type,302);
                    }
                    $message=[
                        "push_num" => $push_num,
                        "lessonid" => (int)$lessonid,
                    ];
                    \App\Helper\Net::baidu_push($userid,$message_type,$title,$message);
                    if(isset($message_parent)){
                        \App\Helper\Net::baidu_push($parentid,$parent_msg_type,$title_parent,$message_parent);
                    }
                }
            }
        }
    }

    function get_homework_student_info($ret_id,$type=1){
        $v1    = '';
        $small = '';
        foreach($ret_id as $val){
            if($val['lesson_type']<1000){
                $v1[] = $val['lessonid'];
            }elseif($val['lesson_type']<4000 && $val['lesson_type']>3000){
                $small[] = $val['lessonid'];
            }
        }

        $v1_l    = is_array($v1)?implode(',',$v1):true;
        $small_l = is_array($small)?implode(',',$small):true;

        $ret_1v1   = $this->t_homework_info->get_homework_user_list($v1_l,$type);
        $ret_small = $this->t_small_lesson_info->get_homework_user_list($small_l,$type);
        $ret_list  = array_merge($ret_1v1,$ret_small);
        return $ret_list;
    }

    /**
     * type的类型
     * 1 第一次提醒
     * 2 48小时作业提醒
     */
    function change_homework_flag($lessonid,$userid,$lesson_type,$type){
        if($lesson_type<1000){
            $ret = $this->t_homework_info->change_homework_flag($lessonid,$userid,$type);
        }else{
            $ret = $this->t_small_lesson_info->change_homework_flag($lessonid,$userid,$type);
        }
        return $ret;
    }

    /**
     * 机器人课程生成视屏命令
     */
    function set_upload_info(){
        $time_start = time()-570;
        $time_end   = time()-630;

        $ret_info = $this->t_lesson_info->get_lesson_list($time_start,$time_end);
        foreach($ret_info as $val){
            $this->t_lesson_info->set_upload_info($val['lessonid'],$val['real_begin_time'],$val['real_end_time']
                                                  ,$val['draw'],$val['audio']);
        }
    }

    /**
     * 1对1课程2天未评价给老师推送
     */
    function notice_teacher_set_stu_performance(){
        $start = time()-86430;
        $end   = $start+30;

        $ret_id = $this->t_lesson_info->get_lesson_list_not_set_performance($start,$end);
        if(is_array($ret_id)){
            $push_num = 106;
            foreach($ret_id as $val){
                $lesson_start = $val['lesson_start'];
                $lesson_end   = $val['lesson_end'];
                $date_str = date("m月d日 H:i",$lesson_start)."-".date("H:i",$lesson_end);
                $title    = $date_str."的课程未评价，请及时评价";

                $teacherid = $val['teacherid'];
                $lessonid  = $val['lessonid'];
                $this->t_baidu_msg->baidu_push_msg($teacherid,$title,$lessonid,2012,$push_num);
                $message=array(
                    "push_num" => $push_num,
                    "lessonid" => (int)$lessonid,
                );
                \App\Helper\Net::baidu_push($teacherid,'2012',$title,$message);
            }
        }
    }

}