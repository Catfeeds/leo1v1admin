interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	page_num:	number;
	page_count:	number;
	leader_flag:	number;
	assistantid:	number;
	ass_renw_flag:	number;
	master_renw_flag:	number;
	renw_week:	number;
	end_week:	number;
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
	 mkdir -p ../user_manage_new; vi  ../user_manage_new/ass_warning_stu_info.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-ass_warning_stu_info.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		date_type_config:	$('#id_date_type_config').val(),
		date_type:	$('#id_date_type').val(),
		opt_date_type:	$('#id_opt_date_type').val(),
		start_time:	$('#id_start_time').val(),
		end_time:	$('#id_end_time').val(),
		leader_flag:	$('#id_leader_flag').val(),
		assistantid:	$('#id_assistantid').val(),
		ass_renw_flag:	$('#id_ass_renw_flag').val(),
		master_renw_flag:	$('#id_master_renw_flag').val(),
		renw_week:	$('#id_renw_week').val(),
		end_week:	$('#id_end_week').val()
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
	$('#id_leader_flag').val(g_args.leader_flag);
	$('#id_assistantid').admin_select_user_new({
		"user_type"    : "assistant",
		"select_value" : g_args.assistantid,
		"onChange"     : load_data,
		"th_input_id"  : "th_assistantid",
		"only_show_in_th_input"     : false,
		"can_select_all_flag"     : true
	});
	$('#id_ass_renw_flag').val(g_args.ass_renw_flag);
	$('#id_master_renw_flag').val(g_args.master_renw_flag);
	$('#id_renw_week').val(g_args.renw_week);
	$('#id_end_week').val(g_args.end_week);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...
{!!\App\Helper\Utils::th_order_gen([["date_type_config title", "date_type_config", "th_date_type_config" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["date_type title", "date_type", "th_date_type" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["opt_date_type title", "opt_date_type", "th_opt_date_type" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["start_time title", "start_time", "th_start_time" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["end_time title", "end_time", "th_end_time" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_num title", "page_num", "th_page_num" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_count title", "page_count", "th_page_count" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">leader_flag</span>
                <input class="opt-change form-control" id="id_leader_flag" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["leader_flag title", "leader_flag", "th_leader_flag" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">assistantid</span>
                <input class="opt-change form-control" id="id_assistantid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["assistantid title", "assistantid", "th_assistantid" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">ass_renw_flag</span>
                <input class="opt-change form-control" id="id_ass_renw_flag" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["ass_renw_flag title", "ass_renw_flag", "th_ass_renw_flag" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">master_renw_flag</span>
                <input class="opt-change form-control" id="id_master_renw_flag" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["master_renw_flag title", "master_renw_flag", "th_master_renw_flag" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">renw_week</span>
                <input class="opt-change form-control" id="id_renw_week" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["renw_week title", "renw_week", "th_renw_week" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">end_week</span>
                <input class="opt-change form-control" id="id_end_week" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["end_week title", "end_week", "th_end_week" ]])!!}
*/
