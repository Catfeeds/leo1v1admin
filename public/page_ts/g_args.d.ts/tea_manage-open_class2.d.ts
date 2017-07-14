interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	lesson_status:	number;
	lesson_type:	number;
	teacherid:	number;
	lessonid:	number;
	page_num:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	courseid	:any;
	lessonid	:any;
	lesson_status	:any;
	lesson_intro	:any;
	from_lessonid	:any;
	teacherid	:any;
	can_set_as_from_lessonid	:any;
	lesson_num	:any;
	lesson_start	:any;
	lesson_end	:any;
	lesson_type	:any;
	tea_cw_url	:any;
	tea_cw_status	:any;
	lesson_total	:any;
	course_name	:any;
	lesson_time	:any;
	can_set	:any;
	lesson_type_str	:any;
	cw_status	:any;
	nick	:any;
	stu_total	:any;
	stu_join	:any;
}

/*

tofile: 
	 mkdir -p ../tea_manage; vi  ../tea_manage/open_class2.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tea_manage-open_class2.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type_config:	$('#id_date_type_config').val(),
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
			lesson_status:	$('#id_lesson_status').val(),
			lesson_type:	$('#id_lesson_type').val(),
			teacherid:	$('#id_teacherid').val(),
			lessonid:	$('#id_lessonid').val()
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
	$('#id_lesson_status').val(g_args.lesson_status);
	$('#id_lesson_type').val(g_args.lesson_type);
	$('#id_teacherid').val(g_args.teacherid);
	$('#id_lessonid').val(g_args.lessonid);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">lesson_status</span>
                <input class="opt-change form-control" id="id_lesson_status" />
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
                <span class="input-group-addon">teacherid</span>
                <input class="opt-change form-control" id="id_teacherid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">lessonid</span>
                <input class="opt-change form-control" id="id_lessonid" />
            </div>
        </div>
*/
