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

$(function(){
    function load_data(){
        $.reload_self_page ( {
			student_type:	$('#id_student_type').val(),
			teacherid:	$('#id_teacherid').val()
        });
    }


	$('#id_student_type').val(g_args.student_type);
	$('#id_teacherid').val(g_args.teacherid);


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

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">teacherid</span>
                <input class="opt-change form-control" id="id_teacherid" />
            </div>
        </div>
*/
