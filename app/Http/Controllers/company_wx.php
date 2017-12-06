<?php

namespace App\Http\Controllers;
use \App\Http\Controllers\Controller;
use \App\Enums as E;
use \App\Helper\Config as Config;

use Illuminate\Support\Facades\Redis;
class company_wx extends Controller
{
    public function test_redis() { // 测试redis
        $redis = Redis::connection('cache_nick');
        // for($i = 0; $i < 100; $i ++) {
        //     $redis->lPush('flush_company_wx_data', date('Y-m-d H:i:s',time()).' 加载权限结束'.$i);
        // }
        // var_dump($redis -> lPush('favorite_fruit','cherry'));
        // var_dump($redis -> lPush('favorite_fruit','banana'));
        // $len = $redis -> lLen('flush_company_wx_data');
        // $log = '';
        // for ($i = 1; $i <= 10; $i ++) {
        //     $log[] = $redis -> rPop('flush_company_wx_data');
        // }
        // dd($log);
        // var_dump($redis -> lLen('flush_company_wx_data'));
    }

    public function get_company_all_user() {
        $config = Config::get_config("company_wx");
        if (!$config) {
            exit('没有配置');
        }

        $info = $this->t_company_wx_department->get_all_list();
        $depart_info = array_column($info, 'id');
        $users = $this->t_company_wx_users->get_all_list();
        $peo = [];
        foreach($users as $item) {
            $peo[] = $item['pId'].$item['userid'];
        }
        $tag_department = $this->t_company_wx_tag_department->get_all_list();
        $tag_depart = [];
        foreach($tag_department as $item) {
            $tag_depart[$item['id']][] = $item['department'];
        }

        // 获取token
        $url = $config['url'].'/cgi-bin/gettoken?corpid='.$config['CorpID'].'&corpsecret='.$config['Secret'];
        $token = $this->get_company_wx_data($url, 'access_token'); // 获取tocken

        // 获取标签
        $url = $config['url'].'/cgi-bin/tag/list?access_token='.$token;
        $tag = $this->get_company_wx_data($url,'taglist');
        if ($tag) {
            foreach($tag as $item) {
                $info = $this->t_company_wx_tag->get_name($item['tagid']);
                if (!$info) {
                    $id = $this->t_company_wx_tag->row_insert([
                        "id" => $item['tagid'],
                        'name' => $item['tagname'],
                    ]);
                }
                // 获取标签部门
                $url = $config['url'].'/cgi-bin/tag/get?access_token='.$token.'&tagid='.$item['tagid'];
                $info = file_get_contents($url);
                $info = json_decode($info, true);

                $users = $this->get_company_wx_data($url,"partylist");
                foreach($users as $val) {
                    if (!(isset($tag_depart[$item['tagid']]) && in_array($val, $tag_depart[$item['tagid']]))) { 
                        $this->t_company_wx_tag_department->row_insert([
                            "id" => $item['tagid'],
                            'department' => $val
                        ]);
                    }
                }

            }
            echo '加载标签完成';
        }

        // 获取部门
        $url = $config['url'].'/cgi-bin/department/list?access_token='.$token;
        $department = $this->get_company_wx_data($url, 'department');
        $users = '';
        if ($department) {
            foreach ($department as $val) {
                $department = $val['id'];
                // 获取部门下的用户
                $url = $config['url'].'/cgi-bin/user/list?access_token='.$token.'&department_id='.$department.'&fetch_child=0';
                $users = $this->get_company_wx_data($url, 'userlist');
                if ($users) {
                    foreach($users as $item) {
                        $depart = array_flip($item['department']);
                        if (in_array($department.$item['userid'], $peo)) continue; //判断当前用户是否已存在
                        $this->t_company_wx_users->row_insert([
                            "userid" => $item['userid'],
                            "name" => $item['name'],
                            "department" => $department,
                            "position" => $item['position'],
                            "mobile" => $item['mobile'],
                            "gender" => $item['gender'],
                            "email" => $item["email"],
                            "avatar" => $item['avatar'],
                            "isleader" => $item['isleader'],
                            "english_name" => $item["english_name"],
                            "telephone" => $item["telephone"],
                            "`order`" => $item['order'][$depart[$department]]]);
                    }
                }
                if (in_array($department, $depart_info)) continue;
                $this->t_company_wx_department->row_insert([
                    "id" => $val['id'],
                    "name" => $val['name'],
                    "parentid" => $val['parentid'],
                    "`order`" => $val['order']
                ]);
            }
            echo '加载部门成功';
        }
    }

    public function get_approve() { // 获取审批数据
        $config = Config::get_config("company_wx");
        if (!$config) {
            exit('没有配置');
        }

        // list($start_time, $end_time) = $this->get_in_date_range_day(0);
        // $start_time = $this->get_in_str_val('start_time');
        // $end_time = $this->get_in_str_val('end_time');
        // 获取token
        $url = $config['url'].'/cgi-bin/gettoken?corpid='.$config['CorpID'].'&corpsecret='.$config['Secret2'];
        $token = $this->get_company_wx_data($url, 'access_token'); // 获取tocken

        $start_time = strtotime('2017-11-29');
        $end_time = strtotime('2017-12-1');
        // 获取审批数据
        $url = $config['url'].'/cgi-bin/corp/getapprovaldata?access_token='.$token;
        $post_data = json_encode(["starttime" => $start_time,"endtime" => $end_time]);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        $output = curl_exec($ch);
        curl_close($ch);
        $output = json_decode($output, true);

        dd($output);
        $info = $output['data'];
        // foreach($info as $item) {
        //     $approval_name = implode(',', $item['approval_name']);
        //     $notify_name = implode(',', $item['notify_name']);
        //     // 添加数据
        //     $this->t_table_name->row_insert([
        //         'spname' => $item['spane'],
        //         'apply_name' => $item['apply_name'],
        //         'apply_org' => $item['apply_org'],
        //         'approval_name' => $approval_name,
        //         'notify_name' => $notify_name,
        //         'sp_status' => $item['sp_status'],
        //         'sp_num' => $item['sp_num'],
        //         'title' => $item['title'],
        //"apply_time" => $item['apply_time'],
        //          "apply_user_id" => $item['apply_user_id']
        //     ]);
        // }
    }

    public function get_company_wx_data($url, $index='') { //根据不同路由获取不同的数据 (企业微信)
        $info = file_get_contents($url);
        $info = json_decode($info, true);
        if ($index && isset($info[$index])) {
            return $info[$index];
        }
        return $info;
    }

    public function show_department_users() {
        $id = $this->get_in_int_val("id", 0);
        $type = $this->get_in_int_val('type', 0);
        $info = $this->t_company_wx_department->get_all_list();
        $info[0]['open'] = true;
        $info[1]['open'] = true;
        $users = $this->t_company_wx_users->get_all_list();
        $ext['id'] = $id;
        $ext['type'] = $type;
        $tag = $this->t_company_wx_tag->get_all_department();
        foreach($tag as $key => $item) {
            $tag[$key]['department'] = explode(',', $item['department']);
        }
        foreach($info as $key=>$item) {
            foreach($tag as $val) {
                if (in_array($item['id'], $val['department'])) {
                    $info[$key]['name'] = $item['name'].'('.$val['name'].')';
                }
            }
        }
        // $len = count($info);
        // foreach($info as $key=>$item) {
        //     foreach($tag as $val) {
        //         if (in_array($item['id'], $val['department'])) {
        //             $pid = $val['id'] + $len;
        //             $info[$len]['id'] = $pid;
        //             $info[$len]['pId'] = $item['pId'];
        //             $info[$len]['name'] = $val['name'];
        //             $info[$key]['pId'] = $pid;
        //             $len++;
        //         }
        //     }
        // }

        //$role = $this->t_company_wx_role->get_all_for_auth(); // 获取当前权限组的所有成员
        foreach($users as $key => $item) {
            $power = '';
            if ($item['power']) $power = '('.$item['power'].')';
            $name = $item['position'].'-'.$item['name'].$power;
            if ($item['isleader'] == 1) {
                $name .= '(领导)';
            }
            $users[$key]['name'] = $name;
            
            // if ( $item['id'] < 600) {
            //     $users[$key]['checked'] = true;
            //     $users[$key]['open'] = true;
            // }
        }

        // if ($type == 1) { // 部门授权 
        // } elseif ($type == 2) { // 职们授权
        //     $people = [];
        //     foreach($users as $item) {
        //         $people[$item['position'].$item['pId']]['id'] = $item['id'];
        //         $people[$item['position'].$item['pId']]['name'] = $item['position'];
        //         $people[$item['position'].$item['pId']]['pId'] = $item['pId'];
        //     }
        //     $i = 0;
        //     foreach($people as $key => $item) {
        //         $people[$i] = $item;
        //         unset($people[$key]);
        //         $i ++;
        //     }
        //     $info = array_merge($info, $people);
        // } else {
        //    $info = array_merge($info,$users);
        // }
        $info = array_merge($info,$users);
        //$info = $this->genTree($info, 0);
        return $this->pageView(__METHOD__, '', [
            'info' => $info,
            'ext' => $ext
        ]);
    }

    public function all_users() {
        E\Eseller_student_status::V_100;
        $tag = $this->t_company_wx_tag->get_all_list();
        $list    = $this->t_authority_group->get_all_list();
        $group = [];
        foreach($list as $item) {
            $group[$item['groupid']] = $item['group_name'];
        }

        foreach($tag as &$item) {
            if ($item['leader_power']) {
                $power = explode(',', $item['leader_power']);
                $power_s = '';
                foreach($power as $val) {
                    $power_s .= $val.'-'.$group[$val];
                }
                $item['leader_power'] = $power_s;
            }
            // no_leader_power
            if ($item['no_leader_power']) {
                $power = explode(',', $item['no_leader_power']);
                $power_s = '';
                foreach($power as $val) {
                    $power_s .= $val.'-'.$group[$val];
                }
                $item['no_leader_power'] = $power_s;
            }
        }
        
        return $this->pageView(__METHOD__, '', [
            'info' => $tag,
            'group' => $group
        ]);
    }

    public function role_list() {
        $info = $this->t_company_wx_role->get_all_list();
        return $this->pageView(__METHOD__,null,[
            'info' => $info
        ]);
    }

    public function add_role_data() {
        $id = $this->get_in_int_val("id");
        $name = $this->get_in_str_val("name");
        $userid = $this->get_in_str_val('userid');
        if ($id) {
            $this->t_company_wx_role->field_update_list($id,[
                'u_id' => $userid
            ]);
            return $this->output_succ();
        }
        $this->t_company_wx_role->row_insert([
            'name' => $name
        ]);
        return $this->output_succ();
    }

    public function update_role_data() {
        $id = $this->get_in_int_val("id");
        $type = $this->get_in_int_val("type",-1);
        if ($type == 0) {
            $this->t_company_wx_role->field_update_list($id,[
                'u_id' => $userid
            ]);
            return $this->output_succ();
        } elseif ($type ==  1) {
            //
        } elseif ($type == 2) {
            //
        } else {
            $name = $this->get_in_str_val("name");
            $this->t_company_wx_role->field_update_list($id,[
                'name' => $name
            ]);
            return $this->output_succ();
        }
    }

    public function set_permission() {
        //$userid = $this->get_in_str_val("userid",0);
        $id = $this->get_in_int_val("id");
        $status = $this->get_in_int_val("status");
        $groupid_list = \App\Helper\Utils::json_decode_as_int_array( $this->get_in_str_val("groupid_list"));
        $permission = implode(",",$groupid_list);
        // $this->t_company_wx_users->update_field_list('db_weiyi_admin.t_company_wx_users',[
        //     "permission" => $permission
        // ],"userid",$userid);
        if ($status == 1) {
            $this->t_company_wx_tag->field_update_list($id, [
                "leader_power" => $permission
            ]);
        } elseif ($status == 2) {
            $this->t_company_wx_tag->field_update_list($id, [
                "no_leader_power" => $permission
            ]);
        }
        return $this->output_succ();

        // //$old_permission = $this-> get_in_str_val('old_permission');
        // $adminid = session('adminid');
        // $uid = $uid;
        // $type = 1;
        // $old = $old_permission;
        // $new = $permission;
        // $this->t_seller_edit_log->row_insert([
        //     "adminid"     => $adminid,
        //     "type"        => $type,
        //     "uid"         => $uid,
        //     "old"         => $old,
        //     "new"         => $new,
        //     "create_time" => time(NULL),
        // ],false,false,true );
        //return $this->output_succ();

    }

    public function test_list() {
        $info = $this->t_company_wx_department->get_all_list();

        // 生成树节点
        //$info = $this->genTree($info, 74);
        //dd($info);

        // 获取某节点的所有父节点
        //$info = $this->get_parent_node($info,74);

        // 获取某节点的所有子节点
        $info = $this->get_child_node($info, 74);
        // 根据部门获取对应的tag
        // $tag_department = $this->t_company_wx_tag_department->get_all_list();
        // $tag_depart = [];
        // foreach($tag_department as $item) {
        //     $tag_depart[$item['id']] = $item['department'];
        // }
        // $tag = $this->t_company_wx_tag->get_all_list();
        
        dd($info);
    }

    public function genTree($data,$pid) {
        $tree = '';
        foreach($data as $k => $v)
        {
            if($v['pId'] == $pid)
            {
                $arr = $this->genTree($data, $v['id']); // 找子节点
                // if (isset($users[$v['id']])) {
                //     $v['users'] = $users[$v['id']];
                // }
                $tree[] = $v;
            }
        }
        return $tree;
    }

    public function get_parent_node($data, $parent) { // 获取某节点的所有父节点
        foreach($data as $k => $v) {
            if ($parent == 0) {
                return $parent;
            }
            if ($v['id'] == $parent) {
                $parent .= '-'.$this->get_parent_node($data, $v['pId']);
                break;
            }
        }
        return $parent;
    }

    public function get_child_node($data, $child) { // 获取某节点的所有子节点
        $tree = '';
        foreach($data as $k => $v) {
            if ($v['pId'] == $child) {
                $ret = $this->get_child_node($data, $v['id']);
                // if (is_array($ret)) { // 数组方式显现
                //     $tree[] = $v;
                //     $tree = array_merge($tree, $ret);
                // } else {
                //     $tree[] = $v;
                // }
                if ($ret) {
                    $tree[] = $v['id'];
                    $tree = array_merge($tree, $ret);
                } else {
                    $tree[] = $v['id'];
                }
            }
        }
        return $tree;
    }

    public function flush_company_wx_data() {
        $acc = $this->get_account();
        //$base = substr(dirname(__FILE__), 0, -20);
        //$command = $base.'artisan command:update_company_wx_data 0 > /tmp/1.log';
        //exec($command);
        dispatch( new \App\Jobs\update_company_wx_data());
        return $this->output_succ();
    }

    public function flush_company_wx_data_log() {
        $redis = Redis::connection('cache_nick');
        $len = $redis -> lLen('flush_company_wx_data');
        $log = '';
        for ($i = 1; $i <= $len; $i ++) {
            $log[] = $redis -> rPop('flush_company_wx_data');
        }
        return $this->output_succ(['data' => $log]);
    }

    public function get_ower_power() {
        $uid = $this->get_in_str_val('uid', 0);
        $power = $this->t_manager_info->get_power($uid);
        return $this->output_succ(['data' => $power]);
    }

    // 显示企业微信与后台不一样的数据
    public function dissimil_users() {
        // 企业微信用户
        $users = $this->t_company_wx_users->get_all_users();
        // 后台管理用户
        $manager = $this->t_manager_info->get_all_list();
        $info = '';
        foreach($users as $key => $item) {
            if (!isset($manager[$key])) {
                $info[$key]['name'] = $item['name'];
                $info[$key]['phone'] = $item['mobile'];
                $info[$key]['mobile'] = preg_replace('/(1[3456789]{1}[0-9])[0-9]{4}([0-9]{4})/i','$1****$2',$item['mobile']);
            }
        }
        return $this->pageView(__METHOD__,null,[
            'info' => $info
        ]);
    }

    //更新手机号
    public function update_phone_data() {
        $name = $this->get_in_str_val('name');
        $phone = $this->get_in_str_val('phone','');
        $info = $this->t_manager_info->get_phone_by_name($name);
        if ($info) {
            
            $this->t_manager_info->field_update_list($info['uid'], [
                'phone' => $phone
            ]);
            return $this->output_succ();
        }
        return $this->output_err('管理后台无此账号');
    }
}