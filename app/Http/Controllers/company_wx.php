<?php

namespace App\Http\Controllers;
use \App\Http\Controllers\Controller;
use \App\Enums as E;
use \App\Helper\Config as Config;

use Illuminate\Support\Facades\Redis;
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

    public  function get_openid() { // openid与userid互转
        $config = Config::get_config("company_wx");
        if (!$config) {
            exit('没有配置');
        }

        $type = 1; // 通过用户userid来获取用户openid
        $type = 2; // 通过用户openid来获取用户userid
        $userid = "CaoPeng";
        $openid = "orwGAs6R4UremX_fhr24MvStIxJc";
        $openid = "ocupr077yrT3vk4-DtWcHzPcOz_c";

        $config = Config::get_config("company_wx");
        $url = $config['url'].'/cgi-bin/gettoken?corpid='.$config['CorpID'].'&corpsecret='.$config['Secret2'];
        $token = $this->get_company_wx_data($url, 'access_token'); // 获取tocken

        if ($type == 1) {
            $url = $config['url'].'/cgi-bin/user/convert_to_openid?access_token='.$token;
            $post_data = json_encode(["userid" => $userid]);
        } else {
            $url = $config['url'].'/cgi-bin/user/convert_to_userid?access_token='.$token;
            $post_data = json_encode(["openid" => $openid]);
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        $output = curl_exec($ch);
        curl_close($ch);
        $output = json_decode($output, true);

        dd($output);
        $openid = $output["openid"];
    }

    public function get_approve() { // 获取审批数据
        //dd('只用于测试');

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

        $start_time = strtotime("2018-1-17");
        $end_time = strtotime("2018-1-18");
        // 获取审批数据
        $url = $config['url'].'/cgi-bin/corp/getapprovaldata?access_token='.$token;
        $post_data = json_encode(["starttime" => $start_time,"endtime" => $end_time, "next_spnum" => "201801170003"]);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        $output = curl_exec($ch);
        curl_close($ch);
        $output = json_decode($output, true);

        //var_dump($output);

        $info = $output['data'];
        foreach($info as $item) {
            $approval_name = implode(',', $item['approval_name']);
            $notify_name = implode(',', $item['notify_name']);
            $common = [
                'spname' => $item['spname'],
                'apply_name' => $item['apply_name'],
                'apply_org' => $item['apply_org'],
                'approval_name' => $approval_name,
                'notify_name' => $notify_name,
                'sp_status' => $item['sp_status'],
                'sp_num' => $item['sp_num'],
                //'mediaids' => json_encode($item['mediaids']),
                "apply_time" => $item['apply_time'],
                "apply_user_id" => $item['apply_user_id']
            ];

            if (isset($item['leave'])) { // 处理请假
                $lea = $item['leave'];
                $leave = [
                    "timeunit" => $lea['timeunit'],
                    //"leave_type" => $lea['type'],
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
            $data_desc = $data_column = $require_reason = $require_time = "";

            foreach ($leave as $val) {

                if ($item['spname'] == "武汉请假流") {
                    //if ($val['title'] == '请假类型') $common["leave_type"] = 3;
                    if ($val['title'] == '开始时间') $common['start_time'] = ($val['value'] / 1000);
                    if ($val['title'] == '结束时间') $common['end_time'] = ($val['value'] / 1000);
                    if ($val['title'] == '事由') $common['reason'] = $val['value'];
                    $common['type'] = 2;
                }
   
                if ($item['spname'] == '拉取数据审批') {
                    if ($val['title'] == '数据描述') $data_desc = $val['value'];
                    if ($val["title"] == "数据字段") $data_column = $val["value"];
                    if ($val["title"] == "需求原因") $require_reason = $val["value"];
                    if ($val['title'] == '需要时间') $require_time = ($val['value'] / 1000);
                    $common['type'] = 4;
                    // t_field($table->string("data_desc"), "数据描述");
                    // t_field($table->string("data_column"), "数据字段");
                    // t_field($table->string("require_reason"), "需求原因");
                    // t_field($table->integer("require_time"), "需求时间");
                    echo $data_desc." --- ".$data_column." --- ".$require_reason." --- ".$require_time;

                }
                if ($item['spname'] == '费用申请') {
                    if ($val['title'] == '费用类型') $common['reason'] = $val['value'];
                    if ($val['title'] == '费用金额') $common['sums'] = $val['value'];
                    if (isset($item['value'])) $items[$val['title']] = $val['value'];
                    $common['type'] = 3;
                }
                if ($item['spname'] == '学生年级修改') {
                    if ($val["title"] == "备注") $common['reason'] = $val['value'];
                }
                if (isset($val['value'])) $items[$val['title']] = $val['value'];
            }

            if ($item["spname"] == "拉取数据审批") {
                $info = $this->t_company_wx_approval_data->get_list_for_user_time($common["apply_user_id"], $common["apply_time"]);

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
                    $this->t_company_wx_approval_data->row_insert($data);
                    echo "加载拉取数据审批成功";

                }

            }


            if ($items) $common['item'] = json_encode($item);
            // 添加数据
            $this->t_company_wx_approval->row_insert($common);
            //echo '加载数据成功'.$common['spname'];
        }
    }

    public function pull_approve_data() {
        $config = Config::get_config("company_wx");
        if (!$config) {
            return $this->output_err("没有配置");
        }

        $approv_type  = array_flip(E\Eapprov_type::get_specify_select());

        // 获取token
        $config = Config::get_config("company_wx");
        $url = $config['url'].'/cgi-bin/gettoken?corpid='.$config['CorpID'].'&corpsecret='.$config['Secret2'];
        $token = $this->get_company_wx_data($url, 'access_token'); // 获取tocken

        $start_time = strtotime("-5day");
        $end_time = time();

        $approv = $this->t_company_wx_approval->get_all_info($start_time, $end_time);

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
                $index = $item['apply_user_id'].'-'.$item['apply_time'];
                if ($approv[$index]['sp_status'] != $item['sp_status']) {
                    $this->t_company_wx_approval->field_update_list($approv[$index]['id'], [ // 更改审核状态
                        "sp_status" => $item['sp_status']
                    ]);
                }
                continue;
            }

            $approval_name = implode(',', $item['approval_name']); // 审批人
            $notify_name = implode(',', $item['notify_name']); // 抄送人
            $names = array_merge($item["approval_name"], $item["notify_name"]);
            array_push($names, $item["apply_name"]); // 申请人
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

            if (isset($item['leave'])) { // 处理请假
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
            $this->handle_aprove_type($item, $leave, $approv_type, $common, $names);
        }
       
        return $this->output_succ();
    }

    public function handle_aprove_type($item, $leave, $approv_type, $common, $names) {
        $items = "";
        //初始化
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

            if ($item['spname'] == '费用申请') {
                if ($val['title'] == '费用类型') $common['reason'] = $val['value'];
                if ($val['title'] == '费用金额') $common['sums'] = $val['value'];
                if (isset($item['value'])) $items[$val['title']] = $val['value'];
                $common['type'] = E\Eapproval_type::V_2;
            }

            if ($item['spname'] == '拉取数据审批') {
                if ($val['title'] == '数据描述') $data_desc = $val['value'];
                if ($val["title"] == "数据字段") $data_column = $val["value"];
                if ($val["title"] == "需求原因") $require_reason = $val["value"];
                if ($val['title'] == '需要时间') $require_time = ($val['value'] / 1000);
                $common['type'] = E\Eapproval_type::V_11;
            }

            if ($item['spname'] == '学生年级修改') {
                if ($val["title"] == "备注") $common['reason'] = $val['value'];
                $common['type'] = E\Eapproval_type::V_13;
            }
            if (isset($val['value'])) $items[$val['title']] = $val['value'];
        }

        if ($item["spname"] == "拉取数据审批") {
            $info = $this->t_company_wx_approval_data->get_list_for_user_time($common["apply_user_id"], $common["apply_time"]);

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
                $this->t_company_wx_approval_data->row_insert($data);

                $id = $this->t_company_wx_approval_data->get_last_insertid();
                foreach($names as $name) {
                    $userid = $this->t_company_wx_users->get_userid_for_name($name);
                    $did = $this->t_company_wx_approval_notify->get_list_for_user_id($id,$userid);
                    if (!$did) {
                        $this->t_company_wx_approval_notify->row_insert([
                            "d_id" => $id,
                            "user_id" => $userid
                        ]);
                    }
                }

                echo "加载拉取数据审批成功";

            }

        }

        if ($items) $common['item'] = json_encode($item);
        $this->t_company_wx_approval->row_insert($common);
    }

    public function get_company_wx_data($url, $index='') { //根据不同路由获取不同的数据 (企业微信)
        $info = file_get_contents($url);
        $info = json_decode($info, true);
        if ($index && isset($info[$index])) {
            return $info[$index];
        }
        return $info;
    }

    public function show_approv_data_self() {
        $adminid = $this->get_account_id();
        $phone = $this->t_manager_info->get_phone($adminid);
        $userid = $this->t_company_wx_users->get_userid_for_adminid($phone);
        if ($userid) {
            $this->set_in_value("userid", $userid);
            return $this->show_approv_data();
        } else {
            exit("您企业微信中的手机号与后台不一致，请联系后台管理员更改手机号");
        }
    }

    public function show_approv_data() {
        $userid = $this->get_in_int_val("userid", -1);
        $flag = false;
        if ($userid == -1) $flag = true;
        $info = $this->t_company_wx_approval_data->get_all_list($userid);
        foreach($info as &$item) {
            $item["apply_time"] = date("Y-m-d H:i:s", $item["apply_time"]);
            $item['require_time'] = date("Y-m-d H:i:s", $item["require_time"]);
        }
        return $this->pageView(__METHOD__, '', [
            'info' => $info,
            "flag" => $flag
        ],[
            'qiniu_upload_domain_url' =>Config::get_qiniu_public_url()."/"
        ]);
    }

    public function update_approval_data_url() {
        $id = $this->get_in_int_val("id");
        $data_url = $this->get_in_str_val("data_url");
        if (!$data_url) {
            return $this->output_err("数据下载地址不能为空");
        }
        $acc = $this->get_account();
        $this->t_company_wx_approval_data->field_update_list($id, [
            "acc" => $acc,
            "data_url" => $data_url
        ]);
        return $this->output_succ();
    }

    public function update_approval_page_url() {
        $id = $this->get_in_int_val("id");
        $page_url = $this->get_in_str_val("page_url");
        if (!$page_url) {
            return $this->output_err("数据页面地址不能为空");
        }
        $acc = $this->get_account();
        $this->t_company_wx_approval_data->field_update_list($id, [
            "acc" => $acc,
            "page_url" => $page_url
        ]);
        return $this->output_succ();
    }


    public function show_approv() {
        list($start_time, $end_time) = $this->get_in_date_range_day(0);
        $info = $this->t_company_wx_approval->get_all_list($start_time, $end_time);
        foreach($info as &$item) {
            $item['flag'] = false;
            $item['apply_time_str'] = date('Y-m-d H:i:s', $item['apply_time']);
            $item['start_time_str'] = '';
            $item['end_time_str'] = '';
            if ($item['start_time']) $item['start_time_str'] = date('Y-m-d H:i:s', $item['start_time']);
            if ($item['end_time']) $item['end_time_str'] = date('Y-m-d H:i:s', $item['end_time']);
            //：1审批中；2 已通过；3已驳回；4已取消；6通过后撤销；10已支付
            
            if ($item['sp_status'] == 1) {
                $item['sp_status_str'] = '审批中';
            } elseif ($item['sp_status'] == 2) {
                $item['sp_status_str'] = '已通过';
            } elseif ($item['sp_status'] == 3) {
                $item['sp_status_str'] = '已驳回';
            } elseif ($item['sp_status'] == 4) {
                $item['sp_status_str'] = '已取消';
            } elseif ($item['sp_status'] == 6) {
                $item['sp_status_str'] = '通过后撤销';
            } elseif ($item['sp_status'] == 10) {
                $item['sp_status_str'] == '已支付';
            }
            if ($item["type"] == 13 && $item["sp_status"] == 2) {
                $item['flag'] = true;
            }
        }

        return $this->pageView(__METHOD__, '', [
            'info' => $info,
        ]);
    }

    public function get_approve_detail() {
        $id = $this->get_in_str_val("id", 0);
        $info = $this->t_company_wx_approval->field_get_list($id, "id,spname as '审批名',apply_name as '申请人',apply_time as '申请时间',reason as '申请原因',item, type");

        // 处理申请时间
        $info['申请时间'] = date("Y-m-d H:i:s", $info["申请时间"]);

        // 处理item
        if ($info['item']) {
            $data = json_decode($info['item'], true);
            if ($data) {
                foreach($data as $key => $item) {
                    $info[$key] = $item;
                }
            }
            unset($info['item']);
        }
        return $this->output_succ(['data' => $info]);
    }

    public function update_approval() {
        $grade = array_flip(E\Egrade::get_specify_select());
        $id = $this->get_in_str_val("id", 0);
        $type = $this->get_in_str_val("type", 0);

        if ($type == 13) { // 修改学生年级
            $info = $this->t_company_wx_approval->get_item($id);
            $data = json_decode($info, true);
            $phone = $data["学生电话"];
            $grade_before = $grade[$data["修改前年级"]];
            $grade_after = $grade[$data["修改后年级"]];

            $stu = $this->t_student_info->get_grade_info($phone);
            if (!$stu) {
                return $this->output_err("查无数据");
            }

            if ($stu["grade"] == $grade_after) {
                return $this->output_err("年级已修改，无需修改");
            }

            $this->t_student_info->field_update_list($stu["userid"], [
                "grade" => $grade_after
            ]);
        }

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

        foreach($users as $key => $item) {
            $power = '';
            if ($item['power']) $power = '('.$item['power'].')';
            $name = $item['position'].'-'.$item['name'].$power;
            if ($item['isleader'] == 1) {
                $name .= '(领导)';
            }
            $users[$key]['name'] = $name;
            
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
                    $power_s .= $val.'-'.$group[$val].',';
                }
                $item['leader_power'] = substr($power_s,0,-1);
            }
            // no_leader_power
            if ($item['no_leader_power']) {
                $power = explode(',', $item['no_leader_power']);
                $power_s = '';
                foreach($power as $val) {
                    $power_s .= $val.'-'.$group[$val].',';
                }
                $item['no_leader_power'] = substr($power_s,0,-1);
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
        $old_permission = $this-> get_in_str_val('old_permission');
        $adminid = session('adminid');
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

        $adminid = session('adminid');
        $type = 1;
        $old = $old_permission;
        $new = $permission;
        $this->t_seller_edit_log->row_insert([
            "adminid"     => $adminid,
            "type"        => $type,
            "old"         => $old,
            "new"         => $new,
            "create_time" => time(NULL),
        ],false,false,true );
        return $this->output_succ();
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

    public function flush_company_wx_data_log() { // 数据添加至redis 参看 app/Jobs/update_company_wx_data.php
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
        foreach($users as $item) {
            $key = $item['mobile'];
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
        $phone = $this->get_in_str_val('phone');
        $info = $this->t_manager_info->get_phone_by_name($name);
        if ($info) { 
            $this->t_manager_info->field_update_list($info['uid'], [
                'phone' => $phone
            ]);

            // 添加操作日志
            $this->t_user_log->add_data("企业微信与后台管理手机号不一致,修改手机号,修改前: ".$info['phone'].' 修改后: '.$phone, $info['uid']);
            return $this->output_succ();
        }
        return $this->output_err('管理后台无此账号');
    }

    // 下载日志
    public function download_log() {
        $account = $this->get_account();
        $this->t_user_log->add_data($account.":拉取数据审批页下载数据");
        return $this->output_succ();
    }

    public function add_warn_revisit_record() { // 添加退费预警回访
        $userid = $this->get_in_str_val("userid");
        $revisit_type = $this->get_in_str_val("revisit_type");
        $revisit_person = $this->get_in_str_val("revisit_person");
        $revisit_path = $this->get_in_str_val("revisit_path");
        $operator_note = $this->get_in_str_val("operator_note");
        $is_over = $this->get_in_str_val("is_over");
        $sys_operator = $this->get_account();

        $this->t_revisit_info->row_insert([
            "userid" => $userid,
            "revisit_type" => $revisit_type,
            "revisit_person" => $revisit_person,
            "revisit_path" => $revisit_path,
            "operator_note" => $operator_note,
            "sys_operator" => $sys_operator,
            "revisit_time" => time()
        ]);

        $refund = $this->t_student_info->field_get_list($userid, "refund_warning_count");
        $count = $refund["refund_warning_count"] + 1;
        if ($is_over == 0) {
            $this->t_student_info->field_update_list($userid, [
                "refund_warning_level" => 0,
                "refund_warning_count" => $count
            ]);
        } else {
            $this->t_student_info->field_update_list($userid, [
                "refund_warning_count" => $count
            ]);
        }

        $this->t_user_log->add_data($sys_operator."操作退费预警回访,userid:".$userid." 回访时间:".date("Y-m-d H:i:s", time()));
        return $this->output_succ();
    }

}