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
	child_groupid	:any;
	child_group_name	:any;
	master_adminid	:any;
	group_assign_percent	:any;
	master_nick	:any;
}

/*

tofile: 
	 mkdir -p ../user_manage_new; vi  ../user_manage_new/admin_main_group_edit.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-admin_main_group_edit.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			main_type:	$('#id_main_type').val(),
			groupid:	$('#id_groupid').val()
        });
    }


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

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">groupid</span>
                <input class="opt-change form-control" id="id_groupid" />
            </div>
        </div>
*/
