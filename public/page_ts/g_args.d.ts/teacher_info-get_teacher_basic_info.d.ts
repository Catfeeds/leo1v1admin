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
	level	:any;
	wx_openid	:any;
	nick	:any;
	phone	:any;
	email	:any;
	teacher_type	:any;
	teacher_ref_type	:any;
	create_time	:any;
	identity	:any;
	grade_start	:any;
	grade_end	:any;
	realname	:any;
	work_year	:any;
	textbook_type	:any;
	dialect_notes	:any;
	gender	:any;
	birth	:any;
	address	:any;
	face	:any;
	grade_part_ex	:any;
	bankcard	:any;
	bank_province	:any;
	bank_city	:any;
	bank_type	:any;
	bank_phone	:any;
	bank_account	:any;
	bank_address	:any;
	idcard	:any;
	train_through_new	:any;
	trial_lecture_is_pass	:any;
	wx_use_flag	:any;
	subject_str	:any;
	textbook_type_str	:any;
	identity_str	:any;
	teacher_ref_type_str	:any;
	gender_str	:any;
}

/*

tofile: 
	 mkdir -p ../teacher_info; vi  ../teacher_info/get_teacher_basic_info.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/teacher_info-get_teacher_basic_info.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {

        });
    }




	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...
*/
