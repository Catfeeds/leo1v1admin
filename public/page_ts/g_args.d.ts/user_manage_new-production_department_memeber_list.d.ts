interface GargsStatic {
	page_num:	number;
	page_count:	number;
	post:	number;
	department:	number;
	department_group:	number;
	user_info:	string;
	adminid:	number;
	main_department:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	uid	:any;
	account	:any;
	name	:any;
	email	:any;
	phone	:any;
	permission	:any;
	create_time	:any;
	del_flag	:any;
	administrator	:any;
	account_role	:any;
	wx_openid	:any;
	creater_adminid	:any;
	cardid	:any;
	seller_level	:any;
	wx_id	:any;
	tquin	:any;
	up_adminid	:any;
	admin_work_status	:any;
	last_login_time	:any;
	day_new_user_flag	:any;
	ytx_phone	:any;
	become_full_member_flag	:any;
	fingerprint1	:any;
	fingerprint2	:any;
	headpic	:any;
	call_phone_type	:any;
	call_phone_passwd	:any;
	company	:any;
	gender	:any;
	education	:any;
	employee_level	:any;
	gra_school	:any;
	gra_major	:any;
	identity_card	:any;
	order_end_time	:any;
	post	:any;
	department	:any;
	basic_pay	:any;
	merit_pay	:any;
	post_basic_pay	:any;
	post_merit_pay	:any;
	personal_email	:any;
	department_group	:any;
	personal_desc	:any;
	become_full_member_time	:any;
	resume_url	:any;
	main_department	:any;
	fulltime_teacher_type	:any;
	become_member_time	:any;
	leave_member_time	:any;
	id	:any;
	gender_str	:any;
	education_str	:any;
	company_str	:any;
	employee_level_str	:any;
	post_str	:any;
	department_group_str	:any;
	department_str	:any;
	create_time_str	:any;
	become_full_member_time_str	:any;
	order_end_time_str	:any;
	rurl	:any;
}

/*

tofile: 
	 mkdir -p ../user_manage_new; vi  ../user_manage_new/production_department_memeber_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-production_department_memeber_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			post:	$('#id_post').val(),
			department:	$('#id_department').val(),
			department_group:	$('#id_department_group').val(),
			user_info:	$('#id_user_info').val(),
			adminid:	$('#id_adminid').val(),
			main_department:	$('#id_main_department').val()
        });
    }


	$('#id_post').val(g_args.post);
	$('#id_department').val(g_args.department);
	$('#id_department_group').val(g_args.department_group);
	$('#id_user_info').val(g_args.user_info);
	$('#id_adminid').val(g_args.adminid);
	$('#id_main_department').val(g_args.main_department);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">post</span>
                <input class="opt-change form-control" id="id_post" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">department</span>
                <input class="opt-change form-control" id="id_department" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">department_group</span>
                <input class="opt-change form-control" id="id_department_group" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">user_info</span>
                <input class="opt-change form-control" id="id_user_info" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">adminid</span>
                <input class="opt-change form-control" id="id_adminid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">main_department</span>
                <input class="opt-change form-control" id="id_main_department" />
            </div>
        </div>
*/
