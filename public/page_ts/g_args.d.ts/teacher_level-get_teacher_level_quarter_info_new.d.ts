interface GargsStatic {
	order_by_str:	string;
	quarter_start:	number;
	teacher_money_type:	number;
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
	teacherid	:any;
	realname	:any;
	level	:any;
	level_after	:any;
	level_str	:any;
	level_after_str	:any;
	total_score	:any;
	record_final_score	:any;
	record_score_avg	:any;
	record_score	:any;
	record_num	:any;
	other_order_score	:any;
	other_order_per	:any;
	other_order_num	:any;
	other_test_num	:any;
	cc_order_score	:any;
	cc_order_per	:any;
	cc_order_num	:any;
	cc_test_num	:any;
	lesson_count_score	:any;
	lesson_count	:any;
	is_refund_str	:any;
	is_refund	:any;
	hand_flag	:any;
}

/*

tofile: 
	 mkdir -p ../teacher_level; vi  ../teacher_level/get_teacher_level_quarter_info_new.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/teacher_level-get_teacher_level_quarter_info_new.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			order_by_str:	$('#id_order_by_str').val(),
			quarter_start:	$('#id_quarter_start').val(),
			teacher_money_type:	$('#id_teacher_money_type').val()
        });
    }


	$('#id_order_by_str').val(g_args.order_by_str);
	$('#id_quarter_start').val(g_args.quarter_start);
	$('#id_teacher_money_type').val(g_args.teacher_money_type);


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
*/
