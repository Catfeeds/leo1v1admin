interface GargsStatic {
	sid:	number;
	page_num:	number;
	page_count:	number;
	order_by_str:	string;
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	subject:	number;
	grade:	number;
	current_id:	number;
	cw_status:	number;
	preview_status:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	lesson_start	:any;
	lesson_end	:any;
	subject	:any;
	grade	:any;
	teacherid	:any;
	lessonid	:any;
	realname	:any;
	lesson_num	:any;
	tea_cw_upload_time	:any;
	tea_cw_url	:any;
	preview_status	:any;
	cw_status	:any;
	grade_str	:any;
	subject_str	:any;
	lesson_time	:any;
	cw_url	:any;
	cw_status_str	:any;
	preview_status_str	:any;
}

/*

tofile: 
	 mkdir -p ../stu_manage; vi  ../stu_manage/student_lesson_learning_record.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/stu_manage-student_lesson_learning_record.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		sid:	$('#id_sid').val(),
		order_by_str:	$('#id_order_by_str').val(),
		date_type_config:	$('#id_date_type_config').val(),
		date_type:	$('#id_date_type').val(),
		opt_date_type:	$('#id_opt_date_type').val(),
		start_time:	$('#id_start_time').val(),
		end_time:	$('#id_end_time').val(),
		subject:	$('#id_subject').val(),
		grade:	$('#id_grade').val(),
		current_id:	$('#id_current_id').val(),
		cw_status:	$('#id_cw_status').val(),
		preview_status:	$('#id_preview_status').val()
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
	$('#id_sid').val(g_args.sid);
	$('#id_order_by_str').val(g_args.order_by_str);
	$('#id_subject').val(g_args.subject);
	$('#id_grade').val(g_args.grade);
	$('#id_current_id').val(g_args.current_id);
	$('#id_cw_status').val(g_args.cw_status);
	$('#id_preview_status').val(g_args.preview_status);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">sid</span>
                <input class="opt-change form-control" id="id_sid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["sid title", "sid", "th_sid" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_num title", "page_num", "th_page_num" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_count title", "page_count", "th_page_count" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">order_by_str</span>
                <input class="opt-change form-control" id="id_order_by_str" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["order_by_str title", "order_by_str", "th_order_by_str" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["date_type_config title", "date_type_config", "th_date_type_config" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["date_type title", "date_type", "th_date_type" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["opt_date_type title", "opt_date_type", "th_opt_date_type" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["start_time title", "start_time", "th_start_time" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["end_time title", "end_time", "th_end_time" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">subject</span>
                <input class="opt-change form-control" id="id_subject" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["subject title", "subject", "th_subject" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">grade</span>
                <input class="opt-change form-control" id="id_grade" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["grade title", "grade", "th_grade" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">current_id</span>
                <input class="opt-change form-control" id="id_current_id" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["current_id title", "current_id", "th_current_id" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">cw_status</span>
                <input class="opt-change form-control" id="id_cw_status" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["cw_status title", "cw_status", "th_cw_status" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">preview_status</span>
                <input class="opt-change form-control" id="id_preview_status" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["preview_status title", "preview_status", "th_preview_status" ]])!!}
*/
