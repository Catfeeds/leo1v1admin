interface GargsStatic {
	admin_revisiterid:	number;
	page_num:	number;
	page_count:	number;
	global_tq_called_flag:	number;//枚举: App\Enums\Etq_called_flag
	seller_student_status:	number;//枚举: App\Enums\Eseller_student_status
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	add_time	:any;
	admin_revisiterid	:any;
	userid	:any;
	phone	:any;
	user_desc	:any;
	account	:any;
	admin_assign_time	:any;
	global_tq_called_flag	:any;
	seller_student_status	:any;
	seller_level	:any;
	student_nick	:any;
	global_tq_called_flag_str	:any;
	seller_level_str	:any;
	seller_student_status_str	:any;
}

/*

tofile: 
	 mkdir -p ../seller_student_new2; vi  ../seller_student_new2/seller_no_call_to_free_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student_new2-seller_no_call_to_free_list.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		admin_revisiterid:	$('#id_admin_revisiterid').val(),
		global_tq_called_flag:	$('#id_global_tq_called_flag').val(),
		seller_student_status:	$('#id_seller_student_status').val()
		});
}
$(function(){


	$('#id_admin_revisiterid').val(g_args.admin_revisiterid);
	$('#id_global_tq_called_flag').admin_set_select_field({
		"enum_type"    : "tq_called_flag",
		"field_name" : "global_tq_called_flag",
		"select_value" : g_args.global_tq_called_flag,
		"onChange"     : load_data,
		"multi_select_flag"     : false ,
		"th_input_id"  : "th_global_tq_called_flag",
		"only_show_in_th_input"     : false,
		"btn_id_config"     : {},
	});
	$('#id_seller_student_status').admin_set_select_field({
		"enum_type"    : "seller_student_status",
		"field_name" : "seller_student_status",
		"select_value" : g_args.seller_student_status,
		"onChange"     : load_data,
		"multi_select_flag"     : false ,
		"th_input_id"  : "th_seller_student_status",
		"only_show_in_th_input"     : false,
		"btn_id_config"     : {},
	});


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">admin_revisiterid</span>
                <input class="opt-change form-control" id="id_admin_revisiterid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["admin_revisiterid title", "admin_revisiterid", "th_admin_revisiterid" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_num title", "page_num", "th_page_num" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_count title", "page_count", "th_page_count" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">TQ</span>
                <select class="opt-change form-control" id="id_global_tq_called_flag" >
                </select>
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["global_tq_called_flag title", "global_tq_called_flag", "th_global_tq_called_flag" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">seller_student_status</span>
                <select class="opt-change form-control" id="id_seller_student_status" >
                </select>
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["seller_student_status title", "seller_student_status", "th_seller_student_status" ]])!!}
*/
