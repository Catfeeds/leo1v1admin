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
		"can_sellect_all_flag"     : true
	});
	$('#id_parent_orderid').val(g_args.parent_orderid);
	$('#id_child_orderid').val(g_args.child_orderid);
	$('#id_repay_status').val(g_args.repay_status);


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
                <span class="input-group-addon">pay_status</span>
                <input class="opt-change form-control" id="id_pay_status" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">channel</span>
                <input class="opt-change form-control" id="id_channel" />
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
                <span class="input-group-addon">parent_orderid</span>
                <input class="opt-change form-control" id="id_parent_orderid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">child_orderid</span>
                <input class="opt-change form-control" id="id_child_orderid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">repay_status</span>
                <input class="opt-change form-control" id="id_repay_status" />
            </div>
        </div>
*/
