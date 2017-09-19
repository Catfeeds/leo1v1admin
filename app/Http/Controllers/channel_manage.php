<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use App\Helper\Utils;
use Illuminate\Support\Facades\Cookie ;

class channel_manage extends Controller
{
    use CacheNick;
    use TeaPower;

    public function admin_channel_manage(){
        $ret_info = $this->t_admin_channel_list->get_admin_channel_info();
        // dd($ret_info);
        $list=[];
        $num=1;
        foreach($ret_info as $s)   {
            $n = $num;
            $channel_id = $s["channel_id"];
            $channel_name = $s["channel_name"];

            $list[] = [
                "channel_id"=>$channel_id, //
                "channel_name"=>$channel_name, //
                "up_group_name"=>"",
                "group_name"=>"",
                "account"=>"",

                "main_type_class"=>"campus_id-".$n,
                "up_group_name_class"=>"",
                "group_name_class"=>"",
                "account_class"=>"",

                "level"=>"l-1" //
            ];

            $group_list = $this->t_admin_channel_group->get_group_id_list($channel_id);
            //dd($group_list);
            foreach($group_list as $item){
                $list[] = [
                    "channel_id"=>$channel_id, //
                    "channel_name"=>$channel_name,//
                    "up_group_name"=>'',
                    "group_name"=>E\Eteacher_ref_type::get_desc($item["ref_type"]), //
                    "account"=>"",

                    "main_type_class"=>"campus_id-".$n,
                    "up_group_name_class"=>"up_group_name-".++$num,
                    "group_name_class"=>"",
                    "account_class"=>"",

                    "level"=>"l-2",
                    "up_master_adminid"=>'',
                    "group_id"=>$item["ref_type"], //
                    "main_type"=>''
                    ];

                $admin_list = $this->t_admin_channel_user->get_user_list_new($item["ref_type"]);

                $m = $num;
                foreach($admin_list as $val){
                    $list[] = [
                        "channel_id"=>$channel_id, //
                        "channel_name"=>$channel_name,//
                        "group_name"=>E\Eteacher_ref_type::get_desc($item["ref_type"]), // admin_id
                        "group_id"=>$item["ref_type"],
                        "account"=>"",

                        "main_type_class"=>"campus_id-".$n,
                        "up_group_name_class"=>"up_group_name-".$m,
                        "group_name_class"=>"group_name-".++$num,
                        "account_class"=>"",

                        "admin_id"=>@$val["teacherid"],
                        "admin_name"=>@$val["realname"],
                        "level"=>"l-3",
                        "master_adminid"=>'',
                        "main_type"=>'',
                        "admin_phone" => $val['phone'],
                        "zs_id" => $val['zs_id'],
                        "zs_name" => $this->cache_get_account_nick($val["zs_id"] ),
                        "email" => $val['email'],
                        "teacher_type_str" =>E\Eteacher_type::get_desc($val["teacher_type"]),
                    ];

                    $test_list = [];
                    $c = $num;
                    foreach($test_list as $v){
                        $list[] = [
                            "channel_id"=>$channel_id,
                            "channel_name"=>$channel_name,
                            "up_group_name"=>$item["group_name"],
                            "group_name"=>$val["group_name"],
                            "account"=>'',
                            "main_type_class"=>"campus_id-".$n,
                            "up_group_name_class"=>"up_group_name-".$m,
                            "group_name_class"=>"group_name-".$c,
                            "account_class"=>"account-".++$num,
                            "adminid"=>'',
                            "groupid"=>'',
                            "level"=>"l-4",
                            "main_type"=>'',
                        ];

                    }
                }

            }
            $num++;
        }
        return $this->pageView(__METHOD__,\App\Helper\Utils::list_to_page_info($list));

      
    }


    /*
     *@author   sam
     *@function 新增渠道
     */
    public function add_channel(){
        $channel_name =trim($this->get_in_str_val("channel_name"));
        $this->t_admin_channel_list->row_insert([
            "channel_name" =>$channel_name
        ]);
        return $this->output_succ();
    }

    /*
     *@author   sam
     *@function 新增渠道
     */
    public function set_channel_id(){
        $channel_id= $this->get_in_int_val("channel_id");
        $group_id = $this->get_in_int_val("group_id");
        $ret = $this->t_admin_channel_group->get_group($group_id);
        if($ret[0]['channel_id'] == 0){
            $ret = $this->t_admin_channel_group->field_update_list($ret[0]['ref_type'],[
                "channel_id"  =>$channel_id 
            ]);
        }else{
            $ret = $this->t_admin_channel_group->field_update_list($ret[0]['ref_type'],[
                "channel_id"  =>$channel_id 
            ]);
        }
        
        return $this->output_succ();
    }

    public function get_teacher_type_ref(){
        $page_num     = $this->get_in_page_num();
        $ret_info   = $this->t_admin_channel_group->get_all_group_id($page_num);
        if($ret_info['list'] != []){
            foreach( $ret_info["list"] as $key => &$item ) {
                $item['group_name']        = E\Eteacher_ref_type::get_desc($item["ref_type"]);
                $item["group_id"]  = $item['ref_type'];
            }
            $ret_info["page_info"] = $this->get_page_info_for_js( $ret_info["page_info"]   );
            return outputjson_success(array('data' => $ret_info));
        }else{
            $arr = E\Eteacher_ref_type::$desc_map;  
            foreach ($arr as $key => $value) {
                $ret = $this->t_admin_channel_group->row_insert([
                    'ref_type'  => $key,
                ]);
            }
            $ret_info   = $this->t_admin_channel_group->get_all_group_id($page_num);
            foreach( $ret_info["list"] as $key => &$item ) {
                $item['group_name']        = E\Eteacher_ref_type::get_desc($item["ref_type"]);
                $item["group_id"]  = $item['ref_type'];
            }
            $ret_info["page_info"] = $this->get_page_info_for_js( $ret_info["page_info"]   );
            return outputjson_success(array('data' => $ret_info));
        }
    }

    public function get_teacher_admin(){
        $page_num     = $this->get_in_page_num();
        $ret_info   = $this->t_admin_channel_group->get_all_teacher($page_num);
        $ret_info["page_info"] = $this->get_page_info_for_js( $ret_info["page_info"]   );
        return outputjson_success(array('data' => $ret_info));
    }
    public function update_channel_name(){
        $channel_name =trim($this->get_in_str_val("channel_name"));
        $channel_id =$this->get_in_int_val("channel_id");
        $ret = $this->t_admin_channel_list->field_update_list( $channel_id,[
                "channel_name"  =>$channel_name 
            ]);
        return $this->output_succ();
    }
    public function set_teacher_ref_type()
    {
        $teacher_id =$this->get_in_int_val("teacher_id");
        $group_id =$this->get_in_int_val("group_id");
        if($group_id>=0){
            $ret = $this->t_teacher_info->field_update_list( $teacher_id,[
                "teacher_ref_type"  =>$group_id 
            ]);
            return $this->output_succ();
        }else{
            return $this->output_error();
        }
    }

    public function zs_origin_list_new(){
        $channel_info = $this->t_admin_channel_list->get_admin_channel_info();
        $list=[];
        $num=1;
        foreach($channel_info as $s)   {
            $n = $num;
            $channel_id = $s["channel_id"];
            $channel_name = $s["channel_name"];

            $list[] = [
                "channel_id"=>$channel_id, //
                "channel_name"=>$channel_name, //
                "up_group_name"=>"",
                "group_name"=>"",
                "account"=>"",
                "main_type_class"=>"campus_id-".$n,
                "up_group_name_class"=>"",
                "group_name_class"=>"",
                "account_class"=>"",
                "level"=>"l-1" //
            ];

            $group_list = $this->t_admin_channel_group->get_group_id_list($channel_id);
            //dd($group_list);
            foreach($group_list as $item){
                $list[] = [
                    "channel_id"=>$channel_id, //
                    "channel_name"=>$channel_name,//
                    "up_group_name"=>'',
                    "group_name"=>E\Eteacher_ref_type::get_desc($item["ref_type"]), //
                    "account"=>"",

                    "main_type_class"=>"campus_id-".$n,
                    "up_group_name_class"=>"up_group_name-".++$num,
                    "group_name_class"=>"",
                    "account_class"=>"",

                    "level"=>"l-2",
                    "up_master_adminid"=>'',
                    "group_id"=>$item["ref_type"], //
                    "main_type"=>''
                    ];

                $admin_list = $this->t_admin_channel_user->get_user_list_new($item["ref_type"]);

                $m = $num;
                foreach($admin_list as $val){
                    $list[] = [
                        "channel_id"=>$channel_id, //
                        "channel_name"=>$channel_name,//
                        "group_name"=>E\Eteacher_ref_type::get_desc($item["ref_type"]), // admin_id
                        "group_id"=>$item["ref_type"],
                        "account"=>"",

                        "main_type_class"=>"campus_id-".$n,
                        "up_group_name_class"=>"up_group_name-".$m,
                        "group_name_class"=>"group_name-".++$num,
                        "account_class"=>"",

                        "admin_id"=>@$val["teacherid"],
                        "admin_name"=>@$val["realname"],
                        "level"=>"l-3",
                        "master_adminid"=>'',
                        "main_type"=>'',
                        "admin_phone" => $val['phone'],
                    ];

                    $test_list = [];
                    $c = $num;
                    foreach($test_list as $v){
                        $list[] = [
                            "channel_id"=>$channel_id,
                            "channel_name"=>$channel_name,
                            "up_group_name"=>$item["group_name"],
                            "group_name"=>$val["group_name"],
                            "account"=>'',
                            "main_type_class"=>"campus_id-".$n,
                            "up_group_name_class"=>"up_group_name-".$m,
                            "group_name_class"=>"group_name-".$c,
                            "account_class"=>"account-".++$num,
                            "adminid"=>'',
                            "groupid"=>'',
                            "level"=>"l-4",
                            "main_type"=>'',
                        ];

                    }
                }

            }
            $num++;
        }
        //dd($list);
        //---------------------------calculate---------------------------------------
        $this->switch_tongji_database(); 
        list($start_time,$end_time) = $this->get_in_date_range(0,0,0,null,3); 

        //报名数
        $ret_info = $this->t_teacher_lecture_appointment_info->get_app_lecture_sum_by_reference($start_time,$end_time);
      
       //录制试讲提交数
        $video_add = $this->t_teacher_lecture_info->get_video_add_num_by_reference($start_time,$end_time);

        ///面试预约数
        $lesson_add = $this->t_lesson_info_b2->get_lesson_add_num_by_reference($start_time,$end_time);
        
        //入职总人数以及各老师类型入职人数
        $train_through_all = $this->t_teacher_info->get_train_through_all_list($start_time,$end_time);

        //录制试讲入职人数
        $train_through_video = $this->t_teacher_info->get_train_through_video_list($start_time,$end_time);

        //面试试讲入职人数
        $train_through_lesson = $this->t_teacher_info->get_train_through_lesson_list($start_time,$end_time);

        foreach($ret_info as $k=>&$val){
            $val["video_add_num"] = isset($video_add[$k]["video_add_num"])?$video_add[$k]["video_add_num"]:0;
            $val["lesson_add_num"] = isset($lesson_add[$k]["lesson_add_num"])?$lesson_add[$k]["lesson_add_num"]:0;
            
            $val["through_all"] = isset($train_through_all[$k])?$train_through_all[$k]["through_all"]:0;

            $val["through_jg"] = isset($train_through_all[$k])?$train_through_all[$k]["through_jg"]:0;
            $val["through_gx"] = isset($train_through_all[$k])?$train_through_all[$k]["through_gx"]:0;
            $val["through_zz"] = isset($train_through_all[$k])?$train_through_all[$k]["through_zz"]:0;
            $val["through_gxs"] = isset($train_through_all[$k])?$train_through_all[$k]["through_gxs"]:0;

            $val["through_video"] = isset($train_through_video[$k])?$train_through_video[$k]["through_video"]:0;
            $val["through_lesson"] = isset($train_through_lesson[$k])?$train_through_lesson[$k]["through_lesson"]:0;
        }
        foreach($video_add as $k=>$v){
            if(!isset($ret_info[$k])){
                $ret_info[$k]=$v;
            }
        }
        foreach($lesson_add as $k=>$v){
            if(!isset($ret_info[$k])){
                $ret_info[$k]=$v;
            }
        }
        foreach($train_through_all as $k=>$v){
            if(!isset($ret_info[$k])){
                $ret_info[$k]=$v;
            }
        }
        //dd($ret_info);
        foreach ($list as $key => $value) {

            if(isset($value['admin_phone'])){
                //print_r($value['admin_phone']);
                $phone = intval($value['admin_phone']);
                if(isset($ret_info[$phone])){
                    $list[$key]['app_num']         = @$ret_info[$phone]['app_num'];
                    $list[$key]['video_add_num']   = @$ret_info[$phone]['video_add_num'];
                    $list[$key]['lesson_add_num']  = @$ret_info[$phone]['lesson_add_num'];

                    $list[$key]['through_all']     = @$ret_info[$phone]['through_all'];
                    $list[$key]['through_jg']      = @$ret_info[$phone]['through_jg'];

                    $list[$key]['through_gx']      = @$ret_info[$phone]['through_gx'];
                    $list[$key]['through_zz']      = @$ret_info[$phone]['through_zz'];
                    $list[$key]['through_gxs']     = @$ret_info[$phone]['through_gxs'];
                    $list[$key]['through_video']   = @$ret_info[$phone]['through_video'];
                    $list[$key]['through_lesson']  = @$ret_info[$phone]['through_lesson'];
                }else{
                    unset($list[$key]); 
                }
               
            }
        }
        //undefined 
	    $n = $num;
        $list_undefined=[];
        $list_undefined[] = [
            "channel_id"=>-1, //
            "channel_name"=>'未定义', //
            "up_group_name"=>"",
            "group_name"=>"",
            "account"=>"",
            "main_type_class"=>"campus_id-".$n,
            "up_group_name_class"=>"",
            "group_name_class"=>"",
            "account_class"=>"",
            "level"=>"l-1" //
        ];
        $list_undefined[] = [
            "channel_id"=>-1, //
            "channel_name"=>'未定义',//
            "up_group_name"=>'',
            "group_name"=>'未定义', //
            "account"=>"",

            "main_type_class"=>"campus_id-".$n,
            "up_group_name_class"=>"up_group_name-".++$num,
            "group_name_class"=>"",
            "account_class"=>"",

            "level"=>"l-2",
            "up_master_adminid"=>'',
            "group_id"=>-1, //
            "main_type"=>''
        ];
	    $m = $num;
        dd($ret_info);
        foreach ($ret_info as $key => $value) {
            if($value['channel_id'] == null){
                $arr = [
                    "channel_id"=>-1, //
                    "channel_name"=>'未定义',//
                    "group_name"=>'未定义', // admin_id
                    "group_id"=>-1,
                    "account"=>"",

                    "main_type_class"=>"campus_id-".$n,
                    "up_group_name_class"=>"up_group_name-".$m,
                    "group_name_class"=>"group_name-".++$num,
                    "account_class"=>"",

                    "admin_id"=>'',
                    "admin_name"=>'',
                    "level"=>"l-3",
                    "master_adminid"=>'',
                    "main_type"=>'',
                    "admin_phone" => @$value['phone'],
                ];
                $arr = array_merge($value,$arr);
                $list_undefined[] = $arr;

            }
        }
        $list_undefined = array_reverse($list_undefined);
        foreach ($list_undefined as $key => $value) {
            array_unshift($list,$value);
        }
        $total = [
                'app_num'         => 0,
                'video_add_num'   => 0,
                'lesson_add_num'  => 0,

                'through_all'     => 0,
                'through_jg'      => 0,

                'through_gx'      => 0,
                'through_zz'      => 0,
                'through_gxs'     => 0,
                'through_video'   => 0,
                'through_lesson'  => 0,
                "channel_id"=>-100, //
                "channel_name"=>"总计", //
                "up_group_name"=>"",
                "group_name"=>"",
                "account"=>"",
                "main_type_class"=>"campus_id-",
                "up_group_name_class"=>"",
                "group_name_class"=>"",
                "account_class"=>"",
                "level"=>"l-1" //
            ];

        foreach ($list as $key => $value) {
            # code...
            if($value['level'] == 'l-1'){
                $list[$key]['app_num']         = 0;
                $list[$key]['video_add_num']   = 0;
                $list[$key]['lesson_add_num']  = 0;

                $list[$key]['through_all']     = 0;
                $list[$key]['through_jg']      = 0;

                $list[$key]['through_gx']      = 0;
                $list[$key]['through_zz']      = 0;
                $list[$key]['through_gxs']     = 0;
                $list[$key]['through_video']   = 0;
                $list[$key]['through_lesson']  = 0;
                $now1 = $key;
            } 
            if($value['level'] == 'l-2'){
                $list[$key]['app_num']         = 0;
                $list[$key]['video_add_num']   = 0;
                $list[$key]['lesson_add_num']  = 0;

                $list[$key]['through_all']     = 0;
                $list[$key]['through_jg']      = 0;

                $list[$key]['through_gx']      = 0;
                $list[$key]['through_zz']      = 0;
                $list[$key]['through_gxs']     = 0;
                $list[$key]['through_video']   = 0;
                $list[$key]['through_lesson']  = 0;
                $now2 = $key;
            }
            if($value['level'] == 'l-3'){
                if(isset($value['app_num'])){
                    $total['app_num']       += $value['app_num'];
                    $list[$now1]['app_num'] += $value['app_num'];
                    $list[$now2]['app_num'] += $value['app_num'];
                }
                if(isset($value['video_add_num'])){
                    $total['video_add_num'] += $value['video_add_num'];
                    $list[$now1]['video_add_num'] += $value['video_add_num'];
                    $list[$now2]['video_add_num'] += $value['video_add_num'];
                }
                if(isset($value['lesson_add_num'])){
                    $total['lesson_add_num'] += $value['lesson_add_num'];
                    $list[$now1]['lesson_add_num'] += $value['lesson_add_num'];
                    $list[$now2]['lesson_add_num'] += $value['lesson_add_num'];
                }

                if(isset($value['through_all'])){
                    $total['through_all'] += $value['through_all'];
                    $list[$now1]['through_all'] += $value['through_all'];
                    $list[$now2]['through_all'] += $value['through_all'];
                }
                 if(isset($value['through_jg'])){
                    $total['through_jg'] += $value['through_jg'];
                    $list[$now1]['through_jg'] += $value['through_jg'];
                    $list[$now2]['through_jg'] += $value['through_jg'];
                }
                if(isset($value['through_gx'])){
                    $total['through_gx'] += $value['through_gx'];
                    $list[$now1]['through_gx'] += $value['through_gx'];
                    $list[$now2]['through_gx'] += $value['through_gx'];
                }
                if(isset($value['through_zz'])){
                    $total['through_zz'] += $value['through_zz'];
                    $list[$now1]['through_zz'] += $value['through_zz'];
                    $list[$now2]['through_zz'] += $value['through_zz'];
                }
                if(isset($value['through_gxs'])){
                    $total['through_gxs'] += $value['through_gxs'];
                    $list[$now1]['through_gxs'] += $value['through_gxs'];
                    $list[$now2]['through_gxs'] += $value['through_gxs'];
                }
                if(isset($value['through_video'])){
                    $total['through_video'] += $value['through_video'];
                    $list[$now1]['through_video'] += $value['through_video'];
                    $list[$now2]['through_video'] += $value['through_video'];
                }
                if(isset($value['through_lesson'])){
                    $total['through_lesson'] += $value['through_lesson'];
                    $list[$now1]['through_lesson'] += $value['through_lesson'];
                    $list[$now2]['through_lesson'] += $value['through_lesson'];
                }

            }
        }
        array_unshift($list,$total);
        //dd($list);
        return $this->pageView(__METHOD__,\App\Helper\Utils::list_to_page_info($list));
    }

    public function update_zs_id(){
        $teacherid = $this->get_in_int_val("teacherid");
        $zs_id     = $this->get_in_int_val("zs_id");
        $ret = $this->t_teacher_info->field_update_list($teacherid,[
                "zs_id" => $zs_id
            ]);
        return $this->output_succ();
    }
}
