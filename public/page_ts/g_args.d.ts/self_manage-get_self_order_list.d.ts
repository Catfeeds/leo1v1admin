interface GargsStatic {
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	userid	:any;
	nick	:any;
	orderid	:any;
}

/*

tofile: 
	 mkdir -p ../self_manage; vi  ../self_manage/get_self_order_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/self_manage-get_self_order_list.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {

    });
}
$(function(){




	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...
*/
