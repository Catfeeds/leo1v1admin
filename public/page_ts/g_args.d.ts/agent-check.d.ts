interface GargsStatic {
	origin_ex:	string;
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
	textbook_str	:any;
}

/*

tofile: 
	 mkdir -p ../agent; vi  ../agent/check.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/agent-check.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		origin_ex:	$('#id_origin_ex').val()
		});
}
$(function(){


	$('#id_origin_ex').val(g_args.origin_ex);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">origin_ex</span>
                <input class="opt-change form-control" id="id_origin_ex" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["origin_ex title", "origin_ex", "th_origin_ex" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_num title", "page_num", "th_page_num" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_count title", "page_count", "th_page_count" ]])!!}
*/
