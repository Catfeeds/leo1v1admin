<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;

class NoticeTeacher extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:NoticeTeacher {--type=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '微信老师推送';

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
        $wx   = new \App\Console\Tasks\TeacherTask();
        $type = $this->option('type');
        if($type===null){
            $type=1;
        }

        switch($type){
        case 1:
            $wx->notice_set_stu_performance($type);
            break;
        case 2:
            $wx->late_for_lesson($type);
            break;
        case 3:
            $wx->not_upload_cw($type);
            break;
        case 4:
            $wx->late_for_rate_trial($type);
            break;
        case 5:
            $wx->late_for_rate_normal($type);
            break;
        case 6:
            $wx->late_for_check_homework($type);
            break;
        case 7:
            $wx->notice_teacher_lesson_end($type);
            break;
        case 8:
            $wx->notice_teacher_upload_stu_cw($type);
            break;
        case 10:
            $wx->notice_teacher_for_lesson_info($type);
            break;
        case 11:
            $wx->notice_teacher_for_rate_student($type);
            break;
        case 12:
            $wx->notice_teacher_download_paper($type);
            break;
        case 13:
            $wx->late_for_rate_normal_by_week_num($type);
            break;
        case 14:
            $wx->notice_teacher_not_through_list($type);
            break;
        case 15:
            $wx->before_four_hour_not_upload_cw($type);
            break;
        case 16:
            $wx->before_thirty_minute_wx($type);
            break;
        case 17:
            $wx->tomorrow_lesson_remind_wx($type);
            break;           
        default:
            break;
        }
    }

}
