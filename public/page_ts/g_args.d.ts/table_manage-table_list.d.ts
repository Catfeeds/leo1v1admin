interface GargsStatic {
	db_name:	string;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	table_name	:any;
	table_comment	:any;
}

/*

tofile: 
	 mkdir -p ../table_manage; vi  ../table_manage/table_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/table_manage-table_list.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		db_name:	$('#id_db_name').val()
		});
}
$(function(){


	$('#id_db_name').val(g_args.db_name);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">db_name</span>
                <input class="opt-change form-control" id="id_db_name" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["db_name title", "db_name", "th_db_name" ]])!!}
*/
