<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class reset_lesson_count_when_lesson_start extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:reset_lesson_count_when_lesson_start {--all}';

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
        $lesson_start = time()-120;
        $lesson_start -= $lesson_start%60;


        $all_flag=$this->option('all');
        /**  @var    \App\Console\Tasks\TaskController  $task*/
        $task=new \App\Console\Tasks\TaskController();
        if ($all_flag)  {
            //系统判定无效
            $now=time(NULL);
            $user_list=$task->t_seller_student_new->get_user_list_by_add_time( $now-86400*101 ,$now );
            foreach ($user_list as $item ) {
                $userid=$item["userid"];
                echo "$userid\n";
                $task->t_seller_student_new->reset_sys_invaild_flag($userid);
                $phone= $item["phone"];
                $adminid = $item["admin_revisiterid"];
                $cur_adminid_call_count= $task->t_tq_call_info->get_cur_adminid_call_count($adminid,$phone);
                if ($cur_adminid_call_count != $item["cur_adminid_call_count"]) {
                    $task->t_seller_student_new->field_update_list($userid,[
                        "cur_adminid_call_count" => $cur_adminid_call_count
                    ]);
                }
            }
        }

        if ($all_flag) {
            $list = $task->t_course_order->get_userid_list();
        }else{
            $list = $task->t_lesson_info->get_current_student_list_by_start_time($lesson_start);
        }

        foreach($list as $item) {
            $userid = $item["userid"];
            echo "do $userid \n";
            $task->t_student_info->reset_lesson_count($userid);
        }
    }



}
