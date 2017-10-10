<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;

class ResetTeacherLessonCount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:ResetTeacherLessonCount {--day=}{--teacher_money_type=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '更新老师对所教学生的累积课时';

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
        $day = $this->option('day');
        $end = strtotime(date("Y-m-d",time()+86400));
        if($day===null){
            $start = strtotime(date("Y-m-01",time()));
            $end   = strtotime("+1 month",$start);
        }else{
            $start = strtotime(date("Y-m-d",(time()-$day*86400)));
        }

        $teacher_money_type= $this->option('teacher_money_type');
        if($teacher_money_type===null){
            $teacher_money_type = 0;
        }
        \App\Helper\Utils::logger("reset teacher command start:".$start."end:".$end);

        $t_lesson_info = new \App\Models\t_lesson_info();

        $tea_list = $t_lesson_info->get_teacherid_for_reset_lesson_count($start,$end);
        if(!empty($tea_list) && is_array($tea_list)){
            foreach($tea_list as $val){
                $stu_list = $t_lesson_info->get_student_list_by_teacher($val['teacherid'],$start,$end);
                if(!empty($stu_list) && is_array($stu_list)){
                    foreach($stu_list as $item){
                        $t_lesson_info->reset_teacher_student_already_lesson_count($val['teacherid'],$item['userid']);
                    }
                }
            }
        }
        \App\Helper\Utils::logger("reset teacher lesson count has finished");

        /**
           $job = new \App\Jobs\ResetAlreadyLessonCount($start,$end);
           dispatch($job);
        */
    }
}
