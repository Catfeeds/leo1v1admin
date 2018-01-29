interface GargsStatic {
	teacherid:	number;
	page_num:	number;
	page_count:	number;
	research_flag:	number;
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
	subject	:any;
	grade_start	:any;
	grade_end	:any;
	grade_part_ex	:any;
	phone	:any;
	realname	:any;
	second_subject	:any;
	second_grade_end	:any;
	second_grade_start	:any;
	limit_day_lesson_num	:any;
	limit_week_lesson_num	:any;
	limit_month_lesson_num	:any;
	saturday_lesson_num	:any;
	week_lesson_count	:any;
	week_limit_time_info	:any;
	limit_seller_require_flag	:any;
	subject_str	:any;
	grade_start_str	:any;
	grade_end_str	:any;
	second_subject_str	:any;
	second_grade_start_str	:any;
	second_grade_end_str	:any;
	limit_seller_require_flag_str	:any;
	week_limit_time_info_str	:any;
}

/*

tofile: 
	 mkdir -p ../human_resource; vi  ../human_resource/reaearch_teacher_lesson_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/human_resource-reaearch_teacher_lesson_list.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		teacherid:	$('#id_teacherid').val(),
		research_flag:	$('#id_research_flag').val()
		});
}
$(function(){


	$('#id_teacherid').admin_select_user_new({
		"user_type"    : "teacher",
		"select_value" : g_args.teacherid,
		"onChange"     : load_data,
		"th_input_id"  : "th_teacherid",
		"only_show_in_th_input"     : false,
		"can_select_all_flag"     : true
	});
	$('#id_research_flag').val(g_args.research_flag);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">teacherid</span>
                <input class="opt-change form-control" id="id_teacherid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["teacherid title", "teacherid", "th_teacherid" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_num title", "page_num", "th_page_num" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_count title", "page_count", "th_page_count" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">research_flag</span>
                <input class="opt-change form-control" id="id_research_flag" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["research_flag title", "research_flag", "th_research_flag" ]])!!}
*/
