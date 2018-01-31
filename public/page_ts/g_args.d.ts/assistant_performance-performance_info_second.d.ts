interface GargsStatic {
	page_num:	number;
	page_count:	number;
	order_by_str:	string;
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
	adminid	:any;
	month	:any;
	read_student	:any;
	stop_student	:any;
	month_stop_student	:any;
	all_student	:any;
	warning_student	:any;
	renw_price	:any;
	renw_student	:any;
	tran_price	:any;
	lesson_total	:any;
	lesson_ratio	:any;
	kk_num	:any;
	userid_list	:any;
	refund_student	:any;
	new_refund_money	:any;
	renw_refund_money	:any;
	read_student_last	:any;
	userid_list_last	:any;
	lesson_total_old	:any;
	kpi_type	:any;
	read_student_new	:any;
	all_student_new	:any;
	lesson_money	:any;
	end_stu_num	:any;
	lesson_student	:any;
	new_student	:any;
	new_lesson_count	:any;
	revisit_target	:any;
	revisit_real	:any;
	first_revisit_num	:any;
	un_first_revisit_num	:any;
	refund_score	:any;
	lesson_price_avg	:any;
	student_finsh	:any;
	tran_num	:any;
	hand_kk_num	:any;
	assign_lesson	:any;
	cc_tran_num	:any;
	cc_tran_money	:any;
	hand_tran_num	:any;
	stop_student_list	:any;
	registered_student_list	:any;
	end_no_renw_num	:any;
	estimate_month_lesson_count	:any;
	seller_month_lesson_count	:any;
	seller_week_stu_num	:any;
	ass_refund_money	:any;
	all_ass_stu_num	:any;
	revisit_reword_per	:any;
	kpi_lesson_count_finish_per	:any;
	performance_end_stu_list	:any;
	first_lesson_stu_list	:any;
	performance_cc_tran_num	:any;
	performance_cc_tran_money	:any;
	performance_cr_renew_num	:any;
	performance_cr_renew_money	:any;
	performance_cr_new_num	:any;
	performance_cr_new_money	:any;
	master_adminid	:any;
	name	:any;
	account	:any;
	assistantid	:any;
	account_role	:any;
	del_flag	:any;
	create_time	:any;
	leave_member_time	:any;
	become_full_member_time	:any;
	become_member_time	:any;
	group_name	:any;
	main_type	:any;
	become_member_time_str	:any;
	become_full_member_time_str	:any;
	leave_member_time_str	:any;
	del_flag_str	:any;
	account_role_str	:any;
	all_student_last	:any;
	last_registered_num	:any;
	lesson_count_finish_reword	:any;
	renw_target	:any;
	renw_reword	:any;
	cc_tran_reword	:any;
	cc_tran_price_reword	:any;
	kk_num_old	:any;
	kk_all	:any;
	kk_reword_per	:any;
	kk_reword_per_old	:any;
	kk_reword_old	:any;
	stop_reword_per	:any;
	end_no_renw_reword_per	:any;
	revisit_reword	:any;
	kpi_lesson_count_finish_reword	:any;
	kk_reword	:any;
	stop_reword	:any;
	end_no_renw_reword	:any;
	all_reword	:any;
	old_ewnew_money	:any;
}

/*

tofile: 
	 mkdir -p ../assistant_performance; vi  ../assistant_performance/performance_info_second.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/assistant_performance-performance_info_second.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		order_by_str:	$('#id_order_by_str').val(),
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
	$('#id_order_by_str').val(g_args.order_by_str);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...
{!!\App\Helper\Utils::th_order_gen([["page_num title", "page_num", "th_page_num" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_count title", "page_count", "th_page_count" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">order_by_str</span>
                <input class="opt-change form-control" id="id_order_by_str" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["order_by_str title", "order_by_str", "th_order_by_str" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["date_type_config title", "date_type_config", "th_date_type_config" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["date_type title", "date_type", "th_date_type" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["opt_date_type title", "opt_date_type", "th_opt_date_type" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["start_time title", "start_time", "th_start_time" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["end_time title", "end_time", "th_end_time" ]])!!}
*/
