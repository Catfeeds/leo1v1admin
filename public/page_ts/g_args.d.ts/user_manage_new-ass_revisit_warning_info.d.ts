interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	page_num:	number;
	page_count:	number;
	is_warning_flag:	number;
	ass_adminid:	number;
	seller_groupid_ex:	string;
	revisit_warning_type:	string;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	revisit_time	:any;
	revisit_person	:any;
	operator_note	:any;
	operator_audio	:any;
	sys_operator	:any;
	revisit_type	:any;
	operation_satisfy_flag	:any;
	operation_satisfy_type	:any;
	operation_satisfy_info	:any;
	record_tea_class_flag	:any;
	child_performance	:any;
	tea_content_satisfy_flag	:any;
	tea_content_satisfy_type	:any;
	tea_content_satisfy_info	:any;
	other_parent_info	:any;
	child_class_performance_flag	:any;
	child_class_performance_type	:any;
	child_class_performance_info	:any;
	school_score_change_flag	:any;
	school_score_change_info	:any;
	school_work_change_flag	:any;
	school_work_change_type	:any;
	school_work_change_info	:any;
	other_warning_info	:any;
	is_warning_flag	:any;
	warning_deal_url	:any;
	warning_deal_info	:any;
	nick	:any;
	userid	:any;
	revisit_time_str	:any;
	revisit_type_str	:any;
	operation_satisfy_flag_str	:any;
	school_work_change_flag_str	:any;
	tea_content_satisfy_flag_str	:any;
	school_work_change_type_str	:any;
	school_score_change_flag_str	:any;
	operation_satisfy_type_str	:any;
	tea_content_satisfy_type_str	:any;
	child_class_performance_flag_str	:any;
	child_class_performance_type_str	:any;
	is_warning_flag_str	:any;
}

/*

tofile: 
	 mkdir -p ../user_manage_new; vi  ../user_manage_new/ass_revisit_warning_info.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-ass_revisit_warning_info.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		date_type_config:	$('#id_date_type_config').val(),
		date_type:	$('#id_date_type').val(),
		opt_date_type:	$('#id_opt_date_type').val(),
		start_time:	$('#id_start_time').val(),
		end_time:	$('#id_end_time').val(),
		is_warning_flag:	$('#id_is_warning_flag').val(),
		ass_adminid:	$('#id_ass_adminid').val(),
		seller_groupid_ex:	$('#id_seller_groupid_ex').val(),
		revisit_warning_type:	$('#id_revisit_warning_type').val()
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
	$('#id_is_warning_flag').val(g_args.is_warning_flag);
	$('#id_ass_adminid').val(g_args.ass_adminid);
	$('#id_seller_groupid_ex').val(g_args.seller_groupid_ex);
	$('#id_revisit_warning_type').val(g_args.revisit_warning_type);


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
                <span class="input-group-addon">is_warning_flag</span>
                <input class="opt-change form-control" id="id_is_warning_flag" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["is_warning_flag title", "is_warning_flag", "th_is_warning_flag" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">ass_adminid</span>
                <input class="opt-change form-control" id="id_ass_adminid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["ass_adminid title", "ass_adminid", "th_ass_adminid" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">seller_groupid_ex</span>
                <input class="opt-change form-control" id="id_seller_groupid_ex" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["seller_groupid_ex title", "seller_groupid_ex", "th_seller_groupid_ex" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">revisit_warning_type</span>
                <input class="opt-change form-control" id="id_revisit_warning_type" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["revisit_warning_type title", "revisit_warning_type", "th_revisit_warning_type" ]])!!}
*/
