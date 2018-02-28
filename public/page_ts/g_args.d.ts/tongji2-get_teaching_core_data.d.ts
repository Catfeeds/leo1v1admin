interface GargsStatic {
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	time	:any;
	type	:any;
	new_train_through_num	:any;
	lesson_teacher_num	:any;
	new_lesson_teacher_num	:any;
	old_lesson_teacher_num	:any;
	lose_teacher_num	:any;
	lose_teacher_num_three	:any;
	read_stu_num	:any;
	tea_stu_per	:any;
	test_teacher_num	:any;
	normal_teacher_num	:any;
	test_textbook_rate	:any;
	new_train_through_per	:any;
	new_train_through_time	:any;
	new_tea_thirty_stay_per	:any;
	new_tea_sixty_stay_per	:any;
	new_tea_ninty_stay_per	:any;
	new_tea_thirty_tran_per	:any;
	new_tea_sixty_tran_per	:any;
	new_tea_ninty_tran_per	:any;
	new_tea_thirty_lesson_count	:any;
	new_tea_sixty_lesson_count	:any;
	new_tea_ninty_lesson_count	:any;
	new_teacher_public	:any;
	new_teacher_college	:any;
	new_teacher_outfit	:any;
	appointment_num	:any;
	interview_pass_num	:any;
	new_teacher_train_num	:any;
	new_teacher_train_throuth_num	:any;
	appointment_time	:any;
	interview_pass_time	:any;
	new_teacher_train_time	:any;
	new_teacher_train_throuth_time	:any;
	all_new_train_num	:any;
	train_part_per	:any;
	train_pass_per	:any;
	set_count_all	:any;
	set_count_top	:any;
	set_count_green	:any;
	set_count_grab	:any;
	set_count_normal	:any;
	set_count_all_avg	:any;
	set_count_time_avg	:any;
	set_count_all_per	:any;
	set_count_seller_per	:any;
	set_count_expand_per	:any;
	set_count_change_per	:any;
	set_count_top_per	:any;
	set_count_green_per	:any;
	set_count_grab_per	:any;
	set_count_normal_per	:any;
	grab_success_per	:any;
	teacher_late_num	:any;
	teacher_change_num	:any;
	teacher_leave_num	:any;
	change_tea_num	:any;
	teacher_refund_num	:any;
	teacher_late_per	:any;
	teacher_change_per	:any;
	teacher_leave_per	:any;
	change_tea_per	:any;
	thirty_lesson_tea_num	:any;
	sixty_lesson_tea_num	:any;
	ninty_lesson_tea_num	:any;
	hundred_twenty_lesson_tea_num	:any;
	lose_teacher_num_three_chinese	:any;
	lose_teacher_num_three_math	:any;
	lose_teacher_num_three_english	:any;
	lose_teacher_num_three_chem	:any;
	lose_teacher_num_three_physics	:any;
	lose_teacher_num_three_multiple	:any;
	tea_complaint_num	:any;
	tea_complaint_deal_time	:any;
	simulated_audition_num	:any;
	simulated_audition_time	:any;
	set_count_green_top_per	:any;
	test_no_reg_num	:any;
	fulltime_teacher_count	:any;
	fulltime_teacher_student	:any;
	platform_teacher_student	:any;
	fulltime_teacher_lesson_count	:any;
	platform_teacher_lesson_count	:any;
	platform_teacher_cc_lesson	:any;
	platform_teacher_cc_order	:any;
	fulltime_teacher_cc_lesson	:any;
	fulltime_teacher_cc_order	:any;
	fulltime_normal_stu_num	:any;
	platform_normal_stu_num	:any;
	platform_teacher_count	:any;
	fulltime_teacher_count_wuhan	:any;
	fulltime_teacher_student_wuhan	:any;
	fulltime_teacher_lesson_count_wuhan	:any;
	fulltime_teacher_cc_lesson_wuhan	:any;
	fulltime_teacher_cc_order_wuhan	:any;
	fulltime_normal_stu_num_wuhan	:any;
	fulltime_teacher_count_shanghai	:any;
	fulltime_teacher_student_shanghai	:any;
	fulltime_teacher_lesson_count_shanghai	:any;
	fulltime_teacher_cc_lesson_shanghai	:any;
	fulltime_teacher_cc_order_shanghai	:any;
	fulltime_normal_stu_num_shanghai	:any;
	month	:any;
}

/*

tofile: 
	 mkdir -p ../tongji2; vi  ../tongji2/get_teaching_core_data.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji2-get_teaching_core_data.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,

		});
}
$(function(){




	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...
*/
