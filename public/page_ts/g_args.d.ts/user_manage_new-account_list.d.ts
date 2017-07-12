interface GargsStatic {
	page_num:	number;
	page_count:	number;
	phone:	string;
	userid:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	phone	:any;
	role	:any;
	userid	:any;
	nick	:any;
	role_str	:any;
}

/*

tofile: 
	 mkdir -p ../user_manage_new; vi  ../user_manage_new/account_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-account_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			phone:	$('#id_phone').val(),
			userid:	$('#id_userid').val()
        });
    }


	$('#id_phone').val(g_args.phone);
	$('#id_userid').val(g_args.userid);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">phone</span>
                <input class="opt-change form-control" id="id_phone" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">userid</span>
                <input class="opt-change form-control" id="id_userid" />
            </div>
        </div>
*/
