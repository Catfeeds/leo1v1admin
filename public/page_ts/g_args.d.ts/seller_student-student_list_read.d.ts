interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	origin:	string;
	grade:	number;//App\Enums\Egrade 
	subject:	number;//App\Enums\Esubject 
	phone:	string;
	nick:	string;
	phone_location:	string;
	admin_revisiterid:	number;
	status:	number;
	origin_ex:	string;
	page_num:	number;
	page_count:	number;
	has_pad:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
interface RowData {
	phone	:any;
	userid	:any;
	notify_lesson_day1	:any;
	notify_lesson_day2	:any;
	money_all	:any;
	last_revisit_time	:any;
	next_revisit_time	:any;
	st_application_time	:any;
	phone_location	:any;
	id	:any;
	add_time	:any;
	origin	:any;
	nick	:any;
	status	:any;
	user_desc	:any;
	grade	:any;
	subject	:any;
	has_pad	:any;
	admin_revisiterid	:any;
	admin_assign_time	:any;
	last_revisit_msg	:any;
	teacherid	:any;
	lesson_start	:any;
	lesson_end	:any;
	first_revisite_time	:any;
	st_arrange_lessonid	:any;
	subject_str	:any;
	has_pad_str	:any;
	lesson_time	:any;
	status_str	:any;
	grade_str	:any;
	opt_time	:any;
	number	:any;
	admin_revisiterid_nick	:any;
	teacher_nick	:any;
}

/*

tofile: 
	 mkdir -p ../seller_student ; vi  ../seller_student/student_list_read.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student-student_list_read.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
			origin:	$('#id_origin').val(),
			grade:	$('#id_grade').val(),
			subject:	$('#id_subject').val(),
			phone:	$('#id_phone').val(),
			nick:	$('#id_nick').val(),
			phone_location:	$('#id_phone_location').val(),
			admin_revisiterid:	$('#id_admin_revisiterid').val(),
			status:	$('#id_status').val(),
			origin_ex:	$('#id_origin_ex').val(),
			page_count:	$('#id_page_count').val(),
			has_pad:	$('#id_has_pad').val()
        });
    }

	Enum_map.append_option_list("grade",$("#id_grade")); 
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
	$('#id_origin').val(g_args.origin);
	$('#id_grade').val(g_args.grade);
	$('#id_subject').val(g_args.subject);
	$('#id_phone').val(g_args.phone);
	$('#id_nick').val(g_args.nick);
	$('#id_phone_location').val(g_args.phone_location);
	$('#id_admin_revisiterid').val(g_args.admin_revisiterid);
	$('#id_status').val(g_args.status);
	$('#id_origin_ex').val(g_args.origin_ex);
	$('#id_page_count').val(g_args.page_count);
	$('#id_has_pad').val(g_args.has_pad);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">origin</span>
                <input class="opt-change form-control" id="id_origin" />
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
                <span class="input-group-addon">科目</span>
                <select class="opt-change form-control" id="id_subject" >
                </select>
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
                <span class="input-group-addon">nick</span>
                <input class="opt-change form-control" id="id_nick" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">phone_location</span>
                <input class="opt-change form-control" id="id_phone_location" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">admin_revisiterid</span>
                <input class="opt-change form-control" id="id_admin_revisiterid" />
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
                <span class="input-group-addon">origin_ex</span>
                <input class="opt-change form-control" id="id_origin_ex" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">page_count</span>
                <input class="opt-change form-control" id="id_page_count" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">has_pad</span>
                <input class="opt-change form-control" id="id_has_pad" />
            </div>
        </div>
*/
