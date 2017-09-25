interface GargsStatic {
	start_time:	string;
	end_time:	string;
	contract_type:	number;
	is_test_user:	number;//App\Enums\Eboolean
	studentid:	number;
	check_money_flag:	number;
	origin:	string;
	from_type:	number;
	account_role:	number;
	sys_operator:	string;
	seller_groupid_ex:	string;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
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
	del_flag	:any;
	del_flag_str	:any;
}

/*

tofile: 
	 mkdir -p ../tongji_ss; vi  ../tongji_ss/contract_count.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji_ss-contract_count.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
			contract_type:	$('#id_contract_type').val(),
			is_test_user:	$('#id_is_test_user').val(),
			studentid:	$('#id_studentid').val(),
			check_money_flag:	$('#id_check_money_flag').val(),
			origin:	$('#id_origin').val(),
			from_type:	$('#id_from_type').val(),
			account_role:	$('#id_account_role').val(),
			sys_operator:	$('#id_sys_operator').val(),
			seller_groupid_ex:	$('#id_seller_groupid_ex').val()
        });
    }

	Enum_map.append_option_list("boolean",$("#id_is_test_user"));

	$('#id_start_time').val(g_args.start_time);
	$('#id_end_time').val(g_args.end_time);
	$('#id_contract_type').val(g_args.contract_type);
	$('#id_is_test_user').val(g_args.is_test_user);
	$('#id_studentid').val(g_args.studentid);
	$('#id_check_money_flag').val(g_args.check_money_flag);
	$('#id_origin').val(g_args.origin);
	$('#id_from_type').val(g_args.from_type);
	$('#id_account_role').val(g_args.account_role);
	$('#id_sys_operator').val(g_args.sys_operator);
	$('#id_seller_groupid_ex').val(g_args.seller_groupid_ex);


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
                <span class="input-group-addon">contract_type</span>
                <input class="opt-change form-control" id="id_contract_type" />
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
                <span class="input-group-addon">seller_groupid_ex</span>
                <input class="opt-change form-control" id="id_seller_groupid_ex" />
            </div>
        </div>
*/
