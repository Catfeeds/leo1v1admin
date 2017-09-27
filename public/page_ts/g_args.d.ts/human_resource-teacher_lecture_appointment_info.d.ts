interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	lecture_appointment_status:	number;
	teacherid:	number;
	status:	number;
	interview_type:	number;
	user_name:	string;
	record_status:	number;
	page_num:	number;
	page_count:	number;
	grade:	number;
	subject:	number;
	have_wx:	number;
	lecture_revisit_type:	number;
	lecture_revisit_type_new:	number;
	full_time:	number;
	show_full_time:	number;
	teacher_ref_type:	string;//枚举列表: App\Enums\Eteacher_ref_type
 	fulltime_teacher_type:	number;
	accept_adminid:	number;
	second_train_status:	number;
	teacher_pass_type:	number;
	tea_adminid:	number;
	fulltime_flag:	number;
	next_day:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	id	:any;
	name	:any;
	phone	:any;
	email	:any;
	textbook	:any;
	school	:any;
	train_through_new_time	:any;
	grade_ex	:any;
	subject_ex	:any;
	trans_grade_ex	:any;
	trans_subject_ex	:any;
	grade_1v1	:any;
	trans_grade_1v1	:any;
	teacher_type	:any;
	custom	:any;
	self_introduction_experience	:any;
	full_time	:any;
	lecture_appointment_status	:any;
	reference	:any;
	answer_begin_time	:any;
	answer_end_time	:any;
	status	:any;
	lesson_start	:any;
	lecture_revisit_type	:any;
	trial_train_status	:any;
	subject	:any;
	grade	:any;
	acc	:any;
	reason	:any;
	record_info	:any;
	train_lessonid	:any;
	interviewer_teacherid	:any;
	reference_name	:any;
	teacherid	:any;
	account	:any;
	zs_name	:any;
	train_teacherid	:any;
	qq	:any;
	wx_openid	:any;
	user_agent	:any;
	hand_flag	:any;
	full_status	:any;
	full_record_info	:any;
	teacher_pass_type	:any;
	no_pass_reason	:any;
	begin	:any;
	end	:any;
	answer_time	:any;
	teacher_type_str	:any;
	interviewer_teacher_str	:any;
	lecture_appointment_status_str	:any;
	lecture_revisit_type_str	:any;
	full_time_str	:any;
	subject_ex_str	:any;
	trans_subject_ex_str	:any;
	status_str	:any;
	full_status_str	:any;
	phone_ex	:any;
	qq_ex	:any;
	email_ex	:any;
	have_wx_flag	:any;
	lecture_revisit_type_new_str	:any;
	train_through_new_time_str	:any;
	grade_ex_str	:any;
}

/*

tofile: 
	 mkdir -p ../human_resource; vi  ../human_resource/teacher_lecture_appointment_info.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/human_resource-teacher_lecture_appointment_info.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type_config:	$('#id_date_type_config').val(),
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
			lecture_appointment_status:	$('#id_lecture_appointment_status').val(),
			teacherid:	$('#id_teacherid').val(),
			status:	$('#id_status').val(),
			interview_type:	$('#id_interview_type').val(),
			user_name:	$('#id_user_name').val(),
			record_status:	$('#id_record_status').val(),
			grade:	$('#id_grade').val(),
			subject:	$('#id_subject').val(),
			have_wx:	$('#id_have_wx').val(),
			lecture_revisit_type:	$('#id_lecture_revisit_type').val(),
			lecture_revisit_type_new:	$('#id_lecture_revisit_type_new').val(),
			full_time:	$('#id_full_time').val(),
			show_full_time:	$('#id_show_full_time').val(),
			teacher_ref_type:	$('#id_teacher_ref_type').val(),
			fulltime_teacher_type:	$('#id_fulltime_teacher_type').val(),
			accept_adminid:	$('#id_accept_adminid').val(),
			second_train_status:	$('#id_second_train_status').val(),
			teacher_pass_type:	$('#id_teacher_pass_type').val(),
			tea_adminid:	$('#id_tea_adminid').val(),
			fulltime_flag:	$('#id_fulltime_flag').val(),
			next_day:	$('#id_next_day').val()
        });
    }


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
	$('#id_lecture_appointment_status').val(g_args.lecture_appointment_status);
	$('#id_teacherid').val(g_args.teacherid);
	$('#id_status').val(g_args.status);
	$('#id_interview_type').val(g_args.interview_type);
	$('#id_user_name').val(g_args.user_name);
	$('#id_record_status').val(g_args.record_status);
	$('#id_grade').val(g_args.grade);
	$('#id_subject').val(g_args.subject);
	$('#id_have_wx').val(g_args.have_wx);
	$('#id_lecture_revisit_type').val(g_args.lecture_revisit_type);
	$('#id_lecture_revisit_type_new').val(g_args.lecture_revisit_type_new);
	$('#id_full_time').val(g_args.full_time);
	$('#id_show_full_time').val(g_args.show_full_time);
	$('#id_teacher_ref_type').val(g_args.teacher_ref_type);
	$.enum_multi_select( $('#id_teacher_ref_type'), 'teacher_ref_type', function(){load_data();} )
	$('#id_fulltime_teacher_type').val(g_args.fulltime_teacher_type);
	$('#id_accept_adminid').val(g_args.accept_adminid);
	$('#id_second_train_status').val(g_args.second_train_status);
	$('#id_teacher_pass_type').val(g_args.teacher_pass_type);
	$('#id_tea_adminid').val(g_args.tea_adminid);
	$('#id_fulltime_flag').val(g_args.fulltime_flag);
	$('#id_next_day').val(g_args.next_day);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">lecture_appointment_status</span>
                <input class="opt-change form-control" id="id_lecture_appointment_status" />
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
                <span class="input-group-addon">status</span>
                <input class="opt-change form-control" id="id_status" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">interview_type</span>
                <input class="opt-change form-control" id="id_interview_type" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">user_name</span>
                <input class="opt-change form-control" id="id_user_name" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">record_status</span>
                <input class="opt-change form-control" id="id_record_status" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">grade</span>
                <input class="opt-change form-control" id="id_grade" />
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
                <span class="input-group-addon">have_wx</span>
                <input class="opt-change form-control" id="id_have_wx" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">lecture_revisit_type</span>
                <input class="opt-change form-control" id="id_lecture_revisit_type" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">lecture_revisit_type_new</span>
                <input class="opt-change form-control" id="id_lecture_revisit_type_new" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">full_time</span>
                <input class="opt-change form-control" id="id_full_time" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">show_full_time</span>
                <input class="opt-change form-control" id="id_show_full_time" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">teacher_ref_type</span>
                <input class="opt-change form-control" id="id_teacher_ref_type" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">fulltime_teacher_type</span>
                <input class="opt-change form-control" id="id_fulltime_teacher_type" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">accept_adminid</span>
                <input class="opt-change form-control" id="id_accept_adminid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">second_train_status</span>
                <input class="opt-change form-control" id="id_second_train_status" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">teacher_pass_type</span>
                <input class="opt-change form-control" id="id_teacher_pass_type" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">tea_adminid</span>
                <input class="opt-change form-control" id="id_tea_adminid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">fulltime_flag</span>
                <input class="opt-change form-control" id="id_fulltime_flag" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">next_day</span>
                <input class="opt-change form-control" id="id_next_day" />
            </div>
        </div>
*/
