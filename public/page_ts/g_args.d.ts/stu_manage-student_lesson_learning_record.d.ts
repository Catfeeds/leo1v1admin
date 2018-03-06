interface GargsStatic {
	sid:	number;
	page_num:	number;
	page_count:	number;
	order_by_str:	string;
	start_date:	string;
	end_date:	string;
	subject:	number;
	grade:	number;
	semester:	number;
	stu_score_type:	number;
	current_id:	number;
	current_table_id:	number;
	cw_status:	number;
	preview_status:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	lesson_start	:any;
	lesson_end	:any;
	subject	:any;
	confirm_flag	:any;
	grade	:any;
	teacherid	:any;
	lessonid	:any;
	realname	:any;
	userid	:any;
	lesson_num	:any;
	teacher_effect	:any;
	teacher_quality	:any;
	stu_score	:any;
	teacher_interact	:any;
	stu_stability	:any;
	teacher_comment	:any;
	stu_comment	:any;
	stu_performance	:any;
	grade_str	:any;
	subject_str	:any;
	lesson_time	:any;
	stu_intro	:any;
	stu_point_performance	:any;
}

/*

tofile: 
	 mkdir -p ../stu_manage; vi  ../stu_manage/student_lesson_learning_record.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/stu_manage-student_lesson_learning_record.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		sid:	$('#id_sid').val(),
		order_by_str:	$('#id_order_by_str').val(),
		start_date:	$('#id_start_date').val(),
		end_date:	$('#id_end_date').val(),
		subject:	$('#id_subject').val(),
		grade:	$('#id_grade').val(),
		semester:	$('#id_semester').val(),
		stu_score_type:	$('#id_stu_score_type').val(),
		current_id:	$('#id_current_id').val(),
		current_table_id:	$('#id_current_table_id').val(),
		cw_status:	$('#id_cw_status').val(),
		preview_status:	$('#id_preview_status').val()
		});
}
$(function(){


	$('#id_sid').val(g_args.sid);
	$('#id_order_by_str').val(g_args.order_by_str);
	$('#id_start_date').val(g_args.start_date);
	$('#id_end_date').val(g_args.end_date);
	$('#id_subject').val(g_args.subject);
	$('#id_grade').val(g_args.grade);
	$('#id_semester').val(g_args.semester);
	$('#id_stu_score_type').val(g_args.stu_score_type);
	$('#id_current_id').val(g_args.current_id);
	$('#id_current_table_id').val(g_args.current_table_id);
	$('#id_cw_status').val(g_args.cw_status);
	$('#id_preview_status').val(g_args.preview_status);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">sid</span>
                <input class="opt-change form-control" id="id_sid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["sid title", "sid", "th_sid" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_num title", "page_num", "th_page_num" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_count title", "page_count", "th_page_count" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">order_by_str</span>
                <input class="opt-change form-control" id="id_order_by_str" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["order_by_str title", "order_by_str", "th_order_by_str" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">start_date</span>
                <input class="opt-change form-control" id="id_start_date" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["start_date title", "start_date", "th_start_date" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">end_date</span>
                <input class="opt-change form-control" id="id_end_date" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["end_date title", "end_date", "th_end_date" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">subject</span>
                <input class="opt-change form-control" id="id_subject" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["subject title", "subject", "th_subject" ]])!!}

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

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">current_id</span>
                <input class="opt-change form-control" id="id_current_id" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["current_id title", "current_id", "th_current_id" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">current_table_id</span>
                <input class="opt-change form-control" id="id_current_table_id" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["current_table_id title", "current_table_id", "th_current_table_id" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">cw_status</span>
                <input class="opt-change form-control" id="id_cw_status" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["cw_status title", "cw_status", "th_cw_status" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">preview_status</span>
                <input class="opt-change form-control" id="id_preview_status" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["preview_status title", "preview_status", "th_preview_status" ]])!!}
*/
