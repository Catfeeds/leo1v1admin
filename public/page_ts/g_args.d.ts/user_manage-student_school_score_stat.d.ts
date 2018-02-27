interface GargsStatic {
	order_by_str:	string;
	username:	string;
	grade:	number;
	semester:	number;
	stu_score_type:	number;
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
	admin_type	:any;
	userid	:any;
	create_time	:any;
	create_adminid	:any;
	subject	:any;
	stu_score_type	:any;
	stu_score_time	:any;
	score	:any;
	total_score	:any;
	rank	:any;
	semester	:any;
	grade	:any;
	grade_rank	:any;
	status	:any;
	month	:any;
	rank_up	:any;
	rank_down	:any;
	realname	:any;
	school	:any;
	name	:any;
	nick	:any;
	file_url	:any;
	paper_upload_time	:any;
	school_ex	:any;
	num	:any;
	semester_str	:any;
	grade_str	:any;
	stu_score_type_str	:any;
	create_admin_nick	:any;
}

/*

tofile: 
	 mkdir -p ../user_manage; vi  ../user_manage/student_school_score_stat.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage-student_school_score_stat.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		order_by_str:	$('#id_order_by_str').val(),
		username:	$('#id_username').val(),
		grade:	$('#id_grade').val(),
		semester:	$('#id_semester').val(),
		stu_score_type:	$('#id_stu_score_type').val()
		});
}
$(function(){


	$('#id_order_by_str').val(g_args.order_by_str);
	$('#id_username').val(g_args.username);
	$('#id_grade').val(g_args.grade);
	$('#id_semester').val(g_args.semester);
	$('#id_stu_score_type').val(g_args.stu_score_type);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">order_by_str</span>
                <input class="opt-change form-control" id="id_order_by_str" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["order_by_str title", "order_by_str", "th_order_by_str" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">username</span>
                <input class="opt-change form-control" id="id_username" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["username title", "username", "th_username" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">grade</span>
                <input class="opt-change form-control" id="id_grade" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["grade title", "grade", "th_grade" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">semester</span>
                <input class="opt-change form-control" id="id_semester" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["semester title", "semester", "th_semester" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">stu_score_type</span>
                <input class="opt-change form-control" id="id_stu_score_type" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["stu_score_type title", "stu_score_type", "th_stu_score_type" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_num title", "page_num", "th_page_num" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_count title", "page_count", "th_page_count" ]])!!}
*/
