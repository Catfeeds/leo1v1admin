interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	is_done:	number;
	assistantid:	number;
	userid:	number;
	student_type:	number;
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
	grade	:any;
	user_nick	:any;
	grade_str	:any;
	lesson_total	:any;
	is_clash	:any;
	is_col	:any;
	is_clash_str	:any;
	is_col_str	:any;
	regular_total	:any;
	is_con	:any;
	is_done	:any;
	is_done_str	:any;
	is_con_str	:any;
}

/*

tofile: 
	 mkdir -p ../tea_manage; vi  ../tea_manage/course_plan_stu_summer.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tea_manage-course_plan_stu_summer.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type_config:	$('#id_date_type_config').val(),
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
			is_done:	$('#id_is_done').val(),
			assistantid:	$('#id_assistantid').val(),
			userid:	$('#id_userid').val(),
			student_type:	$('#id_student_type').val()
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
	$('#id_is_done').val(g_args.is_done);
	$('#id_assistantid').val(g_args.assistantid);
	$('#id_userid').val(g_args.userid);
	$('#id_student_type').val(g_args.student_type);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">is_done</span>
                <input class="opt-change form-control" id="id_is_done" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">assistantid</span>
                <input class="opt-change form-control" id="id_assistantid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">userid</span>
                <input class="opt-change form-control" id="id_userid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">student_type</span>
                <input class="opt-change form-control" id="id_student_type" />
            </div>
        </div>
*/
