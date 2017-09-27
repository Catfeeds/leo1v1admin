interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	account:	string;
	seller_work_status:	number;
	plan_seller_work_status:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: string;
declare var g_adminid: string;
interface RowData {
	month_time	:any;
	leave_and_overtime	:any;
	plan_do	:any;
	real_do	:any;
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
	plan_do_str	:any;
	real_do_str	:any;
}

/*

tofile: 
	 mkdir -p ../user_manage_new; vi  ../user_manage_new/seller_attendance_info.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-seller_attendance_info.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
			account:	$('#id_account').val(),
			seller_work_status:	$('#id_seller_work_status').val(),
			plan_seller_work_status:	$('#id_plan_seller_work_status').val()
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
	$('#id_account').val(g_args.account);
	$('#id_seller_work_status').val(g_args.seller_work_status);
	$('#id_plan_seller_work_status').val(g_args.plan_seller_work_status);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">account</span>
                <input class="opt-change form-control" id="id_account" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">seller_work_status</span>
                <input class="opt-change form-control" id="id_seller_work_status" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">plan_seller_work_status</span>
                <input class="opt-change form-control" id="id_plan_seller_work_status" />
            </div>
        </div>
*/
