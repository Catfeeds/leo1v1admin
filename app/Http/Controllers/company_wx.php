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
        $url = $config['url'].'/cgi-bin/gettoken?corpid='.$config['CorpID'].'&corpsecret='.$config['Secret'];
        $tocken = $this->get_company_wx_data($url, 'access_token'); // 获取tocken
        $url = $config['url'].'/cgi-bin/department/list?access_token='.$tocken;
        $department = $this->get_company_wx_data($url, 'department');
        $users = '';
        if ($department) {
            foreach ($department as $val) {
                $department = $val['id'];
                $url = $config['url'].'/cgi-bin/user/list?access_token='.$tocken.'&department_id='.$department.'&fetch_child=0';
                $users = $this->get_company_wx_data($url, 'userlist');
                if ($users) {
                    foreach($users as $item) {
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
                            "`order`" => $item['order'][0]
                        ]);
                    }
                }

                // $this->t_company_wx_department->row_insert([
                //     "id" => $val['id'],
                //     "name" => $val['name'],
                //     "parentid" => $val['parentid'],
                //     "`order`" => $val['order']
                // ]);
            }
            echo '加载部门成功';
        }
        // $url = $config['url'].'/cgi-bin/user/list?access_token='.$tocken.'&department_id=1&fetch_child=1';
        // $users = $this->get_company_wx_data($url, 'userlist');
        // if ($users) {
        //     foreach ($users as $item) {
        //         // 处理部门
        //         $department = '';
        //         foreach($item['department'] as $v) {
        //             $department += $v.',';
        //         }
        //         $department = substr($department,0,-1);
        //         $this->t_company_wx_users->row_insert([
        //             "userid" => $item['userid'],
        //             "name" => $item['name'],
        //             "department" => $department,
        //             "position" => $item['position'],
        //             "mobile" => $item['mobile'],
        //             "gender" => $item['gender'],
        //             "email" => $item["email"],
        //             "avatar" => $item['avatar'],
        //             "isleader" => $item['isleader'],
        //             "english_name" => $item["english_name"],
        //             "telephone" => $item["telephone"],
        //             "`order`" => $item['order'][0]
        //         ]);
        //     }
        //     echo '加载员工成功';
        // }
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
        $info = $this->t_company_wx_department->get_all_list();
        $users = $this->t_company_wx_users->get_all_list();
        $people = [];
        foreach($users as $item) {
            $people[$item['parentid']][] = $item;
        }
        //$info = array_merge($info,$users);
        $info = $this->getTree($info, 0, $people);
        $ret_info = $info;
        return $this->pageView(__METHOD__, '', []);
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

}