interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	change_type:	number;
	record_type:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	add_time	:any;
	acc	:any;
	teacherid	:any;
	limit_plan_lesson_type	:any;
	seller_require_flag	:any;
	limit_plan_lesson_type_old	:any;
	realname	:any;
	limit_week_lesson_num_new	:any;
	limit_week_lesson_num_old	:any;
	lesson_info	:any;
	lesson_flag	:any;
	order_info	:any;
	order_flag	:any;
	limit_plan_lesson_type_str	:any;
	limit_plan_lesson_type_old_str	:any;
	add_time_str	:any;
}

/*

tofile: 
	 mkdir -p ../tongji_ss; vi  ../tongji_ss/get_seller_require_modify_info.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji_ss-get_seller_require_modify_info.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type_config:	$('#id_date_type_config').val(),
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
			change_type:	$('#id_change_type').val(),
			record_type:	$('#id_record_type').val()
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
	$('#id_change_type').val(g_args.change_type);
	$('#id_record_type').val(g_args.record_type);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">change_type</span>
                <input class="opt-change form-control" id="id_change_type" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">record_type</span>
                <input class="opt-change form-control" id="id_record_type" />
            </div>
        </div>
*/
