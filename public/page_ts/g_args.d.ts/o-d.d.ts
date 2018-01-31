interface GargsStatic {
	sn:	string;
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
	 mkdir -p ../o; vi  ../o/d.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/o-d.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		sn:	$('#id_sn').val()
		});
}
$(function(){


	$('#id_sn').val(g_args.sn);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">sn</span>
                <input class="opt-change form-control" id="id_sn" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["sn title", "sn", "th_sn" ]])!!}
*/
