interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	contract_type:	number;
	contract_status:	number;
	config_courseid:	number;
	test_user:	number;
	studentid:	number;
	page_num:	number;
	has_money:	number;
	sys_operator:	string;
	stu_from_type:	number;
	account_role:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
interface RowData {
	orderid	:any;
	order_time	:any;
	stu_from_type	:any;
	is_new_stu	:any;
	contractid	:any;
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
	userid	:any;
	competition_flag	:any;
	lesson_left	:any;
	address	:any;
	origin_userid	:any;
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
	is_new_stu_str	:any;
	grade_str	:any;
	stu_from_type_str	:any;
	contract_status_str	:any;
	contract_type_str	:any;
	subject_str	:any;
	from_type_str	:any;
	assistant_nick	:any;
	order_left	:any;
	competition_flag_str	:any;
	per_price	:any;
}

/*

tofile: 
	 mkdir -p ../user_manage ; vi  ../user_manage/contract_list_seller.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage-contract_list_seller.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
			contract_type:	$('#id_contract_type').val(),
			contract_status:	$('#id_contract_status').val(),
			config_courseid:	$('#id_config_courseid').val(),
			test_user:	$('#id_test_user').val(),
			studentid:	$('#id_studentid').val(),
			has_money:	$('#id_has_money').val(),
			sys_operator:	$('#id_sys_operator').val(),
			stu_from_type:	$('#id_stu_from_type').val(),
			account_role:	$('#id_account_role').val()
        });
    }


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
	$('#id_contract_type').val(g_args.contract_type);
	$('#id_contract_status').val(g_args.contract_status);
	$('#id_config_courseid').val(g_args.config_courseid);
	$('#id_test_user').val(g_args.test_user);
	$('#id_studentid').val(g_args.studentid);
	$('#id_has_money').val(g_args.has_money);
	$('#id_sys_operator').val(g_args.sys_operator);
	$('#id_stu_from_type').val(g_args.stu_from_type);
	$('#id_account_role').val(g_args.account_role);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">contract_type</span>
                <input class="opt-change form-control" id="id_contract_type" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">contract_status</span>
                <input class="opt-change form-control" id="id_contract_status" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">config_courseid</span>
                <input class="opt-change form-control" id="id_config_courseid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">test_user</span>
                <input class="opt-change form-control" id="id_test_user" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">studentid</span>
                <input class="opt-change form-control" id="id_studentid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">has_money</span>
                <input class="opt-change form-control" id="id_has_money" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">sys_operator</span>
                <input class="opt-change form-control" id="id_sys_operator" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">stu_from_type</span>
                <input class="opt-change form-control" id="id_stu_from_type" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">account_role</span>
                <input class="opt-change form-control" id="id_account_role" />
            </div>
        </div>
*/
