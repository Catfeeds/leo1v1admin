<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use App\Helper\Utils;

class small_class extends Controller
{
    use  CacheNick;

    public function index()
    {
        $teacherid   = $this->get_in_int_val("teacherid",-1);
        $assistantid = $this->get_in_int_val("assistantid",-1);
        $start_time  = $this->get_in_str_val('start_time',date('Y-m-d', time()-86400*7));
        $end_time    = $this->get_in_str_val('end_time',date('Y-m-d', time()+86400));
        $courseid    = $this->get_in_courseid(-1);
        $page_num    = $this->get_in_page_num();
        $group_flag  = $this->get_in_str_val("group_flag");

        $start_time_s = strtotime($start_time);
        $end_time_s   = strtotime($end_time);

        $course_type = E\Econtract_type::V_SMALL_CLASS;

        $ret_info = $this->t_course_order->get_courses_ex($teacherid,$assistantid,$course_type,$start_time_s,$end_time_s
                                                        ,$courseid,$page_num);

        foreach( $ret_info["list"] as &$item){
            E\Egrade::set_item_value_str($item, "grade");
            E\Esubject::set_item_value_str($item, "subject");
            $item["stu_current"] = $this->t_small_class_user->get_small_class_user_count($item["courseid"]);
        }

        return $this->pageView(__METHOD__,$ret_info ,[
            "group_flag" => $group_flag
        ]);
    }

    public function index_ass(){
        $assistantid=$this->t_assistant_info->get_assistantid($this->get_account());
        if($assistantid == 0){
            $assistantid = -1;
        }
        $this->set_in_value("assistantid", $assistantid);
        $this->set_in_value("group_flag","assistant");
        return $this->index();
    }

    public function lesson_list_new()
    {
        $courseid=$this->get_in_str_val("courseid");
        $lessonid = $this->get_in_lessonid(-1);
        $page_num = $this->get_in_page_num();

        $ret_list = $this->t_lesson_info->get_lesson_list_new($courseid,$lessonid,$page_num);
        if (!$courseid) {
            return $this->error_view(
                [
                    " 没有小班课次信息 ",
                    " 请从[小班管理] 点击\"课次详细信息\"进来 ",
                ]
            );
        } else {
            foreach($ret_list["list"] as &$item) {
                $item["teacher_nick"]   = $this->cache_get_teacher_nick( $item["teacherid"]);
                $item["assistant_nick"] = $this->cache_get_assistant_nick( $item["assistantid"]);
                $item["lesson_time"]    = date("Y-m-d H:i", $item['lesson_start']) . '-' . date("H:i", $item['lesson_end']);
                E\Esubject::set_item_value_str($item,"subject");
                E\Egrade::set_item_value_str($item, "grade");
            }
        }
        return $this->pageView(__METHOD__, $ret_list);
    }

    public function lesson_list_new_ass(){
        return $this->lesson_list_new();
    }

    public function student_list_new()
    {
        $lessonid  = $this->get_in_lessonid();
        $page_num  = $this->get_in_page_num();

        $ret_list=$this->t_small_lesson_info->small_class_get_lesson_student_list(
            $lessonid,$page_num
        );
        if (!$lessonid) {
            return $this->error_view(
                [
                    " 没有小班学生信息 ",
                    " 请从[小班课次管理] 点击\"学生信息\"进来 ",
                ]
            );
        } else {
            foreach ($ret_list["list"] as &$item){
                $work_status = $item["work_status"];
                // $item["student_nick"] = $this->cache_get_student_nick($item["studentid"]);
                $item["student_nick"] = $item['nick'];
                if($work_status != 0){
                    $item['download_url'] = $item[E\Ework_status::v2s($work_status)."_url"];
                }
                E\Ework_status::set_item_value_str($item,"work_status");
            }

        }
        return $this->pageView(__METHOD__, $ret_list);
    }
    public function student_list_new_ass(){
        return $this->student_list_new();
    }


    public function get_config_courseid(){
        $config_courseid = $this->get_in_int_val("courseid");

        // t_order_info  config_courseid  == courseid
        $count= $this->t_order_info->count_config_courseid($config_courseid);

        return outputjson_success( ["count"=> $count]);
    }

    public function del_lesson(){
        $courseid = $this->get_in_int_val('courseid');

        $ret = $this->t_course_order->update_course($courseid);

        return outputjson_ret($ret);
    }

    public function get_teacher_clothes_list(){
        $type = $this->get_in_int_val('type',0);

        $ret_list = $this->t_pic_manage_info->get_teacher_clothes_list($type);

        $default_arr['k'] ='0';
        $default_arr['v'] ='默认';
        array_unshift($ret_list,$default_arr);

        return outputjson_success(["list"=>$ret_list]);
    }

    public function get_teacher_clothes(){
        $teacherid = $this->get_in_int_val('teacherid',0);
        $lessonid  = $this->get_in_int_val('lessonid',0);

        if($lessonid==0){
            $ret_info = $this->t_teacher_info->field_get_list($teacherid,'gender,clothes');
        }else{
            $ret_info = $this->t_lesson_info->get_teacher_clothes_info($lessonid,$teacherid);
        }

        return outputjson_success(["ret_info"=>$ret_info]);
    }

    public function get_teacher_pic(){
        $id = $this->get_in_int_val('id');

        $ret_info = $this->t_pic_manage_info->get_url($id);

        return outputjson_success(["ret_info"=>$ret_info]);
    }

    public function set_teacher_clothes(){
        $lessonid  = $this->get_in_int_val('lessonid',0);
        $teacherid = $this->get_in_int_val('teacherid',0);
        $clothes   = $this->get_in_int_val('clothes',0);
        $all_type  = $this->get_in_str_val('all_type','');

        if($all_type=='all'){
            $set_field_arr = array(
                'clothes' => $clothes
            );
            $ret = $this->t_teacher_info->field_update_list($teacherid,$set_field_arr);
            $ret = $this->t_lesson_info->set_teacher_clothes($lessonid,$clothes);
        }else{
            $set_field_arr = array(
                'teacher_clothes' => $clothes
            );
            $ret= $this->t_lesson_info->field_update_list($lessonid,$set_field_arr);
        }
        return outputjson_ret($ret);
    }

    public function add_small_student(){
        $courseid     = $this->get_in_int_val("courseid");
        $userid       = $this->get_in_int_val("userid");
        $is_change    = $this->get_in_int_val("is_change");
        $old_courseid = $this->get_in_int_val("old_courseid");

        if($userid==0 || $courseid==0){
            return $this->output_err("用户或小班课id出错!");
        }
        $phone = $this->t_student_info->get_phone($userid);
        if(!$phone){
            return $this->output_err("用户不存在!");
        }

        if($courseid>0){
            $new_lessonid = $this->t_lesson_info->get_lessonid_list($courseid,0);
            $this->t_small_lesson_info->start_transaction();
            foreach($new_lessonid as $new_val){
                $ret = $this->t_small_lesson_info->check_user($new_val['lessonid'],$userid);
                if(!$ret){
                    $new_insert_ret = $this->t_small_lesson_info->row_insert([
                        "lessonid" => $new_val['lessonid'],
                        "userid"   => $userid,
                    ]);
                    $this->check_succ($new_insert_ret,"加入学生出错!");
                }
            }
            $new_ret = $this->t_small_class_user->check_user($courseid,$userid);
            if(!$new_ret){
                $new_insert_ret = $this->t_small_class_user->row_insert([
                    "courseid"  => $courseid,
                    "userid"    => $userid,
                    "join_time" => time(),
                ]);
                $this->check_succ($new_insert_ret,"加入课程时出错!");
            }
        }

        if($old_courseid>0){
            $old_lessonid = $this->t_lesson_info->get_lessonid_list($old_courseid,0);
            foreach($old_lessonid as $old_val){
                $ret = $this->t_small_lesson_info->check_user($old_val['lessonid'],$userid);
                if($ret){
                    $del_ret=$this->t_small_lesson_info->row_delete_2($old_val['lessonid'],$userid);
                }
                $this->check_succ($del_ret,"移除学生出错!");
            }
            $old_ret = $this->t_small_class_user->check_user($old_courseid,$userid);
            if($old_ret){
                $del_ret=$this->t_small_class_user->row_delete_2($old_courseid,$userid);
                $this->check_succ($del_ret,"移除学生课程出错!");
            }
        }
        $this->t_small_class_user->commit();
        return $this->output_succ();
    }

    private function check_succ($ret,$err_info,$roll_back_flag=true){
        if(!$ret){
            if($roll_back_flag){
                $this->t_small_class_user->rollback();
                return $this->output_succ($err_info);
            }
        }
    }

}