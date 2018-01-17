interface GargsStatic {
	page_num:	number;
	page_count:	number;
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	have_test_lesson_flag:	number;
	subject:	number;
	grade_part_ex:	number;
	train_through_new:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	teacherid	:any;
	realname	:any;
	train_through_new_time	:any;
	train_through_new	:any;
	subject	:any;
	grade_part_ex	:any;
	phone	:any;
	confirm_time	:any;
	work_day	:any;
	subject_str	:any;
	grade_part_ex_str	:any;
	order_num	:any;
	all_lesson	:any;
	per	:any;
	first_time_str	:any;
	first_time	:any;
	confirm_time_str	:any;
}

/*

tofile: 
	 mkdir -p ../tongji_ss; vi  ../tongji_ss/new_teacher_test_lesson_info.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji_ss-new_teacher_test_lesson_info.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		date_type_config:	$('#id_date_type_config').val(),
		date_type:	$('#id_date_type').val(),
		opt_date_type:	$('#id_opt_date_type').val(),
		start_time:	$('#id_start_time').val(),
		end_time:	$('#id_end_time').val(),
		have_test_lesson_flag:	$('#id_have_test_lesson_flag').val(),
		subject:	$('#id_subject').val(),
		grade_part_ex:	$('#id_grade_part_ex').val(),
		train_through_new:	$('#id_train_through_new').val()
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
	$('#id_have_test_lesson_flag').val(g_args.have_test_lesson_flag);
	$('#id_subject').val(g_args.subject);
	$('#id_grade_part_ex').val(g_args.grade_part_ex);
	$('#id_train_through_new').val(g_args.train_through_new);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...
{!!\App\Helper\Utils::th_order_gen([["page_num title", "page_num", "th_page_num" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_count title", "page_count", "th_page_count" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["date_type_config title", "date_type_config", "th_date_type_config" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["date_type title", "date_type", "th_date_type" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["opt_date_type title", "opt_date_type", "th_opt_date_type" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["start_time title", "start_time", "th_start_time" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["end_time title", "end_time", "th_end_time" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">have_test_lesson_flag</span>
                <input class="opt-change form-control" id="id_have_test_lesson_flag" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["have_test_lesson_flag title", "have_test_lesson_flag", "th_have_test_lesson_flag" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">subject</span>
                <input class="opt-change form-control" id="id_subject" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["subject title", "subject", "th_subject" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">grade_part_ex</span>
                <input class="opt-change form-control" id="id_grade_part_ex" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["grade_part_ex title", "grade_part_ex", "th_grade_part_ex" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">train_through_new</span>
                <input class="opt-change form-control" id="id_train_through_new" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["train_through_new title", "train_through_new", "th_train_through_new" ]])!!}
*/
