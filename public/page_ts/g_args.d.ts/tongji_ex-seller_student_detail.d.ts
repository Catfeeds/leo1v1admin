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
	key0	:any;
	seller_resource_type	:any;
	first_call_time	:any;
	first_contact_time	:any;
	test_lesson_count	:any;
	first_revisit_time	:any;
	last_revisit_time	:any;
	tmk_assign_time	:any;
	last_contact_time	:any;
	last_contact_cc	:any;
	competition_call_adminid	:any;
	competition_call_time	:any;
	sys_invaild_flag	:any;
	wx_invaild_flag	:any;
	return_publish_count	:any;
	tmk_adminid	:any;
	test_lesson_subject_id	:any;
	seller_student_sub_status	:any;
	add_time	:any;
	global_tq_called_flag	:any;
	seller_student_status	:any;
	userid	:any;
	nick	:any;
	origin	:any;
	origin_level	:any;
	phone_location	:any;
	phone	:any;
	sub_assign_adminid_2	:any;
	admin_revisiterid	:any;
	admin_assign_time	:any;
	sub_assign_time_2	:any;
	origin_assistantid	:any;
	origin_userid	:any;
	subject	:any;
	grade	:any;
	user_desc	:any;
	has_pad	:any;
	require_adminid	:any;
	tmk_student_status	:any;
	first_tmk_set_valid_admind	:any;
	first_tmk_set_valid_time	:any;
	tmk_set_seller_adminid	:any;
	first_tmk_set_seller_time	:any;
	first_admin_master_adminid	:any;
	first_admin_master_time	:any;
	first_admin_revisiterid	:any;
	first_admin_revisiterid_time	:any;
	first_seller_status	:any;
	call_count	:any;
	auto_allot_adminid	:any;
	first_called_cc	:any;
	first_get_cc	:any;
	test_lesson_flag	:any;
	orderid	:any;
	price	:any;
	seller_student_status_str	:any;
	global_tq_called_flag_str	:any;
	cc_nick	:any;
	suc_test_flag	:any;
	order_flag	:any;
}

/*

tofile: 
	 mkdir -p ../tongji_ex; vi  ../tongji_ex/seller_student_detail.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji_ex-seller_student_detail.d.ts" />

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
