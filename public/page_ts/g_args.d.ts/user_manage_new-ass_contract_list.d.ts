interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	studentid:	number;
	check_money_flag:	number;
	have_init:	number;
	have_master:	number;
	assistantid:	number;
	page_num:	number;
	page_count:	number;
	contract_type:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	order_stamp_flag	:any;
	flowid	:any;
	flow_status	:any;
	flow_post_msg	:any;
	teacherid	:any;
	tmk_adminid	:any;
	user_agent	:any;
	orderid	:any;
	order_time	:any;
	stu_from_type	:any;
	is_new_stu	:any;
	contractid	:any;
	contract_type	:any;
	contract_status	:any;
	invoice	:any;
	is_invoice	:any;
	contract_starttime	:any;
	taobao_orderid	:any;
	default_lesson_count	:any;
	contract_endtime	:any;
	grade	:any;
	lesson_total	:any;
	price	:any;
	discount_price	:any;
	discount_reason	:any;
	phone_location	:any;
	userid	:any;
	competition_flag	:any;
	lesson_left	:any;
	address	:any;
	origin_userid	:any;
	stu_nick	:any;
	ass_assign_time	:any;
	subject	:any;
	stu_self_nick	:any;
	parent_nick	:any;
	phone	:any;
	origin	:any;
	sys_operator	:any;
	from_type	:any;
	config_lesson_account_id	:any;
	config_courseid	:any;
	check_money_flag	:any;
	check_money_time	:any;
	check_money_adminid	:any;
	check_money_desc	:any;
	assistantid	:any;
	init_info_pdf_url	:any;
	title	:any;
	need_receipt	:any;
	order_promotion_type	:any;
	promotion_discount_price	:any;
	promotion_present_lesson	:any;
	promotion_spec_discount	:any;
	promotion_spec_present_lesson	:any;
	contract_type_str	:any;
	from_type_str	:any;
	check_money_flag_str	:any;
	assistant_nick	:any;
	init_info_pdf_url_str	:any;
}

/*

tofile: 
	 mkdir -p ../user_manage_new; vi  ../user_manage_new/ass_contract_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-ass_contract_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type_config:	$('#id_date_type_config').val(),
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
			studentid:	$('#id_studentid').val(),
			check_money_flag:	$('#id_check_money_flag').val(),
			have_init:	$('#id_have_init').val(),
			have_master:	$('#id_have_master').val(),
			assistantid:	$('#id_assistantid').val(),
			contract_type:	$('#id_contract_type').val()
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
	$('#id_studentid').val(g_args.studentid);
	$('#id_check_money_flag').val(g_args.check_money_flag);
	$('#id_have_init').val(g_args.have_init);
	$('#id_have_master').val(g_args.have_master);
	$('#id_assistantid').val(g_args.assistantid);
	$('#id_contract_type').val(g_args.contract_type);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">studentid</span>
                <input class="opt-change form-control" id="id_studentid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">check_money_flag</span>
                <input class="opt-change form-control" id="id_check_money_flag" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">have_init</span>
                <input class="opt-change form-control" id="id_have_init" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">have_master</span>
                <input class="opt-change form-control" id="id_have_master" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">assistantid</span>
                <input class="opt-change form-control" id="id_assistantid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">contract_type</span>
                <input class="opt-change form-control" id="id_contract_type" />
            </div>
        </div>
*/
