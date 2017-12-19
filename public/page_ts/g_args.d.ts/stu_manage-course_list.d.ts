interface GargsStatic {
	sid:	number;
	competition_flag:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	userid	:any;
	courseid	:any;
	grade	:any;
	subject	:any;
	teacherid	:any;
	lesson_grade_type	:any;
	lesson_count	:any;
	competition_flag	:any;
	no_finish_lesson_count	:any;
	finish_lesson_count	:any;
	add_time	:any;
	assistantid	:any;
	course_type	:any;
	default_lesson_count	:any;
	assigned_lesson_count	:any;
	course_status	:any;
	week_comment_num	:any;
	enable_video	:any;
	reset_lesson_count_flag	:any;
	left_lesson_count	:any;
	add_time_str	:any;
	teacher_nick	:any;
	assistant_nick	:any;
	course_status_str	:any;
	grade_str	:any;
	subject_str	:any;
	enable_video_str	:any;
	reset_lesson_count_flag_str	:any;
	course_type_str	:any;
	week_comment_num_str	:any;
}

/*

tofile: 
	 mkdir -p ../stu_manage; vi  ../stu_manage/course_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/stu_manage-course_list.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		sid:	$('#id_sid').val(),
		competition_flag:	$('#id_competition_flag').val()
		});
}
$(function(){


	$('#id_sid').val(g_args.sid);
	$('#id_competition_flag').val(g_args.competition_flag);


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

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">competition_flag</span>
                <input class="opt-change form-control" id="id_competition_flag" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["competition_flag title", "competition_flag", "th_competition_flag" ]])!!}
*/
