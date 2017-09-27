interface GargsStatic {
	start_date:	string;
	end_date:	string;
	teacherid:	number;
	lesson_status:	number;
	courseid:	number;
	page_num:	number;
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
	 mkdir -p ../tea_manage ; vi  ../tea_manage/robot_open_class.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tea_manage-robot_open_class.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			start_date:	$('#id_start_date').val(),
			end_date:	$('#id_end_date').val(),
			teacherid:	$('#id_teacherid').val(),
			lesson_status:	$('#id_lesson_status').val(),
			courseid:	$('#id_courseid').val()
        });
    }


	$('#id_start_date').val(g_args.start_date);
	$('#id_end_date').val(g_args.end_date);
	$('#id_teacherid').val(g_args.teacherid);
	$('#id_lesson_status').val(g_args.lesson_status);
	$('#id_courseid').val(g_args.courseid);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

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

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">teacherid</span>
                <input class="opt-change form-control" id="id_teacherid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">lesson_status</span>
                <input class="opt-change form-control" id="id_lesson_status" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">courseid</span>
                <input class="opt-change form-control" id="id_courseid" />
            </div>
        </div>
*/
