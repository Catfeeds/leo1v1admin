interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	principal:	number;
	groupid:	number;
	create:	number;
	allocation:	number;
	type:	number;
	search:	string;
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
	phone	:any;
	nick	:any;
	origin_nick	:any;
	reg_time	:any;
	origin_assistantid	:any;
	admin_revisiterid	:any;
	admin_assignerid	:any;
	admin_revisiter_nick	:any;
	admin_revisiter_role	:any;
	sd_nick	:any;
	create_nick	:any;
	create_role	:any;
	admin_assigner_nick	:any;
	userid	:any;
	origin_role	:any;
	create_role_str	:any;
	admin_revisiter_role_str	:any;
	allocation_type	:any;
	allocation_type_str	:any;
}

/*

tofile: 
	 mkdir -p ../seller_student_new; vi  ../seller_student_new/referral_statistics.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student_new-referral_statistics.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		date_type_config:	$('#id_date_type_config').val(),
		date_type:	$('#id_date_type').val(),
		opt_date_type:	$('#id_opt_date_type').val(),
		start_time:	$('#id_start_time').val(),
		end_time:	$('#id_end_time').val(),
		principal:	$('#id_principal').val(),
		groupid:	$('#id_groupid').val(),
		create:	$('#id_create').val(),
		allocation:	$('#id_allocation').val(),
		type:	$('#id_type').val(),
		search:	$('#id_search').val()
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
	$('#id_principal').val(g_args.principal);
	$('#id_groupid').val(g_args.groupid);
	$('#id_create').val(g_args.create);
	$('#id_allocation').val(g_args.allocation);
	$('#id_type').val(g_args.type);
	$('#id_search').val(g_args.search);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...
{!!\App\Helper\Utils::th_order_gen([["date_type_config title", "date_type_config", "th_date_type_config" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["date_type title", "date_type", "th_date_type" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["opt_date_type title", "opt_date_type", "th_opt_date_type" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["start_time title", "start_time", "th_start_time" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["end_time title", "end_time", "th_end_time" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">principal</span>
                <input class="opt-change form-control" id="id_principal" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["principal title", "principal", "th_principal" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">groupid</span>
                <input class="opt-change form-control" id="id_groupid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["groupid title", "groupid", "th_groupid" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">create</span>
                <input class="opt-change form-control" id="id_create" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["create title", "create", "th_create" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">allocation</span>
                <input class="opt-change form-control" id="id_allocation" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["allocation title", "allocation", "th_allocation" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">type</span>
                <input class="opt-change form-control" id="id_type" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["type title", "type", "th_type" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">search</span>
                <input class="opt-change form-control" id="id_search" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["search title", "search", "th_search" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_num title", "page_num", "th_page_num" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_count title", "page_count", "th_page_count" ]])!!}
*/
