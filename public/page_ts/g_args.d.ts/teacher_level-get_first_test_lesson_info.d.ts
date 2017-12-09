interface GargsStatic {
	page_num:	number;
	page_count:	number;
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	subject:	number;
	teacherid:	number;
	record_flag:	number;
	acc:	string;
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
	lessonid	:any;
	lesson_start	:any;
	subject	:any;
	grade_start	:any;
	grade_end	:any;
	grade_part_ex	:any;
	id	:any;
	acc	:any;
	record_info	:any;
	add_time	:any;
	grade	:any;
	lesson_invalid_flag	:any;
	test_stu_request_test_lesson_demand	:any;
	stu_request_test_lesson_demand	:any;
	subject_str	:any;
	grade_part_ex_str	:any;
	grade_start_str	:any;
	grade_end_str	:any;
	grade_str	:any;
	record_flag_str	:any;
	add_time_str	:any;
}

/*

tofile: 
	 mkdir -p ../teacher_level; vi  ../teacher_level/get_first_test_lesson_info.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/teacher_level-get_first_test_lesson_info.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		date_type_config:	$('#id_date_type_config').val(),
		date_type:	$('#id_date_type').val(),
		opt_date_type:	$('#id_opt_date_type').val(),
		start_time:	$('#id_start_time').val(),
		end_time:	$('#id_end_time').val(),
		subject:	$('#id_subject').val(),
		teacherid:	$('#id_teacherid').val(),
		record_flag:	$('#id_record_flag').val(),
		acc:	$('#id_acc').val()
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
	$('#id_subject').val(g_args.subject);
	$('#id_teacherid').admin_select_user_new({
		"user_type"    : "teacher",
		"select_value" : g_args.teacherid,
		"onChange"     : load_data,
		"th_input_id"  : "th_teacherid",
		"only_show_in_th_input"     : false,
		"can_select_all_flag"     : true
	});
	$('#id_record_flag').val(g_args.record_flag);
	$('#id_acc').val(g_args.acc);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

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
                <span class="input-group-addon">record_flag</span>
                <input class="opt-change form-control" id="id_record_flag" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">acc</span>
                <input class="opt-change form-control" id="id_acc" />
            </div>
        </div>
*/
