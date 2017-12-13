interface GargsStatic {
	teacher:	number;
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
	teacherid	:any;
	five_num	:any;
	fift_num	:any;
	leave_num	:any;
	absent_num	:any;
	adjust_num	:any;
	ask_leave_num	:any;
	big_order_num	:any;
	nick	:any;
	all	:any;
}

/*

tofile: 
	 mkdir -p ../teacher_warn; vi  ../teacher_warn/tea_warn_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/teacher_warn-tea_warn_list.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		teacher:	$('#id_teacher').val()
		});
}
$(function(){


	$('#id_teacher').val(g_args.teacher);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">teacher</span>
                <input class="opt-change form-control" id="id_teacher" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["teacher title", "teacher", "th_teacher" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_num title", "page_num", "th_page_num" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_count title", "page_count", "th_page_count" ]])!!}
*/
