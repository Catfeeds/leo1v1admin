interface GargsStatic {
	start_date:	string;
	end_date:	string;
	test_user:	number;
	originid:	number;
	grade:	number;//枚举: App\Enums\Egrade
	user_name:	string;
	phone:	string;
	teacherid:	number;
	assistantid:	number;
	revisit_assistantid:	number;
	page_num:	number;
	page_count:	number;
	userid:	number;
	revisit_type:	number;
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
	revisit_type	:any;
	assistantid	:any;
	revisit_time	:any;
	operator_note	:any;
	sys_operator	:any;
	nick	:any;
	phone	:any;
	originid	:any;
	grade	:any;
	duration	:any;
	originid_str	:any;
	assistant_nick	:any;
	revisit_type_str	:any;
	grade_str	:any;
}

/*

tofile: 
	 mkdir -p ../user_manage; vi  ../user_manage/ass_counts.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage-ass_counts.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		start_date:	$('#id_start_date').val(),
		end_date:	$('#id_end_date').val(),
		test_user:	$('#id_test_user').val(),
		originid:	$('#id_originid').val(),
		grade:	$('#id_grade').val(),
		user_name:	$('#id_user_name').val(),
		phone:	$('#id_phone').val(),
		teacherid:	$('#id_teacherid').val(),
		assistantid:	$('#id_assistantid').val(),
		revisit_assistantid:	$('#id_revisit_assistantid').val(),
		userid:	$('#id_userid').val(),
		revisit_type:	$('#id_revisit_type').val()
		});
}
$(function(){


	$('#id_start_date').val(g_args.start_date);
	$('#id_end_date').val(g_args.end_date);
	$('#id_test_user').val(g_args.test_user);
	$('#id_originid').val(g_args.originid);
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
	$('#id_user_name').val(g_args.user_name);
	$('#id_phone').val(g_args.phone);
	$('#id_teacherid').admin_select_user_new({
		"user_type"    : "teacher",
		"select_value" : g_args.teacherid,
		"onChange"     : load_data,
		"th_input_id"  : "th_teacherid",
		"only_show_in_th_input"     : false,
		"can_select_all_flag"     : true
	});
	$('#id_assistantid').admin_select_user_new({
		"user_type"    : "assistant",
		"select_value" : g_args.assistantid,
		"onChange"     : load_data,
		"th_input_id"  : "th_assistantid",
		"only_show_in_th_input"     : false,
		"can_select_all_flag"     : true
	});
	$('#id_revisit_assistantid').val(g_args.revisit_assistantid);
	$('#id_userid').admin_select_user_new({
		"user_type"    : "student",
		"select_value" : g_args.userid,
		"onChange"     : load_data,
		"th_input_id"  : "th_userid",
		"only_show_in_th_input"     : false,
		"can_select_all_flag"     : true
	});
	$('#id_revisit_type').val(g_args.revisit_type);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">start_date</span>
                <input class="opt-change form-control" id="id_start_date" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["start_date title", "start_date", "th_start_date" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">end_date</span>
                <input class="opt-change form-control" id="id_end_date" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["end_date title", "end_date", "th_end_date" ]])!!}

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
                <span class="input-group-addon">年级</span>
                <select class="opt-change form-control" id="id_grade" >
                </select>
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["grade title", "grade", "th_grade" ]])!!}

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
                <span class="input-group-addon">teacherid</span>
                <input class="opt-change form-control" id="id_teacherid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["teacherid title", "teacherid", "th_teacherid" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">assistantid</span>
                <input class="opt-change form-control" id="id_assistantid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["assistantid title", "assistantid", "th_assistantid" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">revisit_assistantid</span>
                <input class="opt-change form-control" id="id_revisit_assistantid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["revisit_assistantid title", "revisit_assistantid", "th_revisit_assistantid" ]])!!}
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
                <span class="input-group-addon">revisit_type</span>
                <input class="opt-change form-control" id="id_revisit_type" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["revisit_type title", "revisit_type", "th_revisit_type" ]])!!}
*/
