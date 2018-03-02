interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	monthtime_flag:	number;
	main_type_flag:	number;
	group_list:	number;
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
	first_group_name	:any;
	up_group_name	:any;
	group_name	:any;
	account	:any;
	main_type_class	:any;
	first_group_name_class	:any;
	up_group_name_class	:any;
	group_name_class	:any;
	account_class	:any;
	level	:any;
	become_member_time	:any;
	leave_member_time	:any;
	del_flag	:any;
	log_info	:any;
	del_flag_str	:any;
	become_member_num	:any;
	leave_member_num	:any;
	main_type_str	:any;
}

/*

tofile: 
	 mkdir -p ../user_manage_new; vi  ../user_manage_new/admin_group_manage.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-admin_group_manage.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		date_type_config:	$('#id_date_type_config').val(),
		date_type:	$('#id_date_type').val(),
		opt_date_type:	$('#id_opt_date_type').val(),
		start_time:	$('#id_start_time').val(),
		end_time:	$('#id_end_time').val(),
		monthtime_flag:	$('#id_monthtime_flag').val(),
		main_type_flag:	$('#id_main_type_flag').val(),
		group_list:	$('#id_group_list').val()
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
	$('#id_monthtime_flag').val(g_args.monthtime_flag);
	$('#id_main_type_flag').val(g_args.main_type_flag);
	$('#id_group_list').val(g_args.group_list);


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
                <span class="input-group-addon">monthtime_flag</span>
                <input class="opt-change form-control" id="id_monthtime_flag" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["monthtime_flag title", "monthtime_flag", "th_monthtime_flag" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">main_type_flag</span>
                <input class="opt-change form-control" id="id_main_type_flag" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["main_type_flag title", "main_type_flag", "th_main_type_flag" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">group_list</span>
                <input class="opt-change form-control" id="id_group_list" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["group_list title", "group_list", "th_group_list" ]])!!}
*/
