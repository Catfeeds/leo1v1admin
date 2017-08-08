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
        //	bf059d7ccac785e1340f8a590b7866e51502182562119.pdf
        $this->switch_tongji_database();
        $is_full_time = 1;  // 显示兼职老师
        $this->switch_tongji_database();
        $sum_field_list=[
            "stu_num",
            "valid_count",
            "teacher_come_late_count",
            "teacher_cut_class_count",
            "teacher_change_lesson",
            "teacher_leave_lesson",
            "teacher_money_type",
            "lesson_cancel_reason_type",
        ];
        $order_field_arr=  array_merge(["teacher_nick" ] ,$sum_field_list );

        list( $order_in_db_flag, $order_by_str, $order_field_name,$order_type )
            =$this->get_in_order_by_str($order_field_arr ,"teacher_nick desc");
        $assistantid= $this->get_in_int_val("assistantid",-1);

        list($start_time,$end_time) = $this->get_in_date_range(0,0,0,[],3);

        $ret_info = $this->t_lesson_info_b2->get_lesson_info_teacher_tongji_jy($start_time,$end_time,$is_full_time );

        dd($ret_info);
        foreach($ret_info as &$item_list){
                $item_list['teacher_nick'] = $this->cache_get_teacher_nick($item_list['teacherid']);

                if($item_list['train_through_new_time'] !=0){
                    $item_list["work_time"] = ceil((time()-$item_list["train_through_new_time"])/86400);
                }else{
                    $item_list["work_time"] = 0;
                }

                E\Eteacher_money_type::set_item_value_str($item_list);

        }

        $all_item=["teacher_nick" => "全部" ];
        foreach ($ret_info as &$item) {
            foreach ($item as $key => $value) {
                if ((!is_int($key)) && (($key == "stu_num") || ($key =="valid_count") || ($key == "teacher_come_late_count") || ($key == "teacher_cut_class_count") || ($key =="teacher_change_lesson")||($key == 'teacher_leave_lesson') || ($key == "work_time") )) {
                    $all_item[$key]=(@$all_item[$key])+$value;
                }
            }
        }

        if (!$order_in_db_flag) {
            \App\Helper\Utils::order_list( $ret_info, $order_field_name, $order_type );
        }

        array_unshift($ret_info, $all_item);
        return $this->pageView(__METHOD__,\App\Helper\Utils::list_to_page_info($ret_info) ,["data_ex_list"=>$ret_info]);



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

}