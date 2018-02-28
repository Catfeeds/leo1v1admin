interface GargsStatic {
	main_type:	number;
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
	adminid	:any;
	assign_percent	:any;
	admin_nick	:any;
}

/*

tofile: 
	 mkdir -p ../user_manage_new; vi  ../user_manage_new/admin_group_edit.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-admin_group_edit.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		main_type:	$('#id_main_type').val(),
		groupid:	$('#id_groupid').val()
		});
}
$(function(){


	$('#id_main_type').val(g_args.main_type);
	$('#id_groupid').val(g_args.groupid);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">main_type</span>
                <input class="opt-change form-control" id="id_main_type" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["main_type title", "main_type", "th_main_type" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">groupid</span>
                <input class="opt-change form-control" id="id_groupid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["groupid title", "groupid", "th_groupid" ]])!!}
*/
