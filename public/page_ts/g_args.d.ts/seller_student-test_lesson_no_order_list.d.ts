interface GargsStatic {
	page_num:	number;
	grade:	number;//App\Enums\Egrade 
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
interface RowData {
	userid	:any;
	phone	:any;
	last_revisit_admin_time	:any;
	last_revisit_adminid	:any;
	lesson_start	:any;
	gender	:any;
	grade	:any;
	nick	:any;
	grade_str	:any;
	gender_str	:any;
	phone_hide	:any;
}

/*

tofile: 
	 mkdir -p ../seller_student; vi  ../seller_student/test_lesson_no_order_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student-test_lesson_no_order_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			grade:	$('#id_grade').val()
        });
    }

	Enum_map.append_option_list("grade",$("#id_grade")); 

	$('#id_grade').val(g_args.grade);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">年级</span>
                <select class="opt-change form-control" id="id_grade" >
                </select>
            </div>
        </div>
*/
