interface GargsStatic {
	orderid:	number;
	apply_time:	number;
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
	 mkdir -p ../user_manage; vi  ../user_manage/refund_analysis.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage-refund_analysis.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			orderid:	$('#id_orderid').val(),
			apply_time:	$('#id_apply_time').val()
        });
    }


	$('#id_orderid').val(g_args.orderid);
	$('#id_apply_time').val(g_args.apply_time);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">orderid</span>
                <input class="opt-change form-control" id="id_orderid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">apply_time</span>
                <input class="opt-change form-control" id="id_apply_time" />
            </div>
        </div>
*/
