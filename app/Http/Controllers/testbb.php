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


        $url_power_map=\App\Config\url_power_map::get_config();

        dd($url_power_map);

        // $group_list=$this->t_authority_group->get_auth_groups();
        // dd($group_list);
        $uid = $this->get_account_id();

        $permission = $this->t_manager_info->get_permission($uid);

        $per_arr = explode(',',$permission);

        $jiaoxue_part_arr = ['66','52','96','91','70','39','71','97','105','95'];

        $result=array_intersect($per_arr,$jiaoxue_part_arr);

        dd($result);

        $role = $this->get_account_role();
        dd($role);

        // $time = strtotime(date("2017-01-05"));
        $time['start_time'] = 1483545600;
        $time['end_time'] = 1483545699;

        $ret =$this->t_teacher_info->get_freeze_and_limit_tea_info($time);

        // $ret = $this->t_teacher_lecture_appointment_info->tongji_teacher_appoinment_lecture_info($time);
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



    public function seller_test_lesson_info_by_teacher(){ // 处理老师的试听转化率
        $sum_field_list=[
            "work_day",
            "lesson_count",
            "suc_count",
            "lesson_per",
            "order_count",
            "order_per",
            "all_price",
            "money_per",
            "tea_per",
            "range"
        ];
        $order_field_arr=  array_merge(["account" ] ,$sum_field_list );
        list( $order_in_db_flag, $order_by_str, $order_field_name,$order_type )
            =$this->get_in_order_by_str($order_field_arr ,"account desc");

        $show_flag = $this->get_in_int_val("show_flag",0);

        $lesson_money = $this->get_in_int_val("lesson_money",477);

        $this->t_lesson_info->switch_tongji_database();

        list($start_time,$end_time)=$this->get_in_date_range(0,0,0,[],3);

        $ret_info = $this->t_lesson_info_b2->get_teacher_test_lesson_info_for_jy($start_time,$end_time);


        // dd($ret_info);

        foreach($ret_info["list"] as &$item){
            $item["order_per"] = !empty($item["suc_count"])?round($item["order_count"]/$item["suc_count"],4)*100:0;
            if($item["train_through_new_time"] !=0){
                $item["work_day"] = ceil((time()-$item["train_through_new_time"])/86400);
            }else{
                $item["work_day"] ="";
            }

            $item["all_money"]  = $item["lesson_count"]*$lesson_money+$item["order_count"]*60+($item["suc_count"]-$item["order_count"])*30;
            $item["money_per"] = !empty($item["all_money"])?round($item["all_price"]/$item["all_money"]/100,1):0;

            $item["lesson_per"] = !empty($item["lesson_count"])?round($item["suc_count"]/$item["lesson_count"],4)*100:0;

            if($show_flag==1){

                $seller_arr = $this->t_lesson_info_b2->get_test_lesson_info_by_teacherid($item['teacherid'],$start_time, $end_time);
                $ret = $this->t_lesson_info_b2->get_teacher_test_lesson_info_by_seller($start_time,$end_time,$seller_arr);
                // $item["tea_per"] = !empty($ret["lesson_count"])?round($ret["order_count"]/$ret["lesson_count"],4)*100:0;
                // $item["range"] = sprintf("%.2f",$item["order_per"]-$item["tea_per"]);
            }

            // $item['seller_arr'] = $this->t_lesson_info_b2->get_test_lesson_info_by_teacherid($item['teacherid'],$start_time, $end_time);


        }


        dd($ret_info);

        // $num = count($ret_info["list"]);
        // if (!$order_in_db_flag) {
        //     \App\Helper\Utils::order_list( $ret_info["list"], $order_field_name, $order_type );
        // }

        // $all_item = [
        //     "account" => "全部"
        // ];
        // \App\Helper\Utils::list_add_sum_item($ret_info["list"], $all_item,$sum_field_list);
        // foreach($ret_info["list"] as &$val){
        //     if($val["account"]=="全部"){
        //         $val["work_day"] = $num>0?ceil(@$val["work_day"]/$num):""; $val["order_per"] = !empty($val["suc_count"])?round($val["order_count"]/$val["suc_count"],4)*100:0;
        //         $val["lesson_per"] = !empty($val["lesson_count"])?round($val["suc_count"]/$val["lesson_count"],4)*100:0;
        //         $val["all_money"]  = $val["lesson_count"]*$lesson_money+$val["order_count"]*60+($val["suc_count"]-$val["order_count"])*30;
        //         $val["money_per"] = !empty($val["all_money"])?round($val["all_price"]/$val["all_money"]/100,1):0;
        //         $val["tea_per"] = $val["range"]="";
        //     }
        // }

    }





}