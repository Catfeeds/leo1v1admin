interface GargsStatic {
	is_bank:	number;
	page_num:	number;
	page_count:	number;
	teacherid:	number;
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
	 mkdir -p ../teacher_money; vi  ../teacher_money/show_teacher_bank_info_human.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/teacher_money-show_teacher_bank_info_human.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		is_bank:	$('#id_is_bank').val(),
		teacherid:	$('#id_teacherid').val()
		});
}
$(function(){


	$('#id_is_bank').val(g_args.is_bank);
	$('#id_teacherid').admin_select_user_new({
		"user_type"    : "teacher",
		"select_value" : g_args.teacherid,
		"onChange"     : load_data,
		"th_input_id"  : "th_teacherid",
		"only_show_in_th_input"     : false,
		"can_select_all_flag"     : true
	});


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">is_bank</span>
                <input class="opt-change form-control" id="id_is_bank" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["is_bank title", "is_bank", "th_is_bank" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_num title", "page_num", "th_page_num" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_count title", "page_count", "th_page_count" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">teacherid</span>
                <input class="opt-change form-control" id="id_teacherid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["teacherid title", "teacherid", "th_teacherid" ]])!!}
*/
