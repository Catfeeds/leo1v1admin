<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;
use \App\Helper\Config;
//require_once (app_path("Libs/qiniu-7/src/Qiniu/functions.php") );


class agent_info extends Controller
{
    use CacheNick;
    use TeaPower;
    var $check_login_flag=true;

    function __construct( )  {
        parent::__construct();
    }

    function check_login() {
        if (!session("aid")){
            if (!\App\Helper\Utils::check_env_is_test()) {
                \App\Helper\Utils::logger("GOTO: " .$_SERVER["REQUEST_URI"] );
                header('Location: /login/agent?to_url='.$_SERVER["REQUEST_URI"]);
                exit;
            }else{
            }
        }
    }

    public function index() {
        return self::get_agent_group_list();
    }

    public function get_agent_group_list() {
        $agentid   = $this->get_login_agent();
        $page_info=$this->get_in_page_info();
        $ret_info = $this->t_agent_group->get_agent_group_list($agentid,$page_info);
        foreach ($ret_info["list"] as &$item ) {
            \App\Helper\Utils::unixtime2date_for_item($item,"create_time");
            //获取每个团的成员个数
            $item['member_num'] = $this->t_agent_group_members->get_member_num($item['group_id']);
        }
        return $this->pageView(__METHOD__,$ret_info);
        
    }
    //@desn:建立优学优享团
    public function create_group(){
        $agentid   = $this->get_login_agent();
        $group_name = $this->get_in_str_val('group_name');
        $member_arr = [];
        $check_repet = [];
        //循环获取成员电话
        for($x=1;$x<=10;$x++){
            $member_phone = $this->get_in_str_val('member'.$x);
            if(!empty($member_phone)){
                //检测团员有效性
                $check_arr = $this->check_member($member_phone);
                if($check_arr['ret'] != 0)
                    return $this->output_err($member_phone.$check_arr['info']);
                if(isset($check_repet[$member_phone]))
                    return $this->output_err('成员'.$member_phone.'重复了!');

                $check_repet[$member_phone] = true;
                
                $member_arr[] = $member_phone;
            }
        }
        //判断团长原来如果在团队中，将他从原来团队删除
        $is_member_flag = $this->t_agent_group_members->get_is_member($agentid);
        if($is_member_flag)
            $this->t_agent_group_members->row_delete($is_member_flag);

        $empty_flag = 0;
        $member_count = 0;
        //判断成员数量  [大于10人]
        if($member_arr)
            $member_count = count($member_arr);
        else
            $empty_flag = 1;

        if($member_count < 10 || $empty_flag == 1)
            return $this->output_err("建团最小人数为10!");
        
        $ret = $this->t_agent_group->row_insert([
            "group_name" =>  $group_name ,
            "create_time" => time(NULL) ,
            "colconel_agent_id" => $agentid ,
        ]);

        if($ret){
            $group_id = $this->t_agent_group->get_last_insertid();
        }
        
        //循环插入数据库
        foreach($member_arr as $phone){
            //获取成员id
            $member_id = $this->t_agent->get_agentid_by_phone($phone);

            $this->t_agent_group_members->row_insert([
                'group_id' => $group_id,
                'agent_id' => $member_id,
                'add_time' => time(NULL),
            ]);
        }

        return $this->output_succ();
    }

    //@desn:检验团员的有效性
    //@param:$phone 检测手机号
    //@param:$colconel_id 团长id
    public function check_member($phone){
        $agentid   = $this->get_login_agent();
        if(!preg_match('/^1[3|4|5|8][0-9]\d{4,8}$/',$phone))
            return ['ret'=>1,'info'=>'手机号码有误!'];
        //检测该用户是否为团长的邀请人
        $invite_flag = $this->t_agent->check_is_invite($phone,$agentid);

        if(!$invite_flag)
            return ['ret'=>2,'info'=>'用户不是你邀请的，无法组团!'];
        //检测团员是否为会员
        $member_flag = $this->t_agent->check_is_member($phone);
        if(!$member_flag)
            return ['ret'=>5,'info'=>'组员身份必须为会员!'];
        //团长不能入团
        $is_colconel = $this->t_agent->get_agentid_by_phone($phone);
        if($is_colconel == $agentid)
            return ['ret'=>4,'info'=>'团长不能称为组员!'];
        //判断改团员是否已在团中
        $is_in_flag = $this->t_agent_group_members->check_is_in($phone);
        if($is_in_flag)
            return ['ret'=>3,'info'=>'用户已在团中了!'];
        else
            return ['ret'=>0,'info'=>'检测通过!'];

    }
    //@desn:修改优学优享团名称
    public function update_group_name(){
        $group_id = $this->get_in_int_val('group_id');
        $group_name = $this->get_in_str_val('group_name');
        $this->t_agent_group->field_update_list($group_id,[
            "group_name" => $group_name,
        ]);
        return $this->output_succ();
    }
    //@desn:添加优学优享团团员
    public function add_member(){
        $agentid   = $this->get_login_agent();
        $group_id = $this->get_in_int_val('group_id');
        $member_phone = $this->get_in_str_val('member_phone');
        //检测团员有效性
        $check_arr = $this->check_member($member_phone);
        if($check_arr['ret'] != 0)
            return $this->output_err($member_phone.$check_arr['info']);
        //获取成员id
        $member_id = $this->t_agent->get_agentid_by_phone($member_phone);
        $this->t_agent_group_members->row_insert([
            'group_id' => $group_id,
            'agent_id' => $member_id,
            'add_time' => time(NULL),
        ]);

        return $this->output_succ();
    }

    //@desn:团队明细
    public function group_info(){
        $colconel_agent_id   = $this->get_login_agent();
        $group_info =$this->t_agent_group_members->get_group_info($colconel_agent_id);
        //团长业绩[一级]
        $this_test_lesson_count = $this->t_agent->get_this_colconel_test_lesson_count($colconel_agent_id);
        $this_invite_count = $this->t_agent->get_this_colconel_invite_count($colconel_agent_id);
        $this_order_info = $this->t_agent_order->get_this_colconel_order_info($colconel_agent_id);
        $colconel_statistics = [
            'colconel_id' => $this_test_lesson_count['colconel_id'],
            'colconel_name' => $this_test_lesson_count['colconel_name'],
            'test_lesson_count' => $this_test_lesson_count['test_lesson_count'],
            'member_count' => $this_invite_count['member_count'],
            'student_count' => $this_invite_count['student_count'],
            'order_count' => $this_order_info['order_count'],
            'order_money' => $this_order_info['order_money']/100,
        ];
        // $colconel_statistics = $this->t_agent->get_colconel_statistics($colconel_agent_id);
        $colconel_info = $colconel_statistics;
        //将团员的业绩加上团长的业绩
        foreach($group_info as &$item){
            $colconel_statistics['student_count'] += $item['student_count'];
            $colconel_statistics['member_count'] += $item['member_count'];
            $colconel_statistics['test_lesson_count'] += $item['test_lesson_count'];
            $colconel_statistics['order_count'] += $item['order_count'];
            $colconel_statistics['order_money'] += $item['order_money'];
            $item['order_money'] /= 100;
        }

        $colconel_info['order_money'] /= 100;
        $colconel_statistics['order_money'] /= 100;

        return $this->pageView(__METHOD__,'',[
            'colconel_statistics' => $colconel_statistics,
            'group_info' => $group_info,
            'colconel_info' => $colconel_info
        ]);
    }
    //@desn:团员明细
    public function members_info(){
        $group_id= $this->get_in_int_val("group_id");
        $colconel_agent_id   = $this->get_login_agent();
        $page_info=$this->get_in_page_info();
        $ret_info = $this->t_agent_group_members->get_members_info($colconel_agent_id,$page_info,$group_id);
        foreach($ret_info['list'] as &$item){
            $item['cycle_order_money'] = $item['cycle_order_money']/100;
        }
        $group_list = $this->t_agent_group->get_group_list($colconel_agent_id);
        return $this->pageView(__METHOD__,$ret_info,[
            'group_list' => $group_list,
        ]);
    }
    //前端ajax检测邀请团员是否通过
    public function check_phone(){
        $phone = $this->get_in_str_val("phone");
        $flag = $this->get_in_int_val("flag");
        $result = $this->check_member($phone);
        $result['flag'] = $flag;
        return $result;
    }
}
