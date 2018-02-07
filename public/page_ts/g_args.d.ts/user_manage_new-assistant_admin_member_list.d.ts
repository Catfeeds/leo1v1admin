interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	rate_target:	number;
	renew_target:	number;
	group_renew_target:	number;
	all_renew_target:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	main_type	:any;
	up_group_name	:any;
	group_name	:any;
	account	:any;
	main_type_class	:any;
	up_group_name_class	:any;
	group_name_class	:any;
	account_class	:any;
	level	:any;
	main_type_str	:any;
	lesson_target	:any;
	renew_target	:any;
}

/*

tofile: 
	 mkdir -p ../user_manage_new; vi  ../user_manage_new/assistant_admin_member_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-assistant_admin_member_list.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		date_type_config:	$('#id_date_type_config').val(),
		date_type:	$('#id_date_type').val(),
		opt_date_type:	$('#id_opt_date_type').val(),
		start_time:	$('#id_start_time').val(),
		end_time:	$('#id_end_time').val(),
		rate_target:	$('#id_rate_target').val(),
		renew_target:	$('#id_renew_target').val(),
		group_renew_target:	$('#id_group_renew_target').val(),
		all_renew_target:	$('#id_all_renew_target').val()
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
	$('#id_rate_target').val(g_args.rate_target);
	$('#id_renew_target').val(g_args.renew_target);
	$('#id_group_renew_target').val(g_args.group_renew_target);
	$('#id_all_renew_target').val(g_args.all_renew_target);


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
                <span class="input-group-addon">rate_target</span>
                <input class="opt-change form-control" id="id_rate_target" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["rate_target title", "rate_target", "th_rate_target" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">renew_target</span>
                <input class="opt-change form-control" id="id_renew_target" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["renew_target title", "renew_target", "th_renew_target" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">group_renew_target</span>
                <input class="opt-change form-control" id="id_group_renew_target" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["group_renew_target title", "group_renew_target", "th_group_renew_target" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">all_renew_target</span>
                <input class="opt-change form-control" id="id_all_renew_target" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["all_renew_target title", "all_renew_target", "th_all_renew_target" ]])!!}
*/
