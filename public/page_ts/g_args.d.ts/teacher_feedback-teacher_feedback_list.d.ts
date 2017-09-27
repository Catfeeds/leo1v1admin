interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	teacherid:	number;
	assistantid:	number;
	accept_adminid:	number;
	lessonid:	number;
	status:	number;
	feedback_type:	number;
	del_flag:	number;
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
	teacherid	:any;
	lessonid	:any;
	lesson_count	:any;
	status	:any;
	feedback_type	:any;
	nick	:any;
	lesson_start	:any;
	lesson_end	:any;
	userid	:any;
	add_time	:any;
	sys_operator	:any;
	check_time	:any;
	tea_reason	:any;
	back_reason	:any;
	feedback_type_str	:any;
	status_str	:any;
	add_time_str	:any;
	check_time_str	:any;
	lesson_start_str	:any;
	month_start_str	:any;
	lesson_time	:any;
	stu_nick	:any;
}

/*

tofile: 
	 mkdir -p ../teacher_feedback; vi  ../teacher_feedback/teacher_feedback_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/teacher_feedback-teacher_feedback_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type_config:	$('#id_date_type_config').val(),
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
			teacherid:	$('#id_teacherid').val(),
			assistantid:	$('#id_assistantid').val(),
			accept_adminid:	$('#id_accept_adminid').val(),
			lessonid:	$('#id_lessonid').val(),
			status:	$('#id_status').val(),
			feedback_type:	$('#id_feedback_type').val(),
			del_flag:	$('#id_del_flag').val()
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
	$('#id_teacherid').val(g_args.teacherid);
	$('#id_assistantid').val(g_args.assistantid);
	$('#id_accept_adminid').val(g_args.accept_adminid);
	$('#id_lessonid').val(g_args.lessonid);
	$('#id_status').val(g_args.status);
	$('#id_feedback_type').val(g_args.feedback_type);
	$('#id_del_flag').val(g_args.del_flag);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">teacherid</span>
                <input class="opt-change form-control" id="id_teacherid" />
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
                <span class="input-group-addon">accept_adminid</span>
                <input class="opt-change form-control" id="id_accept_adminid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">lessonid</span>
                <input class="opt-change form-control" id="id_lessonid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">status</span>
                <input class="opt-change form-control" id="id_status" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">feedback_type</span>
                <input class="opt-change form-control" id="id_feedback_type" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">del_flag</span>
                <input class="opt-change form-control" id="id_del_flag" />
            </div>
        </div>
*/
