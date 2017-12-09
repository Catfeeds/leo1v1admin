interface GargsStatic {
	page_num:	number;
	page_count:	number;
	order_by_str:	string;
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	record_audio_server1:	string;
	xmpp_server_name:	string;
	lesson_type:	string;//枚举列表: \App\Enums\Econtract_type
 	subject:	string;//枚举列表: \App\Enums\Esubject
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
}

/*

tofile: 
	 mkdir -p ../tea_manage_new; vi  ../tea_manage_new/lesson_record_server_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tea_manage_new-lesson_record_server_list.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		order_by_str:	$('#id_order_by_str').val(),
		date_type_config:	$('#id_date_type_config').val(),
		date_type:	$('#id_date_type').val(),
		opt_date_type:	$('#id_opt_date_type').val(),
		start_time:	$('#id_start_time').val(),
		end_time:	$('#id_end_time').val(),
		record_audio_server1:	$('#id_record_audio_server1').val(),
		xmpp_server_name:	$('#id_xmpp_server_name').val(),
		lesson_type:	$('#id_lesson_type').val(),
		subject:	$('#id_subject').val(),
		userid:	$('#id_userid').val()
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
	$('#id_order_by_str').val(g_args.order_by_str);
	$('#id_record_audio_server1').val(g_args.record_audio_server1);
	$('#id_xmpp_server_name').val(g_args.xmpp_server_name);
	$('#id_lesson_type').admin_set_select_field({
		"enum_type"    : "contract_type",
		"select_value" : g_args.lesson_type,
		"onChange"     : load_data,
		"th_input_id"  : "th_lesson_type",
		"btn_id_config"     : {}
	});
	$('#id_subject').admin_set_select_field({
		"enum_type"    : "subject",
		"select_value" : g_args.subject,
		"onChange"     : load_data,
		"th_input_id"  : "th_subject",
		"btn_id_config"     : {}
	});
	$('#id_userid').admin_select_user_new({
		"user_type"    : "student",
		"select_value" : g_args.userid,
		"onChange"     : load_data,
		"th_input_id"  : "th_userid",
		"can_sellect_all_flag"     : true
	});


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">order_by_str</span>
                <input class="opt-change form-control" id="id_order_by_str" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">record_audio_server1</span>
                <input class="opt-change form-control" id="id_record_audio_server1" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">xmpp_server_name</span>
                <input class="opt-change form-control" id="id_xmpp_server_name" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">lesson_type</span>
                <input class="opt-change form-control" id="id_lesson_type" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">subject</span>
                <input class="opt-change form-control" id="id_subject" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">userid</span>
                <input class="opt-change form-control" id="id_userid" />
            </div>
        </div>
*/
