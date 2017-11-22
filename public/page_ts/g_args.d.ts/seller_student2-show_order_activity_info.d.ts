interface GargsStatic {
	order_activity_type:	number;//\App\Enums\Eorder_activity_type
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
	 mkdir -p ../seller_student2; vi  ../seller_student2/show_order_activity_info.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student2-show_order_activity_info.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
		order_activity_type:	$('#id_order_activity_type').val()
    });
}
$(function(){

	Enum_map.append_option_list("order_activity_type",$("#id_order_activity_type"));

	$('#id_order_activity_type').val(g_args.order_activity_type);


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
*/
