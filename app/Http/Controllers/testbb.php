<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use Illuminate\Support\Facades\Mail ;

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

        // date('Y-m-d H:i:s');
        dd(date('Y-m-d h:i:s'));
        $teacherid = $this->get_in_int_val('id');
        $start_time = 1501516800;
        $end_time = 1501516800 + 86400;
        $seller_arr = $this->t_lesson_info_b2->get_test_lesson_info_by_teacherid($teacherid,$start_time, $end_time);

        $ret = $this->t_lesson_info_b2->get_teacher_test_lesson_info_by_seller($start_time,$end_time,$seller_arr);
        dd($ret);


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


    public function get_rate(){

        $time_arr = [
            "time"=>strtotime('2017-01-01'),
            "time"=>strtotime('2017-01-02'),
            "time"=>strtotime('2017-01-03'),
            "time"=>strtotime('2017-01-04'),
            "time"=>strtotime('2017-01-05'),
            "time"=>strtotime('2017-01-06'),
            "time"=>strtotime('2017-01-07'),
        ];

        dd($time_arr);

        // select count(*) from db_weiyi.t_teacher_info  where create_time <1483200000; 1月
        // select count(*) from db_weiyi.t_teacher_info  where create_time <1485878400; 2月
        // select count(*) from db_weiyi.t_teacher_info  where create_time <1488297600; 3月
        // select count(*) from db_weiyi.t_teacher_info  where create_time <1488297600; 4月

    }



}