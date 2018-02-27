interface GargsStatic {
	address:	string;
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
	teacherid	:any;
	realname	:any;
	limit_week_lesson_num	:any;
	is_freeze	:any;
	freeze_reason	:any;
	freeze_adminid	:any;
	freeze_time	:any;
	limit_plan_lesson_type	:any;
	limit_plan_lesson_reason	:any;
	limit_plan_lesson_time	:any;
	lesson_hold_flag	:any;
	lesson_hold_flag_acc	:any;
	lesson_hold_flag_time	:any;
	limit_plan_lesson_account	:any;
	limit_plan_lesson_type_str	:any;
	freeze_time_str	:any;
	lesson_hold_flag_time_str	:any;
	limit_plan_lesson_time_str	:any;
	is_freeze_str	:any;
}

/*

tofile: 
	 mkdir -p ../human_resource; vi  ../human_resource/teacher_info_for_seller.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/human_resource-teacher_info_for_seller.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		address:	$('#id_address').val()
		});
}
$(function(){


	$('#id_address').val(g_args.address);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">address</span>
                <input class="opt-change form-control" id="id_address" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["address title", "address", "th_address" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_num title", "page_num", "th_page_num" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_count title", "page_count", "th_page_count" ]])!!}
*/
