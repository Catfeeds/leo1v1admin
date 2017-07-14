interface GargsStatic {
	seller_student_id:	string;
	page_num:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
interface RowData {
	id	:any;
	seller_student_id	:any;
	teacherid	:any;
	assign_time	:any;
	teacher_confirm_flag	:any;
	teacher_confirm_time	:any;
	degree	:any;
	assign_adminid	:any;
	openid	:any;
	has_openid	:any;
	teacher_nick	:any;
	assign_admin_nick	:any;
	teacher_confirm_flag_str	:any;
	degree_str	:any;
	has_openid_str	:any;
}

/*

tofile: 
	 mkdir -p ../seller_student ; vi  ../seller_student/test_lesson_assign_teacher.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student-test_lesson_assign_teacher.d.ts" />

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
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">seller_student_id</span>
                <input class="opt-change form-control" id="id_seller_student_id" />
            </div>
        </div>
*/
