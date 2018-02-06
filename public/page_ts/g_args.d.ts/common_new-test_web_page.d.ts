interface GargsStatic {
	web_page_id:	number;
	from_adminid:	number;
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
	 mkdir -p ../common_new; vi  ../common_new/test_web_page.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/common_new-test_web_page.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		web_page_id:	$('#id_web_page_id').val(),
		from_adminid:	$('#id_from_adminid').val()
		});
}
$(function(){


	$('#id_web_page_id').val(g_args.web_page_id);
	$('#id_from_adminid').val(g_args.from_adminid);


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

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">from_adminid</span>
                <input class="opt-change form-control" id="id_from_adminid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["from_adminid title", "from_adminid", "th_from_adminid" ]])!!}
*/
