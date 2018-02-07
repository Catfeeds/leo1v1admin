<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Enums as E;

class phone_city extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:phone_city';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 't_student_info phone_province, phone_city update';

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
        //every week
        /**  @var   $task \App\Console\Tasks\TaskController */
        $task=new \App\Console\Tasks\TaskController();

        $end_time = time();
        $start_time = $end_time - 86400 * 10;

        //update t_studend_info
        $ret_info = $task->t_student_info->get_all_student_phone_and_id($start_time,$end_time);
        foreach ($ret_info as $key => $value) {
            $userid = $value['userid'];
            $phone  = intval(trim($value['phone']));
            $num = substr($phone, 0,7);
            $ret = $task->t_student_score_info->get_province_info($num);
            if($ret){
                $province = $ret['province'];
                $city     = $ret['city'];
            }else{
                $province = "其它";
                $city     = "其它";
            }
            $task->t_student_info->field_update_list($userid,[
                "phone_province" =>$province,
                "phone_city" =>$city,
            ]);
            echo "student $userid $province  $city.fin\n";
        }


        $ret = $task->t_student_info->get_all_teacher_phone_and_id($start_time,$end_time);
        foreach ($ret as $key => $value) {
            $userid = $value['teacherid'];
            $phone  = intval(trim($value['phone']));
            $num = substr($phone, 0,7);
            $ret = $task->t_student_score_info->get_province_info($num);
            if($ret){
                $province = $ret['province'];
                $city     = $ret['city'];
            }else{
                $province = "其它";
                $city     = "其它";
            }
            $task->t_teacher_info->field_update_list($userid,[
                "phone_province" =>$province,
                "phone_city" =>$city,
            ]);
            echo "teacher $userid $province  $city.fin\n";
        }
    }
}
