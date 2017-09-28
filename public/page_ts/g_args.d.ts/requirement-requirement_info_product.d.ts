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
	num	:any;
	name_str	:any;
	priority_str	:any;
	significance_str	:any;
	status_str	:any;
	create_admin_nick	:any;
	product_operator_nick	:any;
	flag	:any;
	operator_status	:any;
	operator_nick	:any;
}

/*

tofile: 
	 mkdir -p ../requirement; vi  ../requirement/requirement_info_product.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/requirement-requirement_info_product.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
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

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">name</span>
                <input class="opt-change form-control" id="id_name" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">priority</span>
                <input class="opt-change form-control" id="id_priority" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">significance</span>
                <input class="opt-change form-control" id="id_significance" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">status</span>
                <input class="opt-change form-control" id="id_status" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">product_status</span>
                <input class="opt-change form-control" id="id_product_status" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">development_status</span>
                <input class="opt-change form-control" id="id_development_status" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">test_status</span>
                <input class="opt-change form-control" id="id_test_status" />
            </div>
        </div>
*/
