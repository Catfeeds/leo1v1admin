interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	studentid:	number;
	teacherid:	number;
	confirm_flag:	number;
	seller_adminid:	number;
	lesson_status:	number;
	assistantid:	number;
	grade:	number;//App\Enums\Egrade 
	test_seller_id:	number;
	has_performance:	number;
	lesson_type:	number;
	subject:	number;
	lesson_count:	number;
	is_with_test_user:	number;
	lessonid:	number;
	page_num:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
interface RowData {
	lessonid	:any;
	courseid	:any;
	lesson_type	:any;
	lesson_count	:any;
	teacherid	:any;
	origin	:any;
	record_audio_server1	:any;
	record_audio_server2	:any;
	lesson_start	:any;
	lesson_end	:any;
	real_begin_time	:any;
	stu_id	:any;
	stu_phone	:any;
	stu_nick	:any;
	stu_user_agent	:any;
	origin_str	:any;
	work_intro	:any;
	work_status	:any;
	issue_url	:any;
	finish_url	:any;
	check_url	:any;
	tea_research_url	:any;
	ass_research_url	:any;
	score	:any;
	issue_time	:any;
	finish_time	:any;
	check_time	:any;
	tea_research_time	:any;
	ass_research_time	:any;
	lesson_status	:any;
	stu_score	:any;
	stu_comment	:any;
	stu_attitude	:any;
	stu_attention	:any;
	stu_ability	:any;
	stu_stability	:any;
	teacher_score	:any;
	teacher_comment	:any;
	tea_rate_time	:any;
	lesson_intro	:any;
	teacher_effect	:any;
	teacher_quality	:any;
	teacher_interact	:any;
	stu_praise	:any;
	stu_cw_upload_time	:any;
	stu_cw_status	:any;
	stu_cw_url	:any;
	tea_cw_name	:any;
	tea_cw_upload_time	:any;
	tea_cw_status	:any;
	use_ppt	:any;
	tea_cw_url	:any;
	is_complained	:any;
	complain_note	:any;
	lesson_upload_time	:any;
	stu_performance	:any;
	audio	:any;
	draw	:any;
	lesson_cancel_reason_type	:any;
	lesson_cancel_reason_next_lesson_time	:any;
	lesson_quiz	:any;
	lesson_quiz_status	:any;
	subject	:any;
	grade	:any;
	confirm_flag	:any;
	confirm_adminid	:any;
	confirm_time	:any;
	confirm_reason	:any;
	lesson_num	:any;
	tea_price	:any;
	level	:any;
	admin_revisiterid	:any;
	test_lesson_origin	:any;
	lesson_name	:any;
	performance_status	:any;
	performance	:any;
	new_test_listen	:any;
	number	:any;
	lesson_time	:any;
	lesson_end_str	:any;
	real_begin_time_str	:any;
	lesson_status_str	:any;
	lesson_vedio_flag	:any;
	lesson_vedio_flag_str	:any;
	stu_cw_status_str	:any;
	tea_cw_status_str	:any;
	work_status_str	:any;
	lesson_quiz_status_str	:any;
	is_complained_str	:any;
	homework_url	:any;
	lesson_type_str	:any;
	tea_nick	:any;
	admin_revisiter_nick	:any;
	teacher_effect_str	:any;
	teacher_quality_str	:any;
	teacher_interact_str	:any;
	stu_stability_str	:any;
	confirm_admin_nick	:any;
	confirm_flag_str	:any;
	grade_str	:any;
	subject_str	:any;
	teacher_money	:any;
}

/*

tofile: 
	 mkdir -p ../tea_manage ; vi  ../tea_manage/lesson_list_ass.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tea_manage-lesson_list_ass.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
			studentid:	$('#id_studentid').val(),
			teacherid:	$('#id_teacherid').val(),
			confirm_flag:	$('#id_confirm_flag').val(),
			seller_adminid:	$('#id_seller_adminid').val(),
			lesson_status:	$('#id_lesson_status').val(),
			assistantid:	$('#id_assistantid').val(),
			grade:	$('#id_grade').val(),
			test_seller_id:	$('#id_test_seller_id').val(),
			has_performance:	$('#id_has_performance').val(),
			lesson_type:	$('#id_lesson_type').val(),
			subject:	$('#id_subject').val(),
			lesson_count:	$('#id_lesson_count').val(),
			is_with_test_user:	$('#id_is_with_test_user').val(),
			lessonid:	$('#id_lessonid').val()
        });
    }

	Enum_map.append_option_list("grade",$("#id_grade")); 

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
	$('#id_studentid').val(g_args.studentid);
	$('#id_teacherid').val(g_args.teacherid);
	$('#id_confirm_flag').val(g_args.confirm_flag);
	$('#id_seller_adminid').val(g_args.seller_adminid);
	$('#id_lesson_status').val(g_args.lesson_status);
	$('#id_assistantid').val(g_args.assistantid);
	$('#id_grade').val(g_args.grade);
	$('#id_test_seller_id').val(g_args.test_seller_id);
	$('#id_has_performance').val(g_args.has_performance);
	$('#id_lesson_type').val(g_args.lesson_type);
	$('#id_subject').val(g_args.subject);
	$('#id_lesson_count').val(g_args.lesson_count);
	$('#id_is_with_test_user').val(g_args.is_with_test_user);
	$('#id_lessonid').val(g_args.lessonid);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">studentid</span>
                <input class="opt-change form-control" id="id_studentid" />
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
                <span class="input-group-addon">confirm_flag</span>
                <input class="opt-change form-control" id="id_confirm_flag" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">seller_adminid</span>
                <input class="opt-change form-control" id="id_seller_adminid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">lesson_status</span>
                <input class="opt-change form-control" id="id_lesson_status" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">assistantid</span>
                <input class="opt-change form-control" id="id_assistantid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">年级</span>
                <select class="opt-change form-control" id="id_grade" >
                </select>
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">test_seller_id</span>
                <input class="opt-change form-control" id="id_test_seller_id" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">has_performance</span>
                <input class="opt-change form-control" id="id_has_performance" />
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
                <span class="input-group-addon">subject</span>
                <input class="opt-change form-control" id="id_subject" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">lesson_count</span>
                <input class="opt-change form-control" id="id_lesson_count" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">is_with_test_user</span>
                <input class="opt-change form-control" id="id_is_with_test_user" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">lessonid</span>
                <input class="opt-change form-control" id="id_lessonid" />
            </div>
        </div>
*/
