interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	origin:	string;
	page_num:	number;
	page_count:	number;
	userid:	number;
	admin_revisiterid:	number;
	tmk_student_status:	number;//App\Enums\Etmk_student_status
	subject:	number;//App\Enums\Esubject
	has_pad:	number;//App\Enums\Epad_type
	grade:	number;//App\Enums\Egrade
	phone_name:	string;
	publish_flag:	number;//\App\Enums\Eboolean
	seller_student_status:	number;//App\Enums\Eseller_student_status
	tmk_adminid:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	tmk_set_seller_adminid	:any;
	return_publish_count	:any;
	tmk_assign_time	:any;
	tmk_student_status	:any;
	tmk_desc	:any;
	tmk_next_revisit_time	:any;
	user_agent	:any;
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
	opt_time	:any;
	grade_str	:any;
	subject_str	:any;
	has_pad_str	:any;
	tmk_student_status_str	:any;
	seller_student_status_str	:any;
	admin_revisiter_nick	:any;
	sub_assign_admin_2_nick	:any;
	teacher_nick	:any;
	success_flag_str	:any;
	test_lesson_fail_flag_str	:any;
	tmk_set_seller_adminid_nick	:any;
}

/*

tofile: 
	 mkdir -p ../seller_student_new2; vi  ../seller_student_new2/tmk_student_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student_new2-tmk_student_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type_config:	$('#id_date_type_config').val(),
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
			origin:	$('#id_origin').val(),
			userid:	$('#id_userid').val(),
			admin_revisiterid:	$('#id_admin_revisiterid').val(),
			tmk_student_status:	$('#id_tmk_student_status').val(),
			subject:	$('#id_subject').val(),
			has_pad:	$('#id_has_pad').val(),
			grade:	$('#id_grade').val(),
			phone_name:	$('#id_phone_name').val(),
			publish_flag:	$('#id_publish_flag').val(),
			seller_student_status:	$('#id_seller_student_status').val(),
			tmk_adminid:	$('#id_tmk_adminid').val()
        });
    }

	Enum_map.append_option_list("tmk_student_status",$("#id_tmk_student_status"));
	Enum_map.append_option_list("subject",$("#id_subject"));
	Enum_map.append_option_list("pad_type",$("#id_has_pad"));
	Enum_map.append_option_list("grade",$("#id_grade"));
	Enum_map.append_option_list("boolean",$("#id_publish_flag"));
	Enum_map.append_option_list("seller_student_status",$("#id_seller_student_status"));

    $('#id_date_range').select_date_range({
        'date_type' : g_args.date_type,
        'opt_date_type' : g_args.opt_date_type,
        'start_time'    : g_args.start_time,
        'end_time'      : g_args.end_time,
        date_type_config : JSON.parse( g_args.date_type_config),
        onQuery :function() {
            load_data();
        }
    });
	$('#id_origin').val(g_args.origin);
	$('#id_userid').val(g_args.userid);
	$('#id_admin_revisiterid').val(g_args.admin_revisiterid);
	$('#id_tmk_student_status').val(g_args.tmk_student_status);
	$('#id_subject').val(g_args.subject);
	$('#id_has_pad').val(g_args.has_pad);
	$('#id_grade').val(g_args.grade);
	$('#id_phone_name').val(g_args.phone_name);
	$('#id_publish_flag').val(g_args.publish_flag);
	$('#id_seller_student_status').val(g_args.seller_student_status);
	$('#id_tmk_adminid').val(g_args.tmk_adminid);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">origin</span>
                <input class="opt-change form-control" id="id_origin" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">userid</span>
                <input class="opt-change form-control" id="id_userid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">admin_revisiterid</span>
                <input class="opt-change form-control" id="id_admin_revisiterid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">tmk_student_status</span>
                <select class="opt-change form-control" id="id_tmk_student_status" >
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
                <span class="input-group-addon">Pad</span>
                <select class="opt-change form-control" id="id_has_pad" >
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

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">phone_name</span>
                <input class="opt-change form-control" id="id_phone_name" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">boolean</span>
                <select class="opt-change form-control" id="id_publish_flag" >
                </select>
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
                <span class="input-group-addon">tmk_adminid</span>
                <input class="opt-change form-control" id="id_tmk_adminid" />
            </div>
        </div>
*/
