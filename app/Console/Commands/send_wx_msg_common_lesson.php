<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use \App\Enums as E;

class send_wx_msg_common_lesson extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:send_wx_msg_common_lesson';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $now = time();

        // 课前四小时未上传讲义
        $four_start = $now+3600*4;
        $four_end   = $four_start+60;
        /**
           {{first.DATA}}
           待办主题：{{keyword1.DATA}}
           待办内容：{{keyword2.DATA}}
           日期：{{keyword3.DATA}}
           {{remark.DATA}}
         **/
        $upload_list = $task->t_lesson_info_b3->check_has_tea_cw_url($four_start,$four_end);
        $template_id_upload = 'rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o';

        foreach($upload_list as $item){
            $data_upload = [
                "first" => '老师您好，'.date('m-d H:i:s',$item['lesson_start']).'~'.date('m-d H:i:s',$item['lesson_end']).'的'.E\Esubject::get_desc($item['subject']).'课未上传讲义',
                "keyword1" => '讲义上传提醒',
                "keyword2" => date('m-d H:i:s',$item['lesson_start']).'~'.date('m-d H:i:s',$item['lesson_end']).'的'.E\Esubject::get_desc($item['subject']).'课未上传讲义，请尽快登录老师后台上传讲义',
                "keyword3" => date('Y-m-d H:i:s')
            ];
            \App\Helper\Utils::send_teacher_msg_for_wx($item['wx_openid'],$template_id_upload, $data_upload,'');
        }

        $lesson_begin_halfhour = $now+30*60;
        $lesson_end_halfhour   = $now+31*60;
        // 获取常规课 课前30分钟
        $common_lesson_list_halfhour = $task->t_lesson_info_b2->get_common_lesson_info_for_time($lesson_begin_halfhour, $lesson_end_halfhour);
        if(count($common_lesson_list_halfhour)<600){
            foreach($common_lesson_list_halfhour as $item){
                $data_par = $this->get_data($item,1,1);
                $data_tea = $this->get_data($item,2,1);
                $this->send_wx_msg_tea($item,3,$data_tea);
                $this->send_wx_msg_par($item,1,$data_par);
            }
        }else{
            $num = count($common_lesson_list_halfhour);
            $this->to_waring('获取常规课 课前30分钟 发送失败 '.$num);
        }

        // 常规课超时5分钟
        $lesson_begin_five = $now-5*60;
        $lesson_end_five   = $now-4*60;
        $common_lesson_list_five = $task->t_lesson_info_b2->get_common_lesson_info_for_time($lesson_begin_five,$lesson_end_five);

        if(count($common_lesson_list_five)<=500){
            foreach($common_lesson_list_five as $item){
                $opt_time_tea = $task->t_lesson_opt_log->get_common_lesson_for_login($item['lessonid'],$item['teacherid']);
                $opt_time_stu = $task->t_lesson_opt_log->get_common_lesson_for_login($item['lessonid'],$item['userid']);
                if($opt_time_stu>=$now){ // 判断学生是否超时 [5分钟]
                    $data_par = $this->get_data($item,1,2,'',$item['stu_nick']);
                    $data_ass = $this->get_data($item,3,2,'',$item['stu_nick']);
                    $this->send_wx_msg_par($item,2,$data_par);
                    $this->send_wx_msg_ass($item,2,$data_ass);
                }

                if($opt_time_tea>=$now){ // 判断老师是否超时  [5分钟]
                    $data_tea = $this->get_data($item,2,2,$item['teacher_nick'],'');
                    $data_ass = $this->get_data($item,3,2,$item['teacher_nick'],'');
                    $this->send_wx_msg_tea($item,2,$data_tea);
                    $this->send_wx_msg_ass($item,2,$data_ass);
                }
            }
        }else{
            $num = count($common_lesson_list_five);
            $this->to_waring('常规课超时5分钟1 数量异常'.$num);
        }

        // 常规课超时15分钟
        $lesson_begin_fith = $now-15*60;
        $lesson_end_fith   = $now-14*60;
        $common_lesson_list_fith = $task->t_lesson_info_b2->get_common_lesson_info_for_time($lesson_begin_fith,$lesson_end_fith);

        if(count($common_lesson_list_fith)<=450){
            foreach($common_lesson_list_fith as $item){
                $opt_time_tea = $task->t_lesson_opt_log->get_common_lesson_for_login($item['lessonid'],$item['teacherid']);
                $opt_time_stu = $task->t_lesson_opt_log->get_common_lesson_for_login($item['lessonid'],$item['userid']);
                if($opt_time_stu>=$now){ // 判断学生是否超时 [15分钟]
                    $data_ass = $this->get_data($item,3,6,'',$item['stu_nick']);
                    $this->send_wx_msg_ass($item,2,$data_ass);

                    //向助教主管发送
                    $data_leader = $this->get_data($item,3,7,'',$item['stu_nick']);
                    $template_id_parent = '9mxyc2khg9bsivl16cjgxfvsi35hiqffpslsjfyckru';
                    $ass_leader_openid = $task->t_manager_info->get_ass_leader_opneid($item['uid']);
                    $wx->send_template_msg($ass_leader_openid,$template_id_parent,$data_leader ,'');
                }

                if($opt_time_tea>=$now){ // 判断老师是否超时  [15分钟]
                    $data_ass = $this->get_data($item,3,6,$item['teacher_nick'],'');
                    $this->send_wx_msg_ass($item,2,$data_ass);
                    //向助教主管发送
                    $data_leader = $this->get_data($item,3,7,$item['teacher_nick'],"");
                    $template_id_parent = '9mxyc2khg9bsivl16cjgxfvsi35hiqffpslsjfyckru';
                    $ass_leader_openid = $task->t_manager_info->get_ass_leader_opneid($item['uid']);
                    $wx->send_template_msg($ass_leader_openid,$template_id_parent,$data_leader ,'');
                }
            }
        }else{
            $num = count($common_lesson_list_fith);
            $this->to_waring('常规课超时15分钟2 数量异常'.$num);
        }

        // 课程中途退出15分钟以上
        $normal_lesson_list = $absenteeism_lesson_list = $task->t_lesson_info_b2->get_common_lesson_list_for_minute();

        $cut_class_lesson_list = $task->t_lesson_info_b2->get_need_late_notic();

        if(count($cut_class_lesson_list)<=1000){
            foreach($cut_class_lesson_list as $item){
                $opt_time_tea_logout = $task->t_lesson_opt_log->get_logout_time($item['lessonid'],$item['teacherid']);
                $opt_time_stu_logout = $task->t_lesson_opt_log->get_logout_time($item['lessonid'],$item['userid']);

                $opt_time_stu_login = $task->t_lesson_opt_log->get_login_time($item['lessonid'],$item['userid']);
                $opt_time_tea_login = $task->t_lesson_opt_log->get_login_time($item['lessonid'],$item['teacherid']);

                if(($opt_time_stu_logout>$opt_time_stu_login)&&($opt_time_stu_logout > $item['lesson_start']) && ($opt_time_stu_logout<=$now-900) && ($now<$item['lesson_end']) ){ // 判断学生是否超时 [15分钟]


                    if($item['stu_late_minute'] == 0){
                        $task->t_lesson_info->field_update_list($item['lessonid'], [
                            "stu_late_minute" => 15
                        ]);

                        $data_ass = $this->get_data($item, 3,3, '', $item['stu_nick']);
                        // $this->to_waring('课程中途退出15分钟 学生 课程id:'.$item['lessonid']); //james
                        $this->send_wx_msg_ass($item,3,$data_ass);
                    }

                }

                if(($opt_time_tea_logout>$opt_time_tea_login)&&($opt_time_tea_logout > $item['lesson_start']) && ($opt_time_tea_logout<=$now-900)  && ($now<$item['lesson_end']) ){ // 判断老师是否超时  [15分钟]

                    if($item['tea_late_minute'] == 0){

                        $task->t_lesson_info->field_update_list($item['lessonid'], [
                            "tea_late_minute" => 15
                        ]);

                        $data_ass = $this->get_data($item, 3,3, $item['teacher_nick'], '');
                        // $this->to_waring('课程中途退出15分钟 老师 课程id:'.$item['lessonid']);//james

                        $this->send_wx_msg_ass($item,3,$data_ass);
                    }
                }
            }
        }else{
            $num = count($cut_class_lesson_list);
            $this->to_waring('课程中途退出15分钟以上'.$num);
        }







        // 旷课
        foreach($absenteeism_lesson_list as $index=>$item){
            $logout_time_tea = $task->t_lesson_opt_log->get_logout_time($item['lessonid'],$item['teacherid']);
            $logout_time_stu = $task->t_lesson_opt_log->get_logout_time($item['lessonid'],$item['userid']);

            if((!$logout_time_tea || $logout_time_tea<$item['lesson_start']) && $now>$item['lesson_end']){
                $data_tea = $this->get_data($item,2,4,'','');
                $data_ass = $this->get_data($item,3,4,$item['teacher_nick'],'');
                $this->send_wx_msg_ass($item,4,$data_ass);
                $this->send_wx_msg_tea($item,2,$data_tea);
            }

            if((!$logout_time_stu || $logout_time_stu<$item['lesson_start']) && $now>$item['lesson_end']){
                $data_ass = $this->get_data($item,3,4,'',$item['stu_nick']);
                $data_par = $this->get_data($item,1,4,'','');
                $this->send_wx_msg_ass($item,4,$data_ass);
                $this->send_wx_msg_par($item,2,$data_par);
            }
        }


        // 常规课 15分钟提示
        $late_time = $now-86400*2+15*60;
        $late_lesson_info = $task->t_lesson_info_b3->get_late_lesson_info($late_time);
        foreach($late_lesson_info as $val){
            $subject_str  = E\Esubject::get_desc($val["subject"]);
            $lesson_time  = date("H:i",$val['lesson_start']);
            $lesson_day   = date("Y-m-d H:i",$val['lesson_start']);

            /**
             * 模板ID   : rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o
             * 标题课程 : 待办事项提醒
             * {{first.DATA}}
             * 待办主题：{{keyword1.DATA}}
             * 待办内容：{{keyword2.DATA}}
             * 日期：{{keyword3.DATA}}
             * {{remark.DATA}}
             */
            $data=[];
            $template_id      = "rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o";
            $data['first']    = "老师您好，".$lesson_time."的".$subject_str."课程已结束，距离课程评价截止时间只剩15分钟了";
            $data['keyword1'] = "课程评价";
            $data['keyword2'] = "距离评价截止时间还有15分钟  \n 课程时间:".$lesson_day."\n 学生姓名:".$val['stu_nick']
                              ."\n 老师姓名".$val['tea_nick'];
            $data['keyword3'] = date("Y-m-d H:i",time());
            $data['remark']   = "请尽快登录老师端,进行评价!";
            $url = "";
            \App\Helper\Utils::send_teacher_msg_for_wx($val['wx_openid'],$template_id,$data,$url);
        }


        # 常规课结束 向家长发送信息
        /**
         * @ 家长您好, xx同学已完成1.5课时,请知晓。如有疑问,请联系班主任
         * @ 课程名称:数学
　       * @ 上课时间:
         * @ 联系电话: {助教电话}
         IyYFpK8WkMGDMqMABls0WdZyC0-jV6xz4PFYO0eja9Q
         {{first.DATA}}
         课程名称：{{keyword1.DATA}}
         下课时间：{{keyword2.DATA}}
         {{remark.DATA}}
         */
        $wx  = new \App\Helper\Wx();
        $oneMinuteStart = $now;
        $oneMinuteEnd   = $oneMinuteStart+60;
        $lessonEndList  = $task->t_lesson_info_b3->getLessonEndList($oneMinuteStart,$oneMinuteEnd);
        $nowMonthStart  = strtotime(date("Y-m-1"));
        $nowMonthEnd    = strtotime(date("Y-m-1",$nowMonthStart+32*86400));

        foreach($lessonEndList as &$itemLessonEnd){
            $subject_str = E\Esubject::get_desc($itemLessonEnd["subject"]);
            $lesson_str  = date("Y-m-d H:i",$itemLessonEnd['lesson_start'])." ~ ".date("H:i",$itemLessonEnd['lesson_end']);
            $templateIdLessonEnd = "IyYFpK8WkMGDMqMABls0WdZyC0-jV6xz4PFYO0eja9Q";
            $dataLessonEnd = [
                "first"    => "家长您好, ".$itemLessonEnd['stu_nick']."同学已完成".number_format($itemLessonEnd['lesson_count'],2)."课时,请知晓。\n如有疑问,请联系班主任",
                "keyword1" => $subject_str,
                "keyword2" => $lesson_str,
                "remark"   => "联系电话: ".$itemLessonEnd['ass_nick']." ".$itemLessonEnd['ass_phone']
            ];

            $urlLessonEnd = "";
            if($itemLessonEnd['wx_openid']!=''){
                $wx->send_template_msg($itemLessonEnd['wx_openid'],$templateIdLessonEnd,$dataLessonEnd ,$urlLessonEnd);
                // $wx->send_template_msg("orwGAs_IqKFcTuZcU1xwuEtV3Kek",$templateIdLessonEnd,$dataLessonEnd ,$urlLessonEnd);//james
            }
        }
    }


    public function to_waring($type){
        $wx  = new \App\Helper\Wx();
        $template_id_self = '9MXYC2KhG9bsIVl16cJgXFVsI35hIqffpSlSJFYckRU'; // 待办主题
        $data_self = [
            "first"    => "常规课 微信推送 报警",
            "keyword1" => $type,
            "keyword2" => date('Y-m-d H:i:s'),
            "keyword3" => '后台',
            "keyword4" => '微信推送 报警',
        ];
        $self_openid = 'orwGAs_IqKFcTuZcU1xwuEtV3Kek'; //james
        $wx->send_template_msg_color($self_openid,$template_id_self,$data_self ,'');
    }

    // $item,3,2,'',$item['stu_nick'])

    public function get_data($item, $account_role,$type, $tea_nick_cut_class='', $stu_nick_cut_class=''){
        $subject_str = E\Esubject::get_desc($item['subject']);
        $data = [];
        if($account_role == 1){ // 家长
            if($type == 1){ // 课前30分钟
                $data = [
                    "first"    => "家长您好，".$item['stu_nick']."同学于30分钟后有一节 ".$item['teacher_nick']."老师的 $subject_str 课。",
                    "keyword1" => "$subject_str",
                    "keyword2" => date('Y-m-d H:i:s',$item['lesson_start']).' ~ '.date('H:i:s',$item['lesson_end']),
                    "keyword3" => '学生端',
                    "keyword4" => '" 助教电话: '.$item['ass_phone'].'"',
                    "remark"   => "可登录学生端提前预习讲义，做好课前准备工作，保持网络畅通，开课前五分钟可提前进入课堂，祝学习愉快！"
                ];
            }elseif($type == 4){ // 旷课通知
                $data = [
                    "first"    => "家长您好，".$item['stu_nick']."的课程已结束,同学未能按时进入课堂",
                    "keyword1" => "旷课提醒",
                    "keyword2" => "未进入课堂 ",
                    "keyword3" => date('Y-m-d H:i:s'),
                    "remark"   => "请尽快进入课堂，如有紧急情况请尽快联系助教老师"
                ];
            }

        }elseif($account_role == 2){ // 老师
            if($type == 1){
                $data = [
                    "first"    => "老师您好，您于30分钟后有一节 $subject_str 课。",
                    "keyword1" => date('Y-m-d H:i:s',$item['lesson_start']).' ~ '.date('H:i:s',$item['lesson_end']),
                    "keyword2" => '常规课',
                    "keyword3" => $item['teacher_nick'],
                    "remark"   => "开课前十五分钟可提前进入课堂，请及时登录老师端，做好课前准备工作。"
                ];
            }elseif($type == 2){ //超时5分钟
                $data = [
                    "first"    => "老师您好,请尽快进入课堂。 ",
                    "keyword1" => '课程提醒',
                    "keyword2" => date('H:i',$item['lesson_start'])."的 $subject_str 课程已开始5分钟，请尽快进入课堂，如有紧急情况请尽快联系助教老师",
                    "keyword3" => date('Y-m-d H:i:s'),
                    "remark"   => "请尽快进入课堂，如有紧急情况请尽快联系助教老师。"
                ];
            }elseif($type == 4){
                $data = [
                    "first"    => $item['teacher_nick']."老师您好，".$item['stu_nick']." 同学的 $subject_str 课程已结束,您未能按时进入课堂 ",
                    "keyword1" => '旷课提醒',
                    "keyword2" => "未进入课堂 ",
                    "keyword3" => date('Y-m-d H:i:s'),
                    "remark"   => "请尽快进入课堂，如有紧急情况请尽快联系咨询老师。"
                ];
            }elseif($type == 5){ // 课程结束
                $data = [
                    "first"    => "{ ".$item['teacher_nick']."}老师您好， 请尽快对本节课做出评价",
                    "keyword1" => '课程评价',
                    "keyword2" => "'".date('H:i',$item['lesson_start'])."' 开始的 $subject_str 课程已结束，请尽快登录老师端，进行评价。",
                    "keyword3" => '"'.date('Y-m-d H:i:s').'"',
                    "remark"   => "请尽快登录老师端，进行评价"
                ];
            }

        }else{ // 助教
            if($type == 1){ // 课前30分钟
                $data = [
                    "first"    => "您好，您的学员".$item['stu_nick']."同学于30分钟后有一节 $subject_str 课。",
                    "keyword1" => "$subject_str",
                    "keyword2" => date('Y-m-d H:i:s',$item['lesson_start']).' ~ '.date('H:i:s',$item['lesson_end']),
                    "keyword3" => "学生端",
                    "keyword4" => '" 家长电话: '.$item['par_phone'].'"',
                    "remark"   => "请及时跟进"
                ];
            }elseif($type == 2){ // 超时5分钟  $tea_nick_cut_class='', $stu_nick_cut_class=
                if($tea_nick_cut_class){
                    $first = "您好，$subject_str 课程已开始5分钟，".$tea_nick_cut_class."老师还未进入课堂。";
                    $name_tmp = '老师';
                }else{
                    $first = "您好，$subject_str 课程已开始5分钟，".$stu_nick_cut_class."同学还未进入课堂。";
                    $name_tmp = '同学';
                }
                $data = [
                    "first"    => "$first",
                    "keyword1" => '课程提醒',
                    "keyword2" => "$subject_str 课程已开始5分钟，$name_tmp 还未进入课堂 ",
                    "keyword3" => "课程时间: ".date('Y-m-d H:i:s',$item['lesson_start']).' ~ '.date('H:i:s',$item['lesson_end']),
                    "remark"   => "请立刻联系 $name_tmp"
                ];

            }elseif($type == 3){ // 学生|老师中途退出15分钟以上
                if($tea_nick_cut_class){
                    $first = " $tea_nick_cut_class 老师已退出课堂15分钟以上，请关注老师情况，保证课程顺利进行";
                }else{
                    $first = "$stu_nick_cut_class 同学已退出课堂15分钟以上，请关注学生情况，保证课程顺利进行";
                }

                $data = [
                    "first"    => "$first",
                    "keyword1" => '课程提醒',
                    "keyword2" => "同学/老师已退出课堂15分钟以上 课程时间：".date('Y-m-d H:i:s',$item['lesson_start']).' ~ '.date('H:i:s',$item['lesson_end'])." 学生名字：".$item['stu_nick']." 老师名字：".$item['teacher_nick'],
                    "keyword3" => date('Y-m-d H:i:s',$item['lesson_start']).' ~ '.date('H:i:s',$item['lesson_end']),
                    "remark"   => "请立刻联系同学/老师。"
                ];
            }elseif($type==4){ // 结束未进入课堂
                if($tea_nick_cut_class){
                    $first = "您好，{".$item['stu_nick']."}同学的课程已结束，$tea_nick_cut_class 老师未能按时进入课堂 ";
                    $keyword2 = "$tea_nick_cut_class 老师";
                }else{
                    $first = "您好，{".$item['stu_nick']."}同学的课程已结束，$stu_nick_cut_class 同学未能按时进入课堂";
                    $keyword2 = "$stu_nick_cut_class 同学";
                }

                $data = [
                    "first"    => "$first",
                    "keyword1" => '旷课提醒',
                    "keyword2" => "$keyword2 未进入课堂 课程时间：".date('Y-m-d H:i:s',$item['lesson_start']).' ~ '.date('H:i:s',$item['lesson_end'])." 学生名字：".$item['stu_nick']." 老师名字：".$item['teacher_nick'],
                    "keyword3" => date('Y-m-d H:i:s',$item['lesson_start']).' ~ '.date('H:i:s',$item['lesson_end']),
                    "remark"   => "请立刻联系同学/老师。"
                ];
            }elseif($type == 5){ // 课程结束
                $data = [
                    "first"    => "您好，您的学员".$item['stu_nick']."同学 $subject_str 课程下课时间已到",
                    "keyword1" => '课程结束通知',
                    "keyword2" => "及时跟进",
                    "keyword3" => date('Y-m-d H:i:s',$item['lesson_start']).' ~ '.date('H:i:s',$item['lesson_end']),
                    "remark"   => "请您及时跟进"
                ];
            }elseif($type == 6){ // 课时超过15分钟
                if($tea_nick_cut_class){
                    $first = "您好，$subject_str 课程已开始15分钟，".$tea_nick_cut_class."老师还未进入课堂。";
                    $name_tmp = '老师';
                }else{
                    $first = "您好，$subject_str 课程已开始15分钟，".$stu_nick_cut_class."同学还未进入课堂。";
                    $name_tmp = '同学';
                }
                $data = [
                    "first"    => "$first",
                    "keyword1" => '课程提醒',
                    "keyword2" => "$subject_str 课程已开始5分钟，$name_tmp 还未进入课堂 ",
                    "keyword3" => "课程时间: ".date('Y-m-d H:i:s',$item['lesson_start']).' ~ '.date('H:i:s',$item['lesson_end'])."学生姓名:".$item['stu_nick']." 老师姓名:".$item['teacher_nick'],
                    "remark"   => "请立刻联系 $name_tmp"
                ];
            }elseif($type == 7){ // 课时超过15分钟 [助教组长]
                if($tea_nick_cut_class){
                    $first = "您好，$subject_str 课程已开始15分钟，".$tea_nick_cut_class."老师还未进入课堂。";
                    $name_tmp = '老师';
                }else{
                    $first = "您好，$subject_str 课程已开始15分钟，".$stu_nick_cut_class."同学还未进入课堂。";
                    $name_tmp = '同学';
                }
                $data = [
                    "first"    => "$first",
                    "keyword1" => '课程提醒',
                    "keyword2" => "$subject_str 课程已开始5分钟，$name_tmp 还未进入课堂 ",
                    "keyword3" => "课程时间: ".date('Y-m-d H:i:s',$item['lesson_start']).' ~ '.date('H:i:s',$item['lesson_end'])."学生姓名:".$item['stu_nick']." 老师姓名:".$item['teacher_nick']."助教姓名:".$item['ass_nick'],
                    "remark"   => "请立刻联系 $name_tmp"
                ];
            }

        }
        return $data;
    }


    public function send_wx_msg_tea($item, $type, $data_tea){ // 给老师发送
        if($type == 1){
            $template_id_teacher = 'gC7xoHWWX9lmbrJrgkUNcdoUfGER05XguI6dVRlwhUk'; // 上课提醒
        }elseif($type == 2){
            $template_id_teacher = 'rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o'; // 待办主题
        }elseif($type == 3){
            $template_id_teacher = 'gC7xoHWWX9lmbrJrgkUNcdoUfGER05XguI6dVRlwhUk'; //课前提醒
        }

        if($type !=3 ){
            \App\Helper\Utils::send_teacher_msg_for_wx($item['tea_openid'],$template_id_teacher, $data_tea,'');
        }

    }


    public function send_wx_msg_par($item, $type, $data_par){ // 向家长
        $wx  = new \App\Helper\Wx();
        if($type == 1){
            $template_id_parent = 'QdFD9O7SPf1eYO_46ptbVeHPnYwTQjCI4_Vj4-wukC8'; // 上课提醒
        }else{
            $template_id_parent = '9MXYC2KhG9bsIVl16cJgXFVsI35hIqffpSlSJFYckRU'; // 待办主题
        }

        if($item['par_openid']){
            // 给家长发送
            if($type !=3  ){
                $wx->send_template_msg($item['par_openid'],$template_id_parent,$data_par ,'');
            }
        }
    }

    public function send_wx_msg_ass($item, $type, $data_ass){ // 向助教发送
        $wx  = new \App\Helper\Wx();
        if($type == 1){
            $template_id_parent = 'QdFD9O7SPf1eYO_46ptbVeHPnYwTQjCI4_Vj4-wukC8'; // 上课提醒
        }else{
            $template_id_parent = '9MXYC2KhG9bsIVl16cJgXFVsI35hIqffpSlSJFYckRU'; // 待办主题
        }
        // 给助教发送
        if($item['ass_openid']){
            $wx->send_template_msg($item['ass_openid'],$template_id_parent,$data_ass ,'');
        }
    }



}


/**
 // gC7xoHWWX9lmbrJrgkUNcdoUfGER05XguI6dVRlwhUk // 老师帮

 {{first.DATA}}
 上课时间：{{keyword1.DATA}}
 课程类型：{{keyword2.DATA}}
 教师姓名：{{keyword3.DATA}}
 {{remark.DATA}}


 rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o // 老师帮 待办主题
 {{first.DATA}}
 待办主题：{{keyword1.DATA}}
 待办内容：{{keyword2.DATA}}
 日期：{{keyword3.DATA}}
 {{remark.DATA}}

**/


/**
 // 待办主题  9MXYC2KhG9bsIVl16cJgXFVsI35hIqffpSlSJFYckRU  // 家长
 {{first.DATA}}
 待办主题：{{keyword1.DATA}}
 待办内容：{{keyword2.DATA}}
 日期：{{keyword3.DATA}}
 {{remark.DATA}}

 QdFD9O7SPf1eYO_46ptbVeHPnYwTQjCI4_Vj4-wukC8

 {{first.DATA}}
 课程名称：{{keyword1.DATA}}
 上课时间：{{keyword2.DATA}}
 上课地点：{{keyword3.DATA}}
 联系电话：{{keyword4.DATA}}
 {{remark.DATA}}

**/
