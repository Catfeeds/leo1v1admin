interface GargsStatic {
	date_type_config:	string;
	order_by_str:	string;
	page_num:	number;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	teacherid:	number;
	teacher_account:	number;
	subject:	number;
	identity:	number;
	is_new_teacher:	number;
	teacher_money_type:	number;
	have_interview_teacher:	number;
	reference_teacherid:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	nick	:any;
	create_time_str	:any;
	teacher_money_type_str	:any;
	level_str	:any;
	work_day	:any;
	school	:any;
	interview_access	:any;
	account	:any;
	identity_str	:any;
}

/*

tofile: 
	 mkdir -p ../tongji_ss; vi  ../tongji_ss/get_teacher_test_lesson_info.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji_ss-get_teacher_test_lesson_info.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			order_by_str:	$('#id_order_by_str').val(),
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
			teacherid:	$('#id_teacherid').val(),
			teacher_account:	$('#id_teacher_account').val(),
			subject:	$('#id_subject').val(),
			identity:	$('#id_identity').val(),
			is_new_teacher:	$('#id_is_new_teacher').val(),
			teacher_money_type:	$('#id_teacher_money_type').val(),
			have_interview_teacher:	$('#id_have_interview_teacher').val(),
			reference_teacherid:	$('#id_reference_teacherid').val()
        });
    }


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
	$('#id_order_by_str').val(g_args.order_by_str);
	$('#id_teacherid').val(g_args.teacherid);
	$('#id_teacher_account').val(g_args.teacher_account);
	$('#id_subject').val(g_args.subject);
	$('#id_identity').val(g_args.identity);
	$('#id_is_new_teacher').val(g_args.is_new_teacher);
	$('#id_teacher_money_type').val(g_args.teacher_money_type);
	$('#id_have_interview_teacher').val(g_args.have_interview_teacher);
	$('#id_reference_teacherid').val(g_args.reference_teacherid);


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
                <span class="input-group-addon">teacherid</span>
                <input class="opt-change form-control" id="id_teacherid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">teacher_account</span>
                <input class="opt-change form-control" id="id_teacher_account" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">subject</span>
                <input class="opt-change form-control" id="id_subject" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">identity</span>
                <input class="opt-change form-control" id="id_identity" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">is_new_teacher</span>
                <input class="opt-change form-control" id="id_is_new_teacher" />
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
                <span class="input-group-addon">have_interview_teacher</span>
                <input class="opt-change form-control" id="id_have_interview_teacher" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">reference_teacherid</span>
                <input class="opt-change form-control" id="id_reference_teacherid" />
            </div>
        </div>
*/
