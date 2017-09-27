interface GargsStatic {
	lesson_type:	number;
	teacherid:	number;
	start_date:	string;
	end_date:	string;
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
	courseid	:any;
	userid	:any;
	lesson_type	:any;
	lesson_start	:any;
	lesson_end	:any;
	tea_nick	:any;
	lesson_time	:any;
	lesson_type_str	:any;
	course_name	:any;
	user_login	:any;
	user_all	:any;
	user_rate	:any;
}

/*

tofile: 
	 mkdir -p ../tea_manage; vi  ../tea_manage/lesson_account.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tea_manage-lesson_account.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			lesson_type:	$('#id_lesson_type').val(),
			teacherid:	$('#id_teacherid').val(),
			start_date:	$('#id_start_date').val(),
			end_date:	$('#id_end_date').val()
        });
    }


	$('#id_lesson_type').val(g_args.lesson_type);
	$('#id_teacherid').val(g_args.teacherid);
	$('#id_start_date').val(g_args.start_date);
	$('#id_end_date').val(g_args.end_date);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">lesson_type</span>
                <input class="opt-change form-control" id="id_lesson_type" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">teacherid</span>
                <input class="opt-change form-control" id="id_teacherid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">start_date</span>
                <input class="opt-change form-control" id="id_start_date" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">end_date</span>
                <input class="opt-change form-control" id="id_end_date" />
            </div>
        </div>
*/
