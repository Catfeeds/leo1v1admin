interface GargsStatic {
	account:	number;
	flag:	number;
	start_date:	string;
	end_date:	string;
	login_info:	string;
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
	account	:any;
	all_count	:any;
	succ	:any;
	fail	:any;
}

/*

tofile: 
	 mkdir -p ../authority; vi  ../authority/get_login_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/authority-get_login_list.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		account:	$('#id_account').val(),
		flag:	$('#id_flag').val(),
		start_date:	$('#id_start_date').val(),
		end_date:	$('#id_end_date').val(),
		login_info:	$('#id_login_info').val()
		});
}
$(function(){


	$('#id_account').val(g_args.account);
	$('#id_flag').val(g_args.flag);
	$('#id_start_date').val(g_args.start_date);
	$('#id_end_date').val(g_args.end_date);
	$('#id_login_info').val(g_args.login_info);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">account</span>
                <input class="opt-change form-control" id="id_account" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["account title", "account", "th_account" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">flag</span>
                <input class="opt-change form-control" id="id_flag" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["flag title", "flag", "th_flag" ]])!!}

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
                <span class="input-group-addon">login_info</span>
                <input class="opt-change form-control" id="id_login_info" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["login_info title", "login_info", "th_login_info" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_num title", "page_num", "th_page_num" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_count title", "page_count", "th_page_count" ]])!!}
*/
