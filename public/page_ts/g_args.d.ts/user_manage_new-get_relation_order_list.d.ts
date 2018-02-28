interface GargsStatic {
	orderid:	number;
	contract_type:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	orderid	:any;
	contract_type	:any;
	sys_operator	:any;
	userid	:any;
	price	:any;
	default_lesson_count	:any;
	lesson_total	:any;
	order_time	:any;
	from_parent_order_type	:any;
	from_parent_order_lesson_count	:any;
	contract_type_str	:any;
	self_flag_str	:any;
	student_nick	:any;
	lesson_count	:any;
}

/*

tofile: 
	 mkdir -p ../user_manage_new; vi  ../user_manage_new/get_relation_order_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-get_relation_order_list.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		orderid:	$('#id_orderid').val(),
		contract_type:	$('#id_contract_type').val()
		});
}
$(function(){


	$('#id_orderid').val(g_args.orderid);
	$('#id_contract_type').val(g_args.contract_type);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

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
*/
