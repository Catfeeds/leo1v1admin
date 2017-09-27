interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	ass_adminid:	number;
	page_num:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
interface RowData {
	phone	:any;
	ass_adminid	:any;
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
	grade_str	:any;
	subject_str	:any;
	status_str	:any;
	ass_admin_nick	:any;
	origin_user_nick	:any;
	admin_revisiterid_nick	:any;
}

/*

tofile: 
	 mkdir -p ../seller_student ; vi  ../seller_student/ass_add_student_list_ass.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student-ass_add_student_list_ass.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
			ass_adminid:	$('#id_ass_adminid').val()
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
	$('#id_ass_adminid').val(g_args.ass_adminid);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">ass_adminid</span>
                <input class="opt-change form-control" id="id_ass_adminid" />
            </div>
        </div>
*/
