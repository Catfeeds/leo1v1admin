interface GargsStatic {
	order_activity_type:	number;//枚举: \App\Enums\Eorder_activity_type
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
	 mkdir -p ../seller_student_new2; vi  ../seller_student_new2/show_order_activity_info.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student_new2-show_order_activity_info.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		order_activity_type:	$('#id_order_activity_type').val()
		});
}
$(function(){


	$('#id_order_activity_type').admin_set_select_field({
		"enum_type"    : "order_activity_type",
		"field_name" : "order_activity_type",
		"select_value" : g_args.order_activity_type,
		"onChange"     : load_data,
		"multi_select_flag"     : false ,
		"th_input_id"  : "th_order_activity_type",
		"only_show_in_th_input"     : false,
		"btn_id_config"     : {},
	});


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">order_activity_type</span>
                <select class="opt-change form-control" id="id_order_activity_type" >
                </select>
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["order_activity_type title", "order_activity_type", "th_order_activity_type" ]])!!}
*/
