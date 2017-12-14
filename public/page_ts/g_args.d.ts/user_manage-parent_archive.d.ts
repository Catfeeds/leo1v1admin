interface GargsStatic {
	parentid:	number;
	gender:	number;
	nick:	string;
	phone:	string;
	last_modified_time:	string;
	assistantid:	number;
	page_num:	number;
	page_count:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	parentid	:any;
	nick	:any;
	phone	:any;
	gender	:any;
	face	:any;
	last_modified_time	:any;
	has_login	:any;
	email	:any;
	wx_openid	:any;
	time	:any;
	phone_hide	:any;
}

/*

tofile: 
	 mkdir -p ../user_manage; vi  ../user_manage/parent_archive.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage-parent_archive.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		parentid:	$('#id_parentid').val(),
		gender:	$('#id_gender').val(),
		nick:	$('#id_nick').val(),
		phone:	$('#id_phone').val(),
		last_modified_time:	$('#id_last_modified_time').val(),
		assistantid:	$('#id_assistantid').val()
		});
}
$(function(){


	$('#id_parentid').val(g_args.parentid);
	$('#id_gender').val(g_args.gender);
	$('#id_nick').val(g_args.nick);
	$('#id_phone').val(g_args.phone);
	$('#id_last_modified_time').val(g_args.last_modified_time);
	$('#id_assistantid').admin_select_user_new({
		"user_type"    : "assistant",
		"select_value" : g_args.assistantid,
		"onChange"     : load_data,
		"th_input_id"  : "th_assistantid",
		"only_show_in_th_input"     : false,
		"can_select_all_flag"     : true
	});


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">parentid</span>
                <input class="opt-change form-control" id="id_parentid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["parentid title", "parentid", "th_parentid" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">gender</span>
                <input class="opt-change form-control" id="id_gender" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["gender title", "gender", "th_gender" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">nick</span>
                <input class="opt-change form-control" id="id_nick" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["nick title", "nick", "th_nick" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">phone</span>
                <input class="opt-change form-control" id="id_phone" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["phone title", "phone", "th_phone" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">last_modified_time</span>
                <input class="opt-change form-control" id="id_last_modified_time" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["last_modified_time title", "last_modified_time", "th_last_modified_time" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">assistantid</span>
                <input class="opt-change form-control" id="id_assistantid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["assistantid title", "assistantid", "th_assistantid" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_num title", "page_num", "th_page_num" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_count title", "page_count", "th_page_count" ]])!!}
*/
