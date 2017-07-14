interface GargsStatic {
	date_type_config:	string;
	callerid:	string;
	origin:	string;
	phone:	string;
	status:	number;
	page_num:	number;
	phone_location:	string;
	subject:	number;//App\Enums\Esubject 
	origin_ex:	string;
	has_pad:	number;
	ass_adminid_flag:	number;//App\Enums\Eboolean 
	seller_resource_type:	number;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
interface RowData {
	phone	:any;
	ass_adminid	:any;
	tq_called_flag	:any;
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
	origin_userid	:any;
	sub_assign_adminid	:any;
	sub_assign_time	:any;
	st_test_paper	:any;
	tea_download_paper_time	:any;
	notify_lesson_flag_str	:any;
	notify_lesson_flag	:any;
	opt_time	:any;
	last_revisit_msg_sub	:any;
	user_desc_sub	:any;
	grade_str	:any;
	subject_str	:any;
	has_pad_str	:any;
	status_str	:any;
	tq_called_flag_str	:any;
	lesson_time	:any;
	teacher_nick	:any;
	ass_admin_nick	:any;
	origin_user_nick	:any;
	st_test_paper_flag_str	:any;
}

/*

tofile: 
	 mkdir -p ../seller_student; vi  ../seller_student/student_list2.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student-student_list2.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			callerid:	$('#id_callerid').val(),
			origin:	$('#id_origin').val(),
			phone:	$('#id_phone').val(),
			status:	$('#id_status').val(),
			phone_location:	$('#id_phone_location').val(),
			subject:	$('#id_subject').val(),
			origin_ex:	$('#id_origin_ex').val(),
			has_pad:	$('#id_has_pad').val(),
			ass_adminid_flag:	$('#id_ass_adminid_flag').val(),
			seller_resource_type:	$('#id_seller_resource_type').val(),
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val()
        });
    }

	Enum_map.append_option_list("subject",$("#id_subject")); 
	Enum_map.append_option_list("boolean",$("#id_ass_adminid_flag")); 

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
	$('#id_callerid').val(g_args.callerid);
	$('#id_origin').val(g_args.origin);
	$('#id_phone').val(g_args.phone);
	$('#id_status').val(g_args.status);
	$('#id_phone_location').val(g_args.phone_location);
	$('#id_subject').val(g_args.subject);
	$('#id_origin_ex').val(g_args.origin_ex);
	$('#id_has_pad').val(g_args.has_pad);
	$('#id_ass_adminid_flag').val(g_args.ass_adminid_flag);
	$('#id_seller_resource_type').val(g_args.seller_resource_type);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">callerid</span>
                <input class="opt-change form-control" id="id_callerid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">origin</span>
                <input class="opt-change form-control" id="id_origin" />
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
                <span class="input-group-addon">status</span>
                <input class="opt-change form-control" id="id_status" />
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
                <span class="input-group-addon">科目</span>
                <select class="opt-change form-control" id="id_subject" >
                </select>
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
                <span class="input-group-addon">has_pad</span>
                <input class="opt-change form-control" id="id_has_pad" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">boolean</span>
                <select class="opt-change form-control" id="id_ass_adminid_flag" >
                </select>
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">seller_resource_type</span>
                <input class="opt-change form-control" id="id_seller_resource_type" />
            </div>
        </div>
*/
