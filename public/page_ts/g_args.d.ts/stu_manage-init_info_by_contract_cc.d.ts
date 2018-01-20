interface GargsStatic {
	sid:	number;
	orderid:	number;
	is_show_submit:	number;
	gg_acc:	number;
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
	 mkdir -p ../stu_manage; vi  ../stu_manage/init_info_by_contract_cc.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/stu_manage-init_info_by_contract_cc.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		sid:	$('#id_sid').val(),
		orderid:	$('#id_orderid').val(),
		is_show_submit:	$('#id_is_show_submit').val(),
		gg_acc:	$('#id_gg_acc').val()
		});
}
$(function(){


	$('#id_sid').val(g_args.sid);
	$('#id_orderid').val(g_args.orderid);
	$('#id_is_show_submit').val(g_args.is_show_submit);
	$('#id_gg_acc').val(g_args.gg_acc);


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
{!!\App\Helper\Utils::th_order_gen([["sid title", "sid", "th_sid" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">orderid</span>
                <input class="opt-change form-control" id="id_orderid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["orderid title", "orderid", "th_orderid" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">is_show_submit</span>
                <input class="opt-change form-control" id="id_is_show_submit" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["is_show_submit title", "is_show_submit", "th_is_show_submit" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">gg_acc</span>
                <input class="opt-change form-control" id="id_gg_acc" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["gg_acc title", "gg_acc", "th_gg_acc" ]])!!}
*/
