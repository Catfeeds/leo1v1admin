interface GargsStatic {
	teacherid:	number;
	assistantid:	number;
	start_time:	string;
	end_time:	string;
	courseid:	number;
	page_num:	number;
	page_count:	number;
	group_flag:	string;
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
	course_name	:any;
	lesson_open	:any;
	teacherid	:any;
	nick	:any;
	subject	:any;
	grade	:any;
	lesson_total	:any;
	assistantid	:any;
	lesson_left	:any;
	stu_total	:any;
	teacher_nick	:any;
	grade_str	:any;
	subject_str	:any;
	stu_current	:any;
}

/*

tofile: 
	 mkdir -p ../small_class; vi  ../small_class/index.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/small_class-index.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		teacherid:	$('#id_teacherid').val(),
		assistantid:	$('#id_assistantid').val(),
		start_time:	$('#id_start_time').val(),
		end_time:	$('#id_end_time').val(),
		courseid:	$('#id_courseid').val(),
		group_flag:	$('#id_group_flag').val()
		});
}
$(function(){


	$('#id_teacherid').admin_select_user_new({
		"user_type"    : "teacher",
		"select_value" : g_args.teacherid,
		"onChange"     : load_data,
		"th_input_id"  : "th_teacherid",
		"only_show_in_th_input"     : false,
		"can_select_all_flag"     : true
	});
	$('#id_assistantid').admin_select_user_new({
		"user_type"    : "assistant",
		"select_value" : g_args.assistantid,
		"onChange"     : load_data,
		"th_input_id"  : "th_assistantid",
		"only_show_in_th_input"     : false,
		"can_select_all_flag"     : true
	});
	$('#id_start_time').val(g_args.start_time);
	$('#id_end_time').val(g_args.end_time);
	$('#id_courseid').val(g_args.courseid);
	$('#id_group_flag').val(g_args.group_flag);


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
{!!\App\Helper\Utils::th_order_gen([["teacherid title", "teacherid", "th_teacherid" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">assistantid</span>
                <input class="opt-change form-control" id="id_assistantid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["assistantid title", "assistantid", "th_assistantid" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">start_time</span>
                <input class="opt-change form-control" id="id_start_time" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["start_time title", "start_time", "th_start_time" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">end_time</span>
                <input class="opt-change form-control" id="id_end_time" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["end_time title", "end_time", "th_end_time" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">courseid</span>
                <input class="opt-change form-control" id="id_courseid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["courseid title", "courseid", "th_courseid" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_num title", "page_num", "th_page_num" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_count title", "page_count", "th_page_count" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">group_flag</span>
                <input class="opt-change form-control" id="id_group_flag" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["group_flag title", "group_flag", "th_group_flag" ]])!!}
*/
