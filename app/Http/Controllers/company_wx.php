<?php

namespace App\Http\Controllers;
use \App\Http\Controllers\Controller;
use \App\Enums as E;
use \App\Helper\Config as Config;

class company_wx extends Controller
{
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
        $url = $config['url'].'/cgi-bin/gettoken?corpid='.$config['CorpID'].'&corpsecret='.$config['Secret'];
        $token = $this->get_company_wx_data($url, 'access_token'); // 获取tocken
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
                $url = $config['url'].'/cgi-bin/tag/get?access_token='.$token.'&tagid='.$item['tagid'];
                $users = $this->get_company_wx_data($url,"partylist");
                $users = [278,279,280,281,282];
                foreach($users as $val) {
                    //$info = $this->t_company_wx_tag_department->get_name($item['tagid']);
                    //if (!$info) {
                        $this->t_company_wx_tag_department->row_insert([
                            "id" => $item['tagid'],
                            'department' => $val
                        ]);

                    //}

                }
            }
            echo '加载标签完成';
        }
        exit;

        $url = $config['url'].'/cgi-bin/department/list?access_token='.$token;
        $department = $this->get_company_wx_data($url, 'department');
        $users = '';
        if ($department) {
            foreach ($department as $val) {
                $department = $val['id'];
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

    public function get_company_wx_data($url, $index) { //根据不同路由获取不同的数据 (企业微信)
        $info = file_get_contents($url);
        $info = json_decode($info, true);
        if (isset($info[$index])) {
            return $info[$index];
        }
        return;
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
            $name = $item['position'].'-'.$item['name'];
            if ($item['isleader'] == 1) {
                $name .= '(领导)';
            }
            $users[$key]['name'] = $name;
            
            // if ( $item['id'] < 600) {
            //     $users[$key]['checked'] = true;
            //     $users[$key]['open'] = true;
            // }
        }

        if ($type == 1) { // 部门授权 
        } elseif ($type == 2) { // 职们授权
            $people = [];
            foreach($users as $item) {
                $people[$item['position'].$item['pId']]['id'] = $item['id'];
                $people[$item['position'].$item['pId']]['name'] = $item['position'];
                $people[$item['position'].$item['pId']]['pId'] = $item['pId'];
            }
            $i = 0;
            foreach($people as $key => $item) {
                $people[$i] = $item;
                unset($people[$key]);
                $i ++;
            }
            $info = array_merge($info, $people);
        } else {
            $info = array_merge($info,$users);
        }

        //$info = $this->getTree($info, 0, $people);
        return $this->pageView(__METHOD__, '', [
            'info' => $info,
            'ext' => $ext
        ]);
    }

    public function all_users() {
        $tag = $this->t_company_wx_tag->get_all_list();
        
        return $this->pageView(__METHOD__, '', [
            'info' => $tag
        ]);
    }

    function getTree($data, $pId, $users)
    {
        $tree = '';
        foreach($data as $k => $v)
        {
            if($v['parentid'] == $pId)
            {
                $v['children'] = $this->getTree($data, $v['id'], $users); // 找子节点
                if (isset($users[$v['id']])) {
                    $v['users'] = $users[$v['id']];
                }
                $tree[] = $v;
            }
        }
        return $tree;
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
}