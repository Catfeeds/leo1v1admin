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
        // 时间调整功能 数据修改 [勿删]
        /*
        Schema::table('db_weiyi.t_lesson_info', function( Blueprint $table)
        {
            t_field($table->string("teacher_deal_time",50),"老师操作时间");
            t_field($table->string("parent_deal_time",50),"家长操作时间");
            t_field($table->string("teacher_modify_time",1024),"老师选择时间段");
            t_field($table->string("teacher_modify_remark",1024),"老师修改时间备注");
            t_field($table->string("parent_modify_time",1024),"家长选择时间段");
            t_field($table->string("parent_modify_remark",1024),"家长修改时间备注");
            t_field($table->integer("is_modify_time_flag"),"上课时间调整是否成功 0:未成功 1:已成功");
        });
        */

        $lessonid = $this->get_in_int_val('lessonid');

        $lesson_old_start = $this->t_lesson_info_b2->get_lesson_start($lessonid);
        $lesson_old_end   = $this->t_lesson_info_b2->get_lesson_end($lessonid);
        $original_lesson_time = $lesson_old_start.','.$lesson_old_end;

        dd($original_lesson_time);

    }

    public function lesson_send_msg(){
        $start_time = time(null);
        $this->t_teacher_info->get_lesson_info_by_time($start_time,$end_time);
    }


    //家长调整时间的功能[勿删]
    public function get_teacher_free_time_by_lessonid(){
        $lessonid = $this->get_in_int_val('lessonid');

        $lesson_time = $this->t_lesson_info_b2->get_lesson_time($lessonid);
        $teacher_lesson_time = $this->t_lesson_info_b2->get_teacher_time_by_lessonid($lessonid);
        $student_lesson_time = $this->t_lesson_info_b2->get_student_lesson_time_by_lessonid($lessonid);

        $lesson_time_arr = [];
        $t = [];
        $t2 = [];
        $t3 = [];
        $t4 = [];
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
           $t4['can_edit'] = 3;// 0:可以编辑 1:不可以编辑 2:课时本来的时间
           array_push($lesson_time_arr,$t4);
           $t3['time'][0] = date('Y-m-d',$item['lesson_end']);
           $t3['time'][1] = date('H',$item['lesson_end']).':59:00';
           $t3['can_edit'] = 3;// 0:可以编辑 1:不可以编辑 2:课时本来的时间且不可编辑
           array_push($lesson_time_arr,$t3);
       }

       return $this->output_succ(['data'=>$lesson_time_arr]);

    }


    public function set_modify_lesson_time_by_parent(){
        $parent_modify_time   = $this->get_in_str_val('parent_modify_time');
        $parent_modify_remark = $this->get_in_str_val('parent_modify_remark');
        $lessonid = $this->get_in_int_val('lessonid');

        $lesson_start_time = $this->t_lesson_info_b2->get_lesson_start($lessonid);
        $stu_nick          = $this->t_student_info->get_stu_nick_by_lessonid($lessonid);

        $ret = $this->t_lesson_info_b2->field_update_list($lessonid,[
            'parent_modify_time' => $parent_modify_time,
            'parent_modify_remark' => $parent_modify_remark,
            'parent_deal_time'   => time(NULL)
        ]);

        if($ret){
            // 发送微信推送[家长]
            $parent_wx_openid = $this->t_parent_info->get_parent_wx_openid();

            $lesson_start_date = date('Y-m-d',$lesson_start_time );
            $result = "原因:{".$parent_modify_remark."}";
            $day_time = date('Y-m-d H:i:s');
            $wx     = new \App\Helper\Wx();
            $url = '';
            $template_id = "9MXYC2KhG9bsIVl16cJgXFVsI35hIqffpSlSJFYckRU";//待处理通知
            $data_msg = [
                "first"     => " 调课申请受理中",
                "keyword1"  => " 调换{".$lesson_start_time."}上课时间",
                "keyword2"  => " 原上课时间:{".$lesson_start_time."}, $result,申请受理中,请稍等!",
                "keyword3"  => " $day_time",
                "remark"    => " 详细进度稍后将以推送的形式发送给您,请注意查看!",

            ];
            $wx->send_template_msg($parent_wx_openid,$template_id,$data_msg ,$url);

            // 发送微信推送[老师]
            $teacher_wx_openid = $this->t_teacher_info->get_wx_openid_by_lessonid($lessonid);
            $teacher_url = ''; //待定
            $template_id_teacher  = "rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o";
            $data['first']      = " 调课申请 ";
            $data['keyword1']   = " 您的学生{".$stu_nick."}的家长申请修改{".$lesson_start_date."}上课时间";
            $data['keyword2']   = " 原上课时间:{".$lesson_start_date."};$result";
            $data['keyword3']   = "$day_time";
            $data['remark']     = "请点击详情查看家长勾选的时间并进行处理!";
            \App\Helper\Utils::send_teacher_msg_for_wx($teacher_wx_openid,$template_id_teacher, $data,$teacher_url);

        }

        return $this->output_succ();

    }


    public function set_teacher_free_time(){
        $free_time = $this->get_in_str_val('parent_modify_time');

        // 加一个时间的限制
    }


    public function get_nick_phone_by_account_type($account_type,&$item){
            $item["user_nick"]  = $this->cache_get_teacher_nick ($item["userid"] );
            $item['phone']      = $this->t_teacher_info->get_phone_by_nick($item['user_nick']);
    }



}