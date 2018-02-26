<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class fulltime_teacher_wuhan_data extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:fulltime_teacher_wuhan_data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '每日武汉全职老师面试数据推送';

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
        $start_time = strtotime(date("Y-m-d",time()));
        $end_time = time();
        $data_start = 1498838400;
        $apply_num = $task->t_teacher_lecture_appointment_info->get_fulltime_teacher_count($start_time,$end_time); //成功注册人数

        $arrive_num  = $task->t_teacher_lecture_appointment_info->get_fulltime_teacher_arrive($start_time,$end_time);//
        $video_num   = $task->t_teacher_lecture_appointment_info->get_fulltime_teacher_arrive_video($start_time,$end_time);//视频试讲人数
        $arrive_num = $arrive_num + $video_num;

        $arrive_through_num = $task->t_teacher_lecture_appointment_info->get_fulltime_teacher_arrive_through($start_time,$end_time);//面试通过人数
        $video_through_num  = $task->t_teacher_lecture_appointment_info->get_fulltime_teacher_arrive_video_through($start_time,$end_time);
        $arrive_through = $arrive_through_num + $video_through_num;
        $second_through  = $task->t_teacher_lecture_appointment_info->get_fulltime_teacher_arrive_second_through($start_time,$end_time);
        $enter_num = $task->t_teacher_lecture_appointment_info->get_fulltime_teacher_enter($start_time,$end_time);
        $apply_total = $task->t_teacher_lecture_appointment_info->get_fulltime_teacher_total( $data_start,$end_time); //累计注册人数

        $arrive_num_total  = $task->t_teacher_lecture_appointment_info->get_fulltime_teacher_arrive( $data_start,$end_time);//累计面试人数
        $video_num_total   = $task->t_teacher_lecture_appointment_info->get_fulltime_teacher_arrive_video( $data_start,$end_time);
        $arrive_total = $arrive_num_total + $video_num_total;
        if($apply_total>0){
            $arrive_num_per = round(100*$arrive_total/$apply_total,2);
            $arrive_num_per .= '%('.$arrive_total.'/'.$apply_total.')';
        }else{
            $arrive_num_per = '0%';
        }
        $arrive_num_total  = $task->t_teacher_lecture_appointment_info->get_fulltime_teacher_arrive($data_start,$end_time);
        $video_num_total   = $task->t_teacher_lecture_appointment_info->get_fulltime_teacher_arrive_video($data_start,$end_time);

        $video_through_num_total  = $task->t_teacher_lecture_appointment_info->get_fulltime_teacher_arrive_video_through($data_start,$end_time);//视频试讲通过人数
        $arrive_through_num_total = $task->t_teacher_lecture_appointment_info->get_fulltime_teacher_arrive_through($data_start,$end_time);//面试通过人数
        $arrive_total = $arrive_num_total +  $video_num_total;
        $arrive_through_total = $video_through_num_total + $arrive_through_num_total;
        if($arrive_total>0){
            $arrive_through_per = round(100*$arrive_through_total/$arrive_total,2);
            $arrive_through_per .= '%('.$arrive_through_total.'/'.$arrive_total.')';
        }else{
            $arrive_through_per = '0%';
        }

        $video_through_num_total  = $task->t_teacher_lecture_appointment_info->get_fulltime_teacher_arrive_video_through($data_start,$end_time);//视频试讲通过人数
        $arrive_through_num_total = $task->t_teacher_lecture_appointment_info->get_fulltime_teacher_arrive_through($data_start,$end_time);//面试通过人数
        $arrive_through_total = $video_through_num_total + $arrive_through_num_total;


        $second_through_num_total  = $task->t_teacher_lecture_appointment_info->get_fulltime_teacher_arrive_second_through($data_start,$end_time);

        if($arrive_through_total>0){
            $second_through_per = round(100* $second_through_num_total/$arrive_through_total,2);
            $second_through_per .= '%('. $second_through_num_total.'/'.$arrive_through_total.')';
        }else{
            $second_through_per = '0%';
        }

        $second_through_num_total  = $task->t_teacher_lecture_appointment_info->get_fulltime_teacher_arrive_second_through($data_start,$end_time);
        $enter_num_total = $task->t_teacher_lecture_appointment_info->get_fulltime_teacher_enter($data_start,$end_time); //入职人数
        if($second_through_num_total >0){
            $enter_num_per = round(100* $enter_num_total/$second_through_num_total ,2);
            $enter_num_per .= '%('. $enter_num_total.'/'.$second_through_num_total .')';
        }else{
            $enter_num_per = '0%';
        }
        $leave_num = $task->t_manager_info->get_admin_leave_num($start_time,$end_time);
        $leave_num_all = $task->t_manager_info->get_admin_leave_num($data_start,$end_time);
        $enter_num_all = $task->t_teacher_lecture_appointment_info->get_fulltime_teacher_enter($data_start,$end_time);
        $leave_per = $enter_num_all>0?round($leave_num_all/$enter_num_all*100,2):0;
        $leave_per .= '%('. $leave_num_all.'/'.$enter_num_all.')';

        $arr=[349,1043,72,480,747,1171,1453,1446];
        foreach($arr as $v){
            $task->t_manager_info->send_wx_todo_msg_by_adminid ($v,"武汉全职老师面试数据","武汉全职老师面试数据","成功注册人数:".$apply_num.",一面到面人数:".$arrive_num.",一面通过人数:".$arrive_through.",二面通过人数:".$second_through.",入职人数:".$enter_num.",离职人数:".$leave_num.",一面到面率:".$arrive_num_per.",一面通过率:".$arrive_through_per.",录用率:".$second_through_per.",入职率:".$enter_num_per.",离职率:".$leave_per,"");
 
        }
       
    }
}
