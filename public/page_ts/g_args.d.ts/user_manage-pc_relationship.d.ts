interface GargsStatic {
	page_num:	number;
	page_count:	number;
	studentid:	number;
	parentid:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	parentid	:any;
	parent_type	:any;
	userid	:any;
	phone	:any;
	role	:any;
	login_phone	:any;
	parent_nick	:any;
	user_nick	:any;
	parent_type_str	:any;
	role_str	:any;
}

/*

tofile: 
	 mkdir -p ../user_manage; vi  ../user_manage/pc_relationship.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage-pc_relationship.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			studentid:	$('#id_studentid').val(),
			parentid:	$('#id_parentid').val()
        });
    }


	$('#id_studentid').val(g_args.studentid);
	$('#id_parentid').val(g_args.parentid);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">studentid</span>
                <input class="opt-change form-control" id="id_studentid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">parentid</span>
                <input class="opt-change form-control" id="id_parentid" />
            </div>
        </div>
*/
