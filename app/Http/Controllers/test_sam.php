<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Cookie;
use App\Http\Controllers\Controller;
use \App\Libs;
use \App\Config as C;
use \App\Enums as E;
use App\Helper\Utils;

class test_sam  extends Controller
{
    use CacheNick;
    use TeaPower;

    public function kk(){
        // $teacherid = 60024;
        // $info = $this->t_teacher_info->get_subject_grade_by_teacherid($teacherid);
        // dd($info);
        phpinfo();
    }
    
    

    


    function request_post($url = '', $param = '')
    {
        if (empty($url) || empty($param)) {
            return false;
        }

        $postUrl = $url;
        $curlPost = $param;
        // 初始化curl
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $postUrl);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        // 要求结果为字符串且输出到屏幕上
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        // post提交方式
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
        // 运行curl
        $data = curl_exec($curl);
        curl_close($curl);

        return $data;
    }

    
    
    public function channel_statistics(){
        //  $this->check_and_switch_tongji_domain();
        $origin            = trim($this->get_in_str_val("origin",""));
        $origin_ex         = $this->get_in_str_val('origin_ex', "");
        $seller_groupid_ex = $this->get_in_str_val('seller_groupid_ex', "");
        $adminid_list      = $this->t_admin_main_group_name->get_adminid_list_new($seller_groupid_ex);
        $admin_revisiterid = $this->get_in_int_val("admin_revisiterid", -1);
        $groupid           = $this->get_in_int_val("groupid",-1);
        $tmk_adminid       = $this->get_in_int_val("tmk_adminid", -1);
        $check_field_id    = $this->get_in_int_val("check_field_id",1);
        $is_history = $this->get_in_int_val('is_history',1);
        $sta_data_type = $this->get_in_int_val('sta_data_type',1);
        $check_field_config=[
            1=> ["渠道","origin", "" ],
            2=> ["年级","grade", E\Egrade::class ],
            3=> ["科目","subject", E\Esubject::class ],
            4=> ["tmk人员","tmk_adminid", "" ],
            5=> ["销售人员","admin_revisiterid", "" ],
            6=> ["渠道等级","origin_level",   E\Eorigin_level::class  ],
        ];

        $data_map=[];
        $check_item=$check_field_config[$check_field_id];
        $field_name       = $check_item[1];
        $field_class_name = $check_item[2];

        list($start_time,$end_time ,$opt_date_str)=$this->get_in_date_range_month( date("Y-m-01"), 0, [
            0 => array( "add_time", "资源进来时间"),
            1 => array("tmk_assign_time","微信运营时间"),
            2 => array("tlsr.require_time","试听申请时间"),
            3 => array("tlsr.accept_time","试听排课时间"),
            4 => array("li.lesson_end","成功试听时间"),
            5 => array("oi.order_time","签单时间"),
        ] );

        $is_history=2;
        $sta_data_type = 1;

        //
        //初始化画图用数据
        $subject_map = $grade_map = $has_pad_map = $origin_level_map = $area_map = $test_grade_map = array();
        $order_area_map = $order_subject_map = $order_grade_map = $test_area_map = $test_subject_map = array();
        $test_has_pad_map = $test_origin_level_map = $order_has_pad_map = $order_origin_level_map = array();
        $group_list = array();
        $origin_type = 1;
        //初始化是否显示饼图标识
        $is_show_pie_flag = 0;
        //月初时间戳
        $month_begin = strtotime(date('Y-m-01',$start_time));
        $this->switch_tongji_database();

        if($is_history == 1 && $sta_data_type == 1){
            //漏斗形存档数据
            $ret_info = $this->t_channel_funnel_archive_data->get_list($month_begin,$origin_ex);
        }elseif($is_history == 1 && $sta_data_type == 2){
            //节点型存档数据
            $ret_info = $this->t_channel_node_type_statistics->get_list($month_begin,$origin_ex);
        }elseif($is_history == 2 && $sta_data_type == 2){
            //节点型实时数据
            //例子总量
            $ret_info = $this->t_test_lesson_subject->get_example_num_now($field_name,$opt_date_str ,$start_time,$end_time,$origin,$origin_ex,"",$adminid_list, $tmk_adminid);
            $data_map=&$ret_info["list"];
            //试听预约数
            $test_lesson_require_data = $this->t_test_lesson_subject_require->get_test_lesson_quire_info($origin, $field_name,$start_time,$end_time,$adminid_list,$tmk_adminid,$origin_ex);
            foreach($test_lesson_require_data as $item){
                $channel_name=$item["check_value"];

                \App\Helper\Utils:: array_item_init_if_nofind( $data_map, $channel_name,["check_value" => $channel_name] );
                $data_map[$channel_name]["require_count"] = $item["require_count"];

            }
            //试听信息
            $test_lesson_data = $this->t_test_lesson_subject_require->get_test_lesson_data_now($origin, $field_name,$start_time,$end_time,$adminid_list,$tmk_adminid,$origin_ex);
            foreach ($test_lesson_data as  $test_item ) {
                $channel_name=$test_item["check_value"];

                \App\Helper\Utils:: array_item_init_if_nofind( $data_map, $channel_name,["check_value" => $channel_name] );
                $data_map[$channel_name]["test_lesson_count"] = $test_item["test_lesson_count"];
                $data_map[$channel_name]["distinct_test_count"] = $test_item["distinct_test_count"];
                $data_map[$channel_name]["succ_test_lesson_count"] = $test_item["succ_test_lesson_count"];
                $data_map[$channel_name]["distinct_succ_count"] = $test_item["distinct_succ_count"];
                //试听率
                if(@$data_map[$channel_name]['tq_called_count'])
                    $data_map[$channel_name]["audition_rate"] = number_format($test_item["distinct_succ_count"]/$data_map[$channel_name]['tq_called_count']*100,2);
                else
                    $data_map[$channel_name]["audition_rate"] = '';

            }
            //订单信息
            $order_data = $this->t_order_info->get_node_type_order_data_now($field_name,$start_time,$end_time,$adminid_list,$tmk_adminid,$origin_ex,$opt_date_str, $origin);
            foreach($order_data as $order_item){
                $channel_name=$order_item["check_value"];
                $channel_name= \App\Helper\Utils:: array_item_init_if_nofind($data_map, $channel_name,["check_value" => $channel_name]);

                $data_map[$channel_name]["order_count"] = $order_item["order_count"];
                $data_map[$channel_name]["user_count"] = $order_item["user_count"];
                $data_map[$channel_name]["order_all_money"] = $order_item["order_all_money"];
            }



        }elseif($is_history == 2 && $sta_data_type == 1){
            //漏斗型实时数据
            if(in_array($opt_date_str,['add_time','tmk_assign_time'])){
                //显示饼图
                $is_show_pie_flag = 1;
                $ret_info = $this->t_seller_student_origin->get_origin_tongji_info_new($field_name,$opt_date_str ,$start_time,$end_time,$origin,$origin_ex,"",$adminid_list, $tmk_adminid);

                //统计试听课相关信息  ---begin---
                $data_map=&$ret_info["list"];
                //试听信息
                $test_lesson_list_new = $this->t_seller_student_origin->get_lesson_list_new($field_name,$opt_date_str ,$start_time,$end_time,$origin,$origin_ex,"",$adminid_list, $tmk_adminid);
                // $test_lesson_list=$this->t_test_lesson_subject_require->tongji_test_lesson_origin( $origin, $field_name,$start_time,$end_time,$adminid_list,$tmk_adminid, $origin_ex );
                foreach ($test_lesson_list_new as  $test_item ) {
                    $check_value=$test_item["check_value"];
                    $check_value=\App\Helper\Utils:: array_item_init_if_nofind( $data_map, $check_value,["check_value" => $check_value] );
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
                //统计试听课相关信息  ---begin---

                //统计订单相关信息  ---begin---
                //合同
                $order_list_new = $this->t_seller_student_origin->get_order_list_new($field_name,$opt_date_str ,$start_time,$end_time,$origin,$origin_ex,"",$adminid_list, $tmk_adminid);

                // $order_list= $this->t_order_info->tongji_seller_order_count_origin( $field_name,$start_time,$end_time,$adminid_list,$tmk_adminid,$origin_ex,$opt_date_str, $origin);
                foreach ($order_list_new as  $order_item ) {
                    $check_value=$order_item["check_value"];
                    $check_value=\App\Helper\Utils:: array_item_init_if_nofind( $data_map, $check_value,["check_value" => $check_value ] );

                    $data_map[$check_value]["order_count"] = $order_item["order_count"];
                    $data_map[$check_value]["user_count"] = $order_item["user_count"];
                    $data_map[$check_value]["order_all_money"] = $order_item["order_all_money"];
                }
                //统计订单相关信息  ---end---


                //饼图用数据 --begin--
                //地区、年级科目、硬件、渠道等级等统计饼图数据
                $data_list = $this->t_seller_student_origin->get_origin_detail_info($opt_date_str,$start_time,$end_time,$origin,$origin_ex,"",$adminid_list,$tmk_adminid);
                $all_count        = count($data_list);

                foreach ($data_list as $a_item) {
                    $subject      = $a_item["subject"];
                    $grade        = $a_item["grade"];
                    $has_pad      = $a_item["has_pad"];
                    $origin_level = $a_item["origin_level"];
                    $area_name    = substr($a_item["phone_location"], 0, -6);
                    @$subject_map[$subject] ++;
                    @$grade_map[$grade] ++;
                    @$has_pad_map[$has_pad] ++;
                    @$origin_level_map[$origin_level] ++;
                    if (strlen($area_name)>5) {
                        @$area_map[$area_name] ++;
                    } else {
                        @$area_map[""] ++;
                    }

                }

                $group_list = $this->t_admin_group_name->get_group_list(2);


                //签单统计用饼图
                //订单信息
                $order_data = $this->t_order_info->tongji_seller_order_info($origin, $field_name,$start_time,$end_time,$adminid_list,$tmk_adminid,$origin_ex,$opt_date_str);
                foreach ($order_data as $a_item) {
                    $subject   = $a_item["subject"];
                    $grade     = $a_item["grade"];
                    $area_name = substr($a_item["phone_location"], 0, -6);
                    $has_pad      = $a_item["has_pad"];
                    $origin_level = $a_item["origin_level"];
                    @$order_subject_map[$subject] ++;
                    @$order_grade_map[$grade] ++;
                    @$order_has_pad_map[$has_pad] ++;
                    @$order_origin_level_map[$origin_level] ++;

                    if (strlen($area_name)>5) {
                        @$order_area_map[$area_name] ++;
                    } else {
                        @$order_area_map[""] ++;
                    }

                }

                //试听统计用饼图
                //试听信息
                $test_data=$this->t_test_lesson_subject_require->tongji_test_lesson_origin_info( $origin, $field_name,$start_time,$end_time,$adminid_list,$tmk_adminid, $origin_ex,'','','',$opt_date_str);
                foreach ($test_data as $a_item) {
                    $subject   = $a_item["subject"];
                    $grade     = $a_item["grade"];
                    $area_name = substr($a_item["phone_location"], 0, -6);
                    $has_pad      = $a_item["has_pad"];
                    $origin_level = $a_item["origin_level"];
                    @$test_subject_map[$subject] ++;
                    @$test_grade_map[$grade] ++;
                    @$test_has_pad_map[$has_pad] ++;
                    @$test_origin_level_map[$origin_level] ++;

                    if (strlen($area_name)>5) {
                        @$test_area_map[$area_name] ++;
                    } else {
                        @$test_area_map[""] ++;
                    }

                }

                //饼图用数据 --end--


            }elseif(in_array($opt_date_str,['tlsr.require_time','tlsr.accept_time'])){
                //时间检索[试听申请时间][试听排课时间]用

                $ret_info = $this->t_test_lesson_subject_require->get_funnel_data($field_name,$opt_date_str ,$start_time,$end_time,$origin,$origin_ex,"",$adminid_list, $tmk_adminid);
                $data_map=&$ret_info["list"];
                //计算不重复的订单数[合同人数] ---begin--
                $order_info = $this->t_test_lesson_subject_require->get_distinct_order_info($field_name,$opt_date_str ,$start_time,$end_time,$origin,$origin_ex,"",$adminid_list, $tmk_adminid);
                foreach ($order_info as  $item ) {
                    $check_value=$item["check_value"];
                    $check_value=\App\Helper\Utils:: array_item_init_if_nofind( $data_map, $check_value,["check_value" => $check_value] );
                    $data_map[$check_value]["user_count"] = $item["user_count"];
                    //矫正预约数、上课成功数
                    $data_map[$check_value]["require_count"] = $data_map[$check_value]["require_count"]-($data_map[$check_value]["order_count"]-$data_map[$check_value]["user_count"]);
                    $data_map[$check_value]["succ_test_lesson_count"] = $data_map[$check_value]["succ_test_lesson_count"]-($data_map[$check_value]["order_count"]-$data_map[$check_value]["user_count"]);
                }
                //计算不重复的订单数[合同人数] ---end--

            }elseif(in_array($opt_date_str,['li.lesson_end'])){

                //时间检索[成功试听]用
                $ret_info = $this->t_lesson_info->get_funnel_data($field_name,$opt_date_str ,$start_time,$end_time,$origin,$origin_ex,"",$adminid_list, $tmk_adminid);
                $data_map=&$ret_info["list"];
                //计算不重复的订单数[合同人数] ---begin--
                $order_info = $this->t_lesson_info->get_distinct_order_info($field_name,$opt_date_str ,$start_time,$end_time,$origin,$origin_ex,"",$adminid_list, $tmk_adminid);
                foreach ($order_info as  $item ) {
                    $check_value=$item["check_value"];
                    $check_value=\App\Helper\Utils:: array_item_init_if_nofind( $data_map, $check_value,["check_value" => $check_value] );
                    $data_map[$check_value]["user_count"] = $item["user_count"];
                    //上课成功数
                    $data_map[$check_value]["succ_test_lesson_count"] = $data_map[$check_value]["succ_test_lesson_count"]-($data_map[$check_value]["order_count"]-$data_map[$check_value]["user_count"]);

                }
                //计算不重复的订单数[合同人数] ---end--

            }elseif(in_array($opt_date_str,['oi.order_time'])){

                //时间检索[签单时间]用
                $ret_info = $this->t_order_info->get_funnel_data($field_name,$opt_date_str ,$start_time,$end_time,$origin,$origin_ex,"",$adminid_list, $tmk_adminid);
                $data_map=&$ret_info["list"];
            }

            
        }

        //实时数据需要对数据进行处理
        if($is_history == 2){
            foreach ($data_map as &$item ) {
                if($field_class_name ) {
                    $item["title"]= $field_class_name::get_desc($item["check_value"]);
                }else{
                    if ($field_name=="tmk_adminid" || $field_name=="admin_revisiterid"  ) {
                        $item["title"]= $this->cache_get_account_nick( $item["check_value"] );
                    }else{
                        $item["title"]= @$item["check_value"];
                    }

                }

                if ($field_name=="origin") {
                    $item["origin"]= $item["title"];
                }
            }
            //重组分层数组
            if ($field_name=="origin"){
                $ret_info["list"]= $this->gen_origin_data_level5(
                    $ret_info["list"],
                    ['avg_first_time','consumption_rate','called_rate','effect_rate','audition_rate'],
                    $origin_ex
                );
            } 
        }

        //将显示饼图标识发送到js
        $this->set_filed_for_js('is_show_pie_flag', $is_show_pie_flag);
        echo "<table>";
        echo "<th>月份</th>";
        echo "<th>k0</th>";
        echo "<th>k1</th>";
        echo "<th>k2</th>";
        echo "<th>k3</th>";
        echo "<th>渠道</th>";

        echo "<th>例子总数(去重) </th>";
        echo "<th>已拨打</th>";
        echo "<th>已拨通 </th>";
        echo "<th>上课数(去重)</th>";
        echo "<th>上课成功数(去重)</th>";
        echo "<th>合同人数</th>";
        echo "<th>合同金额</th>";
        $month = date("Y-m",$start_time);
        foreach ($ret_info['list'] as $key => $value) {
            if($value['level'] == 'l-5'){

                echo "<td width='30'>";echo @$month; echo "</td>";
                echo "<td width='30'>";echo @$value['key0']; echo "</td>";
                echo "<td width='30'>";echo @$value['key1']; echo "</td>";
                echo "<td width='30'>";echo @$value['key2']; echo "</td>";
                echo "<td width='30'>";echo @$value['key3']; echo "</td>";
                echo "<td width='30'>";echo @$value['key4']; echo "</td>";

                echo "<td width='30'>";echo @$value['heavy_count']; echo "</td>";
                echo "<td width='30'>";echo @$value['tq_called_count']; echo "</td>";
                echo "<td width='30'>";echo @$value['called_num']; echo "</td>";
                echo "<td width='30'>";echo @$value['distinct_test_count']; echo "</td>";
                echo "<td width='30'>";echo @$value['distinct_succ_count']; echo "</td>";
                echo "<td width='30'>";echo @$value['user_count']; echo "</td>";
                echo "<td width='30'>";echo @$value['order_all_money']; echo "</td>";
            }
        }
        echo "</table>";
        dd($ret_info);
        //dd($ret_info);
        return $this->pageView(__METHOD__,$ret_info,[
            "subject_map"      => $subject_map,
            "grade_map"        => $grade_map,
            "has_pad_map"      => $has_pad_map,
            "origin_level_map" => $origin_level_map,
            "area_map"         => $area_map,
            "group_list"       => $group_list,
            "field_name"       => $field_name,
            "origin_type"      => $origin_type,
            "order_area_map"   => $order_area_map,
            "order_subject_map"=> $order_subject_map,
            "order_grade_map"  => $order_grade_map,
            "test_area_map"   => $test_area_map,
            "test_subject_map"=> $test_subject_map,
            "test_grade_map"  => $test_grade_map,
            'is_show_pie_flag' => $is_show_pie_flag,
            "test_has_pad_map"      => $test_has_pad_map,
            "test_origin_level_map" => $test_origin_level_map,
            "order_has_pad_map"      => $order_has_pad_map,
            "order_origin_level_map" => $order_origin_level_map,
            'is_history' => $is_history
        ]);

    }
    //@desn:将数据重组结构
    //@param:$old_list 需要重组的数组
    //@param:$no_sum_list 不需要相加的列
    //@param:$origin_ex 渠道字符串
    public function gen_origin_data_level5($old_list,$no_sum_list=[] ,$origin_ex="")
    {
        $value_map=$this->t_origin_key->get_list( $origin_ex);
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
                    @$arr[$k]+=$v;
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


    public function teacher_spring(){
        $start = "1518624000";
        $end   = "1519228800";

        
        for ($i=$start; $i <= $end ; ) { 
            $start_time = $i;
            $end_time   = $i + 86400;

            $day  = date("Y-m-d",$start_time);
            $ret = $this->t_teacher_spring->get_info($start_time,$end_time);
            $count = $this->t_teacher_spring->get_info_count($start_time,$end_time);
            echo "<div align='center'>";
            echo "<span >".$day."参与人次".$count."</span>";
            echo "<table align='center' border='1px solid red'>"; 
            echo "<th width='200px'>获奖人姓名</th><th width='200px'>手机号</th><th width='200px'>时间</th><th width='200px'>次数</th>";                    
            foreach ($ret as $key => $value) {
                if($value['result'] == 1){
                    echo "<tr>";
                    $nick = $this->t_teacher_info->get_nick($value['teacherid']);
                    echo "<td width='200px' align='center'>";echo $nick;echo "</td>";
                    $phone  = $this->t_teacher_info->get_phone($value['teacherid']);
                    echo "<td width='200px' align='center'>";echo $phone;echo "</td>";
                    $time = date("Y-m-d H:i:s",$value['add_time']);
                    echo "<td width='200px' align='center'>";echo $time;echo "</td>";
                    echo "<td width='200px' align='center'>";echo $value['rank'];echo "</td>";
                    echo "</tr>";
                }
                
            }
            echo "</table>";
            echo "</div>";
            $i = $i + 86400;    
        }   

       
    }
    
}

