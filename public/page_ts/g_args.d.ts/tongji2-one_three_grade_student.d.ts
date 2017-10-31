interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
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
	userid	:any;
	nick	:any;
	grade	:any;
	subject	:any;
	stu_request_test_lesson_demand	:any;
	textbook	:any;
	phone_location	:any;
	current_lessonid	:any;
	require_id	:any;
	lessonid	:any;
	success_flag	:any;
	lesson_type	:any;
	lesson_user_online_status	:any;
	price	:any;
	contract_status	:any;
	grade_str	:any;
	subject_str	:any;
	lesson_user_online_status_str	:any;
	status_str	:any;
}

/*

tofile: 
	 mkdir -p ../tongji2; vi  ../tongji2/one_three_grade_student.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji2-one_three_grade_student.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
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


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...
*/
