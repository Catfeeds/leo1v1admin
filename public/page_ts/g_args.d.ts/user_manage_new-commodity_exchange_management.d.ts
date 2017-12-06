interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	page_num:	number;
	page_count:	number;
	gift_type:	number;//App\Enums\Econtract_type
	status:	number;//App\Enums\Econtract_type
	assistantid:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	exchangeid	:any;
	nick	:any;
	phone	:any;
	exchange_time	:any;
	gift_name	:any;
	gift_type	:any;
	account	:any;
	address	:any;
	status	:any;
	consignee	:any;
	consignee_phone	:any;
	ecg_express_name	:any;
	status_str	:any;
}

/*

tofile: 
	 mkdir -p ../user_manage_new; vi  ../user_manage_new/commodity_exchange_management.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-commodity_exchange_management.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
		date_type_config:	$('#id_date_type_config').val(),
		date_type:	$('#id_date_type').val(),
		opt_date_type:	$('#id_opt_date_type').val(),
		start_time:	$('#id_start_time').val(),
		end_time:	$('#id_end_time').val(),
		gift_type:	$('#id_gift_type').val(),
		status:	$('#id_status').val(),
		assistantid:	$('#id_assistantid').val()
    });
}
$(function(){

	Enum_map.append_option_list("contract_type",$("#id_gift_type"));
	Enum_map.append_option_list("contract_type",$("#id_status"));

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
	$('#id_gift_type').val(g_args.gift_type);
	$('#id_status').val(g_args.status);
	$('#id_assistantid').val(g_args.assistantid);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">contract_type</span>
                <select class="opt-change form-control" id="id_gift_type" >
                </select>
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">contract_type</span>
                <select class="opt-change form-control" id="id_status" >
                </select>
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">assistantid</span>
                <input class="opt-change form-control" id="id_assistantid" />
            </div>
        </div>
*/
