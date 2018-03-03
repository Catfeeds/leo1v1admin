<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use \App\Helper\Config as Config;
use Illuminate\Support\Facades\Redis;

class update_company_wx_data extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $redis = Redis::connection('cache_nick');
        $url = $this->get_url();
        $token = $this->get_token(); // 获取token
        $this->flush_tag_data($token,$url,$redis); // 刷新tag
        $this->flush_users_data($token,$url,$redis); // 刷新组织用户
        $this->flush_permission($redis); // 刷新权限
    }

    public function flush_users_data($token,$burl,$redis) {
        \App\Helper\Utils::logger(date('Y-m-d H:i:s',time()).'加载部门数据开始');
        $redis->lPush('flush_company_wx_data', date('Y-m-d H:i:s',time()).'加载部门数据开始');
        $t_company_wx_department = new \App\Models\t_company_wx_department();
        $t_company_wx_users = new \App\Models\t_company_wx_users();
        $info = $t_company_wx_department->get_all_list();
        $depart_info = array_column($info, 'id');
        $users = $t_company_wx_users->get_all_list();
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
                \App\Helper\Utils::logger(date('Y-m-d H:i:s',time()).'加载部门为'.$department.'下的用户数据开始');
                $redis->lPush('flush_company_wx_data', date('Y-m-d H:i:s',time()).'加载部门为'.$department.'下的用户数据开始');
                $users = $this->get_company_wx_data($url, 'userlist');
                if ($users) {
                    $t_company_wx_users->row_delete_for_department($department); // 删除以前的数据
                    foreach($users as $item) {
                        $depart = array_flip($item['department']);
                        //if (in_array($department.$item['userid'], $peo)) continue; //判断当前用户是否已存在
                        $t_company_wx_users->row_insert([ // 加载用户数据
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
                \App\Helper\Utils::logger(date('Y-m-d H:i:s',time()).'加载部门为'.$department.'下的用户数据结束');
                $redis->lPush('flush_company_wx_data', date('Y-m-d H:i:s',time()).'加载部门为'.$department.'下的用户数据结束');
                if (in_array($department, $depart_info)) continue;
                $t_company_wx_department->row_insert([ // 加载部门数据
                    "id" => $val['id'],
                    "name" => $val['name'],
                    "parentid" => $val['parentid'],
                    "`order`" => $val['order']
                ]);
            }
        }
        \App\Helper\Utils::logger(date('Y-m-d H:i:s',time()).'加载部门数据结束');
        $redis->lPush('flush_company_wx_data', date('Y-m-d H:i:s',time()).'加载部门数据结束');
    }

    public function flush_tag_data($token,$burl,$redis) {
        \App\Helper\Utils::logger(date('Y-m-d H:i:s',time()).' 加载标签开始');
        $redis->lPush('flush_company_wx_data', date('Y-m-d H:i:s',time()).' 加载标签开始');
        $t_company_wx_tag_department = new \App\Models\t_company_wx_tag_department();
        $t_company_wx_tag_users = new \App\Models\t_company_wx_tag_users();
        $t_company_wx_tag = new \App\Models\t_company_wx_tag();
        $tag_department = $t_company_wx_tag_department->get_all_list();
        $tag_depart = [];
        foreach($tag_department as $item) {
            $tag_depart[$item['id']][] = $item['department'];
        }
        $tag_users_temp = $t_company_wx_tag_users->get_all_list();
        $tag_users = [];
        foreach($tag_users_temp as $item) {
            $tag_users[$item['id']][] = $item['userid'];
        }

        // 2. 处理标签
        $url = $burl.'/cgi-bin/tag/list?access_token='.$token;
        $tag = $this->get_company_wx_data($url,'taglist');
        if ($tag) {
            foreach($tag as $item) {
                $info = $t_company_wx_tag->get_name($item['tagid']);
                if (!$info) { // 添加标签数据
                    $id = $t_company_wx_tag->row_insert([
                        "id" => $item['tagid'],
                        'name' => $item['tagname'],
                    ]);
                }
                $url = $burl.'/cgi-bin/tag/get?access_token='.$token.'&tagid='.$item['tagid'];
                $tag_d_u = $this->get_company_wx_data($url);
                $department = $tag_d_u['partylist'];
                \App\Helper\Utils::logger(date('Y-m-d H:i:s',time()).' 加载标签为'.$item['tagid'].'下的部门数据开始');
                $redis->lPush('flush_company_wx_data', date('Y-m-d H:i:s',time()).' 加载标签为'.$item['tagid'].'下的部门数据开始');
                foreach($department as $val) {
                    if (!(isset($tag_depart[$item['tagid']]) && in_array($val, $tag_depart[$item['tagid']]))) { // 添加标签部门数据
                        $t_company_wx_tag_department->row_insert([
                            "id" => $item['tagid'],
                            'department' => $val
                        ]);
                    }

                }
                \App\Helper\Utils::logger(date('Y-m-d H:i:s',time()).' 加载标签为'.$item['tagid'].'下的部门数据结束');
                $redis->lPush('flush_company_wx_data', date('Y-m-d H:i:s',time()).' 加载标签为'.$item['tagid'].'下的部门数据结束');
                \App\Helper\Utils::logger(date('Y-m-d H:i:s',time()).' 加载标签为'.$item['tagid'].'下的用户数据开始');
                $redis->lPush('flush_company_wx_data', date('Y-m-d H:i:s',time()).' 加载标签为'.$item['tagid'].'下的用户数据开始');
                // 添加标签用户数据
                $users = $tag_d_u['userlist'];
                foreach($users as $val) {
                    if (!(isset($tag_users[$item['tagid']]) && in_array($val['userid'], $tag_users[$item['tagid']]))) {
                        $t_company_wx_tag_users->row_insert([
                            "id" => $item['tagid'],
                            'userid' => $val['userid']
                        ]);
                    }
                }
                \App\Helper\Utils::logger(date('Y-m-d H:i:s',time()).' 加载标签为'.$item['tagid'].'下的用户数据结束');
                $redis->lPush('flush_company_wx_data', date('Y-m-d H:i:s',time()).' 加载标签为'.$item['tagid'].'下的用户数据结束');
            }
        }
        \App\Helper\Utils::logger(date('Y-m-d H:i:s',time()).' 加载标签结束');
        $redis->lPush('flush_company_wx_data', date('Y-m-d H:i:s',time()).' 加载标签结束');
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

    public function flush_permission($redis) {
        \App\Helper\Utils::logger(date('Y-m-d H:i:s',time()).' 加载权限开始');
        $redis->lPush('flush_company_wx_data', date('Y-m-d H:i:s',time()).' 加载权限开始');
        $t_company_wx_users = new \App\Models\t_company_wx_users();
        $t_company_wx_department = new \App\Models\t_company_wx_department();
        $t_company_wx_tag_department = new \App\Models\t_company_wx_tag_department();
        $t_company_wx_tag = new \App\Models\t_company_wx_tag();
        $t_company_wx_tag_users = new \App\Models\t_company_wx_tag_users();
        $t_manager_info = new \App\Models\t_manager_info();
        // 1. 获取用户 t_manager_info(uid account phone) t_company_wx_users(department)
        $info = $t_company_wx_users->get_all_list_for_manager();
        // 2. 获取用户所拥有的tag
        $department = $t_company_wx_department->get_all_list();
        $tag_depart_temp = $t_company_wx_tag_department->get_all_list();
        $tag_depart = [];
        $tag_department = [];
        foreach($tag_depart_temp as $item) {
            $tag_depart[$item['id']] = $item['department'];
            $tag_department[$item['department']][] = $item['id'];
        }

        $tag = $t_company_wx_tag->get_all_list();
        $tag_users = $t_company_wx_tag_users->get_all_list();

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
                //if ($item['power']) $perm = $item['power'].',';
                $perm = substr($perm,0,-1);
                $perm = explode(',', $perm);
                $perm = array_unique($perm);
                $perm = array_filter($perm);
                $perm = implode(',', $perm);
                $t_manager_info->field_update_list($item['uid'], [
                    'power' => $perm
                ]);
                echo date('Y-m-d H:i:s',time()).'uid: '.$item['uid'].'添加成功 添加权限:'.$perm.PHP_EOL;
                $redis->lPush('flush_company_wx_data', date('Y-m-d H:i:s',time()).'uid: '.$item['uid'].'添加成功 添加权限:'.$perm);
            }
        }
        echo date('Y-m-d H:i:s',time()).' 加载权限结束'.PHP_EOL;
        $redis->lPush('flush_company_wx_data', date('Y-m-d H:i:s',time()).' 加载权限结束');
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

    public function get_company_wx_data($url, $index = '') { //根据不同路由获取不同的数据 (企业微信)
        $info = file_get_contents($url);
        $info = json_decode($info, true);
        if ($index && isset($info[$index])) {
            return $info[$index];
        }
        return $info;
    }

}
