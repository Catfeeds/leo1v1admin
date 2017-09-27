interface GargsStatic {
	courseid:	string;
	lessonid:	number;
	page_num:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
interface RowData {
	courseid	:any;
	lessonid	:any;
	userid	:any;
	teacherid	:any;
	assistantid	:any;
	lesson_start	:any;
	lesson_num	:any;
	stu_attend	:any;
	lesson_end	:any;
	teacher_score	:any;
	teacher_comment	:any;
	teacher_effect	:any;
	teacher_quality	:any;
	teacher_interact	:any;
	grade	:any;
	subject	:any;
	stu_score	:any;
	stu_comment	:any;
	stu_attitude	:any;
	stu_attention	:any;
	stu_ability	:any;
	stu_stability	:any;
	teacher_nick	:any;
	assistant_nick	:any;
	lesson_time	:any;
	subject_str	:any;
	grade_str	:any;
}

/*

tofile: 
	 mkdir -p ../small_class ; vi  ../small_class/lesson_list_new_ass.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/small_class-lesson_list_new_ass.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			courseid:	$('#id_courseid').val(),
			lessonid:	$('#id_lessonid').val()
        });
    }


	$('#id_courseid').val(g_args.courseid);
	$('#id_lessonid').val(g_args.lessonid);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">courseid</span>
                <input class="opt-change form-control" id="id_courseid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">lessonid</span>
                <input class="opt-change form-control" id="id_lessonid" />
            </div>
        </div>
*/
