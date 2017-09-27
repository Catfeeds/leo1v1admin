interface GargsStatic {
	group_id:	number;
	admin_id:	number;
	page_num:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
interface RowData {
	groupid	:any;
	adminid	:any;
	number	:any;
	admin_nick	:any;
}

/*

tofile: 
	 mkdir -p ../authority ; vi  ../authority/member_group.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/authority-member_group.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			group_id:	$('#id_group_id').val(),
			admin_id:	$('#id_admin_id').val()
        });
    }


	$('#id_group_id').val(g_args.group_id);
	$('#id_admin_id').val(g_args.admin_id);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">group_id</span>
                <input class="opt-change form-control" id="id_group_id" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">admin_id</span>
                <input class="opt-change form-control" id="id_admin_id" />
            </div>
        </div>
*/
