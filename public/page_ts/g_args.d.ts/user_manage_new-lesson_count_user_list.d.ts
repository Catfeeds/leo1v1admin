interface GargsStatic {
	start_time:	string;
	end_time:	string;
	lesson_count_start:	number;
	lesson_count_end:	number;
	assistantid:	number;
	type:	number;
	grade:	number;//枚举: App\Enums\Egrade
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
	 mkdir -p ../user_manage_new; vi  ../user_manage_new/lesson_count_user_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-lesson_count_user_list.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		start_time:	$('#id_start_time').val(),
		end_time:	$('#id_end_time').val(),
		lesson_count_start:	$('#id_lesson_count_start').val(),
		lesson_count_end:	$('#id_lesson_count_end').val(),
		assistantid:	$('#id_assistantid').val(),
		type:	$('#id_type').val(),
		grade:	$('#id_grade').val()
		});
}
$(function(){


	$('#id_start_time').val(g_args.start_time);
	$('#id_end_time').val(g_args.end_time);
	$('#id_lesson_count_start').val(g_args.lesson_count_start);
	$('#id_lesson_count_end').val(g_args.lesson_count_end);
	$('#id_assistantid').admin_select_user_new({
		"user_type"    : "assistant",
		"select_value" : g_args.assistantid,
		"onChange"     : load_data,
		"th_input_id"  : "th_assistantid",
		"only_show_in_th_input"     : false,
		"can_select_all_flag"     : true
	});
	$('#id_type').val(g_args.type);
	$('#id_grade').admin_set_select_field({
		"enum_type"    : "grade",
		"field_name" : "grade",
		"select_value" : g_args.grade,
		"onChange"     : load_data,
		"multi_select_flag"     : false ,
		"th_input_id"  : "th_grade",
		"only_show_in_th_input"     : false,
		"btn_id_config"     : {},
	});


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

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">lesson_count_start</span>
                <input class="opt-change form-control" id="id_lesson_count_start" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["lesson_count_start title", "lesson_count_start", "th_lesson_count_start" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">lesson_count_end</span>
                <input class="opt-change form-control" id="id_lesson_count_end" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["lesson_count_end title", "lesson_count_end", "th_lesson_count_end" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">assistantid</span>
                <input class="opt-change form-control" id="id_assistantid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["assistantid title", "assistantid", "th_assistantid" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">type</span>
                <input class="opt-change form-control" id="id_type" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["type title", "type", "th_type" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">年级</span>
                <select class="opt-change form-control" id="id_grade" >
                </select>
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["grade title", "grade", "th_grade" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_num title", "page_num", "th_page_num" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_count title", "page_count", "th_page_count" ]])!!}
*/
