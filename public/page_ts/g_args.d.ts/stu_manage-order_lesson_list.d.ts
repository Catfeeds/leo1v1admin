interface GargsStatic {
	sid:	number;
	type:	number;
	competition_flag:	number;
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
	orderid	:any;
	lessonid	:any;
	lesson_start	:any;
	lesson_end	:any;
	teacherid	:any;
	grade	:any;
	price	:any;
	lesson_count	:any;
	subject	:any;
	lesson_cancel_reason_type	:any;
	lesson_cancel_reason_next_lesson_time	:any;
	confirm_adminid	:any;
	confirm_time	:any;
	confirm_reason	:any;
	confirm_flag	:any;
	contract_type	:any;
	lesson_time	:any;
	tea_nick	:any;
	grade_str	:any;
	subject_str	:any;
	contract_type_str	:any;
	confirm_flag_str	:any;
}

/*

tofile: 
	 mkdir -p ../stu_manage; vi  ../stu_manage/order_lesson_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/stu_manage-order_lesson_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			sid:	$('#id_sid').val(),
			type:	$('#id_type').val(),
			competition_flag:	$('#id_competition_flag').val()
        });
    }


	$('#id_sid').val(g_args.sid);
	$('#id_type').val(g_args.type);
	$('#id_competition_flag').val(g_args.competition_flag);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">sid</span>
                <input class="opt-change form-control" id="id_sid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">type</span>
                <input class="opt-change form-control" id="id_type" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">competition_flag</span>
                <input class="opt-change form-control" id="id_competition_flag" />
            </div>
        </div>
*/
