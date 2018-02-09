interface GargsStatic {
	teacherid:	number;
	start_date:	string;
	end_date:	string;
	lesson_type:	string;//枚举列表: \App\Enums\Econtract_type
 	page_num:	number;
	page_count:	number;
	lessonid:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	price	:any;
	test_lesson_order_fail_flag	:any;
	test_lesson_order_fail_set_time	:any;
	phone	:any;
	test_lesson_order_fail_desc	:any;
	lessonid	:any;
	lesson_type	:any;
	lesson_start	:any;
	lesson_end	:any;
	lesson_intro	:any;
	grade	:any;
	subject	:any;
	lesson_num	:any;
	userid	:any;
	lesson_name	:any;
	lesson_status	:any;
	ass_comment_audit	:any;
	homework_status	:any;
	stu_status	:any;
	tea_status	:any;
	editionid	:any;
	finish_url	:any;
	check_url	:any;
	tea_cw_url	:any;
	stu_cw_url	:any;
	issue_url	:any;
	pdf_question_count	:any;
	lesson_time	:any;
	lesson_type_str	:any;
	lesson_num_str	:any;
	lesson_course_name	:any;
	textbook	:any;
	tea_comment_str	:any;
	tea_comment	:any;
	pdf_status_str	:any;
	pay_flag_str	:any;
	pay_flag	:any;
	pay_info	:any;
}

/*

tofile: 
	 mkdir -p ../teacher_info_admin; vi  ../teacher_info_admin/get_lesson_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/teacher_info_admin-get_lesson_list.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		teacherid:	$('#id_teacherid').val(),
		start_date:	$('#id_start_date').val(),
		end_date:	$('#id_end_date').val(),
		lesson_type:	$('#id_lesson_type').val(),
		lessonid:	$('#id_lessonid').val()
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
	$('#id_start_date').val(g_args.start_date);
	$('#id_end_date').val(g_args.end_date);
	$('#id_lesson_type').admin_set_select_field({
		"enum_type"    : "contract_type",
		"field_name" : "lesson_type",
		"select_value" : g_args.lesson_type,
		"multi_select_flag"     : true,
		"onChange"     : load_data,
		"th_input_id"  : "th_lesson_type",
		"only_show_in_th_input"     : false,
		"btn_id_config"     : {},
	});
	$('#id_lessonid').val(g_args.lessonid);


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
                <span class="input-group-addon">lesson_type</span>
                <input class="opt-change form-control" id="id_lesson_type" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["lesson_type title", "lesson_type", "th_lesson_type" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_num title", "page_num", "th_page_num" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_count title", "page_count", "th_page_count" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">lessonid</span>
                <input class="opt-change form-control" id="id_lessonid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["lessonid title", "lessonid", "th_lessonid" ]])!!}
*/
