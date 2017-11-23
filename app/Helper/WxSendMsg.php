<?php
namespace App\Helper;

class WxSendMsg{

    /**
     * 模板ID   : rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o
     * 标题课程 : 待办事项提醒
     * {{first.DATA}}
     * 待办主题：{{keyword1.DATA}}
     * 待办内容：{{keyword2.DATA}}
     * 日期：{{keyword3.DATA}}
     * {{remark.DATA}}
     */
    static $todo_reminder = 'rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o';

    public function __construct() {
    }

    static public function get_app_id_secret($type='yxyx'){
        $arr = [];
        if ($type == 'yxyx'){
            $arr['appid']     = \App\Helper\Config::get_wx_appid();
            $arr['appsecret'] = \App\Helper\Config::get_wx_appsecret();
        } else if ($type == 'teacher') {
            $arr['appid']     = \App\Helper\Config::get_teacher_wx_appid();
            $arr['appsecret'] = \App\Helper\Config::get_teacher_wx_appsecret();
        } else if ($type == 'online'){
            // $arr['appid']     = \App\Helper\Config::get_teacher_wx_appid();
            // $arr['appsecret'] = \App\Helper\Config::get_teacher_wx_appsecret();
        }
        return $arr;
    }

    //试听课老师提醒
    static public function template_tea_test_lesson_tip($teacherid,$nick, $lesson_time_str, $require_phone, $demand,$bgk_arr = []){
        //$bgk_arr备用数组
        $task = new  \App\Console\Tasks\TaskController();
        $wx_openid = $task->t_teacher_info->get_wx_openid($teacherid);

        if($wx_openid!='') {

            $template_id      = "rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o";
            $data['first']    = $nick."同学的试听课已排好，请尽快完成课前准备工作";
            $data['keyword1'] = "备课通知";
            $data['keyword2'] = "\n上课时间：$lesson_time_str "
                              ."\n教务电话：$require_phone"
                              ."\n试听需求：$demand"
                              ."\n1、请及时确认试听需求并备课"
                              ."\n2、请尽快上传教师讲义、学生讲义（用于学生预习）和作业"
                              ."\n3、老师可提前15分钟进入课堂进行上课准备";
            $data['keyword3'] = date("Y-m-d H:i",time());
            $data['remark']   = "";
            $url = "http://www.leo1v1.com/login/teacher";

            self::send_teacher_msg_for_wx($wx_openid,$template_id,$data,$url);

        }
    }

    //模拟试听提醒
    static public function template_tea_simulation_tip($wx_openid, $flag=true){
        $data=[];
        // $template_id      = "rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o";
        $template_id      = WxSendMsg::$todo_reminder;
        $data['first']    = "请尽快登录老师后台完成模拟试听";
        $data['keyword1'] = "模拟试听";
        if($flag == true){
            $data['keyword2'] = "尽快登录老师后台,选择模拟试听时间";
        } else {
            $data['keyword2'] = "老师您好,很抱歉您的授课视频因数据不完整导致无法成功上传,请老师重新录制课程,期待老师的课程";
        }
        $data['keyword3'] = date("Y-m-d H:i",time());
        $data['remark']   = "通过模拟试听即可获得晋升，理优教育致力于打造高水平的教学服务团队，期待您能通过审核，加油！";
        $url = "";

        self::send_teacher_msg_for_wx($wx_openid,$template_id,$data,$url);

    }

    static public function send_teacher_msg_for_wx($openid,$template_id,$data,$url=""){

        $app = self::get_app_id_secret('teacher');
        $teacher_wx = new \App\Helper\Wx($app['appid'],$app['appsecret']);

        $is_success = $teacher_wx->send_template_msg($openid,$template_id,$data,$url);

        $task = new  \App\Console\Tasks\TaskController();
        $task->t_weixin_msg->row_insert([
            "userid"      => 0,
            "openid"      => $openid,
            "send_time"   => time(),
            "templateid"  => $template_id,
            "title"       => "",
            "notify_data" => json_encode($data),
            "notify_url"  => $url,
            "is_success"  => $is_success?1:0,
        ]);
    }

};
