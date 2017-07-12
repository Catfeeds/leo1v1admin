interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	page_num:	number;
	page_count:	number;
	lesson_flag:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	notify_lesson_day1	:any;
	notify_lesson_day2	:any;
	status	:any;
	grade	:any;
	subject	:any;
	st_application_nick	:any;
	real_lesson_start	:any;
	lesson_end	:any;
	cancel_lesson_start	:any;
	teacherid	:any;
	userid	:any;
	cancel_teacherid	:any;
	lessonid	:any;
	confirm_flag	:any;
	phone	:any;
	lesson_time	:any;
	student_nick	:any;
	teacher_nick	:any;
	grade_str	:any;
	subject_str	:any;
	status_str	:any;
	confirm_flag_str	:any;
	notify_lesson_day1_str	:any;
	notify_lesson_day2_str	:any;
}

/*

tofile: 
	 mkdir -p ../tongji; vi  ../tongji/test_lesson_detail_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji-test_lesson_detail_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type_config:	$('#id_date_type_config').val(),
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
			lesson_flag:	$('#id_lesson_flag').val()
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
	$('#id_lesson_flag').val(g_args.lesson_flag);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">lesson_flag</span>
                <input class="opt-change form-control" id="id_lesson_flag" />
            </div>
        </div>
*/
