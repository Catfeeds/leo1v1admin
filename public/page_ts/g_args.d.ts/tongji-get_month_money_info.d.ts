interface GargsStatic {
	year:	string;//枚举列表: \App\Enums\Eyear
 }
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	all_money	:any;
	order_month	:any;
	count	:any;
	order_total	:any;
}

/*

tofile: 
	 mkdir -p ../tongji; vi  ../tongji/get_month_money_info.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji-get_month_money_info.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
		year:	$('#id_year').val()
    });
}
$(function(){


	$('#id_year').val(g_args.year);
	$.enum_multi_select( $('#id_year'), 'year', function(){load_data();} )


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">year</span>
                <input class="opt-change form-control" id="id_year" />
            </div>
        </div>
*/
