interface GargsStatic {
	web_page_id:	number;
	page_num:	number;
	page_count:	number;
	uid:	number;
	account_role:	number;//枚举: \App\Enums\Eaccount_role
	web_page_title:	number;
	web_page_url:	number;
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
	 mkdir -p ../admin_manage; vi  ../admin_manage/web_page_share.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/admin_manage-web_page_share.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		web_page_id:	$('#id_web_page_id').val(),
		uid:	$('#id_uid').val(),
		account_role:	$('#id_account_role').val(),
		web_page_title:	$('#id_web_page_title').val(),
		web_page_url:	$('#id_web_page_url').val()
		});
}
$(function(){


	$('#id_web_page_id').val(g_args.web_page_id);
	$('#id_uid').val(g_args.uid);
	$('#id_account_role').admin_set_select_field({
		"enum_type"    : "account_role",
		"field_name" : "account_role",
		"select_value" : g_args.account_role,
		"onChange"     : load_data,
		"multi_select_flag"     : false ,
		"th_input_id"  : "th_account_role",
		"only_show_in_th_input"     : false,
		"btn_id_config"     : {},
	});
	$('#id_web_page_title').val(g_args.web_page_title);
	$('#id_web_page_url').val(g_args.web_page_url);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">web_page_id</span>
                <input class="opt-change form-control" id="id_web_page_id" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["web_page_id title", "web_page_id", "th_web_page_id" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_num title", "page_num", "th_page_num" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_count title", "page_count", "th_page_count" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">uid</span>
                <input class="opt-change form-control" id="id_uid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["uid title", "uid", "th_uid" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">account_role</span>
                <select class="opt-change form-control" id="id_account_role" >
                </select>
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["account_role title", "account_role", "th_account_role" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">web_page_title</span>
                <input class="opt-change form-control" id="id_web_page_title" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["web_page_title title", "web_page_title", "th_web_page_title" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">web_page_url</span>
                <input class="opt-change form-control" id="id_web_page_url" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["web_page_url title", "web_page_url", "th_web_page_url" ]])!!}
*/
