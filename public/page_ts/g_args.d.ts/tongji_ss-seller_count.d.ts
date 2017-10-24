interface GargsStatic {
	grade:	string;//枚举列表: App\Enums\Egrade
 	group_adminid:	number;
	order_by_str:	string;
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	origin_ex:	string;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	admin_revisiterid	:any;
	all_count	:any;
	all_count_0	:any;
	all_count_1	:any;
	no_call	:any;
	no_call_0	:any;
	no_call_1	:any;
	call_count	:any;
	all_account	:any;
	invalid_count	:any;
	no_connect	:any;
	valid_count	:any;
	test_lesson_count	:any;
	fail_need_pay_count	:any;
	require_test_count	:any;
	succ_all_count	:any;
	fail_all_count	:any;
	order_count	:any;
	order_money	:any;
	global_tq_no_call	:any;
	new_user_count	:any;
	assigned_count	:any;
	get_histroy_count	:any;
	main_type	:any;
	up_group_name	:any;
	group_name	:any;
	account	:any;
	main_type_class	:any;
	up_group_name_class	:any;
	group_name_class	:any;
	account_class	:any;
	level	:any;
	main_type_str	:any;
}

/*

tofile: 
	 mkdir -p ../tongji_ss; vi  ../tongji_ss/seller_count.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji_ss-seller_count.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
		grade:	$('#id_grade').val(),
		group_adminid:	$('#id_group_adminid').val(),
		order_by_str:	$('#id_order_by_str').val(),
		date_type_config:	$('#id_date_type_config').val(),
		date_type:	$('#id_date_type').val(),
		opt_date_type:	$('#id_opt_date_type').val(),
		start_time:	$('#id_start_time').val(),
		end_time:	$('#id_end_time').val(),
		origin_ex:	$('#id_origin_ex').val()
    });
}
$(function(){


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
	$('#id_grade').val(g_args.grade);
	$.enum_multi_select( $('#id_grade'), 'grade', function(){load_data();} )
	$('#id_group_adminid').val(g_args.group_adminid);
	$('#id_order_by_str').val(g_args.order_by_str);
	$('#id_origin_ex').val(g_args.origin_ex);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">grade</span>
                <input class="opt-change form-control" id="id_grade" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">group_adminid</span>
                <input class="opt-change form-control" id="id_group_adminid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">order_by_str</span>
                <input class="opt-change form-control" id="id_order_by_str" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">origin_ex</span>
                <input class="opt-change form-control" id="id_origin_ex" />
            </div>
        </div>
*/
