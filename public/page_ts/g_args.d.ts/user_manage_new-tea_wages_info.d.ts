interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	teacherid:	number;
	studentid:	number;
	show_type:	string;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	lessonid	:any;
	lesson_type	:any;
	userid	:any;
	grade	:any;
	lesson_start	:any;
	lesson_end	:any;
	deduct_come_late	:any;
	deduct_check_homework	:any;
	deduct_change_class	:any;
	deduct_rate_student	:any;
	deduct_upload_cw	:any;
	subject	:any;
	confirm_flag	:any;
	lesson_full_num	:any;
	stu_nick	:any;
	already_lesson_count	:any;
	lesson_count	:any;
	lesson_price	:any;
	lesson_cancel_time_type	:any;
	lesson_cancel_reason_type	:any;
	teacher_type	:any;
	money	:any;
	type	:any;
	level	:any;
	teacher_money_type	:any;
	l_teacher_type	:any;
	test_lesson_fail_flag	:any;
	fail_greater_4_hour_flag	:any;
	competition_flag	:any;
	lesson_full_reward	:any;
	lesson_cost_normal	:any;
	lesson_cost	:any;
	lesson_cost_info	:any;
	pre_reward	:any;
	price	:any;
	lesson_reward	:any;
	tea_level_num	:any;
	tea_level	:any;
	grade_str	:any;
	subject_str	:any;
	confirm_flag_str	:any;
	lesson_type_str	:any;
	teacher_money_type_str	:any;
	lesson_time	:any;
	lesson_count_err	:any;
	pre_price	:any;
	key1	:any;
	key2	:any;
	key3	:any;
	key1_class	:any;
	key2_class	:any;
	key3_class	:any;
}

/*

tofile: 
	 mkdir -p ../user_manage_new; vi  ../user_manage_new/tea_wages_info.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-tea_wages_info.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		date_type_config:	$('#id_date_type_config').val(),
		date_type:	$('#id_date_type').val(),
		opt_date_type:	$('#id_opt_date_type').val(),
		start_time:	$('#id_start_time').val(),
		end_time:	$('#id_end_time').val(),
		teacherid:	$('#id_teacherid').val(),
		studentid:	$('#id_studentid').val(),
		show_type:	$('#id_show_type').val()
		});
}
$(function(){


	$('#id_date_range').select_date_range({
		'date_type' : g_args.date_type,
		'opt_date_type' : g_args.opt_date_type,
		'start_time'    : g_args.start_time,
		'end_time'      : g_args.end_time,
		date_type_config : JSON.parse( g_args.date_type_config),
		onQuery :function() {
			load_data();
		});
	$('#id_teacherid').admin_select_user_new({
		"user_type"    : "teacher",
		"select_value" : g_args.teacherid,
		"onChange"     : load_data,
		"th_input_id"  : "th_teacherid",
		"only_show_in_th_input"     : false,
		"can_select_all_flag"     : true
	});
	$('#id_studentid').admin_select_user_new({
		"user_type"    : "student",
		"select_value" : g_args.studentid,
		"onChange"     : load_data,
		"th_input_id"  : "th_studentid",
		"only_show_in_th_input"     : false,
		"can_select_all_flag"     : true
	});
	$('#id_show_type').val(g_args.show_type);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...
{!!\App\Helper\Utils::th_order_gen([["date_type_config title", "date_type_config", "th_date_type_config" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["date_type title", "date_type", "th_date_type" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["opt_date_type title", "opt_date_type", "th_opt_date_type" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["start_time title", "start_time", "th_start_time" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["end_time title", "end_time", "th_end_time" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">teacherid</span>
                <input class="opt-change form-control" id="id_teacherid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["teacherid title", "teacherid", "th_teacherid" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">studentid</span>
                <input class="opt-change form-control" id="id_studentid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["studentid title", "studentid", "th_studentid" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">show_type</span>
                <input class="opt-change form-control" id="id_show_type" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["show_type title", "show_type", "th_show_type" ]])!!}
*/
