interface GargsStatic {
	page_num:	number;
	page_count:	number;
	order_by_str:	string;
	machine_id:	number;
	adminid:	number;
	auth_flag:	number;//枚举: \App\Enums\Eboolean
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	title	:any;
	machine_id	:any;
	adminid	:any;
	auth_flag	:any;
	open_door_flag	:any;
	sn	:any;
	del_flag	:any;
	auth_flag_str	:any;
	admin_nick	:any;
}

/*

tofile: 
	 mkdir -p ../admin_manage; vi  ../admin_manage/kaoqin_machine_adminid.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/admin_manage-kaoqin_machine_adminid.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		order_by_str:	$('#id_order_by_str').val(),
		machine_id:	$('#id_machine_id').val(),
		adminid:	$('#id_adminid').val(),
		auth_flag:	$('#id_auth_flag').val()
		});
}
$(function(){


	$('#id_order_by_str').val(g_args.order_by_str);
	$('#id_machine_id').val(g_args.machine_id);
	$('#id_adminid').admin_select_user_new({
		"user_type"    : "account",
		"select_value" : g_args.adminid,
		"onChange"     : load_data,
		"th_input_id"  : "th_adminid",
		"only_show_in_th_input"     : false,
		"can_select_all_flag"     : true
	});
	$('#id_auth_flag').admin_set_select_field({
		"enum_type"    : "boolean",
		"field_name" : "auth_flag",
		"select_value" : g_args.auth_flag,
		"onChange"     : load_data,
		"multi_select_flag"     : false ,
		"th_input_id"  : "th_auth_flag",
		"only_show_in_th_input"     : false,
		"btn_id_config"     : {},
	});


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...
{!!\App\Helper\Utils::th_order_gen([["page_num title", "page_num", "th_page_num" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_count title", "page_count", "th_page_count" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">order_by_str</span>
                <input class="opt-change form-control" id="id_order_by_str" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["order_by_str title", "order_by_str", "th_order_by_str" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">machine_id</span>
                <input class="opt-change form-control" id="id_machine_id" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["machine_id title", "machine_id", "th_machine_id" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">adminid</span>
                <input class="opt-change form-control" id="id_adminid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["adminid title", "adminid", "th_adminid" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">boolean</span>
                <select class="opt-change form-control" id="id_auth_flag" >
                </select>
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["auth_flag title", "auth_flag", "th_auth_flag" ]])!!}
*/
