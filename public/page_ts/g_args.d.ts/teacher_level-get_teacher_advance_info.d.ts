interface GargsStatic {
	start_time:	number;
	quarter_start:	number;
	teacher_money_type:	number;
	teacherid:	number;
	accept_flag:	number;
	fulltime_flag_new:	number;
	is_test_user:	number;
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
	 mkdir -p ../teacher_level; vi  ../teacher_level/get_teacher_advance_info.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/teacher_level-get_teacher_advance_info.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		start_time:	$('#id_start_time').val(),
		quarter_start:	$('#id_quarter_start').val(),
		teacher_money_type:	$('#id_teacher_money_type').val(),
		teacherid:	$('#id_teacherid').val(),
		accept_flag:	$('#id_accept_flag').val(),
		fulltime_flag_new:	$('#id_fulltime_flag_new').val(),
		is_test_user:	$('#id_is_test_user').val()
		});
}
$(function(){


	$('#id_start_time').val(g_args.start_time);
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
	$('#id_accept_flag').val(g_args.accept_flag);
	$('#id_fulltime_flag_new').val(g_args.fulltime_flag_new);
	$('#id_is_test_user').val(g_args.is_test_user);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">start_time</span>
                <input class="opt-change form-control" id="id_start_time" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["start_time title", "start_time", "th_start_time" ]])!!}

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
                <span class="input-group-addon">accept_flag</span>
                <input class="opt-change form-control" id="id_accept_flag" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["accept_flag title", "accept_flag", "th_accept_flag" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">fulltime_flag_new</span>
                <input class="opt-change form-control" id="id_fulltime_flag_new" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["fulltime_flag_new title", "fulltime_flag_new", "th_fulltime_flag_new" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">is_test_user</span>
                <input class="opt-change form-control" id="id_is_test_user" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["is_test_user title", "is_test_user", "th_is_test_user" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_num title", "page_num", "th_page_num" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_count title", "page_count", "th_page_count" ]])!!}
*/
