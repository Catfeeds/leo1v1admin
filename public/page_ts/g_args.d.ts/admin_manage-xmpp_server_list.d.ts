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
	id	:any;
	server_name	:any;
	server_desc	:any;
	ip	:any;
	xmpp_port	:any;
	webrtc_port	:any;
	websocket_port	:any;
	weights	:any;
}

/*

tofile: 
	 mkdir -p ../admin_manage; vi  ../admin_manage/xmpp_server_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/admin_manage-xmpp_server_list.d.ts" />

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
