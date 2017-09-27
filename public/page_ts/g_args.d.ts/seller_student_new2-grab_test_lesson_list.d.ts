interface GargsStatic {
	subject:	string;//枚举列表: App\Enums\Esubject
 }
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	subject	:any;
	grade	:any;
	phone	:any;
	require_id	:any;
	stu_request_test_lesson_time	:any;
	editionid	:any;
	textbook	:any;
	num	:any;
	subject_str	:any;
	grade_str	:any;
	stu_request_test_lesson_time_str	:any;
	editionid_str	:any;
}

/*

tofile: 
	 mkdir -p ../seller_student_new2; vi  ../seller_student_new2/grab_test_lesson_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student_new2-grab_test_lesson_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			subject:	$('#id_subject').val()
        });
    }


	$('#id_subject').val(g_args.subject);
	$.enum_multi_select( $('#id_subject'), 'subject', function(){load_data();} )


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">subject</span>
                <input class="opt-change form-control" id="id_subject" />
            </div>
        </div>
*/
