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
}

/*

tofile: 
	 mkdir -p ../user_manage_new; vi  ../user_manage_new/get_two_weeks_old_stu_seller.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-get_two_weeks_old_stu_seller.d.ts" />

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
		userid:	$('#id_userid').val(),
		seller_groupid_ex:	$('#id_seller_groupid_ex').val(),
		date_type_config:	$('#id_date_type_config').val(),
		date_type:	$('#id_date_type').val(),
		opt_date_type:	$('#id_opt_date_type').val(),
		start_time:	$('#id_start_time').val(),
		end_time:	$('#id_end_time').val()
		});
}
$(function(){


	$('#id_date_range').select_date_range({
		'date_type' : g_args.date_type,
		'opt_date_type' : g_args.opt_date_type,
		'start_time'    : g_args.start_time,
		'end_time'      : g_args.end_time,
		date_type_config : JSON.parse( g_args.date_type_config),
		onQuery :function() {
			load_data();
		});
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
{!!\App\Helper\Utils::th_order_gen([["grade title", "grade", "th_grade" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">all_flag</span>
                <input class="opt-change form-control" id="id_all_flag" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["all_flag title", "all_flag", "th_all_flag" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">test_user</span>
                <input class="opt-change form-control" id="id_test_user" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["test_user title", "test_user", "th_test_user" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">originid</span>
                <input class="opt-change form-control" id="id_originid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["originid title", "originid", "th_originid" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">user_name</span>
                <input class="opt-change form-control" id="id_user_name" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["user_name title", "user_name", "th_user_name" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">phone</span>
                <input class="opt-change form-control" id="id_phone" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["phone title", "phone", "th_phone" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">assistantid</span>
                <input class="opt-change form-control" id="id_assistantid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["assistantid title", "assistantid", "th_assistantid" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">seller_adminid</span>
                <input class="opt-change form-control" id="id_seller_adminid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["seller_adminid title", "seller_adminid", "th_seller_adminid" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">order_type</span>
                <input class="opt-change form-control" id="id_order_type" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["order_type title", "order_type", "th_order_type" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">student_type</span>
                <input class="opt-change form-control" id="id_student_type" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["student_type title", "student_type", "th_student_type" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_num title", "page_num", "th_page_num" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_count title", "page_count", "th_page_count" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">userid</span>
                <input class="opt-change form-control" id="id_userid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["userid title", "userid", "th_userid" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">seller_groupid_ex</span>
                <input class="opt-change form-control" id="id_seller_groupid_ex" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["seller_groupid_ex title", "seller_groupid_ex", "th_seller_groupid_ex" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["date_type_config title", "date_type_config", "th_date_type_config" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["date_type title", "date_type", "th_date_type" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["opt_date_type title", "opt_date_type", "th_opt_date_type" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["start_time title", "start_time", "th_start_time" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["end_time title", "end_time", "th_end_time" ]])!!}
*/
