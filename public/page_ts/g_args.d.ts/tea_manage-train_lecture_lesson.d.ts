interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	lesson_status:	number;
	subject:	number;
	identity:	number;
	grade:	number;
	check_status:	number;
	train_teacherid:	number;
	page_num:	number;
	page_count:	number;
	lessonid:	number;
	res_teacherid:	number;
	have_wx:	number;
	lecture_status:	number;
	train_email_flag:	number;
	is_all:	number;
	full_time:	number;
	fulltime_flag:	number;
	teacherid:	string;
	id_train_through_new_time:	number;
	id_train_through_new:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	lessonid	:any;
	lesson_start	:any;
	lesson_end	:any;
	lesson_name	:any;
	audio	:any;
	draw	:any;
	grade	:any;
	subject	:any;
	lesson_status	:any;
	teacherid	:any;
	nick	:any;
	user_agent	:any;
	l_teacherid	:any;
	courseid	:any;
	record_type	:any;
	reference	:any;
	teacher_type	:any;
	reference_name	:any;
	trial_train_status	:any;
	acc	:any;
	phone_spare	:any;
	lecture_status	:any;
	real_teacherid	:any;
	account	:any;
	real_begin_time	:any;
	record_info	:any;
	identity	:any;
	add_time	:any;
	wx_openid	:any;
	train_email_flag	:any;
	lecture_status_ex	:any;
	access_id	:any;
	train_type	:any;
	zs_account	:any;
	zs_name	:any;
	tt_train_type	:any;
	tt_train_lessonid	:any;
	tt_id	:any;
	tt_add_time	:any;
	resume_url	:any;
	train_through_new_time	:any;
	train_through_new	:any;
	lesson_time	:any;
	lesson_status_str	:any;
	grade_str	:any;
	subject_str	:any;
	train_email_flag_str	:any;
	train_status_str	:any;
	train_through_str	:any;
	trial_train_status_str	:any;
	tea_nick	:any;
	lecture_status_str	:any;
	add_time_str	:any;
	have_wx_flag	:any;
	identity_str	:any;
	phone_ex	:any;
}

/*

tofile: 
	 mkdir -p ../tea_manage; vi  ../tea_manage/train_lecture_lesson.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tea_manage-train_lecture_lesson.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
		date_type_config:	$('#id_date_type_config').val(),
		date_type:	$('#id_date_type').val(),
		opt_date_type:	$('#id_opt_date_type').val(),
		start_time:	$('#id_start_time').val(),
		end_time:	$('#id_end_time').val(),
		lesson_status:	$('#id_lesson_status').val(),
		subject:	$('#id_subject').val(),
		identity:	$('#id_identity').val(),
		grade:	$('#id_grade').val(),
		check_status:	$('#id_check_status').val(),
		train_teacherid:	$('#id_train_teacherid').val(),
		lessonid:	$('#id_lessonid').val(),
		res_teacherid:	$('#id_res_teacherid').val(),
		have_wx:	$('#id_have_wx').val(),
		lecture_status:	$('#id_lecture_status').val(),
		train_email_flag:	$('#id_train_email_flag').val(),
		is_all:	$('#id_is_all').val(),
		full_time:	$('#id_full_time').val(),
		fulltime_flag:	$('#id_fulltime_flag').val(),
		teacherid:	$('#id_teacherid').val(),
		id_train_through_new_time:	$('#id_id_train_through_new_time').val(),
		id_train_through_new:	$('#id_id_train_through_new').val()
    });
}
$(function(){


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
	$('#id_lesson_status').val(g_args.lesson_status);
	$('#id_subject').val(g_args.subject);
	$('#id_identity').val(g_args.identity);
	$('#id_grade').val(g_args.grade);
	$('#id_check_status').val(g_args.check_status);
	$('#id_train_teacherid').val(g_args.train_teacherid);
	$('#id_lessonid').val(g_args.lessonid);
	$('#id_res_teacherid').val(g_args.res_teacherid);
	$('#id_have_wx').val(g_args.have_wx);
	$('#id_lecture_status').val(g_args.lecture_status);
	$('#id_train_email_flag').val(g_args.train_email_flag);
	$('#id_is_all').val(g_args.is_all);
	$('#id_full_time').val(g_args.full_time);
	$('#id_fulltime_flag').val(g_args.fulltime_flag);
	$('#id_teacherid').val(g_args.teacherid);
	$('#id_id_train_through_new_time').val(g_args.id_train_through_new_time);
	$('#id_id_train_through_new').val(g_args.id_train_through_new);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">lesson_status</span>
                <input class="opt-change form-control" id="id_lesson_status" />
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
                <span class="input-group-addon">identity</span>
                <input class="opt-change form-control" id="id_identity" />
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
                <span class="input-group-addon">check_status</span>
                <input class="opt-change form-control" id="id_check_status" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">train_teacherid</span>
                <input class="opt-change form-control" id="id_train_teacherid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">lessonid</span>
                <input class="opt-change form-control" id="id_lessonid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">res_teacherid</span>
                <input class="opt-change form-control" id="id_res_teacherid" />
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
                <span class="input-group-addon">lecture_status</span>
                <input class="opt-change form-control" id="id_lecture_status" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">train_email_flag</span>
                <input class="opt-change form-control" id="id_train_email_flag" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">is_all</span>
                <input class="opt-change form-control" id="id_is_all" />
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
                <span class="input-group-addon">fulltime_flag</span>
                <input class="opt-change form-control" id="id_fulltime_flag" />
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
                <span class="input-group-addon">id_train_through_new_time</span>
                <input class="opt-change form-control" id="id_id_train_through_new_time" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">id_train_through_new</span>
                <input class="opt-change form-control" id="id_id_train_through_new" />
            </div>
        </div>
*/
