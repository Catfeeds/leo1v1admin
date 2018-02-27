interface GargsStatic {
	id:	number;
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	page_num:	number;
	page_count:	number;
	adminid:	number;
	accept_adminid:	number;
	accept_adminid_flag:	string;
	require_adminid:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	id	:any;
	add_time	:any;
	adminid	:any;
	teacherid	:any;
	complaints_info	:any;
	complaints_info_url	:any;
	realname	:any;
	subject	:any;
	grade_part_ex	:any;
	grade_start	:any;
	grade_end	:any;
	record_scheme	:any;
	record_scheme_url	:any;
	accept_adminid	:any;
	accept_time	:any;
	is_done	:any;
	done_time	:any;
	account	:any;
	accept_account	:any;
	id_index	:any;
	add_time_str	:any;
	accept_time_str	:any;
	done_time_str	:any;
	subject_str	:any;
	grade_part_ex_str	:any;
	grade_start_str	:any;
	grade_end_str	:any;
	curl	:any;
	surl	:any;
}

/*

tofile: 
	 mkdir -p ../tea_manage_new; vi  ../tea_manage_new/get_teacher_complaints_info.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tea_manage_new-get_teacher_complaints_info.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		id:	$('#id_id').val(),
		date_type_config:	$('#id_date_type_config').val(),
		date_type:	$('#id_date_type').val(),
		opt_date_type:	$('#id_opt_date_type').val(),
		start_time:	$('#id_start_time').val(),
		end_time:	$('#id_end_time').val(),
		adminid:	$('#id_adminid').val(),
		accept_adminid:	$('#id_accept_adminid').val(),
		accept_adminid_flag:	$('#id_accept_adminid_flag').val(),
		require_adminid:	$('#id_require_adminid').val()
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
	$('#id_id').val(g_args.id);
	$('#id_adminid').admin_select_user_new({
		"user_type"    : "account",
		"select_value" : g_args.adminid,
		"onChange"     : load_data,
		"th_input_id"  : "th_adminid",
		"only_show_in_th_input"     : false,
		"can_select_all_flag"     : true
	});
	$('#id_accept_adminid').val(g_args.accept_adminid);
	$('#id_accept_adminid_flag').val(g_args.accept_adminid_flag);
	$('#id_require_adminid').val(g_args.require_adminid);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">id</span>
                <input class="opt-change form-control" id="id_id" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["id title", "id", "th_id" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["date_type_config title", "date_type_config", "th_date_type_config" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["date_type title", "date_type", "th_date_type" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["opt_date_type title", "opt_date_type", "th_opt_date_type" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["start_time title", "start_time", "th_start_time" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["end_time title", "end_time", "th_end_time" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_num title", "page_num", "th_page_num" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_count title", "page_count", "th_page_count" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">adminid</span>
                <input class="opt-change form-control" id="id_adminid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["adminid title", "adminid", "th_adminid" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">accept_adminid</span>
                <input class="opt-change form-control" id="id_accept_adminid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["accept_adminid title", "accept_adminid", "th_accept_adminid" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">accept_adminid_flag</span>
                <input class="opt-change form-control" id="id_accept_adminid_flag" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["accept_adminid_flag title", "accept_adminid_flag", "th_accept_adminid_flag" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">require_adminid</span>
                <input class="opt-change form-control" id="id_require_adminid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["require_adminid title", "require_adminid", "th_require_adminid" ]])!!}
*/
