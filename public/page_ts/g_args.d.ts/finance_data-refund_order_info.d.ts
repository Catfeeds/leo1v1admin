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
	id	:any;
	nick	:any;
	phone	:any;
	grade	:any;
	order_custom	:any;
	sys_operator	:any;
	order_time	:any;
	contract_type	:any;
	lesson_total	:any;
	refund_lesson_count	:any;
	order_cost_price	:any;
	refund_price	:any;
	order_price	:any;
	is_invoice	:any;
	invoice	:any;
	payment_account_id	:any;
	refund_info	:any;
	save_info	:any;
	apply_account	:any;
	apply_time	:any;
	approve_time	:any;
	approve_status	:any;
	refund_status	:any;
	period_flag	:any;
	assistant_name	:any;
	subject	:any;
	teacher_realname	:any;
	connection_state	:any;
	lifting_state	:any;
	learning_attitude	:any;
	order_three_month_flag	:any;
	assistant_one_level_cause	:any;
	assistant_two_level_cause	:any;
	assistant_three_level_cause	:any;
	assistant_deduction_value	:any;
	assistant_cause_rate	:any;
	assistant_cause_analysis	:any;
	registrar_one_level_cause	:any;
	registrar_two_level_cause	:any;
	registrar_three_level_cause	:any;
	registrar_deduction_value	:any;
	registrar_cause_rate	:any;
	registrar_cause_analysis	:any;
	teacher_manage_one_level_cause	:any;
	teacher_manage_two_level_cause	:any;
	teacher_manage_three_level_cause	:any;
	teacher_manage_deduction_value	:any;
	teacher_manage_cause_rate	:any;
	teacher_manage_cause_analysis	:any;
	dvai_one_level_cause	:any;
	dvai_two_level_cause	:any;
	dvai_three_level_cause	:any;
	dvai_deduction_value	:any;
	dvai_cause_rate	:any;
	dvai_cause_analysis	:any;
	product_one_level_cause	:any;
	product_two_level_cause	:any;
	product_three_level_cause	:any;
	product_deduction_value	:any;
	product_cause_rate	:any;
	product_cause_analysis	:any;
	advisory_one_level_cause	:any;
	advisory_two_level_cause	:any;
	advisory_three_level_cause	:any;
	advisory_deduction_value	:any;
	advisory_cause_rate	:any;
	advisory_cause_analysis	:any;
	customer_changes_one_level_cause	:any;
	customer_changes_two_level_cause	:any;
	customer_changes_three_level_cause	:any;
	customer_changes_deduction_value	:any;
	customer_changes_cause_rate	:any;
	customer_changes_cause_analysis	:any;
	teacher_one_level_cause	:any;
	teacher_two_level_cause	:any;
	teacher_three_level_cause	:any;
	teacher_deduction_value	:any;
	teacher_cause_rate	:any;
	teacher_cause_analysis	:any;
	subject_one_level_cause	:any;
	subject_two_level_cause	:any;
	subject_three_level_cause	:any;
	subject_deduction_value	:any;
	subject_cause_rate	:any;
	subject_cause_analysis	:any;
	other_cause	:any;
	quality_control_global_analysis	:any;
	later_countermeasure	:any;
	order_time_str	:any;
	apply_time_str	:any;
	approve_time_str	:any;
	order_custom_str	:any;
}

/*

tofile: 
	 mkdir -p ../finance_data; vi  ../finance_data/refund_order_info.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/finance_data-refund_order_info.d.ts" />

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
