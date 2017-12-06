interface GargsStatic {
	is_full_time:	number;
	teacher_money_type:	number;
	page_num:	number;
	page_count:	number;
	order_by_str:	string;
	assistantid:	number;
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	teacher_type	:any;
	account_role	:any;
	stu_num	:any;
	valid_count	:any;
	teacher_come_late_count	:any;
	teacher_cut_class_count	:any;
	teacher_change_lesson	:any;
	teacher_leave_lesson	:any;
	teacher_leave_num	:any;
	teacher_money_type	:any;
	train_through_new_time	:any;
	lesson_cancel_reason_type	:any;
	teacherid	:any;
	teacher_nick	:any;
	work_time	:any;
	lesson_leavel_rate	:any;
	lesson_come_late_rate	:any;
	lesson_cut_class_rate	:any;
	lesson_change_rate	:any;
	teacher_money_type_str	:any;
	index_num	:any;
}

/*

tofile: 
	 mkdir -p ../tongji_ss; vi  ../tongji_ss/tongji_change_lesson_for_jy.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji_ss-tongji_change_lesson_for_jy.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
		is_full_time:	$('#id_is_full_time').val(),
		teacher_money_type:	$('#id_teacher_money_type').val(),
		order_by_str:	$('#id_order_by_str').val(),
		assistantid:	$('#id_assistantid').val(),
		date_type_config:	$('#id_date_type_config').val(),
		date_type:	$('#id_date_type').val(),
		opt_date_type:	$('#id_opt_date_type').val(),
		start_time:	$('#id_start_time').val(),
		end_time:	$('#id_end_time').val()
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
        }
    });
	$('#id_is_full_time').val(g_args.is_full_time);
	$('#id_teacher_money_type').val(g_args.teacher_money_type);
	$('#id_order_by_str').val(g_args.order_by_str);
	$('#id_assistantid').val(g_args.assistantid);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">is_full_time</span>
                <input class="opt-change form-control" id="id_is_full_time" />
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
                <span class="input-group-addon">order_by_str</span>
                <input class="opt-change form-control" id="id_order_by_str" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">assistantid</span>
                <input class="opt-change form-control" id="id_assistantid" />
            </div>
        </div>
*/
