interface GargsStatic {
	grade:	number;//App\Enums\Egrade
	all_flag:	number;
	test_user:	number;
	originid:	number;
	user_name:	string;
	phone:	string;
	assistantid:	number;
	seller_adminid:	number;
	order_type:	number;
	page_num:	number;
	page_count:	number;
	userid:	number;
	seller_groupid_ex:	string;
	date_type_config:	string;
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
	user_agent_simple	:any;
	seller_admin_nick	:any;
	assistant_nick	:any;
	ss_assign_time	:any;
	cache_nick	:any;
}

/*

tofile: 
	 mkdir -p ../user_manage_new; vi  ../user_manage_new/get_two_weeks_old_stu.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-get_two_weeks_old_stu.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			grade:	$('#id_grade').val(),
			all_flag:	$('#id_all_flag').val(),
			test_user:	$('#id_test_user').val(),
			originid:	$('#id_originid').val(),
			user_name:	$('#id_user_name').val(),
			phone:	$('#id_phone').val(),
			assistantid:	$('#id_assistantid').val(),
			seller_adminid:	$('#id_seller_adminid').val(),
			order_type:	$('#id_order_type').val(),
			userid:	$('#id_userid').val(),
			seller_groupid_ex:	$('#id_seller_groupid_ex').val(),
			date_type_config:	$('#id_date_type_config').val(),
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val()
        });
    }

	Enum_map.append_option_list("grade",$("#id_grade"));

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
	$('#id_grade').val(g_args.grade);
	$('#id_all_flag').val(g_args.all_flag);
	$('#id_test_user').val(g_args.test_user);
	$('#id_originid').val(g_args.originid);
	$('#id_user_name').val(g_args.user_name);
	$('#id_phone').val(g_args.phone);
	$('#id_assistantid').val(g_args.assistantid);
	$('#id_seller_adminid').val(g_args.seller_adminid);
	$('#id_order_type').val(g_args.order_type);
	$('#id_userid').val(g_args.userid);
	$('#id_seller_groupid_ex').val(g_args.seller_groupid_ex);


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
                <span class="input-group-addon">userid</span>
                <input class="opt-change form-control" id="id_userid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">seller_groupid_ex</span>
                <input class="opt-change form-control" id="id_seller_groupid_ex" />
            </div>
        </div>
*/
