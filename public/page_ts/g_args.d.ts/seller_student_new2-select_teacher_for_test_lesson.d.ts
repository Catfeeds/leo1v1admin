interface GargsStatic {
	require_id:	number;
	teacher_tags:	string;
	teaching_tags:	string;
	lesson_tags:	string;
	identity:	number;
	gender:	number;
	tea_age:	number;
	refresh_flag:	number;
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
	age_flag	:any;
	is_identity	:any;
	is_gender	:any;
	is_age	:any;
	match_num	:any;
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
		identity:	$('#id_identity').val(),
		gender:	$('#id_gender').val(),
		tea_age:	$('#id_tea_age').val(),
		refresh_flag:	$('#id_refresh_flag').val()
		});
}
$(function(){


	$('#id_require_id').val(g_args.require_id);
	$('#id_teacher_tags').val(g_args.teacher_tags);
	$('#id_teaching_tags').val(g_args.teaching_tags);
	$('#id_lesson_tags').val(g_args.lesson_tags);
	$('#id_identity').val(g_args.identity);
	$('#id_gender').val(g_args.gender);
	$('#id_tea_age').val(g_args.tea_age);
	$('#id_refresh_flag').val(g_args.refresh_flag);


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

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">teacher_tags</span>
                <input class="opt-change form-control" id="id_teacher_tags" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">teaching_tags</span>
                <input class="opt-change form-control" id="id_teaching_tags" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">lesson_tags</span>
                <input class="opt-change form-control" id="id_lesson_tags" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">identity</span>
                <input class="opt-change form-control" id="id_identity" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">gender</span>
                <input class="opt-change form-control" id="id_gender" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">tea_age</span>
                <input class="opt-change form-control" id="id_tea_age" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">refresh_flag</span>
                <input class="opt-change form-control" id="id_refresh_flag" />
            </div>
        </div>
*/
