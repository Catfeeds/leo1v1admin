interface GargsStatic {
	hold_flag:	number;//App\Enums\Eboolean
	phone_name:	string;
	page_num:	number;
	page_count:	number;
	seller_student_status:	number;//App\Enums\Eseller_student_status
	subject:	number;//App\Enums\Esubject
	grade:	number;//App\Enums\Egrade
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	cur_require_adminid	:any;
	test_lesson_order_fail_flag	:any;
	user_agent	:any;
	hold_flag	:any;
	lesson_count_left	:any;
	notify_lesson_day1	:any;
	notify_lesson_day2	:any;
	confirm_time	:any;
	confirm_adminid	:any;
	fail_greater_4_hour_flag	:any;
	current_lessonid	:any;
	test_lesson_fail_flag	:any;
	success_flag	:any;
	fail_reason	:any;
	current_require_id	:any;
	test_lesson_subject_id	:any;
	add_time	:any;
	seller_student_status	:any;
	userid	:any;
	nick	:any;
	origin	:any;
	phone_location	:any;
	phone	:any;
	sub_assign_adminid_2	:any;
	admin_revisiterid	:any;
	admin_assign_time	:any;
	sub_assign_time_2	:any;
	origin_assistantid	:any;
	origin_userid	:any;
	subject	:any;
	grade	:any;
	user_desc	:any;
	has_pad	:any;
	last_revisit_time	:any;
	last_revisit_msg	:any;
	tq_called_flag	:any;
	next_revisit_time	:any;
	lesson_start	:any;
	lesson_del_flag	:any;
	require_time	:any;
	teacherid	:any;
	stu_test_paper	:any;
	tea_download_paper_time	:any;
	seller_require_change_flag	:any;
	index	:any;
	set_not_hold_err_msg	:any;
	seller_student_status_str	:any;
	hold_flag_str	:any;
	grade_str	:any;
	subject_str	:any;
	has_pad_str	:any;
}

/*

tofile: 
	 mkdir -p ../seller_student_new; vi  ../seller_student_new/get_hold_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student_new-get_hold_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			hold_flag:	$('#id_hold_flag').val(),
			phone_name:	$('#id_phone_name').val(),
			seller_student_status:	$('#id_seller_student_status').val(),
			subject:	$('#id_subject').val(),
			grade:	$('#id_grade').val()
        });
    }

	Enum_map.append_option_list("boolean",$("#id_hold_flag"));
	Enum_map.append_option_list("seller_student_status",$("#id_seller_student_status"));
	Enum_map.append_option_list("subject",$("#id_subject"));
	Enum_map.append_option_list("grade",$("#id_grade"));

	$('#id_hold_flag').val(g_args.hold_flag);
	$('#id_phone_name').val(g_args.phone_name);
	$('#id_seller_student_status').val(g_args.seller_student_status);
	$('#id_subject').val(g_args.subject);
	$('#id_grade').val(g_args.grade);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">boolean</span>
                <select class="opt-change form-control" id="id_hold_flag" >
                </select>
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">phone_name</span>
                <input class="opt-change form-control" id="id_phone_name" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">seller_student_status</span>
                <select class="opt-change form-control" id="id_seller_student_status" >
                </select>
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">科目</span>
                <select class="opt-change form-control" id="id_subject" >
                </select>
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">年级</span>
                <select class="opt-change form-control" id="id_grade" >
                </select>
            </div>
        </div>
*/
