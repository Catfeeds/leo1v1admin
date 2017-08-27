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
        //dd($ret_info);
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
        //dd($ret_info);
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
//dd($ret_info);
//$ret_info[12515215151]['']
        foreach ($list as $key => $value) {

            if(isset($value['admin_phone'])){
                //print_r($value['admin_phone']);
                $phone = intval($value['admin_phone']);
                $phone = 12515215151;
                $list[$key]['app_num']         = $ret_info[$phone]['app_num'];
                $list[$key]['video_add_num']   = $ret_info[$phone]['video_add_num'];
                $list[$key]['lesson_add_num']  = $ret_info[$phone]['lesson_add_num'];

                $list[$key]['through_all']     = $ret_info[$phone]['through_all'];
                $list[$key]['through_jg']      = $ret_info[$phone]['through_jg'];

                $list[$key]['through_gx']      = $ret_info[$phone]['through_gx'];
                $list[$key]['through_zz']      = $ret_info[$phone]['through_zz'];
                $list[$key]['through_gxs']     = $ret_info[$phone]['through_gxs'];
                $list[$key]['through_video']   = $ret_info[$phone]['through_video'];
                $list[$key]['through_lesson']  = $ret_info[$phone]['through_lesson'];

                if($list[$key]['through_all']  > 0){
                    $list[$key]['through_all_per'] = round((100*$list[$key]['through_all']/$list[$key]['app_num'] ),2);
                    $list[$key]['through_all_per'] .= '%';
                }else{
                    $list[$key]['through_all_per'] = '0%';
                }
                if($list[$key]['through_lesson']  > 0){
                    $list[$key]['through_lesson_per'] = round((100*$list[$key]['through_lesson']/$list[$key]['lesson_add_num']),2);
                    $list[$key]['through_lesson_per'] .= '%';
                }else{
                    $list[$key]['through_lesson_per'] = '0%';
                }

                if($list[$key]['through_video']  > 0){
                    $list[$key]['through_video_per'] = round((100*$list[$key]['through_video']/$list[$key]['video_add_num']),2);
                    $list[$key]['through_video_per'] .= '%';
                }else{
                    $list[$key]['through_video_per'] = '0%';
                }
            }
        }

        //dd($list);
        return $this->pageView(__METHOD__,\App\Helper\Utils::list_to_page_info($list));

      
    }
}
