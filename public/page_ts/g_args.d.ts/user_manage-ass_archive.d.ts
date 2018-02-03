interface GargsStatic {
	order_by_str:	string;
	test_user:	number;
	originid:	number;
	grade:	string;//枚举列表: \App\Enums\Egrade
 	user_name:	string;
	phone:	string;
	teacherid:	number;
	student_type:	number;
	assistantid:	number;
	page_num:	number;
	page_count:	number;
	userid:	number;
	revisit_flag:	number;
	warning_stu:	number;
	revisit_warn_flag:	number;
	refund_warn:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	userid	:any;
	type	:any;
	refund_warning_level	:any;
	lesson_num	:any;
	is_auto_set_type_flag	:any;
	stu_lesson_stop_reason	:any;
	phone	:any;
	is_test_user	:any;
	originid	:any;
	grade	:any;
	praise	:any;
	assistantid	:any;
	parent_name	:any;
	parent_type	:any;
	last_login_ip	:any;
	last_login_time	:any;
	lesson_count_all	:any;
	lesson_count_left	:any;
	user_agent	:any;
	ass_revisit_last_month_time	:any;
	ass_revisit_last_week_time	:any;
	ass_assign_time	:any;
	phone_location	:any;
	nick	:any;
	lesson_total	:any;
	type_str	:any;
	user_agent_simple	:any;
	ass_assign_time_str	:any;
	lesson_count_done	:any;
	assistant_nick	:any;
	ass_revisit_week_flag	:any;
	ass_revisit_month_flag	:any;
	ass_revisit_week_flag_str	:any;
	ass_revisit_month_flag_str	:any;
	status	:any;
	status_str	:any;
	cur	:any;
	last	:any;
	cur_str	:any;
	last_str	:any;
	location	:any;
	course_list_total	:any;
}

/*

tofile: 
	 mkdir -p ../user_manage; vi  ../user_manage/ass_archive.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage-ass_archive.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		order_by_str:	$('#id_order_by_str').val(),
		test_user:	$('#id_test_user').val(),
		originid:	$('#id_originid').val(),
		grade:	$('#id_grade').val(),
		user_name:	$('#id_user_name').val(),
		phone:	$('#id_phone').val(),
		teacherid:	$('#id_teacherid').val(),
		student_type:	$('#id_student_type').val(),
		assistantid:	$('#id_assistantid').val(),
		userid:	$('#id_userid').val(),
		revisit_flag:	$('#id_revisit_flag').val(),
		warning_stu:	$('#id_warning_stu').val(),
		revisit_warn_flag:	$('#id_revisit_warn_flag').val(),
		refund_warn:	$('#id_refund_warn').val()
		});
}
$(function(){


	$('#id_order_by_str').val(g_args.order_by_str);
	$('#id_test_user').val(g_args.test_user);
	$('#id_originid').val(g_args.originid);
	$('#id_grade').admin_set_select_field({
		"enum_type"    : "grade",
		"field_name" : "grade",
		"select_value" : g_args.grade,
		"multi_select_flag"     : true,
		"onChange"     : load_data,
		"th_input_id"  : "th_grade",
		"only_show_in_th_input"     : false,
		"btn_id_config"     : {},
	});
	$('#id_user_name').val(g_args.user_name);
	$('#id_phone').val(g_args.phone);
	$('#id_teacherid').admin_select_user_new({
		"user_type"    : "teacher",
		"select_value" : g_args.teacherid,
		"onChange"     : load_data,
		"th_input_id"  : "th_teacherid",
		"only_show_in_th_input"     : false,
		"can_select_all_flag"     : true
	});
	$('#id_student_type').val(g_args.student_type);
	$('#id_assistantid').admin_select_user_new({
		"user_type"    : "assistant",
		"select_value" : g_args.assistantid,
		"onChange"     : load_data,
		"th_input_id"  : "th_assistantid",
		"only_show_in_th_input"     : false,
		"can_select_all_flag"     : true
	});
	$('#id_userid').admin_select_user_new({
		"user_type"    : "student",
		"select_value" : g_args.userid,
		"onChange"     : load_data,
		"th_input_id"  : "th_userid",
		"only_show_in_th_input"     : false,
		"can_select_all_flag"     : true
	});
	$('#id_revisit_flag').val(g_args.revisit_flag);
	$('#id_warning_stu').val(g_args.warning_stu);
	$('#id_revisit_warn_flag').val(g_args.revisit_warn_flag);
	$('#id_refund_warn').val(g_args.refund_warn);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">order_by_str</span>
                <input class="opt-change form-control" id="id_order_by_str" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["order_by_str title", "order_by_str", "th_order_by_str" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">test_user</span>
                <input class="opt-change form-control" id="id_test_user" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["test_user title", "test_user", "th_test_user" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">originid</span>
                <input class="opt-change form-control" id="id_originid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["originid title", "originid", "th_originid" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">grade</span>
                <input class="opt-change form-control" id="id_grade" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["grade title", "grade", "th_grade" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">user_name</span>
                <input class="opt-change form-control" id="id_user_name" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["user_name title", "user_name", "th_user_name" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">phone</span>
                <input class="opt-change form-control" id="id_phone" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["phone title", "phone", "th_phone" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">teacherid</span>
                <input class="opt-change form-control" id="id_teacherid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["teacherid title", "teacherid", "th_teacherid" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">student_type</span>
                <input class="opt-change form-control" id="id_student_type" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["student_type title", "student_type", "th_student_type" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">assistantid</span>
                <input class="opt-change form-control" id="id_assistantid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["assistantid title", "assistantid", "th_assistantid" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_num title", "page_num", "th_page_num" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_count title", "page_count", "th_page_count" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">userid</span>
                <input class="opt-change form-control" id="id_userid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["userid title", "userid", "th_userid" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">revisit_flag</span>
                <input class="opt-change form-control" id="id_revisit_flag" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["revisit_flag title", "revisit_flag", "th_revisit_flag" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">warning_stu</span>
                <input class="opt-change form-control" id="id_warning_stu" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["warning_stu title", "warning_stu", "th_warning_stu" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">revisit_warn_flag</span>
                <input class="opt-change form-control" id="id_revisit_warn_flag" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["revisit_warn_flag title", "revisit_warn_flag", "th_revisit_warn_flag" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">refund_warn</span>
                <input class="opt-change form-control" id="id_refund_warn" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["refund_warn title", "refund_warn", "th_refund_warn" ]])!!}
*/
