interface GargsStatic {
	is_full_time:	number;
	teacher_money_type:	number;
	page_num:	number;
	page_count:	number;
	assistantid:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	teacher_nick	:any;
	teacher_money_type_str	:any;
	work_time	:any;
	lesson_leavel_rate	:any;
	lesson_come_late_rate	:any;
	lesson_cut_class_rate	:any;
	lesson_change_rate	:any;
	index_num	:any;
	stu_num	:any;
}

/*

tofile: 
	 mkdir -p ../test_sam; vi  ../test_sam/tongji_change_lesson_for_jy.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/test_sam-tongji_change_lesson_for_jy.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
		is_full_time:	$('#id_is_full_time').val(),
		teacher_money_type:	$('#id_teacher_money_type').val(),
		assistantid:	$('#id_assistantid').val()
    });
}
$(function(){


	$('#id_is_full_time').val(g_args.is_full_time);
	$('#id_teacher_money_type').val(g_args.teacher_money_type);
	$('#id_assistantid').val(g_args.assistantid);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">is_full_time</span>
                <input class="opt-change form-control" id="id_is_full_time" />
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
                <span class="input-group-addon">assistantid</span>
                <input class="opt-change form-control" id="id_assistantid" />
            </div>
        </div>
*/
