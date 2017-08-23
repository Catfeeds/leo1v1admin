interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	teacher_id:	number;
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
	teacherid	:any;
	realname	:any;
	teacher_money_type_str	:any;
	teacher_money_type_simulate_str	:any;
	level_str	:any;
	level_simulate_str	:any;
	money	:any;
	money_simulate	:any;
	reward	:any;
	reward_simulate	:any;
	lesson_price	:any;
	lesson_count	:any;
	lesson_price_simulate	:any;
}

/*

tofile: 
	 mkdir -p ../teacher_simulate; vi  ../teacher_simulate/new_teacher_money_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/teacher_simulate-new_teacher_money_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type_config:	$('#id_date_type_config').val(),
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
			teacher_id:	$('#id_teacher_id').val(),
			teacher_money_type:	$('#id_teacher_money_type').val(),
			level:	$('#id_level').val()
        });
    }


    $('#id_date_range').select_date_range({
        'date_type' : g_args.date_type,
        'opt_date_type' : g_args.opt_date_type,
        'start_time'    : g_args.start_time,
        'end_time'      : g_args.end_time,
        date_type_config : JSON.parse( g_args.date_type_config),
        onQuery :function() {
            load_data();
        }
    });
	$('#id_teacher_id').val(g_args.teacher_id);
	$('#id_teacher_money_type').val(g_args.teacher_money_type);
	$('#id_level').val(g_args.level);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">teacher_id</span>
                <input class="opt-change form-control" id="id_teacher_id" />
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
                <span class="input-group-addon">level</span>
                <input class="opt-change form-control" id="id_level" />
            </div>
        </div>
*/
