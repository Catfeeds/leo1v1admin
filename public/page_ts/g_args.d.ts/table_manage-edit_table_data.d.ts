interface GargsStatic {
	db_name:	string;
	table_name:	string;
	id1:	string;
	id2:	string;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	k	:any;
	v	:any;
	comment	:any;
}

/*

tofile: 
	 mkdir -p ../table_manage; vi  ../table_manage/edit_table_data.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/table_manage-edit_table_data.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		db_name:	$('#id_db_name').val(),
		table_name:	$('#id_table_name').val(),
		id1:	$('#id_id1').val(),
		id2:	$('#id_id2').val()
		});
}
$(function(){


	$('#id_db_name').val(g_args.db_name);
	$('#id_table_name').val(g_args.table_name);
	$('#id_id1').val(g_args.id1);
	$('#id_id2').val(g_args.id2);


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

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">table_name</span>
                <input class="opt-change form-control" id="id_table_name" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">id1</span>
                <input class="opt-change form-control" id="id_id1" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">id2</span>
                <input class="opt-change form-control" id="id_id2" />
            </div>
        </div>
*/
