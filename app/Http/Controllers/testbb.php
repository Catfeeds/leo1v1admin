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
        $a= new \App\Jobs\send_error_mail(1,33,33);
        $a->task->t_agent->get_agent_count_by_id(1);

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
        dd(date('Y年m月'));
    }


    public function test_img(){
        $date = date('Y_m-d');
        $title= substr($date,2);

        dd($title);


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




    // public function sd(){
    //     $this->switch_tongji_database();
    //     $ret = $this->t_teacher_info->get_teacher_openid_list();

    //     $ww = [];
    //     foreach($ret as $item){
    //         $agent_arr = json_decode($item['user_agent'],true);
    //         $version_arr = explode('.',$agent_arr['version']);
    //         $v = substr($agent_arr['device_model'],0,3);
    //         if(($v == 'Win' || $v=='Mac') && !empty($version_arr) && (($version_arr[0]==3 && $version_arr[1]<=2) || ($version_arr[0]<3 ) ) ){
    //             dispatch( new \App\Jobs\send_wx_to_teacher_for_update_software($item['wx_openid']) );
    //         }
    //     }
    // }







}