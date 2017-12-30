interface GargsStatic {
	role:	number;
	groupid:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
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
	 mkdir -p ../user_manage_new; vi  ../user_manage_new/user_power_group_edit.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-user_power_group_edit.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		role:	$('#id_role').val(),
		groupid:	$('#id_groupid').val()
		});
}
$(function(){


	$('#id_role').val(g_args.role);
	$('#id_groupid').val(g_args.groupid);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">role</span>
                <input class="opt-change form-control" id="id_role" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["role title", "role", "th_role" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">groupid</span>
                <input class="opt-change form-control" id="id_groupid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["groupid title", "groupid", "th_groupid" ]])!!}
*/
