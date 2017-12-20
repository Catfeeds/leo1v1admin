interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	grab_lesson_link:	string;
	grabid:	number;
	live_time:	number;
	adminid:	number;
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
	grabid	:any;
	grab_lesson_link	:any;
	live_time	:any;
	adminid	:any;
	create_time	:any;
	requireids	:any;
	visit_count	:any;
	grab_count	:any;
	succ_count	:any;
	fail_count	:any;
	nick	:any;
	lesson_count	:any;
}

/*

tofile: 
	 mkdir -p ../grab_lesson; vi  ../grab_lesson/get_all_grab_info.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/grab_lesson-get_all_grab_info.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		date_type_config:	$('#id_date_type_config').val(),
		date_type:	$('#id_date_type').val(),
		opt_date_type:	$('#id_opt_date_type').val(),
		start_time:	$('#id_start_time').val(),
		end_time:	$('#id_end_time').val(),
		grab_lesson_link:	$('#id_grab_lesson_link').val(),
		grabid:	$('#id_grabid').val(),
		live_time:	$('#id_live_time').val(),
		adminid:	$('#id_adminid').val()
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
	$('#id_grab_lesson_link').val(g_args.grab_lesson_link);
	$('#id_grabid').val(g_args.grabid);
	$('#id_live_time').val(g_args.live_time);
	$('#id_adminid').admin_select_user_new({
		"user_type"    : "account",
		"select_value" : g_args.adminid,
		"onChange"     : load_data,
		"th_input_id"  : "th_adminid",
		"only_show_in_th_input"     : false,
		"can_select_all_flag"     : true
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
                <span class="input-group-addon">grab_lesson_link</span>
                <input class="opt-change form-control" id="id_grab_lesson_link" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["grab_lesson_link title", "grab_lesson_link", "th_grab_lesson_link" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">grabid</span>
                <input class="opt-change form-control" id="id_grabid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["grabid title", "grabid", "th_grabid" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">live_time</span>
                <input class="opt-change form-control" id="id_live_time" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["live_time title", "live_time", "th_live_time" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">adminid</span>
                <input class="opt-change form-control" id="id_adminid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["adminid title", "adminid", "th_adminid" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_num title", "page_num", "th_page_num" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_count title", "page_count", "th_page_count" ]])!!}
*/
