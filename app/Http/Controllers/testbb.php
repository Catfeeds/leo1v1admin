<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use Illuminate\Support\Facades\Mail ;

class testbb extends Controller
{
    var $check_login_flag = false;
    public function get_msg_num($phone) {
        return \App\Helper\Common::redis_set_json_date_add("WX_P_PHONE_$phone",1000000);

    }

    public function assistant_info_new2(){
        $today      = date('Y-m-d',time(null));
        $today      = '20170626';
        $start_time = strtotime($today.'00:00:00');
        $end_time   = $start_time+24*3600;
        $userid=-1;
        $lesson_arr = [];
        $phone = '456';
        $lesson_arr = $this->t_agent->get_agent_info_row_by_phone($phone);
    }


    public function test1() {

        $lesson_end_time = $this->t_lesson_info->get_lesson_end(2367);
        dd($lesson_end_time);
        dd(strtotime(date('Y-m-d',time(NULL))));
        chmod("/var/www/admin.yb1v1.com/public/wximg/l_3689y277y0ydraw.xml",0777);

        exit();
        // $lessonid  = $item['lessonid'];
        $lessonid = 190149;
        $ret_video = $this->t_lesson_info_b2->get_lesson_url($lessonid);

        if(isset($ret_video[0]['draw'])){
            $item['draw_url']  =  \App\Helper\Utils::gen_download_url($ret_video[0]['draw']);
            $savePathFile = public_path('wximg').'/'.$ret_video[0]['draw'];
            \App\Helper\Utils::savePicToServer($item['draw_url'],$savePathFile);

            $xml = file_get_contents($savePathFile);

            $xmlstring = simplexml_load_string($xml);

            $svgLists = json_decode(json_encode($xmlstring),true);


            $stroke_time = 0;

            foreach($svgLists['svg'] as $svg){
                if (array_key_exists('path',$svg)) {
                    $stroke_time = $svg['@attributes']['timestamp'];
                }
            }

            // dd($ret_video[0]['real_begin_time']<($stroke_time-30*60));

            if ($ret_video[0]['real_begin_time']<($stroke_time-30*60)) {
                $re = $this->t_lesson_info->field_update_list($lessonid,[
                    "lesson_user_online_status" =>  1
                ]);
                dd($re);

            }
            unlink($savePathFile);
        }


        // $this->t_manager_info->send_wx_todo_msg("tom","sdfa","dfadf");

    }





    public function test () {
        $type = $this->get_account_role();
        // dd($type);
        /* ["课时损失率","lesson_lose_rate" ], */
        /* ["课时系数","lesson_rate" ], */

        $ret = $this->t_test_lesson_subject_sub_list->ceshi();
        dd($ret);

         // select tr.change_teacher_reason_type, tt.require_adminid, s.nick,l.lesson_start,l.grade,l.subject,tr.origin,tt.ass_test_lesson_type,t.realname,tt.textbook,s.editionid,tss.success_flag,tss.fail_reason ,l.userid,tss.fail_greater_4_hour_flag,tss.test_lesson_fail_flag,l.lessonid,l.teacherid,  tss.ass_test_lesson_order_fail_flag ,tss.ass_test_lesson_order_fail_desc, tss.order_confirm_flag  from db_weiyi.t_test_lesson_subject_sub_list tss left join db_weiyi.t_lesson_info l on tss.lessonid = l.lessonid left join db_weiyi.t_student_info s on l.userid = s.userid left join db_weiyi.t_teacher_info t on t.teacherid = l.teacherid left join db_weiyi.t_test_lesson_subject_require tr on tss.require_id = tr.require_id left join db_weiyi.t_test_lesson_subject tt on tr.test_lesson_subject_id = tt.test_lesson_subject_id left join db_weiyi_admin.t_manager_info m on tr.cur_require_adminid = m.uid where l.lesson_del_flag=0 and m.account_role=1 and tr.origin like '换老师' and l.lesson_start>=1501171200 and l.lesson_start<1501257600 order by l.lesson_start  limit 0,10
    }

    public function lesson_send_msg(){
        $start_time = time(null);
        $this->t_teacher_info->get_lesson_info_by_time($start_time,$end_time);
    }







    public function set_teacher_free_time(){
        $free_time = $this->get_in_str_val('parent_modify_time');

        // 加一个时间的限制
    }


    public function get_nick_phone_by_account_type($account_type,&$item){
            $item["user_nick"]  = $this->cache_get_teacher_nick ($item["userid"] );
            $item['phone']      = $this->t_teacher_info->get_phone_by_nick($item['user_nick']);
    }






    public function get_modify_lesson_time_by_teacher(){//1027 // 老师 点击家长调课 推送详情
        $lessonid = $this->get_in_int_val('lessonid');

        $lesson_time = $this->t_lesson_info_b2->get_lesson_time($lessonid);
        $teacher_lesson_time = $this->t_lesson_info_b2->get_teacher_time_by_lessonid($lessonid);
        $student_lesson_time = $this->t_lesson_info_b2->get_student_lesson_time_by_lessonid($lessonid);
        $parent_modify_time  = $this->t_lesson_time_modify->get_parent_modify_time($lessonid);


        $lesson_time_arr = [];
        $t = [];
        $t2 = [];
        $t3 = [];
        $t4 = [];
        $t5 = [];
        $all_tea_stu_lesson_time = array_merge($teacher_lesson_time, $student_lesson_time);
        foreach($all_tea_stu_lesson_time  as $item){
            $t['time'][0] = date('Y-m-d',$item['lesson_start']);
            $t['time'][1] = date('H',$item['lesson_start']).':59:00';
            $t['can_edit'] = 1;// 0:可以编辑 1:不可以编辑 2:课时本来的时间
            array_push($lesson_time_arr,$t);
            $t2['time'][0] = date('Y-m-d',$item['lesson_end']);
            $t2['time'][1] = date('H',$item['lesson_end']).':59:00';
            $t2['can_edit'] = 1;// 0:可以编辑 1:不可以编辑 2:课时本来的时间且不可编辑
            array_push($lesson_time_arr,$t2);
        }

        foreach($lesson_time as $item){
            $t4['time'][0] = date('Y-m-d',$item['lesson_start']);
            $t4['time'][1] = date('H',$item['lesson_start']).':59:00';
            $t4['can_edit'] = 2;// 0:可以编辑 1:不可以编辑 2:课时本来的时间
            array_push($lesson_time_arr,$t4);
            $t3['time'][0] = date('Y-m-d',$item['lesson_end']);
            $t3['time'][1] = date('H',$item['lesson_end']).':59:00';
            $t3['can_edit'] = 2;// 0:可以编辑 1:不可以编辑 2:课时本来的时间且不可编辑
            array_push($lesson_time_arr,$t3);
        }

        $parent_modify_time_arr = explode(',',$parent_modify_time);
        // dd($parent_modify_time);
        // foreach($parent_modify_time as $item){
        //     $t5['time'][0] = date('Y-m-d',$item);
        //     $t5['time'][1] = date('H',$item).':59:00';
        //     $t5['can_edit'] = 3;// 0:可以编辑 1:不可以编辑 2:课时本来的时间且不可编辑 3:家长填写的调课时间
        //     array_push($lesson_time_arr,$t5);
        // }
        return $this->output_succ(['data'=>$lesson_time_arr]);
    }





}