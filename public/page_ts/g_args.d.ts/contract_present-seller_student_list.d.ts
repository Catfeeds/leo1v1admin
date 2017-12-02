interface GargsStatic {
	cur_page:	number;
	status_list_str:	string;
	no_jump:	number;
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	adminid_list:	string;
	origin_assistant_role:	number;//App\Enums\Eaccount_role 
	origin:	string;
	page_num:	number;
	userid:	number;
	seller_student_status:	number;//App\Enums\Eseller_student_status 
	seller_groupid_ex:	string;
	phone_location:	string;
	page_count:	number;
	require_admin_type:	number;
	subject:	number;//App\Enums\Esubject 
	has_pad:	number;//App\Enums\Epad_type 
	tq_called_flag:	number;//App\Enums\Etq_called_flag 
	seller_resource_type:	number;//App\Enums\Eseller_resource_type 
	origin_assistantid:	number;
	admin_revisiterid:	number;
	success_flag:	number;//App\Enums\Eset_boolean 
	seller_require_change_flag:	number;
	group_seller_student_status:	number;//App\Enums\Egroup_seller_student_status 
	tmk_student_status:	number;//\App\Enums\Etmk_student_status 
	phone_name:	string;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	pay_time	:any;
	test_lesson_order_fail_desc	:any;
	test_lesson_order_fail_flag	:any;
	seller_student_sub_status	:any;
	stu_test_paper_flow_status	:any;
	stu_test_paper_flowid	:any;
	order_price	:any;
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
	seller_require_change_flag	:any;
	seller_require_change_time	:any;
	accept_adminid	:any;
	stu_request_test_lesson_time	:any;
	tea_phone	:any;
	tea_user_agent	:any;
	rate_score	:any;
	ass_phone	:any;
	ass_name	:any;
	lesson_status	:any;
	contract_status	:any;
	study_type	:any;
	lesson_count_all	:any;
	lesson_count_left	:any;
	contract_type	:any;
	price	:any;
	lesson_total	:any;
	discount_price	:any;
	order_status	:any;
	accept_flag	:any;
	init_info_pdf_url	:any;
	orderid	:any;
	lesson_plan_status	:any;
	stu_test_paper_flow_status_str	:any;
	opt_time	:any;
	last_revisit_msg_sub	:any;
	user_desc_sub	:any;
	grade_str	:any;
	subject_str	:any;
	has_pad_str	:any;
	seller_student_status_str	:any;
	tq_called_flag_str	:any;
	success_flag_str	:any;
	fail_greater_4_hour_flag_str	:any;
	lesson_used_flag_str	:any;
	test_lesson_fail_flag_str	:any;
	test_lesson_order_fail_flag_str	:any;
	confirm_admin_nick	:any;
	teacher_nick	:any;
	origin_assistant_nick	:any;
	origin_user_nick	:any;
	admin_revisiter_nick	:any;
	stu_test_paper_flag_str	:any;
	notify_lesson_flag_str	:any;
	notify_lesson_flag	:any;
	accept_admin_nick	:any;
	seller_require_change_flag_str	:any;
	lesson_price	:any;
	all_price	:any;
}

/*

tofile: 
	 mkdir -p ../contract_present; vi  ../contract_present/seller_student_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/contract_present-seller_student_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			cur_page:	$('#id_cur_page').val(),
			status_list_str:	$('#id_status_list_str').val(),
			no_jump:	$('#id_no_jump').val(),
			date_type_config:	$('#id_date_type_config').val(),
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
			adminid_list:	$('#id_adminid_list').val(),
			origin_assistant_role:	$('#id_origin_assistant_role').val(),
			origin:	$('#id_origin').val(),
			userid:	$('#id_userid').val(),
			seller_student_status:	$('#id_seller_student_status').val(),
			seller_groupid_ex:	$('#id_seller_groupid_ex').val(),
			phone_location:	$('#id_phone_location').val(),
			page_count:	$('#id_page_count').val(),
			require_admin_type:	$('#id_require_admin_type').val(),
			subject:	$('#id_subject').val(),
			has_pad:	$('#id_has_pad').val(),
			tq_called_flag:	$('#id_tq_called_flag').val(),
			seller_resource_type:	$('#id_seller_resource_type').val(),
			origin_assistantid:	$('#id_origin_assistantid').val(),
			admin_revisiterid:	$('#id_admin_revisiterid').val(),
			success_flag:	$('#id_success_flag').val(),
			seller_require_change_flag:	$('#id_seller_require_change_flag').val(),
			group_seller_student_status:	$('#id_group_seller_student_status').val(),
			tmk_student_status:	$('#id_tmk_student_status').val(),
			phone_name:	$('#id_phone_name').val()
        });
    }

	Enum_map.append_option_list("account_role",$("#id_origin_assistant_role"));
	Enum_map.append_option_list("seller_student_status",$("#id_seller_student_status"));
	Enum_map.append_option_list("subject",$("#id_subject"));
	Enum_map.append_option_list("pad_type",$("#id_has_pad"));
	Enum_map.append_option_list("tq_called_flag",$("#id_tq_called_flag"));
	Enum_map.append_option_list("seller_resource_type",$("#id_seller_resource_type"));
	Enum_map.append_option_list("set_boolean",$("#id_success_flag"));
	Enum_map.append_option_list("group_seller_student_status",$("#id_group_seller_student_status"));
	Enum_map.append_option_list("tmk_student_status",$("#id_tmk_student_status"));

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
	$('#id_cur_page').val(g_args.cur_page);
	$('#id_status_list_str').val(g_args.status_list_str);
	$('#id_no_jump').val(g_args.no_jump);
	$('#id_adminid_list').val(g_args.adminid_list);
	$('#id_origin_assistant_role').val(g_args.origin_assistant_role);
	$('#id_origin').val(g_args.origin);
	$('#id_userid').val(g_args.userid);
	$('#id_seller_student_status').val(g_args.seller_student_status);
	$('#id_seller_groupid_ex').val(g_args.seller_groupid_ex);
	$('#id_phone_location').val(g_args.phone_location);
	$('#id_page_count').val(g_args.page_count);
	$('#id_require_admin_type').val(g_args.require_admin_type);
	$('#id_subject').val(g_args.subject);
	$('#id_has_pad').val(g_args.has_pad);
	$('#id_tq_called_flag').val(g_args.tq_called_flag);
	$('#id_seller_resource_type').val(g_args.seller_resource_type);
	$('#id_origin_assistantid').val(g_args.origin_assistantid);
	$('#id_admin_revisiterid').val(g_args.admin_revisiterid);
	$('#id_success_flag').val(g_args.success_flag);
	$('#id_seller_require_change_flag').val(g_args.seller_require_change_flag);
	$('#id_group_seller_student_status').val(g_args.group_seller_student_status);
	$('#id_tmk_student_status').val(g_args.tmk_student_status);
	$('#id_phone_name').val(g_args.phone_name);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">cur_page</span>
                <input class="opt-change form-control" id="id_cur_page" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">status_list_str</span>
                <input class="opt-change form-control" id="id_status_list_str" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">no_jump</span>
                <input class="opt-change form-control" id="id_no_jump" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">adminid_list</span>
                <input class="opt-change form-control" id="id_adminid_list" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">account_role</span>
                <select class="opt-change form-control" id="id_origin_assistant_role" >
                </select>
            </div>
        </div>

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
                <span class="input-group-addon">seller_student_status</span>
                <select class="opt-change form-control" id="id_seller_student_status" >
                </select>
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">seller_groupid_ex</span>
                <input class="opt-change form-control" id="id_seller_groupid_ex" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">phone_location</span>
                <input class="opt-change form-control" id="id_phone_location" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">page_count</span>
                <input class="opt-change form-control" id="id_page_count" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">require_admin_type</span>
                <input class="opt-change form-control" id="id_require_admin_type" />
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
                <span class="input-group-addon">TQ</span>
                <select class="opt-change form-control" id="id_tq_called_flag" >
                </select>
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">资源</span>
                <select class="opt-change form-control" id="id_seller_resource_type" >
                </select>
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">origin_assistantid</span>
                <input class="opt-change form-control" id="id_origin_assistantid" />
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
                <span class="input-group-addon">set_boolean</span>
                <select class="opt-change form-control" id="id_success_flag" >
                </select>
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">seller_require_change_flag</span>
                <input class="opt-change form-control" id="id_seller_require_change_flag" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">年</span>
                <select class="opt-change form-control" id="id_group_seller_student_status" >
                </select>
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
                <span class="input-group-addon">phone_name</span>
                <input class="opt-change form-control" id="id_phone_name" />
            </div>
        </div>
*/
