interface GargsStatic {
	year:	number;
	month:	number;
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
	 mkdir -p ../user_manage_new; vi  ../user_manage_new/month_user_info.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-month_user_info.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			year:	$('#id_year').val(),
			month:	$('#id_month').val()
        });
    }


	$('#id_year').val(g_args.year);
	$('#id_month').val(g_args.month);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">year</span>
                <input class="opt-change form-control" id="id_year" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">month</span>
                <input class="opt-change form-control" id="id_month" />
            </div>
        </div>
*/
