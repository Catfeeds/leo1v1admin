interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	page_num:	number;
	page_count:	number;
	teacherid:	number;
	lesson_status:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	lessonid	:any;
	teacherid	:any;
	tea_nick	:any;
	lesson_start	:any;
	lesson_end	:any;
	lesson_type	:any;
	subject	:any;
	grade	:any;
	lesson_name	:any;
	tea_cw_url	:any;
	lesson_status	:any;
	server_type	:any;
	courseid	:any;
	lesson_num	:any;
	user_num	:any;
	login_num	:any;
	through_num	:any;
	train_type	:any;
	lesson_time	:any;
	subject_str	:any;
	grade_str	:any;
	lesson_status_str	:any;
	lesson_type_str	:any;
	cw_status	:any;
	index	:any;
	region	:any;
	ip	:any;
	port	:any;
	server_type_str	:any;
}

/*

tofile: 
	 mkdir -p ../tea_manage; vi  ../tea_manage/train_lesson_list_fulltime.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tea_manage-train_lesson_list_fulltime.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type_config:	$('#id_date_type_config').val(),
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
			teacherid:	$('#id_teacherid').val(),
			lesson_status:	$('#id_lesson_status').val()
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
	$('#id_lesson_status').val(g_args.lesson_status);


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
                <span class="input-group-addon">lesson_status</span>
                <input class="opt-change form-control" id="id_lesson_status" />
            </div>
        </div>
*/
