interface GargsStatic {
	page_num:	number;
	page_count:	number;
	deal_flag:	number;
	lesson_problem:	number;
	feedback_adminid:	number;
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
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
	zip_url	:any;
	video_url	:any;
	img_url	:any;
	lesson_problem	:any;
	id	:any;
	deal_flag	:any;
	feedback_adminid	:any;
	record_adminid	:any;
	describe_msg	:any;
	lesson_url	:any;
	reason	:any;
	solution	:any;
	remark	:any;
	create_time	:any;
	stu_nick	:any;
	stu_phone	:any;
	stu_agent	:any;
	sid	:any;
	tid	:any;
	tea_nick	:any;
	tea_phone	:any;
	tea_agent	:any;
	stu_agent_simple	:any;
	tea_agent_simple	:any;
	feedback_nick	:any;
	record_nick	:any;
	deal_flag_str	:any;
	lesson_problem_str	:any;
}

/*

tofile: 
	 mkdir -p ../user_manage_new; vi  ../user_manage_new/product_info.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-product_info.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		deal_flag:	$('#id_deal_flag').val(),
		lesson_problem:	$('#id_lesson_problem').val(),
		feedback_adminid:	$('#id_feedback_adminid').val(),
		date_type_config:	$('#id_date_type_config').val(),
		date_type:	$('#id_date_type').val(),
		opt_date_type:	$('#id_opt_date_type').val(),
		start_time:	$('#id_start_time').val(),
		end_time:	$('#id_end_time').val()
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
	$('#id_deal_flag').val(g_args.deal_flag);
	$('#id_lesson_problem').val(g_args.lesson_problem);
	$('#id_feedback_adminid').val(g_args.feedback_adminid);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...
{!!\App\Helper\Utils::th_order_gen([["page_num title", "page_num", "th_page_num" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_count title", "page_count", "th_page_count" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">deal_flag</span>
                <input class="opt-change form-control" id="id_deal_flag" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["deal_flag title", "deal_flag", "th_deal_flag" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">lesson_problem</span>
                <input class="opt-change form-control" id="id_lesson_problem" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["lesson_problem title", "lesson_problem", "th_lesson_problem" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">feedback_adminid</span>
                <input class="opt-change form-control" id="id_feedback_adminid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["feedback_adminid title", "feedback_adminid", "th_feedback_adminid" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["date_type_config title", "date_type_config", "th_date_type_config" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["date_type title", "date_type", "th_date_type" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["opt_date_type title", "opt_date_type", "th_opt_date_type" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["start_time title", "start_time", "th_start_time" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["end_time title", "end_time", "th_end_time" ]])!!}
*/
