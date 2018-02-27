interface GargsStatic {
	order_by_str:	string;
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	subject:	number;
	teacher_account:	number;
	reference_teacherid:	number;
	identity:	number;
	interview_type:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	account	:any;
	all_num	:any;
	all_count	:any;
	real_num	:any;
	real_all	:any;
	pass_per	:any;
	ave_time	:any;
	order_per	:any;
	all_pass_per	:any;
}

/*

tofile: 
	 mkdir -p ../tongji_ss; vi  ../tongji_ss/teacher_interview_info_tongji.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji_ss-teacher_interview_info_tongji.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		order_by_str:	$('#id_order_by_str').val(),
		date_type_config:	$('#id_date_type_config').val(),
		date_type:	$('#id_date_type').val(),
		opt_date_type:	$('#id_opt_date_type').val(),
		start_time:	$('#id_start_time').val(),
		end_time:	$('#id_end_time').val(),
		subject:	$('#id_subject').val(),
		teacher_account:	$('#id_teacher_account').val(),
		reference_teacherid:	$('#id_reference_teacherid').val(),
		identity:	$('#id_identity').val(),
		interview_type:	$('#id_interview_type').val()
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
	$('#id_order_by_str').val(g_args.order_by_str);
	$('#id_subject').val(g_args.subject);
	$('#id_teacher_account').val(g_args.teacher_account);
	$('#id_reference_teacherid').val(g_args.reference_teacherid);
	$('#id_identity').val(g_args.identity);
	$('#id_interview_type').val(g_args.interview_type);


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
                <span class="input-group-addon">teacher_account</span>
                <input class="opt-change form-control" id="id_teacher_account" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["teacher_account title", "teacher_account", "th_teacher_account" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">reference_teacherid</span>
                <input class="opt-change form-control" id="id_reference_teacherid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["reference_teacherid title", "reference_teacherid", "th_reference_teacherid" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">identity</span>
                <input class="opt-change form-control" id="id_identity" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["identity title", "identity", "th_identity" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">interview_type</span>
                <input class="opt-change form-control" id="id_interview_type" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["interview_type title", "interview_type", "th_interview_type" ]])!!}
*/
