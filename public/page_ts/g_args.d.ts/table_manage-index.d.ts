interface GargsStatic {
	db_name:	string;
	table_name:	string;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	Field	:any;
	Type	:any;
	Collation	:any;
	Null	:any;
	Key	:any;
	Default	:any;
	Extra	:any;
	Privileges	:any;
	Comment	:any;
}

/*

tofile: 
	 mkdir -p ../table_manage; vi  ../table_manage/index.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/table_manage-index.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			db_name:	$('#id_db_name').val(),
			table_name:	$('#id_table_name').val()
        });
    }


	$('#id_db_name').val(g_args.db_name);
	$('#id_table_name').val(g_args.table_name);


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
*/
