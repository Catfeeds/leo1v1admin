interface GargsStatic {
	url:	string;
	orderid:	number;
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
	 mkdir -p ../common_new; vi  ../common_new/redirect_for_pdf.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/common_new-redirect_for_pdf.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		url:	$('#id_url').val(),
		orderid:	$('#id_orderid').val()
		});
}
$(function(){


	$('#id_url').val(g_args.url);
	$('#id_orderid').val(g_args.orderid);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">url</span>
                <input class="opt-change form-control" id="id_url" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["url title", "url", "th_url" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">orderid</span>
                <input class="opt-change form-control" id="id_orderid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["orderid title", "orderid", "th_orderid" ]])!!}
*/
