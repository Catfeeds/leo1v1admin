interface GargsStatic {
	order_by_str:	string;
	quarter_start:	number;
	teacher_money_type:	number;
	teacherid:	number;
	is_test_user:	number;
	show_all:	number;
	advance_require_flag:	number;
	withhold_require_flag:	number;
	page_num:	number;
	page_count:	number;
	start_time:	number;
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
	 mkdir -p ../teacher_level; vi  ../teacher_level/get_teacher_level_quarter_info_show.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/teacher_level-get_teacher_level_quarter_info_show.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		order_by_str:	$('#id_order_by_str').val(),
		quarter_start:	$('#id_quarter_start').val(),
		teacher_money_type:	$('#id_teacher_money_type').val(),
		teacherid:	$('#id_teacherid').val(),
		is_test_user:	$('#id_is_test_user').val(),
		show_all:	$('#id_show_all').val(),
		advance_require_flag:	$('#id_advance_require_flag').val(),
		withhold_require_flag:	$('#id_withhold_require_flag').val(),
		start_time:	$('#id_start_time').val()
		});
}
$(function(){


	$('#id_order_by_str').val(g_args.order_by_str);
	$('#id_quarter_start').val(g_args.quarter_start);
	$('#id_teacher_money_type').val(g_args.teacher_money_type);
	$('#id_teacherid').admin_select_user_new({
		"user_type"    : "teacher",
		"select_value" : g_args.teacherid,
		"onChange"     : load_data,
		"th_input_id"  : "th_teacherid",
		"only_show_in_th_input"     : false,
		"can_select_all_flag"     : true
	});
	$('#id_is_test_user').val(g_args.is_test_user);
	$('#id_show_all').val(g_args.show_all);
	$('#id_advance_require_flag').val(g_args.advance_require_flag);
	$('#id_withhold_require_flag').val(g_args.withhold_require_flag);
	$('#id_start_time').val(g_args.start_time);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">order_by_str</span>
                <input class="opt-change form-control" id="id_order_by_str" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["order_by_str title", "order_by_str", "th_order_by_str" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">quarter_start</span>
                <input class="opt-change form-control" id="id_quarter_start" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["quarter_start title", "quarter_start", "th_quarter_start" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">teacher_money_type</span>
                <input class="opt-change form-control" id="id_teacher_money_type" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["teacher_money_type title", "teacher_money_type", "th_teacher_money_type" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">teacherid</span>
                <input class="opt-change form-control" id="id_teacherid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["teacherid title", "teacherid", "th_teacherid" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">is_test_user</span>
                <input class="opt-change form-control" id="id_is_test_user" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["is_test_user title", "is_test_user", "th_is_test_user" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">show_all</span>
                <input class="opt-change form-control" id="id_show_all" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["show_all title", "show_all", "th_show_all" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">advance_require_flag</span>
                <input class="opt-change form-control" id="id_advance_require_flag" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["advance_require_flag title", "advance_require_flag", "th_advance_require_flag" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">withhold_require_flag</span>
                <input class="opt-change form-control" id="id_withhold_require_flag" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["withhold_require_flag title", "withhold_require_flag", "th_withhold_require_flag" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_num title", "page_num", "th_page_num" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_count title", "page_count", "th_page_count" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">start_time</span>
                <input class="opt-change form-control" id="id_start_time" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["start_time title", "start_time", "th_start_time" ]])!!}
*/
