interface GargsStatic {
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
	group_id	:any;
	group_name	:any;
	create_time	:any;
	phone	:any;
	nickname	:any;
	colconel_agent_id	:any;
	member_num	:any;
}

/*

tofile: 
	 mkdir -p ../agent_info; vi  ../agent_info/get_agent_group_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/agent_info-get_agent_group_list.d.ts" />

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
