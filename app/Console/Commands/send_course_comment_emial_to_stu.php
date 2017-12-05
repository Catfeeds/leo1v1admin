<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class send_course_comment_emial_to_stu extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:send_course_comment_emial_to_stu';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '发送课堂反馈给学生';

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
        /**  @var   $task \App\Console\Tasks\TaskController */

        $task=new \App\Console\Tasks\TaskController();
        $start_time = strtotime(date("Y-m-d",time()))-10*86400;
        $end_time   = time();
        $level      = [0=>"",1=>"加油",2=>"还行",3=>"不错",4=>"良好",5=>"优秀"];

        $lesson_info = $task->t_lesson_info->get_performance_stu_new($start_time,$end_time);
        foreach ($lesson_info as $item){
            if(!empty($item['stu_performance']) && !empty($item['stu_email'])){
                $ret_info     = json_decode($item['stu_performance'],true);
                if(is_array($ret_info) && isset($ret_info['total_judgement'])){
                    $lesson_start = date('Y-m-d H:i',$item['lesson_start']);
                    $lesson_end   = date('H:i',$item['lesson_end']);
                    $stu_email    = trim($item['stu_email']);
                    $level_stu    = $level[$ret_info['total_judgement']];
                    if(isset($ret_info['point_note_list']) && is_array($ret_info['point_note_list'])){
                        foreach($ret_info['point_note_list'] as $key => $val){
                            \App\Helper\Utils::logger("send_code_stu1".json_encode($val));

                            $ret_info['point_name'][$key]     = @$val['point_name'];
                            $ret_info['point_stu_desc'][$key] = @$val['point_stu_desc'];
                        }
                    }
                    $num  = count(@$ret_info['point_stu_desc']);
                    $time = date('Y-m-d H:i:s',time());

                    if(is_array($ret_info['stu_comment'])){
                        $str = json_encode($ret_info['stu_comment']);
                        $str = "总结如下:<br>".$task->get_test_lesson_comment_str($str);
                    }else{
                        $str = $ret_info['stu_comment'];
                    }
                    $ret_info['stu_comment'] = $str;

                    if($num>=3){
                        $num = 3;
                        dispatch( new \App\Jobs\SendEmail(
                            $stu_email,$lesson_start."-".$lesson_end." ".$item['stu_nick']." 课堂反馈",
                            "<div style=\"width:700px;font-size:18px\"><div style=\"margin-top:30px\">总体评价:<span style=\"color:#e8a541\">".$level_stu."</span></div><div style=\"margin-top:30px\">上次作业<span style=\"color:#e8a541\">".$ret_info['homework_situation']."</span></div><div style=\"margin-top:10px\">上课时<span  style=\"color:#e8a541\">".$ret_info['lesson_interact']."</span></div><div style=\"margin-top:10px\">课程内容<span  style=\"color:#e8a541\">".$ret_info['content_grasp']."</span></div><p style=\"margin-top:20px\">课程中我们进行了：<span style=\"color:#e8a541\">".@$ret_info['point_name'][0]."、".@$ret_info['point_name'][1]."、".@$ret_info['point_name'][2]."</span><span > ". $num."</span>个知识点的学习</p><ul ><li><i >".@$ret_info['point_name'][0]."</i><p>".@$ret_info['point_stu_desc'][0]."</p></li><li><i>".@$ret_info['point_name'][1]."</i><p>".@$ret_info['point_stu_desc'][1]."</p></li><li><i>".@$ret_info['point_name'][2]."</i><p>".@$ret_info['point_stu_desc'][2]."</p></li></ul><div style=\"margin-top:40px\">".@$ret_info['stu_comment']."</div><div style=\"float:right;margin-top:20px\"><img src=\"http://dev.admin.leo1v1.com/images/dack2.png\" ><div style=\"margin-top:-100px;margin-left:120px\">老师:".$item['tea_nick']."</div><div style=\"margin-left:100px\">".$time."</div></div></div>"
                        )); 
                    }elseif($num== 2){
                        dispatch( new \App\Jobs\SendEmail(
                            $stu_email,$lesson_start."-".$lesson_end." ".$item['stu_nick']." 课堂反馈",
                            "<div style=\"width:700px;font-size:18px\"><div style=\"margin-top:30px\">总体评价:<span style=\"color:#e8a541\">".$level_stu."</span></div><div style=\"margin-top:30px\">上次作业<span style=\"color:#e8a541\">".$ret_info['homework_situation']."</span></div><div style=\"margin-top:10px\">上课时<span  style=\"color:#e8a541\">".$ret_info['lesson_interact']."</span></div><div style=\"margin-top:10px\">课程内容<span  style=\"color:#e8a541\">".$ret_info['content_grasp']."</span></div><p style=\"margin-top:20px\">课程中我们进行了：<span style=\"color:#e8a541\">".@$ret_info['point_name'][0]."、".@$ret_info['point_name'][1]."</span><span > ". $num."</span>个知识点的学习</p><ul ><li><i >".@$ret_info['point_name'][0]."</i><p>".@$ret_info['point_stu_desc'][0]."</p></li><li><i>".@$ret_info['point_name'][1]."</i><p>".@$ret_info['point_stu_desc'][1]."</p></li></ul><div style=\"margin-top:40px\">".@$ret_info['stu_comment']."</div><div style=\"float:right;margin-top:20px\"><img src=\"http://dev.admin.leo1v1.com/images/dack2.png\" ><div style=\"margin-top:-100px;margin-left:120px\">老师:".$item['tea_nick']."</div><div style=\"margin-left:100px\">".$time."</div></div></div>"
                        )); 
                    }elseif($num==1 ){
                        dispatch( new \App\Jobs\SendEmail(
                            $stu_email,$lesson_start."-".$lesson_end." ".$item['stu_nick']." 课堂反馈",
                            "<div style=\"width:700px;font-size:18px\"><div style=\"margin-top:30px\">总体评价:<span style=\"color:#e8a541\">".$level_stu."</span></div><div style=\"margin-top:30px\">上次作业<span style=\"color:#e8a541\">".$ret_info['homework_situation']."</span></div><div style=\"margin-top:10px\">上课时<span  style=\"color:#e8a541\">".$ret_info['lesson_interact']."</span></div><div style=\"margin-top:10px\">课程内容<span  style=\"color:#e8a541\">".$ret_info['content_grasp']."</span></div><p style=\"margin-top:20px\">课程中我们进行了：<span style=\"color:#e8a541\">".@$ret_info['point_name'][0]."</span><span > ". $num."</span>个知识点的学习</p><ul ><li><i >".@$ret_info['point_name'][0]."</i><p>".@$ret_info['point_stu_desc'][0]."</p></li></ul><div style=\"margin-top:40px\">".@$ret_info['stu_comment']."</div><div style=\"float:right;margin-top:20px\"><img src=\"http://dev.admin.leo1v1.com/images/dack2.png\" ><div style=\"margin-top:-100px;margin-left:120px\">老师:".$item['tea_nick']."</div><div style=\"margin-left:100px\">".$time."</div></div></div>"
                        )); 
                    }else{
                        dispatch( new \App\Jobs\SendEmail(
                            $stu_email,$lesson_start."-".$lesson_end." ".$item['stu_nick']." 课堂反馈",
                            "<div style=\"width:700px;font-size:18px\"><div style=\"margin-top:30px\">总体评价:<span style=\"color:#e8a541\">".$level_stu."</span></div><div style=\"margin-top:30px\">上次作业<span style=\"color:#e8a541\">".$ret_info['homework_situation']."</span></div><div style=\"margin-top:10px\">上课时<span  style=\"color:#e8a541\">".$ret_info['lesson_interact']."</span></div><div style=\"margin-top:10px\">课程内容<span  style=\"color:#e8a541\">".$ret_info['content_grasp']."</span></div><div style=\"margin-top:40px\">".@$ret_info['stu_comment']."</div><div style=\"float:right;margin-top:20px\"><img src=\"http://dev.admin.leo1v1.com/images/dack2.png\" ><div style=\"margin-top:-100px;margin-left:120px\">老师:".$item['tea_nick']."</div><div style=\"margin-left:100px\">".$time."</div></div></div>"
                        )); 
                    }
                    $task->t_lesson_info->field_update_list($item['lessonid'],["lesson_comment_send_email_flag"=>1]);
                    \App\Helper\Utils::logger("send email.lessonid:".$item['lessonid']." date:".date("Y-m-d H:i",time()));
                }
            }
        }

    }
}
