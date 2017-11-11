interface GargsStatic {
	teacher_money_type:	number;
	teacherid:	number;
	batch:	number;
	not_start:	number;
	not_end:	number;
	status:	number;
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
	teacher_money_type	:any;
	level	:any;
	new_level	:any;
	batch	:any;
	status	:any;
	realname	:any;
	put_time	:any;
	confirm_time	:any;
	new_teacher_money_type	:any;
	all_money_different	:any;
	base_money_different	:any;
	lesson_total	:any;
	month_time	:any;
	num	:any;
	teacher_money_type_str	:any;
	level_str	:any;
	new_level_str	:any;
	batch_str	:any;
	status_str	:any;
	time_str	:any;
	per_money_different	:any;
}

/*

tofile: 
	 mkdir -p ../teacher_level; vi  ../teacher_level/teacher_switch_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/teacher_level-teacher_switch_list.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
		teacher_money_type:	$('#id_teacher_money_type').val(),
		teacherid:	$('#id_teacherid').val(),
		batch:	$('#id_batch').val(),
		not_start:	$('#id_not_start').val(),
		not_end:	$('#id_not_end').val(),
		status:	$('#id_status').val()
    });
}
$(function(){


	$('#id_teacher_money_type').val(g_args.teacher_money_type);
	$('#id_teacherid').val(g_args.teacherid);
	$('#id_batch').val(g_args.batch);
	$('#id_not_start').val(g_args.not_start);
	$('#id_not_end').val(g_args.not_end);
	$('#id_status').val(g_args.status);


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

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">teacherid</span>
                <input class="opt-change form-control" id="id_teacherid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">batch</span>
                <input class="opt-change form-control" id="id_batch" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">not_start</span>
                <input class="opt-change form-control" id="id_not_start" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">not_end</span>
                <input class="opt-change form-control" id="id_not_end" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">status</span>
                <input class="opt-change form-control" id="id_status" />
            </div>
        </div>
*/
