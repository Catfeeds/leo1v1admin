interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	origin:	string;
	grade:	number;//App\Enums\Egrade 
	subject:	number;//App\Enums\Esubject 
	phone:	string;
	st_application_nick:	string;
	status:	number;
	from_type:	number;
	st_arrange_lessonid:	number;
	page_num:	number;
	origin_ex:	string;
	userid:	number;
	teacherid:	number;
	confirm_flag:	number;
	require_user_type:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
interface RowData {
	id	:any;
	phone	:any;
	ass_adminid	:any;
	st_userid	:any;
	userid	:any;
	lessonid	:any;
	from_type	:any;
	st_application_time	:any;
	st_application_nick	:any;
	st_demand	:any;
	st_arrange_lessonid	:any;
	grade	:any;
	subject	:any;
	nick	:any;
	status	:any;
	origin	:any;
	phone_location	:any;
	st_from_school	:any;
	teacherid	:any;
	confirm_reason	:any;
	confirm_flag	:any;
	lesson_start	:any;
	lesson_end	:any;
	st_class_time	:any;
	has_pad	:any;
	st_test_paper	:any;
	user_desc	:any;
	admin_revisiterid	:any;
	assigned_teacherid	:any;
	last_revisit_time	:any;
	last_revisit_msg	:any;
	courseid	:any;
	stu_test_lesson_level	:any;
	stu_test_ipad_flag	:any;
	stu_score_info	:any;
	stu_character_info	:any;
	stu_request_test_lesson_time_info	:any;
	stu_request_lesson_time_info	:any;
	cancel_lesson_start	:any;
	cancel_flag	:any;
	editionid	:any;
	cancel_adminid	:any;
	cancel_time	:any;
	cancel_teacherid	:any;
	cancel_reason	:any;
	grade_str	:any;
	editionid_str	:any;
	cancel_flag_str	:any;
	subject_str	:any;
	has_pad_str	:any;
	status_str	:any;
	from_type_str	:any;
	stu_test_lesson_level_str	:any;
	stu_test_ipad_flag_str	:any;
	confirm_flag_str	:any;
	cancel_admin_nick	:any;
	assigned_teacher_nick	:any;
	cancel_teacher_nick	:any;
	lesson_time	:any;
	teacher_nick	:any;
	stu_request_lesson_time_info_str	:any;
	stu_request_test_lesson_time_info_str	:any;
	st_test_paper_str	:any;
	admin_revisiterid_nick	:any;
	parent_nick	:any;
	parent_phone	:any;
	address	:any;
}

/*

tofile: 
	 mkdir -p ../seller_student ; vi  ../seller_student/test_lesson_list_ass.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student-test_lesson_list_ass.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
			origin:	$('#id_origin').val(),
			grade:	$('#id_grade').val(),
			subject:	$('#id_subject').val(),
			phone:	$('#id_phone').val(),
			st_application_nick:	$('#id_st_application_nick').val(),
			status:	$('#id_status').val(),
			from_type:	$('#id_from_type').val(),
			st_arrange_lessonid:	$('#id_st_arrange_lessonid').val(),
			origin_ex:	$('#id_origin_ex').val(),
			userid:	$('#id_userid').val(),
			teacherid:	$('#id_teacherid').val(),
			confirm_flag:	$('#id_confirm_flag').val(),
			require_user_type:	$('#id_require_user_type').val()
        });
    }

	Enum_map.append_option_list("grade",$("#id_grade")); 
	Enum_map.append_option_list("subject",$("#id_subject")); 

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
	$('#id_grade').val(g_args.grade);
	$('#id_subject').val(g_args.subject);
	$('#id_phone').val(g_args.phone);
	$('#id_st_application_nick').val(g_args.st_application_nick);
	$('#id_status').val(g_args.status);
	$('#id_from_type').val(g_args.from_type);
	$('#id_st_arrange_lessonid').val(g_args.st_arrange_lessonid);
	$('#id_origin_ex').val(g_args.origin_ex);
	$('#id_userid').val(g_args.userid);
	$('#id_teacherid').val(g_args.teacherid);
	$('#id_confirm_flag').val(g_args.confirm_flag);
	$('#id_require_user_type').val(g_args.require_user_type);


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
                <span class="input-group-addon">年级</span>
                <select class="opt-change form-control" id="id_grade" >
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
                <span class="input-group-addon">phone</span>
                <input class="opt-change form-control" id="id_phone" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">st_application_nick</span>
                <input class="opt-change form-control" id="id_st_application_nick" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">status</span>
                <input class="opt-change form-control" id="id_status" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">from_type</span>
                <input class="opt-change form-control" id="id_from_type" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">st_arrange_lessonid</span>
                <input class="opt-change form-control" id="id_st_arrange_lessonid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">origin_ex</span>
                <input class="opt-change form-control" id="id_origin_ex" />
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
                <span class="input-group-addon">teacherid</span>
                <input class="opt-change form-control" id="id_teacherid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">confirm_flag</span>
                <input class="opt-change form-control" id="id_confirm_flag" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">require_user_type</span>
                <input class="opt-change form-control" id="id_require_user_type" />
            </div>
        </div>
*/
