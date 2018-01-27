interface GargsStatic {
	id:	number;
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	page_num:	number;
	page_count:	number;
	ass_adminid:	number;
	accept_flag:	number;
	require_adminid:	number;
	accept_adminid:	number;
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
	ass_adminid	:any;
	userid	:any;
	teacherid	:any;
	change_reason	:any;
	except_teacher	:any;
	subject	:any;
	grade	:any;
	textbook	:any;
	phone_location	:any;
	stu_score_info	:any;
	stu_character_info	:any;
	record_teacher	:any;
	accept_reason	:any;
	accept_flag	:any;
	accept_adminid	:any;
	accept_time	:any;
	realname	:any;
	nick	:any;
	account	:any;
	change_reason_url	:any;
	commend_teacherid	:any;
	commend_realname	:any;
	change_teacher_reason_type	:any;
	accept_account	:any;
	is_done_flag	:any;
	done_time	:any;
	is_resubmit_flag	:any;
	id_index	:any;
	add_time_str	:any;
	accept_time_str	:any;
	subject_str	:any;
	grade_str	:any;
	change_teacher_reason_type_str	:any;
	url	:any;
	deal_time	:any;
}

/*

tofile: 
	 mkdir -p ../user_manage_new; vi  ../user_manage_new/get_ass_change_teacher_info.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-get_ass_change_teacher_info.d.ts" />

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
		ass_adminid:	$('#id_ass_adminid').val(),
		accept_flag:	$('#id_accept_flag').val(),
		require_adminid:	$('#id_require_adminid').val(),
		accept_adminid:	$('#id_accept_adminid').val()
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
	$('#id_ass_adminid').val(g_args.ass_adminid);
	$('#id_accept_flag').val(g_args.accept_flag);
	$('#id_require_adminid').val(g_args.require_adminid);
	$('#id_accept_adminid').val(g_args.accept_adminid);


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
                <span class="input-group-addon">ass_adminid</span>
                <input class="opt-change form-control" id="id_ass_adminid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["ass_adminid title", "ass_adminid", "th_ass_adminid" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">accept_flag</span>
                <input class="opt-change form-control" id="id_accept_flag" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["accept_flag title", "accept_flag", "th_accept_flag" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">require_adminid</span>
                <input class="opt-change form-control" id="id_require_adminid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["require_adminid title", "require_adminid", "th_require_adminid" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">accept_adminid</span>
                <input class="opt-change form-control" id="id_accept_adminid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["accept_adminid title", "accept_adminid", "th_accept_adminid" ]])!!}
*/
