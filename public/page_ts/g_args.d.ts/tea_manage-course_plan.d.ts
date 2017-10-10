interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	page_num:	number;
	page_count:	number;
	assistantid:	number;
	subject:	number;
	lesson_type:	number;
	studentid:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	lesson_type	:any;
	confirm_flag	:any;
	lesson_cancel_reason_type	:any;
	courseid	:any;
	lessonid	:any;
	userid	:any;
	lesson_count	:any;
	lesson_status	:any;
	teacherid	:any;
	lesson_start	:any;
	lesson_num	:any;
	lesson_end	:any;
	grade	:any;
	subject	:any;
	confirm_reason	:any;
	lesson_cancel_reason_next_lesson_time	:any;
	phone	:any;
	ass_nick	:any;
	lesson_name	:any;
	teacher_nick	:any;
	user_nick	:any;
	level	:any;
	teacher_money_type	:any;
	grade_str	:any;
	subject_str	:any;
	teacher_money_type_str	:any;
	level_str	:any;
	lesson_status_str	:any;
	confirm_flag_str	:any;
	lesson_cancel_reason_type_str	:any;
	lesson_start_str	:any;
	lesson_end_str	:any;
	lesson_diff	:any;
}

/*

tofile: 
	 mkdir -p ../tea_manage; vi  ../tea_manage/course_plan.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tea_manage-course_plan.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type_config:	$('#id_date_type_config').val(),
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
			assistantid:	$('#id_assistantid').val(),
			subject:	$('#id_subject').val(),
			lesson_type:	$('#id_lesson_type').val(),
			studentid:	$('#id_studentid').val()
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
	$('#id_assistantid').val(g_args.assistantid);
	$('#id_subject').val(g_args.subject);
	$('#id_lesson_type').val(g_args.lesson_type);
	$('#id_studentid').val(g_args.studentid);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">assistantid</span>
                <input class="opt-change form-control" id="id_assistantid" />
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
                <span class="input-group-addon">lesson_type</span>
                <input class="opt-change form-control" id="id_lesson_type" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">studentid</span>
                <input class="opt-change form-control" id="id_studentid" />
            </div>
        </div>
*/
