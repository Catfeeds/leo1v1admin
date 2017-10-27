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
	colconel_id	:any;
	colconel_name	:any;
	test_lesson_count	:any;
	member_count	:any;
	student_count	:any;
	order_count	:any;
	order_money	:any;
	is_colconel	:any;
	level	:any;
	main_type_class	:any;
	up_group_name_class	:any;
	group_name_class	:any;
}

/*

tofile: 
	 mkdir -p ../agent; vi  ../agent/agent_group_statistics.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/agent-agent_group_statistics.d.ts" />

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
