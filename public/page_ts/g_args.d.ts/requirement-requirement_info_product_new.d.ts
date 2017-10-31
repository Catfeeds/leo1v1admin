interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	priority:	number;
	id_productid:	number;
	product_status:	number;
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
	id	:any;
	name	:any;
	create_adminid	:any;
	create_phone	:any;
	create_time	:any;
	expect_time	:any;
	priority	:any;
	significance	:any;
	notes	:any;
	statement	:any;
	content_pic	:any;
	product_solution	:any;
	product_operator	:any;
	product_phone	:any;
	product_add_time	:any;
	product_submit_time	:any;
	product_reject	:any;
	product_reject_time	:any;
	development_operator	:any;
	development_phone	:any;
	development_add_time	:any;
	development_submit_time	:any;
	development_reject	:any;
	development_reject_time	:any;
	test_operator	:any;
	test_phone	:any;
	test_add_time	:any;
	test_submit_time	:any;
	test_reject	:any;
	test_reject_time	:any;
	product_status	:any;
	development_status	:any;
	test_status	:any;
	status	:any;
	del_flag	:any;
	forecast_time	:any;
	product_comment	:any;
	product_name	:any;
	expect_time_a	:any;
	forecast_time_a	:any;
	expect_time_b	:any;
	forecast_time_b	:any;
	product_operator_str	:any;
	name_str	:any;
	priority_str	:any;
	significance_str	:any;
	status_str	:any;
	create_admin_nick	:any;
	flag	:any;
	operator_status	:any;
	operator_nick	:any;
}

/*

tofile: 
	 mkdir -p ../requirement; vi  ../requirement/requirement_info_product_new.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/requirement-requirement_info_product_new.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
		date_type_config:	$('#id_date_type_config').val(),
		date_type:	$('#id_date_type').val(),
		opt_date_type:	$('#id_opt_date_type').val(),
		start_time:	$('#id_start_time').val(),
		end_time:	$('#id_end_time').val(),
		priority:	$('#id_priority').val(),
		id_productid:	$('#id_id_productid').val(),
		product_status:	$('#id_product_status').val()
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
        }
    });
	$('#id_priority').val(g_args.priority);
	$('#id_id_productid').val(g_args.id_productid);
	$('#id_product_status').val(g_args.product_status);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">priority</span>
                <input class="opt-change form-control" id="id_priority" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">id_productid</span>
                <input class="opt-change form-control" id="id_id_productid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">product_status</span>
                <input class="opt-change form-control" id="id_product_status" />
            </div>
        </div>
*/
