interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	stu_from_type:	number;
	contract_type:	number;//枚举: App\Enums\Econtract_type
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	title	:any;
	order_count	:any;
	money	:any;
}

/*

tofile: 
	 mkdir -p ../tongji; vi  ../tongji/contract.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji-contract.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		date_type_config:	$('#id_date_type_config').val(),
		date_type:	$('#id_date_type').val(),
		opt_date_type:	$('#id_opt_date_type').val(),
		start_time:	$('#id_start_time').val(),
		end_time:	$('#id_end_time').val(),
		stu_from_type:	$('#id_stu_from_type').val(),
		contract_type:	$('#id_contract_type').val()
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
	$('#id_stu_from_type').val(g_args.stu_from_type);
	$('#id_contract_type').admin_set_select_field({
		"enum_type"    : "contract_type",
		"field_name" : "contract_type",
		"select_value" : g_args.contract_type,
		"onChange"     : load_data,
		"multi_select_flag"     : false ,
		"th_input_id"  : "th_contract_type",
		"only_show_in_th_input"     : false,
		"btn_id_config"     : {},
	});


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
                <span class="input-group-addon">stu_from_type</span>
                <input class="opt-change form-control" id="id_stu_from_type" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["stu_from_type title", "stu_from_type", "th_stu_from_type" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">contract_type</span>
                <select class="opt-change form-control" id="id_contract_type" >
                </select>
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["contract_type title", "contract_type", "th_contract_type" ]])!!}
*/
