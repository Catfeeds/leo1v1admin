interface GargsStatic {
	is_part_time:	number;
	tea_nick:	string;
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
	teacherid	:any;
	nick	:any;
	tutor_subject	:any;
	teacher_type	:any;
	tea_nick	:any;
	quiz_num	:any;
}

/*

tofile: 
	 mkdir -p ../tea_manage; vi  ../tea_manage/quiz_info.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tea_manage-quiz_info.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		is_part_time:	$('#id_is_part_time').val(),
		tea_nick:	$('#id_tea_nick').val()
		});
}
$(function(){


	$('#id_is_part_time').val(g_args.is_part_time);
	$('#id_tea_nick').val(g_args.tea_nick);


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
                <span class="input-group-addon">tea_nick</span>
                <input class="opt-change form-control" id="id_tea_nick" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["tea_nick title", "tea_nick", "th_tea_nick" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_num title", "page_num", "th_page_num" ]])!!}
*/
