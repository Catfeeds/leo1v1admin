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
                "role_groupid"  => $role_groupid
            ]);
 
        }

        if($user_id){
            $this->add_user_power($role_groupid,$edit_power_id,$user_id);
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
        $groupid     = $this->get_in_int_val("groupid");
        
        $info = $this->add_user_power($role_groupid,$groupid,$user_id);
        if($info[0] == 1){
            return $this->output_succ();
        }else{
            return $this->output_err($info[1]);
        }

    }

    private function add_user_power($role_groupid,$groupid,$user_id){
        $have_permit = $this->t_manager_info->get_permission($user_id);
        $have_role = $this->t_manager_info->get_account_role($user_id);

        if( $have_role == $role_groupid && $have_permit){
            $group_arr = explode(',',$have_permit);
            if(in_array($groupid,$group_arr)){
                return [0,"该用户存在该权限,所以不能添加！"];
            }

            $have_permit .= ','.$groupid;
        }else{
            if($have_permit){
                $have_permit .= ','.$groupid;
            }else{
                $have_permit = $groupid;
            }
        }
        $this->t_manager_info->field_update_list($user_id,['account_role'=>$role_groupid,'permission'=>$have_permit]);

        /**
         * @ 产品部加 数据更改日志
         */
        $this->t_user_log->row_insert([
            "add_time" => time(),
            "adminid"  => $this->get_account_id(),
            "msg"      => "权限管理页面,添加用户修改记录: [用户id:$user_id,角色:$role_groupid]",
            "user_log_type" => 4, //权限页面添加用户记录
        ]);

        return [1];
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
        $role_groupid  = $this->get_in_int_val('role_groupid');
        $groupid  = $this->get_in_int_val('groupid');

        $have_permit = $this->t_manager_info->get_permission($uid);
        $have_role = $this->t_manager_info->get_account_role($uid);
        //echo $have_permit;
        if($have_permit){
            $group_arr = explode(',',$have_permit);
            $new_permit = '';
            foreach($group_arr as $k => $v){
                if( $v != $groupid ){
                    $new_permit .= $v.',';
                }
            }
            $new_permit = substr($new_permit, 0,-1);
            $this->t_manager_info->field_update_list($uid,['permission'=>$new_permit]);
        }

        return $this->output_succ();
    }

    //用户管理获取该角色对应的权限
    public function get_permission_list(){
        $permission = $this->get_in_str_val('permission');
        $account_role = $this->get_in_str_val('account_role');
        $list    = $this->t_authority_group->get_groupid_by_role($account_role);
        if($list && !empty($permission)){
            $permission = trim($permission,",");

            //该用户拥有的权限
            $arr = explode(",",$permission);

            //该角色的所有权限组id
            $role_permit = array_column($list,'groupid');

            foreach( $list as &$item){
                $item["has_power"] = 0;
                if( in_array($item['groupid'],$arr)){
                    $item["has_power"] = 1;
                }
            }

            //不属于该角色的权限组id
            $not_belog_role = array_diff($arr,$role_permit);

            if($not_belog_role){
                $idstr= "(";
                foreach($not_belog_role as $var){
                    $idstr .= $var.',';
                }
                $idstr = substr($idstr,0,-1).')';
                //dd($idstr);
                $more_group = $this->t_authority_group->get_groups_by_id_str($idstr);
                if($more_group){
                    foreach($more_group as &$v){
                        $v["has_power"] = 1;
                    }
                }
                $list = array_merge($list,$more_group);
            }
        }
     
        return $this->output_succ(["data"=> $list]);
    }

}