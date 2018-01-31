<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use \App\Helper\Config as Config;

use \App\Enums as E;

class update_company_wx_data extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:update_company_wx_data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '将企业微信数据更新至本地';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $task = new \App\Console\Tasks\TaskController();
        $start_time = strtotime(date('Y-m-d', strtotime('-1 month')));
        //$end_time = strtotime(date('Y-m-1', time()));
        $end_time = time();
        $this->get_approve($task,$start_time, $end_time); // 拉取审批数据
        //$url = $this->get_url();
        //$token = $this->get_token(); // 获取token
        //$this->flush_tag_data($token,$url,$task); // 刷新tag
        //$this->flush_users_data($token,$url,$task); // 刷新组织用户
        //$this->flush_permission($task); // 刷新权限
    }

    public function get_approve($task,$start_time, $end_time) { // 获取审批数据
        $approv_type  = E\Eapprov_type::get_specify_select();
        $approv_type = array_flip($approv_type);

        $approv = $task->t_company_wx_approval->get_all_info($start_time, $end_time);

        $config = Config::get_config("company_wx");
        if (!$config) {
            exit('没有配置');
        }

        // list($start_time, $end_time) = $this->get_in_date_range_day(0);
        // $start_time = $this->get_in_str_val('start_time');
        // $end_time = $this->get_in_str_val('end_time');
        // 获取token
        $config = Config::get_config("company_wx");
        $url = $config['url'].'/cgi-bin/gettoken?corpid='.$config['CorpID'].'&corpsecret='.$config['Secret2'];
        $token = $this->get_company_wx_data($url, 'access_token'); // 获取tocken

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

        $info = $output['data'];
        foreach($info as $item) {
            if (isset($approv[$item['apply_user_id'].'-'.$item['apply_time']])) {
                echo "数据已存在，正在更新状态 ... ";
                $index = $item['apply_user_id'].'-'.$item['apply_time'];
                if ($approv[$index]['sp_status'] != $item['sp_status']) {
                    $task->t_company_wx_approval->field_update_list($approv[$index]['id'], [ // 更改审核状态
                        "sp_status" => $item['sp_status']
                    ]);
                }
                continue;
            }
            $approval_name = implode(',', $item['approval_name']);
            $notify_name = implode(',', $item['notify_name']);
            $names = array_merge($item["approval_name"], $item["notify_name"]);
            $common = [
                'spname' => $item['spname'],
                'apply_name' => $item['apply_name'],
                'apply_org' => $item['apply_org'],
                'approval_name' => $approval_name,
                'notify_name' => $notify_name,
                'sp_status' => $item['sp_status'],
                'sp_num' => $item['sp_num'],
                "apply_time" => $item['apply_time'],
                "apply_user_id" => $item['apply_user_id']
            ];

            if (isset($item['leave'])) { // 处理请假 type=1 请假
                $lea = $item['leave'];
                $leave = [
                    "timeunit" => $lea['timeunit'],
                    "approv_type" => $lea['leave_type'],
                    "start_time" => $lea['start_time'],
                    "end_time" => $lea['end_time'],
                    "duration" => $lea['duration'],
                    "reason" => $lea['reason']
                ];
                $common = array_merge($common, $leave);
                $common['type'] = E\Eapproval_type::V_1;
            }
            $leave = json_decode($item['comm']['apply_data'], true);
            $items = "";
            // 1.初始化
            $data_desc = $data_column = $require_reason = $require_time = "";

            foreach ($leave as $val) {
                if ($item['spname'] == "武汉请假流") {
                    if ($val['title'] == '请假类型') {
                        if (isset($approv_type[$val['value']])) {
                            $common["approv_type"] = $approv_type[$val['value']];
                        } else {
                            $common["approv_type"] = 8;
                        }
                    }
                    if ($val['title'] == '开始时间') $common['start_time'] = ($val['value'] / 1000);
                    if ($val['title'] == '结束时间') $common['end_time'] = ($val['value'] / 1000);
                    if ($val['title'] == '事由') $common['reason'] = $val['value'];
                    $common['type'] = E\Eapproval_type::V_1;
                }
                if ($item['spname'] == '拉取数据审批') {
                    // 2. 赋值
                    if ($val['title'] == '数据描述') $data_desc = $val['value'];
                    if ($val["title"] == "数据字段") $data_column = $val["value"];
                    if ($val["title"] == "需求原因") $require_reason = $val["value"];
                    if ($val['title'] == '需要时间') $require_time = ($val['value'] / 1000);
                    $common['type'] = E\Eapproval_type::V_11;
                }
                if ($item['spname'] == '费用申请') {
                    if ($val['title'] == '费用类型') $common['reason'] = $val['value'];
                    if ($val['title'] == '费用金额') $common['sums'] = $val['value'];
                    //if (isset($item['value'])) $items[$val['title']] = $val['value'];
                    $common['type'] = E\Eapproval_type::V_2;
                }
                if ($item['spname'] == '学生年级修改') {
                    if ($val["title"] == "备注") $common['reason'] = $val['value'];
                    $common['type'] = E\Eapproval_type::V_13;
                }
                if (isset($val['value'])) $items[$val['title']] = $val['value'];
            }

            // 3. 将数据添加到数据库中
            if ($item["spname"] == "拉取数据审批") {
                $info = $task->t_company_wx_approval_data->get_list_for_user_time($common["apply_user_id"], $common["apply_time"]);

                if (!$info) {
                    $data = [
                        "apply_name" => $common["apply_name"],
                        "apply_user_id" => $common["apply_user_id"],
                        "apply_time" => $common["apply_time"],
                        "data_desc" => $data_desc,
                        "data_column" => $data_column,
                        "require_reason" => $require_reason,
                        "require_time" => $require_time
                    ];
                    $task->t_company_wx_approval_data->row_insert($data);
                    $id = $task->t_company_wx_approval_data->get_last_insertid();
                    foreach($names as $name) {
                        $userid = $task->t_company_wx_users->get_userid_for_name($name);
                        $did = $task->t_company_wx_approval_notify->get_list_for_user_id($id,$userid);
                        if (!$did) {
                            $task->t_company_wx_approval_notify->row_insert([
                                "d_id" => $id,
                                "user_id" => $userid
                            ]);
                            echo "加载关联数据成功关联人".$name;
                        }
                    }

                    echo "加载拉取数据审批成功";
                }

            }

            if ($items) $common['item'] = json_encode($items);
            // 添加数据
            $task->t_company_wx_approval->row_insert($common);
            echo '加载数据成功'.$common['spname'];
        }
    }

    public function flush_users_data($token,$burl,$task) {
        echo date('Y-m-d H:i:s',time()).'加载部门数据开始'.PHP_EOL;
        $info = $task->t_company_wx_department->get_all_list();
        $depart_info = array_column($info, 'id');
        $users = $task->t_company_wx_users->get_all_list();
        $peo = [];
        foreach($users as $item) {
            $peo[] = $item['pId'].$item['userid'];
        }

        // 处理部门
        $url = $burl.'/cgi-bin/department/list?access_token='.$token;
        $department = $this->get_company_wx_data($url, 'department');
        $users = '';
        if ($department) {
            foreach ($department as $val) {
                $department = $val['id'];
                $url = $burl.'/cgi-bin/user/list?access_token='.$token.'&department_id='.$department.'&fetch_child=0';
                echo date('Y-m-d H:i:s',time()).'加载部门为'.$department.'下的用户数据开始'.PHP_EOL;
                $users = $this->get_company_wx_data($url, 'userlist');
                if ($users) {
                    $task->t_company_wx_users->row_delete_for_department($department);
                    foreach($users as $item) {
                        $depart = array_flip($item['department']);
                        //if (in_array($department.$item['userid'], $peo)) continue; //判断当前用户是否已存在
                        $task->t_company_wx_users->row_insert([ // 加载用户数据
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
                echo date('Y-m-d H:i:s',time()).'加载部门为'.$department.'下的用户数据结束'.PHP_EOL;
                if (in_array($department, $depart_info)) continue;
                $task->t_company_wx_department->row_insert([ // 加载部门数据
                    "id" => $val['id'],
                    "name" => $val['name'],
                    "parentid" => $val['parentid'],
                    "`order`" => $val['order']
                ]);
            }
        }
        echo date('Y-m-d H:i:s',time()).'加载部门数据开始'.PHP_EOL;
    }

    public function flush_tag_data($token,$burl,$task) {
        echo date('Y-m-d H:i:s',time()).' 加载标签开始'.PHP_EOL;
        $tag_department = $task->t_company_wx_tag_department->get_all_list();
        $tag_depart = [];
        foreach($tag_department as $item) {
            $tag_depart[$item['id']][] = $item['department'];
        }
        $tag_users_temp = $task->t_company_wx_tag_users->get_all_list();
        $tag_users = [];
        foreach($tag_users_temp as $item) {
            $tag_users[$item['id']][] = $item['userid'];
        }

        // 2. 处理标签
        $url = $burl.'/cgi-bin/tag/list?access_token='.$token;
        $tag = $this->get_company_wx_data($url,'taglist');
        if ($tag) {
            foreach($tag as $item) {
                $info = $task->t_company_wx_tag->get_name($item['tagid']);
                if (!$info) { // 添加标签数据
                    $id = $task->t_company_wx_tag->row_insert([
                        "id" => $item['tagid'],
                        'name' => $item['tagname'],
                    ]);
                }
                $url = $burl.'/cgi-bin/tag/get?access_token='.$token.'&tagid='.$item['tagid'];
                $tag_d_u = $this->get_company_wx_data($url);
                $department = $tag_d_u['partylist'];
                echo date('Y-m-d H:i:s',time()).' 加载标签为'.$item['tagid'].'下的部门数据开始'.PHP_EOL;
                foreach($department as $val) {
                    if (!(isset($tag_depart[$item['tagid']]) && in_array($val, $tag_depart[$item['tagid']]))) { // 添加标签部门数据
                        $task->t_company_wx_tag_department->row_insert([
                            "id" => $item['tagid'],
                            'department' => $val
                        ]);
                    }

                }
                echo date('Y-m-d H:i:s',time()).' 加载标签为'.$item['tagid'].'下的部门数据结束'.PHP_EOL;
                echo date('Y-m-d H:i:s',time()).' 加载标签为'.$item['tagid'].'下的用户数据开始'.PHP_EOL;
                // 添加标签用户数据
                $users = $tag_d_u['userlist'];
                foreach($users as $val) {
                    if (!(isset($tag_users[$item['tagid']]) && in_array($val['userid'], $tag_users[$item['tagid']]))) {
                        $task->t_company_wx_tag_users->row_insert([
                            "id" => $item['tagid'],
                            'userid' => $val['userid']
                        ]);
                    }
                }
                echo date('Y-m-d H:i:s',time()).' 加载标签为'.$item['tagid'].'下的用户数据结束'.PHP_EOL;
            }
        }
        echo date('Y-m-d H:i:s',time()).' 加载标签结束'.PHP_EOL;
    }

    public function get_token() {
        $config = Config::get_config("company_wx");
        // 1. 获取 token
        $url = $config['url'].'/cgi-bin/gettoken?corpid='.$config['CorpID'].'&corpsecret='.$config['Secret'];
        $token = $this->get_company_wx_data($url, 'access_token'); // 获取tocken
        return $token;
    }

    public function get_url() {
        $config = Config::get_config("company_wx");
        if (!$config) {
            exit('没有配置');
        }
        return $config['url'];
    }

    public function flush_permission($task) {
        echo date('Y-m-d H:i:s',time()).' 加载权限开始'.PHP_EOL;
        // 1. 获取用户 t_manager_info(uid account phone) t_company_wx_users(department)
        $info = $task->t_company_wx_users->get_all_list_for_manager();
        // 2. 获取用户所拥有的tag
        // $department = $task->t_company_wx_department->get_all_list();
        // $tag_depart_temp = $task->t_company_wx_tag_department->get_all_list();
        // $tag_depart = [];
        // $tag_department = [];
        // foreach($tag_depart_temp as $item) {
        //     $tag_depart[$item['id']] = $item['department'];
        //     $tag_department[$item['department']][] = $item['id'];
        // }

        // $tag = $task->t_company_wx_tag->get_all_list();
        // $tag_users = $task->t_company_wx_tag_users->get_all_list();

        foreach ($info as $item) {
            $item['power'] = '';
            if ($item['isleader'] == 1 || true) { // 领导
                $perm = @$tag[$tag_users[$item['userid']]['id']]['leader_power'];
                $parent = $this->get_parent_node($department, $item['department']);
                $parent = explode("-", $parent);
                $tag_d = [];
                foreach ($parent as $val) {
                    if (isset($tag_department[$val])) array_push($tag_d, $tag_department[$val]);
                }
                if ($tag_d) {
                    foreach($tag_d as $val) {

                        foreach($val as $v) {
                            if ($tag[$v]['no_leader_power']) {
                                if ($perm) {
                                    $perm .= ','.$tag[$v]['no_leader_power'].',';
                                } else {
                                    $perm .= $tag[$v]['no_leader_power'].',';
                                }
                            }
                        }
                    }
                }

                // if ($item['department']) {
                //     $child = $this->get_child_node($department, $item['department']); // 获取当前用户所拥有的部门id

                //     foreach($tag_depart as $key => $val) {
                //         if(in_array($val, $child)) { // 当前用户所拥有的tag
                //             $perm .= ','.$tag[$tag_depart[$key]]['leader_power'];
                //         }
                //     }

                // }
            } else {
                $perm = @$tag[$tag_users[$item['userid']]['id']]['not_leader_power'];
            }
            if ($perm) {
                if ($item['power']) $perm = $item['power'].',';
                $perm = substr($perm,0,-1);
                $perm = explode(',', $perm);
                array_unique($perm);
                $perm = implode(',', $perm);
                $task->t_manager_info->field_update_list($item['uid'], [
                    'power' => $perm
                ]);
                echo date('Y-m-d H:i:s',time()).'uid: '.$item['uid'].'添加成功 添加权限:'.$perm.PHP_EOL;
            }
        }
        echo date('Y-m-d H:i:s',time()).' 加载权限结束'.PHP_EOL;
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


    public function flush_data_for_company_wx($task) {
        $config = Config::get_config("company_wx");
        if (!$config) {
            exit('没有配置');
        }
        $info = $task->t_company_wx_department->get_all_list();
        $depart_info = array_column($info, 'id');
        $users = $task->t_company_wx_users->get_all_list();
        $peo = [];
        foreach($users as $item) {
            $peo[] = $item['pId'].$item['userid'];
        }
        $tag_department = $task->t_company_wx_tag_department->get_all_list();
        $tag_depart = [];
        foreach($tag_department as $item) {
            $tag_depart[$item['id']][] = $item['department'];
        }
        $tag_users_temp = $task->t_company_wx_tag_users->get_all_list();
        $tag_users = [];
        foreach($tag_users_temp as $item) {
            $tag_users[$item['id']][] = $item['userid'];
        }

        // 1. 获取 token
        $url = $config['url'].'/cgi-bin/gettoken?corpid='.$config['CorpID'].'&corpsecret='.$config['Secret'];
        $token = $this->get_company_wx_data($url, 'access_token'); // 获取tocken

        // 2. 处理标签
        $url = $config['url'].'/cgi-bin/tag/list?access_token='.$token;
        $tag = $this->get_company_wx_data($url,'taglist');
        echo date('Y-m-d H:i:s',time()).' 加载标签开始'.PHP_EOL;
        if ($tag) {
            foreach($tag as $item) {
                $info = $task->t_company_wx_tag->get_name($item['tagid']);
                if (!$info) { // 添加标签数据
                    $id = $task->t_company_wx_tag->row_insert([
                        "id" => $item['tagid'],
                        'name' => $item['tagname'],
                    ]);
                }
                $url = $config['url'].'/cgi-bin/tag/get?access_token='.$token.'&tagid='.$item['tagid'];
                $tag_d_u = $this->get_company_wx_data($url);
                $department = $tag_d_u['partylist'];
                echo date('Y-m-d H:i:s',time()).' 加载标签为'.$item['tagid'].'下的部门数据开始'.PHP_EOL;
                foreach($department as $val) {
                    if (!(isset($tag_depart[$item['tagid']]) && in_array($val, $tag_depart[$item['tagid']]))) { // 添加标签部门数据
                        $task->t_company_wx_tag_department->row_insert([
                            "id" => $item['tagid'],
                            'department' => $val
                        ]);
                    }

                }
                echo date('Y-m-d H:i:s',time()).' 加载标签为'.$item['tagid'].'下的部门数据结束'.PHP_EOL;
                echo date('Y-m-d H:i:s',time()).' 加载标签为'.$item['tagid'].'下的用户数据开始'.PHP_EOL;
                // 添加标签用户数据
                $users = $tag_d_u['userlist'];
                foreach($users as $val) {
                    if (!(isset($tag_users[$item['tagid']]) && in_array($val['userid'], $tag_users[$item['tagid']]))) {
                        $task->t_company_wx_tag_users->row_insert([
                            "id" => $item['tagid'],
                            'userid' => $val['userid']
                        ]);
                    }
                }
                echo date('Y-m-d H:i:s',time()).' 加载标签为'.$item['tagid'].'下的用户数据结束'.PHP_EOL;
            }
        }
        echo date('Y-m-d H:i:s',time()).' 加载标签结束'.PHP_EOL;

        echo date('Y-m-d H:i:s',time()).'加载部门数据开始'.PHP_EOL;
        // 处理部门
        $url = $config['url'].'/cgi-bin/department/list?access_token='.$token;
        $department = $this->get_company_wx_data($url, 'department');
        $users = '';
        if ($department) {
            foreach ($department as $val) {
                $department = $val['id'];
                $url = $config['url'].'/cgi-bin/user/list?access_token='.$token.'&department_id='.$department.'&fetch_child=0';
                echo date('Y-m-d H:i:s',time()).'加载部门为'.$department.'下的用户数据开始'.PHP_EOL;
                $users = $this->get_company_wx_data($url, 'userlist');
                if ($users) {
                    foreach($users as $item) {
                        $depart = array_flip($item['department']);
                        if (in_array($department.$item['userid'], $peo)) continue; //判断当前用户是否已存在
                        $task->t_company_wx_users->row_insert([ // 加载用户数据
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
                echo date('Y-m-d H:i:s',time()).'加载部门为'.$department.'下的用户数据结束'.PHP_EOL;
                if (in_array($department, $depart_info)) continue;
                $task->t_company_wx_department->row_insert([ // 加载部门数据
                    "id" => $val['id'],
                    "name" => $val['name'],
                    "parentid" => $val['parentid'],
                    "`order`" => $val['order']
                ]);
            }
        }
        echo date('Y-m-d H:i:s',time()).'加载部门数据开始'.PHP_EOL;

    }

    public function get_company_wx_data($url, $index = '') { //根据不同路由获取不同的数据 (企业微信)
        $info = file_get_contents($url);
        $info = json_decode($info, true);
        if ($index && isset($info[$index])) {
            return $info[$index];
        }
        return $info;
    }

}
