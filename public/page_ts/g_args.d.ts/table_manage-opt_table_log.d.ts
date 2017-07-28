interface GargsStatic {
	start_time:	string;
	end_time:	string;
	adminid:	number;
	sql_str:	string;
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
	id	:any;
	opt_time	:any;
	adminid	:any;
	sql_str	:any;
	change_count	:any;
	admin_nick	:any;
}

/*

tofile: 
	 mkdir -p ../table_manage; vi  ../table_manage/opt_table_log.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/table_manage-opt_table_log.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
			adminid:	$('#id_adminid').val(),
			sql_str:	$('#id_sql_str').val()
        });
    }


	$('#id_start_time').val(g_args.start_time);
	$('#id_end_time').val(g_args.end_time);
	$('#id_adminid').val(g_args.adminid);
	$('#id_sql_str').val(g_args.sql_str);


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
                <span class="input-group-addon">adminid</span>
                <input class="opt-change form-control" id="id_adminid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">sql_str</span>
                <input class="opt-change form-control" id="id_sql_str" />
            </div>
        </div>
*/
