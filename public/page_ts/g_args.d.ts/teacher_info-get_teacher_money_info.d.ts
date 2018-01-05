interface GargsStatic {
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
	teacher_money_type	:any;
	nick	:any;
	phone	:any;
	email	:any;
	teacher_type	:any;
	teacher_ref_type	:any;
	identity	:any;
	grade_start	:any;
	grade_end	:any;
	realname	:any;
	work_year	:any;
	textbook_type	:any;
	dialect_notes	:any;
	level	:any;
	face	:any;
	gender	:any;
	birth	:any;
	grade_part_ex	:any;
	bankcard	:any;
	bank_province	:any;
	bank_city	:any;
	bank_type	:any;
	bank_phone	:any;
	bank_account	:any;
	bank_address	:any;
	idcard	:any;
	jianli	:any;
	train_through_new	:any;
	trial_lecture_is_pass	:any;
	create_time	:any;
	wx_openid	:any;
	test_transfor_per	:any;
	school	:any;
	change_count	:any;
	noevaluate_count	:any;
	late_count	:any;
	leave_count	:any;
	normal_count	:any;
	subject_str	:any;
	textbook_type_str	:any;
	identity_str	:any;
	teacher_ref_type_str	:any;
	gender_str	:any;
	days	:any;
}

/*

tofile: 
	 mkdir -p ../teacher_info; vi  ../teacher_info/get_teacher_money_info.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/teacher_info-get_teacher_money_info.d.ts" />

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
*/
