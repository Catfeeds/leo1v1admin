interface GargsStatic {
	sid:	number;
	type:	number;
	competition_flag:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	orderid	:any;
	contract_type	:any;
	contract_status	:any;
	lesson_total	:any;
	default_lesson_count	:any;
	lesson_left	:any;
	grade	:any;
	subject	:any;
	price	:any;
	discount_price	:any;
	discount_reason	:any;
	sys_operator	:any;
	realname	:any;
	phone	:any;
	contractid	:any;
	order_left	:any;
	hand_over_view	:any;
	per_price	:any;
	contract_type_str	:any;
	contract_status_str	:any;
	grade_str	:any;
	subject_str	:any;
}

/*

tofile: 
	 mkdir -p ../stu_manage; vi  ../stu_manage/order_info_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/stu_manage-order_info_list.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		sid:	$('#id_sid').val(),
		type:	$('#id_type').val(),
		competition_flag:	$('#id_competition_flag').val()
		});
}
$(function(){


	$('#id_sid').val(g_args.sid);
	$('#id_type').val(g_args.type);
	$('#id_competition_flag').val(g_args.competition_flag);


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

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">type</span>
                <input class="opt-change form-control" id="id_type" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["type title", "type", "th_type" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">competition_flag</span>
                <input class="opt-change form-control" id="id_competition_flag" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["competition_flag title", "competition_flag", "th_competition_flag" ]])!!}
*/
