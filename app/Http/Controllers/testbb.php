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
        $teacherid = $this->get_in_int_val('t');


        $test_lesson_info = $this->t_teacher_info->get_test_lesson_info_for_teacher_day($teacherid);

        $common_lesson_info = $this->t_teacher_info->get_common_lesson_info_for_teacher_day($teacherid);

        $common_lesson_num = $this->t_teacher_info->get_common_lesson_num_for_teacher_day($teacherid);

        $stu_num = $this->t_teacher_info->get_student_num_for_teacher_day($teacherid);

        $ret_info = array_merge($test_lesson_info, $common_lesson_info, $common_lesson_num, $stu_num);

        dd($ret_info);



        // $ret = $this->t_teacher_info->get_teacher_info_for_teacher_day($teacherid);
        $ret1 = $this->t_teacher_info->get_common_lesson_info_for_teacher_day($teacherid);
        dd($ret1);
        $lesson_start = 0;
        $lesson_end =0;
        $ret = $this->t_lesson_opt_log->get_test_lesson_for_login($lessonid,$stu_id,$lesson_start,$lesson_end);
        dd($ret);
    }













}