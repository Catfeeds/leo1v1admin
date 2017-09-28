interface GargsStatic {
	sid:	number;
	dymanic_flag:	number;
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
}

/*

tofile: 
	 mkdir -p ../stu_manage; vi  ../stu_manage/user_login_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/stu_manage-user_login_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			sid:	$('#id_sid').val(),
			dymanic_flag:	$('#id_dymanic_flag').val()
        });
    }


	$('#id_sid').val(g_args.sid);
	$('#id_dymanic_flag').val(g_args.dymanic_flag);


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

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">dymanic_flag</span>
                <input class="opt-change form-control" id="id_dymanic_flag" />
            </div>
        </div>
*/
