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
     * 模板ID   : J57C9QLB-K3SeKgIwdvBMz1RfjUinhwWsN3lEM-Xo5o
     * 待定模板----调课结果待定,老师
     * {{first.DATA}}
     * {{keyword1.DATA}}
     * {{keyword2.DATA}}
     * {{keyword3.DATA}}
     * {{remark.DATA}}
     */
    static $tea_change_lesson_wait_pass = 'J57C9QLB-K3SeKgIwdvBMz1RfjUinhwWsN3lEM-Xo5o';

    /**
     * 模板ID   : Wch1WZWbJvIckNJ8kA9r7v72nZeXlHM2cGFNLevfAQI
     * 待定模板----调课结果待定,家长
     * {{first.DATA}}
     * 课程名称：{{keyword1.DATA}}
     * 课程时间：{{keyword2.DATA}}
     * 学生姓名：{{keyword3.DATA}}
     * {{remark.DATA}}
     */
    static $par_change_lesson_wait_pass = 'Wch1WZWbJvIckNJ8kA9r7v72nZeXlHM2cGFNLevfAQI';


    /**
     * 模板ID   : 9glANaJcn7XATXo0fr86ifu0MEjfegz9Vl_zkB2nCjQ
     * 评估结果通知
     * {{first.DATA}}
     * 评估内容：{{keyword1.DATA}}
     * 评估结果：{{keyword2.DATA}}
     * 日期：{{keyword3.DATA}}
     * {{remark.DATA}}
     */
    static $tea_assess = '9glANaJcn7XATXo0fr86ifu0MEjfegz9Vl_zkB2nCjQ';


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
     * 模板ID : eHa4a9BoAbEycjIYSakPHx7zkqXDLoHbwEy6HDj4Gb4
     * 标题   : 课程取消通知
     * {{first.DATA}}
     * 课程类型：{{keyword1.DATA}}
     * 上课时间：{{keyword2.DATA}}
     * {{remark.DATA}}
     */
    static $tea_lesson_cancel = 'eHa4a9BoAbEycjIYSakPHx7zkqXDLoHbwEy6HDj4Gb4';

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

    /**
     * 模板ID   : E9JWlTQUKVWXmUUJq_hvXrGT3gUvFLN6CjYE1gzlSY0
     * 标题课程 : 等级升级通知
     * {{first.DATA}}
     * 用户昵称：{{keyword1.DATA}}
     * 最新等级：{{keyword2.DATA}}
     * 生效时间：{{keyword3.DATA}}
     * {{remark.DATA}}
     */
    static $tea_up_level = 'E9JWlTQUKVWXmUUJq_hvXrGT3gUvFLN6CjYE1gzlSY0';

    /**
     * 模板ID : n8m_rkyP_hhDxPGxG1yzOfcoRX1t017RQtiyY9_8TDc
     * 标题   :课程冻结通知
     * {{first.DATA}}
     * 课程名称：{{keyword1.DATA}}
     * 操作时间：{{keyword2.DATA}}
     * {{remark.DATA}}
     */
    static $tea_lesson_frozen = 'n8m_rkyP_hhDxPGxG1yzOfcoRX1t017RQtiyY9_8TDc';

    /**
     * 模板ID   : gFBAryMgP7IAeLEIKJgui4JaMCHrBSGHm2wrvQP2EcA
     * 标题课程 : 解冻通知
     * {{first.DATA}}
     * 课程名称：{{keyword1.DATA}}
     * 操作时间：{{keyword2.DATA}}
     * {{remark.DATA}}
     */
    static $tea_lesson_thaw = 'gFBAryMgP7IAeLEIKJgui4JaMCHrBSGHm2wrvQP2EcA';

    /**
     * 模板ID   : Fw49ejW84qSRcXFFCrHsiCY3Lz6H57IeZZ_gVRdq9TM
     * 标题课程 : 资料领取通知
     * {{first.DATA}}
     *用户姓名：{{keyword1.DATA}}
     *资料名称：{{keyword2.DATA}}
     *{{remark.DATA}}
     */
    static $tea_get_resource = 'Fw49ejW84qSRcXFFCrHsiCY3Lz6H57IeZZ_gVRdq9TM';

    /**
     * 模板ID   : r6WBVhVRG8tYS_4RGVwKbPcDPVBrMtOCBqfU87sdvdE
     * 老师投诉通知
     * {{first.DATA}}
     *{{keyword1.DATA}}
     *{{keyword2.DATA}}
     *{{keyword3.DATA}}
     */
    static $tea_complaint = "r6WBVhVRG8tYS_4RGVwKbPcDPVBrMtOCBqfU87sdvdE";


    /**
     * 模板ID : kvkJPCc9t5LDc8sl0ll0imEWK7IGD1NrFKAiVSMwGwc
     * 标题   : 反馈进度通知
     * {{first.DATA}}
     * 反馈内容：{{keyword1.DATA}}
     * 处理结果：{{keyword2.DATA}}
     * {{remark.DATA}}
     */
    static $tea_complaint_process = "kvkJPCc9t5LDc8sl0ll0imEWK7IGD1NrFKAiVSMwGwc";

    /**
     * 模板ID   : 9MXYC2KhG9bsIVl16cJgXFVsI35hIqffpSlSJFYckRU
     * 待处理通知
     * {{first.DATA}}
     *{{keyword1.DATA}}
     *{{keyword2.DATA}}
     *{{keyword3.DATA}}
     */
    static $wait_deal = "9MXYC2KhG9bsIVl16cJgXFVsI35hIqffpSlSJFYckRU";

    /**
     * 模板ID   : 8GYohyn1V6dmhuEB6ZQauz5ZtmqnnJFy-ETM8yesU3I
     * 投诉结果通知
     * {{first.DATA}}
     * {{keyword1.DATA}}
     * {{keyword2.DATA}}
     * {{keyword3.DATA}}
     */
    static $complaint_res = "8GYohyn1V6dmhuEB6ZQauz5ZtmqnnJFy-ETM8yesU3I";

    //反馈QC与上级领导
    /**
       tK_q5C8q1Iqp7qY2KXKuRQ6-jvlj59Kc8ddB4chIstI
       反馈投诉结果通知
       {{first.DATA}}
       反馈者：{{keyword1.DATA}}
       反馈类型：{{keyword2.DATA}}
       反馈时间：{{keyword3.DATA}}
       问题描述：{{keyword4.DATA}}
       处理结果：{{keyword5.DATA}}
       {{remark.DATA}}
    **/
    static $qc_complaint_res = "tK_q5C8q1Iqp7qY2KXKuRQ6-jvlj59Kc8ddB4chIstI";


    public function __construct() {
    }

    //老师 待办事项提醒
    static public function template_tea_tip($wx_openid,$first, $keyword1, $keyword2,$keyword3,$remark,$url){
        $template_id      = WxSendMsg::$tea_todo_reminder;
        $data['first']    = $first;
        $data['keyword1'] = $keyword1;
        $data['keyword2'] = $keyword2;
        $data['keyword3'] = $keyword3;
        $data['remark']   = $remark;
        self::wx_send_to_teacher($wx_openid,$template_id,$data,$url);
    }

    //老师　面试通知
    static public function template_tea_face_test($wx_openid,$name,$time,$phone,$grader,$subject){
        $template_id      = WxSendMsg::$tea_todo_reminder;
        $data['first']    = $name."老师您好,您的面试课程已排好";
        $data['keyword1'] = "1对1面试课程";
        $data['keyword2'] = "\n面试时间：$time "
                          ."\n面试账号：$phone"
                          ."\n面试密码：123456"
                          ."\n年级科目 : ".$grade." ".$subject;
        $data['keyword3'] = date("Y-m-d H:i",time());
        $data['remark']   = "请查阅邮件(报名时填写的邮箱),准备好耳机和话筒,并在面试开始前5分钟进入软件,理优教育致力于打造高水平的教学服务团队,期待您的加入,加油!";

        self::wx_send_to_teacher($wx_openid,$template_id,$data);
    }

    //老师　邀请参训通知(原来在job中，通知为通过测试的老师，人数较多用job)
    static public function template_tea_train($wx_openid){
        $template_id      = WxSendMsg::$tea_todo_reminder;
        $data['first']    = "老师您好！";
        $data['keyword1'] = "邀请参训通知";
        $data['keyword2'] = "经系统核查您试讲通过多日培训未通过，为方便老师尽快完成入职手续，敬请您参加定于每周三、五19点的新师培训；若时间冲突，您可登录老师端，在【我的培训】中观看回放后，点击【自我测评】回答，通过后即收到【入职offer】，另请您在【后台】尽快设置【模拟课程时间】，通过后即成功晋升。";
        $data['keyword3'] = date("Y-m-d",time());
        $data['remark']   = "如有任何疑问可在新师培训群：315540732咨询【师训】沈老师。";

        self::wx_send_to_teacher($wx_openid,$template_id,$data);
    }

    //老师 试听课提醒
    static public function template_tea_test_lesson_tip($teacherid,$nick, $lesson_time_str, $require_phone, $demand){
        $task = new  \App\Console\Tasks\TaskController();
        $wx_openid = $task->t_teacher_info->get_wx_openid($teacherid);

        if($wx_openid!='') {
            $template_id      = WxSendMsg::$tea_todo_reminder;
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

            self::wx_send_to_teacher($wx_openid,$template_id,$data,$url);
        }
    }

    //老师 试讲结果通知
    static public function template_tea_pass($wx_openid,$flag,$phone='',$content=''){
        $template_id      = WxSendMsg::$tea_todo_reminder;
        if($flag == true){//通过
            $data['first']    = "老师您好,恭喜您已经成功通过试讲";
            $data['keyword1'] = "通过";
            $data['keyword2'] = "\n账号:".$phone
                              ."\n密码:leo+手机号后4位"
                              ."\n新师培训群号：315540732"
                              ."\n请在【我的培训】或【培训课程】中查看培训课程,每周我们都会组织新入职老师的培训,帮助各位老师熟悉使用软件,提高教学技能,请您准时参加,培训通过后我们会及时给您安排试听课";
            $data['keyword3'] = date("Y-m-d H:i",time());
            $data['remark']   = "理优期待与你一起共同进步,提供高质量教学品质";
            $url="https://jq.qq.com/?_wv=1027&k=4Bik1eq";
        } else {//没通过
            $data['first']    = "老师您好,通过评审老师的1对1面试,很抱歉您没有通过面试审核,希望您再接再厉";
            $data['keyword1'] = "未通过";
            $data['keyword2'] = "\n您的面试反馈情况是".$record_info
                              ."\n如果对于面试结果有疑问，请添加试讲答疑2群，群号：26592743";
            $data['keyword3'] = date("Y-m-d H:i",time());
            $data['remark']   = "理优教育致力于打造高水平的教学服务团队,期待您能通过下次面试,加油!如对面试结果有疑问,请联系招聘老师";
            $url="https://jq.qq.com/?_wv=1027&k=4BiqfPA";
        }

        self::wx_send_to_teacher($wx_openid,$template_id,$data,$url);
    }

    //老师　模拟试听提醒
    static public function template_tea_simulation_tip($wx_openid, $flag=true){
        $data=[];
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

        self::wx_send_to_teacher($wx_openid,$template_id,$data);

    }

    //老师　模拟试听结果通知
    static public function template_tea_simulation_result($wx_openid, $info){
        $template_id      = WxSendMsg::$tea_todo_reminder;
        $data['first']    = "老师您好，很抱歉您没有通过模拟试听，希望您再接再厉。";
        $data['keyword1'] = $info['record_info'];
        $data['keyword2'] = $info['keyword2'];
        $data['keyword3'] = date("Y-m-d H:i:s");
        $data['remark'] = "请重新提交模拟试听时间，理优教育致力于打造高水平的教学服务团队，期待您能通过下次模拟试听，加油！";
        $url = "http://admin.leo1v1.com/common/teacher_record_detail_info?id=".$info['id'];
        self::wx_send_to_teacher($wx_openid,$template_id,$data,$url);
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
        self::wx_send_to_teacher($teacher_info['wx_openid'],$template_id,$data,$offer_url);

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
        self::wx_send_to_teacher($teacher_info['wx_openid'],$template_id, $data);

    }

    //老师　初试评估
    static public function template_tea_first_test_tip($wx_openid, $status){
        $template_id      = WxSendMsg::$tea_assess;
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

        self::wx_send_to_teacher($wx_openid,$template_id, $data);

    }

    //老师　晋升驳回通知
    static public function template_tea_up_level_refuse($wx_openid, $name,$content){
        $template_id      = WxSendMsg::$tea_assess;
        $data['first']    = "晋升申请驳回";
        $data['keyword1'] = $name."未能通过晋升申请";
        $data['keyword2'] = $content;
        $data['keyword3'] = date("Y-m-d H:i",time());
        $data['remark']   = "";
        self::wx_send_to_teacher($wx_openid,$template_id, $data);

    }

    //老师　课程异常 抽查评估
    static public function template_tea_lesson_check($wx_openid, $str){
        $template_id = WxSendMsg::$tea_assess;
        $data=[];
        $data['first']    = "老师你好，近期我们对您的课程进行了听课抽查，课程的质量反馈报告如下：";
        $data['keyword1'] = $str;
        $data['keyword2'] = "暂无";
        $data['keyword3'] = date("Y-m-d H:i:s",time())."(教研评价时间)";
        $data['remark'] = "监课情况:因课程异常，本堂课无法正常反馈，后续课堂中遇到问题可尝试如下方式："
                        ."\n1、和学生一起退出重进"
                        ."\n2、下载“手机语音监测APP”自我监测"
                        ."\n3、联系助教/咨询老师进行协调处理"
                        ."\n希望老师之后碰到相关问题切莫惊慌，镇定处理。理优期待与你一起共同进步，提供高品质教学服务。";

        self::wx_send_to_teacher($wx_openid,$template_id, $data);

    }

    //老师　教学质量 教研评价
    static public function template_tea_level_check($teacher_info, $info){
        $template_id = WxSendMsg::$tea_assess;
        $data['first']    = "老师你好，近期我们对您的课程进行了听课抽查，课程的质量反馈报告如下：";
        $data['keyword1'] = $info['str'];
        $data['keyword2'] = $info['record_score']."分    等级:".$info['record_rank']."(S>A>B>C)";//human  2603没有写等级
        $data['keyword3'] = date("Y-m-d H:i:s",time())."(教研评价时间)";
        $data['remark'] = "监课情况:".$info['record_monitor_class']
                        ."\n建       议:".$info['record_info']
                        ."\n如有疑问请联系各学科教研老师，理优期待与你一起共同进步，提供高品质教学服务。";

        $url = "http://admin.leo1v1.com/common/teacher_record_detail_info?teacherid=".$teacher_info['teacherid']
             ."&type=".$info['type']."&add_time=".$info['add_time'];

        self::wx_send_to_teacher($teacher_info['wx_openid'],$template_id, $data,$url);

    }

    //老师　升级通知 版本1
    static public function template_tea_up_level($teacher_info, $info){
        $template_id = WxSendMsg::$tea_up_level;
        $data['first']    = "恭喜您获得了晋升";
        $data['keyword1'] = $teacher_info["nick"];
        $data['keyword2'] = $info['level_degree'];
        $data['keyword3'] = date("Y-m-d H:i",time());
        $data['remark']   = "\n升级原因:".$info['record_info']."\n您将获得20元的课时奖励,愿老师您与我们一起以春风化雨的精神，打造高品质教学服务，助我们理优学子更上一层楼。";
        $url = "http://admin.leo1v1.com/common/show_level_up_html?teacherid=".$teacher_info['teacherid'];

        self::wx_send_to_teacher($teacher_info['wx_openid'],$template_id, $data,$url);

    }

    //老师　升级通知 版本2
    static public function template_tea_up_level_two($teacher_info, $info){
        $template_id = WxSendMsg::$tea_up_level;
        $data['first']    = "老师你好，你的表现达到理优平台升级标准，因此会提高你的薪资等级。";
        $data['keyword1'] = $teacher_info["nick"];
        $data['keyword2'] = $info['level_str'];
        $data['keyword3'] = date("Y-m-d H:i",time());
        $data['remark']   = "升级后你的课时费每课时增加".$info['diff_money']."元，期待你在理优平台进一步成长进步，加油！";

        self::wx_send_to_teacher($teacher_info['wx_openid'],$template_id, $data);

    }

    //老师　升级通知 版本3
    static public function template_tea_up_level_three($wx_openid,$teacherid,$name,$level){
        $template_id = WxSendMsg::$tea_up_level;

        $data['first']    = "恭喜".$name."老师,您已经成功晋级到了".$level;
        $data['keyword1'] = $name;
        $data['keyword2'] = $level;
        $data['keyword3'] = date("Y-m-d 00:00",time());
        /* $data['remark']   = "晋升分数:".$score
           ."\n请您继续加油,理优期待与你一起共同进步,提供高品质教学服务";*/
        $data['remark']   = "希望老师在今后的教学中继续努力,再创佳绩";

        $url = "http://admin.leo1v1.com/common/show_level_up_html?teacherid=".$teacherid;
        self::wx_send_to_teacher($wx_openid,$template_id, $data,$url);

    }

    //老师　课程取消
    static public function template_tea_lesson_cancel($teacher_info, $info){
        $template_id = WxSendMsg::$tea_lesson_cancel;

        $data['first']    = $teacher_info['nick']."老师您好！您在"
                          .$info['lesson_time'].",".$info['nick']."学生的试听课由于学生无法如期进行,故作取消";

        $data['keyword1'] = "试听课";
        $data['keyword2'] = $info['lesson_time'];
        $data['remark']   = $info['remark_ex']."理优教务老师会尽快给您再次安排适合的试听课机会，请您及时留意理优的推送通知";
        self::wx_send_to_teacher($teacher_info['wx_openid'],$template_id, $data);

    }

    //老师　排课限制
    static public function template_tea_lesson_limit($wx_openid, $lesson_num, $reason,$grade_str=''){
        $template_id = WxSendMsg::$tea_lesson_frozen;

        $data['first']    = "老师您好，近期我们进行教学质量抽查，您".$grade_str."的课程被限制排课,一周试听课排课数量不超过"
                          .$lesson_num."次。\n限制排课原因："
                          .$reason;

        $data['keyword1'] = "试听课";
        $data['keyword2'] = date("Y-m-d H:i",time());
        $data['remark']   = "参加相关培训达标后，系统会放开排课限制，"
                          ."如有疑问请联系各学科教研老师，理优期待与你一起共同进步，提高教学服务质量。";
        self::wx_send_to_teacher($wx_openid,$template_id, $data);

    }

    //老师　解除限制
    static public function template_tea_lesson_limit_remove($wx_openid, $grade_str=''){
        $template_id = WxSendMsg::$tea_lesson_thaw;
        $data['first']    = "老师您好，您的".$grade_str."课程已解除排课限制。";
        $data['keyword1'] = "试听课";
        $data['keyword2'] = date("Y-m-d H:i",time());
        $data['remark']   = "请继续关注理优的培训活动，理优期待与你一起共同进步，提高教学服务质量。";
        self::wx_send_to_teacher($wx_openid,$template_id, $data);

    }

    //老师　课程冻结
    static public function template_tea_lesson_frozen($wx_openid, $freeze_reason,$grade_str = ''){
        $template_id = WxSendMsg::$tea_lesson_frozen;

        $data['first']    = "老师您好，近期我们进行教学质量抽查，您".$grade_str."的课程被冻结。您的课程反馈情况是：".$freeze_reason;
        $data['keyword1'] = "试听课";
        $data['keyword2'] = date("Y-m-d H:i",time());
        $data['remark']   = "冻结期间无法继续安排试听课，参加相关培训达标后，我们会第一时间进行解冻操作，"
                          ."如有疑问请联系各学科教研老师，理优期待与你一起共同进步，提高教学服务质量。";


        self::wx_send_to_teacher($wx_openid,$template_id, $data);

    }

    //老师　课程解冻
    static public function template_tea_lesson_thaw($wx_openid, $freeze_reason,$grade_str = ''){
        $template_id = WxSendMsg::$tea_lesson_thaw;
        if($grade_str == ''){
            $data['first']    = "恭喜,您所有年级的课程已经解冻,解冻原因:".$freeze_reason;
        } else {
            $data['first']    = "恭喜,您".$grage_str."的课程已经解冻,解冻原因:".$freeze_reason;
        }
        $data['keyword1'] = "试听课";
        $data['keyword2'] = date("Y-m-d H:i",time());
        $data['remark']   = "请继续关注理优的培训活动，理优期待与你一起共同进步，提高教学服务质量。";


        self::wx_send_to_teacher($wx_openid,$template_id, $data);

    }

    //老师　优秀视频推荐
    static public function template_tea_good_video($wx_openid, $tea_name,$grade,$content,$lesson_start,$reason,$url){
        $template_id      = WxSendMsg::$tea_todo_reminder;
        $data['first']    = $tea_name."老师试听转化率较高，现推荐该老师的部分优秀视频供老师们观看，希望有所帮助";
        $data['keyword1'] = "优秀视频推荐";
        $data['keyword2'] = $grade.",课程内容:".$content;
        $data['keyword3'] = date("Y-m-d H:i",$lesson_start);
        $data['remark']   = $reason
                          ."\n理优期待与你共同进步,打造高品质教学服务！"
                          ."\n立即观看优秀视频";
        self::wx_send_to_teacher($wx_openid,$template_id,$data,$url);

    }

    //老师　投诉处理　反馈
    static public function template_tea_complaint($wx_openid, $nick,$content){
        $template_id = WxSendMsg::$tea_complaint;

        $data['first']      = "尊敬的老师 $nick 您好,您的投诉我们已经处理 ";
        $data['keyword1']   = $content;
        $data['keyword2']   = "我们已经核实了相关问题,并进行了处理,感谢您用宝贵的时间和我们沟通!";
        $data['remark']     = "感谢您用宝贵的时间和我们沟通！";

        self::wx_send_to_teacher($wx_openid,$template_id, $data);

    }

    //老师　反馈进度通知
    static public function template_tea_complaint_process($wx_openid, $keyword1,$keyword2='',$remark=''){
        $template_id = WxSendMsg::$tea_complaint_process;

        $data["first"]    = "老师您好，您所提交的反馈已处理。";
        if($keyword2=='' && $remark == '') {
            $data['first']      = "您好,您的反馈我们已经收到! ";
            $keyword2 = "已提交";
            $remark   = "我们会在3个工作日内处理,感谢您的反馈!";
        }
        $data['keyword1']   = $keyword1;
        $data['keyword2']   = @$keyword2;
        $data['remark']     = @$remark;

        self::wx_send_to_teacher($wx_openid,$template_id, $data);

    }

    //后台　老师投诉通知
    static public function template_leo_tea_complaint($wx_openid_arr, $nick,$content,$time,$is_app=false){
        $template_id      = WxSendMsg::$wait_deal;
        if($is_app == false){
            $data = [
                "first"     => "$nick 老师发布了一条投诉",
                "keyword1"  => "常规投诉",
                "keyword2"  => "老师投诉内容:$content",
                "keyword3"  => "投诉时间 $time ",
            ];
            $url = 'http://admin.leo1v1.com/user_manage/qc_complaint/';
        } else {
            $data = [
                "first"     => "$nick 老师发布了一条软件使用反馈",
                "keyword1"  => "软件使用反馈",
                "keyword2"  => "老师反馈内容:$content",
                "keyword3"  => "反馈时间 $time ",
            ];
            $url = 'http://admin.leo1v1.com/user_manage/complaint_department_deal_product';
        }
        foreach($wx_openid_arr as $openid){
            self::wx_send_to_teacher($openid,$template_id, $data, $url);
        }
    }

    //后台　投诉待处理通知
    static public function template_complaint_tip($wx_openid, $nick, $content, $time){

        $template_id      = WxSendMsg::$wait_deal;
        $data = [
            "first"     => " 你收到一条投诉处理 分配人: $nick",
            "keyword1"  => " 投诉处理",
            "keyword2"  => " 投诉内容:$content",
            "keyword3"  => " 分配时间:$time",
        ];
        self::wx_send_to_parent_or_leo($wx_openid,$template_id,$data);

    }

    //　排课解冻申请
    static public function template_apply_thaw($wx_openid, $name,$phone, $content){

        $template_id      = WxSendMsg::$wait_deal;
        $data = [
            "first" => '排课解冻',
            "keyword1" => '申请人:'.$name,
            "keyword2" => '解冻学生:'.$phone,
            "keyword3" => date("Y-m-d H:i:s"),
            "remark"   => '申请说明:'.$content,
        ];
        $url = 'http://admin.leo1v1.com/test_lesson_review/test_lesson_review_list';
        self::wx_send_to_parent_or_leo($wx_openid,$template_id,$data,$url);

    }

    //后台　投诉退费通知
    static public function template_refund($wx_openid, $nick, $content, $time){

        $template_id      = WxSendMsg::$wait_deal;
        $data = [
            "first"     => "$nick 发布了一条退费投诉",
            "keyword1"  => "QC退费投诉",
            "keyword2"  => "QC投诉内容:$content",
            "keyword3"  => "QC投诉时间 $time ",
        ];
        $url = 'http://admin.leo1v1.com/user_manage/qc_complaint/';
        self::wx_send_to_parent_or_leo($wx_openid,$template_id,$data,$url);

    }

    //家长　投诉待处理反馈通知　版本１
    static public function template_par_complaint_process($wx_openid, $nick, $content, $time){

        $template_id = WxSendMsg::$complaint_res;

        $data = [
            "first"     => "尊敬的 家长 $nick 您好,您的投诉我们已处理",
            "keyword1"  => $content,
            "keyword2"  => "我们已经核实了相关问题,并进行了处理,感谢您用宝贵的时间和我们沟通!",
            "keyword3"  => $time_date,
        ];

        self::wx_send_to_parent_or_leo($wx_openid,$template_id,$data);

    }

    //家长　投诉待处理反馈通知　版本２
    static public function template_par_complaint_process_new($wx_openid, $content, $time){

        $template_id = WxSendMsg::$complaint_res;
        $data = [
            "first"     => " 您好,您的反馈我们已经收到!",
            "keyword1"  => $content,
            "keyword2"  => " 已提交",
            "keyword3"  => $time,
            "remark"    => " 我们会在3个工作日内进行处理,感谢您的反馈!"
        ];
        self::wx_send_to_parent_or_leo($wx_openid,$template_id,$data);

    }

    //后台　家长投诉待处理通知
    static public function template_par_complaint($wx_openid, $nick, $content, $time){
        $template_id = WxSendMsg::$wait_deal;
        $data = [
            "first"     => "家长投诉通知",
            "keyword1"  => "家长投诉待处理",
            "keyword2"  => "家长 $nick 投诉 $content",
            "keyword3"  => "投诉时间 $time",
        ];
        $url = "http://admin.leo1v1.com/user_manage/complaint_department_deal_parent";
        self::wx_send_to_parent_or_leo($wx_openid,$template_id,$data,$url);
    }

    //后台　学生上传成绩提醒
    static public function template_upload_score($wx_openid, $nick, $userid){
        $template_id = WxSendMsg::$wait_deal;
        $data = [
            "first"     => "$nick 同学的家长上传了学生成绩",
            "keyword1"  => "成绩录入提醒",
            "keyword2"  => "点击详情进行查看",
            "keyword3"  => date('Y-m-d H:i:s'),
        ];
        $url = 'http://admin.leo1v1.com/stu_manage/score_list?sid='.$userid;
        self::wx_send_to_parent_or_leo($wx_openid,$template_id,$data,$url);
    }

    //QC及上级领导　投诉处理
    static public function template_qc_complaint($wx_openid,$first,$nick,$type,$content,$time,$deal_account,$deal_info){

        $template_id = WxSendMsg::$qc_complaint_res;
        $data = [
            "first"     => $first,
            "keyword1"  => $nick,
            "keyword2"  => $type,
            "keyword3"  => $time,
            "keyword4"  => $content,
            "keyword5"  => "处理人:$deal_account  处理方案:$deal_info",
        ];
        $url = 'http://admin.leo1v1.com/user_manage/complaint_department_deal_teacher/';
        self::wx_send_to_parent_or_leo($wx_openid,$template_id,$data,$url);

    }

    //家长,老师　调课申请
    static public function template_par_change_lesson($par_openid,$tea_openid,$stu_nick,$lessonid,$start,$end,$time,$result){
        $template_id = WxSendMsg::$complaint_res;
        $url ="http://wx-parent.leo1v1.com/wx_parent/adjust_progress?lessonid=".$lessonid;
        $data = [
            "first"     => " 调课申请受理中",
            "keyword1"  => " 调换上课时间",
            "keyword2"  => " 原上课时间: $start ~ $end ,申请受理中,请稍等!",
            "keyword3"  => " $time",
            "remark"    => " 详细进度稍后将以推送的形式发送给您,请注意查看!",
        ];

        self::wx_send_to_parent_or_leo($par_openid,$template_id,$data,$url);//发送给家长

        $template_id = WxSendMsg::$tea_todo_reminder;
        $tea_url = "http://wx-teacher-web.leo1v1.com/handle-adjust/index.html?lessonid=".$lessonid; //待定
        $data['first']      = " 调课申请 ";
        $data['keyword1']   = " 您的学生".$stu_nick."的家长申请修改上课时间";
        $data['keyword2']   = " 原上课时间: $start ~ $end ;$result";
        $data['keyword3']   = $time;
        $data['remark']     = "请点击详情查看家长勾选的时间并进行处理!";
        self::wx_send_to_teacher($tea_openid,$template_id,$data,$tea_url);//发送给老师
    }

    //家长　调课进程通知
    static public function template_par_change_lesson_time($wx_openid,$old_start,$new_start,$result,$time){
        $template_id      = WxSendMsg::$wait_deal;
        $data = [
            "first"     => " 调课申请受理中",
            "keyword1"  => " 调换{".$new_start."}上课时间",
            "keyword2"  => " 原上课时间:{".$old_start."}, $result,申请受理中,请稍等!",
            "keyword3"  => $time,
            "remark"    => " 详细进度稍后将以推送的形式发送给您,请注意查看!",
        ];
        self::wx_send_to_parent_or_leo($wx_openid,$template_id,$data);
    }

    //助教,教务,家长　学生上课时间调整通知
    static public function template_change_lesson_time(
        $wx_openid, $stu_nick, $old_start,$old_end,$new_start,$new_end, $account,$phone,$type=1
    ){
        $template_id      = WxSendMsg::$wait_deal;
        $data = [
            "first"     => "上课时间调整通知",
            "keyword1"  => "上课时间调整",
            "keyword2"  => "学生".$stu_nick."从 $old_start 至 $old_end 的课程 已调整为 $new_start 至 $new_end",
            "remark"     => " 修改人: $account 联系电话: $phone"
        ];
        if($type == 1){//助教,教务
            self::wx_send_to_parent_or_leo($wx_openid,$template_id,$data);
        }else if ($type == 2){//家长
            $data['keyword2'] = "您 从 $old_lesson_start 至 $old_lesson_end 的课程 已调整为 $lesson_start 至 $lesson_end";
            self::send_wx_to_par($wx_openid,$template_id,$data);
        }
    }

    //老师　调课时间结果等待同意 通知
    static public function template_tea_change_lesson_wait_pass($wx_openid,$stu_nick,$lesson_old_date,$result,$data_date){
        $template_id      = WxSendMsg::$tea_todo_reminder;
        $data['first']      = "您申请修改学生{ $stu_nick } 的家长发起的申请修改{ $lesson_old_date } 的上课时间 ";
        $data['keyword1']   = " 调换{ $stu_nick } 的家长发起的换时间申请";
        $data['keyword2']   = "原上课时间:{ $lesson_old_date } ,$result";
        $data['keyword3']   = $day_date;
        $data['remark']     = "详细进度稍后将以推送形式发给您,请注意查看!";
        self::wx_send_to_teacher($wx_openid,$template_id,$data);
    }

    //家长　调课时间结果等待同意　通知
    static public function template_par_change_lesson_wait_pass($wx_openid,$tea_nick,$lesson_old_date,$result,$data_date){

        $template_id        = WxSendMsg::$wait_deal;
        $data = [
            'first' => "{ $tea_nick } 老师要求调换您发起的换时间申请",
            'keyword1' =>"调换{ $lesson_old_date }上课时间",
            'keyword2' => "原上课时间:{ $lesson_old_date },$result",
            'keyword3' => $day_date,
            'remark'   => "请点击详情查看老师勾选的时间并进行处理!"
        ];
        self::wx_send_to_parent_or_leo($wx_openid,$template_id,$data);
    }

    //后台，家长，老师　　调课结果同意／拒绝　通知 **********开发中
    static public function template_change_lesson_ret($par_openid,$tea_openid,$tea_nick,$stu_nick,$lesson_old_time,$lesson_new_time,$lesson_name,$master_openid_arr,$is_teacher_agree,$is_agree){
        if($is_agree){

            if($is_teacher_agree == 1){ // 家长同意
                $data['first']        = "$tea_nick 老师您好, { $stu_nick }的家长同意将时间做出如下修改,原课程时间:{ $lesson_old_time },最终时间调整至{ $lesson_new_time }";

                $data_parent['first'] = "$stu_nick 的家长您好,您发起的调课申请更改如下: 原课程时间:{ $lesson_old_time }; 最终时间调整至{ $lesson_new_time }";

                $data_leo['first'] = "{ $tea_nick } 老师申请调整{ $stu_nick }的家长发起的调课申请,已获得{ $stu_nick }家长的同意,原课程时间{ $lesson_old_time },最终时间调整至{ $lesson_new_time }";
            }elseif($is_teacher_agree == 2){ //老师同意
                $data['first'] = " $tea_nick 老师您好,您于{".$lesson_old_time."的".$lesson_name."},已调整至{".$lesson_new_time."} ";
                $data_parent['first'] = "$stu_nick 的家长您好,您的调课申请已经得到 $tea_nick 老师的同意,{ $lesson_old_time }已调整至{ $lesson_new_time }";
                $data_leo['first'] = "$stu_nick 的家长的调课申请,已经得到{ $tea_nick }老师的同意,{ $lesson_old_time }已经调整至{ $lesson_new_time }";


            }

            $template_id_teacher  = self::$tea_change_lesson_wait_pass;
            $data['keyword1']   = " {".$lesson_name."}";
            $data['keyword2']   = " {".$lesson_new_time."}";
            $data['keyword3']   = " {".$stu_nick."}";
            $data['remark']     = "感谢老师的支持!";

            self::wx_send_to_teacher($tea_openid,$template_id_teacher, $data);//发给老师

            $parent_template_id      = self::$par_change_lesson_wait_pass;
            $data_parent = [
                'keyword1' => $lesson_name,
                'keyword2' => $lesson_new_time,
                'keyword3' => $stu_nick,
                'remark'   => '请注意调整后的时间,感谢家长的支持!'
            ];
            self::wx_send_to_parent_or_leo($par_openid, $parent_template_id, $data_parent);


            $data_leo = [
                'keyword1' => "$lesson_name",
                'keyword2' => "$lesson_new_time",
                'keyword3' => "$stu_nick",
                'remark'   => "请注意调整您的时间安排!"
            ];

            foreach($master_openid_arr as $item_openid ){
                self::wx_send_to_parent_or_leo($item_openid, $parent_template_id, $data_leo);
            }
        } else {//拒绝

            if($is_teacher_keep == 1){ // 1:家长

                $teacher_keep_original_remark = $this->t_lesson_time_modify->get_teacher_keep_original_remark($lessonid);
                $result = "原因: $teacher_keep_original_remark";

                $first    = "您的学生{ $stu_nick }的家长申请修改{ $lesson_start_date }上课时间被{ $tea_nick }老师拒绝!";
                $keyword1 = "老师拒绝调课申请";
                $keyword2 = "原上课时间:{ $lesson_start_date }; $result";

                // 给家长推送结果
                $parent_template_id  = self::$wait_deal;
                $data_parent = [
                    'first' => "您已拒绝{ $tea_nick } 老师要求调换您发起的换时间申请",
                    'keyword1' =>"拒绝调课申请",
                    'keyword2' => "原上课时间:{ $lesson_old_date },您已拒绝",
                    'keyword3' => "$day_date",
                    'remark'   => "详细进度稍后将以推送的形式发给您,请注意查看!"
                ];

            } elseif($is_teacher_keep == 2){ // 2:老师
                //推送给老师

                $parent_keep_original_remark = $this->t_lesson_time_modify->get_parent_keep_original_remark($lessonid);
                $result = "原因: $parent_keep_original_remark ";


                $first    = "您的学生{ $stu_nick }的家长申请修改{ $lesson_start_date }上课时间被{ $teacher_nick }老师拒绝!";
                $keyword1 = "老师拒绝调课申请";

                $keyword2 = "原上课时间:{ $lesson_start_date }; $result";

                $teacher_wx_openid = $this->t_teacher_info->get_wx_openid_by_lessonid($lessonid);
                $template_id_teacher  = "rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o";
                $data['first']      = " 您的学生{ $stu_nick }的家长申请修改{ $lesson_start_date }上课时间,您已拒绝! ";
                $data['keyword1']   = " 拒绝调课申请";
                $data['keyword2']   = " 原上课时间:{".$lesson_start_date."};您已拒绝";
                $data['keyword3']   = "$day_date";
                $data['remark']     = "详细进度稍后将以推送的形式发给您,请注意查收!";
            }
        }

    }

    //cc 交接单被驳回通知
    static public function template_refuse_cc($wx_openid, $account, $nick){

        $template_id      = WxSendMsg::$wait_deal;
        $data = [
            "first"     => $account."已处理 ".$nick."同学被驳回的交接单 ",
            "keyword1"  => " CC交接单驳回处理完成",
            "keyword2"  => " CC交接单驳回处理完成",
            "keyword3"  => " 提交时间:".date('Y-m-d H:i:s'),
        ];
        self::wx_send_to_parent_or_leo($wx_openid,$template_id,$data);

    }

    //master 交接单驳回通知
    static public function template_refuse_master($wx_openid,$name,$orderid,$time,$reason){

        $template_id = WxSendMsg::$wait_deal;
        $data = [
            "first"     => "交接单被驳回",
            "keyword1"  => "交接单驳回处理",
            "keyword2"  => "$name 驳回交接单, 交接单合同号$orderid,驳回原因:$reason",
            "keyword3"  => $time,
        ];
        $url = 'http://admin.leo1v1.com/stu_manage/init_info_by_contract_cr?orderid='.$orderid;

        self::wx_send_to_parent_or_leo($wx_openid,$template_id,$data,$url);
    }

    //master 交接单更新通知
    static public function template_transfer_change($wx_openid,$stu,$account,$userid){
        $template_id = WxSendMsg::$wait_deal;
        $data = [
            "first"    => "PDF 交接单 更新",
            "keyword1" => "学生-$stu",
            "keyword2" => "助教-$account",
            "keyword3" => date("Y-m-d H:i:s"),
            "remark"   => "请点击[详情],进入管理系统操作",
        ];
        $url = "http://admin.leo1v1.com/user_manage_new/ass_contract_list?studentid=$userid";

        self::wx_send_to_parent_or_leo($wx_openid,$template_id,$data,$url);
    }

    //家长　理优周年庆
    static public function template_par_leo_year($wx_openid,$content){
        $template_id    = WxSendMsg::$wait_deal;
        $data = [
            "first"     => "您有一条未处理的\"理优周年庆\"活动奖励，请及时处理",
            "keyword1"  => "理优周年庆活动",
            "keyword2"  => "奖学金现金劵使用码",
            "keyword3"  => date("Y-m-d"),
            "remark"    => $content,
        ];
        self::wx_send_to_parent_or_leo($wx_openid,$template_id,$data);
    }

    //家长　奖品券推送
    static public function template_par_lottery($wx_openid){

        $template_id      = WxSendMsg::$wait_deal;
        $data = [
            "first"     => "您好，您的双十一奖品券已存放进您的账户",
            "keyword1"  => "获奖详情",
            "keyword2"  => "点击 个人中心→奖品区 即可兑换奖券",
            "keyword3"  => date('Y-m-d H:i:s'),
        ];
        $url = "http://wx-parent-web.leo1v1.com/prizes";

        self::wx_send_to_parent_or_leo($wx_openid,$template_id,$data,$url);
    }

    //微信发送给老师
    static public function wx_send_to_teacher($openid,$template_id,$data,$url=""){
        $appid     = \App\Helper\Config::get_teacher_wx_appid();
        $appsecret = \App\Helper\Config::get_teacher_wx_appsecret();
        $teacher_wx = new \App\Helper\Wx($appid,$appsecret);

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

    //微信发送给家长或后台
    static public function wx_send_to_parent_or_leo($wx_openid,$template_id,$data,$url=""){
        $wx  = new \App\Helper\Wx();
        $ret = $wx->send_template_msg($wx_openid,$template_id,$data,$url);
    }

    static public function send_wx_notic_for_software($wx_openid, $data, $url){
        $wx  = new \App\Helper\Wx();
        $template_id = WxSendMsg::$wait_deal;
        $ret = $wx->send_template_msg($wx_openid,$template_id,$data,$url);
    }

    static public function send_ass_for_first($wx_openid, $data, $url){
        $wx  = new \App\Helper\Wx();
        $template_id = WxSendMsg::$wait_deal;
        $ret = $wx->send_template_msg($wx_openid,$template_id,$data,$url);
    }


};
