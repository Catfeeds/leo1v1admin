interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	teacherid:	number;
	assistantid:	number;
	accept_adminid:	number;
	lessonid:	number;
	status:	number;
	feedback_type:	number;
	del_flag:	number;
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
	id	:any;
	teacherid	:any;
	lessonid	:any;
	lesson_count	:any;
	status	:any;
	feedback_type	:any;
	nick	:any;
	lesson_start	:any;
	lesson_end	:any;
	userid	:any;
	add_time	:any;
	sys_operator	:any;
	check_time	:any;
	deduct_come_late	:any;
	deduct_check_homework	:any;
	deduct_change_class	:any;
	deduct_rate_student	:any;
	deduct_upload_cw	:any;
	grade	:any;
	teacher_money_type	:any;
	level	:any;
	del_flag	:any;
	tea_reason	:any;
	back_reason	:any;
	feedback_type_str	:any;
	status_str	:any;
	grade_str	:any;
	level_str	:any;
	teacher_money_type_str	:any;
	add_time_str	:any;
	check_time_str	:any;
	lesson_start_str	:any;
	month_start_str	:any;
	lesson_time	:any;
	stu_nick	:any;
	processing_time	:any;
	processing_time_str	:any;
	lesson_deduct	:any;
}

/*

tofile: 
	 mkdir -p ../teacher_feedback; vi  ../teacher_feedback/teacher_feedback_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/teacher_feedback-teacher_feedback_list.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		date_type_config:	$('#id_date_type_config').val(),
		date_type:	$('#id_date_type').val(),
		opt_date_type:	$('#id_opt_date_type').val(),
		start_time:	$('#id_start_time').val(),
		end_time:	$('#id_end_time').val(),
		teacherid:	$('#id_teacherid').val(),
		assistantid:	$('#id_assistantid').val(),
		accept_adminid:	$('#id_accept_adminid').val(),
		lessonid:	$('#id_lessonid').val(),
		status:	$('#id_status').val(),
		feedback_type:	$('#id_feedback_type').val(),
		del_flag:	$('#id_del_flag').val()
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
	$('#id_teacherid').admin_select_user_new({
		"user_type"    : "teacher",
		"select_value" : g_args.teacherid,
		"onChange"     : load_data,
		"th_input_id"  : "th_teacherid",
		"only_show_in_th_input"     : false,
		"can_select_all_flag"     : true
	});
	$('#id_assistantid').admin_select_user_new({
		"user_type"    : "assistant",
		"select_value" : g_args.assistantid,
		"onChange"     : load_data,
		"th_input_id"  : "th_assistantid",
		"only_show_in_th_input"     : false,
		"can_select_all_flag"     : true
	});
	$('#id_accept_adminid').val(g_args.accept_adminid);
	$('#id_lessonid').val(g_args.lessonid);
	$('#id_status').val(g_args.status);
	$('#id_feedback_type').val(g_args.feedback_type);
	$('#id_del_flag').val(g_args.del_flag);


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
                <span class="input-group-addon">teacherid</span>
                <input class="opt-change form-control" id="id_teacherid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["teacherid title", "teacherid", "th_teacherid" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">assistantid</span>
                <input class="opt-change form-control" id="id_assistantid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["assistantid title", "assistantid", "th_assistantid" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">accept_adminid</span>
                <input class="opt-change form-control" id="id_accept_adminid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["accept_adminid title", "accept_adminid", "th_accept_adminid" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">lessonid</span>
                <input class="opt-change form-control" id="id_lessonid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["lessonid title", "lessonid", "th_lessonid" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">status</span>
                <input class="opt-change form-control" id="id_status" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["status title", "status", "th_status" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">feedback_type</span>
                <input class="opt-change form-control" id="id_feedback_type" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["feedback_type title", "feedback_type", "th_feedback_type" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">del_flag</span>
                <input class="opt-change form-control" id="id_del_flag" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["del_flag title", "del_flag", "th_del_flag" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_num title", "page_num", "th_page_num" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_count title", "page_count", "th_page_count" ]])!!}
*/
