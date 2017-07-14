interface GargsStatic {
	page_num:	number;
	page_count:	number;
	machine_id:	number;
	adminid:	number;
	auth_flag:	number;//\App\Enums\Eboolean
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	title	:any;
	machine_id	:any;
	adminid	:any;
	auth_flag	:any;
	open_door_flag	:any;
	sn	:any;
	del_flag	:any;
	auth_flag_str	:any;
	admin_nick	:any;
}

/*

tofile: 
	 mkdir -p ../admin_manage; vi  ../admin_manage/kaoqin_machine_adminid.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/admin_manage-kaoqin_machine_adminid.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			machine_id:	$('#id_machine_id').val(),
			adminid:	$('#id_adminid').val(),
			auth_flag:	$('#id_auth_flag').val()
        });
    }

	Enum_map.append_option_list("boolean",$("#id_auth_flag"));

	$('#id_machine_id').val(g_args.machine_id);
	$('#id_adminid').val(g_args.adminid);
	$('#id_auth_flag').val(g_args.auth_flag);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">machine_id</span>
                <input class="opt-change form-control" id="id_machine_id" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">adminid</span>
                <input class="opt-change form-control" id="id_adminid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">boolean</span>
                <select class="opt-change form-control" id="id_auth_flag" >
                </select>
            </div>
        </div>
*/
