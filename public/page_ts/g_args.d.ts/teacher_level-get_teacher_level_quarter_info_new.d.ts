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
	teacherid	:any;
	realname	:any;
	level	:any;
	teacher_money_type	:any;
	phone	:any;
	train_through_new_time	:any;
	create_time	:any;
	require_time	:any;
	require_adminid	:any;
	accept_adminid	:any;
	accept_time	:any;
	accept_flag	:any;
	accept_info	:any;
	level_str	:any;
	level_after	:any;
	level_after_str	:any;
	accept_time_str	:any;
	require_time_str	:any;
	accept_flag_str	:any;
	hand_flag	:any;
}

/*

tofile: 
	 mkdir -p ../teacher_level; vi  ../teacher_level/get_teacher_level_quarter_info_new.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/teacher_level-get_teacher_level_quarter_info_new.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {

        });
    }




	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...
*/
