interface GargsStatic {
	web_page_id:	number;
	page_num:	number;
	page_count:	number;
	uid:	number;
	account_role:	number;//\App\Enums\Eaccount_role
	web_page_title:	number;
	web_page_url:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	create_time	:any;
	leave_member_time	:any;
	become_member_time	:any;
	call_phone_type	:any;
	call_phone_passwd	:any;
	fingerprint1	:any;
	ytx_phone	:any;
	wx_id	:any;
	up_adminid	:any;
	day_new_user_flag	:any;
	account_role	:any;
	creater_adminid	:any;
	uid	:any;
	del_flag	:any;
	account	:any;
	seller_level	:any;
	name	:any;
	nickname	:any;
	email	:any;
	phone	:any;
	password	:any;
	permission	:any;
	tquin	:any;
	wx_openid	:any;
	cardid	:any;
	become_full_member_flag	:any;
	main_department	:any;
	fulltime_teacher_type	:any;
	account_role_str	:any;
	seller_level_str	:any;
	department_str	:any;
	become_full_member_flag_str	:any;
}

/*

tofile: 
	 mkdir -p ../admin_manage; vi  ../admin_manage/web_page_share.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/admin_manage-web_page_share.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
		web_page_id:	$('#id_web_page_id').val(),
		uid:	$('#id_uid').val(),
		account_role:	$('#id_account_role').val(),
		web_page_title:	$('#id_web_page_title').val(),
		web_page_url:	$('#id_web_page_url').val()
    });
}
$(function(){

	Enum_map.append_option_list("account_role",$("#id_account_role"));

	$('#id_web_page_id').val(g_args.web_page_id);
	$('#id_uid').val(g_args.uid);
	$('#id_account_role').val(g_args.account_role);
	$('#id_web_page_title').val(g_args.web_page_title);
	$('#id_web_page_url').val(g_args.web_page_url);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">web_page_id</span>
                <input class="opt-change form-control" id="id_web_page_id" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">uid</span>
                <input class="opt-change form-control" id="id_uid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">account_role</span>
                <select class="opt-change form-control" id="id_account_role" >
                </select>
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">web_page_title</span>
                <input class="opt-change form-control" id="id_web_page_title" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">web_page_url</span>
                <input class="opt-change form-control" id="id_web_page_url" />
            </div>
        </div>
*/
