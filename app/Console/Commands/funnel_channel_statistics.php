<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers as C;


class funnel_channel_statistics extends cmd_base
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:funnel_channel_statistics';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '渠道结点型数据统计';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     * @desn:刷新漏斗型渠道数据
     * @param: $job 1：脚本调用 2：手动调用
     * @return mixed
     */
    public function handle($job=1)
    {

        $start_time = strtotime(date('Y-m-01'));
        $end_time = strtotime('+1 month -1 second',$start_time);
        $field_name = 'origin';
        $opt_date_str = 'add_time';
        $origin = $origin_ex = '';
        $adminid_list = [];
        $tmk_adminid = -1;

        $ret_info = $this->task->t_seller_student_origin->get_origin_tongji_info_new($field_name,$opt_date_str ,$start_time,$end_time,$origin,$origin_ex,"",$adminid_list, $tmk_adminid);

        //统计试听课相关信息  ---begin---
        $data_map=&$ret_info["list"];
        //试听信息
        $test_lesson_list_new = $this->task->t_seller_student_origin->get_lesson_list_new($field_name,$opt_date_str ,$start_time,$end_time,$origin,$origin_ex,"",$adminid_list, $tmk_adminid);
        foreach ($test_lesson_list_new as  $test_item ) {
            $check_value=$test_item["check_value"];
            \App\Helper\Utils:: array_item_init_if_nofind( $data_map, $check_value,["check_value" => $check_value] );
            $data_map[$check_value]["require_count"] = $test_item["require_count"];
            $data_map[$check_value]["distinct_test_count"] = $test_item["distinct_test_count"];
            $data_map[$check_value]["succ_test_lesson_count"] = $test_item["succ_test_lesson_count"];
            $data_map[$check_value]["test_lesson_count"] = $test_item["test_lesson_count"];
            $data_map[$check_value]["distinct_succ_count"] = $test_item["distinct_succ_count"];
            //试听率
            if(@$data_map[$check_value]['tq_called_count'])
                $data_map[$check_value]["audition_rate"] = number_format($test_item["distinct_succ_count"]/$data_map[$check_value]['tq_called_count']*100,2);
            else
                $data_map[$check_value]["audition_rate"] = '';

        }

        //统计试听课相关信息  ---end---

        //统计订单相关信息  ---begin---
        //合同
        $order_list_new = $this->task->t_seller_student_origin->get_order_list_new($field_name,$opt_date_str ,$start_time,$end_time,$origin,$origin_ex,"",$adminid_list, $tmk_adminid);

        foreach ($order_list_new as  $order_item ) {
            $check_value=$order_item["check_value"];
            \App\Helper\Utils:: array_item_init_if_nofind( $data_map, $check_value,["check_value" => $check_value ] );

            $data_map[$check_value]["order_count"] = $order_item["order_count"];
            $data_map[$check_value]["user_count"] = $order_item["user_count"];
            $data_map[$check_value]["order_all_money"] = $order_item["order_all_money"];
        }
        //统计订单相关信息  ---end---
        foreach ($data_map as &$item ) {
            $item["title"]= @$item["check_value"];
            $item["origin"]= $item["title"];
        }

        //重组数据结构
        $ret_info["list"]= $this->gen_origin_data_level5(
            $ret_info["list"],
            ['avg_first_time','consumption_rate','called_rate','effect_rate','audition_rate'],
            $origin_ex
        );
        $sort = 0;
        //插入数据库
        foreach($ret_info["list"] as $item){
            if($job == 1)
                echo $sort.'ok'."\n";
            $id = $this->task->t_channel_funnel_archive_data->get_id_by_sort($sort,$start_time);
            if($id){
                //更新数据
                $this->task->t_channel_funnel_archive_data->field_update_list($id, [
                    'channel_name' => @$item['value'],
                    'add_time' => $start_time,
                    'all_count' => @$item['all_count'],
                    'heavy_count' => @$item['heavy_count'],
                    'assigned_count' => @$item['assigned_count'],
                    'tmk_assigned_count' => @$item['tmk_assigned_count'],
                    'avg_first_time' => @$item['avg_first_time'],
                    'tq_called_count' => @$item['tq_called_count'],
                    'tq_no_call_count' => @$item['tq_no_call_count'],
                    'consumption_rate' => @$item['consumption_rate'],
                    'called_num' => @$item['called_num'],
                    'tq_call_succ_valid_count' => @$item['tq_call_succ_valid_count'],
                    'tq_call_succ_invalid_count' => @$item['tq_call_succ_invalid_count'],
                    'called_rate' => @$item['called_rate'],
                    'effect_rate' => @$item['effect_rate'],
                    'tq_call_fail_count' => @$item['tq_call_fail_count'],
                    'tq_call_fail_invalid_count' => @$item['tq_call_fail_invalid_count'],
                    'have_intention_a_count' => @$item['have_intention_b_count'],
                    'have_intention_c_count' => @$item['have_intention_c_count'],
                    'require_count' => @$item['require_count'],
                    'test_lesson_count' => @$item['test_lesson_count'],
                    'succ_test_lesson_count' => @$item['succ_test_lesson_count'],
                    'audition_rate' => @$item['audition_rate'],
                    'order_count' => @$item['order_count'],
                    'user_count' => @$item['user_count'],
                    'order_all_money' => @$item['order_all_money'],
                    'distinct_succ_count' => @$item['distinct_succ_count'],
                    'distinct_test_count' => @$item['distinct_test_count'],
                    'key0' => @$item['key0'],
                    'key1' => @$item['key1'],
                    'key2' => @$item['key2'],
                    'key3' => @$item['key3'],
                    'key4' => @$item['key4'],
                    'key0_class' => @$item['key0_class'],
                    'key1_class' => @$item['key1_class'],
                    'key2_class' => @$item['key2_class'],
                    'key3_class' => @$item['key3_class'],
                    'key4_class' => @$item['key4_class'],
                    'old_key5' => @$item['old_key5'],
                    'level' => @$item['level'],
                    'sort' => $sort++,

                ]);

            }else{
                //添加数据
                $this->task->t_channel_funnel_archive_data->row_insert([
                    'channel_name' => @$item['value'],
                    'add_time' => $start_time,
                    'all_count' => @$item['all_count'],
                    'heavy_count' => @$item['heavy_count'],
                    'assigned_count' => @$item['assigned_count'],
                    'tmk_assigned_count' => @$item['tmk_assigned_count'],
                    'avg_first_time' => @$item['avg_first_time'],
                    'tq_called_count' => @$item['tq_called_count'],
                    'tq_no_call_count' => @$item['tq_no_call_count'],
                    'consumption_rate' => @$item['consumption_rate'],
                    'called_num' => @$item['called_num'],
                    'tq_call_succ_valid_count' => @$item['tq_call_succ_valid_count'],
                    'tq_call_succ_invalid_count' => @$item['tq_call_succ_invalid_count'],
                    'called_rate' => @$item['called_rate'],
                    'effect_rate' => @$item['effect_rate'],
                    'tq_call_fail_count' => @$item['tq_call_fail_count'],
                    'tq_call_fail_invalid_count' => @$item['tq_call_fail_invalid_count'],
                    'have_intention_a_count' => @$item['have_intention_b_count'],
                    'have_intention_c_count' => @$item['have_intention_c_count'],
                    'require_count' => @$item['require_count'],
                    'test_lesson_count' => @$item['test_lesson_count'],
                    'succ_test_lesson_count' => @$item['succ_test_lesson_count'],
                    'audition_rate' => @$item['audition_rate'],
                    'order_count' => @$item['order_count'],
                    'user_count' => @$item['user_count'],
                    'order_all_money' => @$item['order_all_money'],
                    'distinct_succ_count' => @$item['distinct_succ_count'],
                    'distinct_test_count' => @$item['distinct_test_count'],
                    'key0' => @$item['key0'],
                    'key1' => @$item['key1'],
                    'key2' => @$item['key2'],
                    'key3' => @$item['key3'],
                    'key4' => @$item['key4'],
                    'key0_class' => @$item['key0_class'],
                    'key1_class' => @$item['key1_class'],
                    'key2_class' => @$item['key2_class'],
                    'key3_class' => @$item['key3_class'],
                    'key4_class' => @$item['key4_class'],
                    'old_key5' => @$item['old_key5'],
                    'level' => @$item['level'],
                    'sort' => $sort++,
                ]);

            }
        }
    }

    //@desn:将数据重组结构
    //@param:$old_list 需要重组的数组
    //@param:$no_sum_list 不需要相加的列
    //@param:$origin_ex 渠道字符串
    private function gen_origin_data_level5($old_list,$no_sum_list=[] ,$origin_ex="")
    {
        $value_map=$this->task->t_origin_key->get_list( $origin_ex);
        //组织分层用类标识
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
        //计算相加数据
        $add_data=function (&$item, $add_item ,$self_flag=false) use ( $no_sum_list) {
            $arr=&$item["data"];
            foreach ($add_item as $k => $v) {
                if (!is_int($k) && $k!="origin" && ($self_flag|| !in_array($k,$no_sum_list))){
                    if (!isset($arr[$k])) {
                        $arr[$k]=0;
                    }
                    $arr[$k]+=$v;
                }
            }

        };

        $all_item=["origin"=>"全部"];
        $check_init_map_item($data_map,"","");
        foreach ($old_list as &$item) {
            $value=trim($item["origin"]);
            //没有key0 key1 key2 key3
            if (!isset($value_map[$value])) {
                $value_map[$value]=[
                    "key0"=>"未定义",
                    "key1"=>"未定义",
                    "key2"=>"未定义",
                    "key3"=>"未定义",
                    "key4"=>$value,
                    "value"=>$value,
                ];
            }

            $conf=$value_map[$value];

            $key0=$conf["key0"];
            $key1=$conf["key1"];
            $key2=$conf["key2"];
            $key3=$conf["key3"];
            $key4=$conf["key4"];
            $key_map=&$data_map[""];
            $add_data($key_map, $item );

            $check_init_map_item($key_map["sub_list"] , $key0,"key0" );
            $key0_map=&$key_map["sub_list"][$key0];
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
        foreach ($data_map as $key0 => $item0) {  //第0层
            $data=$item0["data"];
            $data["key0"]="全部";
            $data["key1"]="";
            $data["key2"]="";
            $data["key3"]="";
            $data["key4"]="";
            $data["key0_class"]="";
            $data["key1_class"]="";
            $data["key2_class"]="";
            $data["key3_class"]="";
            $data["key4_class"]="";
            $data["level"]="l-0";

            $list[]=$data;
            foreach ($item0["sub_list"] as $key1 => $item1) {//第1层
                $data=$item1["data"];
                $data["key0"]=$key1;
                $data["key1"]="";
                $data["key2"]="";
                $data["key3"]="";
                $data["key4"]="";
                $data["key0_class"]=$item1["key_class"];
                $data["key1_class"]="";
                $data["key2_class"]="";
                $data["key3_class"]="";
                $data["key4_class"]="";
                $data["level"]="l-1";

                $list[]=$data;

                foreach ($item1["sub_list"] as $key2 => $item2) {//第2层
                    $data=$item2["data"];
                    $data["key0"]=$key1;
                    $data["key1"]=$key2;
                    $data["key2"]="";
                    $data["key3"]="";
                    $data["key4"]="";
                    $data["key0_class"]=$item1["key_class"];
                    $data["key1_class"]=$item2["key_class"];
                    $data["key2_class"]="";
                    $data["key3_class"]="";
                    $data["key4_class"]="";
                    $data["level"]="l-2";

                    $list[]=$data;
                    foreach ($item2["sub_list"] as $key3 => $item3) {//第3层
                        $data=$item3["data"];
                        $data["key0"]=$key1;
                        $data["key1"]=$key2;
                        $data["key2"]=$key3;
                        $data["key3"]="";
                        $data["key4"]="";
                        $data["key0_class"]=$item1["key_class"];
                        $data["key1_class"]=$item2["key_class"];
                        $data["key2_class"]=$item3["key_class"];
                        $data["key3_class"]="";
                        $data["key4_class"]="";
                        $data["level"]="l-3";

                        $list[]=$data;
                        foreach ($item3["sub_list"] as $key4 => $item4) {//第4层
                            $data=$item4["data"];
                            $data["key0"]=$key1;
                            $data["key1"]=$key2;
                            $data["key2"]=$key3;
                            $data["key3"]=$key4;
                            $data["key4"]="";
                            $data["key0_class"]=$item1["key_class"];
                            $data["key1_class"]=$item2["key_class"];
                            $data["key2_class"]=$item3["key_class"];
                            $data["key3_class"]=$item4["key_class"];
                            $data["key4_class"]="";
                            $data["level"]="l-4";
                            $list[]=$data;

                            foreach ($item4["sub_list"] as $key5 => $item5) {//第5层
                                $data=$item5["data"];
                                $data["key0"]=$key1;
                                $data["key1"]=$key2;
                                $data["key2"]=$key3;
                                $data["key3"]=$key4;
                                $data["key4"]=$key5;
                                $data["value"] = $item5["value"];
                                $data["key0_class"]=$item1["key_class"];
                                $data["key1_class"]=$item2["key_class"];
                                $data["key2_class"]=$item3["key_class"];
                                $data["key3_class"]=$item4["key_class"];
                                $data["key4_class"]=$item5["key_class"];
                                $k5_v=$item5["value"];
                                if ($k5_v != $key5) {
                                    $data["key5"]=$key5."/". $k5_v ;
                                }
                                $data["old_key5"]=$key5;
                                $data["level"]="l-5";
                                $list[]=$data;
                            }
                        }

                    }

                }


            }
        }
        // dd($list);
        foreach($list as &$item){
            if($item["level"]=="l-5" && $item["key0"]!="未定义"){
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
