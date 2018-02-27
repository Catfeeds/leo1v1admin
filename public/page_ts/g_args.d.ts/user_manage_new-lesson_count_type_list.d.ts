interface GargsStatic {
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
	assistantid	:any;
	nick	:any;
	face	:any;
	birth	:any;
	phone	:any;
	level	:any;
	base_intro	:any;
	advantage	:any;
	course	:any;
	school	:any;
	title	:any;
	rate_score	:any;
	rate_attitude	:any;
	rate_kind	:any;
	rate_effect	:any;
	five_star	:any;
	four_star	:any;
	three_star	:any;
	two_star	:any;
	one_star	:any;
	last_modified_time	:any;
	grade	:any;
	work_year	:any;
	tutor_subject	:any;
	tutor_grade	:any;
	gender	:any;
	stu_num	:any;
	email	:any;
	assistant_type	:any;
	prize	:any;
	ass_style	:any;
	achievement	:any;
	is_quit	:any;
	e_name	:any;
	assign_lesson_count	:any;
	yi_total_revisit	:any;
	total_revisit	:any;
	first_revisit	:any;
	yi_first_revisit	:any;
	xq_revisit	:any;
	yd_revisit	:any;
	assistant_nick	:any;
	yyd_revisit	:any;
	yxq_revisit	:any;
}

/*

tofile: 
	 mkdir -p ../user_manage_new; vi  ../user_manage_new/lesson_count_type_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-lesson_count_type_list.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,

		});
}
$(function(){




	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...
{!!\App\Helper\Utils::th_order_gen([["page_num title", "page_num", "th_page_num" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_count title", "page_count", "th_page_count" ]])!!}
*/
