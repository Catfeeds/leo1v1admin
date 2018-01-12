interface GargsStatic {
	role_groupid:	number;
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
	power_id	:any;
	name	:any;
	url	:any;
	domain_type	:any;
	id	:any;
	pId	:any;
	page_id	:any;
	checked	:any;
}

/*

tofile: 
	 mkdir -p ../user_manage_new; vi  ../user_manage_new/power_group_edit_new.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-power_group_edit_new.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		role_groupid:	$('#id_role_groupid').val(),
		groupid:	$('#id_groupid').val()
		});
}
$(function(){


	$('#id_role_groupid').val(g_args.role_groupid);
	$('#id_groupid').val(g_args.groupid);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">role_groupid</span>
                <input class="opt-change form-control" id="id_role_groupid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["role_groupid title", "role_groupid", "th_role_groupid" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">groupid</span>
                <input class="opt-change form-control" id="id_groupid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["groupid title", "groupid", "th_groupid" ]])!!}
*/
