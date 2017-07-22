interface GargsStatic {
	sid:	number;
	page_num:	number;
	page_count:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	lessonid	:any;
	lesson_start	:any;
	lesson_end	:any;
	grade	:any;
	subject	:any;
	teacherid	:any;
	lesson_status	:any;
	lesson_del_flag	:any;
	lesson_time	:any;
	lesson_status_str	:any;
	grade_str	:any;
	subject_str	:any;
	lesson_del_flag_str	:any;
	teacher_nick	:any;
}

/*

tofile: 
	 mkdir -p ../stu_manage; vi  ../stu_manage/test_lesson_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/stu_manage-test_lesson_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			sid:	$('#id_sid').val()
        });
    }


	$('#id_sid').val(g_args.sid);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">sid</span>
                <input class="opt-change form-control" id="id_sid" />
            </div>
        </div>
*/
