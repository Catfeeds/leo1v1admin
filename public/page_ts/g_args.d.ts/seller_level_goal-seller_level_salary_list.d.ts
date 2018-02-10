interface GargsStatic {
	seller_level:	number;
	define_date:	number;
	base_salary:	number;
	sup_salary:	number;
	per_salary:	number;
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
	seller_level	:any;
	define_date	:any;
	base_salary	:any;
	sup_salary	:any;
	per_salary	:any;
	create_time	:any;
	seller_level_str	:any;
}

/*

tofile: 
	 mkdir -p ../seller_level_goal; vi  ../seller_level_goal/seller_level_salary_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_level_goal-seller_level_salary_list.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		seller_level:	$('#id_seller_level').val(),
		define_date:	$('#id_define_date').val(),
		base_salary:	$('#id_base_salary').val(),
		sup_salary:	$('#id_sup_salary').val(),
		per_salary:	$('#id_per_salary').val()
		});
}
$(function(){


	$('#id_seller_level').val(g_args.seller_level);
	$('#id_define_date').val(g_args.define_date);
	$('#id_base_salary').val(g_args.base_salary);
	$('#id_sup_salary').val(g_args.sup_salary);
	$('#id_per_salary').val(g_args.per_salary);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">seller_level</span>
                <input class="opt-change form-control" id="id_seller_level" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["seller_level title", "seller_level", "th_seller_level" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">define_date</span>
                <input class="opt-change form-control" id="id_define_date" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["define_date title", "define_date", "th_define_date" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">base_salary</span>
                <input class="opt-change form-control" id="id_base_salary" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["base_salary title", "base_salary", "th_base_salary" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">sup_salary</span>
                <input class="opt-change form-control" id="id_sup_salary" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["sup_salary title", "sup_salary", "th_sup_salary" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">per_salary</span>
                <input class="opt-change form-control" id="id_per_salary" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["per_salary title", "per_salary", "th_per_salary" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_num title", "page_num", "th_page_num" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_count title", "page_count", "th_page_count" ]])!!}
*/
