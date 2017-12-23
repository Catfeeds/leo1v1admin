interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	page_num:	number;
	page_count:	number;
	require_adminid:	number;
	master_flag:	number;
	assistantid:	number;
	success_flag:	number;
	order_confirm_flag:	number;
	master_adminid:	number;
	lessonid:	number;
	account:	number;
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
	 mkdir -p ../seller_student_new2; vi  ../seller_student_new2/get_ass_test_lesson_info.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student_new2-get_ass_test_lesson_info.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		date_type_config:	$('#id_date_type_config').val(),
		date_type:	$('#id_date_type').val(),
		opt_date_type:	$('#id_opt_date_type').val(),
		start_time:	$('#id_start_time').val(),
		end_time:	$('#id_end_time').val(),
		require_adminid:	$('#id_require_adminid').val(),
		master_flag:	$('#id_master_flag').val(),
		assistantid:	$('#id_assistantid').val(),
		success_flag:	$('#id_success_flag').val(),
		order_confirm_flag:	$('#id_order_confirm_flag').val(),
		master_adminid:	$('#id_master_adminid').val(),
		lessonid:	$('#id_lessonid').val(),
		account:	$('#id_account').val()
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
	$('#id_require_adminid').val(g_args.require_adminid);
	$('#id_master_flag').val(g_args.master_flag);
	$('#id_assistantid').admin_select_user_new({
		"user_type"    : "assistant",
		"select_value" : g_args.assistantid,
		"onChange"     : load_data,
		"th_input_id"  : "th_assistantid",
		"only_show_in_th_input"     : false,
		"can_select_all_flag"     : true
	});
	$('#id_success_flag').val(g_args.success_flag);
	$('#id_order_confirm_flag').val(g_args.order_confirm_flag);
	$('#id_master_adminid').val(g_args.master_adminid);
	$('#id_lessonid').val(g_args.lessonid);
	$('#id_account').val(g_args.account);


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
                <span class="input-group-addon">require_adminid</span>
                <input class="opt-change form-control" id="id_require_adminid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["require_adminid title", "require_adminid", "th_require_adminid" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">master_flag</span>
                <input class="opt-change form-control" id="id_master_flag" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["master_flag title", "master_flag", "th_master_flag" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">assistantid</span>
                <input class="opt-change form-control" id="id_assistantid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["assistantid title", "assistantid", "th_assistantid" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">success_flag</span>
                <input class="opt-change form-control" id="id_success_flag" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["success_flag title", "success_flag", "th_success_flag" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">order_confirm_flag</span>
                <input class="opt-change form-control" id="id_order_confirm_flag" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["order_confirm_flag title", "order_confirm_flag", "th_order_confirm_flag" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">master_adminid</span>
                <input class="opt-change form-control" id="id_master_adminid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["master_adminid title", "master_adminid", "th_master_adminid" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">lessonid</span>
                <input class="opt-change form-control" id="id_lessonid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["lessonid title", "lessonid", "th_lessonid" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">account</span>
                <input class="opt-change form-control" id="id_account" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["account title", "account", "th_account" ]])!!}
*/
