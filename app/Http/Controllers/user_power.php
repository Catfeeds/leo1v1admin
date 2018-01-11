<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;


class user_power extends Controller
{

    public function get_desc_power()
	{
		$url       = $this->get_in_str_val('url');
        $group_id  = $this->get_in_int_val('group_id',"");
        $desc_class = $this->get_class_function($url);
        
        if(!$desc_class){
            return $this->output_succ();
        }

        //dd($desc_class);
        //查找是否存在该类
        $desc_class="\\App\\Config\\url_desc_power\\".$desc_class[0]."\\".$desc_class[1];

        if(!class_exists($desc_class)){
            return $this->output_succ();
        };
        $desc_power = $desc_class::get_config();
        //dd($desc_power);
        $select_power = [];
        if($group_id){        
            $select_power = $this->t_url_desc_power->url_desc_power_list($group_id,$url);
            $result = [];

            if($select_power){
                $result = array_column($select_power,"open_flag","opt_key");
                foreach( $desc_power as &$item){
                    if(  array_key_exists($item['field_name'],$result) ){
                        $item['field_value'] = $result[$item['field_name']];
                    }
                }
            }
  
        }
        return $this->output_succ(["data"=> $desc_power]);
	}

    //查看该类和方法是否存
    private function get_class_function($url){
        if(!$url){
            return false;
        }
        $strpos = strripos($url,"/");
        if(!$strpos){
            return false;
        }
        $controller_name = substr($url,1,$strpos - 1);
        if(!$controller_name){
            return false;
        }
        
        $func_name = substr($url, $strpos + 1);
        if(!$func_name){
            return false;
        }
        return [$controller_name,$func_name];
    }

    public function save_desc_power(){
        $url       = $this->get_in_str_val('url');
        $group_id  = $this->get_in_int_val('group_id');
        $opt_key_list  = $this->get_in_str_val('opt_key_list');
        $all_list  = $this->get_in_str_val('all_list');

        if(!$url || !$group_id ){
            return $this->output_succ();
        }
        $opt_key_arr = json_decode($opt_key_list,true);
        $all_arr = json_decode($all_list,true);
        $forbid_arr = array_diff($all_arr,$opt_key_arr);
        $item = 0;
        $data = [
            "url" => $url,
            "role_groupid" => $group_id,
            "open_flag" => 1
        ];
        if($opt_key_arr){
            foreach($opt_key_arr as $opt_key){
                $data["opt_key"] = $opt_key;
                $this->save_edit_desc_power($data);
            }
        }
  
        if($forbid_arr){
            $data['open_flag'] = 0;
            foreach($forbid_arr as $opt_key){
                $data["opt_key"] = $opt_key;
                $this->save_edit_desc_power($data);
            }
        }
        return $this->output_succ();
    }

    private function save_edit_desc_power($data){
        $url = $data['url'];
        $role_groupid = $data['role_groupid'];
        $opt_key = $data['opt_key'];

        //查看是否存在
        $url_desc_power_id = $this->t_url_desc_power->url_desc_power_id($url,$role_groupid,$opt_key);
        if($url_desc_power_id){
            //更新
            $ret = $this->t_url_desc_power->field_update_list($url_desc_power_id,$data); 
        }else{
            //添加
            $ret = $this->t_url_desc_power->row_insert($data);
        }
    }

    public function get_input_define()
	{
		$url       = $this->get_in_str_val('url');
        $group_id  = $this->get_in_int_val('group_id');
        $desc_class = $this->get_class_function($url);
        
        if(!$desc_class){
            return $this->output_succ();
        }

        //dd($desc_class);
        //查找是否存在该类
        $desc_class="\\App\\Config\\url_desc_power\\".$desc_class[0]."\\".$desc_class[1];

        if(!class_exists($desc_class)){
            return $this->output_succ();
        };

        $desc_power = $desc_class::get_input_value_config();
        //dd($desc_power2);
        $select_power = [];
        if($group_id){        
            $select_power = $this->t_url_input_define->url_input_define_list($group_id,$url);
            $result = [];
            if($select_power){
                $result = array_column($select_power,"field_val","field_name");
                foreach( $desc_power as &$item){
                    if( array_key_exists($item['field_name'],$result) ){
                        $item['field_val'] = $result[$item['field_name']];
                    }           
                }          
            }
            
        }
        return $this->output_succ(["data"=> $desc_power,'status'=>200]);
	}

    public function save_input_define(){
        $url       = $this->get_in_str_val('url');
        $group_id  = $this->get_in_int_val('group_id');
        $save_data  = $this->get_in_str_val('save_data');
        //dd($save_data);
        if(!$url || !$group_id ){
            return $this->output_succ();
        }
        $item = 0;
        $data = [
            "url" => $url,
            "role_groupid" => $group_id,
        ];
        //dd($save_data);
        if($save_data){
            foreach($save_data as $key => $val){
                $data["field_name"] = $key;
                $data["field_val"] = $val[0];
                $data["field_type"] = $val[1];
                $this->save_edit_input_define($data);
            }
        }
        return $this->output_succ();
    }

    private function save_edit_input_define($data){
        $url = $data['url'];
        $role_groupid = $data['role_groupid'];
        $field_name = $data['field_name'];
        
        //查看是否存在
        $url_input_define_id = $this->t_url_input_define->url_input_define_id($url,$role_groupid,$field_name);
        if($url_input_define_id){
            //更新
            $ret = $this->t_url_input_define->field_update_list($url_input_define_id,$data); 
        }else{
            //添加
            $ret = $this->t_url_input_define->row_insert($data);
        }
    }

    public function edit_role_groupid(){
        $role_groupid          = $this->get_in_int_val('role_groupid');
        $edit_type             = $this->get_in_int_val('edit_type');
        $edit_power_name       = $this->get_in_str_val('edit_power_name');
        $edit_power_id         = $this->get_in_str_val('edit_power_id');
        $user_id               = $this->get_in_int_val('user_id');

        if( $edit_type == 1){
            //添加权限组
            $this->t_authority_group->row_insert([
                "group_name"  => $edit_power_name,
                "role_groupid" => $role_groupid,
                "create_time"  => time(NULL)
            ]);
            $edit_power_id = $this->t_authority_group->get_last_insertid();
        }else{
            //编辑权限组
            $this->t_authority_group->field_update_list($edit_power_id,[
                "group_name"  => $edit_power_name,
            ]);
 
        }

        if($user_id){
            //添加用户
            $this->t_manager_info->field_update_list($user_id,['role_groupid'=>$role_groupid]);
            /**
             * @ 产品部加 数据更改日志
             */
            $this->t_user_log->row_insert([
                "add_time" => time(),
                "adminid"  => $this->get_account_id(),
                "msg"      => "权限管理页面,添加用户修改记录: [用户id:$user_id,角色:$role_groupid]",
                "user_log_type" => 4, //权限页面添加用户记录
            ]);

        }

        return $this->output_succ();
    }

    public function add_user(){
        $user_id      = $this->get_in_int_val("user_id") ;
        $role_groupid  = $this->get_in_int_val("role_groupid") ;

        $this->t_manager_info->field_update_list($user_id,['role_groupid'=>$role_groupid]);

        /**
         * @ 产品部加 数据更改日志
         */
        $this->t_user_log->row_insert([
            "add_time" => time(),
            "adminid"  => $this->get_account_id(),
            "msg"      => "权限管理页面,添加用户修改记录: [用户id:$user_id,角色:$role_groupid]",
            "user_log_type" => 4, //权限页面添加用户记录
        ]);

        return $this->output_succ();
    }
    //删除权限组
    public function dele_role_groupid(){
        $role_groupid  = $this->get_in_int_val('role_groupid');
        $groupid  = $this->get_in_int_val('groupid');
        $deleNum = $this->t_authority_group->dele_by_id($role_groupid, $groupid);
        if($deleNum){
            return $this->output_succ();
        }else{
            return $this->output_err();
        }
    }

    public function set_group_power(){
        $role_groupid         = $this->get_in_int_val('role_groupid');
        $groupid              = $this->get_in_int_val('groupid');
        $power_list_str       = $this->get_in_str_val('power_list_str');
        $this->t_authority_group->field_update_list($groupid,[
            "group_authority"  => $power_list_str,
        ]);
        return $this->output_succ();
    }

    public function dele_role_user(){
        $uid                  = $this->get_in_int_val("uid");
        $this->t_manager_info->field_update_list($uid,['role_groupid'=>0]);
        return $this->output_succ();
    }
}