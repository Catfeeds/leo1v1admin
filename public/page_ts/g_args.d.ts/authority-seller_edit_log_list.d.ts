interface GargsStatic {
	uid:	number;
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
	adminid_nick	:any;
	uid_nick	:any;
}

/*

tofile: 
	 mkdir -p ../authority; vi  ../authority/seller_edit_log_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/authority-seller_edit_log_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			uid:	$('#id_uid').val()
        });
    }


	$('#id_uid').val(g_args.uid);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">uid</span>
                <input class="opt-change form-control" id="id_uid" />
            </div>
        </div>
*/
