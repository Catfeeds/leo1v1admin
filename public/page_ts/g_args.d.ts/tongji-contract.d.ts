interface GargsStatic {
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	stu_from_type:	number;
	contract_type:	number;//App\Enums\Econtract_type
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	title	:any;
	order_count	:any;
	money	:any;
}

/*

tofile: 
	 mkdir -p ../tongji; vi  ../tongji/contract.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji-contract.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
			stu_from_type:	$('#id_stu_from_type').val(),
			contract_type:	$('#id_contract_type').val()
        });
    }

	Enum_map.append_option_list("contract_type",$("#id_contract_type"));

	$('#id_opt_date_type').val(g_args.opt_date_type);
	$('#id_start_time').val(g_args.start_time);
	$('#id_end_time').val(g_args.end_time);
	$('#id_stu_from_type').val(g_args.stu_from_type);
	$('#id_contract_type').val(g_args.contract_type);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">opt_date_type</span>
                <input class="opt-change form-control" id="id_opt_date_type" />
            </div>
        </div>

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
                <span class="input-group-addon">stu_from_type</span>
                <input class="opt-change form-control" id="id_stu_from_type" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">contract_type</span>
                <select class="opt-change form-control" id="id_contract_type" >
                </select>
            </div>
        </div>
*/
