interface GargsStatic {
	lessonid:	number;
	page_num:	number;
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
	studentid	:any;
	lesson_num	:any;
	lesson_start	:any;
	issue_url	:any;
	issue_time	:any;
	finish_url	:any;
	finish_time	:any;
	check_url	:any;
	check_time	:any;
	tea_research_url	:any;
	tea_research_time	:any;
	ass_research_url	:any;
	ass_research_time	:any;
	work_status	:any;
	student_nick	:any;
	work_status_str	:any;
}

/*

tofile: 
	 mkdir -p ../small_class; vi  ../small_class/student_list_new.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/small_class-student_list_new.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			lessonid:	$('#id_lessonid').val()
        });
    }


	$('#id_lessonid').val(g_args.lessonid);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">lessonid</span>
                <input class="opt-change form-control" id="id_lessonid" />
            </div>
        </div>
*/
