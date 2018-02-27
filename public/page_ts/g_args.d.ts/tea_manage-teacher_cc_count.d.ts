interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	subject:	number;
	grade_part_ex:	number;
	tranfer_per:	number;
	teacherid:	number;
	test_lesson_flag:	number;
	test_lesson_num:	number;
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
}

/*

tofile: 
	 mkdir -p ../tea_manage; vi  ../tea_manage/teacher_cc_count.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tea_manage-teacher_cc_count.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		date_type_config:	$('#id_date_type_config').val(),
		date_type:	$('#id_date_type').val(),
		opt_date_type:	$('#id_opt_date_type').val(),
		start_time:	$('#id_start_time').val(),
		end_time:	$('#id_end_time').val(),
		subject:	$('#id_subject').val(),
		grade_part_ex:	$('#id_grade_part_ex').val(),
		tranfer_per:	$('#id_tranfer_per').val(),
		teacherid:	$('#id_teacherid').val(),
		test_lesson_flag:	$('#id_test_lesson_flag').val(),
		test_lesson_num:	$('#id_test_lesson_num').val()
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
	$('#id_subject').val(g_args.subject);
	$('#id_grade_part_ex').val(g_args.grade_part_ex);
	$('#id_tranfer_per').val(g_args.tranfer_per);
	$('#id_teacherid').admin_select_user_new({
		"user_type"    : "teacher",
		"select_value" : g_args.teacherid,
		"onChange"     : load_data,
		"th_input_id"  : "th_teacherid",
		"only_show_in_th_input"     : false,
		"can_select_all_flag"     : true
	});
	$('#id_test_lesson_flag').val(g_args.test_lesson_flag);
	$('#id_test_lesson_num').val(g_args.test_lesson_num);


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
                <span class="input-group-addon">tranfer_per</span>
                <input class="opt-change form-control" id="id_tranfer_per" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["tranfer_per title", "tranfer_per", "th_tranfer_per" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">teacherid</span>
                <input class="opt-change form-control" id="id_teacherid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["teacherid title", "teacherid", "th_teacherid" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">test_lesson_flag</span>
                <input class="opt-change form-control" id="id_test_lesson_flag" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["test_lesson_flag title", "test_lesson_flag", "th_test_lesson_flag" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">test_lesson_num</span>
                <input class="opt-change form-control" id="id_test_lesson_num" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["test_lesson_num title", "test_lesson_num", "th_test_lesson_num" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_num title", "page_num", "th_page_num" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_count title", "page_count", "th_page_count" ]])!!}
*/
