interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	select_type:	number;
	student_type:	number;
	renewal_rate:	number;
	month_cost:	number;
	month_cost_ex:	number;
	order_str:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	nick	:any;
	userid	:any;
	subject_str	:any;
	lesson_total	:any;
	lesson_cost	:any;
	lesson_left	:any;
	renewal_day	:any;
}

/*

tofile: 
	 mkdir -p ../tongji_ss; vi  ../tongji_ss/stu_lesson_total_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji_ss-stu_lesson_total_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type_config:	$('#id_date_type_config').val(),
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
			select_type:	$('#id_select_type').val(),
			student_type:	$('#id_student_type').val(),
			renewal_rate:	$('#id_renewal_rate').val(),
			month_cost:	$('#id_month_cost').val(),
			month_cost_ex:	$('#id_month_cost_ex').val(),
			order_str:	$('#id_order_str').val()
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
	$('#id_select_type').val(g_args.select_type);
	$('#id_student_type').val(g_args.student_type);
	$('#id_renewal_rate').val(g_args.renewal_rate);
	$('#id_month_cost').val(g_args.month_cost);
	$('#id_month_cost_ex').val(g_args.month_cost_ex);
	$('#id_order_str').val(g_args.order_str);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">select_type</span>
                <input class="opt-change form-control" id="id_select_type" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">student_type</span>
                <input class="opt-change form-control" id="id_student_type" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">renewal_rate</span>
                <input class="opt-change form-control" id="id_renewal_rate" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">month_cost</span>
                <input class="opt-change form-control" id="id_month_cost" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">month_cost_ex</span>
                <input class="opt-change form-control" id="id_month_cost_ex" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">order_str</span>
                <input class="opt-change form-control" id="id_order_str" />
            </div>
        </div>
*/
