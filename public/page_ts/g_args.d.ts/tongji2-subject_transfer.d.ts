interface GargsStatic {
	chinese:	number;
	math:	number;
	english:	number;
	start_time:	string;
	end_time:	string;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	title	:any;
	subject_chinese	:any;
	subject_math	:any;
	subject_english	:any;
}

/*

tofile: 
	 mkdir -p ../tongji2; vi  ../tongji2/subject_transfer.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji2-subject_transfer.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		chinese:	$('#id_chinese').val(),
		math:	$('#id_math').val(),
		english:	$('#id_english').val(),
		start_time:	$('#id_start_time').val(),
		end_time:	$('#id_end_time').val()
		});
}
$(function(){


	$('#id_chinese').val(g_args.chinese);
	$('#id_math').val(g_args.math);
	$('#id_english').val(g_args.english);
	$('#id_start_time').val(g_args.start_time);
	$('#id_end_time').val(g_args.end_time);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">chinese</span>
                <input class="opt-change form-control" id="id_chinese" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["chinese title", "chinese", "th_chinese" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">math</span>
                <input class="opt-change form-control" id="id_math" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["math title", "math", "th_math" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">english</span>
                <input class="opt-change form-control" id="id_english" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["english title", "english", "th_english" ]])!!}

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
