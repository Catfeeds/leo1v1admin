interface GargsStatic {
	page_num:	number;
	page_count:	number;
	deal_flag:	number;
	feedback_adminid:	number;
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
	id	:any;
	deal_flag	:any;
	feedback_adminid	:any;
	record_adminid	:any;
	describe	:any;
	lesson_url	:any;
	reason	:any;
	solution	:any;
	remark	:any;
	create_time	:any;
	stu_nick	:any;
	stu_phone	:any;
	stu_agent	:any;
	tea_nick	:any;
	tea_phone	:any;
	tea_agent	:any;
	stu_agent_simple	:any;
	tea_agent_simple	:any;
	feedback_nick	:any;
	record_nick	:any;
	deal_flag_str	:any;
}

/*

tofile: 
	 mkdir -p ../user_manage_new; vi  ../user_manage_new/product_info.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-product_info.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
		deal_flag:	$('#id_deal_flag').val(),
		feedback_adminid:	$('#id_feedback_adminid').val(),
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
	$('#id_deal_flag').val(g_args.deal_flag);
	$('#id_feedback_adminid').val(g_args.feedback_adminid);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">deal_flag</span>
                <input class="opt-change form-control" id="id_deal_flag" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">feedback_adminid</span>
                <input class="opt-change form-control" id="id_feedback_adminid" />
            </div>
        </div>
*/
