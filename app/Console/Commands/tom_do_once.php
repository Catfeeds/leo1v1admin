<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Qiniu\Auth;

// 引入上传类
use Qiniu\Storage\UploadManager;
use Qiniu\Storage\BucketManager;

require_once  app_path("/Libs/Qiniu/functions.php");

class tom_do_once extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:tom_do_once';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';


    /**
     * task
     *
     * @var \App\Console\Tasks\TaskController
     */

    var $task       ;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->task        = new \App\Console\Tasks\TaskController();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $now = time();
        $list=$this->task->t_seller_student_new->get_all_list();
        foreach ($list as $item) {
            $userid = $item["userid"];
            $phone = $item["phone"];
            // echo "$userid\n";
            if($userid){
                //例子试听成功次数
                // $succ_test_info = $this->task->t_lesson_info_b2->get_succ_test_lesson_count($userid);
                // $succ_count = $succ_test_info['count'];
                // echo "$userid".':'."$succ_count"."\n";
                // if($item['test_lesson_count'] != $succ_count){
                //     $this->task->t_seller_student_new->field_update_list($userid,['test_lesson_count'=>$succ_count]);
                // }

                //例子回流时间
                // $ret = $this->task->t_test_subject_free_list->get_all_list_by_userid($userid);
                // if($ret){
                //     $this->task->t_seller_student_new->field_update_list($userid,['free_adminid'=>$ret['adminid'],'free_time'=>$ret['add_time']]);
                // }

                //例子最后回访时间
                $ret = $this->task->t_tq_call_info->get_last_call_by_phone($phone);
                $call_time = isset($ret['start_time'])?$ret['start_time']:0;
                echo $userid.':'."$call_time"."\n";
                $set_arr=[];
                $set_arr["last_revisit_time"]=$call_time;
                $this->task->t_seller_student_new->field_update_list($userid,$set_arr);
            }
        }
    }
            /*

        $task        = new \App\Console\Tasks\TaskController();
        $teacher_list=$task->t_teacher_info->get_all_list();
        foreach ($teacher_list as $tea_item  ) {
            $teacherid=$tea_item["teacherid"];
            $arr=$task->t_teacher_freetime_for_week->get_common_lesson_config($teacherid);
            foreach( $arr as $item) {
                $end = $item['end_time'];
                $arr=explode("-",$item['start_time']);
                $start = @$arr[1];
                $lesson_start = strtotime(date("Y-m-d", time(NULL))." $start");
                $lesson_end = strtotime(date("Y-m-d", time(NULL))." $end");
                $diff=($lesson_end-$lesson_start)/60;
                if ($diff<=40) {
                    $lesson_count=100;
                } else if ( $diff <= 60) {
                    $lesson_count=150;
                } else if ( $diff <=90 ) {
                    $lesson_count=200;
                }else{
                    $lesson_count= ceil($diff/40)*100 ;
                }


                echo "insert into  db_weiyi.t_week_regular_course (teacherid,userid,start_time,end_time,lesson_count) values('".$teacherid."','".$item['userid']."','".$item['start_time']."','".$item['end_time']."','".$lesson_count."');\n" ;
            }

            */

        /*
        $task        = new \App\Console\Tasks\TaskController();
        $sql="select userid,grade from db_weiyi.t_student_info ";
        $list=$task->t_student_info->main_get_list($sql);
        foreach ($list as $item) {
            $userid         = $item["userid"];
            $grade = $item["grade"];
            $next_grade= \App\Helper\Utils::get_next_grade($grade);

            echo  "$userid\t$grade $next_grade\n  ";
            $task->t_student_info->field_update_list($userid,[
                "grade" => $next_grade,
            ]);

        }
        */

}
