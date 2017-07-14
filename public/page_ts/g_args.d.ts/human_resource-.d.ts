interface GargsStatic {
	teacherid:	number;
	teacher_money_type:	number;
	page_num:	number;
	need_test_lesson_flag:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
interface RowData {
	need_test_lesson_flag	:any;
	nick	:any;
	realname	:any;
	teacher_type	:any;
	gender	:any;
	teacher_money_type	:any;
	birth	:any;
	phone	:any;
	email	:any;
	rate_score	:any;
	teacherid	:any;
	user_agent	:any;
	teacher_tags	:any;
	teacher_textbook	:any;
	create_meeting	:any;
	level	:any;
	work_year	:any;
	advantage	:any;
	base_intro	:any;
	teacher_type_str	:any;
	need_test_lesson_flag_str	:any;
	gender_str	:any;
	age	:any;
	level_str	:any;
	teacher_money_type_str	:any;
}

/*

tofile: 
	 mkdir -p ../human_resource ; vi  ../human_resource/.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/human_resource-.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			teacherid:	$('#id_teacherid').val(),
			teacher_money_type:	$('#id_teacher_money_type').val(),
			need_test_lesson_flag:	$('#id_need_test_lesson_flag').val()
        });
    }


	$('#id_teacherid').val(g_args.teacherid);
	$('#id_teacher_money_type').val(g_args.teacher_money_type);
	$('#id_need_test_lesson_flag').val(g_args.need_test_lesson_flag);


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
                <span class="input-group-addon">teacher_money_type</span>
                <input class="opt-change form-control" id="id_teacher_money_type" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">need_test_lesson_flag</span>
                <input class="opt-change form-control" id="id_need_test_lesson_flag" />
            </div>
        </div>
*/
