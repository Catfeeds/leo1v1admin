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
            //课堂结束提醒老师评价学生
            $wx->notice_set_stu_performance($type);
            break;
        case 2:
            //上课迟到扣款,3次以内免责,前后课程时间间隔30分钟内,不算迟到
            $wx->late_for_lesson($type);
            break;
        case 3:
            //常规课课前未传学生讲义
            $wx->not_upload_cw($type);
            break;
        case 4:
            //试听课未及时评价扣款
            $wx->late_for_rate_trial($type);
            break;
        case 5:
            //常规课未及时评价扣款
            $wx->late_for_rate_normal($type);
            break;
        case 6:
            //两天未批改学生作业(目前已取消)
            $wx->late_for_check_homework($type);
            break;
        case 7:
            // 课程结束的工资信息
            $wx->notice_teacher_lesson_end($type);
            break;
        case 8:
            // 课前提醒老师上传学生讲义(前一天晚8点提醒)
            $wx->notice_teacher_upload_stu_cw($type);
            break;
        case 10:
            // 课堂信息有误(学生,老师讲义,作业上传有问题)
            $wx->notice_teacher_for_lesson_info($type);
            break;
        case 11:
            // 常规课后24小时老师未评价,提醒老师评价学生
            $wx->notice_teacher_for_rate_student($type);
            break;
        case 12:
            // 试听课课前1小时提醒未下载试卷的老师下载试卷
            $wx->notice_teacher_download_paper($type);
            break;
        case 13:
            // 每周多次排课只需评价1节课程
            $wx->late_for_rate_normal_by_week_num($type);
            break;
        case 14:
            // 每3天给培训未通过的老师推送
            $wx->notice_teacher_not_through_list($type);
            break;
        case 15:
            // 试听/试听模拟课课前4小时未传学生讲义,老师讲义,作业
            $wx->before_four_hour_not_upload_cw($type);
            break;
        case 16:
            // 试听/试听模拟课课前30分钟上课提醒
            $wx->before_thirty_minute_wx($type);
            break;
        case 17:
            // 试听/试听模拟课课前一天晚八点上课提醒
            $wx->tomorrow_lesson_remind_wx($type);
            break;           
        case 18:
            // 模拟试听课堂结束提醒老师评价学生
            $wx->train_lesson_notice_set_stu_performance($type);
            break;
        case 19:
            // 模拟试听课未及时评价扣款
            $wx->train_lesson_late_for_rate_trial($type);
            break;
        case 20:
            // 模拟试听课未评价倒计时15分钟提醒
            $wx->train_lesson_no_comment_remind($type);
            break;
        case 21:
            // 模拟试听课旷课微信推送
            $wx->train_lesson_absenteeism_set($type);
            break;
        default:
            break;
        }
    }

}
