interface GargsStatic {
	sid:	number;
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
	userid	:any;
	login_time	:any;
	nick	:any;
	ip	:any;
	role	:any;
	login_type	:any;
	dymanic_flag	:any;
}

/*

tofile: 
	 mkdir -p ../stu_manage; vi  ../stu_manage/todo_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/stu_manage-todo_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			sid:	$('#id_sid').val()
        });
    }


	$('#id_sid').val(g_args.sid);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">sid</span>
                <input class="opt-change form-control" id="id_sid" />
            </div>
        </div>
*/
