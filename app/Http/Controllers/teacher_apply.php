<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use Illuminate\Support\Facades\Mail ;

class teacher_apply extends Controller
{
    var $check_login_flag = false;
    public function get_msg_num($phone) {
        return \App\Helper\Common::redis_set_json_date_add("WX_P_PHONE_$phone",1000000);

    }
    public function teacher_apply_list_two(){
        $cc_id = $this->get_account_id();
        $this->set_in_value("cc_id",$cc_id);
        return $this->teacher_apply_list();
    }
    public function  teacher_apply_list_one() {
        $cc_id = $this->get_account_id();
        $this->set_in_value("cc_id",$cc_id);
        return $this->teacher_apply_list();
    }

    public function teacher_apply_list() {
        $page_num    = $this->get_in_page_num();
        $page_info   = $this->get_in_page_info();
        $cc_id       = $this->get_in_int_val("cc_id");
        $ret_info    = $this->t_teacher_apply->get_teacher_apply_list($cc_id,$page_info);
        if($ret_info['list']){
            $lesson_info = $this->t_lesson_info->get_lesson_info_by_lessonid_new('lessonid,lesson_type,lesson_name',array_column($ret_info['list'],'lessonid'));
        }
        foreach($ret_info['list'] as &$item){
            foreach($lesson_info as $key=>$info){
                if($item['lessonid'] == $info['lessonid']){
                    $item['lesson_name'] = $info['lesson_name'];
                    $item['lesson_type'] = $info['lesson_type'];
                }else{
                    $item['lesson_type'] = 0;
                }
            }

            if($item['teacher_time']){
                $item['teacher_time'] = date('Y-m-d H:i:s',$item['teacher_time']);
            }else{
                $item['teacher_time'] = '';
            }
            if($item['cc_time']){
                $item['cc_time'] = date('Y-m-d H:i:s',$item['cc_time']);
            }else{
                $item['cc_time'] = '';
            }
            if($item['create_time']){
                $item['create_time'] = date('Y-m-d H:i:s',$item['create_time']);
            }else{
                $item['create_time'] = '';
            }

        }
        return $this->pageView(__METHOD__,$ret_info);
    }

    public function teacher_apply_edit() {
        // \App\Helper\Utils::logger();

        // $stu_info_all = $task->t_student_info->get_ass_stu_info_new();
        // $c = 7;
        // $a = ($b=2)||($c=1);
        // dd($c);
        // $this->t_manager_info->send_wx_todo_msg("tom","sdfa","dfadf");

    }










}
