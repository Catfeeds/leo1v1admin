interface GargsStatic {
	assign_groupid:	number;
	assign_account_role:	number;
	creater_adminid:	number;
	uid:	number;
	user_info:	string;
	has_question_user:	number;
	del_flag:	number;
	page_num:	number;
	account_role:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
interface RowData {
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
	reset_passwd_flag	:any;
	creater_admin_nick	:any;
	account_role_str	:any;
	seller_level_str	:any;
}

/*

tofile: 
	 mkdir -p ../authority ; vi  ../authority/manager_list_for_seller.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/authority-manager_list_for_seller.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			assign_groupid:	$('#id_assign_groupid').val(),
			assign_account_role:	$('#id_assign_account_role').val(),
			creater_adminid:	$('#id_creater_adminid').val(),
			uid:	$('#id_uid').val(),
			user_info:	$('#id_user_info').val(),
			has_question_user:	$('#id_has_question_user').val(),
			del_flag:	$('#id_del_flag').val(),
			account_role:	$('#id_account_role').val()
        });
    }


	$('#id_assign_groupid').val(g_args.assign_groupid);
	$('#id_assign_account_role').val(g_args.assign_account_role);
	$('#id_creater_adminid').val(g_args.creater_adminid);
	$('#id_uid').val(g_args.uid);
	$('#id_user_info').val(g_args.user_info);
	$('#id_has_question_user').val(g_args.has_question_user);
	$('#id_del_flag').val(g_args.del_flag);
	$('#id_account_role').val(g_args.account_role);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">assign_groupid</span>
                <input class="opt-change form-control" id="id_assign_groupid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">assign_account_role</span>
                <input class="opt-change form-control" id="id_assign_account_role" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">creater_adminid</span>
                <input class="opt-change form-control" id="id_creater_adminid" />
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
                <span class="input-group-addon">user_info</span>
                <input class="opt-change form-control" id="id_user_info" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">has_question_user</span>
                <input class="opt-change form-control" id="id_has_question_user" />
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
                <span class="input-group-addon">account_role</span>
                <input class="opt-change form-control" id="id_account_role" />
            </div>
        </div>
*/
