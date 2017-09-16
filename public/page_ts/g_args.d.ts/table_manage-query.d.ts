interface GargsStatic {
	db_name:	string;
	sql:	string;
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
	zjs_unallot_count	:any;
	all_unallot_count	:any;
	all_unallot_count_hight_school	:any;
	all_unallot_count_Y	:any;
	all_uncall_count	:any;
	by_hand_all_uncall_count	:any;
	tmk_unallot_count	:any;
}

/*

tofile: 
	 mkdir -p ../table_manage; vi  ../table_manage/query.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/table_manage-query.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			db_name:	$('#id_db_name').val(),
			sql:	$('#id_sql').val()
        });
    }


	$('#id_db_name').val(g_args.db_name);
	$('#id_sql').val(g_args.sql);


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
                <span class="input-group-addon">sql</span>
                <input class="opt-change form-control" id="id_sql" />
            </div>
        </div>
*/
