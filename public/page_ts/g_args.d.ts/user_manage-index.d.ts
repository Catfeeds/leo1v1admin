interface GargsStatic {
	grade:	number;//枚举: App\Enums\Egrade
	all_flag:	number;
	test_user:	number;
	originid:	number;
	user_name:	string;
	phone:	string;
	assistantid:	number;
	seller_adminid:	number;
	order_type:	number;
	student_type:	number;
	page_num:	number;
	page_count:	number;
	userid:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	origin_userid	:any;
	userid	:any;
	nick	:any;
	realname	:any;
	spree	:any;
	phone	:any;
	is_test_user	:any;
	originid	:any;
	origin	:any;
	grade	:any;
	praise	:any;
	parent_name	:any;
	parent_type	:any;
	last_login_ip	:any;
	last_lesson_time	:any;
	last_login_time	:any;
	assistantid	:any;
	lesson_count_all	:any;
	lesson_count_left	:any;
	user_agent	:any;
	seller_adminid	:any;
	ass_assign_time	:any;
	reg_time	:any;
	phone_location	:any;
	origin_assistantid	:any;
	grade_up	:any;
	phone_hide	:any;
	is_test_user_str	:any;
	user_agent_simple	:any;
	seller_admin_nick	:any;
	assistant_nick	:any;
	origin_ass_nick	:any;
	ss_assign_time	:any;
	cache_nick	:any;
}

/*

tofile: 
	 mkdir -p ../user_manage; vi  ../user_manage/index.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage-index.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		grade:	$('#id_grade').val(),
		all_flag:	$('#id_all_flag').val(),
		test_user:	$('#id_test_user').val(),
		originid:	$('#id_originid').val(),
		user_name:	$('#id_user_name').val(),
		phone:	$('#id_phone').val(),
		assistantid:	$('#id_assistantid').val(),
		seller_adminid:	$('#id_seller_adminid').val(),
		order_type:	$('#id_order_type').val(),
		student_type:	$('#id_student_type').val(),
		userid:	$('#id_userid').val()
		});
}
$(function(){


	$('#id_grade').admin_set_select_field({
		"enum_type"    : "grade",
		"field_name" : "grade",
		"select_value" : g_args.grade,
		"onChange"     : load_data,
		"multi_select_flag"     : false ,
		"th_input_id"  : "th_grade",
		"only_show_in_th_input"     : false,
		"btn_id_config"     : {},
	});
	$('#id_all_flag').val(g_args.all_flag);
	$('#id_test_user').val(g_args.test_user);
	$('#id_originid').val(g_args.originid);
	$('#id_user_name').val(g_args.user_name);
	$('#id_phone').val(g_args.phone);
	$('#id_assistantid').admin_select_user_new({
		"user_type"    : "assistant",
		"select_value" : g_args.assistantid,
		"onChange"     : load_data,
		"th_input_id"  : "th_assistantid",
		"only_show_in_th_input"     : false,
		"can_select_all_flag"     : true
	});
	$('#id_seller_adminid').val(g_args.seller_adminid);
	$('#id_order_type').val(g_args.order_type);
	$('#id_student_type').val(g_args.student_type);
	$('#id_userid').admin_select_user_new({
		"user_type"    : "student",
		"select_value" : g_args.userid,
		"onChange"     : load_data,
		"th_input_id"  : "th_userid",
		"only_show_in_th_input"     : false,
		"can_select_all_flag"     : true
	});


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">年级</span>
                <select class="opt-change form-control" id="id_grade" >
                </select>
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">all_flag</span>
                <input class="opt-change form-control" id="id_all_flag" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">test_user</span>
                <input class="opt-change form-control" id="id_test_user" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">originid</span>
                <input class="opt-change form-control" id="id_originid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">user_name</span>
                <input class="opt-change form-control" id="id_user_name" />
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
                <span class="input-group-addon">assistantid</span>
                <input class="opt-change form-control" id="id_assistantid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">seller_adminid</span>
                <input class="opt-change form-control" id="id_seller_adminid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">order_type</span>
                <input class="opt-change form-control" id="id_order_type" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">student_type</span>
                <input class="opt-change form-control" id="id_student_type" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">userid</span>
                <input class="opt-change form-control" id="id_userid" />
            </div>
        </div>
*/
