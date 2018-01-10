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
        $role_groupid  = $this->get_in_int_val('role_groupid');
        $edit_type  = $this->get_in_int_val('edit_type');
        $exits_power       = $this->get_in_str_val('exits_power');
        $edit_power_name       = $this->get_in_str_val('edit_power_name');
        $edit_power_id  = $this->get_in_str_val('edit_power_id');
        if( $edit_type == 1){
            //添加权限组

        }else{
            //编辑权限组

        }

        //该角色对应的权限组id
        $group_exist = $this->t_authority_group->get_groupid_by_role($role_groupid);
        $group_exist_id = array_column($group_exist,'groupid');
        $choose_groupid = explode(',', $exits_power);


    }

    public function add_user(){
        
    }

    public function dele_role_groupid(){
        
    }
}