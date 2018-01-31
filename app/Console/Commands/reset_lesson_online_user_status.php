<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class reset_lesson_online_user_status extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:reset_lesson_online_user_status {--day=} {--always_reset=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';


    /**
     * @var  \App\Console\Tasks\TaskController
     */
    public $task;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->task= new \App\Console\Tasks\TaskController();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $day=$this->option('day');

        if ( $day == null ){
            $day=date("Y-m-d");
        }

        $start_time= strtotime( $day) ;
        $end_time= $start_time+86400;

        if ($end_time > time(NULL)) {
            $end_time=time(NULL);
        }
        $end_time-=30*60;
        $always_reset = $this->option("always_reset");
        if ($always_reset==1) {
            $always_reset=true;
        }else{
            $always_reset=false;
        }

        $lesson_list = $this->task->t_lesson_info_b2->get_lesson_list_for_set_online_user_status($start_time, $end_time  , $always_reset);
        foreach ( $lesson_list as $item) {
            $lessonid  = $item["lessonid"];
            $studentid = $item["userid"];
            $teacherid = $item["teacherid"];
            $lesson_type = $item["lesson_type"];
            $check_time  = $item["lesson_start"]+30*60;

            /**
             * @ 试听课如果不是[顺利完成] 或者 [正常上课] 则直接判定无效课程 [叶老师]
             **/
            $is_fail=0;
            if($lesson_type == 2){
                $is_fail = $this->task->t_lesson_info_b3->check_is_fail($lessonid,$lesson_type);
            }

            if($is_fail == 1){
                $this->task->t_lesson_info->field_update_list($lessonid,[
                    "lesson_user_online_status" => 2,
                    "lesson_login_status" =>  0,
                ]);
            }else{
                list($tea_logintime ,$check_teacher_online_flag) =$this->task->t_lesson_opt_log->check_online_flag($lessonid,$teacherid, $check_time );

                list($stu_logintime ,$check_student_online_flag) =$this->task->t_lesson_opt_log->check_online_flag($lessonid,$studentid, $check_time );

                $lesson_online_user_status = $check_teacher_online_flag && $check_student_online_flag  ;
                $lesson_login_flag = $tea_logintime && $stu_logintime;

                if ($lesson_online_user_status ==1 ) {
                    //优学优享
                    $agent_id= $this->task->t_agent->get_agentid_by_userid($studentid);
                    if ($agent_id) {
                        dispatch( new \App\Jobs\agent_reset($agent_id) );
                    }
                }

                $this->task->t_lesson_info->field_update_list($lessonid,[
                    "lesson_user_online_status" =>  $lesson_online_user_status ? 1:2  ,
                    "lesson_login_status" =>  $lesson_login_flag? 1:2  ,
                ]);
                if($lesson_online_user_status == 1){
                    $origin = $this->$task->t_seller_student_origin->get_last_origin($item["userid"],$item["lesson_start"]);
                    if($origin != ''){
                        $this->$task->t_seller_student_origin->field_update_list_2($item["userid"], $origin, ['last_suc_lessonid'=>$lessonid]);
                    }
                }
            }

        }

    }
}
