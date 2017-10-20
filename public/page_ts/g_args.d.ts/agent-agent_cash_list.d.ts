interface GargsStatic {
	cash:	number;
	type:	number;
	page_num:	number;
	page_count:	number;
	agent_check_money_flag:	number;//App\Enums\Eagent_check_money_flag
	phone:	string;
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
	aid	:any;
	cash	:any;
	is_suc_flag	:any;
	create_time	:any;
	type	:any;
	check_money_flag	:any;
	check_money_adminid	:any;
	check_money_time	:any;
	check_money_desc	:any;
	nickname	:any;
	phone	:any;
	bankcard	:any;
	bank_type	:any;
	bank_account	:any;
	bank_address	:any;
	bank_phone	:any;
	bank_province	:any;
	bank_city	:any;
	zfb_name	:any;
	zfb_account	:any;
	all_yxyx_money	:any;
	all_open_cush_money	:any;
	all_have_cush_money	:any;
	agent_check_money_flag	:any;
	check_money_admin_nick	:any;
	agent_check_money_flag_str	:any;
}

/*

tofile: 
	 mkdir -p ../agent; vi  ../agent/agent_cash_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/agent-agent_cash_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			cash:	$('#id_cash').val(),
			type:	$('#id_type').val(),
			agent_check_money_flag:	$('#id_agent_check_money_flag').val(),
			phone:	$('#id_phone').val()
        });
    }

	Enum_map.append_option_list("agent_check_money_flag",$("#id_agent_check_money_flag"));

	$('#id_cash').val(g_args.cash);
	$('#id_type').val(g_args.type);
	$('#id_agent_check_money_flag').val(g_args.agent_check_money_flag);
	$('#id_phone').val(g_args.phone);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">cash</span>
                <input class="opt-change form-control" id="id_cash" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">type</span>
                <input class="opt-change form-control" id="id_type" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">agent_check_money_flag</span>
                <select class="opt-change form-control" id="id_agent_check_money_flag" >
                </select>
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">phone</span>
                <input class="opt-change form-control" id="id_phone" />
            </div>
        </div>
*/
