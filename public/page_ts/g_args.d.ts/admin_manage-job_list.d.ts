interface GargsStatic {
	page_num:	number;
	page_count:	number;
	query_text:	string;
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
	 mkdir -p ../admin_manage; vi  ../admin_manage/job_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/admin_manage-job_list.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		query_text:	$('#id_query_text').val()
		});
}
$(function(){


	$('#id_query_text').val(g_args.query_text);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...
{!!\App\Helper\Utils::th_order_gen([["page_num title", "page_num", "th_page_num" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_count title", "page_count", "th_page_count" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">query_text</span>
                <input class="opt-change form-control" id="id_query_text" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["query_text title", "query_text", "th_query_text" ]])!!}
*/
