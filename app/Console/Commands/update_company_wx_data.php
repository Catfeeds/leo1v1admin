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
        foreach($tag_depart_temp as $item) {
            $tag_depart[$item['id']] = $item['department'];
        }
        $tag = $task->t_company_wx_tag->get_all_list();

        foreach ($info as $item) {
            if ($item['department'] && $item['department'] == 74) {
                $child = $this->get_child_node($department, $item['department']); // 获取当前用户所拥有的部门id

                foreach($tag_depart as $key => $val) {
                    echo $key;
                    if(in_array($val, $child)) { // 当前用户所拥有的tag
                        $perm = $task->t_manager_info->get_power($item['uid']);
                        if ($item['isleader'] == 1) {
                            dd($tag[$key]);
                            $task->t_company_wx_users->field_update_list($item['uid'], [
                                'permission' => $item['leader_power']
                            ]);
                        } else {
                            $task->t_company_wx_users->field_update_list($id, [
                                'permission' => $item['not_leader_power']
                            ]);
                        }
                    }
                }

            }
        }
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
                $users = $this->get_company_wx_data($url,"partylist");
                foreach($users as $val) {
                    if (!(isset($tag_depart[$item['tagid']]) && in_array($val, $tag_depart[$item['tagid']]))) { // 添加标签部门数据
                        $task->t_company_wx_tag_department->row_insert([
                            "id" => $item['tagid'],
                            'department' => $val
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
                $this->t_company_wx_department->row_insert([ // 加载部门数据
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

}
