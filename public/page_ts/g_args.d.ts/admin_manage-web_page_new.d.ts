interface GargsStatic {
	web_page_id:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	share_wx_flag	:any;
	count	:any;
	ip_count	:any;
	up_groupid	:any;
	from_adminid_nick	:any;
	share_wx_flag_str	:any;
	main_type_str	:any;
	main_type	:any;
	up_group_name	:any;
	group_name	:any;
	account	:any;
	main_type_class	:any;
	up_group_name_class	:any;
	group_name_class	:any;
	account_class	:any;
	level	:any;
}

/*

tofile: 
	 mkdir -p ../admin_manage; vi  ../admin_manage/web_page_new.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/admin_manage-web_page_new.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
		web_page_id:	$('#id_web_page_id').val()
    });
}
$(function(){


	$('#id_web_page_id').val(g_args.web_page_id);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">web_page_id</span>
                <input class="opt-change form-control" id="id_web_page_id" />
            </div>
        </div>
*/
