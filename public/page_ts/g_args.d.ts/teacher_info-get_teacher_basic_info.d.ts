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
	prove	:any;
	seniority	:any;
	teaching_achievement	:any;
	wx_name	:any;
	is_prove	:any;
	qq_info	:any;
	teacher_type	:any;
	teacher_ref_type	:any;
	identity	:any;
	grade_start	:any;
	grade_end	:any;
	address	:any;
	realname	:any;
	work_year	:any;
	teacher_textbook	:any;
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
	teacher_tags	:any;
	test_transfor_per	:any;
	school	:any;
	need_test_lesson_flag	:any;
	education	:any;
	major	:any;
	hobby	:any;
	speciality	:any;
	change_count	:any;
	noevaluate_count	:any;
	late_count	:any;
	leave_count	:any;
	normal_count	:any;
	subject_str	:any;
	teacher_textbook_str	:any;
	is_prove_str	:any;
	identity_str	:any;
	teacher_ref_type_str	:any;
	gender_str	:any;
	education_str	:any;
	days	:any;
	teacher_title	:any;
	grade_str	:any;
	teacher_tags_arr	:any;
	tags_flag	:any;
	teaching_achievement_code	:any;
	wx_name_code	:any;
	is_prove_code	:any;
	qq_info_code	:any;
	address_code	:any;
	work_year_code	:any;
	teacher_textbook_code	:any;
	dialect_notes_code	:any;
	gender_code	:any;
	birth_code	:any;
	bankcard_code	:any;
	bank_province_code	:any;
	bank_city_code	:any;
	bank_type_code	:any;
	bank_phone_code	:any;
	bank_account_code	:any;
	bank_address_code	:any;
	idcard_code	:any;
	school_code	:any;
	education_code	:any;
	teacher_textbook_str_code	:any;
	is_prove_str_code	:any;
	integrity	:any;
}

/*

tofile: 
	 mkdir -p ../teacher_info; vi  ../teacher_info/get_teacher_basic_info.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/teacher_info-get_teacher_basic_info.d.ts" />

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
