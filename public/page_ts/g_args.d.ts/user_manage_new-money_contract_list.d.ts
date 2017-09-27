interface GargsStatic {
	start_time:	string;
	end_time:	string;
	userid_flag:	number;
	contract_type:	number;
	contract_status:	string;//枚举列表: \App\Enums\Econtract_status
 	is_test_user:	number;//App\Enums\Eboolean
	studentid:	number;
	check_money_flag:	number;
	origin:	string;
	page_num:	number;
	page_count:	number;
	from_type:	number;
	account_role:	number;
	sys_operator:	string;
	need_receipt:	number;//App\Enums\Eboolean
	userid_stu:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	order_price_desc	:any;
	from_parent_order_type	:any;
	lesson_count_all	:any;
	userid	:any;
	get_packge_time	:any;
	order_stamp_flag	:any;
	flowid	:any;
	flow_status	:any;
	flow_post_msg	:any;
	teacherid	:any;
	tmk_adminid	:any;
	user_agent	:any;
	orderid	:any;
	order_time	:any;
	stu_from_type	:any;
	is_new_stu	:any;
	contractid	:any;
	contract_type	:any;
	contract_status	:any;
	invoice	:any;
	is_invoice	:any;
	channel	:any;
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
	lesson_start	:any;
	ass_master_adminid	:any;
	master_nick	:any;
	master_assign_time	:any;
	pdf_url	:any;
	pre_from_orderno	:any;
	from_orderno	:any;
	pre_pay_time	:any;
	pre_price	:any;
	order_time_1_day_flag	:any;
	check_money_time_1_day_flag	:any;
	order_time_1_day_flag_str	:any;
	check_money_time_1_day_flag_str	:any;
	from_parent_order_type_str	:any;
	order_stamp_flag_str	:any;
	is_invoice_str	:any;
	grade_str	:any;
	contract_type_str	:any;
	stu_from_type_str	:any;
	from_type_str	:any;
	check_money_flag_str	:any;
	competition_flag_str	:any;
	check_money_admin_nick	:any;
	order_promotion_type_str	:any;
	flow_status_str	:any;
	pre_status	:any;
}

/*

tofile: 
	 mkdir -p ../user_manage_new; vi  ../user_manage_new/money_contract_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-money_contract_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
			userid_flag:	$('#id_userid_flag').val(),
			contract_type:	$('#id_contract_type').val(),
			contract_status:	$('#id_contract_status').val(),
			is_test_user:	$('#id_is_test_user').val(),
			studentid:	$('#id_studentid').val(),
			check_money_flag:	$('#id_check_money_flag').val(),
			origin:	$('#id_origin').val(),
			from_type:	$('#id_from_type').val(),
			account_role:	$('#id_account_role').val(),
			sys_operator:	$('#id_sys_operator').val(),
			need_receipt:	$('#id_need_receipt').val(),
			userid_stu:	$('#id_userid_stu').val()
        });
    }

	Enum_map.append_option_list("boolean",$("#id_is_test_user"));
	Enum_map.append_option_list("boolean",$("#id_need_receipt"));

	$('#id_start_time').val(g_args.start_time);
	$('#id_end_time').val(g_args.end_time);
	$('#id_userid_flag').val(g_args.userid_flag);
	$('#id_contract_type').val(g_args.contract_type);
	$('#id_contract_status').val(g_args.contract_status);
	$.enum_multi_select( $('#id_contract_status'), 'contract_status', function(){load_data();} )
	$('#id_is_test_user').val(g_args.is_test_user);
	$('#id_studentid').val(g_args.studentid);
	$('#id_check_money_flag').val(g_args.check_money_flag);
	$('#id_origin').val(g_args.origin);
	$('#id_from_type').val(g_args.from_type);
	$('#id_account_role').val(g_args.account_role);
	$('#id_sys_operator').val(g_args.sys_operator);
	$('#id_need_receipt').val(g_args.need_receipt);
	$('#id_userid_stu').val(g_args.userid_stu);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">start_time</span>
                <input class="opt-change form-control" id="id_start_time" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">end_time</span>
                <input class="opt-change form-control" id="id_end_time" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">userid_flag</span>
                <input class="opt-change form-control" id="id_userid_flag" />
            </div>
        </div>

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
                <span class="input-group-addon">boolean</span>
                <select class="opt-change form-control" id="id_is_test_user" >
                </select>
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
                <span class="input-group-addon">check_money_flag</span>
                <input class="opt-change form-control" id="id_check_money_flag" />
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
                <span class="input-group-addon">from_type</span>
                <input class="opt-change form-control" id="id_from_type" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">account_role</span>
                <input class="opt-change form-control" id="id_account_role" />
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
                <span class="input-group-addon">boolean</span>
                <select class="opt-change form-control" id="id_need_receipt" >
                </select>
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">userid_stu</span>
                <input class="opt-change form-control" id="id_userid_stu" />
            </div>
        </div>
*/
