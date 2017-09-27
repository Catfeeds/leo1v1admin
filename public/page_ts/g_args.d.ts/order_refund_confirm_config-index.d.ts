interface GargsStatic {
	key1:	number;
	key2:	number;
	key3:	number;
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
	 mkdir -p ../order_refund_confirm_config; vi  ../order_refund_confirm_config/index.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/order_refund_confirm_config-index.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			key1:	$('#id_key1').val(),
			key2:	$('#id_key2').val(),
			key3:	$('#id_key3').val()
        });
    }


	$('#id_key1').val(g_args.key1);
	$('#id_key2').val(g_args.key2);
	$('#id_key3').val(g_args.key3);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">key1</span>
                <input class="opt-change form-control" id="id_key1" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">key2</span>
                <input class="opt-change form-control" id="id_key2" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">key3</span>
                <input class="opt-change form-control" id="id_key3" />
            </div>
        </div>
*/
