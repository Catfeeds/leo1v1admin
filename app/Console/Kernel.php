<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

use Illuminate\Support\Facades\Log ;
use Illuminate\Support\Facades\App ;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\add_warning_overtime::class,
        Commands\reset_parent_call_status::class,
        Commands\send_wx_msg_for_test_lesson::class,
        Commands\check_system::class,
        Commands\CheckLessonTeacherMoneyType::class,
        Commands\deal_pdf_to_png::class,
        Commands\send_lesson_notice_next_day::class,
        Commands\check_test_lesson_succ_flag_for_send_wx::class,
        Commands\audio_post::class,
        Commands\sync_email_account::class,
        Commands\tom_do_once::class,
        Commands\office_close::class,
        Commands\reset_lesson_online_user_status_by_stroke_time::class,
        Commands\update_lesson_call_time::class,
        Commands\update_seller_level::class,
        Commands\todo_reset::class,
        Commands\agent_reset::class,
        Commands\BindUserid2Phone::class,
        Commands\gen_todo::class,
        Commands\sync_tianrun::class,
        Commands\do_once::class,
        Commands\notify_day_report::class,
        Commands\tt::class,
        Commands\jhp::class,
        Commands\lesson_check::class,
        Commands\fulltime_teacher_interview_info::class,
        Commands\teacher_first_test_lesson_deal::class,
        Commands\teacher_fifth_test_lesson_deal::class,
        Commands\teacher_first_regular_lesson_deal::class,
        Commands\teacher_fifth_regular_lesson_deal::class,
        Commands\seller_new_count_day_gen::class,
        Commands\zs_lecture_info_day::class,
        Commands\cr_info_week::class,
        Commands\cr_info_month::class,
        Commands\cr_info_funnel_month::class,
        Commands\zs_lecture_info_day_new::class,
        Commands\zs_lecture_info_month::class,
        Commands\zs_lecture_info_week::class,
        Commands\zs_lecture_info_all::class,
        Commands\zs_send_data_every_week::class,
        Commands\zs_send_data_every_month::class,
        Commands\update_ass_warning_list::class,        
        Commands\fulltime_teacher_kaoqin::class,
        Commands\zs_train_interview_info_wx::class,
        Commands\teacher_advance_send_wx::class,
	    Commands\set_every_month_student_score::class,
        Commands\update_course_list::class,
        Commands\ass_wx_remind_send_day::class,
        Commands\no_auto_student_change_type::class,
        Commands\send_teacher_train_interview_info_day::class,
        Commands\send_teacher_train_interview_before::class,
        Commands\fulltime_teacher_lesson_count_send_wx::class,
        Commands\teacher_test_lesson_tra_order::class,
        Commands\teacher_test_lesson_tra_day_wx::class,
        Commands\teacher_test_lesson_low_pre::class,
        Commands\teacher_freeze_info_wx::class,
        Commands\limit_require_deal::class,
        Commands\get_ass_weekly_info::class,
        Commands\get_ass_month_info::class,
        Commands\update_teacher_leave_info::class,
        Commands\update_train_lesson_email_send_info::class,
        Commands\teacher_train_interview_lesson_wx::class,
        Commands\all_type_interview_info_send_wx::class,
        Commands\all_type_interview_info_send_wx_today::class,
        Commands\commend_teacher_info_send_wx::class,
        Commands\update_teacher_have_test_lesson_flag::class,
        Commands\regular_course_un_plan_send_wx::class,
        Commands\teacher_limit_plan_lesson_info_wx::class,
        Commands\teacher_interview_pass_info_wx::class,
        Commands\send_tea_record_by_email_week::class,
        Commands\student_assign_for_ass_auto::class,
        Commands\get_research_teacher_kpi_info::class,
        Commands\get_research_teacher_kpi_info_subject::class,
        Commands\send_courseware_PDF_email_to_stu::class,
        Commands\send_course_comment_emial_to_stu::class,
        Commands\teacher_change_good_send_wx_week::class,
        Commands\jw_teacher_test_lesson_assign_auto::class,
        Commands\get_teacher_week_liveness::class,
        Commands\update_order_unit_price_and_send_left::class,
        Commands\get_teacher_test_transfor_per::class,
        Commands\update_teacher_limit_require_seller::class,
        Commands\teacher_limit_lesson_num_change::class,
        Commands\test_lesson_require_time_auto_change::class,
        Commands\get_ass_stu_info_update::class,
        Commands\get_jw_lesson_info_update::class,
        Commands\teacher_stu_paper_download_warn::class,
        Commands\get_admin_group_info_update::class,
        Commands\get_ass_warning_stu_info_update::class,
        Commands\ass_stu_warning_renw_info_update::class,
        Commands\get_teacher_lesson_hold_flag_update::class,
        Commands\teacher_assign_jw_teacher_auto::class,
        Commands\teacher_first_test_lesson_wx::class,
        Commands\teacher_first_test_lesson_wx_day::class,
        Commands\zs_teacher_ten_lecture_appoinment_assign_auto::class,
        Commands\teacher_have_order_send_wx_for_money::class,
        Commands\tongji_teacher_week_order_info_wx::class,
        Commands\friday_send_new_teacher_info_wx::class,
        Commands\get_week_freeze_teacher_info::class,
        Commands\send_small_class_courseware_PDF_email_to_stu::class,
        Commands\send_small_class_course_comment_emial_to_stu::class,
        Commands\check_stu_tea::class,
        Commands\NoticeTeacherFeedbackToAdmin::class,
        Commands\NoticeTomorrowLessonToParent::class,
        Commands\NoticeLessonHomework::class,
        Commands\NoticeLessonHomeworkNotFinish::class,
        Commands\NoticeLessonStartToStudent::class,
        Commands\NoticeLessonStartToTeacher::class,
        Commands\log_valid_user_count::class,
        Commands\NoticeTeacherSetStuPerformance::class,
        Commands\NoticeTeacher::class,
        Commands\NoticeStudent::class,
        Commands\SetTeacherMoney::class,
        Commands\reset_lesson_info_real_begin_time::class,
        Commands\ytx_sync::class,
        Commands\ytx_sync_wav::class,
        Commands\ResetTeacherLessonCount::class,
        Commands\UpdateOrderLessonList::class,
        Commands\UpdateTeacherLectureAppointment::class,
        Commands\UpdateSetLessonFullNum::class,
        Commands\UpdateTeacherLectureAudio::class,
        Commands\sync_tq::class,
        Commands\gen_top_info::class,

        Commands\set_lesson_audio_record_server_at_time::class,
        Commands\set_every_month_student_score::class,
        Commands\set_lesson_audio_record_server_at_time_new::class,
        Commands\tongji::class,
        Commands\log_seller_call_phone_day::class,
        Commands\reset_lesson_count_when_lesson_start::class,
        Commands\SetUploadInfo::class,
        Commands\reset_menu_url_power_map::class,
        Commands\notify_teacher_lesson_tomorrow::class,
        Commands\notify_regiter_user::class,
        Commands\test_lesson_set_seller_student_status_290::class,
        Commands\reset_lesson_online_user_status::class,
        Commands\CheckTeacherIsInRoom::class,
        Commands\seller_reset_no_call_to_new_user::class,
        Commands\xmpp_del_room::class,

    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //每分钟
        try {
            $schedule->call(Tasks\LessonTask::class. "@set_begin_lesson")->everyMinute();
        }catch(\Exception $e){

        }

        try {
            $schedule->call(Tasks\LessonTask::class. "@set_end_lesson")->everyMinute();
        }catch(\Exception $e){

        }

        //$schedule->
        //echo "xxx\n";
        // $schedule->command('inspire')
        //          ->hourly();
    }
}
