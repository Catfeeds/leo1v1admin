interface GargsStatic {
	seller_level:	number;
	define_date:	number;
	base_salary:	number;
	sup_salary:	number;
	per_salary:	number;
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
}

/*

tofile: 
	 mkdir -p ../seller_level_goal; vi  ../seller_level_goal/seller_level_salary_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_level_goal-seller_level_salary_list.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
		seller_level:	$('#id_seller_level').val(),
		define_date:	$('#id_define_date').val(),
		base_salary:	$('#id_base_salary').val(),
		sup_salary:	$('#id_sup_salary').val(),
		per_salary:	$('#id_per_salary').val()
    });
}
$(function(){


	$('#id_seller_level').val(g_args.seller_level);
	$('#id_define_date').val(g_args.define_date);
	$('#id_base_salary').val(g_args.base_salary);
	$('#id_sup_salary').val(g_args.sup_salary);
	$('#id_per_salary').val(g_args.per_salary);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">seller_level</span>
                <input class="opt-change form-control" id="id_seller_level" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">define_date</span>
                <input class="opt-change form-control" id="id_define_date" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">base_salary</span>
                <input class="opt-change form-control" id="id_base_salary" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">sup_salary</span>
                <input class="opt-change form-control" id="id_sup_salary" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">per_salary</span>
                <input class="opt-change form-control" id="id_per_salary" />
            </div>
        </div>
*/
