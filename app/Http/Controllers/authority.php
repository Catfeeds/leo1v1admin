<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

class authority extends Controller
{
    use CacheNick;


    public function get_login_list() {

        $account    = $this->get_in_int_val('account',-1);
        $flag       = $this->get_in_int_val('flag',-1 );
        $start_date = $this->get_in_str_val('start_date',date('Y-m-d', time(NULL)-3*86400 ));
        $end_date   = $this->get_in_str_val('end_date',date('Y-m-d', time(NULL)+86400 ));
        $login_info = $this->get_in_str_val("login_info","");//sql
        $page_num   = $this->get_in_page_num();

        $start_date_s = strtotime($start_date);
        $end_date_s   = strtotime($end_date)+86400;
        $ret_info     = $this->t_login_log->get_login_list($account,$flag,$start_date_s,$end_date_s,$login_info,$page_num);


        return $this->pageView(__METHOD__,$ret_info);

    }

    //搜索用户
    public function get_account_role()
    {
        $account   = $this->get_in_str_val('account','');
        $role      = $this->get_in_int_val('account_role',0);

        $ret_info  = $this->t_manager_info->get_sim_role($account);

        return outputJson(array('ret'=>0,'ret_info'=>$ret_info));
    }
    public function set_account_role()
    {
        $uid = $this->get_in_int_val('uid','');
        $account_role = $this->get_in_int_val('account_role',0);
        $creater_adminid = $this->get_in_int_val('creater_adminid',0);

        $this->t_manager_info->field_update_list($uid,[
            "account_role" => $account_role,
            "creater_adminid" => $creater_adminid,
        ]);

        return $this->output_succ();
    }

    public function manager_list()
    {
        $this->get_in_int_val("assign_groupid", -1);
        $this->get_in_int_val("assign_account_role",-1);

        $creater_adminid=$this->get_in_int_val("creater_adminid",-1);

        $adminid           = $this->get_in_adminid(-1);
        $uid               = $this->get_in_int_val('uid',0);
        $user_info         = trim($this->get_in_str_val('user_info',''));
        $has_question_user = $this->get_in_int_val('has_question_user',0);
        $del_flag          = $this->get_in_int_val('del_flag',0);
        $page_info         = $this->get_in_page_info();
        $account_role      = $this->get_in_int_val('account_role', -1);
        $cardid            = $this->get_in_int_val("cardid",-1);
        $day_new_user_flag = $this->get_in_boolean_val("day_new_user_flag",-1);
        $tquin             = $this->get_in_int_val("tquin", -1);
        $fulltime_teacher_type = $this->get_in_int_val("fulltime_teacher_type", -1);
        $seller_level      = $this->get_in_el_seller_level();
        if (!$cardid) {
            $cardid = -1;
        }

        $ret_info = $this->t_manager_info->get_all_manager( $page_info,$uid,$user_info,$has_question_user, $creater_adminid,$account_role,$del_flag,$cardid,$tquin,$day_new_user_flag,$seller_level,$adminid,$fulltime_teacher_type);
        $group_list=$this->t_authority_group->get_auth_groups();
        $group_map=[];
        foreach($group_list as $group_item) {
            $group_map[$group_item["groupid"]]=$group_item["group_name"];
        }
        foreach($ret_info['list'] as &$item){
            $item['old_permission'] = $item['permission'];
            $arr = explode(',',$item['permission']);
            $arr_zh_yi='';
            foreach($arr as $arr_eve){
                $int_eve = (int)$arr_eve;
                $arr_zh_yi .= @$group_map[$int_eve].",";
                //处理
            }
            $init_passwd=md5( md5($item["account"])."#aaron");
            $item["reset_passwd_flag"]=($init_passwd != $item["password"])?"是":"<font color=red>否</font>";
            $item['permission'] = $arr_zh_yi;
            $this->cache_set_item_account_nick($item,"creater_adminid", "creater_admin_nick");
            $this->cache_set_item_account_nick($item,"up_adminid", "up_admin_nick");
            E\Eaccount_role::set_item_value_str($item);
            E\Eseller_level::set_item_value_str($item);
            E\Edepartment::set_item_value_str($item);
            E\Eboolean::set_item_value_str($item,"become_full_member_flag");
            $item['del_flag_str'] = ($item['del_flag']==0)?'在职':'离职';
            if($item["seller_level_str"] == -1){
                $item["seller_level_str"] = "未设置";
            }
            E\Eboolean::set_item_value_simple_str($item,"day_new_user_flag");
        }
        return $this->pageView(__METHOD__,$ret_info);
    }


    public function manager_list_offline()
    {
        $this->get_in_int_val("assign_groupid", -1);
        $this->get_in_int_val("assign_account_role",-1);

        $creater_adminid=$this->get_in_int_val("creater_adminid",-1);

        $uid               = $this->get_in_int_val('uid',0);
        $user_info         = trim($this->get_in_str_val('user_info',''));
        $has_question_user = $this->get_in_int_val('has_question_user',0);
        $del_flag          = $this->get_in_int_val('del_flag',0);
        $page_info          = $this->get_in_page_info();
        $account_role      = $this->get_in_int_val('account_role', -1);
        $cardid            = $this->get_in_int_val("cardid",-1);
        $day_new_user_flag = $this->get_in_boolean_val("day_new_user_flag",-1);
        $tquin             = $this->get_in_int_val("tquin", -1);
        if (!$cardid) {
            $cardid = -1;
        }

        $ret_info = $this->t_manager_info->get_all_manager( $page_info,$uid,$user_info,$has_question_user, $creater_adminid,$account_role,$del_flag,$cardid,$tquin,$day_new_user_flag);
        $group_list=$this->t_authority_group->get_auth_groups();
        $group_map=[];
        foreach($group_list as $group_item) {
            $group_map[$group_item["groupid"]]=$group_item["group_name"];
        }

        foreach($ret_info['list'] as &$item){
            $arr = explode(',',$item['permission']);
            $arr_zh_yi='';
            foreach($arr as $arr_eve){
                $int_eve = (int)$arr_eve;
                $arr_zh_yi .= @$group_map[$int_eve].",";
                //处理
            }
            $init_passwd=md5( md5($item["account"])."#Aaron");
            $item["reset_passwd_flag"]=($init_passwd != $item["password"])?"是":"<font color=red>否</font>";
            $item['permission'] = $arr_zh_yi;
            $this->cache_set_item_account_nick($item,"creater_adminid", "creater_admin_nick");
            $this->cache_set_item_account_nick($item,"up_adminid", "up_admin_nick");
            E\Eaccount_role::set_item_value_str($item);
            E\Eseller_level::set_item_value_str($item);
            E\Eboolean::set_item_value_str($item,"become_full_member_flag");
            $item['del_flag_str'] = ($item['del_flag']==0)?'在职':'离职';
            if($item["seller_level_str"] == -1){
                $item["seller_level_str"] = "未设置";
            }
            E\Eboolean::set_item_value_simple_str($item,"day_new_user_flag");

        }

        return $this->pageView(__METHOD__,$ret_info);
    }


    public function get_permission_list(){
        $permission = $this->get_in_str_val('permission');
        $list    = $this->t_authority_group->get_all_list();
        $res = [];
        foreach($list as $val){
            $res[] = $val['group_name'];

        }
        if(!empty($permission)){
            $permission = trim($permission,",");
            $arr = explode(",",$permission);
            foreach ($arr as $k) {
                if( in_array($k,$res)){
                    foreach($list as &$item){
                        if($k == $item['group_name']){
                            $item["has_power"] = in_array($k,$res)?1:0;
                        }
                    }

                }

            }
        }
        return $this->output_succ(["data"=> $list]);
    }
    public function set_permission(){
        $uid = $this-> get_in_int_val('uid');
        $old_permission = $this-> get_in_str_val('old_permission');
        $groupid_list = \App\Helper\Utils::json_decode_as_int_array( $this->get_in_str_val("groupid_list"));
        $permission = implode(",",$groupid_list);
        $this->t_manager_info->field_update_list($uid,[
            "permission" => $permission ,
        ] );

        $adminid = session('adminid');
        $uid = $uid;
        $type = 1;
        $old = $old_permission;
        $new = $permission;
        $this->t_seller_edit_log->row_insert([
            "adminid"     => $adminid,
            "type"        => $type,
            "uid"         => $uid,
            "old"         => $old,
            "new"         => $new,
            "create_time" => time(NULL),
        ],false,false,true );

        return $this->output_succ();
    }

    public function del_manager() {
        $uid= $this->get_in_str_val("uid","");
        $del_flag= $this->get_in_int_val("del_flag","");
        $set_arr=[
            "del_flag" => $del_flag ,
        ];
        if ($del_flag) {
            $set_arr["wx_openid"]=NULL;
        }
        $this->t_manager_info->field_update_list($uid, $set_arr);
        $this->t_manager_info->sync_kaoqin_user($uid);

        return $this->output_succ();
    }

    public function add_user($account, $permission, $create_time)
    {
        $user_name = $this->get_in_str_val('user_name',"");
        if($user_name == ""){
            return outputJson(array('ret' =>-1,'info'=>'用户名为空'));
        }
        $ret_auth = $this->manage_model->check_permission($this->get_account(), GRP_MANAGE);
        if(!$ret_auth)
            return outputJson(array('ret' => NOT_AUTH, 'info' => $this->err_string[NOT_AUTH]));

        if($this->manage_model->is_auth_grp_exist($group_name))
            return outputJson(array('ret'  => -1, 'info' => '用户组'.$group_name."已经存在！"));

        $ret_info = $this->t_manager_info->add_user_name($user_name);
        return outputJson(array('ret'=>0,'info'=>'成功创建用户:'.$user_name));
    }

    public function get_show_manage_info()
    {
        $uid      = $this->get_in_int_val('uid',0);

        $ret_info = $this->t_manager_info->get_show_manage_info($uid);

        return outputJson(array('ret'=>0,'ret_info'=>$ret_info));
    }
    public function get_group_user_list() {
        $group_name=$this->get_in_str_val("group_name");

        $user_list=$this->t_authority_group->get_admin_list_by_group_name($group_name);

        return outputJson(array('ret' => 0, 'user_list' => $user_list));
    }
    public function get_group_user_list_ex() {
        $groupid = $this->get_in_int_val('groupid',1);

        $user_list = $this->t_admin_group->get_admin_group_by_group_id($groupid);
        foreach($user_list as &$item){
            $item['admin_nick']    = $item["account"] . '-'. $item ["name"];
        }

        return outputJson(array('ret' => 0, 'user_list' => $user_list));
    }

    public function edit_group()
    {
        $ret_info=$this->t_authority_group-> get_auth_groups();
        return $this->view(__METHOD__, [
            "grp_list"=> $ret_info ,

        "power_define_list"=> E\Epower::$desc_map ,
        ] );
    }

    public function jurisdiction()
    {
        $list=$this->t_authority_group-> get_auth_groups_new();

        foreach ($list as &$item ) {
            $power_list=explode(",", $item["group_authority"]);
            foreach ( $power_list as $p) {
                $item["l_$p"] = true;
            }

        }
        $ret_info=\App\Helper\Utils::list_to_page_info($list);

        return $this->pageView(__METHOD__,$ret_info,
                               ["power_define_list"=> E\Epower::$desc_map ,] );

    }

    public function member_group()
    {
        $groupid= $this->get_in_int_val('group_id',-1);
        $adminid= $this->get_in_int_val('admin_id',-1);
        $page_num  = $this->get_in_page_num();

        $ret_info = $this->t_admin_group->get_info_list($groupid,$adminid,$page_num);
        $number = 1;
        foreach ($ret_info['list'] as &$item ) {
            $item['number']     = $number;
            $item['groupid']    = E\Eaccount_role::get_desc($item['groupid']);
            $item['admin_nick']    = $this->cache_get_account_nick($item["adminid"]);
            $number++;
        }
        $adminid_str=$this->t_admin_group->get_adminid();

        return $this->pageView(__METHOD__,$ret_info, [
            "adminid_str" => $adminid_str,
        ]);
    }


    public function get_member_info()
    {
        $adminid= $this->get_in_int_val('adminid',-1);

        $ret_info = $this->t_admin_group->get_member_sim($adminid);

        return outputjson_success(array(
            'adminid'  => $ret_info['adminid'],
            'groupid' => $ret_info['groupid']
        ));

    }

    public function add_member_info()
    {
        $groupid= $this->get_in_int_val('groupid',-1);
        $adminid= $this->get_in_int_val('adminid',-1);

        $ret_info = $this->t_admin_group->add_member($groupid,$adminid);

        return $this->output_succ();
    }

    public function edit_member_info()
    {
        $groupid= $this->get_in_int_val('groupid',-1);
        $adminid= $this->get_in_int_val('adminid',-1);
        $old_adminid= $this->get_in_int_val('old_adminid',-1);

        $ret_info = $this->t_admin_group->edit_member($groupid,$adminid,$old_adminid);

        return $this->output_succ();

    }

    public function delete_member()
    {
        $adminid= $this->get_in_int_val('adminid',-1);

        $ret_info = $this->t_admin_group->delete_member_info($adminid);

        return $this->output_succ();

    }


    public function add_manager()
    {
        $account = $this->get_in_str_val('account',"");
        $passwd  = $this->get_in_str_val('passwd',"");
        $name    = $this->get_in_str_val('name',"");
        $email   = $this->get_in_str_val('email',"");
        $phone   = $this->get_in_str_val('phone',"");
        $groupid   = $this->get_in_int_val('groupid',0);
        $account_role = $this->get_in_int_val('account_role',0);


        if($account == "" || $passwd == "" || $name == ""){
            return $this->output_err("参数错误");
        }


        if($this->t_admin_users->get_id_by_account($account)) {
            return $this->output_err("用户已经存在 t_admin_users");
        }

        if($this->t_manager_info->get_id_by_account($account)) {
            return $this->output_err("用户已经存在 t_manager_info");
        }

        if($this->t_manager_info->get_id_by_phone($phone)) {
            return $this->output_err("电话已经存在:$phone ");
        }


        $passwd = md5(md5($passwd)."#Aaron");

        $adminid=$this->get_account_id();
        $this->t_admin_users->row_insert([
            "account" => $account,
            "password" => $passwd,
            "create_time" =>time(NULL) ,
        ]);
        $uid=$this->t_admin_users->get_last_insertid();
        $this->t_manager_info->row_insert([
            "uid" => $uid,
            "account" => $account,
            "name" => $name,
            "email" => $email,
            "phone" => $phone,
            "create_time" => time(NULL) ,
            "permission" => $groupid ,
            "account_role" => $account_role,
            "creater_adminid" => $adminid ,
            "day_new_user_flag" => 1,
        ]);

        return $this->output_succ();

    }
    public function manager_list_for_seller() {
        $this->set_in_value("account_role", E\Eaccount_role::V_2);
        $this->set_in_value("assign_account_role",E\Eaccount_role::V_2);
        //$this->set_in_value("creater_adminid",$this->get_account_id() );
        return $this->manager_list( );


    }

    public function manager_list_for_kaoqin() {
        return $this->manager_list( );
    }

    public function manager_list_for_qz() {
        $this->set_in_value("account_role", E\Eaccount_role::V_5);
        $this->set_in_value("assign_account_role",E\Eaccount_role::V_5);
        //$this->set_in_value("creater_adminid",$this->get_account_id() );
        return $this->manager_list( );

    }

    public function manager_list_for_qz_shanghai(){
        $this->set_in_value("fulltime_teacher_type",1);
        return $this->manager_list_for_qz();
    }

    public function manager_list_for_qz_wuhan(){
        $this->set_in_value("fulltime_teacher_type",2);
        return $this->manager_list_for_qz();
    }




    public function manager_list_for_ass() {
        $this->set_in_value("account_role", E\Eaccount_role::V_1);
        $this->set_in_value("assign_account_role",E\Eaccount_role::V_1);
        return $this->manager_list( );

    }


    public function account_menu_list() {
        $uid=$this->get_in_int_val("uid");

    }

    public function set_fulltime_teacher_type(){
        $uid   = $this->get_in_int_val('uid');
        $fulltime_teacher_type   = $this->get_in_int_val('fulltime_teacher_type');
        $this->t_manager_info->field_update_list($uid,[
            "fulltime_teacher_type"=>$fulltime_teacher_type
        ]);
        return $this->output_succ();
    }

    public function seller_edit_log_list(){
        $uid_new = $this->get_in_int_val('uid');
        $list = $this->t_seller_edit_log->get_all_list($uid_new);
        $group_list=$this->t_authority_group->get_auth_groups();
        $group_map=[];
        foreach($group_list as $group_item) {
            $group_map[$group_item["groupid"]]=$group_item["group_name"];
        }
        foreach ($list as &$item ){
            if($item['type'] == 1){
                $item['type'] = '修改权限组';
                $arr          = explode(',',$item['old']);
                $arr_zh_yi    = '';
                foreach($arr as $arr_eve){
                    $int_eve    = (int)$arr_eve;
                    $arr_zh_yi .= @$group_map[$int_eve].",";
                }
                $arr_new       = explode(',',$item['new']);
                $arr_zh_yi_new = '';
                foreach($arr_new as $arr_eve_new){
                    $int_eve_new    = (int)$arr_eve_new;
                    $arr_zh_yi_new .= @$group_map[$int_eve_new].",";
                }
                $item['old'] = $arr_zh_yi;
                $item['new'] = $arr_zh_yi_new;
            }elseif($item['type'] == 2){
                $item['type'] = '修改咨询师等级';
                $item['seller_level'] = $item['old'];
                E\Eseller_level::set_item_value_str($item);
                $item['old'] = $item['seller_level_str'];
                $item['seller_level'] = $item['new'];
                E\Eseller_level::set_item_value_str($item);
                $item['new'] = $item['seller_level_str'];

            }
            $this->cache_set_item_account_nick($item,"adminid", "adminid_nick");
            $this->cache_set_item_account_nick($item,"uid", "uid_nick");
            $item['create_time'] = date('Y-m-d H:i:s',$item['create_time']);
        }
        $ret_info=\App\Helper\Utils::list_to_page_info($list);
        return $this->pageView(__METHOD__,$ret_info);
    }

}
