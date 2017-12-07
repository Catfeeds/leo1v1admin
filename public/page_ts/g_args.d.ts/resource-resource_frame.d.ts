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
	key1_class	:any;
	level	:any;
	resource_type_str	:any;
	resource_type	:any;
	subject_str	:any;
	grade_str	:any;
	tag_one_str	:any;
	tag_two_str	:any;
	tag_three_str	:any;
	tag_four_str	:any;
}

/*

tofile: 
	 mkdir -p ../resource; vi  ../resource/resource_frame.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/resource-resource_frame.d.ts" />

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
