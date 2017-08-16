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





    //  向 老师推送老师端版本更新 通知

    public function send_wx_to_update_software(){

        $teacher_list = $this->t_teacher_info->get_teacher_openid_list();

        $date_time = date("Y-m-d");

        $url_teacher = "";

        foreach($teacher_list as $item){
            // dispatch( new \App\Jobs\send_wx_to_teacher_for_update_software( $item['wx_openid']));
        }

    }


    public function get_orgin(){

        $start_time = 1501516800;
        $end_time   = 1504108800;


        $seller_groupid_ex    = $this->get_in_str_val('seller_groupid_ex', "");
        $require_adminid_list = $this->t_admin_main_group_name->get_adminid_list_new($seller_groupid_ex);

        $field_name = 'origin';
        $field_class_name = '';

        $this->t_seller_student_origin->switch_tongji_database();

        $origin_info = $this->t_seller_student_origin->get_origin_tongji_info_for_jy('origin', 'add_time' ,$start_time,$end_time,"","","",$require_adminid_list, 0);

        $data_map = &$origin_info['list'];


        $this->t_test_lesson_subject_require->switch_tongji_database();
        $test_lesson_list=$this->t_test_lesson_subject_require->tongji_test_lesson_origin( $field_name,$start_time,$end_time,$require_adminid_list,'', '' );

        foreach ($test_lesson_list as  $test_item ) {
            $check_value=$test_item["check_value"];
            \App\Helper\Utils:: array_item_init_if_nofind( $data_map, $check_value,["check_value" => $check_value] );
            $data_map[$check_value]["test_lesson_count"] = $test_item["test_lesson_count"];
            $data_map[$check_value]["succ_test_lesson_count"] = $test_item["succ_test_lesson_count"];
        }


        $this->t_order_info->switch_tongji_database();
        $order_list= $this->t_order_info->tongji_seller_order_count_origin( $field_name,$start_time,$end_time,$require_adminid_list,'','','add_time');
        foreach ($order_list as  $order_item ) {
            $check_value=$order_item["check_value"];
            \App\Helper\Utils:: array_item_init_if_nofind( $data_map, $check_value,["check_value" => $check_value ] );

            $data_map[$check_value]["order_count"] = $order_item["order_count"];
            $data_map[$check_value]["user_count"] = $order_item["user_count"];
            $data_map[$check_value]["order_all_money"] = $order_item["order_all_money"];
        }



        foreach ($data_map as &$item ) {
            $item["title"]= $item["check_value"];

            if ($field_name=="origin") {
                $item["origin"]= $item["title"];
            }
        }

        if ($field_name=="origin") {
            $origin_info["list"]= $this->gen_origin_data($origin_info["list"],["avg_first_time"], '');
        }


        dd($origin_info);











    }






    public function gen_origin_data($old_list,$no_sum_list=[] ,$origin_ex="")
    {

        $value_map=$this->t_origin_key->get_list( $origin_ex);
        $cur_key_index=1;
        $check_init_map_item=function (&$item, $key, $key_class, $value = "") {
            global $cur_key_index;
            if (!isset($item [$key])) {
                $item[$key] = [
                    "value" => $value,
                    "key_class" => $key_class."-".$cur_key_index,
                    "sub_list"=>[] ,
                    "data" => array(),
                ];
                $cur_key_index++;
            }
        };
        $add_data=function (&$item, $add_item ,$self_flag=false) use ( $no_sum_list) {
            $arr=&$item["data"];
            foreach ($add_item as $k => $v) {
                if (!is_int($k) && $k!="origin" &&
                    ($self_flag|| !in_array(  $k ,$no_sum_list ) )
                ) {
                    if (!isset($arr[$k])) {
                        $arr[$k]=0;
                    }
                    $arr[$k]+=$v;
                }
            }

        };
        /*
          [
          "baidu pc " => ["data" => $item
          sub_list=> [
          "xx" ＝ ["data" => $item
          sub_list= [...]
          ]

          ]
          ]
          ]

        */

        $all_item=["origin"=>"全部"];

        $check_init_map_item($data_map,"","");
        foreach ($old_list as &$item) {
            $value=trim($item["origin"]);
            if (!isset($value_map[$value])) {
                $value_map[$value]=[
                    "key1"=>"未定义",
                    "key2"=>"未定义",
                    "key3"=>"未定义",
                    "key4"=>$value,
                    "value"=>$value,
                ];
            }

            $conf=$value_map[$value];

            $key1=$conf["key1"];
            $key2=$conf["key2"];
            $key3=$conf["key3"];
            $key4=$conf["key4"];
            $key0_map=&$data_map[""];
            $add_data($key0_map, $item );

            $check_init_map_item($key0_map["sub_list"] , $key1,"key1" );
            $key1_map=&$key0_map["sub_list"][$key1];
            $add_data($key1_map, $item );

            $check_init_map_item($key1_map["sub_list"] , $key2 ,"key2");
            $key2_map=&$key1_map["sub_list"][$key2];
            $add_data($key2_map, $item );

            $check_init_map_item($key2_map["sub_list"] , $key3 ,"key3");
            $key3_map=&$key2_map["sub_list"][$key3];
            $add_data($key3_map, $item );

            $check_init_map_item($key3_map["sub_list"] , $key4,"key4",$value);
            $key4_map=&$key3_map["sub_list"][$key4];
            $add_data($key4_map, $item, true);

        }
        $list=[];
        //array_unshift($ret_info["list"],$all_item);
        foreach ($data_map as $key0 => $item0) {
            $data=$item0["data"];
            $data["key1"]="全部";
            $data["key2"]="";
            $data["key3"]="";
            $data["key4"]="";
            $data["key1_class"]="";
            $data["key2_class"]="";
            $data["key3_class"]="";
            $data["key4_class"]="";
            $data["level"]="l-0";

            $list[]=$data;
            foreach ($item0["sub_list"] as $key1 => $item1) {
                $data=$item1["data"];
                $data["key1"]=$key1;
                $data["key2"]="";
                $data["key3"]="";
                $data["key4"]="";
                $data["key1_class"]=$item1["key_class"];
                $data["key2_class"]="";
                $data["key3_class"]="";
                $data["key4_class"]="";
                $data["level"]="l-1";

                $list[]=$data;

                foreach ($item1["sub_list"] as $key2 => $item2) {
                    $data=$item2["data"];
                    $data["key1"]=$key1;
                    $data["key2"]=$key2;
                    $data["key3"]="";
                    $data["key4"]="";
                    $data["key1_class"]=$item1["key_class"];
                    $data["key2_class"]=$item2["key_class"];
                    $data["key3_class"]="";
                    $data["key4_class"]="";
                    $data["level"]="l-2";

                    $list[]=$data;
                    foreach ($item2["sub_list"] as $key3 => $item3) {
                        $data=$item3["data"];
                        $data["key1"]=$key1;
                        $data["key2"]=$key2;
                        $data["key3"]=$key3;
                        $data["key4"]="";
                        $data["key1_class"]=$item1["key_class"];
                        $data["key2_class"]=$item2["key_class"];
                        $data["key3_class"]=$item3["key_class"];
                        $data["key4_class"]="";
                        $data["level"]="l-3";

                        $list[]=$data;
                        foreach ($item3["sub_list"] as $key4 => $item4) {
                            $data=$item4["data"];
                            $data["key1"]=$key1;
                            $data["key2"]=$key2;
                            $data["key3"]=$key3;
                            $data["key4"]=$key4;
                            $data["value"] = $item4["value"];
                            $data["key1_class"]=$item1["key_class"];
                            $data["key2_class"]=$item2["key_class"];
                            $data["key3_class"]=$item3["key_class"];
                            $data["key4_class"]=$item4["key_class"];
                            $k4_v=$item4["value"];
                            if ($k4_v != $key4) {
                                $data["key4"]=$key4."/". $k4_v ;
                            }
                            $data["old_key4"]=$key4;
                            $data["level"]="l-4";
                            $list[]=$data;
                        }

                    }

                }


            }
        }

        foreach($list as &$item){
            if($item["level"]=="l-4" && $item["key1"]!="未定义"){
                $item["create_time"] = $value_map[$item['value']]["create_time"];
                if(!empty($item["create_time"])){
                    $item["create_time"] = date('Y-m-d',$item["create_time"]);
                }else{
                    $item["create_time"] = "";
                }
            }else{
                $item["create_time"] = "";
            }
        }
        return $list;
    }





}