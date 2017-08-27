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
                    "group_name"=>$item['group_name'], //
                    "account"=>"",

                    "main_type_class"=>"campus_id-".$n,
                    "up_group_name_class"=>"up_group_name-".++$num,
                    "group_name_class"=>"",
                    "account_class"=>"",

                    "level"=>"l-2",
                    "up_master_adminid"=>'',
                    "group_id"=>$item["group_id"], //
                    "main_type"=>''
                    ];

                $admin_list = $this->t_admin_channel_user->get_user_list_new($item["group_id"]);

                $m = $num;
                foreach($admin_list as $val){
                    $list[] = [
                        "channel_id"=>$channel_id, //
                        "channel_name"=>$channel_name,//
                        "group_name"=>$item['group_name'], // admin_id
                        "group_id"=>$item["group_id"],
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
        //dd($channel_id);
        $ret = $this->t_admin_channel_group->get_group($group_id);
        if($ret[0]['channel_id'] == 0){
            $ret = $this->t_admin_channel_group->field_update_list($ret[0]['id'],[
                "channel_id"  =>$channel_id 
            ]);
        }else{
            $ret = $this->t_admin_channel_group->field_update_list($ret[0]['id'],[
                "channel_id"  =>$channel_id 
            ]);
        }
        
        return $this->output_succ();
    }

    public function get_teacher_type_ref(){
        $page_num     = $this->get_in_page_num();
        $ret_info   = $this->t_admin_channel_group->get_all_group_id($page_num);
        if($ret_info['list'] != []){
            $ret_info["page_info"] = $this->get_page_info_for_js( $ret_info["page_info"]   );
            return outputjson_success(array('data' => $ret_info));
        }else{
            $arr = E\Eteacher_ref_type::$desc_map;  
            foreach ($arr as $key => $value) {
                $ret = $this->t_admin_channel_group->row_insert([
                    'group_id'  => $key,
                    'group_name' => $value,
                ]);
            }
            $ret_info["page_info"] = $this->get_page_info_for_js( $ret_info["page_info"]   );
            return outputjson_success(array('data' => $ret_info));
        }
        
        //dd(2);
        
        
        
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
}