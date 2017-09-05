<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helper\Common;
use \App\Enums as E;



class send_wx_msg_for_test_lesson extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:send_wx_msg_for_test_lesson';

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
        //
        $task = new \App\Console\Tasks\TaskController();

        $now = time();
        $lesson_begin_halfhour = $now+30*60;
        $lesson_end_halfhour   = $now+31*60;

        // 获取试听课 课前30分钟
        // $test_lesson_list_halfhour = $task->t_lesson_info_b2->get_test_lesson_info_for_time($lesson_begin_halfhour, $lesson_end_halfhour);


        // 测试数据
        //orwGAs_IqKFcTuZcU1xwuEtV3Kek [家长端 james]
        // oJ_4fxPmwXgLmkCTdoJGhSY1FTlc [老师帮 james]

        $test_lesson_list_halfhour[] = [
            "ass_phone" => "18201985007",
            "par_phone" => "13933633400",
            "teacherid" => "254321",
            "subject" => "3",
            "ass_openid" => "orwGAs_IqKFcTuZcU1xwuEtV3Kek",
            "tea_openid" => "oJ_4fxPmwXgLmkCTdoJGhSY1FTlc",
            "par_openid" => "orwGAs_IqKFcTuZcU1xwuEtV3Kek",
            "lesson_start" => "1504585800",
            "lesson_end" => "1504588200",
            "teacher_nick" => "james-teacher",
            "userid" => "316441",
            "stu_nick" => "james-student",
            "parent_nick" => "13933633400"
        ];
        // 测试数据

        foreach($test_lesson_list_halfhour as $item){
            $data_tea = $this->get_data($item,1,1);
            $data_par = $this->get_data($item,2,1);
            $data_ass = $this->get_data($item,3,1);

            $this->send_wx_msg_tea($item,1,$data_tea);
            $this->send_wx_msg_par($item,1,$data_par);
            $this->send_wx_msg_ass($item,1,$data_ass);
        }

        /*

        // 试听课超时5分钟
        $lesson_begin_five = $now-5*60;
        $lesson_end_five   = $now-6*60;
        $test_lesson_list_five  = $task->t_lesson_info_b2->get_test_lesson_info_for_time($lesson_begin_five,$lesson_end_five);
        foreach($test_lesson_list_five as $item){
            $opt_time_tea = $task->t_lesson_opt_log->get_test_lesson_for_login($item['lessonid'],$item['teacherid'],$item['lesson_start'],$item['lesson_end']);
            $opt_time_stu = $task->t_lesson_opt_log->get_test_lesson_for_login($item['lessonid'],$item['userid'],$item['lesson_start'],$item['lesson_end']);

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
                $this->send_wx_msg_tea($item,2,$data_ass);
            }
        }



        // 课程中途退出10分钟以上
        $cut_class_lesson_list = $normal_lesson_list = $absenteeism_lesson_list = $task->t_lesson_info_b2->get_lesson_list_for_minute();
        foreach($cut_class_lesson as $item){
            $opt_time_tea = $task->t_lesson_opt_log->get_test_lesson_for_logout($item['lessonid'],$item['teacherid'],$item['lesson_start'],$item['lesson_end']);
            $opt_time_stu = $task->t_lesson_opt_log->get_test_lesson_for_logout($item['lessonid'],$item['userid'],$item['lesson_start'],$item['lesson_end']);

            if($opt_time_stu<=$now-600 && $opt_time_stu<$item['lesson_end']){ // 判断学生是否超时 [10分钟]
                $data_ass = $this->get_data($item, 3,3, '', $item['stu_nick']);
            }

            if($opt_time_tea<=$now-600 && $opt_time_tea<$item['lesson_end']){ // 判断老师是否超时  [10分钟]
                $data_ass = $this->get_data($item, 3,3, $item['teacher_nick'], '');
            }

            $data_ass = $this->get_data();
            $this->send_wx_msg_ass($item,3,$data_ass);
        }

        // 旷课

        foreach($absenteeism_lesson_list as $index=>$item){
            $logout_time_tea = $task->t_lesson_opt_log->get_logout_time($item['lessonid'],$item['teacherid']);
            $logout_time_stu = $task->t_lesson_opt_log->get_logout_time($item['lessonid'],$item['userid']);

            if(!$logout_time_tea || $logout_time_tea<$item['lesson_start']){
                $data_ass = $this->get_data($item,3,4,$item['teacher_nick'],'');
                $this->send_wx_msg_ass($item,4,$data_ass);
            }

            if(!$logout_time_stu || $logout_time_stu<$item['lesson_start']){
                $data_ass = $this->get_data($item,3,4,'',$item['stu_nick']);
                $this->send_wx_msg_ass($item,4,$data_ass);
            }

        }

        // 试听课正常结束

        foreach($normal_lesson_list as $index=>$item){

            $logout_time_tea = $task->t_lesson_opt_log->get_logout_time($item['lessonid'],$item['teacherid']);
            $logout_time_stu = $task->t_lesson_opt_log->get_logout_time($item['lessonid'],$item['userid']);

            if( $logout_time_tea>$item['lesson_end']-600){
                $data_ass = $this->get_data($item,3,6);
                $this->send_wx_msg_ass($item,5,$data_ass);
            }

            if($logout_time_stu>$item['lesson_end']-600){
                $data_ass = $this->get_data($item,3,6);
                $this->send_wx_msg_ass($item,5,$data_ass);
            }

        }
        */

    }


    public function get_data($item, $account_role,$type, $tea_nick_cut_class='', $stu_nick_cut_class=''){
        $subject_str = E\Esubject::get_desc($item['subject']);
        if($account_role == 1){ // 家长
            if($type == 1){ // 课前30分钟
                $data = [
                    "first"    => "家长您好，".$item['stu_nick']."同学于30分钟后有一节 $subject_str 课。",
                    "keyword1" => "$subject_str -- 课程类型: 试听课 -- 老师: ".$item['teacher_nick'],
                    "keyword2" => date('Y-m-d H:i:s',$item['lesson_start']).' ~ '.date('H:i:s',$item['lesson_end']),
                    "keyword3" => '学生端',
                    "keyword4" => '"'.$item['ass_phone'].'"',
                    "remark"   => "可登录学生端提前预习讲义，做好课前准备工作，保持网络畅通，开课前五分钟可提前进入课堂，祝学习愉快！"
                ];
            }elseif($type == 2){ // 超时5分钟
                $data = [
                    "first"    => "家长您好，请提醒".$item['stu_nick']."同学尽快进入课堂.",
                    "keyword1" => "课程提醒",
                    "keyword2" =>  date("H:i",$item['lesson_start'])."$subject_str 课程已开始5分钟，".$item['stu_nick']." 同学还未进入课堂,请尽快进入课堂，如有紧急情况请尽快联系咨询老师.",
                    "keyword3" => date('Y-m-d H:i:s'),
                    "remark"   => ""
                ];

            }elseif($type == 4){ // 课程结束通知
                $data = [
                    "first"    => "家长您好，".$item['stu_nick']."的课程已结束,同学未能按时进入课堂",
                    "keyword1" => "旷课提醒",
                    "keyword2" => "未进入课堂 课程时间：{".date('Y-m-d H:i:s',$item['lesson_start']).' ~ '.date('H:i:s',$item['lesson_end'])."} 学生名字：{".$item['stu_nick']."} 老师名字：{".$item['teacher_nick']."}",
                    "keyword3" => date('Y-m-d H:i:s'),
                    "remark"   => "请尽快进入课堂，如有紧急情况请尽快联系咨询老师"
                ];
            }

        }elseif($account_role == 2){ // 老师
            if($type == 1){
                $data = [
                    "first"    => "老师您好，您于30分钟后有一节 $subject_str 课。",
                    "keyword1" => date('Y-m-d H:i:s',$item['lesson_start']).' ~ '.date('H:i:s',$item['lesson_end']),
                    "keyword2" => '试听课',
                    "keyword3" => "'".$item['teacher_nick']."'",
                    "remark"   => "开课前十五分钟可提前进入课堂，请及时登录老师端，做好课前准备工作。"
                ];
            }elseif($type == 2){ //超时5分钟
                $data = [
                    "first"    => "老师您好,请尽快进入课堂。 ",
                    "keyword1" => '课程提醒',
                    "keyword2" => "'".date('H:i',$item['lesson_start'])."'"."$subject_str 课程已开始5分钟，请尽快进入课堂，如有紧急情况请尽快联系咨询老师",
                    "keyword3" => "'".date('Y-m-d H:i:s')."'",
                    "remark"   => "请尽快进入课堂，如有紧急情况请尽快联系咨询老师。"
                ];
            }elseif($type == 4){
                $data = [
                    "first"    => "{ ".$item['teacher_nick']."}老师您好，".$item['stu_nick']." 同学的 $subject_str 课程已结束,您未能按时进入课堂 ",
                    "keyword1" => '旷课提醒',
                    "keyword2" => "未进入课堂 ",
                    "keyword3" => '"'.date('Y-m-d H:i:s').'"',
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
                    "keyword4" => '"'.$item['par_phone'].'"',
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
                    "keyword3" => date('Y-m-d H:i:s',$item['lesson_start']).' ~ '.date('H:i:s',$item['lesson_end']),
                    "remark"   => "请立刻联系 $name_tmp"
                ];

            }elseif($type == 3){ // 学生|老师中途退出5分钟以上
                if($tea_nick_cut_class){
                    $first = " $tea_nick_cut_class 老师已退出课堂5分钟以上，请关注老师情况，保证课程顺利进行";
                }else{
                    $first = "$stu_nick_cut_class 同学已退出课堂5分钟以上，请关注学生情况，保证课程顺利进行";
                }

                $data = [
                    "first"    => "$first",
                    "keyword1" => '课程提醒',
                    "keyword2" => "同学/老师已退出课堂5分钟以上 课程时间：{".date('Y-m-d H:i:s',$item['lesson_start']).' ~ '.date('H:i:s',$item['lesson_end'])."} 学生名字：{".$item['stu_nick']."} 老师名字：{".$item['teacher_nick']."}",
                    "keyword3" => date('Y-m-d H:i:s',$item['lesson_start']).' ~ '.date('H:i:s',$item['lesson_end']),
                    "remark"   => "请立刻联系同学/老师。"
                ];
            }elseif($type==4){ // 结束未进入课堂
                if($tea_nick_cut_class){
                    $first = "您好，{".$item['stu_nick']."}同学的课程已结束，$tea_nick_cut_class 老师未能按时进入课堂 ";
                }else{
                    $first = "您好，{".$item['stu_nick']."}同学的课程已结束，$stu_nick_cut_class 同学未能按时进入课堂";
                }

                $data = [
                    "first"    => "$first",
                    "keyword1" => '旷课提醒',
                    "keyword2" => "xx同学/xx老师未进入课堂 课程时间：{".date('Y-m-d H:i:s',$item['lesson_start']).' ~ '.date('H:i:s',$item['lesson_end'])."} 学生名字：{".$item['stu_nick']."} 老师名字：{".$item['teacher_nick']."}",
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
            }
        }
        return $data;
    }


    public function send_wx_msg_tea($item, $type, $data_tea){ // 给老师发送
        if($type == 1){
            $template_id_teacher = 'gC7xoHWWX9lmbrJrgkUNcdoUfGER05XguI6dVRlwhUk'; // 上课提醒
        }else{
            $template_id_teacher = 'rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o'; // 待办主题
        }

       if($type !=3  ){
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
        // 给家长发送
        if($type !=3  ){
            $wx->send_template_msg($item['par_openid'],$template_id_parent,$data_par ,'');
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
        $wx->send_template_msg($item['ass_openid'],$template_id_parent,$data_ass ,'');
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
