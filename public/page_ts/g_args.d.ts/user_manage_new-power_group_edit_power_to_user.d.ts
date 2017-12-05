interface GargsStatic {
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
interface RowData {
	k1	:any;
	k2	:any;
	k3	:any;
	folder	:any;
	pid	:any;
	k_class	:any;
	class	:any;
	level	:any;
	has_power_flag	:any;
	url	:any;
}

/*

tofile: 
	 mkdir -p ../user_manage_new ; vi  ../user_manage_new/power_group_edit_power_to_user.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-power_group_edit_power_to_user.d.ts" />

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
