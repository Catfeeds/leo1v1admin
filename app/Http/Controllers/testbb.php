<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use Illuminate\Support\Facades\Mail ;

require_once app_path('/Libs/TCPDF/tcpdf.php');
require_once app_path('/Libs/TCPDF/config/tcpdf_config.php');

class testbb extends Controller
{
    use CacheNick;

    var $check_login_flag = false;
    public function get_msg_num() {
        $bt_str=" ";
        $e=new \Exception();
        foreach( $e->getTrace() as &$bt_item ) {
            //$args=json_encode($bt_item["args"]);
            $bt_str.= @$bt_item["class"]. @$bt_item["type"]. @$bt_item["function"]."---".
                @$bt_item["file"].":".@$bt_item["line"].
                "<br/>";
        }
        echo $bt_str;

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
        $account_id = $this->get_in_int_val('id');
        $ass_list = $this->t_admin_group_name->get_group_admin_list($account_id);
        $ass_list = array_column($ass_list,'adminid');
        $ass_list_str = implode(',',$ass_list);
        dd($ass_list_str);
    }



    public function test () {

        $t = $this->get_in_int_val('t',-1);
        dd($t);
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




    public function tt() {
        $store=new \App\FileStore\file_store_tea();
        $ret=$store->list_dir("10001", "/log1");
        dd($ret);
    }
    public function rename_file() {


    }


    public function test_img(){
        $a = [
            "a"=>1,
            "b"=>2
        ];

        $b = [
            "c"=>3,
            "d"=>4
        ];

        $ret = array_merge($a,$b);

        dd($ret);
    }




    public function sd(){



        $this->switch_tongji_database();

        $ret_info = [];
        // $teacherid = $this->get_teacherid();
        $teacherid = $this->get_in_int_val('t');

        $test_lesson_info = $this->t_teacher_info->get_test_lesson_info_for_teacher_day($teacherid);

        $test_lesson_info["work_day"] = ceil((time()-$test_lesson_info["work_day"])/86400)."天";
        $test_lesson_info["test_lesson_time"] = date("Y.m.d",$test_lesson_info['test_lesson_time']);

        $common_lesson_info = $this->t_teacher_info->get_common_lesson_info_for_teacher_day($teacherid);
        $common_lesson_info["common_lesson_start"] = date("Y.m.d",$common_lesson_info['common_lesson_start']);

        $common_lesson_num = $this->t_teacher_info->get_common_lesson_num_for_teacher_day($teacherid);

        $stu_num = $this->t_teacher_info->get_student_num_for_teacher_day($teacherid);


        $ret_info = array_merge($test_lesson_info, $common_lesson_info, $common_lesson_num, $stu_num);


        $url = "http://admin.yb1v1.com/teacher_money/get_teacher_total_money?type=admin&teacherid=".$teacherid;
        $ret =\App\Helper\Utils::send_curl_post($url);
        $ret = json_decode($ret,true);
        if(isset($ret) && is_array($ret) && isset($ret["data"][0]["lesson_price"])){
            $money = $ret["data"][0]["lesson_price"];
        }else{
            $money = 0;
        }

        $ret_info['money'] = $money;

        dd($ret_info);



        // $ret = $this->t_teacher_info->get_teacher_info_for_teacher_day($teacherid);
        $ret1 = $this->t_teacher_info->get_common_lesson_info_for_teacher_day($teacherid);
        dd($ret1);
        $lesson_start = 0;
        $lesson_end =0;
        $ret = $this->t_lesson_opt_log->get_test_lesson_for_login($lessonid,$stu_id,$lesson_start,$lesson_end);
        dd($ret);
    }



    public function get_teacher_free_time_by_lessonid(){ // 获取老师和学生的上课时间

        $lessonid = $this->get_in_int_val('lessonid');

        $lesson_end = $this->t_lesson_info_b2->get_lesson_end($lessonid);
        $filter_lesson_time_start = time(NULL)+86400;
        $filter_lesson_time_end   = $lesson_end+3*86400;

        $teacher_lesson_time = $this->t_lesson_info_b2->get_teacher_time_by_lessonid($lessonid, $filter_lesson_time_start, $filter_lesson_time_end);
        $student_lesson_time = $this->t_lesson_info_b2->get_student_lesson_time_by_lessonid($lessonid, $filter_lesson_time_start, $filter_lesson_time_end);

        $all_tea_stu_lesson_time = array_merge($teacher_lesson_time, $student_lesson_time);
        $all_tea_stu_lesson_time['start'] = $filter_lesson_time_start;
        
        $all_tea_stu_lesson_time['end']   = $filter_lesson_time_end;


        return $this->output_succ(['data'=>$all_tea_stu_lesson_time]);




        $lessonid = $this->get_in_int_val('lessonid');

        $lesson_end = $this->t_lesson_info_b2->get_lesson_end($lessonid);
        $filter_lesson_time_start = time(NULL)+86400;
        $filter_lesson_time_end   = $lesson_end+3*86400;

        // $lesson_time = $this->t_lesson_info_b2->get_lesson_time($lessonid);
        $teacher_lesson_time = $this->t_lesson_info_b2->get_teacher_time_by_lessonid($lessonid, $filter_lesson_time_start, $filter_lesson_time_end);
        $student_lesson_time = $this->t_lesson_info_b2->get_student_lesson_time_by_lessonid($lessonid, $filter_lesson_time_start, $filter_lesson_time_end);





        $lesson_time_arr = [];
        $t = [];
        $t2 = [];
        $t3 = [];
        $t4 = [];
        $all_tea_stu_lesson_time = array_merge($teacher_lesson_time, $student_lesson_time);

        // foreach($all){

        // }

        dd($all_tea_stu_lesson_time);

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

        // foreach($lesson_time as $item){
        //     $t4['time'][0] = date('Y-m-d',$item['lesson_start']);
        //     $t4['time'][1] = date('H',$item['lesson_start']).':59:00';
        //     $t4['can_edit'] = 2;// 0:可以编辑 1:不可以编辑 2:课时本来的时间
        //     array_push($lesson_time_arr,$t4);
        //     $t3['time'][0] = date('Y-m-d',$item['lesson_end']);
        //     $t3['time'][1] = date('H',$item['lesson_end']).':59:00';
        //     $t3['can_edit'] = 2;// 0:可以编辑 1:不可以编辑 2:课时本来的时间且不可编辑
        //     array_push($lesson_time_arr,$t3);
        // }

        // return $this->output_succ(['data'=>$lesson_time_arr]);
    }














}