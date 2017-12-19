interface GargsStatic {
	sid:	number;
	courseid:	number;
	all_flag:	number;
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
	subject	:any;
	grade	:any;
	courseid	:any;
	lesson_num	:any;
	lesson_type	:any;
	userid	:any;
	phone	:any;
	teacherid	:any;
	assistantid	:any;
	teacher_nick	:any;
	has_quiz	:any;
	lesson_start	:any;
	lesson_end	:any;
	lesson_intro	:any;
	lesson_status	:any;
	lesson_count	:any;
	confirm_flag	:any;
	confirm_adminid	:any;
	confirm_time	:any;
	confirm_reason	:any;
	level	:any;
	teacher_money_type	:any;
	lesson_cancel_reason_type	:any;
	lesson_cancel_reason_next_lesson_time	:any;
	confirm_admin_nick	:any;
	lesson_diff	:any;
	lesson_start_str	:any;
	lesson_end_str	:any;
	confirm_time_str	:any;
	lesson_status_str	:any;
	confirm_flag_str	:any;
	grade_str	:any;
	subject_str	:any;
	lesson_cancel_reason_type_str	:any;
	level_str	:any;
	teacher_money_type_str	:any;
}

/*

tofile: 
	 mkdir -p ../stu_manage; vi  ../stu_manage/course_lesson_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/stu_manage-course_lesson_list.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		sid:	$('#id_sid').val(),
		courseid:	$('#id_courseid').val(),
		all_flag:	$('#id_all_flag').val()
		});
}
$(function(){


	$('#id_sid').val(g_args.sid);
	$('#id_courseid').val(g_args.courseid);
	$('#id_all_flag').val(g_args.all_flag);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">sid</span>
                <input class="opt-change form-control" id="id_sid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["sid title", "sid", "th_sid" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">courseid</span>
                <input class="opt-change form-control" id="id_courseid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["courseid title", "courseid", "th_courseid" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">all_flag</span>
                <input class="opt-change form-control" id="id_all_flag" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["all_flag title", "all_flag", "th_all_flag" ]])!!}
*/
