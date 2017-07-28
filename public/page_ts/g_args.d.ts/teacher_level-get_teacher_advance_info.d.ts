interface GargsStatic {
	start_time:	number;
	quarter_start:	number;
	teacher_money_type:	number;
	teacherid:	number;
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
	start_time	:any;
	teacherid	:any;
	level_before	:any;
	level_after	:any;
	lesson_count	:any;
	lesson_count_score	:any;
	cc_test_num	:any;
	cc_order_num	:any;
	cc_order_per	:any;
	cc_order_score	:any;
	other_test_num	:any;
	other_order_num	:any;
	other_order_per	:any;
	other_order_score	:any;
	record_num	:any;
	record_score_avg	:any;
	record_final_score	:any;
	is_refund	:any;
	total_score	:any;
	require_adminid	:any;
	require_time	:any;
	accept_adminid	:any;
	accept_time	:any;
	accept_flag	:any;
	accept_info	:any;
	hand_flag	:any;
	realname	:any;
	level_before_str	:any;
	level_after_str	:any;
	accept_time_str	:any;
	require_time_str	:any;
	accept_flag_str	:any;
	is_refund_str	:any;
}

/*

tofile: 
	 mkdir -p ../teacher_level; vi  ../teacher_level/get_teacher_advance_info.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/teacher_level-get_teacher_advance_info.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			start_time:	$('#id_start_time').val(),
			quarter_start:	$('#id_quarter_start').val(),
			teacher_money_type:	$('#id_teacher_money_type').val(),
			teacherid:	$('#id_teacherid').val()
        });
    }


	$('#id_start_time').val(g_args.start_time);
	$('#id_quarter_start').val(g_args.quarter_start);
	$('#id_teacher_money_type').val(g_args.teacher_money_type);
	$('#id_teacherid').val(g_args.teacherid);


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

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">quarter_start</span>
                <input class="opt-change form-control" id="id_quarter_start" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">teacher_money_type</span>
                <input class="opt-change form-control" id="id_teacher_money_type" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">teacherid</span>
                <input class="opt-change form-control" id="id_teacherid" />
            </div>
        </div>
*/
