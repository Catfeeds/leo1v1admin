interface GargsStatic {
	start_date:	string;
	end_date:	string;
	contract_type:	number;
	studentid:	number;
	check_money_flag:	number;
	page_num:	number;
	page_count:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	money_all	:any;
	order_count	:any;
	user_count	:any;
	new_price	:any;
	test_count	:any;
	test_user_count	:any;
	al_count	:any;
	revisited_yi	:any;
	revisited_wei	:any;
	no_call	:any;
	revisited_wuxiao	:any;
	key1	:any;
	key2	:any;
	key3	:any;
	key4	:any;
	key1_class	:any;
	key2_class	:any;
	key3_class	:any;
	key4_class	:any;
	level	:any;
}

/*

tofile: 
	 mkdir -p ../seller_student; vi  ../seller_student/money_contract_list_for_origin.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student-money_contract_list_for_origin.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			start_date:	$('#id_start_date').val(),
			end_date:	$('#id_end_date').val(),
			contract_type:	$('#id_contract_type').val(),
			studentid:	$('#id_studentid').val(),
			check_money_flag:	$('#id_check_money_flag').val()
        });
    }


	$('#id_start_date').val(g_args.start_date);
	$('#id_end_date').val(g_args.end_date);
	$('#id_contract_type').val(g_args.contract_type);
	$('#id_studentid').val(g_args.studentid);
	$('#id_check_money_flag').val(g_args.check_money_flag);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">start_date</span>
                <input class="opt-change form-control" id="id_start_date" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">end_date</span>
                <input class="opt-change form-control" id="id_end_date" />
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
*/
