interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	teacher:	number;
	teacher_type:	number;
	teacherid:	number;
	g_adminid:	number;
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
	pay_time	:any;
	add_time	:any;
	teacherid	:any;
	realname	:any;
	phone	:any;
	level	:any;
	bankcard	:any;
	bank_address	:any;
	bank_account	:any;
	idcard	:any;
	bank_phone	:any;
	bank_type	:any;
	bank_province	:any;
	bank_city	:any;
	money	:any;
	pay_status	:any;
	is_negative	:any;
	teacher_money_type	:any;
	teacher_type	:any;
	subject	:any;
	subject_str	:any;
	teacher_type_str	:any;
	teacher_money_type_str	:any;
}

/*

tofile: 
	 mkdir -p ../teacher_money; vi  ../teacher_money/teacher_salary_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/teacher_money-teacher_salary_list.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		date_type_config:	$('#id_date_type_config').val(),
		date_type:	$('#id_date_type').val(),
		opt_date_type:	$('#id_opt_date_type').val(),
		start_time:	$('#id_start_time').val(),
		end_time:	$('#id_end_time').val(),
		teacher:	$('#id_teacher').val(),
		teacher_type:	$('#id_teacher_type').val(),
		teacherid:	$('#id_teacherid').val(),
		g_adminid:	$('#id_g_adminid').val()
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
	$('#id_teacher').val(g_args.teacher);
	$('#id_teacher_type').val(g_args.teacher_type);
	$('#id_teacherid').admin_select_user_new({
		"user_type"    : "teacher",
		"select_value" : g_args.teacherid,
		"onChange"     : load_data,
		"th_input_id"  : "th_teacherid",
		"only_show_in_th_input"     : false,
		"can_select_all_flag"     : true
	});
	$('#id_g_adminid').val(g_args.g_adminid);


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
                <span class="input-group-addon">teacher</span>
                <input class="opt-change form-control" id="id_teacher" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["teacher title", "teacher", "th_teacher" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">teacher_type</span>
                <input class="opt-change form-control" id="id_teacher_type" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["teacher_type title", "teacher_type", "th_teacher_type" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">teacherid</span>
                <input class="opt-change form-control" id="id_teacherid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["teacherid title", "teacherid", "th_teacherid" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">g_adminid</span>
                <input class="opt-change form-control" id="id_g_adminid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["g_adminid title", "g_adminid", "th_g_adminid" ]])!!}
*/
