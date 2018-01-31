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
        $edit_power_name       = trim($this->get_in_str_val('edit_power_name'));
        $edit_power_id         = $this->get_in_str_val('edit_power_id');
        $user_id               = $this->get_in_int_val('user_id');
        $is_copy_power         = $this->get_in_int_val('is_copy_power'); //是否复制其他权限组
        $copy_groupid          = $this->get_in_int_val('copy_groupid');  //所要复制权限组的id

        $data = [
            "group_name"  => $edit_power_name,
            "role_groupid" => $role_groupid,
        ];

        // if($is_copy_power){
        //     $authority = $this->t_authority_group->get_group_authority($copy_groupid);
        //     if($authority){
        //         $data['group_authority'] = $authority;
        //     }
        // }
        $edit = 0;
        if( $edit_type == 1){
            //添加权限组
            $data["create_time"] = time(NULL);
            $edit = $this->t_authority_group->row_insert($data);
            $edit_power_id = $this->t_authority_group->get_last_insertid();
        }else{
            //编辑权限组
            $edit = $this->t_authority_group->field_update_list($edit_power_id,$data);
        }

        $role_name = E\Eaccount_role::get_desc($role_groupid);
        if($edit > 0){
            $this->t_user_log->row_insert([
                "add_time" => time(),
                "adminid"  => $this->get_account_id(),
                "msg"      => "权限管理页面,权限名修改记录: [权限id:$edit_power_id,权限名:$edit_power_name,角色:$role_name]",
                "user_log_type" => 4, //权限页面添加用户记录
            ]);
        }

        if($user_id){
            $this->add_user_power($role_groupid,$edit_power_id,$user_id);
            $this->t_user_log->row_insert([
                "add_time" => time(),
                "adminid"  => $this->get_account_id(),
                "msg"      => "权限管理页面,添加用户修改记录: [用户id:$user_id,权限id:$edit_power_id,权限名:$edit_power_name,角色:$role_name]",
                "user_log_type" => 4, //权限页面添加用户记录
            ]);

        }

        return $this->output_succ();
    }

    public function add_user(){
        $user_id      = $this->get_in_int_val("user_id") ;
        $role_groupid  = $this->get_in_int_val("role_groupid") ;
        $groupid     = $this->get_in_int_val("groupid");
        $info = $this->add_user_power($role_groupid,$groupid,$user_id,0);

        $role_name = E\Eaccount_role::get_desc($role_groupid);
        if($info[0] == 1){
            $this->t_user_log->row_insert([
                "add_time" => time(),
                "adminid"  => $this->get_account_id(),
                "msg"      => "权限管理页面,添加用户记录: [用户id:$user_id,权限id:$groupid,角色:$role_name]",
                "user_log_type" => 4, //权限页面添加用户记录
            ]);

            return $this->output_succ();
        }else{
            return $this->output_err($info[1]);
        }

    }

    public function batch_add_user(){
        $uid_str      = $this->get_in_str_val("uid_str") ;
        $role_groupid  = $this->get_in_int_val("role_groupid") ;
        $groupid     = $this->get_in_int_val("groupid");
        $change_role     = $this->get_in_int_val("change_role",0);

        $role_name = E\Eaccount_role::get_desc($role_groupid);
        if(!empty($uid_str)){
            $uid_arr = explode(',',$uid_str);
            $all_num = count($uid_arr);
            $succ_num = 0;
            $fail_num = 0;
            foreach($uid_arr as $uid){
                $info = $this->add_user_power($role_groupid,$groupid,$uid,$change_role);
                if($info[0] == 1){
                    $succ_num += 1;
                }else{
                    $fail_num += 1;
                }
            }
            $info = "为该权限添加用户总数：".$all_num." 其中成功：".$succ_num."，失败：".$fail_num;

            $this->t_user_log->row_insert([
                "add_time" => time(),
                "adminid"  => $this->get_account_id(),
                "msg"      => "权限管理页面,添加用户记录: [用户id:$uid_str,角色:$role_name,权限:$groupid]".$info,
                "user_log_type" => 4, //权限页面添加用户记录
            ]);

            return $this->output_succ($info);

        }else{
            return $this->output_succ("请为权限添加用户");
        }
    }

    private function add_user_power($role_groupid,$groupid,$user_id,$change_role = 0){
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

        if( $change_role == 1 ){
            $this->t_manager_info->field_update_list($user_id,['account_role'=>$role_groupid,'permission'=>$have_permit]);
        }else{
            $this->t_manager_info->field_update_list($user_id,['permission'=>$have_permit]);
        }

        return [1];
    }

    //删除权限组
    public function dele_role_groupid(){
        $role_groupid  = $this->get_in_int_val('role_groupid');
        $groupid  = $this->get_in_int_val('groupid');
        $role_name = E\Eaccount_role::get_desc($role_groupid);
        $deleNum = $this->t_authority_group->dele_by_id($role_groupid, $groupid);
        if($deleNum){

            $this->t_user_log->row_insert([
                "add_time" => time(),
                "adminid"  => $this->get_account_id(),
                "msg"      => "权限管理页面,删除权限组id记录: [删除的权限组id:$groupid,角色:$role_name]",
                "user_log_type" => 4, //权限页面添加用户记录
            ]);

            return $this->output_succ();
        }else{
            return $this->output_err();
        }
    }

    //编辑权限
    public function set_group_power(){
        $role_groupid         = $this->get_in_int_val('role_groupid');
        $groupid              = $this->get_in_int_val('groupid');
        $power_list_str       = $this->get_in_str_val('power_list_str');
        $num = $this->t_authority_group->field_update_list($groupid,[
            "group_authority"  => $power_list_str,
        ]);

        $role_name = E\Eaccount_role::get_desc($role_groupid);
        if( $num > 0){            
            $this->t_user_log->row_insert([
                "add_time" => time(),
                "adminid"  => $this->get_account_id(),
                "msg"      => " 角色: $role_name, 权限id:$groupid, 权限管理页面,权限修改记录:$power_list_str",
                "user_log_type" =>  E\Euser_log_type::V_4, //权限页面修改记录
            ]);
        }
        return $this->output_succ();
    }

    //批量删除用户
    public function batch_dele_user(){
        $dele_uid_str      = $this->get_in_str_val("dele_uid_str") ;
        $role_groupid  = $this->get_in_int_val("role_groupid") ;
        $groupid     = $this->get_in_int_val("groupid");
        if(!empty($dele_uid_str)){
            $uid_arr = explode(',', $dele_uid_str);
            if(!is_array($uid_arr) || count($uid_arr) == 0 ){
                return $this->output_succ();
            }
            $succ_num_dele = 0;
            foreach($uid_arr as $uid){
                $this->dele_user_power($uid,$groupid);
            }

            //批量删除用户
            $this->t_user_log->row_insert([
                "add_time" => time(),
                "adminid"  => $this->get_account_id(),
                "msg"      => "批量删除用户权限, 用户id:$uid, 所要删除的权限组id:$groupid",
                "user_log_type" =>  E\Euser_log_type::V_4, //权限页面修改记录
            ]);

        }
        return $this->output_succ();
    }

    //删除用户
    public function dele_role_user(){
        $uid                  = $this->get_in_int_val("uid");
        $role_groupid  = $this->get_in_int_val('role_groupid');
        $groupid  = $this->get_in_int_val('groupid');

        $this->dele_user_power($uid,$groupid);

        $this->t_user_log->row_insert([
            "add_time" => time(),
            "adminid"  => $this->get_account_id(),
            "msg"      => "删除用户权限, 用户id:$uid, 所要删除的权限组id:$groupid",
            "user_log_type" =>  E\Euser_log_type::V_4, //权限页面修改记录
        ]);

        return $this->output_succ();
    }

    private function dele_user_power($uid,$groupid){
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

    }

    //获取所有后台管理员
    public function get_user_list(){
        $name_phone = $this->get_in_str_val('name_phone');
        $gender = $this->get_in_int_val('gender');
        $page_num  = $this->get_in_page_num();

        $ret_list = $this->t_manager_info->get_user_list_sec($gender,$name_phone,$page_num);
        if($ret_list['list']){
            foreach($ret_list['list'] as &$var){
                $var['account_role_str'] = E\Eaccount_role::get_desc($var['account_role']);
            }
        }
        //dd($ret_list);
        return $this->output_ajax_table($ret_list, [ "lru_list" => [] ]);
    }

    //用户管理获取该角色对应的权限
    public function get_permission_list(){
        $permission = $this->get_in_str_val('permission');
        $account_role = $this->get_in_str_val('account_role');

        //该角色下的权限
        $list    = $this->t_authority_group->get_groupid_by_role($account_role);

        //通用权限
        $common_list = $this->t_authority_group->get_groupid_by_role('1003');

        $list = array_merge($common_list,$list);

        $role_permit = array_column($list, 'groupid');

        if($list){
            $permission = trim($permission,",");

            //该用户拥有的权限
            $arr = explode(",",$permission);

            foreach( $list as &$item){
                $item["has_power"] = in_array($item['groupid'],$arr) ? 1 : 0;
                $item["account_role_str"] = E\Eaccount_role::get_desc($item["role_groupid"]);
                $item["forbid"] = 0;
            }

            //不属于该角色的权限组id
            $not_belog_role = array_diff($arr,$role_permit);

            if($not_belog_role && count($not_belog_role) > 0){

                $idstr= "";
                foreach($not_belog_role as $var){
                    if($var = ''){
                        $idstr .= $var.',';
                    }
                }
                if($idstr != ''){
                    $idstr = '('.substr($idstr,0,-1).')';

                    //dd($idstr);
                    $more_group = $this->t_authority_group->get_groups_by_id_str($idstr);
                    if($more_group){
                        foreach( $more_group as &$v){
                            $v["has_power"] = 1;
                            $v["account_role_str"] = E\Eaccount_role::get_desc($v["role_groupid"]);
                            $v["forbid"] = 1;
                        }
                    }
                    $list = array_merge($list,$more_group);

                }
            }
        }
        //dd($list);
        return $this->output_succ(["data"=> $list]);
    }

    // 备份当前权限
    public function power_back() {
        $this->t_power_back->back();
        return $this->output_succ();
    }

}