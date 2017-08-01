<?php
namespace App\Console\Commands;
use \App\Enums as E;
class set_every_month_student_score extends cmd_base
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:set_every_month_student_score';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '设置每月学生成绩信息记录';




    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $time = strtotime(date("Y-m"));
        $ret_info = $this->task->t_student_score_info->set_every_month_student_score($time);
        $arr = [];
        foreach($ret_info as $key => $value){
           $arr[$key] = $ret_info[$key]['userid'].','.$ret_info[$key]['subject'].','.$ret_info[$key]['grade'];
        }
        $arr = array_unique($arr);
        $arr = array_merge($arr);
        foreach($arr as $key => $value)
        {
           $res = explode(',',$arr[$key]);
           $userid = intval($res[0]);
           $create_time = time();
           $create_adminid = 99;
           $subject = intval($res[1]);
           $stu_score_type = 1;
           $grade = intval($res[2]);
           if(date("M",$time) < 9 && date("M",$time) > 2){
               $semester = 1;
           }else{
               $semester = 0;
           }
           $status = 2; //待补充
           $ret = $this->task->t_student_score_info->row_insert([
                   "userid"                => $userid,     //
                   "create_time"           => $create_time,
                   "create_adminid"        => $create_adminid,
                   "subject"               => $subject,    //
                   "stu_score_type"        => $stu_score_type,
                   "semester"              => $semester,   
                   "grade"                 => $grade,      //
                   "status"                => $status,
               ],false,false,true);
               if($ret){
                   //echo "success<br/>";
               }
        }
    }
}