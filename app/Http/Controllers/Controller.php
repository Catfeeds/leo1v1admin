<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Log ;
use \App\Enums as E;

//use Illuminate\Support\Facades\Session;

require_once( app_path() ."/Helper/functions.php"  );
/**
 * @property  \App\Models\t_lesson_info_b2       $t_lesson_info_b2

//MODEL_DEFINE_DEGIN
 * @property  \App\Models\t_student_score_info  	 $t_student_score_info
 * @property  \App\Models\t_student_cc_to_cr  	 $t_student_cc_to_cr
 * @property  \App\Models\t_todo  	 $t_todo
 * @property  \App\Models\t_complaint_info  	 $t_complaint_info
 * @property  \App\Models\t_complaint_assign_info  	 $t_complaint_assign_info
 * @property  \App\Models\t_complaint_deal_info  	 $t_complaint_deal_info
 * @property  \App\Models\t_agent  	 $t_agent
 * @property  \App\Models\t_agent_order  	 $t_agent_order
 * @property  \App\Models\t_agent_cash  	 $t_agent_cash
 * @property  \App\Models\t_user_report  	 $t_user_report
 * @property  \App\Models\t_kaoqin_machine  	 $t_kaoqin_machine
 * @property  \App\Models\t_kaoqin_machine_adminid  	 $t_kaoqin_machine_adminid
 * @property  \App\Models\t_graduating_student_lesson_time_count  	 $t_graduating_student_lesson_time_count
 * @property  \App\Models\t_refund_analysis  	 $t_refund_analysis
 * @property  \App\Models\t_order_refund_confirm_config  	 $t_order_refund_confirm_config
 * @property  \App\Models\t_admin_card_log  	 $t_admin_card_log
 * @property  \App\Models\t_id_opt_log  	 $t_id_opt_log
 * @property  \App\Models\t_seller_new_count  	 $t_seller_new_count
 * @property  \App\Models\t_seller_new_count_get_detail  	 $t_seller_new_count_get_detail
 * @property  \App\Models\t_online_count_log  	 $t_online_count_log
 * @property  \App\Models\t_seller_student_origin  	 $t_seller_student_origin
 * @property  \App\Models\t_seller_student_new  	 $t_seller_student_new
 * @property  \App\Models\t_test_lesson_subject  	 $t_test_lesson_subject
 * @property  \App\Models\t_test_lesson_subject_require  	 $t_test_lesson_subject_require
 * @property  \App\Models\t_test_lesson_subject_sub_list  	 $t_test_lesson_subject_sub_list
 * @property  \App\Models\t_user_authority_group  	 $t_user_authority_group
 * @property  \App\Models\t_train_lesson_user  	 $t_train_lesson_user
 * @property  \App\Models\t_student_init_info  	 $t_student_init_info
 * @property  \App\Models\t_gift_consign  	 $t_gift_consign
 * @property  \App\Models\t_gift_info  	 $t_gift_info
 * @property  \App\Models\t_lesson_info  	 $t_lesson_info
 * @property  \App\Models\t_homework_info  	 $t_homework_info
 * @property  \App\Models\t_course_order  	 $t_course_order
 * @property  \App\Models\t_order_course_list  	 $t_order_course_list
 * @property  \App\Models\t_small_class_user  	 $t_small_class_user
 * @property  \App\Models\t_teacher_info  	 $t_teacher_info
 * @property  \App\Models\t_teacher_record_list  	 $t_teacher_record_list
 * @property  \App\Models\t_teacher_feedback_list  	 $t_teacher_feedback_list
 * @property  \App\Models\t_teacher_lecture_info  	 $t_teacher_lecture_info
 * @property  \App\Models\t_teacher_apply  	 $t_teacher_apply
 * @property  \App\Models\t_revisit_info  	 $t_revisit_info
 * @property  \App\Models\t_parent_info  	 $t_parent_info
 * @property  \App\Models\t_error_info  	 $t_error_info
 * @property  \App\Models\t_quiz_info  	 $t_quiz_info
 * @property  \App\Models\t_assistant_info  	 $t_assistant_info
 * @property  \App\Models\t_festival_info  	 $t_festival_info
 * @property  \App\Models\t_opt_table_log  	 $t_opt_table_log
 * @property  \App\Models\t_good_video_send_list  	 $t_good_video_send_list
 * @property  \App\Models\t_teacher_lecture_appointment_info  	 $t_teacher_lecture_appointment_info
 * @property  \App\Models\t_parent_child  	 $t_parent_child
 * @property  \App\Models\t_teacher_month_money  	 $t_teacher_month_money
 * @property  \App\Models\t_month_ass_student_info  	 $t_month_ass_student_info
 * @property  \App\Models\t_jw_teacher_month_plan_lesson_info  	 $t_jw_teacher_month_plan_lesson_info
 * @property  \App\Models\t_month_ass_warning_student_info  	 $t_month_ass_warning_student_info
 * @property  \App\Models\t_teacher_phone_click_info  	 $t_teacher_phone_click_info
 * @property  \App\Models\t_ass_weekly_info  	 $t_ass_weekly_info
 * @property  \App\Models\t_teacher_money_type  	 $t_teacher_money_type
 * @property  \App\Models\t_teacher_label  	 $t_teacher_label
 * @property  \App\Models\t_field_modified_list  	 $t_field_modified_list
 * @property  \App\Models\t_fulltime_teacher_attendance_list  	 $t_fulltime_teacher_attendance_list
 * @property  \App\Models\t_test_lesson_require_teacher_list  	 $t_test_lesson_require_teacher_list
 * @property  \App\Models\t_student_type_change_list  	 $t_student_type_change_list
 * @property  \App\Models\t_tongji_date  	 $t_tongji_date
 * @property  \App\Models\t_taobao_item  	 $t_taobao_item
 * @property  \App\Models\t_taobao_type_list  	 $t_taobao_type_list
 * @property  \App\Models\users  	 $users
 * @property  \App\Models\t_audio_record_server  	 $t_audio_record_server
 * @property  \App\Models\t_baidu_msg  	 $t_baidu_msg
 * @property  \App\Models\t_baidu_push_msg  	 $t_baidu_push_msg
 * @property  \App\Models\t_mypraise  	 $t_mypraise
 * @property  \App\Models\t_teacher_money_list  	 $t_teacher_money_list
 * @property  \App\Models\t_teacher_freetime_for_week  	 $t_teacher_freetime_for_week
 * @property  \App\Models\t_teacher_closest  	 $t_teacher_closest
 * @property  \App\Models\t_teacher_closest_grade  	 $t_teacher_closest_grade
 * @property  \App\Models\t_teacher_closest_subject  	 $t_teacher_closest_subject
 * @property  \App\Models\t_appointment_info  	 $t_appointment_info
 * @property  \App\Models\t_order_info  	 $t_order_info
 * @property  \App\Models\t_order_refund  	 $t_order_refund
 * @property  \App\Models\t_order_lesson_list  	 $t_order_lesson_list
 * @property  \App\Models\t_open_lesson_user  	 $t_open_lesson_user
 * @property  \App\Models\t_order_course_info  	 $t_order_course_info
 * @property  \App\Models\t_small_lesson_info  	 $t_small_lesson_info
 * @property  \App\Models\t_student_info  	 $t_student_info
 * @property  \App\Models\t_book_info  	 $t_book_info
 * @property  \App\Models\t_book_revisit  	 $t_book_revisit
 * @property  \App\Models\t_book_info  	 $t_book_info
 * @property  \App\Models\t_user_lesson_account  	 $t_user_lesson_account
 * @property  \App\Models\t_user_lesson_account_log  	 $t_user_lesson_account_log
 * @property  \App\Models\t_user_lesson_account_lesson  	 $t_user_lesson_account_lesson
 * @property  \App\Models\t_user_origin_info  	 $t_user_origin_info
 * @property  \App\Models\t_teacher_freetime_for_week  	 $t_teacher_freetime_for_week
 * @property  \App\Models\t_lesson_opt_log  	 $t_lesson_opt_log
 * @property  \App\Models\t_pic_manage_info  	 $t_pic_manage_info
 * @property  \App\Models\t_seller_student_info  	 $t_seller_student_info
 * @property  \App\Models\t_seller_student_info_sub  	 $t_seller_student_info_sub
 * @property  \App\Models\t_test_lesson_log_list  	 $t_test_lesson_log_list
 * @property  \App\Models\t_teacher_complaints_info  	 $t_teacher_complaints_info
 * @property  \App\Models\t_seller_info  	 $t_seller_info
 * @property  \App\Models\t_test_lesson_order_info_old  	 $t_test_lesson_order_info_old
 * @property  \App\Models\t_lecture_revisit_info  	 $t_lecture_revisit_info
 * @property  \App\Models\t_test_subject_free_list  	 $t_test_subject_free_list
 * @property  \App\Models\t_send_wx_template_record_list  	 $t_send_wx_template_record_list
 * @property  \App\Models\t_research_teacher_kpi_info  	 $t_research_teacher_kpi_info
 * @property  \App\Models\t_origin_key  	 $t_origin_key
 * @property  \App\Models\t_wx_openid_bind  	 $t_wx_openid_bind
 * @property  \App\Models\t_test_lesson_assign_teacher  	 $t_test_lesson_assign_teacher
 * @property  \App\Models\t_week_regular_course  	 $t_week_regular_course
 * @property  \App\Models\t_winter_week_regular_course  	 $t_winter_week_regular_course
 * @property  \App\Models\t_summer_week_regular_course  	 $t_summer_week_regular_course
 * @property  \App\Models\t_teacher_assess  	 $t_teacher_assess
 * @property  \App\Models\t_teacher_meeting_info  	 $t_teacher_meeting_info
 * @property  \App\Models\t_teacher_meeting_join_info  	 $t_teacher_meeting_join_info
 * @property  \App\Models\t_teacher_cancel_lesson_list  	 $t_teacher_cancel_lesson_list
 * @property  \App\Models\t_research_teacher_rerward_list  	 $t_research_teacher_rerward_list
 * @property  \App\Models\t_change_teacher_list  	 $t_change_teacher_list
 * @property  \App\Models\t_seller_and_ass_record_list  	 $t_seller_and_ass_record_list
 * @property  \App\Models\t_teacher_leave_info  	 $t_teacher_leave_info
 * @property  \App\Models\t_fulltime_teacher_assessment_list  	 $t_fulltime_teacher_assessment_list
 * @property  \App\Models\t_fulltime_teacher_positive_require_list  	 $t_fulltime_teacher_positive_require_list
 * @property  \App\Models\t_upload_info  	 $t_upload_info
 * @property  \App\Models\t_upload_student_info  	 $t_upload_student_info
 * @property  \App\Models\t_flow  	 $t_flow
 * @property  \App\Models\t_flow_node  	 $t_flow_node
 * @property  \App\Models\t_jiaqi_year_count  	 $t_jiaqi_year_count
 * @property  \App\Models\t_qingjia  	 $t_qingjia
 * @property  \App\Models\t_admin_card_date_log  	 $t_admin_card_date_log
 * @property  \App\Models\t_admin_group  	 $t_admin_group
 * @property  \App\Models\t_admin_group_name  	 $t_admin_group_name
 * @property  \App\Models\t_admin_group_user  	 $t_admin_group_user
 * @property  \App\Models\t_login_log  	 $t_login_log
 * @property  \App\Models\t_manager_info  	 $t_manager_info
 * @property  \App\Models\t_authority_group  	 $t_authority_group
 * @property  \App\Models\t_admin_users  	 $t_admin_users
 * @property  \App\Models\t_adid_to_adminid  	 $t_adid_to_adminid
 * @property  \App\Models\t_tongji  	 $t_tongji
 * @property  \App\Models\t_wx_user_info  	 $t_wx_user_info
 * @property  \App\Models\t_wx_key_value  	 $t_wx_key_value
 * @property  \App\Models\t_tq_call_info  	 $t_tq_call_info
 * @property  \App\Models\t_apply_reg  	 $t_apply_reg
 * @property  \App\Models\t_tongji_seller_top_info  	 $t_tongji_seller_top_info
 * @property  \App\Models\t_admin_main_group_name  	 $t_admin_main_group_name
 * @property  \App\Models\t_seller_month_money_target  	 $t_seller_month_money_target
 * @property  \App\Models\t_assistant_month_target  	 $t_assistant_month_target
 * @property  \App\Models\t_ass_group_target  	 $t_ass_group_target
 * @property  \App\Models\t_admin_group_month_time  	 $t_admin_group_month_time
 * @property  \App\Models\t_user_video_info  	 $t_user_video_info
 * @property  \App\Models\t_group_name_month  	 $t_group_name_month
 * @property  \App\Models\t_group_user_month  	 $t_group_user_month
 * @property  \App\Models\t_main_group_name_month  	 $t_main_group_name_month
 * @property  \App\Models\t_phone_to_user  	 $t_phone_to_user
 * @property  \App\Models\t_user_info  	 $t_user_info
 * @property  \App\Models\t_news_info  	 $t_news_info
 * @property  \App\Models\t_news_tags_info  	 $t_news_tags_info
 * @property  \App\Models\t_news_headlines  	 $t_news_headlines
 * @property  \App\Models\t_news_activity_info  	 $t_news_activity_info
 * @property  \App\Models\t_news_ad_info  	 $t_news_ad_info
 * @property  \App\Models\t_paper_info  	 $t_paper_info
 * @property  \App\Models\t_school_info  	 $t_school_info
 * @property  \App\Models\t_scores_info  	 $t_scores_info
 * @property  \App\Models\t_scores_min  	 $t_scores_min
 * @property  \App\Models\t_sms_msg  	 $t_sms_msg
 * @property  \App\Models\t_weixin_msg  	 $t_weixin_msg
 * @property  \App\Models\users  	 $users

//MODEL_DEFINE_END

 * @property  string account


 //ENUM_GET_IN_DEFINE_DEGIN
  * @method integer get_in_e_accept_flag( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_accept_flag( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_account_role( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_account_role( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_ad_status( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_ad_status( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_assess_res( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_assess_res( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_assistant_type( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_assistant_type( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_ass_test_lesson_type( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_ass_test_lesson_type( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_attendance_type( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_attendance_type( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_authority( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_authority( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_book_grade( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_book_grade( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_book_status( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_book_status( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_boolean( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_boolean( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_call_phone_type( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_call_phone_type( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_can_set( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_can_set( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_change_teacher_reason_type( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_change_teacher_reason_type( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_check_money_flag( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_check_money_flag( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_check_status( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_check_status( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_child_class_performance_flag( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_child_class_performance_flag( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_child_class_performance_type( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_child_class_performance_type( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_class_time( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_class_time( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_class_will_sub_type( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_class_will_sub_type( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_class_will_type( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_class_will_type( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_click_status( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_click_status( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_company( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_company( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_competition_flag( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_competition_flag( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_complaint_department( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_complaint_department( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_complaint_reject_flag( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_complaint_reject_flag( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_complaint_state( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_complaint_state( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_complaint_type( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_complaint_type( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_complaint_user_type( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_complaint_user_type( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_confirm_flag( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_confirm_flag( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_contract_from_type( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_contract_from_type( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_contract_status( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_contract_status( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_contract_type_ex( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_contract_type_ex( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_contract_type( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_contract_type( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_course_status( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_course_status( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_date_id_log_type( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_date_id_log_type( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_degree( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_degree( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_department_group( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_department_group( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_department( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_department( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_dialect_type( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_dialect_type( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_education( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_education( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_employee_level( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_employee_level( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_ency_type( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_ency_type( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_env( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_env( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_error( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_error( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_express_name( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_express_name( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_feedback_type( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_feedback_type( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_flow_check_flag( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_flow_check_flag( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_flow_status( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_flow_status( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_flow_type( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_flow_type( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_freetime( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_freetime( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_from_parent_order_type( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_from_parent_order_type( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_from_type( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_from_type( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_gender( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_gender( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_gift_status( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_gift_status( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_gift_type( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_gift_type( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_grab_status( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_grab_status( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_grade_part_ex( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_grade_part_ex( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_grade_part( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_grade_part( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_grade( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_grade( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_grade_range( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_grade_range( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_grade_type( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_grade_type( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_groupid( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_groupid( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_group_seller_student_status( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_group_seller_student_status( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_honor_list( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_honor_list( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_hour( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_hour( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_identity( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_identity( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_is_auto_set_type_flag( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_is_auto_set_type_flag( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_is_freeze( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_is_freeze( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_is_invoice( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_is_invoice( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_is_test( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_is_test( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_is_top( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_is_top( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_is_warning_flag( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_is_warning_flag( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_jw_test_lesson_status( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_jw_test_lesson_status( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_lecture_appointment_origin( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_lecture_appointment_origin( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_lecture_appointment_status( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_lecture_appointment_status( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_lesson_cancel_reason_type( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_lesson_cancel_reason_type( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_lesson_cancel_time_type( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_lesson_cancel_time_type( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_lesson_count_level( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_lesson_count_level( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_lesson_deduct( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_lesson_deduct( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_lesson_error( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_lesson_error( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_lesson_grade_type( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_lesson_grade_type( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_lesson_status( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_lesson_status( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_lesson_sub_type( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_lesson_sub_type( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_lesson_work_status( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_lesson_work_status( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_level( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_level( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_limit_plan_lesson_type( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_limit_plan_lesson_type( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_main_department( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_main_department( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_main_type( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_main_type( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_message_type( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_message_type( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_operation_satisfy_type( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_operation_satisfy_type( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_opt_type( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_opt_type( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_order_price_type( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_order_price_type( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_order_promotion_type( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_order_promotion_type( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_origin_level( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_origin_level( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_package_type( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_package_type( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_pad_type( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_pad_type( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_performance( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_performance( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_permission( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_permission( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_pic_time_type( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_pic_time_type( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_pic_type( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_pic_type( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_pic_usage_type( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_pic_usage_type( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_positive_type( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_positive_type( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_post( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_post( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_power( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_power( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_praise( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_praise( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_process_status( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_process_status( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_push_num( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_push_num( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_push_status( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_push_status( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_push_type( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_push_type( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_putonghua_is_correctly( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_putonghua_is_correctly( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_qingjia( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_qingjia( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_qingjia_type( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_qingjia_type( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_question_check_flag( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_question_check_flag( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_question_difficulty( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_question_difficulty( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_question_grade( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_question_grade( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_question_type( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_question_type( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_record_type( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_record_type( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_region_version( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_region_version( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_relation_ship( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_relation_ship( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_renw_type( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_renw_type( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_residual( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_residual( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_retrial( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_retrial( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_revisit_origin( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_revisit_origin( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_revisit_person( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_revisit_person( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_revisit_type( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_revisit_type( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_reward_type( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_reward_type( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_role( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_role( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_school_score_change_flag( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_school_score_change_flag( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_school_scores_type( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_school_scores_type( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_school_work_change_type( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_school_work_change_type( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_seller_book_status( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_seller_book_status( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_seller_level( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_seller_level( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_seller_new_count_type( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_seller_new_count_type( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_seller_order_money( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_seller_order_money( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_seller_require_change_flag( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_seller_require_change_flag( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_seller_require_change_type( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_seller_require_change_type( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_seller_resource_type( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_seller_resource_type( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_seller_student_status( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_seller_student_status( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_seller_student_sub_status( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_seller_student_sub_status( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_seller_work_status( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_seller_work_status( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_send_gift( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_send_gift( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_set_boolean( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_set_boolean( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_sh_area( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_sh_area( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_sh_page_grade( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_sh_page_grade( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_sms_type( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_sms_type( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_star_level( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_star_level( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_student_type( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_student_type( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_stu_origin( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_stu_origin( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_stu_score_type( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_stu_score_type( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_subject( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_subject( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_teacher_is_good( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_teacher_is_good( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_teacher_join_info( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_teacher_join_info( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_teacher_label_type( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_teacher_label_type( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_teacher_lecture_score( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_teacher_lecture_score( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_teacher_money_type( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_teacher_money_type( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_teacher_ref_type( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_teacher_ref_type( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_teacher_type( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_teacher_type( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_tea_content_satisfy_flag( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_tea_content_satisfy_flag( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_tea_content_satisfy_type( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_tea_content_satisfy_type( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_tea_label_atmos_type( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_tea_label_atmos_type( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_tea_label_interact_type( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_tea_label_interact_type( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_tea_label_norm_type( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_tea_label_norm_type( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_tea_label_style_type( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_tea_label_style_type( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_tea_type( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_tea_type( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_template_type( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_template_type( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_test_lesson_cancel_flag( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_test_lesson_cancel_flag( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_test_lesson_fail_flag( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_test_lesson_fail_flag( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_test_lesson_level( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_test_lesson_level( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_test_lesson_order_fail_flag( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_test_lesson_order_fail_flag( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_test_lesson_score( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_test_lesson_score( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_test_lesson_status( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_test_lesson_status( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_test_listen_from_type( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_test_listen_from_type( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_test_subject_free_reason( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_test_subject_free_reason( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_test_subject_free_reason_teacher( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_test_subject_free_reason_teacher( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_test_subject_free_type( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_test_subject_free_type( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_test_user( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_test_user( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_textbook_type( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_textbook_type( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_tmk_student_status( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_tmk_student_status( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_todo_status( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_todo_status( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_todo_type( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_todo_type( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_tongji_type( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_tongji_type( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_tq_called_flag( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_tq_called_flag( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_train_lesson_type( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_train_lesson_type( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_train_type( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_train_type( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_trial_type( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_trial_type( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_user_lesson_account_reason( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_user_lesson_account_reason( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_user_report_from_type( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_user_report_from_type( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_week( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_week( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_work_status( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_work_status( $def_value=[-1] , $filed_name="" );
  * @method integer get_in_e_year( $def_value=0 , $filed_name="" );
  * @method integer get_in_el_year( $def_value=[-1] , $filed_name="" );

//ENUM_GET_IN_DEFINE_END
*/
class Controller extends BaseController
{
    var $last_in_values   = array();
    var $last_in_types    = array();
    var $check_login_flag = true;
    var $switch_tongji_database_flag = false;

    use ViewDeal;
    use InputDeal;

    function __construct()  {
        if ($this->check_login_flag ) {
            $this->check_login();
        }
        $this->setUpTraits();
    }
    protected function switch_tongji_database( $flag = true) {

        $this->switch_tongji_database_flag=$flag;
    }

    protected function setUpTraits()
    {
        $uses = array_flip(class_uses_recursive(get_class($this)));

        if (isset($uses[CacheNick::class])) {
            $this->CacheNickInit();
        }
    }
    public function __get( $name ) {
        if (substr($name ,0,2  ) == "t_" || $name=="users") {
            $reflectionObj = new \ReflectionClass( "App\\Models\\$name");
            $this->$name= $reflectionObj->newInstanceArgs();
            if ($this->switch_tongji_database_flag){
                $this->$name->switch_tongji_database();
            }
            return $this->$name;
        }else if ($name == "account" ){
            return $this->get_account();
        }else{
            throw new \Exception() ;
        }
    }
    public function __call($method,$arg )  {
        if ( preg_match("/^get_in_e_(.*)$/",$method,$ret_arr)) {
            $def_value=0;
            $field_name="";
            if (isset($arg[0] )) {
                $def_value=$arg[0];
            }
            if (isset($arg[1] )) {
                $field_name=$arg[1];
            }
            $class_name= "\\App\\Enums\\E{$ret_arr[1]}";
            return $this->get_in_enum_val($class_name ,$def_value ,$field_name);
        }else if ( preg_match("/^get_in_el_(.*)$/",$method,$ret_arr)) {
            $def_value=-1;
            $field_name="";
            if (isset($arg[0] )) {
                $def_value=$arg[0];
            }
            if (isset($arg[1] )) {
                $field_name=$arg[1];
            }
            $class_name= "\\App\\Enums\\E{$ret_arr[1]}";
            return $this->get_in_enum_list ( $class_name, $def_value ,$field_name);

        }

        throw new \Exception("$method  no find ");
    }


    function check_login() {

        if (!session("acc")){
            Log::debug(" DO: Location: / ");
            if (!\App\Helper\Utils::check_env_is_test()) {
                \App\Helper\Utils::logger("GOTO: " .$_SERVER["REQUEST_URI"] );

                header('Location: /?to_url='. bin2hex( $_SERVER["REQUEST_URI"] ) );
                exit;
            }else{
            }
        }
    }


    public function check_account_in_arr($arr ) {
        return in_array(session("acc"), $arr ) ;
    }

    function get_account(){
        return  session("acc");
    }
    function get_account_id(){
        return  session("adminid");
    }

    public function get_login_teacher() {
        return session("tid");
    }

    function get_wx_teacherid(){
        return session("login_userid");
    }
    function get_wx_role(){
        return session("login_user_role");
    }

    static public function check_power($powerid){
        $power_list= json_decode(session("power_list"),true);
        return @$power_list[$powerid];
    }

    static public function check_user_and_power_do_exit($powerid){
        if (!static::check_power($powerid) ){
            return $this->view_with_header_info ( "common.without-power", [],[
                "_ctr"          => "xx",
                "_act"          => "xx",
                "js_values_str" => "",
            ] );
        }else{
            return false;
        }
    }



    public function get_seller_adminid_and_branch(){
        $adminid      = $this->get_account_id();
        $groupid      = $this->t_admin_group_user->get_groupid_value($adminid);
        $adminid_list = [];
        //超权限人员账号集合
        $super_id = [60,186,188,303,323,349];
        //leowang/jim 暂时设定可以看全部
        if ($this->check_account_in_arr(["jim","leowang", "fly"])  ) {
            return $adminid_list;
        }

        if(empty($groupid)){

        }else{
            $master_adminid = $this->t_admin_group_name->get_master_adminid($groupid );
            $up_groupid = $this->t_admin_group_name->get_up_groupid($groupid );
            $main_type = $this->t_admin_group_name->get_main_type($groupid );
            $main_master_adminid = $this->t_admin_main_group_name->get_master_adminid($up_groupid );
            if($adminid != $master_adminid && $adminid != $main_master_adminid){
                $adminid_list[] = $adminid;
            }else if($adminid == $main_master_adminid){
                $list = $this->t_admin_group_name->get_adminid_list_by_up_groupid($up_groupid);
                foreach($list as $item){
                    $adminid_list[]= $item['adminid'];
                }
            }else{
                $adminid_list = $this->t_admin_group_user->get_userid_arr($groupid);
            }

        }
        return $adminid_list;
    }

    public function get_seller_adminid_and_right(){
        $adminid = $this->get_account_id();
        $account = $this->get_account();
        $groupid = $this->t_admin_group_user->get_groupid_value($adminid);
        $adminid_right = [];
        if(empty($groupid)){

        }else{
            $group_name = $this->t_admin_group_name->get_group_name($groupid);
            $master_adminid = $this->t_admin_group_name->get_master_adminid($groupid );
            $up_groupid = $this->t_admin_group_name->get_up_groupid($groupid );
            $up_group_name = $this->t_admin_main_group_name->get_group_name($up_groupid);
            $main_type = $this->t_admin_group_name->get_main_type($groupid );
            $main_type_name = E\Emain_type::get_desc($main_type);

            $main_master_adminid = $this->t_admin_main_group_name->get_master_adminid($up_groupid );
            if($adminid != $master_adminid && $adminid != $main_master_adminid){
                $adminid_right=[0=>$main_type_name,1=>$up_group_name,2=>$group_name,3=>$account];
            }else if($adminid == $main_master_adminid){
                $adminid_right=[0=>$main_type_name,1=>$up_group_name,2=>"",3=>""];
            }else{
                $adminid_right=[0=>$main_type_name,1=>$up_group_name,2=>$group_name,3=>""];
            }

        }
        return $adminid_right;
    }

    public function check_lesson_clash($teacherid,$userid,$lessonid,$lesson_start,$lesson_end){
        $ret_row1 = $this->t_lesson_info->check_student_time_free(
            $userid,$lessonid,$lesson_start,$lesson_end);

        if($ret_row1) {
            $error_lessonid=$ret_row1["lessonid"];
            return $this->output_err(
                "<div>有现存的学生课程与该课程时间冲突！<a href='/tea_manage/lesson_list?lessonid=$error_lessonid/' target='_blank'>查看[lessonid=$error_lessonid]<a/><div>"
            );
        }

        $ret_row2=$this->t_lesson_info->check_teacher_time_free(
            $teacherid,$lessonid,$lesson_start,$lesson_end);

        if($ret_row2) {
            $error_lessonid=$ret_row2["lessonid"];
            return $this->output_err(
                "<div>有现存的老师课程与该课程时间冲突！<a href='/tea_manage/lesson_list?lessonid=$error_lessonid/' target='_blank'>查看[lessonid=$error_lessonid]<a/><div>"
            );
        }
    }

    public function get_account_role() {
        return session("account_role");
    }

    public function del( $userid) {
        $this->t_seller_student_new->row_delete($userid);
    }
}
