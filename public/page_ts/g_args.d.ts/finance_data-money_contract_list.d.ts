interface GargsStatic {
	start_time:	string;
	end_time:	string;
	userid_flag:	number;
	contract_type:	number;
	contract_status:	string;//枚举列表: \App\Enums\Econtract_status
 	is_test_user:	number;//App\Enums\Eboolean
	can_period_flag:	number;
	studentid:	number;
	check_money_flag:	number;
	origin:	string;
	page_num:	number;
	page_count:	number;
	from_type:	number;
	account_role:	number;
	sys_operator:	string;
	need_receipt:	number;//App\Enums\Eboolean
	userid_stu:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
}

/*

tofile: 
	 mkdir -p ../finance_data; vi  ../finance_data/money_contract_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/finance_data-money_contract_list.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
		start_time:	$('#id_start_time').val(),
		end_time:	$('#id_end_time').val(),
		userid_flag:	$('#id_userid_flag').val(),
		contract_type:	$('#id_contract_type').val(),
		contract_status:	$('#id_contract_status').val(),
		is_test_user:	$('#id_is_test_user').val(),
		can_period_flag:	$('#id_can_period_flag').val(),
		studentid:	$('#id_studentid').val(),
		check_money_flag:	$('#id_check_money_flag').val(),
		origin:	$('#id_origin').val(),
		from_type:	$('#id_from_type').val(),
		account_role:	$('#id_account_role').val(),
		sys_operator:	$('#id_sys_operator').val(),
		need_receipt:	$('#id_need_receipt').val(),
		userid_stu:	$('#id_userid_stu').val()
    });
}
$(function(){

	Enum_map.append_option_list("boolean",$("#id_is_test_user"));
	Enum_map.append_option_list("boolean",$("#id_need_receipt"));

	$('#id_start_time').val(g_args.start_time);
	$('#id_end_time').val(g_args.end_time);
	$('#id_userid_flag').val(g_args.userid_flag);
	$('#id_contract_type').val(g_args.contract_type);
	$('#id_contract_status').val(g_args.contract_status);
	$.enum_multi_select( $('#id_contract_status'), 'contract_status', function(){load_data();} )
	$('#id_is_test_user').val(g_args.is_test_user);
	$('#id_can_period_flag').val(g_args.can_period_flag);
	$('#id_studentid').val(g_args.studentid);
	$('#id_check_money_flag').val(g_args.check_money_flag);
	$('#id_origin').val(g_args.origin);
	$('#id_from_type').val(g_args.from_type);
	$('#id_account_role').val(g_args.account_role);
	$('#id_sys_operator').val(g_args.sys_operator);
	$('#id_need_receipt').val(g_args.need_receipt);
	$('#id_userid_stu').val(g_args.userid_stu);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">start_time</span>
                <input class="opt-change form-control" id="id_start_time" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">end_time</span>
                <input class="opt-change form-control" id="id_end_time" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">userid_flag</span>
                <input class="opt-change form-control" id="id_userid_flag" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">contract_type</span>
                <input class="opt-change form-control" id="id_contract_type" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">contract_status</span>
                <input class="opt-change form-control" id="id_contract_status" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">boolean</span>
                <select class="opt-change form-control" id="id_is_test_user" >
                </select>
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">can_period_flag</span>
                <input class="opt-change form-control" id="id_can_period_flag" />
            </div>
        </div>

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
                <span class="input-group-addon">origin</span>
                <input class="opt-change form-control" id="id_origin" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">from_type</span>
                <input class="opt-change form-control" id="id_from_type" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">account_role</span>
                <input class="opt-change form-control" id="id_account_role" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">sys_operator</span>
                <input class="opt-change form-control" id="id_sys_operator" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">boolean</span>
                <select class="opt-change form-control" id="id_need_receipt" >
                </select>
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">userid_stu</span>
                <input class="opt-change form-control" id="id_userid_stu" />
            </div>
        </div>
*/
