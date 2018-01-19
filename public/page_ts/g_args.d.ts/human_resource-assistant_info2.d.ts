interface GargsStatic {
	is_part_time:	number;
	ass_nick:	string;
	phone:	string;
	score:	number;
	page_num:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	nick	:any;
	assistant_type	:any;
	gender	:any;
	birth	:any;
	phone	:any;
	email	:any;
	rate_score	:any;
	assistantid	:any;
	school	:any;
	prize	:any;
	number	:any;
	ass_nick	:any;
	is_part_time	:any;
	age	:any;
}

/*

tofile: 
	 mkdir -p ../human_resource; vi  ../human_resource/assistant_info2.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/human_resource-assistant_info2.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		is_part_time:	$('#id_is_part_time').val(),
		ass_nick:	$('#id_ass_nick').val(),
		phone:	$('#id_phone').val(),
		score:	$('#id_score').val()
		});
}
$(function(){


	$('#id_is_part_time').val(g_args.is_part_time);
	$('#id_ass_nick').val(g_args.ass_nick);
	$('#id_phone').val(g_args.phone);
	$('#id_score').val(g_args.score);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">is_part_time</span>
                <input class="opt-change form-control" id="id_is_part_time" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["is_part_time title", "is_part_time", "th_is_part_time" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">ass_nick</span>
                <input class="opt-change form-control" id="id_ass_nick" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["ass_nick title", "ass_nick", "th_ass_nick" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">phone</span>
                <input class="opt-change form-control" id="id_phone" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["phone title", "phone", "th_phone" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">score</span>
                <input class="opt-change form-control" id="id_score" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["score title", "score", "th_score" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_num title", "page_num", "th_page_num" ]])!!}
*/
