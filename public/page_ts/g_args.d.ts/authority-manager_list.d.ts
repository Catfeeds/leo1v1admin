interface GargsStatic {
	assign_groupid:	number;
	assign_account_role:	number;
	creater_adminid:	number;
	adminid:	number;
	uid:	number;
	user_info:	string;
	has_question_user:	number;
	del_flag:	number;
	page_num:	number;
	page_count:	number;
	account_role:	number;
	cardid:	number;
	day_new_user_flag:	number;//枚举: App\Enums\Eboolean
	tquin:	number;
	fulltime_teacher_type:	number;
	call_phone_type:	number;
	seller_groupid_ex:	string;
	seller_level:	string;//枚举列表: \App\Enums\Eseller_level
 }
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	no_update_seller_level_flag	:any;
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
	old_permission	:any;
	reset_passwd_flag	:any;
	creater_admin_nick	:any;
	up_admin_nick	:any;
	account_role_str	:any;
	seller_level_str	:any;
	department_str	:any;
	become_full_member_flag_str	:any;
	no_update_seller_level_flag_str	:any;
	del_flag_str	:any;
	leave_time	:any;
	become_time	:any;
	day_new_user_flag_str	:any;
}

/*

tofile: 
	 mkdir -p ../authority; vi  ../authority/manager_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/authority-manager_list.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		assign_groupid:	$('#id_assign_groupid').val(),
		assign_account_role:	$('#id_assign_account_role').val(),
		creater_adminid:	$('#id_creater_adminid').val(),
		adminid:	$('#id_adminid').val(),
		uid:	$('#id_uid').val(),
		user_info:	$('#id_user_info').val(),
		has_question_user:	$('#id_has_question_user').val(),
		del_flag:	$('#id_del_flag').val(),
		account_role:	$('#id_account_role').val(),
		cardid:	$('#id_cardid').val(),
		day_new_user_flag:	$('#id_day_new_user_flag').val(),
		tquin:	$('#id_tquin').val(),
		fulltime_teacher_type:	$('#id_fulltime_teacher_type').val(),
		call_phone_type:	$('#id_call_phone_type').val(),
		seller_groupid_ex:	$('#id_seller_groupid_ex').val(),
		seller_level:	$('#id_seller_level').val()
		});
}
$(function(){


	$('#id_assign_groupid').val(g_args.assign_groupid);
	$('#id_assign_account_role').val(g_args.assign_account_role);
	$('#id_creater_adminid').val(g_args.creater_adminid);
	$('#id_adminid').admin_select_user_new({
		"user_type"    : "account",
		"select_value" : g_args.adminid,
		"onChange"     : load_data,
		"th_input_id"  : "th_adminid",
		"only_show_in_th_input"     : false,
		"can_select_all_flag"     : true
	});
	$('#id_uid').val(g_args.uid);
	$('#id_user_info').val(g_args.user_info);
	$('#id_has_question_user').val(g_args.has_question_user);
	$('#id_del_flag').val(g_args.del_flag);
	$('#id_account_role').val(g_args.account_role);
	$('#id_cardid').val(g_args.cardid);
	$('#id_day_new_user_flag').admin_set_select_field({
		"enum_type"    : "boolean",
		"field_name" : "day_new_user_flag",
		"select_value" : g_args.day_new_user_flag,
		"onChange"     : load_data,
		"multi_select_flag"     : false ,
		"th_input_id"  : "th_day_new_user_flag",
		"only_show_in_th_input"     : false,
		"btn_id_config"     : {},
	});
	$('#id_tquin').val(g_args.tquin);
	$('#id_fulltime_teacher_type').val(g_args.fulltime_teacher_type);
	$('#id_call_phone_type').val(g_args.call_phone_type);
	$('#id_seller_groupid_ex').val(g_args.seller_groupid_ex);
	$('#id_seller_level').admin_set_select_field({
		"enum_type"    : "seller_level",
		"field_name" : "seller_level",
		"select_value" : g_args.seller_level,
		"multi_select_flag"     : true,
		"onChange"     : load_data,
		"th_input_id"  : "th_seller_level",
		"only_show_in_th_input"     : false,
		"btn_id_config"     : {},
	});


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
{!!\App\Helper\Utils::th_order_gen([["assign_groupid title", "assign_groupid", "th_assign_groupid" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">assign_account_role</span>
                <input class="opt-change form-control" id="id_assign_account_role" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["assign_account_role title", "assign_account_role", "th_assign_account_role" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">creater_adminid</span>
                <input class="opt-change form-control" id="id_creater_adminid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["creater_adminid title", "creater_adminid", "th_creater_adminid" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">adminid</span>
                <input class="opt-change form-control" id="id_adminid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["adminid title", "adminid", "th_adminid" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">uid</span>
                <input class="opt-change form-control" id="id_uid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["uid title", "uid", "th_uid" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">user_info</span>
                <input class="opt-change form-control" id="id_user_info" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["user_info title", "user_info", "th_user_info" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">has_question_user</span>
                <input class="opt-change form-control" id="id_has_question_user" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["has_question_user title", "has_question_user", "th_has_question_user" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">del_flag</span>
                <input class="opt-change form-control" id="id_del_flag" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["del_flag title", "del_flag", "th_del_flag" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_num title", "page_num", "th_page_num" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_count title", "page_count", "th_page_count" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">account_role</span>
                <input class="opt-change form-control" id="id_account_role" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["account_role title", "account_role", "th_account_role" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">cardid</span>
                <input class="opt-change form-control" id="id_cardid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["cardid title", "cardid", "th_cardid" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">boolean</span>
                <select class="opt-change form-control" id="id_day_new_user_flag" >
                </select>
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["day_new_user_flag title", "day_new_user_flag", "th_day_new_user_flag" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">tquin</span>
                <input class="opt-change form-control" id="id_tquin" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["tquin title", "tquin", "th_tquin" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">fulltime_teacher_type</span>
                <input class="opt-change form-control" id="id_fulltime_teacher_type" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["fulltime_teacher_type title", "fulltime_teacher_type", "th_fulltime_teacher_type" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">call_phone_type</span>
                <input class="opt-change form-control" id="id_call_phone_type" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["call_phone_type title", "call_phone_type", "th_call_phone_type" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">seller_groupid_ex</span>
                <input class="opt-change form-control" id="id_seller_groupid_ex" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["seller_groupid_ex title", "seller_groupid_ex", "th_seller_groupid_ex" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">seller_level</span>
                <input class="opt-change form-control" id="id_seller_level" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["seller_level title", "seller_level", "th_seller_level" ]])!!}
*/
