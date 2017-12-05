interface GargsStatic {
	opt_type:	number;
	page_num:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
interface RowData {
	seller_student_id	:any;
	grade	:any;
	subject	:any;
	st_class_time	:any;
	assigned_teacherid	:any;
	teacher_confirm_flag	:any;
	teacher_confirm_time	:any;
	grade_str	:any;
	subject_str	:any;
	st_class_time_str	:any;
}

/*

tofile: 
	 mkdir -p ../wx_teacher_info ; vi  ../wx_teacher_info/test_lesson_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/wx_teacher_info-test_lesson_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			opt_type:	$('#id_opt_type').val()
        });
    }

	$('#id_opt_type').val(g_args.opt_type);


	$('.opt-change').set_input_change_event(load_data);
});



*/
