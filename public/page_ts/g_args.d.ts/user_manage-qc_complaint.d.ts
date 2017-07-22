interface GargsStatic {
	page_num:	number;
	page_count:	number;
	is_complaint_state:	number;
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
	complaint_id	:any;
	complaint_type	:any;
	userid	:any;
	account_type	:any;
	complaint_info	:any;
	add_time	:any;
	current_adminid	:any;
	current_account	:any;
	complaint_state	:any;
	current_admin_assign_time	:any;
	complained_adminid	:any;
	complained_adminid_type	:any;
	complained_adminid_nick	:any;
	suggest_info	:any;
	deal_info	:any;
	deal_time	:any;
	deal_adminid	:any;
	complaint_type_str	:any;
	complained_adminid_type_str	:any;
	complaint_state_str	:any;
	account_type_str	:any;
	deal_date	:any;
	complaint_date	:any;
	current_admin_assign_time_date	:any;
	deal_admin_nick	:any;
	user_nick	:any;
	phone	:any;
	follow_state_str	:any;
}

/*

tofile: 
	 mkdir -p ../user_manage; vi  ../user_manage/qc_complaint.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage-qc_complaint.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			is_complaint_state:	$('#id_is_complaint_state').val(),
			date_type_config:	$('#id_date_type_config').val(),
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
	$('#id_is_complaint_state').val(g_args.is_complaint_state);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">is_complaint_state</span>
                <input class="opt-change form-control" id="id_is_complaint_state" />
            </div>
        </div>
*/
