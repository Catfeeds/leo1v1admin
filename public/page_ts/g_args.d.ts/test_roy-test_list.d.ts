interface GargsStatic {
	userid:	number;
	page_num:	number;
	page_count:	number;
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
	nick	:any;
	face	:any;
	upload_face_time	:any;
	praise	:any;
	exp	:any;
	phone	:any;
	stu_phone	:any;
	gender	:any;
	birth	:any;
	birthday_gift_time	:any;
	grade	:any;
	type	:any;
	test_status	:any;
	textbook	:any;
	region	:any;
	school	:any;
	address	:any;
	addr_code	:any;
	rate_score	:any;
	one_star	:any;
	two_star	:any;
	three_star	:any;
	four_star	:any;
	five_star	:any;
	rate_ability	:any;
	rate_attention	:any;
	rate_attitude	:any;
	parent_name	:any;
	parentid	:any;
	parent_type	:any;
	status	:any;
	revisit_cnt	:any;
	gift_sent	:any;
	reg_grade	:any;
	reg_time	:any;
	reg_ip	:any;
	login_cnt	:any;
	last_login_ip	:any;
	last_login_time	:any;
	operator_note	:any;
	last_modified_time	:any;
	editionid	:any;
	current_point	:any;
	is_called	:any;
	user_agent	:any;
	host_code	:any;
	guest_code	:any;
	ios_version	:any;
	android_version	:any;
	test_room	:any;
	revisit_status	:any;
	revisit_time	:any;
	hair	:any;
	clothes	:any;
	assistantid	:any;
	is_test_user	:any;
	originid	:any;
	origin_userid	:any;
	lesson_count_all	:any;
	lesson_count_left	:any;
	seller_adminid	:any;
	origin	:any;
	spree	:any;
	last_lesson_time	:any;
	money_all	:any;
	ass_assign_time	:any;
	email	:any;
	init_info_pdf_url	:any;
	phone_location	:any;
	realname	:any;
	ass_revisit_last_week_time	:any;
	ass_revisit_last_month_time	:any;
	sms_notify_flag	:any;
	last_revisit_admin_time	:any;
	last_revisit_adminid	:any;
	stu_email	:any;
	stu_lesson_stop_reason	:any;
	is_auto_set_type_flag	:any;
	origin_assistantid	:any;
	origin_level	:any;
	ass_master_adminid	:any;
	master_assign_time	:any;
	type_change_time	:any;
	stu_end_lesson_time	:any;
	grade_up	:any;
	province	:any;
	city	:any;
	area	:any;
	subject_ex	:any;
	grade_str	:any;
}

/*

tofile: 
	 mkdir -p ../test_roy; vi  ../test_roy/test_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/test_roy-test_list.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
		userid:	$('#id_userid').val()
    });
}
$(function(){


	$('#id_userid').val(g_args.userid);


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
*/
