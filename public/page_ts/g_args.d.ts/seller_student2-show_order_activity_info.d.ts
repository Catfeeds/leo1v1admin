interface GargsStatic {
	id_open_flag:	number;
	id_can_disable_flag:	number;
	id_contract_type:	number;
	id_period_flag:	number;
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
	 mkdir -p ../seller_student2; vi  ../seller_student2/show_order_activity_info.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student2-show_order_activity_info.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
		id_open_flag:	$('#id_id_open_flag').val(),
		id_can_disable_flag:	$('#id_id_can_disable_flag').val(),
		id_contract_type:	$('#id_id_contract_type').val(),
		id_period_flag:	$('#id_id_period_flag').val()
    });
}
$(function(){


	$('#id_id_open_flag').val(g_args.id_open_flag);
	$('#id_id_can_disable_flag').val(g_args.id_can_disable_flag);
	$('#id_id_contract_type').val(g_args.id_contract_type);
	$('#id_id_period_flag').val(g_args.id_period_flag);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">id_open_flag</span>
                <input class="opt-change form-control" id="id_id_open_flag" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">id_can_disable_flag</span>
                <input class="opt-change form-control" id="id_id_can_disable_flag" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">id_contract_type</span>
                <input class="opt-change form-control" id="id_id_contract_type" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">id_period_flag</span>
                <input class="opt-change form-control" id="id_id_period_flag" />
            </div>
        </div>
*/
