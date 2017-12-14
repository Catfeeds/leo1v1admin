interface GargsStatic {
	date:	string;
	st_application_nick:	string;
	require_adminid:	number;
	test_seller_id:	number;
	seller_flag:	number;
	userid:	number;
	teacherid:	number;
	run_flag:	number;
	assistantid:	number;
	group_type:	number;
	self_groupid:	number;
	is_group_leader_flag:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	lessonid	:any;
	require_adminid	:any;
	account	:any;
	userid	:any;
	teacherid	:any;
	assistantid	:any;
	lesson_start	:any;
	lesson_end	:any;
	courseid	:any;
	lesson_type	:any;
	lesson_num	:any;
	current_server	:any;
	server_type	:any;
	xmpp_server_name	:any;
	index	:any;
	region	:any;
	ip	:any;
	port	:any;
	room_id	:any;
	lesson_time	:any;
	lesson_type_str	:any;
	assistant_nick	:any;
	teacher_nick	:any;
	student_nick	:any;
	server_type_str	:any;
}

/*

tofile: 
	 mkdir -p ../supervisor; vi  ../supervisor/monitor.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/supervisor-monitor.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		date:	$('#id_date').val(),
		st_application_nick:	$('#id_st_application_nick').val(),
		require_adminid:	$('#id_require_adminid').val(),
		test_seller_id:	$('#id_test_seller_id').val(),
		seller_flag:	$('#id_seller_flag').val(),
		userid:	$('#id_userid').val(),
		teacherid:	$('#id_teacherid').val(),
		run_flag:	$('#id_run_flag').val(),
		assistantid:	$('#id_assistantid').val(),
		group_type:	$('#id_group_type').val(),
		self_groupid:	$('#id_self_groupid').val(),
		is_group_leader_flag:	$('#id_is_group_leader_flag').val()
		});
}
$(function(){


	$('#id_date').val(g_args.date);
	$('#id_st_application_nick').val(g_args.st_application_nick);
	$('#id_require_adminid').val(g_args.require_adminid);
	$('#id_test_seller_id').val(g_args.test_seller_id);
	$('#id_seller_flag').val(g_args.seller_flag);
	$('#id_userid').admin_select_user_new({
		"user_type"    : "student",
		"select_value" : g_args.userid,
		"onChange"     : load_data,
		"th_input_id"  : "th_userid",
		"only_show_in_th_input"     : false,
		"can_select_all_flag"     : true
	});
	$('#id_teacherid').admin_select_user_new({
		"user_type"    : "teacher",
		"select_value" : g_args.teacherid,
		"onChange"     : load_data,
		"th_input_id"  : "th_teacherid",
		"only_show_in_th_input"     : false,
		"can_select_all_flag"     : true
	});
	$('#id_run_flag').val(g_args.run_flag);
	$('#id_assistantid').admin_select_user_new({
		"user_type"    : "assistant",
		"select_value" : g_args.assistantid,
		"onChange"     : load_data,
		"th_input_id"  : "th_assistantid",
		"only_show_in_th_input"     : false,
		"can_select_all_flag"     : true
	});
	$('#id_group_type').val(g_args.group_type);
	$('#id_self_groupid').val(g_args.self_groupid);
	$('#id_is_group_leader_flag').val(g_args.is_group_leader_flag);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">date</span>
                <input class="opt-change form-control" id="id_date" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["date title", "date", "th_date" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">st_application_nick</span>
                <input class="opt-change form-control" id="id_st_application_nick" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["st_application_nick title", "st_application_nick", "th_st_application_nick" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">require_adminid</span>
                <input class="opt-change form-control" id="id_require_adminid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["require_adminid title", "require_adminid", "th_require_adminid" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">test_seller_id</span>
                <input class="opt-change form-control" id="id_test_seller_id" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["test_seller_id title", "test_seller_id", "th_test_seller_id" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">seller_flag</span>
                <input class="opt-change form-control" id="id_seller_flag" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["seller_flag title", "seller_flag", "th_seller_flag" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">userid</span>
                <input class="opt-change form-control" id="id_userid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["userid title", "userid", "th_userid" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">teacherid</span>
                <input class="opt-change form-control" id="id_teacherid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["teacherid title", "teacherid", "th_teacherid" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">run_flag</span>
                <input class="opt-change form-control" id="id_run_flag" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["run_flag title", "run_flag", "th_run_flag" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">assistantid</span>
                <input class="opt-change form-control" id="id_assistantid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["assistantid title", "assistantid", "th_assistantid" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">group_type</span>
                <input class="opt-change form-control" id="id_group_type" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["group_type title", "group_type", "th_group_type" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">self_groupid</span>
                <input class="opt-change form-control" id="id_self_groupid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["self_groupid title", "self_groupid", "th_self_groupid" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">is_group_leader_flag</span>
                <input class="opt-change form-control" id="id_is_group_leader_flag" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["is_group_leader_flag title", "is_group_leader_flag", "th_is_group_leader_flag" ]])!!}
*/
