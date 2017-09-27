interface GargsStatic {
	user_name:	string;
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
	userid	:any;
	nick	:any;
	realname	:any;
	normal_nums	:any;
	free_nums	:any;
	lesson_count	:any;
	board_nums	:any;
}

/*

tofile: 
	 mkdir -p ../user_manage_new; vi  ../user_manage_new/stu_lesson_info.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-stu_lesson_info.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			user_name:	$('#id_user_name').val()
        });
    }


	$('#id_user_name').val(g_args.user_name);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">user_name</span>
                <input class="opt-change form-control" id="id_user_name" />
            </div>
        </div>
*/
