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
    static $tea_todo_reminder = 'rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o';

    /**
     * 模板ID   : 9glANaJcn7XATXo0fr86ifu0MEjfegz9Vl_zkB2nCjQ
     * {{first.DATA}}
     * 评估内容：{{keyword1.DATA}}
     * 评估结果：{{keyword2.DATA}}
     * 日期：{{keyword3.DATA}}
     * {{remark.DATA}}
     */
    static $tea_first_test = '9glANaJcn7XATXo0fr86ifu0MEjfegz9Vl_zkB2nCjQ';


    /**
     * 模板ID : 1FahTQqlGwCu1caY9wHCuBQXPOPKETuG_EGRNYU89II
     * 标题   : 入职邀请通知
     * {{first.DATA}}
     * 职位名称：{{keyword1.DATA}}
     * 公司名称：{{keyword2.DATA}}
     * 入职时间：{{keyword3.DATA}}
     * {{remark.DATA}}
     */
    static $tea_offer = '1FahTQqlGwCu1caY9wHCuBQXPOPKETuG_EGRNYU89II';

    /**
     * 模板ID : bW8mP8cCxszBrM2qLBIlj0MOsGgTGwQtWbvoGYhhGtw
     * 标题　：课程反馈通知
     * {{first.DATA}}
     * 课程名称：{{keyword1.DATA}}
     * 课程时间：{{keyword2.DATA}}
     * 学生姓名：{{keyword3.DATA}}
     * {{remark.DATA}}
     * xx:xx的xx课xx老师已经提交了课程评价
     * 课程名称：{课程名称}
     * 课程时间：xx-xx xx:xx~xx:xx
     * 学生姓名：xxx
     * 可登录学生端查看详情，谢谢！
    */
    static $tea_lesson_evaluate = 'bW8mP8cCxszBrM2qLBIlj0MOsGgTGwQtWbvoGYhhGtw';

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

    //老师 试听课提醒
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

    //老师　模拟试听提醒
    static public function template_tea_simulation_tip($wx_openid, $flag=true){
        $data=[];
        // $template_id      = "rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o";
        $template_id      = WxSendMsg::$tea_todo_reminder;
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

    //老师　入职通知
    static public function template_tea_offer_tip($teacher_info,$level_str){
        // $template_id      = "1FahTQqlGwCu1caY9wHCuBQXPOPKETuG_EGRNYU89II";
        $template_id      = WxSendMsg::$tea_offer;
        $data["first"]    = "老师您好，恭喜你已经通过理优入职培训，成为理优正式授课老师，等级为：".$level_str;
        $data["keyword1"] = "教职老师";
        $data["keyword2"] = "理优教育";
        $data["keyword3"] = date("Y年m月d日",time());
        $data["remark"]   = "愿老师您与我们一起以春风化雨的精神,打造高品质教学服务,助我们理优学子更上一层楼。";
        $offer_url        = "http://admin.leo1v1.com/common/show_offer_html?teacherid=".$teacher_info["teacherid"];
        self::send_teacher_msg_for_wx($teacher_info['wx_openid'],$template_id,$data,$offer_url);

    }

    //老师　课堂反馈
    static public function template_tea_lesson_evaluate($teacher_info, $subject_str){
        $template_id      = WxSendMsg::$tea_lesson_evaluate;
        $data = [
            "first" => date('m月d日 H:i:s',$teacher_info['lesson_start'])."的".$subject_str."课".$teacher_info['tea_nick']."老师已提交了课程评价",
            "keyword1" => $subject_str,
            "keyword2" => date('m月d日 H:i')." ~ ".date('H:i'),
            "keyword3" => $teacher_info['stu_nick'],
            "remark"   => '可登录学生端查看详情，谢谢！',
        ];
        $url = '';
        self::send_teacher_msg_for_wx($teacher_info['wx_openid'],$template_id, $data,$url);

    }

    //老师　初试评估
    static public function template_tea_first_test_tip($wx_openid, $status){
        $template_id      = WxSendMsg::$tea_first_test;
        $data=[];
        if($status==1){
            $data['first']="老师您好，恭喜您已经成功通过初试。";
            $data['keyword1']="初试结果";
            $data['keyword2']="通过";
            $data['keyword3']=date("Y年m月d日 H:i:s");
            $data['remark']="后续将有HR和您联系，请保持电话畅通。";
        }elseif($status==2){
            $data['first']="老师您好，很抱歉您没有通过试讲审核。";
            $data['keyword1']="初试结果";
            $data['keyword2']="未通过";
            $data['keyword3']=date("Y年m月d日 H:i:s");
            $data['remark']="感谢您的投递，您的简历已进入我公司的简历库，如有需要我们会与您取得联系。";
        }elseif($status==3){
            $data['first']="老师您好，您的试讲审核结果未可重审";
            $data['keyword1']="初试结果";
            $data['keyword2']="可重审,您可以再次提交试讲视频";
            $data['keyword3']=date("Y年m月d日 H:i:s");
            $data['remark']="感谢您的投递，您的简历已进入我公司的简历库，如有需要我们会与您取得联系。";
        }

        $url = '';
        self::send_teacher_msg_for_wx($wx_openid,$template_id, $data,$url);

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
