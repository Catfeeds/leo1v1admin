interface GargsStatic {
	page_num:	number;
	page_count:	number;
	account_type:	number;
	is_complaint_state:	number;
	is_allot_flag:	number;
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	complaint_id	:any;
	complained_department	:any;
	complaint_type	:any;
	userid	:any;
	account_type	:any;
	complaint_info	:any;
	add_time	:any;
	current_adminid	:any;
	current_account	:any;
	complaint_state	:any;
	current_admin_assign_time	:any;
	complained_adminid	:any;
	complained_adminid_type	:any;
	complained_adminid_nick	:any;
	suggest_info	:any;
	deal_info	:any;
	deal_time	:any;
	deal_adminid	:any;
	complaint_img_url	:any;
	complaint_type_str	:any;
	complained_department_str	:any;
	complained_adminid_type_str	:any;
	complaint_state_str	:any;
	account_type_str	:any;
	deal_date	:any;
	complaint_date	:any;
	current_admin_assign_time_date	:any;
	deal_admin_nick	:any;
	user_nick	:any;
	phone	:any;
	phone_hide	:any;
	follow_state_str	:any;
}

/*

tofile: 
	 mkdir -p ../user_manage; vi  ../user_manage/qc_complaint.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage-qc_complaint.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		account_type:	$('#id_account_type').val(),
		is_complaint_state:	$('#id_is_complaint_state').val(),
		is_allot_flag:	$('#id_is_allot_flag').val(),
		date_type_config:	$('#id_date_type_config').val(),
		date_type:	$('#id_date_type').val(),
		opt_date_type:	$('#id_opt_date_type').val(),
		start_time:	$('#id_start_time').val(),
		end_time:	$('#id_end_time').val()
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
	$('#id_account_type').val(g_args.account_type);
	$('#id_is_complaint_state').val(g_args.is_complaint_state);
	$('#id_is_allot_flag').val(g_args.is_allot_flag);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...
{!!\App\Helper\Utils::th_order_gen([["page_num title", "page_num", "th_page_num" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_count title", "page_count", "th_page_count" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">account_type</span>
                <input class="opt-change form-control" id="id_account_type" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["account_type title", "account_type", "th_account_type" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">is_complaint_state</span>
                <input class="opt-change form-control" id="id_is_complaint_state" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["is_complaint_state title", "is_complaint_state", "th_is_complaint_state" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">is_allot_flag</span>
                <input class="opt-change form-control" id="id_is_allot_flag" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["is_allot_flag title", "is_allot_flag", "th_is_allot_flag" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["date_type_config title", "date_type_config", "th_date_type_config" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["date_type title", "date_type", "th_date_type" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["opt_date_type title", "opt_date_type", "th_opt_date_type" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["start_time title", "start_time", "th_start_time" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["end_time title", "end_time", "th_end_time" ]])!!}
*/
