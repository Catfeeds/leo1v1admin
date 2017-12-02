<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use \App\Helper\Config as Config;

class update_company_wx_data extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:update_company_wx_data {type}';

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
        $type = $this->argument('type');
        if ($type == 1) { // 刷新权限
            $this->flush_permission($task);
        } else { // 刷新企业微信数据到本地
            $this->flush_data_for_company_wx($task);
        }

    }

    public function flush_permission($task) {
        // 1. 获取用户 t_manager_info(uid account phone) t_company_wx_users(department)
        $info = $task->t_company_wx_users->get_all_list_for_manager();
        // 2. 获取用户所拥有的tag
        $department = $task->t_company_wx_department->get_all_list();
        $tag_depart_temp = $task->t_company_wx_tag_department->get_all_list();
        $tag_depart = [];
        $tag_department = [];
        foreach($tag_depart_temp as $item) {
            $tag_depart[$item['id']] = $item['department'];
            $tag_department[$item['department']][] = $item['id'];
        }

        $tag = $task->t_company_wx_tag->get_all_list();
        $tag_users = $task->t_company_wx_tag_users->get_all_list();

        foreach ($info as $item) {
            if ($item['isleader'] == 1) { // 领导
                $perm = @$tag[$tag_users[$item['userid']]['id']]['leader_power'];
                $perm .= ','.$item['power'];
                $parent = $this->get_parent_node($department, $item['department']);
                $parent = explode("-", $parent);
                $tag_d = [];
                foreach ($parent as $val) {
                    if (isset($tag_department[$val])) array_push($tag_d, $tag_department[$val]);
                }
                if ($tag_d) {
                    foreach($tag_d as $val) {
                        foreach($val as $v) {
                            $perm .= ','.$tag[$v]['no_leader_power'];
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
            $perm = explode(',', $perm);
            array_unique($perm);
            $perm = implode(',', $perm);
            $task->t_manager_info->field_update_list($item['uid'], [
                'power' => $perm
            ]);
            echo 'uid: '.$item['uid'].'添加成功 添加权限:'.$perm.PHP_EOL;
        }
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
                foreach($department as $val) {
                    if (!(isset($tag_depart[$item['tagid']]) && in_array($val, $tag_depart[$item['tagid']]))) { // 添加标签部门数据
                        $task->t_company_wx_tag_department->row_insert([
                            "id" => $item['tagid'],
                            'department' => $val
                        ]);
                    }

                }
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

            }
            echo '加载标签完成';
        }

        // 处理部门
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
                if (in_array($department, $depart_info)) continue;
                $task->t_company_wx_department->row_insert([ // 加载部门数据
                    "id" => $val['id'],
                    "name" => $val['name'],
                    "parentid" => $val['parentid'],
                    "`order`" => $val['order']
                ]);
            }
            echo '加载部门成功';
        }

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
