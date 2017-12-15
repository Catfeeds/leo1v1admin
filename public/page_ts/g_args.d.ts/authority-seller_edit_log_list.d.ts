interface GargsStatic {
	adminid:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	id	:any;
	uid	:any;
	type	:any;
	create_time	:any;
	adminid	:any;
	old	:any;
	new	:any;
	seller_level	:any;
	seller_level_str	:any;
	adminid_nick	:any;
	uid_nick	:any;
}

/*

tofile: 
	 mkdir -p ../authority; vi  ../authority/seller_edit_log_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/authority-seller_edit_log_list.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		adminid:	$('#id_adminid').val()
		});
}
$(function(){


	$('#id_adminid').admin_select_user_new({
		"user_type"    : "account",
		"select_value" : g_args.adminid,
		"onChange"     : load_data,
		"th_input_id"  : "th_adminid",
		"only_show_in_th_input"     : false,
		"can_select_all_flag"     : true
	});


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">adminid</span>
                <input class="opt-change form-control" id="id_adminid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["adminid title", "adminid", "th_adminid" ]])!!}
*/
