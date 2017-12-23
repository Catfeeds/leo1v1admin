interface GargsStatic {
	require_id:	number;
	teacher_tags:	string;
	teaching_tags:	string;
	lesson_tags:	string;
	refresh_flag:	number;
	identity:	number;
	gender:	number;
	tea_age:	number;
	teacher_type:	number;
	region_version:	number;
	teacher_info:	string;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	teacherid	:any;
	subject	:any;
	grade_start	:any;
	grade_end	:any;
	second_subject	:any;
	second_grade_start	:any;
	teacher_type	:any;
	second_grade_end	:any;
	limit_plan_lesson_type	:any;
	limit_day_lesson_num	:any;
	limit_week_lesson_num	:any;
	limit_month_lesson_num	:any;
	train_through_new_time	:any;
	identity	:any;
	gender	:any;
	age	:any;
	realname	:any;
	phone	:any;
	free_time_new	:any;
	teacher_tags	:any;
	teacher_textbook	:any;
	age_flag	:any;
	is_identity	:any;
	is_gender	:any;
	is_age	:any;
	is_search	:any;
	is_textbook	:any;
	teacher_textbook_str	:any;
	is_teacher_type	:any;
	match_time	:any;
	tags_str	:any;
	match_tags	:any;
	teacher_type_str	:any;
	identity_str	:any;
	gender_str	:any;
	work_day	:any;
	phone_hide	:any;
}

/*

tofile: 
	 mkdir -p ../seller_student_new2; vi  ../seller_student_new2/select_teacher_for_test_lesson.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student_new2-select_teacher_for_test_lesson.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		require_id:	$('#id_require_id').val(),
		teacher_tags:	$('#id_teacher_tags').val(),
		teaching_tags:	$('#id_teaching_tags').val(),
		lesson_tags:	$('#id_lesson_tags').val(),
		refresh_flag:	$('#id_refresh_flag').val(),
		identity:	$('#id_identity').val(),
		gender:	$('#id_gender').val(),
		tea_age:	$('#id_tea_age').val(),
		teacher_type:	$('#id_teacher_type').val(),
		region_version:	$('#id_region_version').val(),
		teacher_info:	$('#id_teacher_info').val()
		});
}
$(function(){


	$('#id_require_id').val(g_args.require_id);
	$('#id_teacher_tags').val(g_args.teacher_tags);
	$('#id_teaching_tags').val(g_args.teaching_tags);
	$('#id_lesson_tags').val(g_args.lesson_tags);
	$('#id_refresh_flag').val(g_args.refresh_flag);
	$('#id_identity').val(g_args.identity);
	$('#id_gender').val(g_args.gender);
	$('#id_tea_age').val(g_args.tea_age);
	$('#id_teacher_type').val(g_args.teacher_type);
	$('#id_region_version').val(g_args.region_version);
	$('#id_teacher_info').val(g_args.teacher_info);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">require_id</span>
                <input class="opt-change form-control" id="id_require_id" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["require_id title", "require_id", "th_require_id" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">teacher_tags</span>
                <input class="opt-change form-control" id="id_teacher_tags" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["teacher_tags title", "teacher_tags", "th_teacher_tags" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">teaching_tags</span>
                <input class="opt-change form-control" id="id_teaching_tags" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["teaching_tags title", "teaching_tags", "th_teaching_tags" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">lesson_tags</span>
                <input class="opt-change form-control" id="id_lesson_tags" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["lesson_tags title", "lesson_tags", "th_lesson_tags" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">refresh_flag</span>
                <input class="opt-change form-control" id="id_refresh_flag" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["refresh_flag title", "refresh_flag", "th_refresh_flag" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">identity</span>
                <input class="opt-change form-control" id="id_identity" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["identity title", "identity", "th_identity" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">gender</span>
                <input class="opt-change form-control" id="id_gender" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["gender title", "gender", "th_gender" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">tea_age</span>
                <input class="opt-change form-control" id="id_tea_age" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["tea_age title", "tea_age", "th_tea_age" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">teacher_type</span>
                <input class="opt-change form-control" id="id_teacher_type" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["teacher_type title", "teacher_type", "th_teacher_type" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">region_version</span>
                <input class="opt-change form-control" id="id_region_version" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["region_version title", "region_version", "th_region_version" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">teacher_info</span>
                <input class="opt-change form-control" id="id_teacher_info" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["teacher_info title", "teacher_info", "th_teacher_info" ]])!!}
*/
