interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	status:	number;
	lesson_status:	number;
	grade:	number;
	subject:	number;
	teacherid:	number;
	is_test:	number;
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
	id	:any;
	lessonid	:any;
	audio	:any;
	draw	:any;
	teacherid	:any;
	subject	:any;
	grade	:any;
	tea_nick	:any;
	wx_openid	:any;
	lesson_start	:any;
	lesson_end	:any;
	lesson_status	:any;
	add_time	:any;
	record_monitor_class	:any;
	record_info	:any;
	stu_request_test_lesson_demand	:any;
	subject_str	:any;
	grade_str	:any;
	lesson_status_str	:any;
	lesson_time	:any;
}

/*

tofile: 
	 mkdir -p ../tea_manage; vi  ../tea_manage/trial_train_lesson_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tea_manage-trial_train_lesson_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type_config:	$('#id_date_type_config').val(),
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
			status:	$('#id_status').val(),
			lesson_status:	$('#id_lesson_status').val(),
			grade:	$('#id_grade').val(),
			subject:	$('#id_subject').val(),
			teacherid:	$('#id_teacherid').val(),
			is_test:	$('#id_is_test').val()
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
	$('#id_status').val(g_args.status);
	$('#id_lesson_status').val(g_args.lesson_status);
	$('#id_grade').val(g_args.grade);
	$('#id_subject').val(g_args.subject);
	$('#id_teacherid').val(g_args.teacherid);
	$('#id_is_test').val(g_args.is_test);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">status</span>
                <input class="opt-change form-control" id="id_status" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">lesson_status</span>
                <input class="opt-change form-control" id="id_lesson_status" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">grade</span>
                <input class="opt-change form-control" id="id_grade" />
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
                <span class="input-group-addon">teacherid</span>
                <input class="opt-change form-control" id="id_teacherid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">is_test</span>
                <input class="opt-change form-control" id="id_is_test" />
            </div>
        </div>
*/
