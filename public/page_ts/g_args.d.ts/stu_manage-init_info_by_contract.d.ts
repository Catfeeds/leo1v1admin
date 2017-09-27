interface GargsStatic {
	sid:	number;
	orderid:	number;
	is_show_submit:	number;
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
	 mkdir -p ../stu_manage; vi  ../stu_manage/init_info_by_contract.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/stu_manage-init_info_by_contract.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			sid:	$('#id_sid').val(),
			orderid:	$('#id_orderid').val(),
			is_show_submit:	$('#id_is_show_submit').val()
        });
    }


	$('#id_sid').val(g_args.sid);
	$('#id_orderid').val(g_args.orderid);
	$('#id_is_show_submit').val(g_args.is_show_submit);


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
                <span class="input-group-addon">orderid</span>
                <input class="opt-change form-control" id="id_orderid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">is_show_submit</span>
                <input class="opt-change form-control" id="id_is_show_submit" />
            </div>
        </div>
*/
