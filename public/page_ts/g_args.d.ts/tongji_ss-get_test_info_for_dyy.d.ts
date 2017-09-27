interface GargsStatic {
	date_type_config:	string;
	page_num:	number;
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
declare var g_account_role: string;
declare var g_adminid: string;
interface RowData {
	cur_require_adminid	:any;
	account	:any;
	userid	:any;
	nick	:any;
	lessonid	:any;
	success_flag	:any;
	phone	:any;
	grade	:any;
	origin	:any;
	phone_location	:any;
	reg_time	:any;
	order_time	:any;
	price	:any;
	lesson_total	:any;
	teacherid	:any;
	realname	:any;
	lesson_start	:any;
	subject	:any;
	add_time	:any;
	account_role	:any;
	grade_str	:any;
}

/*

tofile: 
	 mkdir -p ../tongji_ss; vi  ../tongji_ss/get_test_info_for_dyy.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji_ss-get_test_info_for_dyy.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val()
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


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...
*/
