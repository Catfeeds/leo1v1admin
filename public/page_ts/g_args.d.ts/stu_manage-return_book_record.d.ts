interface GargsStatic {
	sid:	number;
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
	revisit_time	:any;
	operator_note	:any;
	operator_audio	:any;
	sys_operator	:any;
}

/*

tofile: 
	 mkdir -p ../stu_manage; vi  ../stu_manage/return_book_record.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/stu_manage-return_book_record.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		sid:	$('#id_sid').val()
		});
}
$(function(){


	$('#id_sid').val(g_args.sid);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">sid</span>
                <input class="opt-change form-control" id="id_sid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["sid title", "sid", "th_sid" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_num title", "page_num", "th_page_num" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_count title", "page_count", "th_page_count" ]])!!}
*/
