<?php

namespace App\Console\Commands;

use \App\Enums as E;

class gen_todo extends cmd_base
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:gen_todo {--day=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function do_handle()
    {
        //下次回访,任务
        $start_time=strtotime(date("Y-m-d"));
        $end_time= $start_time+86400;
        $this->gen_next_revisit_time( $start_time,$end_time );
        $this->gen_lesson_todo( $start_time,$end_time );

    }

    public function gen_next_revisit_time( $start_time, $end_time) {

        $list=$this->task->t_seller_student_new->get_next_revisit_time_list($start_time, $end_time );
        $todo_type= E\Etodo_type::V_SELLER_NEXT_CALL;
        foreach ($list as $item )  {
            //admin_revisiterid, next_revisit_time, userid
            $adminid           = $item["admin_revisiterid"];
            $next_revisit_time = $item["next_revisit_time"];
            $userid            = $item["userid"];
            $from_key_int  = $userid;
            $from_key2_int = $next_revisit_time;
            \App\Todo\todo_base::add($todo_type,$next_revisit_time ,$next_revisit_time+7200,$adminid,$from_key_int,$from_key2_int);
        }


    }


    public function gen_lesson_todo( $start_time, $end_time) {


        $list=$this->task->t_lesson_info_b2->get_seller_test_lesson_list($start_time, $end_time+12*3600 );
        foreach ($list as $item )  {
            $adminid      = $item["cur_require_adminid"];
            $lessonid     = $item["lessonid"];
            $lesson_start = $item["lesson_start"];
            $day_start    = strtotime(date("Y-m-d", $lesson_start));
            $day_end      = $day_start+86400;
            $check_time   = $day_start+12*3600;
            $todo_start_time= $day_start;
            if ( $lesson_start<$check_time) {
                $todo_start_time= $day_start-12*3600;
            }

            $from_key_int  = $lessonid;
            $from_key2_int = 0;
            \App\Todo\todo_base::add(
                E\Etodo_type::V_SELLER_BEFORE_LESSON_CALL,  $todo_start_time,
                $lesson_start,$adminid,$from_key_int,$from_key2_int);

            \App\Todo\todo_base::add(
                E\Etodo_type::V_SELLER_AFTER_LESSON_CALL ,  $lesson_start+1800,
                $lesson_start+1800+7200, $adminid,$from_key_int,$from_key2_int);
        }

    }

}