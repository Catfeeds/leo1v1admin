interface GargsStatic {
	userid:	number;
	start_date:	string;
	end_date:	string;
	lesson_type:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	test_lesson_subject_id	:any;
	lessonid	:any;
	lesson_type	:any;
	lesson_start	:any;
	lesson_end	:any;
	lesson_intro	:any;
	grade	:any;
	subject	:any;
	confirm_flag	:any;
	assistantid	:any;
	ass_phone	:any;
	lesson_num	:any;
	userid	:any;
	lesson_name	:any;
	lesson_status	:any;
	ass_comment_audit	:any;
	homework_status	:any;
	stu_status	:any;
	tea_status	:any;
	editionid	:any;
	textbook	:any;
	train_type	:any;
	finish_url	:any;
	check_url	:any;
	tea_cw_url	:any;
	tea_cw_upload_time	:any;
	tea_cw_pic_flag	:any;
	tea_cw_pic	:any;
	tea_cw_origin	:any;
	stu_cw_origin	:any;
	tea_cw_file_id	:any;
	stu_cw_file_id	:any;
	stu_cw_url	:any;
	stu_cw_upload_time	:any;
	issue_url	:any;
	issue_time	:any;
	pdf_question_count	:any;
	tea_more_cw_url	:any;
	stu_test_paper	:any;
	require_adminid	:any;
	cc_account	:any;
	cc_phone	:any;
	accept_adminid	:any;
	stu_request_test_lesson_demand	:any;
	jw_name	:any;
	jw_phone	:any;
	address	:any;
	interests_and_hobbies	:any;
	character_type	:any;
	need_teacher_style	:any;
	extra_improvement	:any;
	habit_remodel	:any;
	study_habit	:any;
	lesson_type_str	:any;
	grade_str	:any;
	extra_improvement_str	:any;
	habit_remodel_str	:any;
	lesson_time	:any;
	tea_comment_str	:any;
	pdf_status_str	:any;
}

/*

tofile: 
	 mkdir -p ../teacher_info; vi  ../teacher_info/get_lesson_list_new.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/teacher_info-get_lesson_list_new.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		userid:	$('#id_userid').val(),
		start_date:	$('#id_start_date').val(),
		end_date:	$('#id_end_date').val(),
		lesson_type:	$('#id_lesson_type').val()
		});
}
$(function(){


	$('#id_userid').admin_select_user_new({
		"user_type"    : "student",
		"select_value" : g_args.userid,
		"onChange"     : load_data,
		"th_input_id"  : "th_userid",
		"only_show_in_th_input"     : false,
		"can_select_all_flag"     : true
	});
	$('#id_start_date').val(g_args.start_date);
	$('#id_end_date').val(g_args.end_date);
	$('#id_lesson_type').val(g_args.lesson_type);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">userid</span>
                <input class="opt-change form-control" id="id_userid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["userid title", "userid", "th_userid" ]])!!}

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
*/
