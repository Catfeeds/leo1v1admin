interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	require_test_count_for_month	:any;
	suc_lesson_count_one	:any;
	suc_lesson_count_two	:any;
	suc_lesson_count_three	:any;
	suc_lesson_count_four	:any;
	suc_lesson_count_one_rate	:any;
	suc_lesson_count_two_rate	:any;
	suc_lesson_count_three_rate	:any;
	suc_lesson_count_four_rate	:any;
	suc_lesson_count_rate_all	:any;
	suc_lesson_count_rate	:any;
	main_type	:any;
	first_group_name	:any;
	up_group_name	:any;
	group_name	:any;
	account	:any;
	main_type_class	:any;
	first_group_name_class	:any;
	up_group_name_class	:any;
	group_name_class	:any;
	account_class	:any;
	level	:any;
	become_member_time	:any;
	leave_member_time	:any;
	del_flag	:any;
	main_type_str	:any;
	seller_level_str	:any;
	lesson_per	:any;
	kpi	:any;
	order_per	:any;
	finish_per	:any;
	finish_personal_per	:any;
	duration_count_for_day	:any;
	ave_price_for_month	:any;
	los_money	:any;
	los_personal_money	:any;
	del_flag_str	:any;
	become_member_num	:any;
	leave_member_num	:any;
}

/*

tofile: 
	 mkdir -p ../user_manage_new; vi  ../user_manage_new/seller_tongji_report_info.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-seller_tongji_report_info.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
		date_type_config:	$('#id_date_type_config').val(),
		date_type:	$('#id_date_type').val(),
		opt_date_type:	$('#id_opt_date_type').val(),
		start_time:	$('#id_start_time').val(),
		end_time:	$('#id_end_time').val()
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


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...
*/
