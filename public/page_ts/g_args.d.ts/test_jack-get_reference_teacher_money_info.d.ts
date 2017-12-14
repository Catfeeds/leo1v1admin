interface GargsStatic {
	start:	string;
	end:	string;
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
	 mkdir -p ../test_jack; vi  ../test_jack/get_reference_teacher_money_info.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/test_jack-get_reference_teacher_money_info.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		start:	$('#id_start').val(),
		end:	$('#id_end').val()
		});
}
$(function(){


	$('#id_start').val(g_args.start);
	$('#id_end').val(g_args.end);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">start</span>
                <input class="opt-change form-control" id="id_start" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["start title", "start", "th_start" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">end</span>
                <input class="opt-change form-control" id="id_end" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["end title", "end", "th_end" ]])!!}
*/
