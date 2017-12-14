interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	contract_type:	number;
	contract_status:	number;
	pay_status:	number;
	channel:	number;
	userid:	number;
	parent_orderid:	number;
	child_orderid:	number;
	repay_status:	number;
	page_num:	number;
	page_count:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
}

/*

tofile: 
	 mkdir -p ../period_order; vi  ../period_order/get_all_payed_order_info.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/period_order-get_all_payed_order_info.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		date_type_config:	$('#id_date_type_config').val(),
		date_type:	$('#id_date_type').val(),
		opt_date_type:	$('#id_opt_date_type').val(),
		start_time:	$('#id_start_time').val(),
		end_time:	$('#id_end_time').val(),
		contract_type:	$('#id_contract_type').val(),
		contract_status:	$('#id_contract_status').val(),
		pay_status:	$('#id_pay_status').val(),
		channel:	$('#id_channel').val(),
		userid:	$('#id_userid').val(),
		parent_orderid:	$('#id_parent_orderid').val(),
		child_orderid:	$('#id_child_orderid').val(),
		repay_status:	$('#id_repay_status').val()
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
	$('#id_contract_type').val(g_args.contract_type);
	$('#id_contract_status').val(g_args.contract_status);
	$('#id_pay_status').val(g_args.pay_status);
	$('#id_channel').val(g_args.channel);
	$('#id_userid').admin_select_user_new({
		"user_type"    : "student",
		"select_value" : g_args.userid,
		"onChange"     : load_data,
		"th_input_id"  : "th_userid",
		"only_show_in_th_input"     : false,
		"can_select_all_flag"     : true
	});
	$('#id_parent_orderid').val(g_args.parent_orderid);
	$('#id_child_orderid').val(g_args.child_orderid);
	$('#id_repay_status').val(g_args.repay_status);


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
                <span class="input-group-addon">pay_status</span>
                <input class="opt-change form-control" id="id_pay_status" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["pay_status title", "pay_status", "th_pay_status" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">channel</span>
                <input class="opt-change form-control" id="id_channel" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["channel title", "channel", "th_channel" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">userid</span>
                <input class="opt-change form-control" id="id_userid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["userid title", "userid", "th_userid" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">parent_orderid</span>
                <input class="opt-change form-control" id="id_parent_orderid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["parent_orderid title", "parent_orderid", "th_parent_orderid" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">child_orderid</span>
                <input class="opt-change form-control" id="id_child_orderid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["child_orderid title", "child_orderid", "th_child_orderid" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">repay_status</span>
                <input class="opt-change form-control" id="id_repay_status" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["repay_status title", "repay_status", "th_repay_status" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_num title", "page_num", "th_page_num" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_count title", "page_count", "th_page_count" ]])!!}
*/
