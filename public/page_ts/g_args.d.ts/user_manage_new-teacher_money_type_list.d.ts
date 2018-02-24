interface GargsStatic {
	teacher_money_type:	number;
	level:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	grade	:any;
	money	:any;
	grade_str	:any;
	money_0	:any;
	money_1	:any;
	money_2	:any;
	money_3	:any;
	money_4	:any;
	money_5	:any;
	money_6	:any;
}

/*

tofile: 
	 mkdir -p ../user_manage_new; vi  ../user_manage_new/teacher_money_type_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-teacher_money_type_list.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		teacher_money_type:	$('#id_teacher_money_type').val(),
		level:	$('#id_level').val()
		});
}
$(function(){


	$('#id_teacher_money_type').val(g_args.teacher_money_type);
	$('#id_level').val(g_args.level);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">teacher_money_type</span>
                <input class="opt-change form-control" id="id_teacher_money_type" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["teacher_money_type title", "teacher_money_type", "th_teacher_money_type" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">level</span>
                <input class="opt-change form-control" id="id_level" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["level title", "level", "th_level" ]])!!}
*/
