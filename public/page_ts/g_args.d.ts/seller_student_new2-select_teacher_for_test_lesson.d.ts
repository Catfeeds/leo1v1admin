interface GargsStatic {
	require_id:	number;
	teacher_tags:	string;
	teaching_tags:	string;
	lesson_tags:	string;
	refresh_flag:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
}

/*

tofile: 
	 mkdir -p ../seller_student_new2; vi  ../seller_student_new2/select_teacher_for_test_lesson.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student_new2-select_teacher_for_test_lesson.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		require_id:	$('#id_require_id').val(),
		teacher_tags:	$('#id_teacher_tags').val(),
		teaching_tags:	$('#id_teaching_tags').val(),
		lesson_tags:	$('#id_lesson_tags').val(),
		refresh_flag:	$('#id_refresh_flag').val()
		});
}
$(function(){


	$('#id_require_id').val(g_args.require_id);
	$('#id_teacher_tags').val(g_args.teacher_tags);
	$('#id_teaching_tags').val(g_args.teaching_tags);
	$('#id_lesson_tags').val(g_args.lesson_tags);
	$('#id_refresh_flag').val(g_args.refresh_flag);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">require_id</span>
                <input class="opt-change form-control" id="id_require_id" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["require_id title", "require_id", "th_require_id" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">teacher_tags</span>
                <input class="opt-change form-control" id="id_teacher_tags" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["teacher_tags title", "teacher_tags", "th_teacher_tags" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">teaching_tags</span>
                <input class="opt-change form-control" id="id_teaching_tags" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["teaching_tags title", "teaching_tags", "th_teaching_tags" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">lesson_tags</span>
                <input class="opt-change form-control" id="id_lesson_tags" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["lesson_tags title", "lesson_tags", "th_lesson_tags" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">refresh_flag</span>
                <input class="opt-change form-control" id="id_refresh_flag" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["refresh_flag title", "refresh_flag", "th_refresh_flag" ]])!!}
*/
