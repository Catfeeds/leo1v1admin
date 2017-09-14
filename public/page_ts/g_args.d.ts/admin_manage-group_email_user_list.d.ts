interface GargsStatic {
	groupid:	number;
	adminid:	number;
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
	groupid	:any;
	adminid	:any;
	name	:any;
	email	:any;
	account	:any;
	email_create_flag	:any;
	create_flag	:any;
	email_create_flag_str	:any;
	create_flag_str	:any;
}

/*

tofile: 
	 mkdir -p ../admin_manage; vi  ../admin_manage/group_email_user_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/admin_manage-group_email_user_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			groupid:	$('#id_groupid').val(),
			adminid:	$('#id_adminid').val()
        });
    }


	$('#id_groupid').val(g_args.groupid);
	$('#id_adminid').val(g_args.adminid);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">groupid</span>
                <input class="opt-change form-control" id="id_groupid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">adminid</span>
                <input class="opt-change form-control" id="id_adminid" />
            </div>
        </div>
*/
