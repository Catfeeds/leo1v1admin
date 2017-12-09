interface GargsStatic {
	require_id:	number;
	identity:	number;
	gender:	number;
	age:	number;
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
	free_time_new	:any;
	age_flag	:any;
	is_identity	:any;
	is_gender	:any;
	is_age	:any;
	match_num	:any;
	identity_str	:any;
	gender_str	:any;
	ruzhi_day	:any;
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
		identity:	$('#id_identity').val(),
		gender:	$('#id_gender').val(),
		age:	$('#id_age').val(),
		refresh_flag:	$('#id_refresh_flag').val()
		});
}
$(function(){


	$('#id_require_id').val(g_args.require_id);
	$('#id_identity').val(g_args.identity);
	$('#id_gender').val(g_args.gender);
	$('#id_age').val(g_args.age);
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
                <span class="input-group-addon">age</span>
                <input class="opt-change form-control" id="id_age" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">refresh_flag</span>
                <input class="opt-change form-control" id="id_refresh_flag" />
            </div>
        </div>
*/
