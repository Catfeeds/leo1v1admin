interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	refund_type:	number;
	userid:	number;
	is_test_user:	number;
	page_num:	number;
	page_count:	number;
	refund_userid:	number;
	qc_flag:	number;
	has_money:	number;
	sys_operator:	string;
	assistant_nick:	string;
	seller_groupid_ex:	string;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	pay_account_admin	:any;
	qc_adminid	:any;
	qc_deal_time	:any;
	assistantid	:any;
	subject	:any;
	teacher_id	:any;
	qc_contact_status	:any;
	qc_advances_status	:any;
	qc_voluntarily_status	:any;
	userid	:any;
	phone	:any;
	discount_price	:any;
	orderid	:any;
	contract_type	:any;
	lesson_total	:any;
	flow_status	:any;
	flow_status_time	:any;
	flowid	:any;
	should_refund	:any;
	price	:any;
	invoice	:any;
	order_time	:any;
	sys_operator	:any;
	pay_account	:any;
	real_refund	:any;
	refund_status	:any;
	apply_time	:any;
	refund_userid	:any;
	contractid	:any;
	save_info	:any;
	refund_info	:any;
	file_url	:any;
	grade	:any;
	need_receipt	:any;
	is_staged_flag	:any;
	deal_nick	:any;
	ass_nick	:any;
	tea_nick	:any;
	subject_str	:any;
	is_staged_flag_str	:any;
	user_nick	:any;
	refund_user	:any;
	apply_time_str	:any;
	refund_status_str	:any;
	flow_status_str	:any;
	contract_type_str	:any;
	need_receipt_str	:any;
	grade_str	:any;
	qc_advances_status_str	:any;
	qc_contact_status_str	:any;
	qc_voluntarily_status_str	:any;
	order_time_str	:any;
	is_pass	:any;
	qc_other_reason	:any;
	qc_analysia	:any;
	qc_reply	:any;
	duty	:any;
	duty_str	:any;
	max_time_str	:any;
	min_time_str	:any;
	助教部一级原因	:any;
	助教部二级原因	:any;
	助教部三级原因	:any;
	助教部reason	:any;
	助教部dep_score	:any;
	助教部扣分值	:any;
	助教部责任值	:any;
	教务部一级原因	:any;
	教务部二级原因	:any;
	教务部三级原因	:any;
	教务部reason	:any;
	教务部dep_score	:any;
	教务部扣分值	:any;
	教务部责任值	:any;
	老师管理一级原因	:any;
	老师管理二级原因	:any;
	老师管理三级原因	:any;
	老师管理reason	:any;
	老师管理dep_score	:any;
	老师管理扣分值	:any;
	老师管理责任值	:any;
	教学部一级原因	:any;
	教学部二级原因	:any;
	教学部三级原因	:any;
	教学部reason	:any;
	教学部dep_score	:any;
	教学部扣分值	:any;
	教学部责任值	:any;
	产品问题一级原因	:any;
	产品问题二级原因	:any;
	产品问题三级原因	:any;
	产品问题reason	:any;
	产品问题dep_score	:any;
	产品问题扣分值	:any;
	产品问题责任值	:any;
	咨询部一级原因	:any;
	咨询部二级原因	:any;
	咨询部三级原因	:any;
	咨询部reason	:any;
	咨询部dep_score	:any;
	咨询部扣分值	:any;
	咨询部责任值	:any;
	客户情况变化一级原因	:any;
	客户情况变化二级原因	:any;
	客户情况变化三级原因	:any;
	客户情况变化reason	:any;
	客户情况变化dep_score	:any;
	客户情况变化扣分值	:any;
	客户情况变化责任值	:any;
	老师一级原因	:any;
	老师二级原因	:any;
	老师三级原因	:any;
	老师reason	:any;
	老师dep_score	:any;
	老师扣分值	:any;
	老师责任值	:any;
	科目一级原因	:any;
	科目二级原因	:any;
	科目三级原因	:any;
	科目reason	:any;
	科目dep_score	:any;
	科目扣分值	:any;
	科目责任值	:any;
}

/*

tofile: 
	 mkdir -p ../user_manage; vi  ../user_manage/refund_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage-refund_list.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		date_type_config:	$('#id_date_type_config').val(),
		date_type:	$('#id_date_type').val(),
		opt_date_type:	$('#id_opt_date_type').val(),
		start_time:	$('#id_start_time').val(),
		end_time:	$('#id_end_time').val(),
		refund_type:	$('#id_refund_type').val(),
		userid:	$('#id_userid').val(),
		is_test_user:	$('#id_is_test_user').val(),
		refund_userid:	$('#id_refund_userid').val(),
		qc_flag:	$('#id_qc_flag').val(),
		has_money:	$('#id_has_money').val(),
		sys_operator:	$('#id_sys_operator').val(),
		assistant_nick:	$('#id_assistant_nick').val(),
		seller_groupid_ex:	$('#id_seller_groupid_ex').val()
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
	$('#id_refund_type').val(g_args.refund_type);
	$('#id_userid').admin_select_user_new({
		"user_type"    : "student",
		"select_value" : g_args.userid,
		"onChange"     : load_data,
		"th_input_id"  : "th_userid",
		"only_show_in_th_input"     : false,
		"can_select_all_flag"     : true
	});
	$('#id_is_test_user').val(g_args.is_test_user);
	$('#id_refund_userid').val(g_args.refund_userid);
	$('#id_qc_flag').val(g_args.qc_flag);
	$('#id_has_money').val(g_args.has_money);
	$('#id_sys_operator').val(g_args.sys_operator);
	$('#id_assistant_nick').val(g_args.assistant_nick);
	$('#id_seller_groupid_ex').val(g_args.seller_groupid_ex);


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
                <span class="input-group-addon">refund_type</span>
                <input class="opt-change form-control" id="id_refund_type" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["refund_type title", "refund_type", "th_refund_type" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">userid</span>
                <input class="opt-change form-control" id="id_userid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["userid title", "userid", "th_userid" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">is_test_user</span>
                <input class="opt-change form-control" id="id_is_test_user" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["is_test_user title", "is_test_user", "th_is_test_user" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_num title", "page_num", "th_page_num" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_count title", "page_count", "th_page_count" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">refund_userid</span>
                <input class="opt-change form-control" id="id_refund_userid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["refund_userid title", "refund_userid", "th_refund_userid" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">qc_flag</span>
                <input class="opt-change form-control" id="id_qc_flag" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["qc_flag title", "qc_flag", "th_qc_flag" ]])!!}

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
                <span class="input-group-addon">assistant_nick</span>
                <input class="opt-change form-control" id="id_assistant_nick" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["assistant_nick title", "assistant_nick", "th_assistant_nick" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">seller_groupid_ex</span>
                <input class="opt-change form-control" id="id_seller_groupid_ex" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["seller_groupid_ex title", "seller_groupid_ex", "th_seller_groupid_ex" ]])!!}
*/
