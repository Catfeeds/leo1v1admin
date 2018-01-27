interface GargsStatic {
	del_flag:	number;//枚举: \App\Enums\Eboolean
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	adminid	:any;
	count	:any;
	del_flag	:any;
	account	:any;
	del_flag_str	:any;
}

/*

tofile: 
	 mkdir -p ../tongji2; vi  ../tongji2/seller_student_admin_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji2-seller_student_admin_list.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		del_flag:	$('#id_del_flag').val()
		});
}
$(function(){


	$('#id_del_flag').admin_set_select_field({
		"enum_type"    : "boolean",
		"field_name" : "del_flag",
		"select_value" : g_args.del_flag,
		"onChange"     : load_data,
		"multi_select_flag"     : false ,
		"th_input_id"  : "th_del_flag",
		"only_show_in_th_input"     : false,
		"btn_id_config"     : {},
	});


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">boolean</span>
                <select class="opt-change form-control" id="id_del_flag" >
                </select>
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["del_flag title", "del_flag", "th_del_flag" ]])!!}
*/
