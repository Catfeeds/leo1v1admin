interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	trial_money:	number;
	normal_money:	number;
	order_str:	number;
	order_type:	number;
	lesson_num:	number;
	full_type:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	trial_money	:any;
	normal_money	:any;
	count_money	:any;
	nick	:any;
	all_money	:any;
}

/*

tofile: 
	 mkdir -p ../human_resource; vi  ../human_resource/get_lesson_full_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/human_resource-get_lesson_full_list.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		date_type_config:	$('#id_date_type_config').val(),
		date_type:	$('#id_date_type').val(),
		opt_date_type:	$('#id_opt_date_type').val(),
		start_time:	$('#id_start_time').val(),
		end_time:	$('#id_end_time').val(),
		trial_money:	$('#id_trial_money').val(),
		normal_money:	$('#id_normal_money').val(),
		order_str:	$('#id_order_str').val(),
		order_type:	$('#id_order_type').val(),
		lesson_num:	$('#id_lesson_num').val(),
		full_type:	$('#id_full_type').val()
		});
}
$(function(){


	$('#id_date_range').select_date_range({
		'date_type' : g_args.date_type,
		'opt_date_type' : g_args.opt_date_type,
		'start_time'    : g_args.start_time,
		'end_time'      : g_args.end_time,
		date_type_config : JSON.parse( g_args.date_type_config),
		onQuery :function() {
			load_data();
		});
	$('#id_trial_money').val(g_args.trial_money);
	$('#id_normal_money').val(g_args.normal_money);
	$('#id_order_str').val(g_args.order_str);
	$('#id_order_type').val(g_args.order_type);
	$('#id_lesson_num').val(g_args.lesson_num);
	$('#id_full_type').val(g_args.full_type);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...
{!!\App\Helper\Utils::th_order_gen([["date_type_config title", "date_type_config", "th_date_type_config" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["date_type title", "date_type", "th_date_type" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["opt_date_type title", "opt_date_type", "th_opt_date_type" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["start_time title", "start_time", "th_start_time" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["end_time title", "end_time", "th_end_time" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">trial_money</span>
                <input class="opt-change form-control" id="id_trial_money" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["trial_money title", "trial_money", "th_trial_money" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">normal_money</span>
                <input class="opt-change form-control" id="id_normal_money" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["normal_money title", "normal_money", "th_normal_money" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">order_str</span>
                <input class="opt-change form-control" id="id_order_str" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["order_str title", "order_str", "th_order_str" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">order_type</span>
                <input class="opt-change form-control" id="id_order_type" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["order_type title", "order_type", "th_order_type" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">lesson_num</span>
                <input class="opt-change form-control" id="id_lesson_num" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["lesson_num title", "lesson_num", "th_lesson_num" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">full_type</span>
                <input class="opt-change form-control" id="id_full_type" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["full_type title", "full_type", "th_full_type" ]])!!}
*/
