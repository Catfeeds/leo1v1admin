interface GargsStatic {
	teacherid:	number;
	assistantid:	number;
	start_time:	string;
	end_time:	string;
	courseid:	number;
	page_num:	number;
	group_flag:	string;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
interface RowData {
	courseid	:any;
	course_name	:any;
	lesson_open	:any;
	teacherid	:any;
	nick	:any;
	subject	:any;
	grade	:any;
	lesson_total	:any;
	assistantid	:any;
	lesson_left	:any;
	stu_total	:any;
	teacher_nick	:any;
	assistant_nick	:any;
	grade_str	:any;
	subject_str	:any;
	stu_current	:any;
}

/*

tofile: 
	 mkdir -p ../small_class ; vi  ../small_class/index_ass.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/small_class-index_ass.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			teacherid:	$('#id_teacherid').val(),
			assistantid:	$('#id_assistantid').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
			courseid:	$('#id_courseid').val(),
			group_flag:	$('#id_group_flag').val()
        });
    }


	$('#id_teacherid').val(g_args.teacherid);
	$('#id_assistantid').val(g_args.assistantid);
	$('#id_start_time').val(g_args.start_time);
	$('#id_end_time').val(g_args.end_time);
	$('#id_courseid').val(g_args.courseid);
	$('#id_group_flag').val(g_args.group_flag);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">teacherid</span>
                <input class="opt-change form-control" id="id_teacherid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">assistantid</span>
                <input class="opt-change form-control" id="id_assistantid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">start_time</span>
                <input class="opt-change form-control" id="id_start_time" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">end_time</span>
                <input class="opt-change form-control" id="id_end_time" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">courseid</span>
                <input class="opt-change form-control" id="id_courseid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">group_flag</span>
                <input class="opt-change form-control" id="id_group_flag" />
            </div>
        </div>
*/
