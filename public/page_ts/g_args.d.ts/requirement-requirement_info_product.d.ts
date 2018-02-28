interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	name:	number;
	priority:	number;
	significance:	number;
	status:	number;
	product_status:	number;
	development_status:	number;
	test_status:	number;
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
}

/*

tofile: 
	 mkdir -p ../requirement; vi  ../requirement/requirement_info_product.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/requirement-requirement_info_product.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		date_type_config:	$('#id_date_type_config').val(),
		date_type:	$('#id_date_type').val(),
		opt_date_type:	$('#id_opt_date_type').val(),
		start_time:	$('#id_start_time').val(),
		end_time:	$('#id_end_time').val(),
		name:	$('#id_name').val(),
		priority:	$('#id_priority').val(),
		significance:	$('#id_significance').val(),
		status:	$('#id_status').val(),
		product_status:	$('#id_product_status').val(),
		development_status:	$('#id_development_status').val(),
		test_status:	$('#id_test_status').val()
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
	$('#id_name').val(g_args.name);
	$('#id_priority').val(g_args.priority);
	$('#id_significance').val(g_args.significance);
	$('#id_status').val(g_args.status);
	$('#id_product_status').val(g_args.product_status);
	$('#id_development_status').val(g_args.development_status);
	$('#id_test_status').val(g_args.test_status);


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
                <span class="input-group-addon">name</span>
                <input class="opt-change form-control" id="id_name" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["name title", "name", "th_name" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">priority</span>
                <input class="opt-change form-control" id="id_priority" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["priority title", "priority", "th_priority" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">significance</span>
                <input class="opt-change form-control" id="id_significance" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["significance title", "significance", "th_significance" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">status</span>
                <input class="opt-change form-control" id="id_status" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["status title", "status", "th_status" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">product_status</span>
                <input class="opt-change form-control" id="id_product_status" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["product_status title", "product_status", "th_product_status" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">development_status</span>
                <input class="opt-change form-control" id="id_development_status" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["development_status title", "development_status", "th_development_status" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">test_status</span>
                <input class="opt-change form-control" id="id_test_status" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["test_status title", "test_status", "th_test_status" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_num title", "page_num", "th_page_num" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_count title", "page_count", "th_page_count" ]])!!}
*/
