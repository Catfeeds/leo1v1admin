interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	teacherid:	number;
	lesson_status:	number;
	lesson_type:	number;
	lessonid:	number;
	lesson_sub_type:	number;
	train_type:	number;
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
	lessonid	:any;
	teacherid	:any;
	tea_nick	:any;
	lesson_start	:any;
	lesson_end	:any;
	lesson_type	:any;
	subject	:any;
	grade	:any;
	lesson_name	:any;
	tea_cw_url	:any;
	lesson_status	:any;
	server_type	:any;
	courseid	:any;
	lesson_num	:any;
	user_num	:any;
	login_num	:any;
	through_num	:any;
	train_type	:any;
	xmpp_server_name	:any;
	current_server	:any;
	lesson_time	:any;
	subject_str	:any;
	grade_str	:any;
	lesson_status_str	:any;
	lesson_type_str	:any;
	cw_status	:any;
	index	:any;
	region	:any;
	ip	:any;
	port	:any;
	server_type_str	:any;
}

/*

tofile: 
	 mkdir -p ../train_teacher; vi  ../train_teacher/train_lecture_lesson_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/train_teacher-train_lecture_lesson_list.d.ts" />

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
		lesson_status:	$('#id_lesson_status').val(),
		lesson_type:	$('#id_lesson_type').val(),
		lessonid:	$('#id_lessonid').val(),
		lesson_sub_type:	$('#id_lesson_sub_type').val(),
		train_type:	$('#id_train_type').val()
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
	$('#id_lesson_status').val(g_args.lesson_status);
	$('#id_lesson_type').val(g_args.lesson_type);
	$('#id_lessonid').val(g_args.lessonid);
	$('#id_lesson_sub_type').val(g_args.lesson_sub_type);
	$('#id_train_type').val(g_args.train_type);


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
                <span class="input-group-addon">lesson_status</span>
                <input class="opt-change form-control" id="id_lesson_status" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["lesson_status title", "lesson_status", "th_lesson_status" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">lesson_type</span>
                <input class="opt-change form-control" id="id_lesson_type" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["lesson_type title", "lesson_type", "th_lesson_type" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">lessonid</span>
                <input class="opt-change form-control" id="id_lessonid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["lessonid title", "lessonid", "th_lessonid" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">lesson_sub_type</span>
                <input class="opt-change form-control" id="id_lesson_sub_type" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["lesson_sub_type title", "lesson_sub_type", "th_lesson_sub_type" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">train_type</span>
                <input class="opt-change form-control" id="id_train_type" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["train_type title", "train_type", "th_train_type" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_num title", "page_num", "th_page_num" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_count title", "page_count", "th_page_count" ]])!!}
*/
