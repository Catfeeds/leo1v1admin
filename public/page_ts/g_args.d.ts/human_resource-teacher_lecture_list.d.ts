interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	grade:	number;
	trans_grade:	number;
	subject:	number;
	identity:	number;
	status:	number;
	page_num:	number;
	page_count:	number;
	phone:	string;
	teacherid:	number;
	is_test_flag:	number;
	have_wx:	number;
	full_time:	number;
	fulltime_flag:	number;
	id_train_through_new_time:	number;
	id_train_through_new:	number;
	zs_flag:	number;
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
	nick	:any;
	face	:any;
	phone	:any;
	grade	:any;
	subject	:any;
	title	:any;
	draw	:any;
	real_begin_time	:any;
	real_end_time	:any;
	teacher_re_submit_num	:any;
	account	:any;
	status	:any;
	reason	:any;
	add_time	:any;
	identity	:any;
	identity_image	:any;
	resume_url	:any;
	audio	:any;
	is_test_flag	:any;
	reference_name	:any;
	teacherid	:any;
	answer_begin_time	:any;
	grade_ex	:any;
	t_subject	:any;
	t_teacherid	:any;
	t_create_time	:any;
	textbook	:any;
	confirm_time	:any;
	grade_start	:any;
	grade_end	:any;
	not_grade	:any;
	trans_grade	:any;
	trans_grade_start	:any;
	trans_grade_end	:any;
	wx_openid	:any;
	user_agent	:any;
	accept_account	:any;
	zs_name	:any;
	appointment_id	:any;
	retrial_info	:any;
	teacher_accuracy_score	:any;
	full_time	:any;
	train_through_new_time	:any;
	train_through_new	:any;
	trans_grade_str	:any;
	full_time_str	:any;
	num	:any;
	add_time_str	:any;
	answer_begin_time_str	:any;
	confirm_time_str	:any;
	identity_str	:any;
	subject_str	:any;
	grade_str	:any;
	is_test_flag_str	:any;
	status_str	:any;
	t_subject_str	:any;
	have_wx_flag	:any;
	train_status_str	:any;
	train_through_str	:any;
	phone_ex	:any;
}

/*

tofile: 
	 mkdir -p ../human_resource; vi  ../human_resource/teacher_lecture_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/human_resource-teacher_lecture_list.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		date_type_config:	$('#id_date_type_config').val(),
		date_type:	$('#id_date_type').val(),
		opt_date_type:	$('#id_opt_date_type').val(),
		start_time:	$('#id_start_time').val(),
		end_time:	$('#id_end_time').val(),
		grade:	$('#id_grade').val(),
		trans_grade:	$('#id_trans_grade').val(),
		subject:	$('#id_subject').val(),
		identity:	$('#id_identity').val(),
		status:	$('#id_status').val(),
		phone:	$('#id_phone').val(),
		teacherid:	$('#id_teacherid').val(),
		is_test_flag:	$('#id_is_test_flag').val(),
		have_wx:	$('#id_have_wx').val(),
		full_time:	$('#id_full_time').val(),
		fulltime_flag:	$('#id_fulltime_flag').val(),
		id_train_through_new_time:	$('#id_id_train_through_new_time').val(),
		id_train_through_new:	$('#id_id_train_through_new').val(),
		zs_flag:	$('#id_zs_flag').val()
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
		});
	$('#id_grade').val(g_args.grade);
	$('#id_trans_grade').val(g_args.trans_grade);
	$('#id_subject').val(g_args.subject);
	$('#id_identity').val(g_args.identity);
	$('#id_status').val(g_args.status);
	$('#id_phone').val(g_args.phone);
	$('#id_teacherid').admin_select_user_new({
		"user_type"    : "teacher",
		"select_value" : g_args.teacherid,
		"onChange"     : load_data,
		"th_input_id"  : "th_teacherid",
		"can_sellect_all_flag"     : true
	});
	$('#id_is_test_flag').val(g_args.is_test_flag);
	$('#id_have_wx').val(g_args.have_wx);
	$('#id_full_time').val(g_args.full_time);
	$('#id_fulltime_flag').val(g_args.fulltime_flag);
	$('#id_id_train_through_new_time').val(g_args.id_train_through_new_time);
	$('#id_id_train_through_new').val(g_args.id_train_through_new);
	$('#id_zs_flag').val(g_args.zs_flag);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">grade</span>
                <input class="opt-change form-control" id="id_grade" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">trans_grade</span>
                <input class="opt-change form-control" id="id_trans_grade" />
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
                <span class="input-group-addon">status</span>
                <input class="opt-change form-control" id="id_status" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">phone</span>
                <input class="opt-change form-control" id="id_phone" />
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
                <span class="input-group-addon">is_test_flag</span>
                <input class="opt-change form-control" id="id_is_test_flag" />
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

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">zs_flag</span>
                <input class="opt-change form-control" id="id_zs_flag" />
            </div>
        </div>
*/
