interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	order_by_str:	string;
	orderid:	number;
	contract_type:	string;//枚举列表: \App\Enums\Econtract_type
 	contract_status:	string;//枚举列表: \App\Enums\Econtract_status
 	config_courseid:	number;
	test_user:	number;//枚举: \App\Enums\Eboolean
	studentid:	number;
	page_num:	number;
	page_count:	number;
	has_money:	number;
	sys_operator:	string;
	stu_from_type:	number;
	account_role:	number;
	seller_groupid_ex:	string;
	grade:	string;//枚举列表: \App\Enums\Egrade
 	subject:	string;//枚举列表: \App\Enums\Esubject
 	self_adminid:	number;
	tmk_adminid:	number;
	teacherid:	number;
	origin_userid:	number;
	referral_adminid:	number;
	assistantid:	number;
	from_key:	string;
	from_url:	string;
	order_activity_type:	number;//枚举: \App\Enums\Eorder_activity_type
	spec_flag:	number;//枚举: \App\Enums\Eboolean
	adminid:	number;
	account_role_self:	number;
	acc:	number;
	ass_master_flag:	number;
	show_download:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	first_check_time	:any;
	order_price_desc	:any;
	promotion_spec_is_not_spec_flag	:any;
	promotion_spec_diff_money	:any;
	origin_assistantid	:any;
	from_parent_order_type	:any;
	lesson_count_all	:any;
	userid	:any;
	get_packge_time	:any;
	order_stamp_flag	:any;
	flowid	:any;
	flow_status	:any;
	flow_post_msg	:any;
	teacherid	:any;
	lesson_start	:any;
	lesson_end	:any;
	tmk_adminid	:any;
	user_agent	:any;
	orderid	:any;
	order_time	:any;
	stu_from_type	:any;
	is_new_stu	:any;
	contractid	:any;
	from_key	:any;
	from_url	:any;
	contract_type	:any;
	contract_status	:any;
	invoice	:any;
	is_invoice	:any;
	contract_starttime	:any;
	taobao_orderid	:any;
	default_lesson_count	:any;
	contract_endtime	:any;
	grade	:any;
	lesson_total	:any;
	price	:any;
	discount_price	:any;
	discount_reason	:any;
	phone_location	:any;
	competition_flag	:any;
	lesson_left	:any;
	address	:any;
	origin_userid	:any;
	except_lesson_count	:any;
	week_lesson_num	:any;
	stu_nick	:any;
	ass_assign_time	:any;
	subject	:any;
	stu_self_nick	:any;
	parent_nick	:any;
	phone	:any;
	origin	:any;
	sys_operator	:any;
	from_type	:any;
	config_lesson_account_id	:any;
	config_courseid	:any;
	check_money_flag	:any;
	check_money_time	:any;
	check_money_adminid	:any;
	check_money_desc	:any;
	assistantid	:any;
	init_info_pdf_url	:any;
	title	:any;
	need_receipt	:any;
	order_promotion_type	:any;
	promotion_discount_price	:any;
	promotion_present_lesson	:any;
	promotion_spec_discount	:any;
	promotion_spec_present_lesson	:any;
	ass_master_adminid	:any;
	master_nick	:any;
	pdf_url	:any;
	pre_price	:any;
	pre_pay_time	:any;
	pre_from_orderno	:any;
	is_staged_flag	:any;
	can_period_flag	:any;
	is_new_stu_str	:any;
	grade_str	:any;
	stu_from_type_str	:any;
	from_parent_order_type_str	:any;
	contract_status_str	:any;
	contract_type_str	:any;
	subject_str	:any;
	from_type_str	:any;
	tmk_admin_nick	:any;
	assistant_nick	:any;
	origin_assistant_nick	:any;
	teacher_nick	:any;
	order_left	:any;
	competition_flag_str	:any;
	per_price	:any;
	flow_status_str	:any;
	pre_money_info	:any;
	promotion_spec_is_not_spec_flag_str	:any;
	status_color	:any;
	is_staged_flag_str	:any;
	phone_hide	:any;
	first_check_time_str	:any;
	hasCheck	:any;
}

/*

tofile: 
	 mkdir -p ../user_manage; vi  ../user_manage/contract_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage-contract_list.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		date_type_config:	$('#id_date_type_config').val(),
		date_type:	$('#id_date_type').val(),
		opt_date_type:	$('#id_opt_date_type').val(),
		start_time:	$('#id_start_time').val(),
		end_time:	$('#id_end_time').val(),
		order_by_str:	$('#id_order_by_str').val(),
		orderid:	$('#id_orderid').val(),
		contract_type:	$('#id_contract_type').val(),
		contract_status:	$('#id_contract_status').val(),
		config_courseid:	$('#id_config_courseid').val(),
		test_user:	$('#id_test_user').val(),
		studentid:	$('#id_studentid').val(),
		has_money:	$('#id_has_money').val(),
		sys_operator:	$('#id_sys_operator').val(),
		stu_from_type:	$('#id_stu_from_type').val(),
		account_role:	$('#id_account_role').val(),
		seller_groupid_ex:	$('#id_seller_groupid_ex').val(),
		grade:	$('#id_grade').val(),
		subject:	$('#id_subject').val(),
		self_adminid:	$('#id_self_adminid').val(),
		tmk_adminid:	$('#id_tmk_adminid').val(),
		teacherid:	$('#id_teacherid').val(),
		origin_userid:	$('#id_origin_userid').val(),
		referral_adminid:	$('#id_referral_adminid').val(),
		assistantid:	$('#id_assistantid').val(),
		from_key:	$('#id_from_key').val(),
		from_url:	$('#id_from_url').val(),
		order_activity_type:	$('#id_order_activity_type').val(),
		spec_flag:	$('#id_spec_flag').val(),
		adminid:	$('#id_adminid').val(),
		account_role_self:	$('#id_account_role_self').val(),
		acc:	$('#id_acc').val(),
		ass_master_flag:	$('#id_ass_master_flag').val(),
		show_download:	$('#id_show_download').val()
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
	$('#id_order_by_str').val(g_args.order_by_str);
	$('#id_orderid').val(g_args.orderid);
	$('#id_contract_type').admin_set_select_field({
		"enum_type"    : "contract_type",
		"field_name" : "contract_type",
		"select_value" : g_args.contract_type,
		"multi_select_flag"     : true,
		"onChange"     : load_data,
		"th_input_id"  : "th_contract_type",
		"only_show_in_th_input"     : false,
		"btn_id_config"     : {},
	});
	$('#id_contract_status').admin_set_select_field({
		"enum_type"    : "contract_status",
		"field_name" : "contract_status",
		"select_value" : g_args.contract_status,
		"multi_select_flag"     : true,
		"onChange"     : load_data,
		"th_input_id"  : "th_contract_status",
		"only_show_in_th_input"     : false,
		"btn_id_config"     : {},
	});
	$('#id_config_courseid').val(g_args.config_courseid);
	$('#id_test_user').admin_set_select_field({
		"enum_type"    : "boolean",
		"field_name" : "test_user",
		"select_value" : g_args.test_user,
		"onChange"     : load_data,
		"multi_select_flag"     : false ,
		"th_input_id"  : "th_test_user",
		"only_show_in_th_input"     : false,
		"btn_id_config"     : {},
	});
	$('#id_studentid').admin_select_user_new({
		"user_type"    : "student",
		"select_value" : g_args.studentid,
		"onChange"     : load_data,
		"th_input_id"  : "th_studentid",
		"only_show_in_th_input"     : false,
		"can_select_all_flag"     : true
	});
	$('#id_has_money').val(g_args.has_money);
	$('#id_sys_operator').val(g_args.sys_operator);
	$('#id_stu_from_type').val(g_args.stu_from_type);
	$('#id_account_role').val(g_args.account_role);
	$('#id_seller_groupid_ex').val(g_args.seller_groupid_ex);
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
	$('#id_subject').admin_set_select_field({
		"enum_type"    : "subject",
		"field_name" : "subject",
		"select_value" : g_args.subject,
		"multi_select_flag"     : true,
		"onChange"     : load_data,
		"th_input_id"  : "th_subject",
		"only_show_in_th_input"     : false,
		"btn_id_config"     : {},
	});
	$('#id_self_adminid').val(g_args.self_adminid);
	$('#id_tmk_adminid').val(g_args.tmk_adminid);
	$('#id_teacherid').admin_select_user_new({
		"user_type"    : "teacher",
		"select_value" : g_args.teacherid,
		"onChange"     : load_data,
		"th_input_id"  : "th_teacherid",
		"only_show_in_th_input"     : false,
		"can_select_all_flag"     : true
	});
	$('#id_origin_userid').val(g_args.origin_userid);
	$('#id_referral_adminid').val(g_args.referral_adminid);
	$('#id_assistantid').admin_select_user_new({
		"user_type"    : "assistant",
		"select_value" : g_args.assistantid,
		"onChange"     : load_data,
		"th_input_id"  : "th_assistantid",
		"only_show_in_th_input"     : false,
		"can_select_all_flag"     : true
	});
	$('#id_from_key').val(g_args.from_key);
	$('#id_from_url').val(g_args.from_url);
	$('#id_order_activity_type').admin_set_select_field({
		"enum_type"    : "order_activity_type",
		"field_name" : "order_activity_type",
		"select_value" : g_args.order_activity_type,
		"onChange"     : load_data,
		"multi_select_flag"     : false ,
		"th_input_id"  : "th_order_activity_type",
		"only_show_in_th_input"     : false,
		"btn_id_config"     : {},
	});
	$('#id_spec_flag').admin_set_select_field({
		"enum_type"    : "boolean",
		"field_name" : "spec_flag",
		"select_value" : g_args.spec_flag,
		"onChange"     : load_data,
		"multi_select_flag"     : false ,
		"th_input_id"  : "th_spec_flag",
		"only_show_in_th_input"     : false,
		"btn_id_config"     : {},
	});
	$('#id_adminid').admin_select_user_new({
		"user_type"    : "account",
		"select_value" : g_args.adminid,
		"onChange"     : load_data,
		"th_input_id"  : "th_adminid",
		"only_show_in_th_input"     : false,
		"can_select_all_flag"     : true
	});
	$('#id_account_role_self').val(g_args.account_role_self);
	$('#id_acc').val(g_args.acc);
	$('#id_ass_master_flag').val(g_args.ass_master_flag);
	$('#id_show_download').val(g_args.show_download);


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
                <span class="input-group-addon">order_by_str</span>
                <input class="opt-change form-control" id="id_order_by_str" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["order_by_str title", "order_by_str", "th_order_by_str" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">orderid</span>
                <input class="opt-change form-control" id="id_orderid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["orderid title", "orderid", "th_orderid" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">contract_type</span>
                <input class="opt-change form-control" id="id_contract_type" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["contract_type title", "contract_type", "th_contract_type" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">contract_status</span>
                <input class="opt-change form-control" id="id_contract_status" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["contract_status title", "contract_status", "th_contract_status" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">config_courseid</span>
                <input class="opt-change form-control" id="id_config_courseid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["config_courseid title", "config_courseid", "th_config_courseid" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">boolean</span>
                <select class="opt-change form-control" id="id_test_user" >
                </select>
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["test_user title", "test_user", "th_test_user" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">studentid</span>
                <input class="opt-change form-control" id="id_studentid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["studentid title", "studentid", "th_studentid" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_num title", "page_num", "th_page_num" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_count title", "page_count", "th_page_count" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">has_money</span>
                <input class="opt-change form-control" id="id_has_money" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["has_money title", "has_money", "th_has_money" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">sys_operator</span>
                <input class="opt-change form-control" id="id_sys_operator" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["sys_operator title", "sys_operator", "th_sys_operator" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">stu_from_type</span>
                <input class="opt-change form-control" id="id_stu_from_type" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["stu_from_type title", "stu_from_type", "th_stu_from_type" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">account_role</span>
                <input class="opt-change form-control" id="id_account_role" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["account_role title", "account_role", "th_account_role" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">seller_groupid_ex</span>
                <input class="opt-change form-control" id="id_seller_groupid_ex" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["seller_groupid_ex title", "seller_groupid_ex", "th_seller_groupid_ex" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">grade</span>
                <input class="opt-change form-control" id="id_grade" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["grade title", "grade", "th_grade" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">subject</span>
                <input class="opt-change form-control" id="id_subject" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["subject title", "subject", "th_subject" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">self_adminid</span>
                <input class="opt-change form-control" id="id_self_adminid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["self_adminid title", "self_adminid", "th_self_adminid" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">tmk_adminid</span>
                <input class="opt-change form-control" id="id_tmk_adminid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["tmk_adminid title", "tmk_adminid", "th_tmk_adminid" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">teacherid</span>
                <input class="opt-change form-control" id="id_teacherid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["teacherid title", "teacherid", "th_teacherid" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">origin_userid</span>
                <input class="opt-change form-control" id="id_origin_userid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["origin_userid title", "origin_userid", "th_origin_userid" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">referral_adminid</span>
                <input class="opt-change form-control" id="id_referral_adminid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["referral_adminid title", "referral_adminid", "th_referral_adminid" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">assistantid</span>
                <input class="opt-change form-control" id="id_assistantid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["assistantid title", "assistantid", "th_assistantid" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">from_key</span>
                <input class="opt-change form-control" id="id_from_key" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["from_key title", "from_key", "th_from_key" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">from_url</span>
                <input class="opt-change form-control" id="id_from_url" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["from_url title", "from_url", "th_from_url" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">order_activity_type</span>
                <select class="opt-change form-control" id="id_order_activity_type" >
                </select>
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["order_activity_type title", "order_activity_type", "th_order_activity_type" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">boolean</span>
                <select class="opt-change form-control" id="id_spec_flag" >
                </select>
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["spec_flag title", "spec_flag", "th_spec_flag" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">adminid</span>
                <input class="opt-change form-control" id="id_adminid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["adminid title", "adminid", "th_adminid" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">account_role_self</span>
                <input class="opt-change form-control" id="id_account_role_self" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["account_role_self title", "account_role_self", "th_account_role_self" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">acc</span>
                <input class="opt-change form-control" id="id_acc" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["acc title", "acc", "th_acc" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">ass_master_flag</span>
                <input class="opt-change form-control" id="id_ass_master_flag" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["ass_master_flag title", "ass_master_flag", "th_ass_master_flag" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">show_download</span>
                <input class="opt-change form-control" id="id_show_download" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["show_download title", "show_download", "th_show_download" ]])!!}
*/
