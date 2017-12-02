interface GargsStatic {
	seller_student_id:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
interface RowData {
}

/*

tofile: 
	 mkdir -p ../wx_teacher_info ; vi  ../wx_teacher_info/confirm_test_lesson.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/wx_teacher_info-confirm_test_lesson.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			seller_student_id:	$('#id_seller_student_id').val()
        });
    }

	$('#id_seller_student_id').val(g_args.seller_student_id);


	$('.opt-change').set_input_change_event(load_data);
});



*/
