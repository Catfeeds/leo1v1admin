interface GargsStatic {
	date_type_config:	string;
	userid:	number;
	teacherid:	number;
	del_flag:	number;
	phone:	string;
	subject:	number;//App\Enums\Esubject 
	st_application_id:	number;
	test_lesson_status:	number;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	page_num:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: string;
declare var g_adminid: string;
interface RowData {
	id	:any;
	log_time	:any;
	phone	:any;
	phone_location	:any;
	test_lesson_bind_adminid	:any;
	userid	:any;
	nick	:any;
	origin	:any;
	st_application_time	:any;
	st_class_time	:any;
	lessonid	:any;
	teacherid	:any;
	lesson_start	:any;
	lesson_end	:any;
	grade	:any;
	subject	:any;
	st_application_id	:any;
	user_desc	:any;
	test_lesson_status	:any;
	reason	:any;
	st_demand	:any;
	del_flag	:any;
	index	:any;
	subject_str	:any;
	test_lesson_status_str	:any;
	student_nick	:any;
	teacher_nick	:any;
	test_lesson_bind_admin_nick	:any;
	st_application_nick	:any;
	lesson_time	:any;
}

/*

tofile: 
	 mkdir -p ../seller_student; vi  ../seller_student/test_lesson_log_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student-test_lesson_log_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			userid:	$('#id_userid').val(),
			teacherid:	$('#id_teacherid').val(),
			del_flag:	$('#id_del_flag').val(),
			phone:	$('#id_phone').val(),
			subject:	$('#id_subject').val(),
			st_application_id:	$('#id_st_application_id').val(),
			test_lesson_status:	$('#id_test_lesson_status').val(),
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val()
        });
    }

	Enum_map.append_option_list("subject",$("#id_subject")); 

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
	$('#id_userid').val(g_args.userid);
	$('#id_teacherid').val(g_args.teacherid);
	$('#id_del_flag').val(g_args.del_flag);
	$('#id_phone').val(g_args.phone);
	$('#id_subject').val(g_args.subject);
	$('#id_st_application_id').val(g_args.st_application_id);
	$('#id_test_lesson_status').val(g_args.test_lesson_status);


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

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">teacherid</span>
                <input class="opt-change form-control" id="id_teacherid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">del_flag</span>
                <input class="opt-change form-control" id="id_del_flag" />
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
                <span class="input-group-addon">科目</span>
                <select class="opt-change form-control" id="id_subject" >
                </select>
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">st_application_id</span>
                <input class="opt-change form-control" id="id_st_application_id" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">test_lesson_status</span>
                <input class="opt-change form-control" id="id_test_lesson_status" />
            </div>
        </div>
*/
