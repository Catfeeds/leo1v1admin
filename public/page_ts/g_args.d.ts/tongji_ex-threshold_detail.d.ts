interface GargsStatic {
	start_time:	number;
	end_time:	number;
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
	 mkdir -p ../tongji_ex; vi  ../tongji_ex/threshold_detail.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji_ex-threshold_detail.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		start_time:	$('#id_start_time').val(),
		end_time:	$('#id_end_time').val()
		});
}
$(function(){


	$('#id_start_time').val(g_args.start_time);
	$('#id_end_time').val(g_args.end_time);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">start_time</span>
                <input class="opt-change form-control" id="id_start_time" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["start_time title", "start_time", "th_start_time" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">end_time</span>
                <input class="opt-change form-control" id="id_end_time" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["end_time title", "end_time", "th_end_time" ]])!!}
*/
