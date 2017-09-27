interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	page_num:	number;
	page_count:	number;
	teacherid:	number;
	subject:	number;
	teacher_subject:	number;
	identity:	number;
	teacher_money_type:	number;
	grade_part_ex:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	all_lesson	:any;
	teacherid	:any;
	nick	:any;
	create_time	:any;
	level	:any;
	interview_access	:any;
	limit_plan_lesson_type	:any;
	subject	:any;
	teacher_money_type	:any;
	identity	:any;
	school	:any;
	is_freeze	:any;
	account	:any;
	limit_plan_lesson_account	:any;
	limit_plan_lesson_reason	:any;
	limit_plan_lesson_time	:any;
	freeze_time	:any;
	freeze_reason	:any;
	freeze_adminid	:any;
	freeze_account	:any;
}

/*

tofile: 
	 mkdir -p ../tongji_ss; vi  ../tongji_ss/get_teacher_test_lesson_list_new.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji_ss-get_teacher_test_lesson_list_new.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type_config:	$('#id_date_type_config').val(),
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
			page_count:	$('#id_page_count').val(),
			teacherid:	$('#id_teacherid').val(),
			subject:	$('#id_subject').val(),
			teacher_subject:	$('#id_teacher_subject').val(),
			identity:	$('#id_identity').val(),
			teacher_money_type:	$('#id_teacher_money_type').val(),
			grade_part_ex:	$('#id_grade_part_ex').val()
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
	$('#id_page_count').val(g_args.page_count);
	$('#id_teacherid').val(g_args.teacherid);
	$('#id_subject').val(g_args.subject);
	$('#id_teacher_subject').val(g_args.teacher_subject);
	$('#id_identity').val(g_args.identity);
	$('#id_teacher_money_type').val(g_args.teacher_money_type);
	$('#id_grade_part_ex').val(g_args.grade_part_ex);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">page_count</span>
                <input class="opt-change form-control" id="id_page_count" />
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
                <span class="input-group-addon">subject</span>
                <input class="opt-change form-control" id="id_subject" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">teacher_subject</span>
                <input class="opt-change form-control" id="id_teacher_subject" />
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
                <span class="input-group-addon">teacher_money_type</span>
                <input class="opt-change form-control" id="id_teacher_money_type" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">grade_part_ex</span>
                <input class="opt-change form-control" id="id_grade_part_ex" />
            </div>
        </div>
*/
