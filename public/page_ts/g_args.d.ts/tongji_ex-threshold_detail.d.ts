interface GargsStatic {
	report_time:	number;
	color:	string;
	threshold_line:	number;
	count_call:	number;
	count_no_called:	number;
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
		report_time:	$('#id_report_time').val(),
		color:	$('#id_color').val(),
		threshold_line:	$('#id_threshold_line').val(),
		count_call:	$('#id_count_call').val(),
		count_no_called:	$('#id_count_no_called').val(),
		start_time:	$('#id_start_time').val(),
		end_time:	$('#id_end_time').val()
		});
}
$(function(){


	$('#id_report_time').val(g_args.report_time);
	$('#id_color').val(g_args.color);
	$('#id_threshold_line').val(g_args.threshold_line);
	$('#id_count_call').val(g_args.count_call);
	$('#id_count_no_called').val(g_args.count_no_called);
	$('#id_start_time').val(g_args.start_time);
	$('#id_end_time').val(g_args.end_time);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">report_time</span>
                <input class="opt-change form-control" id="id_report_time" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["report_time title", "report_time", "th_report_time" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">color</span>
                <input class="opt-change form-control" id="id_color" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["color title", "color", "th_color" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">threshold_line</span>
                <input class="opt-change form-control" id="id_threshold_line" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["threshold_line title", "threshold_line", "th_threshold_line" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">count_call</span>
                <input class="opt-change form-control" id="id_count_call" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["count_call title", "count_call", "th_count_call" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">count_no_called</span>
                <input class="opt-change form-control" id="id_count_no_called" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["count_no_called title", "count_no_called", "th_count_no_called" ]])!!}

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
