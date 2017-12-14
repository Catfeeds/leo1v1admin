interface GargsStatic {
	student_type:	number;
	teacherid:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	userid	:any;
	nick	:any;
	type	:any;
	parent_name	:any;
	phone	:any;
	grade	:any;
	lesson_count_all	:any;
	lesson_count_left	:any;
	last_lesson_time	:any;
	type_str	:any;
}

/*

tofile: 
	 mkdir -p ../user_manage_new; vi  ../user_manage_new/stu_all_info.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-stu_all_info.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		student_type:	$('#id_student_type').val(),
		teacherid:	$('#id_teacherid').val()
		});
}
$(function(){


	$('#id_student_type').val(g_args.student_type);
	$('#id_teacherid').admin_select_user_new({
		"user_type"    : "teacher",
		"select_value" : g_args.teacherid,
		"onChange"     : load_data,
		"th_input_id"  : "th_teacherid",
		"only_show_in_th_input"     : false,
		"can_select_all_flag"     : true
	});


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">student_type</span>
                <input class="opt-change form-control" id="id_student_type" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["student_type title", "student_type", "th_student_type" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">teacherid</span>
                <input class="opt-change form-control" id="id_teacherid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["teacherid title", "teacherid", "th_teacherid" ]])!!}
*/
