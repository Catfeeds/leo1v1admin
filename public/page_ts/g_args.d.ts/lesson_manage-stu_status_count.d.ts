interface GargsStatic {
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
	id	:any;
	log_date	:any;
	new_course_count	:any;
	old_course_count	:any;
	new_lesson_count	:any;
	old_lesson_count	:any;
	test_lesson_count	:any;
	money	:any;
	real_money	:any;
	test_free_count	:any;
	test_money_count	:any;
	test_money	:any;
	new_count	:any;
	next_count	:any;
	old_count	:any;
	stop_count	:any;
	finish_count	:any;
	teacher_count	:any;
}

/*

tofile: 
	 mkdir -p ../lesson_manage; vi  ../lesson_manage/stu_status_count.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/lesson_manage-stu_status_count.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		start_date:	$('#id_start_date').val(),
		end_date:	$('#id_end_date').val()
		});
}
$(function(){


	$('#id_start_date').val(g_args.start_date);
	$('#id_end_date').val(g_args.end_date);


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
{!!\App\Helper\Utils::th_order_gen([["start_date title", "start_date", "th_start_date" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">end_date</span>
                <input class="opt-change form-control" id="id_end_date" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["end_date title", "end_date", "th_end_date" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_num title", "page_num", "th_page_num" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_count title", "page_count", "th_page_count" ]])!!}
*/
